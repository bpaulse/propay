<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{ asset('sweetalert2/sweetalert2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

	<style>
		.tab-pane {
			padding:2rem 0;
		}

		.nav-link {
			background: linear-gradient(180deg, rgba(255,255,255,1) 0%, rgba(3,3,37,0.1) 100%);
		}

		.nav-link.active {
			font-weight: bold;
			background:#fff;
		}
	</style>

	<title>Results</title>
</head>
<body>

	<div class="container">

		<div class="w-100 text-right p-3"><button class="btn btn-info text-right backWodList">Back</button></div>
	
		<h2>Leader Boards</h2>
		<p>To make the tabs toggleable, add the data-toggle="tab" attribute to each link. Then add a .tab-pane class with a unique ID for every tab and wrap them inside a div element with class .tab-content.</p>
  
		<ul class="nav nav-tabs" id="myTab" role="tablist"></ul>
		<div class="tab-content" id="myTabContent"></div>

	</div>

	<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>

	<script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
	<script src="{{ asset('toastr/toastr.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('js/wodresults.js') }}"></script>

</body>
</html>
