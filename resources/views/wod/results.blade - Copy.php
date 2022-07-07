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
	
		<!-- <ul class="nav nav-tabs"></ul>
		<div class="tab-content"></div> -->
  
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<!-- <li class="nav-item">
				<a 
					class="nav-link active" 
					id="home-tab" 
					data-toggle="tab" 
					href="#home" 
					role="tab" 
					aria-controls="home" 
					aria-selected="true">
					Home
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
			</li> -->
		</ul>

		<div class="tab-content" id="myTabContent">
			<!-- 
			<div 
				class="tab-pane fade show active" 
				id="home" 
				role="tabpanel" 
				aria-labelledby="home-tab"
				>
				<h2>Home</h2>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum!</p>
			</div>
	
			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<h2>Profile</h2>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum!</p>
			</div>
			<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
				<h2>Contact</h2>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum!</p>
			</div> -->
		</div>

	</div>

	<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>

	<script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
	<script src="{{ asset('toastr/toastr.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('js/wodresults.js') }}"></script>

</body>
</html>
