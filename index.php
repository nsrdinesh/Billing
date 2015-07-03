
<!DOCTYPE html>
<html>
<head>
	<title> Online Billing System</title>
	<!--fonts-->
		<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css'>
	<!--//fonts-->
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
	<!-- for-mobile-apps -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="Crazy Fashions UI Kit Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
		Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- //for-mobile-apps -->
	<!-- js -->
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	<!-- js -->
	<!-- script for close -->
	<script>
		$(document).ready(function(c) {
			$('.alert-close').on('click', function(c){
				$('.vlcone').fadeOut('slow', function(c){
					$('.vlcone').remove();
				});
			});	  
		});
	</script>
	<script>$(document).ready(function(c) {
			$('.alert-close1').on('click', function(c){
				$('.vlctwo').fadeOut('slow', function(c){
					$('.vlctwo').remove();
				});
			});	  
		});
	</script>
	<!-- //script for close -->
<!-- script for etalage -->
<!-- FlexSlider -->
  <script defer src="js/jquery.flexslider.js"></script>
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

	<script>
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    controlNav: "thumbnails"
  });
});
</script>

<!-- //script for etalage -->
</head>
<body>
<!-- content -->
<div class="content">
	<div class="container">
		<div class="nav-top-header">
			<h1><a href="#">Online Billing System </a></h1>
		</div>
		<div class="navigation text-center">
				<!-- start h_menu4 -->
				<div class="h_menu4">
					<a class="toggleMenu" href="">Menu</a>
					<ul class="nav">
						
						<li><a href="addcustomer.php">Add Customer</a>
						</li>
						<li><a href="#">View Summary</a>
						</li>
						<li><a href="excel.php">Upload</a>
						</li>
						<li><a href="generate.php">Reports</a></li>
						<li><a href="#">CONTACT US</a></li>
					</ul>
						<script type="text/javascript" src="js/nav.js"></script>
				</div>
				<!-- end h_menu4 -->
		</div>
		<div class="clearfix"></div>
	</div>
</div>	
	
<!-- //content -->
</body>
</html>