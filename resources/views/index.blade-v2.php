@extends('template')

@section('content')

<?
// echo "<pre>";
// 	var_dump($_SESSION);
// echo "</pre>";
?>
<div class="alert alert-danger alert-dismissible fade collapse rounded-0 text-center" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <i aria-hidden="true" class="ion-close"></i>
  </button>
  <strong>Ошибка!</strong> <span>Необходимо выбрать хотя бы один PDF-файл.</span>
</div>
<section id="home">

	<div class="container">
		<div class="row">
			<div class="col-md-12 text-right mt-4 mb-5">
				<a href="#" class="text-dark">PDF to Image</a>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 text-center mt-5 mb-5">
				<h1 class="font-weight-light">Конвертировать PDF в JPG online</h1>
			</div>
		</div>

		<div class="row">
			
			<div class="col-md-12">
				<div class="progress invisible">
				  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success " 
				  role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				  <span></span>
				</div>
			</div>

			<div class="offset-md-4 col-md-4">
				<form id="upload-pdf" method="post" action="/pdf-to-image" enctype="multipart/form-data" >
					{{ csrf_field() }}
					<div class="form-group" hidden>
						<input type="file" name="file" >
					</div>

				    <div class="form-group">
						<button type="button" class="btn btn-custom rounded-0 w-100" id="upload-files">
							<i class="ion-document"></i>Upload PDF files
						</button>
				    </div>

					<input name="_token" type="hidden" value="{{ csrf_token() }}">

				</form>
			</div>

		</div>

	</div>

</section>

<script> 

// Загрузка файлов
$('#upload-files').click(function(){
	$('input[name="file"]').trigger('click');
})

// Валидация файлов на стороне клиента
$('input:file').change(function(event){

	var file = event.currentTarget.files[0];

	if ( file.size > 50000000 || file.type != 'application/pdf' ) {

		$('input:file').val('');
		$('#upload-files').removeClass('invisible');
		$('.progress').addClass('invisible');

		if (file.size > 50000000) {
			$('.alert span').html( 'Файл не может быть больше 50 мегабайт' );
		};
		if (file.type != 'application/pdf') {
			$('.alert span').html( 'Файл должен иметь расширение: pdf' );
		};

		$('.alert').collapse('show');

		return false;

	}else{

		$("form#upload-pdf").submit();

	};

})


$(function(){

	CSRF_TOKEN = $('meta[name="csrf_token"]').attr('content');

	$('#upload-pdf').on('submit', function(e){

		e.preventDefault(e);

		var formData = new FormData($(this).get(0));
		formData.append('action', 'load-file');
		var url = $(this).attr('action');
		var type = $(this).attr('method');

		// Загружаем файлы на сервер
		$.ajax({
			url: url,
			type: type,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
			contentType: false, // без него не грузятся файлы, а с ним не работает вложенный запрос
			processData: false,
			async: true,
			data: formData,
			// dataType: 'json',
			xhr: function(){

		        var xhr = new window.XMLHttpRequest();

				xhr.onloadstart = function(e) {
					$('#upload-files').addClass('invisible');
					$('.progress').removeClass('invisible');
					$(".progress-bar").data( "aria-valuenow", 0 );
					$(".progress-bar").css( "width", "0%" );
				};

		        xhr.upload.onprogress = function(evt) {
		            if (evt.lengthComputable) {
			            var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
			            $(".progress-bar").data( "aria-valuenow", percentComplete );
			            $(".progress-bar").css( "width", percentComplete + "%" );
			            $(".progress span").text( "Загрузка файлов: " + percentComplete + " %" );
		            }
		        };

				xhr.onloadend = function(e) {
			        $(".progress span").text( "Идет подготовка к конвертации файлов..." );
				};

				return xhr;

			},
			success: function(data){
				console.log(data);
			},
			error: function(data){
				var errors = data.responseJSON;

				$(".progress-bar").data( "aria-valuenow", 0 );
				$(".progress-bar").css( "width", "0%" );

				$('.alert span').html( errors['errors']['file'] );
				$('.alert').collapse('show');

				$('input:file').val('');

				$('#upload-files').removeClass('invisible');
				$('.progress').addClass('invisible');

			}
	    }).done(function(data){

	    	convert();

	    });

	});

});

function convert(){

	var errors;

	// Конвертация файлов
	$.ajax({
		url: '/pdf-to-image',
		type: 'post',
		processData: false,
		async: true,
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
		data: "action=convert",
	    success: function (data) {
	    	console.log(data);
	    },
		error: function(data){
			// errors = data.responseJSON;
			// console.log(errors['message']);
			// $(".progress-bar").data( "aria-valuenow", 0 );
			// $(".progress-bar").css( "width", "0%" );

			// $('.alert span').html( errors['message'] );
			// $('.alert').collapse('show');

			// $('input:file').val('');

			// $('#upload-files').removeClass('invisible');
			// $('.progress').addClass('invisible');
		}
	});

	// Если найдены ошибки в документе
	// if (typeof errors == 'string') {
	// 	return false;
	// }

	// Показ прогрессбара
	setTimeout(function(){
		$.ajax({
			url: '/pdf-to-image',
			type: 'post',
			processData: false,
			async: true,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
			data: "action=show_progress",
		    success: function (test) {
		    	// console.log(test);
		    },
			error: function(test){
				console.log(test.responseJSON);
			}
		});

		get_progress();
	},1000)

}

function get_progress(){

	$.ajax({
	    url: '/pdf-to-image',
	    type: 'POST',
		processData: false,
		async: true,
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
		data: "action=get_progress",
	    success: function (msg) {
            $(".progress-bar").data( "aria-valuenow", msg.progress );
            $(".progress-bar").css( "width", msg.progress + "%" );
            $(".progress span").text( "Конвертация файлов: " + msg.progress + " %" );

	    	if (msg.progress <= 100) {
	    		get_progress();
	    	}
	    },

		error: function(msg){
			console.log(msg.progress);
		}
	});

}


</script>

@endsection