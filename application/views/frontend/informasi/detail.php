<?php foreach($toko as $key):?>
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
		            <h1><?php echo $key->nama_toko;?></h1>
		            <p>
                        <?php echo $key->alamat_toko;?>
		            </p>
					<a href="<?php echo "https://maps.google.com/?q=".$key->lat_toko.",".$key->lng_toko;?>" target="_blank" class="btn btn-lg btn-info"><i class="fas fa-map-marker-alt"></i> Temukan kami di GMAPS</a>
					<a href="https://wa.me/<?php echo $key->no_wa;?>" class="btn btn-primary btn-lg"><i class="fab fa-whatsapp"></i> Hubungi Kami</a>
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
		            <h2 class="title text-center">PRODUK KAMI</h2>
		            <p class="desc text-center">
		            </p>
					<div id="trending-items" class="section-container">
						<!-- BEGIN container -->
						<div class="container">
							<!-- BEGIN row -->
							<div class="row row-space-10" id="searchItem">
								<!-- BEGIN col-2 -->
							<?php foreach($produk as $rp):?>
							<div class="col-lg-2 col-md-4 mb-2">
								<!-- BEGIN item -->
								<div class="item item-thumbnail">
									<a href="<?php echo base_url('produk/item/'.url_title($rp->nama_toko,"dash",true).'/'.$rp->slug_produk.'/'.$rp->id_produk);?>" class="item-image">
										<img src="<?php echo base_url().$rp->foto_produk;?>" alt="" />
										<?php if($rp->harga_diskon > 0): ?>
										<?php
											$selisih_harga = $rp->harga_jual_online - $rp->harga_diskon;
											$percent = ($selisih_harga / $rp->harga_jual_online * 100);
										?>
											<div class="discount"><?php echo number_format($percent,0,'.','.');?>% OFF</div>
										<?php endif; ?>
									</a>
									<div class="item-info">
										<h4 class="item-title">
											<a href="<?php echo base_url('produk/item/'.url_title($rp->nama_toko,"dash",true).'/'.$rp->slug_produk.'/'.$rp->id_produk);?>"><?php echo $rp->nama_produk;?></a>
										</h4>
										<?php if($rp->harga_diskon > 0): ?>
											<div class="item-price">Rp <?php echo number_format($rp->harga_diskon,0,'.','.');?></div>
											<div class="item-discount-price">Rp <?php echo number_format($rp->harga_jual_online,0,'.','.');?></div>
										<?php else: ?>
											<div class="item-price">Rp <?php echo number_format($rp->harga_jual_online,0,'.','.');?></div>
										<?php endif; ?>
									</div>
								</div>
								<!-- END item -->
							</div>
							<?php endforeach;?>
								<!-- END col-2 -->
							</div>
							<!-- END row -->
							<div class="ajax-load text-center" style="display:none">
								<p>Loading Data..</p>
							</div>
						</div>
						<!-- END container -->
					</div>
		        </div>
		        <!-- END about-us-content -->
		    </div>
		    <!-- END container -->
		</div>
		<!-- END #about-us-content -->
<script type="text/javascript">
  var page = 12;
  $(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() >= $('#searchItem').height()) {
      loadMoreData(page);
      page+=12;
    }
  });

  function loadMoreData(page) {
    $.ajax({
        url: '<?php echo base_url('informasi/toko/produk');?>',
        data: 'from=' + page + '&uuid_toko=<?php echo $key->uuid_toko; ?>&limit=12',
        type: "get",
        beforeSend: function() {
          $('.ajax-load').show();
        }
      })
      .done(function(data) {
        if (data == 0) {
          $('.ajax-load').html("Tidak Ada Produk Lain");
          return;
        }
        $('.ajax-load').hide();
        $("#searchItem").append(data);
      })
      .fail(function(jqXHR, ajaxOptions, thrownError) {
        alert('server not responding...');
      });
  }
</script>
<?php endforeach;?>