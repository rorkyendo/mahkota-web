<!DOCTYPE html>
<html lang="en" class="no-js">

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
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend2/'); ?>css/main.css">
    <link href="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" />
	<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.bootstrap.min.css" rel="stylesheet" />
	<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo base_url('assets/'); ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
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
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1do2F8MWGnL4MPGT0NIQBo1qzvEr64YE&callback=initMap" async defer></script>
	<script src="https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js"></script>
    	<!-- Google tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-9P89XH59V1"></script>
    <script src="<?php echo base_url('assets/');?>mqttws31.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.all.js"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-9P89XH59V1');
	</script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/modernizr-3.6.0.min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery-3.6.0.min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery-migrate-3.3.0.min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/slick.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery.syotimer.min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/wow.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery-ui.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/perfect-scrollbar.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/magnific-popup.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/select2.min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/waypoints.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/counterup.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery.countdown.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
	<style>
	.scroll-card {
		overflow: auto;
		max-height: 520px; /* Set the maximum height of the scrollable card */
	}
	</style>
</head>

<body>