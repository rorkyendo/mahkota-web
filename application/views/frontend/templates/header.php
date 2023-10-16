<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $appsProfile->apps_name;?> | <?php echo $title;?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta property="og:site_name" content="<?php echo $appsProfile->apps_name;?>">
    <?php if($this->uri->segment(2) == 'item'): ?>
        <?php foreach($produk as $key):?>
            <meta property="og:type" content="article" />
            <meta property="og:title" content="<?php echo $key->nama_produk;?>" />
            <?php if($key->harga_diskon>0): ?>
                <meta property="og:description" content="<?php echo $key->nama_toko;?> | <?php echo $key->nama_produk;?> | Harga Mulai : Rp <?php echo number_format($key->harga_diskon,0,'.','.');?>"/>
            <?php else:?>
                <meta property="og:description" content="<?php echo $key->nama_toko;?> | <?php echo $key->nama_produk;?> | Harga Mulai : Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?>"/>
            <?php endif; ?>
            <meta property="og:url" content="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>" />
            <meta property="og:image" itemprop="image" content="<?php echo base_url().$key->foto_produk;?>" />
            <meta property="article:author" content="<?php echo base_url();?>">
            <meta property="article:published_time" content="<?php echo $key->created_time;?>">
        <?php endforeach;?>
    <?php endif; ?>

	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() . $appsProfile->icon; ?>">
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link rel="manifest" href="<?php echo base_url('/manifest.json'); ?>">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/DataTables/media/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/');?>css/e-commerce/app.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://kenwheeler.github.io/slick/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://kenwheeler.github.io/slick/slick/slick-theme.css"/>
	<link href="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" />
    <!-- ================== END BASE CSS STYLE ================== -->
	<script>
	    if ('serviceWorker' in navigator) {
	        console.log('SERVICE WORKER -> REGISTER -> Try to register the service worker');
	        navigator.serviceWorker.register('<?php echo base_url(); ?>service-worker.js')
	            .then(function (reg) {
	                console.log('SERVICE WORKER -> REGISTER -> Successfully registered!');
	            }).catch(function (err) {
	                console.log("'SERVICE WORKER -> REGISTER -> Registration failed! This happened: ", err)
	            });
	    }
	</script>
    <style>
        .btn-template{
            background-color: #c9002b;
            color:white;
        }

        .bg-template{
            background-color: #c9002b;
        }

        .carousel-indicators li{
            width:10px;
            height:10px;
            border-radius:100%;
        }
    </style>
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo base_url('assets/');?>js/e-commerce/app.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.all.js"></script>
    <!-- ================== END BASE JS ================== -->
	<script src="<?php echo base_url('assets/'); ?>plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/DataTables/media/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
	    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
	    crossorigin="" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.toolbar.js/0.1.0/Leaflet.Toolbar.min.css"
	    integrity="sha512-GA6tz0ONkXAXGUnZU9M7mMkiOiXuXis56gRc73qvS+hXP0Sgb/mXihcqs6haKhes6mMeCPIdopIDixMwnnd+Iw=="
	    crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw-src.css"
	    integrity="sha512-vJfMKRRm4c4UupyPwGUZI8U651mSzbmmPgR3sdE3LcwBPsdGeARvUM5EcSTg34DK8YIRiIo+oJwNfZPMKEQyug=="
	    crossorigin="anonymous" />
	<!-- Make sure you put this AFTER Leaflet's CSS -->
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
	    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
	    crossorigin=""></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"
	    integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw=="
	    crossorigin="anonymous"></script>
	<!-- Load Esri Leaflet from CDN -->
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
	    rel='stylesheet' />
	<link href='<?php echo base_url('assets/css/membership.css');?>'
	    rel='stylesheet' />
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1do2F8MWGnL4MPGT0NIQBo1qzvEr64YE&callback=initMap" async defer></script>
	<script src="https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    	<!-- Google tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-9P89XH59V1"></script>
    <script src="<?php echo base_url('assets/');?>mqttws31.min.js" type="text/javascript"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-9P89XH59V1');
	</script>
</head>

<!-- <body oncontextmenu="return false;" onkeydown="return false;" onmousedown="return false;"> -->
<body>
    <!-- BEGIN #page-container -->
    <div id="page-container" class="fade show">
        <?php $this->load->view('frontend/templates/navbar');?>
        <?php if($title=='Home'): ?>
        <div id="demo" class="carousel slide bg-white" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php $no=0; foreach($slider as $key):?>
                    <?php if($no<1): ?>
                        <li data-target="#demo" data-slide-to="<?php echo $no++;?>" class="active"></li>
                    <?php else: ?>
                        <li data-target="#demo" data-slide-to="<?php echo $no++;?>"></li>
                    <?php endif; ?>
                <?php endforeach;?>
            </ol>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <?php $no=0; foreach($slider as $key):?>
                <?php if($no<1): ?>
                    <div class="carousel-item active">
                <?php else: ?>
                    <div class="carousel-item">
                <?php endif; ?>
                    <center>
                        <img src="<?php echo base_url().$key->gambar_slider;?>" style="width:100%" alt="<?php echo $key->judul_slider;?>">
                    </center>
                </div>
                <?php $no++; endforeach;?>
            </div>

        </div>
        <?php endif;?>