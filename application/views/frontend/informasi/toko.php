		<!-- BEGIN #about-us-cover -->
		<div id="about-us-cover" class="has-bg section-container">
		    <!-- BEGIN cover-bg -->
		    <div class="cover-bg">
		        <img src="<?php echo base_url('assets/img/tokoBG.jpg');?>" alt="" />
		    </div>
		    <!-- END cover-bg -->
		    <!-- BEGIN container -->
		    <div class="container">
		        <!-- BEGIN breadcrumb -->
		        <ul class="breadcrumb">
		            <li class="breadcrumb-item"><a href="#">Home</a></li>
		            <li class="breadcrumb-item active">Toko</li>
		        </ul>
		        <!-- END breadcrumb -->
		        <!-- BEGIN about-us -->
		        <div class="about-us text-center">
		            <h1>Toko</h1>
		            <p>
                        Kami hadir di beberapa cabang di kota anda!
		            </p>
		        </div>
		        <!-- END about-us -->
		    </div>
		    <!-- END container -->
		</div>
		<!-- END #about-us-cover -->

		<!-- BEGIN #about-us-content -->
		<div class="section-container bg-white">
		    <!-- BEGIN container -->
		    <div class="container">
		        <!-- BEGIN about-us-content -->
		        <div class="about-us-content">
		            <h2 class="title text-center">DAFTAR TOKO</h2>
		            <p class="desc text-center">
		                Berikut ini adalah daftar toko kami yang dapat anda kunjungi di kota anda
		            </p>
		            <!-- BEGIN row -->
		            <div class="row">
		                <div class="col-md-12" id="daftarToko">
							<?php foreach($toko as $key):?>
							<div class="card bg-dark-darker mb-3">
								<div class="card-header text-white">
									<h1><?php echo $key->nama_toko;?></h1>
								</div>
								<div class="card-body bg-white">
									<p><?php echo $key->alamat_toko;?></p>
									<a href="<?php echo base_url('informasi/toko/detail/'.$key->slug_toko);?>" class="btn btn-default btn-md"><i class="fa fa-store"></i> Detail</a>
									<a href="<?php echo "https://maps.google.com/?q=".$key->lat_toko.",".$key->lng_toko;?>" target="_blank" class="btn btn-info btn-md"><i class="fas fa-map-marker-alt"></i> Buka GMAPS</a>
									<a href="https://wa.me/<?php echo $key->no_wa;?>" class="btn btn-primary btn-md"><i class="fab fa-whatsapp"></i> Hubungi Toko</a>
								</div>
							</div>
							<?php endforeach;?>
		                </div>
		            </div>
		            <!-- END row -->
		        </div>
		        <!-- END about-us-content -->
		    </div>
		    <!-- END container -->
		</div>
		<!-- END #about-us-content -->