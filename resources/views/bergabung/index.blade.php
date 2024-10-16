<!DOCTYPE html>
<html lang="en">
<head>
	<title>ARUTEKNOLOGI | Teknologi Digital</title>
    <link rel="icon" href="/images/logo2.png" type="image/x-icon" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700,800,900&display=swap"rel="stylesheet">
	<link rel="stylesheet" href="/css/animate.css">
	<link rel="stylesheet" href="/css/icomoon.css">
	<link rel="stylesheet" href="/css/ionicons.min.css">
	<link rel="stylesheet" href="/css/style.css">	
	<link rel="stylesheet" href="/css/flaticon.css">
    <style>
            canvas {
        background-color: #000;
        }
  
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white; 
        }
          #container-login {
            position: relative;
            top: -50%;
            vertical-align: baseline;
            /* right: 15%; */
            margin: auto;
            width: 40%;
            height: 0px;
            text-align: center;
            /* flex-direction: row; */
            /* z-index: 2; */
        }
    </style>
    
  </head>
  <body>
    <canvas id="nokey" width="800" height="800">
        Your Browser Don't Support Canvas, Please Download Chrome ^_^``
    </canvas>

    <div id="container-login" class="row justify-content-center">
        <div class="col-12 col-md-4 mb-2"> <!-- Mengubah col-6 menjadi col-12 untuk ponsel -->
            <a href="#whyJoin" class="btn btn-primary w-100">Mengapa Bergabung?</a>
         </div>
        <div class="col-12 col-md-4 mb-2"> <!-- Mengubah col-6 menjadi col-12 untuk ponsel -->
            <a href="https://wa.me/6282258280517" class="btn btn-primary w-100">Bergabung Sekarang!</a>
        </div>
    </div>

        <script>
            var canvas = document.getElementById('nokey'),
                can_w = parseInt(canvas.getAttribute('width')),
                can_h = parseInt(canvas.getAttribute('height')),
                ctx = canvas.getContext('2d');
            // console.log(typeof can_w);
            var BALL_NUM = 50
    
            var ball = {
                x: 0,
                y: 0,
                vx: 0,
                vy: 0,
                r: 0,
                alpha: 1,
                phase: 0
            },
                ball_color = {
                    r: 207,
                    g: 255,
                    b: 4
                },
                R = 2,
                balls = [],
                alpha_f = 0.03,
                alpha_phase = 0,
    
                // Line
                link_line_width = 0.8,
                dis_limit = 260,
                add_mouse_point = true,
                mouse_in = false,
                mouse_ball = {
                    x: 0,
                    y: 0,
                    vx: 0,
                    vy: 0,
                    r: 0,
                    type: 'mouse'
                };
    
            // Random speed
            function getRandomSpeed(pos) {
                var min = -1,
                    max = 1;
                switch (pos) {
                    case 'top':
                        return [randomNumFrom(min, max), randomNumFrom(0.1, max)];
                        break;
                    case 'right':
                        return [randomNumFrom(min, -0.1), randomNumFrom(min, max)];
                        break;
                    case 'bottom':
                        return [randomNumFrom(min, max), randomNumFrom(min, -0.1)];
                        break;
                    case 'left':
                        return [randomNumFrom(0.1, max), randomNumFrom(min, max)];
                        break;
                    default:
                        return;
                        break;
                }
            }
            function randomArrayItem(arr) {
                return arr[Math.floor(Math.random() * arr.length)];
            }
            function randomNumFrom(min, max) {
                return Math.random() * (max - min) + min;
            }
            console.log(randomNumFrom(0, 10));
            // Random Ball
            function getRandomBall() {
                var pos = randomArrayItem(['top', 'right', 'bottom', 'left']);
                switch (pos) {
                    case 'top':
                        return {
                            x: randomSidePos(can_w),
                            y: -R,
                            vx: getRandomSpeed('top')[0],
                            vy: getRandomSpeed('top')[1],
                            r: R,
                            alpha: 1,
                            phase: randomNumFrom(0, 10)
                        }
                        break;
                    case 'right':
                        return {
                            x: can_w + R,
                            y: randomSidePos(can_h),
                            vx: getRandomSpeed('right')[0],
                            vy: getRandomSpeed('right')[1],
                            r: R,
                            alpha: 1,
                            phase: randomNumFrom(0, 10)
                        }
                        break;
                    case 'bottom':
                        return {
                            x: randomSidePos(can_w),
                            y: can_h + R,
                            vx: getRandomSpeed('bottom')[0],
                            vy: getRandomSpeed('bottom')[1],
                            r: R,
                            alpha: 1,
                            phase: randomNumFrom(0, 10)
                        }
                        break;
                    case 'left':
                        return {
                            x: -R,
                            y: randomSidePos(can_h),
                            vx: getRandomSpeed('left')[0],
                            vy: getRandomSpeed('left')[1],
                            r: R,
                            alpha: 1,
                            phase: randomNumFrom(0, 10)
                        }
                        break;
                }
            }
            function randomSidePos(length) {
                return Math.ceil(Math.random() * length);
            }
    
            // Draw Ball
            function renderBalls() {
                Array.prototype.forEach.call(balls, function (b) {
                    if (!b.hasOwnProperty('type')) {
                        ctx.fillStyle = 'rgba(' + ball_color.r + ',' + ball_color.g + ',' + ball_color.b + ',' + b.alpha + ')';
                        ctx.beginPath();
                        ctx.arc(b.x, b.y, R, 0, Math.PI * 2, true);
                        ctx.closePath();
                        ctx.fill();
                    }
                });
            }
    
            // Update balls
            function updateBalls() {
                var new_balls = [];
                Array.prototype.forEach.call(balls, function (b) {
                    b.x += b.vx;
                    b.y += b.vy;
    
                    if (b.x > -(50) && b.x < (can_w + 50) && b.y > -(50) && b.y < (can_h + 50)) {
                        new_balls.push(b);
                    }
    
                    // alpha change
                    b.phase += alpha_f;
                    b.alpha = Math.abs(Math.cos(b.phase));
                    // console.log(b.alpha);
                });
    
                balls = new_balls.slice(0);
            }
    
            // loop alpha
            function loopAlphaInf() {
    
            }
    
            // Draw lines
            function renderLines() {
                var fraction, alpha;
                for (var i = 0; i < balls.length; i++) {
                    for (var j = i + 1; j < balls.length; j++) {
    
                        fraction = getDisOf(balls[i], balls[j]) / dis_limit;
    
                        if (fraction < 1) {
                            alpha = (1 - fraction).toString();
    
                            ctx.strokeStyle = 'rgba(150,150,150,' + alpha + ')';
                            ctx.lineWidth = link_line_width;
    
                            ctx.beginPath();
                            ctx.moveTo(balls[i].x, balls[i].y);
                            ctx.lineTo(balls[j].x, balls[j].y);
                            ctx.stroke();
                            ctx.closePath();
                        }
                    }
                }
            }
    
            // calculate distance between two points
            function getDisOf(b1, b2) {
                var delta_x = Math.abs(b1.x - b2.x),
                    delta_y = Math.abs(b1.y - b2.y);
    
                return Math.sqrt(delta_x * delta_x + delta_y * delta_y);
            }
    
            // add balls if there a little balls
            function addBallIfy() {
                if (balls.length < BALL_NUM) {
                    balls.push(getRandomBall());
                }
            }
    
            // Render
            function render() {
                ctx.clearRect(0, 0, can_w, can_h);
    
                renderBalls();
    
                renderLines();
    
                updateBalls();
    
                addBallIfy();
    
                window.requestAnimationFrame(render);
            }
    
            // Init Balls
            function initBalls(num) {
                for (var i = 1; i <= num; i++) {
                    balls.push({
                        x: randomSidePos(can_w),
                        y: randomSidePos(can_h),
                        vx: getRandomSpeed('top')[0],
                        vy: getRandomSpeed('top')[1],
                        r: R,
                        alpha: 1,
                        phase: randomNumFrom(0, 10)
                    });
                }
            }
            // Init Canvas
            function initCanvas() {
                canvas.setAttribute('width', window.innerWidth);
                canvas.setAttribute('height', window.innerHeight);
    
                can_w = parseInt(canvas.getAttribute('width'));
                can_h = parseInt(canvas.getAttribute('height'));
            }
            window.addEventListener('resize', function (e) {
                console.log('Window Resize...');
                initCanvas();
            });
    
            function goMovie() {
                initCanvas();
                initBalls(BALL_NUM);
                window.requestAnimationFrame(render);
            }
            goMovie();
    
            // Mouse effect
            canvas.addEventListener('mouseenter', function () {
                console.log('mouseenter');
                mouse_in = true;
                balls.push(mouse_ball);
            });
            canvas.addEventListener('mouseleave', function () {
                console.log('mouseleave');
                mouse_in = false;
                var new_balls = [];
                Array.prototype.forEach.call(balls, function (b) {
                    if (!b.hasOwnProperty('type')) {
                        new_balls.push(b);
                    }
                });
                balls = new_balls.slice(0);
            });
            canvas.addEventListener('mousemove', function (e) {
                var e = e || window.event;
                mouse_ball.x = e.pageX;
                mouse_ball.y = e.pageY;
                // console.log(mouse_ball);
            });
        </script>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-light ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.html"><img src="/images/logo1.png" width="150px" height="50px"> </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
          aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>
  
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="/" class="nav-link">Beranda</a></li>
				<li class="nav-item"><a href="/tentang" class="nav-link">Tentang</a></li>
				<li class="nav-item"><a href="/kontak" class="nav-link">Kontak</a></li>
				<li class="nav-item"><a href="/bergabung" class="nav-link">Bergabung</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- END nav -->
    <div class="col-lg-6 col-md-6 ftco-animate" data-scrollax=" properties: { translateY: '70%' }" id="whyJoin">
      <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
        <br><span style="color: #000086;font-weight:bold">Mengapa Harus Bergabung ?</span></h1>
      </div>
		<section class="ftco-section">
      
      <div class="container">
        <div class="row d-flex">
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry justify-content-end">
              <a class="block-20"
                style="background-image: url(images/digital.gif);">
              </a>
              <div class="text pt-4">
                <h3 class="heading mt-2"><a href="#">Transformasi Digital yang Cepat</a></h3>
                <p>Jika bisnis Anda ingin bertransformasi secara digital tetapi tidak memiliki keahlian internal untuk mengelola infrastruktur cloud, ARU TEKNOLOGI dapat menyediakan solusi yang tepat. Kami membantu Anda mengadopsi teknologi terkini dengan lancar, sehingga Anda dapat tetap bersaing di pasar yang cepat berubah.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry justify-content-end">
              <a class="block-20"
                style="background-image: url(images/pemeliharaan.gif);">
              </a>
              <div class="text pt-4">
                <div class="meta mb-3">
                </div>
                <h3 class="heading mt-2"><a href="#">Pemeliharaan Sistem yang Efisien</a></h3>
                <p>Ketika bisnis Anda mulai tumbuh, mengelola pemeliharaan sistem dan aplikasi bisa menjadi beban. Bergabung dengan ARU TEKNOLOGI memungkinkan Anda untuk mengalihkan fokus dari aspek teknis ke operasi inti bisnis. Kami menawarkan layanan pemeliharaan menyeluruh, memastikan sistem Anda selalu optimal tanpa harus mengalokasikan sumber daya tambahan.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry">
              <a class="block-20"
                style="background-image: url('images/keamanan.gif');">
              </a>
              <div class="text pt-4">
                <div class="meta mb-3">
                </div>
                <h3 class="heading mt-2"><a href="#">Keamanan dan Skalabilitas</a></h3>
                <p>Dalam era di mana keamanan data sangat penting, ARU TEKNOLOGI menyediakan solusi cloud yang aman dan scalable. Jika bisnis Anda khawatir tentang risiko keamanan atau kesulitan dalam menyesuaikan kapasitas sesuai permintaan, kami dapat memberikan infrastruktur yang aman dan fleksibel, memungkinkan Anda untuk tumbuh tanpa kekhawatiran akan keamanan dan ketersediaan sistem.</p>
              </div>
            </div>
          </div>
      </div>
    </section>
		

    <footer class="ftco-footer ftco-bg-dark ftco-section">
		<div class="container">
			<div class="row mb-5">
				<div class="col-md">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">ARU Teknologi</h2>
						<p>ARUTEKNOLOGI: Pelopor solusi cloud, kami memberdayakan bisnis untuk beradaptasi di era digital.</p>
						<ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
						<li class="ftco-animate"><a href="https://www.linkedin.com/in/aru-teknologi-218a68330"><img src="/images/linkedin.png" alt="LinkedIn" width="45" height="45" class="icon-image"></a></li>
							<li class="ftco-animate"><a href="https://www.facebook.com/profile.php?id=61566398174741"><img src="/images/facebook.png" alt="LinkedIn" width="45" height="45" class="icon-image"></a></li>
							<li class="ftco-animate"><a href="https://www.instagram.com/aruteknologi/"><img src="/images/instagram.png" alt="LinkedIn" width="45" height="45" class="icon-image"></a></li>
						</ul>
					</div>
				</div>
				<div class="col-md">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">References</h2>
						<ul class="list-unstyled">
							<!-- <li><a href="speakers.html" class="py-2 d-block">Speakers</a></li> -->
							<li><a href="https://www.aruteknologi.com/" class="py-2 d-block">aruteknologi.com</a></li>

						</ul>
					</div>
				</div>
				
				<div class="col-md">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">Contact Us</h2>
						<div class="block-23 mb-3">
							<ul>
							<li><img src="/images/placeholder.png" width="25" height="25" class="icon-image" style="margin-right: 18px;"><span class="text">Jakarta, Indonesia</span></li>
							<li><img src="/images/whatsapp.png" width="20" height="20" class="icon-image" style="margin-right: 22px;"><span class="text">+62 822-5828-0517</span></li>
                            <li><img src="/images/email.png" width="22" height="22" class="icon-image" style="margin-right: 22px;"><span class="text">aruteknologi@gmail.com</span></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">

					<p>
						Copyright &copy;
						<script>document.write(new Date().getFullYear());</script> All rights reserved </a>
					</p>
				</div>
			</div>
		</div>
	</footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>