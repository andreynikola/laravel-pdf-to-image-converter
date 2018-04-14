@section('head')
<? //include_once $_SERVER['DOCUMENT_ROOT'].'/assets/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="csrf_token" content="{{ csrf_token() }}" />

		<link rel="shortcut icon" href="{{ asset('/public/favicon.ico') }}">

		<!-- Jquery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Notify -->
		<script src="{{ asset('/assets/notify.min.js') }}"></script>

		<!-- Bootstrap -->
		<link href="{{ asset('/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
		<script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}"></script>

		<!-- Ionic icons -->
		<link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

		<!-- Owl carousel -->
		<link rel="stylesheet" href="{{ asset('/assets/owlcarousel/assets/owl.carousel.css') }}">
		<link rel="stylesheet" href="{{ asset('/assets/owlcarousel/assets/owl.theme.default.css') }}">
		<script src="{{ asset('/assets/owlcarousel/owl.carousel.min.js') }}"></script>

		<link rel="stylesheet" href="{{ asset('/css/custom.css') }}">

		<title>Конвертировать PDF в IMAGE</title>
	</head>
@show

@yield('content')