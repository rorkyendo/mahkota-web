		<!-- BEGIN #page-header -->
		<div id="page-header" class="section-container page-header-container bg-black">
			<!-- BEGIN page-header-cover -->
			<div class="page-header-cover">
				<!-- <img src="../assets/img/cover/cover-13.jpg" alt="" /> -->
			</div>
			<!-- END page-header-cover -->
			<!-- BEGIN container -->
			<div class="container">
				<h1 class="page-header">Tukarkan point kamu dengan produk menarik ditoko kami</h1>
			</div>
			<!-- END container -->
		</div>
		<!-- BEGIN #page-header -->
		
		<!-- BEGIN search-results -->
		<div id="search-results" class="section-container">
			<!-- BEGIN container -->
			<div class="container">
				<!-- BEGIN search-container -->
				<div class="search-container">
					<!-- BEGIN search-content -->
					<div class="search-content col-md-12">
						<!-- BEGIN search-item-container -->
						<div class="search-item-container" id="searchItem">
							<div class="item-row">
								<!-- BEGIN item-row -->
							<?php foreach($produkRedeem as $key):?>
									<!-- BEGIN item -->
									<div class="item item-thumbnail">
										<a href="<?php echo base_url('produk/redeemPoint/detail/'.$key->id_produk_redeem);?>" class="item-image">
											<img src="<?php echo base_url().$key->gambar_produk_redeem;?>" alt="" />
										</a>
										<div class="item-info">
											<h4 class="item-title">
												<a href="<?php echo base_url('produk/redeemPoint/detail/'.$key->id_produk_redeem);?>"><?php echo $key->nama_produk_redeem;?></a>
											</h4>
												<div class="item-price"><?php echo number_format($key->harga_point,0,'.','.');?> POINT</div>
										</div>
									</div>
									<!-- END item -->
							<?php endforeach;?>
							</div>
							<!-- END item-row -->
						</div>
						<div class="ajax-load text-center" style="display:none">
							<p>Loading Data..</p>
						</div>
						<!-- END search-item-container -->
					</div>
					<!-- END search-content -->
					<div class="search-sidebar">
						<h4 class="title m-b-0">Profil Pengguna</h4>
						<ul class="search-category-list">
							<?php foreach($pengguna as $key):?>
								<li><a href="#"><?php echo $key->nama_lengkap;?></a></li>
								<li><a href="#"><?php echo $key->nama_tipe_member;?></a></li>
								<li><a href="#"><?php echo number_format($key->point,0,'.','.');?> Point</a></li>
							<?php endforeach;?>
						</ul>
					</div>
					<!-- END search-sidebar -->
				</div>
				<!-- END search-container -->
			</div>
			<!-- END container -->
		</div>
		<!-- END search-results -->

<script type="text/javascript">
  var page = 3;
  $(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() >= $('#searchItem').height()) {
      loadMoreData(page);
      page+=3;
    }
  });

  function loadMoreData(page) {
    $.ajax({
        url: '<?php echo base_url('produk/redeemPoint/getData/');?>',
        data: 'from=' + page + '&limit=3',
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