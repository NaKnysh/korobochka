<?php 
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'guest';
?>
	<!--==================================================-->
	<!-- start Header Top Menu Area-->
	<!--==================================================-->
	<div class="header-top-menu">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="header-top-address">
						<ul>
							<li><a href="#"><i class="far fa-envelope"></i> korobochka@gmail.com</a></li>
							<li><span><i class="fas fa-map"></i> Rivne, Ukraine</span></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="hrader-top-social text-right">
						<a href="#"><i class="fab fa-facebook-f"></i></a>
						<a href="#"><i class="fab fa-twitter"></i></a>
						<a href="#"><i class="fab fa-dribbble"></i></a>
						<a href="#"><i class="fab fa-instagram"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
