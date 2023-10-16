<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<title><?php echo $appsProfile->apps_name; ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() . $appsProfile->icon; ?>">
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link rel="manifest" href="<?php echo base_url('/manifest.json'); ?>">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="<?php echo base_url('assets/'); ?>plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/animate.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/login.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/jquery-ui.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/uniform.default.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/loginv2.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/login.anim.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/gradient.css" rel="stylesheet" media='all' />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>js/main.js"></script>
	<!-- ================== END BASE JS ================== -->
	<script>
	if ('serviceWorker' in navigator) {
		console.log('SERVICE WORKER -> REGISTER -> Try to register the service worker');
		navigator.serviceWorker.register('<?php echo base_url(); ?>service-worker.js')
		.then(function(reg) {
			console.log('SERVICE WORKER -> REGISTER -> Successfully registered!');
		}).catch(function(err) {
			console.log("'SERVICE WORKER -> REGISTER -> Registration failed! This happened: ", err)
		});
	}
	</script>
	<style type="text/css">
		.login-page .form-box .univ-identity-box {
			background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('<?php echo base_url() . $appsProfile->sidebar_login; ?>') bottom;
			background-size: cover;
		}
	</style>
	<style type="text/css">
		.password {
			position: relative;
		}

		.showbtn {
			cursor: pointer;
			overflow: hidden;
			right: 15px;
			position: absolute;
			top: 10px;
			cursor: pointer;
		}
	</style>
</head>

<body class="login-page" style="background:url('<?php echo base_url('assets/img/pat_04.png'); ?>') repeat;">
	<div class="container">
		<div class="row">
			<div class="form-box col-md-8 col-sm-10 col-xs-12">
				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 form-login" align="center">
					<img src="<?php echo base_url() . $appsProfile->icon; ?>" class="logo"><br />
					<h2 align="center" class="text-grey text-light"><?php echo $appsProfile->apps_name;?></h2><br />
					<h2 align="center" class="text-grey text-light">Silakan Login</h2><br />
					<form method="post" action="<?php echo base_url('auth/login/do_login'); ?>">
						<?php echo $this->session->flashdata('notif'); ?>
						<div class="form-group">
							<i class="fa fa-user icon-input"></i> <input type="text" name="username" id="userid" class="form-control input-line" placeholder="Masukkan Username / Email" required="true" />
						</div>
						<div class="form-group">
							<div class="password">
								<i style="margin-left:-20px;" class="fa fa-key icon-input"></i>
								<input type="password" id="password" name="password" class="form-control input-line" placeholder="Masukkan Kata Sandi / Password" required="true" />
								<span id="iconshow" name="iconshow" onClick="showPass()" class=" showbtn fa fa-eye-slash"></span>
							</div>
						</div>
						<br />
						<div class="form-group" align="center">
							<button type="submit" class="btn btn-flat blue-rasp text-white btn-block">Masuk Aplikasi <i class="fa fa-angle-right"></i></button>
							<hr>
							<!-- <a href="https://play.google.com/store/apps/details?id=com.indonesiastudenttrainingcenter">
								<img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png" class="img-responsive" alt="">
							</a> -->
							<small><?php echo $appsProfile->footer; ?></small>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- ================== BEGIN BASE JS ================== -->
	<!--[if lt IE 9]>
		<script src="<?php echo base_url('assets/'); ?>crossbrowserjs/html5shiv.js"></script>
		<script src="<?php echo base_url('assets/'); ?>crossbrowserjs/respond.min.js"></script>
		<script src="<?php echo base_url('assets/'); ?>crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo base_url('assets/'); ?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo base_url('assets/'); ?>js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
	<script type="text/javascript">
		function showPass() {
			if (document.getElementById("password").type == 'password') {
				document.getElementById("password").type = 'text';
				document.getElementById("iconshow").classList.remove('fa-eye-slash');
				document.getElementById("iconshow").classList.add('fa-eye');
			} else {
				document.getElementById("password").type = 'password';
				document.getElementById("iconshow").classList.remove('fa-eye');
				document.getElementById("iconshow").classList.add('fa-eye-slash');
			}
		}
	</script>
	<?php $this->load->view('modalLoading');?>
	<script>
		$('#btnLogin').click(function(){
			$('#modalLoading').modal('show')
   		    $('#modalLoading').modal({backdrop: 'static', keyboard: false})
		})
	</script>
</body>
</html>