<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<title><?php echo $apps_name; ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() . $icon; ?>">
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="<?php echo base_url('assets/'); ?>plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" media='all' />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/animate.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>css/style.min.css" rel="stylesheet" media='all' />
	<link href="<?php echo base_url('assets/'); ?>css/gradient.css" rel="stylesheet" media='all' />
	<link href="<?php echo base_url('assets/'); ?>css/style-responsive.min.css" rel="stylesheet" media='all' />
	<link href="<?php echo base_url('assets/'); ?>css/theme/default.css" rel="stylesheet" id="theme" media='all' />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap-wizard/css/bwizard.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="<?php echo base_url('assets/'); ?>plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/summernote/summernote.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/highcharts-7.1.2/code/css/highcharts.css">
	<link href="<?php echo base_url('assets/'); ?>plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url('assets/'); ?>plugins/pace/pace.min.js"></script>
  	<link href="<?php echo base_url('assets/'); ?>plugins/isotope/isotope.css" rel="stylesheet" />
  	<link href="<?php echo base_url('assets/');?>plugins/lightbox/css/lightbox.css" rel="stylesheet" />
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.all.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo base_url('assets/'); ?>plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>js/table-manage-default.demo.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/printThis/printThis.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.toolbar.js/0.1.0/Leaflet.Toolbar.min.css" integrity="sha512-GA6tz0ONkXAXGUnZU9M7mMkiOiXuXis56gRc73qvS+hXP0Sgb/mXihcqs6haKhes6mMeCPIdopIDixMwnnd+Iw==" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw-src.css" integrity="sha512-vJfMKRRm4c4UupyPwGUZI8U651mSzbmmPgR3sdE3LcwBPsdGeARvUM5EcSTg34DK8YIRiIo+oJwNfZPMKEQyug==" crossorigin="anonymous" />
	<!-- Make sure you put this AFTER Leaflet's CSS -->
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js" integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw==" crossorigin="anonymous"></script>
	<!-- Load Esri Leaflet from CDN -->
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1Uy11T5TGvp51t0sFlompBOSbay5Kb14" async defer></script>
  	<script src="https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js"></script>
	<script src="<?php echo base_url('assets/'); ?>js/qrscanner.js"></script>
	<script src="<?php echo base_url('assets/'); ?>js/quagga.min.js"></script>
	<link href='<?php echo base_url('assets/css/membership.css');?>' rel='stylesheet' />
	<style media="screen">
		.input-container input {
			border: none;
			box-sizing: border-box;
			outline: 0;
			padding: .75rem;
			position: relative;
			width: 100%;
		}

		input[type="date"]::-webkit-calendar-picker-indicator {
			background: transparent;
			bottom: 0;
			color: transparent;
			cursor: pointer;
			height: auto;
			left: 0;
			position: absolute;
			right: 0;
			top: 0;
			width: auto;
		}

		body {
			font-weight: bold;
		}

		@media print {
			.hidden-print {
				display: none !important;
			}
		}

		@media screen {
			.hidden-screen {
				display: none !important;
			}
		}

		.panel-inverse>.panel-heading{
			background-color: #D78473;
		}

		.btn-template{
			color:white;
			background-color: #D78473;
			border-color: #D78473;
		}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
</head>

<body>