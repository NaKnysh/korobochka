<?php
	session_start();
	
	
	include 'db_connect.php';

	// Ваш код для роботи з базою даних
	$result = mysqli_query($connection, "SELECT * FROM user");
	
	require_once('items.php');
	
	while ($row = mysqli_fetch_assoc($result)) {
		echo "ID: " . $row['id'] . " - Name: " . $row['name'] . "<br>";
	}
	
	
	
	function generateBox($conn, $boxtype_id) {
		// Отримання ціни та можливих елементів для даного boxtype_id
		$stmt = $conn->prepare("SELECT price, possibleItemsId FROM boxtype WHERE id = ?");
		$stmt->bind_param("i", $boxtype_id);
		$stmt->execute();
		$stmt->bind_result($price, $possible_items);
		$stmt->fetch();
		$stmt->close();

		// Ініціалізація змінних
		$total_value = 0;
		$itemList = array();
		$possible_items_array = explode(",", $possible_items);

		// Отримання можливих елементів з таблиці item
		$placeholders = implode(',', array_fill(0, count($possible_items_array), '?'));
		$types = str_repeat('i', count($possible_items_array));
		$stmt = $conn->prepare("SELECT id, value, quantityInStock FROM item WHERE id IN ($placeholders) AND quantityInStock > 0 ORDER BY RAND()");
		$stmt->bind_param($types, ...$possible_items_array);
		$stmt->execute();
		$result = $stmt->get_result();

		// Вибір елементів до тих пір, поки їхня загальна вартість не перевищить price
		while ($row = $result->fetch_assoc()) {
			if ($total_value + $row['value'] <= $price) {
				$total_value += $row['value'];
				$itemList[] = $row['id'];

				// Зменшення кількості товару в наявності
				$update_stmt = $conn->prepare("UPDATE item SET quantityInStock = quantityInStock - 1 WHERE id = ?");
				$update_stmt->bind_param("i", $row['id']);
				$update_stmt->execute();
				$update_stmt->close();
			}
		}
		$stmt->close();

		// Створення нового запису в таблиці box
		$itemListId = implode(",", $itemList);
		$stmt = $conn->prepare("INSERT INTO box (typeId, itemListId) VALUES (?, ?)");
		$stmt->bind_param("is", $boxtype_id, $itemListId);
		$stmt->execute();
		$stmt->close();
	}
	
	generateBox($connection, 1);

	// Закриття з'єднання
	mysqli_close($connection);
	include 'extra/header.php';
	
?>
<!DOCTYPE HTML>
<html lang="en-US">

