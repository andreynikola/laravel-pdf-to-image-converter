@extends('template')

@section('content')

<div class="container">

	<div class="row">

		<div class="col-md-12">	

		    <h1 class="mt-5">Готово!</h1>
		    <p class="lead text-muted">Мы конвертировали все страницы Вашего PDF в JPG файлы! Они перечислены ниже и готовы к скачиванию.
		    </p>

			<div class="owl-carousel owl-theme">
			@foreach ($images as $image)
			    <div><img src="{{ $image }}" alt=""> </div>
			@endforeach
			</div>

			<a href="/storage/images/{{ $id }}/archive.zip">Скачать архив</a>

		</div>

	</div>

</div>

<script>
$(document).ready(function(){
  $(".owl-carousel").owlCarousel({
    loop:false,
    margin:10,
    nav:true,
    items: 5
  });
});
</script>

@endsection