<body>
<?php include 'extra/headerlogo.php'; ?>

	<div class="header-area" id="sticky-header">
	   <div class="container">
	   		<div class="m-logo">
				<a href="#"><span class="logo-txt"></span></a>
			</div>
			<div class="menu-toggle"><i class="fas fa-bars"></i></div>
			<div class="menu-wrapper">
				<div class="row align-items-center d-flex">
					<div class="col-lg-3">
						<div class="header-container">
							<div class="header-logo">
								<a class="main-logo" href="index.html"><img src="assets/images/logo1.png" alt=""></a>
								<a class="stiky-logo" href="index.html"><img src="assets/images/logo2.png" alt=""></a>
							</div>
							<div class="header-name">
								<h3>korobochka</h3>
							</div>
						</div>
					</div>
					
					<div class="col-lg-9">
						<div class="header-menu">
							<ul>
								<li><a href="about-style-one.html">about <span class="mobile-menu-icon"><i class="fas fa-angle-right"></i></span></a></li>
								<li><a href="service-style-one.html">service <span class="mobile-menu-icon"><i class="fas fa-angle-right"></i></span></a></li>
								
								<li><a href="#">boxes<span> <i class="fas fa-angle-down"></i></span><span class="mobile-menu-icon"><i class="fas fa-angle-right"></i></span></a>
									<div class="sub-menu">
										<ul>
											<li><a href="portfolio.html">portfolio 3column</a></li>
											<li><a href="portfolio-2column.html">portfolio 2column</a></li>
											<li><a href="portfolio-4column.html">portfolio 4column</a></li>
										</ul>
									</div>
								</li>
								<li><a href="#">news<span><i class="fas fa-angle-down"></i></span><span class="mobile-menu-icon"><i class="fas fa-angle-right"></i></span></a>
									<div class="sub-menu">
										<ul>
											<li><a href="blog-gird.html">blog-grid </a></li>
											<li><a href="blog-list.html">blog-list</a></li>
											<li><a href="Blog-2Column.html">blog-2column</a></li>
										</ul>
									</div>
								</li>
							</ul>
							<div class="header-button">
								<a onclick="return get_cart()" href="#">Корзина <span id="items_in_cart"></span></a>
								<div id="cart_preview"></div>
							</div>
							<div class="header-user">
								<i class="fas fa-user"></i>
								<span id="user_name"><?php echo $user_name; ?></span>
							</div>
							<div class="mobile-menu-social-icon d-lg-none">
								<a href="#"><i class="fab fa-facebook-f"></i></a>
								<a href="#"><i class="fas fa-map-marker-alt"></i></a>
								<a href="#"><i class="fab fa-twitter"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--==================================================-->
	<!--Start korobochka Slider Area-->
	<!--==================================================-->
	<div class="slider_list owl-carousel">	
		<div class="slider-area d-flex align-items-center">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="slider-content ">
							<div class="wow fadeInUp" data-wow-delay="1s" data-wow-duration="1s">
								<h4>60% нашого заробітку йде на Збройні Сили України</h4>
							</div>
							<div class="wow fadeInUp" data-wow-delay="1.5s" data-wow-duration="1.5s">
								<h1>Спробуйте нашу коробку-сюрприз</h1>
								<h2>Лише 99.99</h2>
							</div>
							<div class="wow fadeInUp" data-wow-delay="1.6s" data-wow-duration="2s">
								<p>Речі, які можуть бути включені: насіння соняшника, банка чорнозему, футболка з українськими принтами, чашка з українськими принтами, книга з національними українськими стравами, тощо.</p>
							</div>
							<div class="wow fadeInUp" data-wow-delay="1.7s" data-wow-duration="2.5s">
								<div class="slider-button" id="centered_by_dima">
									<a href="#">дивитися більше <span class="flaticon-right-arrow-2"></span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="slider-area style-two d-flex align-items-center">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="slider-content ">
							<h4>#1 -Welcome to investon company</h4>
							<h1>Intelligent Plan For</h1>
							<h2>Your Business</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut laboret dolore magna aliqua. Ut en ad minim  ullamco laboris nisi ut aliquip.</p>
							<div class="slider-button pt-20">
								<a href="#">check price <span class="flaticon-right-arrow-2"></span></a>
							</div>
							<div class="bd-video">
								<div class="slider-video-icon">
									<div class="slider-video-icon">
										<a class="video-vemo-icon venobox vbox-item" data-vbtype="youtube" data-autoplay="true" href="https://youtu.be/BS4TUd7FJSg"><i class="far fa-play-circle"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div class="about-area pt-70 pb-60">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="korobochka-about-thumb">
						<img src="assets/images/about.png" alt="">
					</div>
				</div>
				<div class="col-lg-6">
					<div class="korobochka-section-title pb-30">
						<div class="korobochka-section-sub-title">
							<h5>Про нас</h5>
						</div>
						<div class="korobochka-section-main-title">
							<h1>Цей сайт, орієнтований на закордонну публіку для залучення донатів та благодійності</h1>
							<h1>Сподіваємося на вашу допомогу</h1>
						</div>
						<div class="korobochka-section-text mt-30">
							<p>Дипломний проєкт з використанням web3 технологій і блокчейну near для проведення транзакцій оплати та синхронізації свого гаманця з аккаунтом на сайті</p>
						</div>
						</div>
						<div class="row">
						<div class="about-tabs">
							<div id="tabs">
								<ul class="tab-items">
									<li><a href="#history">Фонди, яким ми будемо жертвувати</a></li>
									<li><a href="#mission">Детальніше</a></li>
								</ul>
								<div id="history">
									<div class="tabs-content-container">
										<p>Коробочка працює за системою лутбоксів, де ви гарантовано отримуєте деякі предмети з пулу предметів, описаного в описі коробки. Всі речі вибираються випадково і завжди мають в загальну суму товарів в вартості покупки!</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<!--==================================================-->
	<!--Start korobochka service Area-->
	<!--==================================================-->
	<div class="service-area pt-80 pb-70">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="korobochka-section-title style-two ">
						<div class="korobochka-section-main-title">
							<h2>Наші пропозиції</h2>
						</div>
						<div class="korobochka-section-bold-text pt-10">
							<p>Виберіть те, що привернуло вашу увагу</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-40">
			
			<?php 
				foreach($items as $item) {
			?>
				<div class="col-lg-4 col-md-6">
					<div  id="box<?=$item['id']?>" class="korobochka-single-service-box one">
						<div class="service-content">
							<div class="service-title">
								<h3><?=$item['title']?></h3>
							</div>
							<div class="service-content-text">
								<p><?=$item['text']?></p>
							</div>
							<br>
							<div class="header-user">
								<div class="header-button">
									<a href="#" onclick="return add_to_cart(<?=$item['id']?>);">+ кошик</a>
								</div>
								<div class="service-button">
									<a href="box.php?id=<?=$item['id']?>">Дізнатися більше <span class="flaticon-right-arrow-2"></span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
				}
			?>
			</div>
		</div>
	</div>
	<!--==================================================-->
	<!--Start counter Area-->
	<!--==================================================-->
	<div class="counter-area pt-85 pb-70">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="korobochka-section-title style-two pb-30">
						<div class="korobochka-section-main-title">
							<h2 class="text-white">Статистика </h2>
						</div>
						<div class="korobochka-section-bold-text pt-10">
							<p class="text-white">З кожною покупкою ми стаємо на крок ближчі до перемоги.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-25 d-flex justify-content-center">
				<div class="col-lg-3 col-md-6">
					<div class="single-counter-box">
						<div class="dreamir-counter-icon">
							<i class="fas fa-tablet-alt"></i>
						</div>
						<div class="counter-content">
							<div class="counter-number">
								<h1><span class="counter">0</span> <span>+</span></h1>
							</div>
							<div class="counter-title">
								<h4 class="text-white">Внесено</h4>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="single-counter-box">
						<div class="dreamir-counter-icon">
							<i class="fab fa-mixcloud"></i>
						</div>
						<div class="counter-content">
							<div class="counter-number">
								<h1><span class="counter">0</span><span>+</span></h1>
							</div>
							<div class="counter-title">
								<h4 class="text-white">Продано коробок</h4>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="single-counter-box">
						<div class="dreamir-counter-icon">
							<i class="fab fa-behance"></i>
						</div>
						<div class="counter-content">
							<div class="counter-number">
								<h1><span class="counter">0</span><span>+</span></h1>
							</div>
							<div class="counter-title">
								<h4 class="text-white">Відвідувачів</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--==================================================-->
	<!--Start testimonial Area-->
	<!--==================================================-->
	<div class="testimonial-area pt-80 pb-135">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="korobochka-section-title style-two pb-30">
						<div class="korobochka-section-main-title">
							<h2 class="text-black">Відгуки</h2>
						</div>
						<div class="korobochka-section-bold-text pt-10">
							<p>Нам важливо почути вашу думку! Якщо ви чимось не задоволені, або все було чудово, будь ласка, скажіть нам, щоб ми могли ставати краще з кожним днем.</p>
						</div>
					</div>
				</div>
				<form>
					<input class = "text1" type="text">
					<br>
					<div class="header-button">
						<a href="#">Надіслати</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php include 'extra/footer.php';?>
	<!--==================================================-->
	<!--start fTo Top-->
	<!--===================================================-->
	<div class="scroll-area">
		<div class="top-wrap">
			<div class="go-top-btn-wraper">
				<div class="go-top go-top-button">
					<i class="fas fa-arrow-up"></i>
					<i class="fas fa-arrow-up"></i>
				</div>
			</div>
		</div>
	</div>
	<!-- jquery js -->	
	<script src="assets/js/vendor/jquery-3.2.1.min.js"></script>
	<!-- bootstrap js -->	
	<script src="assets/js/bootstrap.min.js"></script>
	<!-- carousel js -->
	<script src="assets/js/owl.carousel.min.js"></script>
	<!-- counterup js -->
	<script src="assets/js/jquery.counterup.min.js"></script>
	<!-- waypoints js -->
	<script src="assets/js/waypoints.min.js"></script>
	<!-- wow js -->
	<script src="assets/js/wow.js"></script>
	<!-- imagesloaded js -->
	<script src="assets/js/imagesloaded.pkgd.min.js"></script>
	<!-- venobox js -->
	<script src="venobox/venobox.js"></script>
	<!--  animated-text js -->	
	<script src="assets/js/animated-text.js"></script>
	<!-- venobox min js -->
	<script src="venobox/venobox.min.js"></script>
	<!-- isotope js -->
	<script src="assets/js/isotope.pkgd.min.js"></script>
	<!-- jquery nivo slider pack js -->
	<script  src="assets/js/jquery.nivo.slider.pack.js"></script>
	<!-- jquery meanmenu js -->	
	<script src="assets/js/jquery.meanmenu.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<!-- jquery scrollup js -->	
	<script src="assets/js/jquery.scrollUp.js"></script>
	<!-- theme js -->	
	<script src="assets/js/jquery.scrollUp.js"></script>
	<!-- Jquery UI js -->
	<script src="assets/js/jquery-ui.min.js"></script>
		<!-- jquery js -->	
	<script src="assets/js/main.js"></script>
		<script>
		 $(window).on('scroll', function () {
        var scrolled = $(window).scrollTop();
        if (scrolled > 300) $('.go-top').addClass('active');
        if (scrolled < 300) $('.go-top').removeClass('active');
    });

    $('.go-top').on('click', function () {
        $("html, body").animate({
            scrollTop: "0"
        }, 1200);
    });
	
	$(document).ready(function(){
		add_to_cart(0);
	});
	
	function get_cart() {
		
		if ( 'none' == $('#cart_preview').css('display') ) {
			$.ajax({
				url: '/ajax/get_cart.php',
				method: 'post',
				dataType: 'html',
				success: function(data){
					$('#cart_preview').html(data);
					$('#cart_preview').show();
				}
			});
		} else {
			$('#cart_preview').hide();
		}
		
		return false;
	}
	
	function add_to_cart(item_id) {
		$('#cart_preview').hide();
		$.ajax({
			url: '/ajax/add_to_cart.php',
			method: 'post',
			dataType: 'json',
			data: {id: item_id},
			success: function(data){
				if (data.items_in_cart) {
					$('#items_in_cart').text(data.items_in_cart);
				} else {
					$('#items_in_cart').text('');
				}
			},
			error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect. Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found (404).');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error (500).');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error. ' + jqXHR.responseText);
			}
			}
		});
		return false;
	}
	
	function del_from_cart(item_id) {
		$('#cart_preview').hide();
		$.ajax({
			url: '/ajax/del_from_cart.php',
			method: 'post',
			dataType: 'json',
			data: {id: item_id},
			success: function(data){
				if (data.items_in_cart) {
					$('#items_in_cart').text(data.items_in_cart);
				} else {
					$('#items_in_cart').text('');
				}
			}
		});
		return false;
	}
	</script>
</body>
</html>