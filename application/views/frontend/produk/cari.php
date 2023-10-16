		<!-- BEGIN #page-header -->
		<div id="page-header" class="section-container page-header-container bg-black">
			<!-- BEGIN page-header-cover -->
			<div class="page-header-cover">
				<!-- <img src="../assets/img/cover/cover-13.jpg" alt="" /> -->
			</div>
			<!-- END page-header-cover -->
			<!-- BEGIN container -->
			<div class="container">
				<h1 class="page-header">Pencarian untuk "<b><?php echo $search;?></b>"</h1>
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
					<div class="search-content">
						<!-- BEGIN search-toolbar -->
						<div class="search-toolbar">
							<!-- BEGIN row -->
							<div class="row">
								<!-- BEGIN col-6 -->
								<div class="col-lg-6">
									<h4>Ada <?php echo number_format($jmlSearch->jumlah,0,'.','.');?> Items untuk pencarian "<?php echo $search;?>"</h4>
								</div>
								<!-- END col-6 -->
								<!-- BEGIN col-6 -->
								<div class="col-lg-6 text-right">
									<ul class="sort-list">
										<li class="text"><i class="fa fa-filter"></i> Sort by:</li>
										<li class="<?php if($active == 'populer'){ echo "active";}?>"><a href="<?php echo base_url('produk/pencarian/populer?nama_produk='.$search);?>">Populer</a></li>
										<li class="<?php if($active != 'populer' && $active != 'harga'){ echo "active";}?>"><a href="<?php echo base_url('produk/pencarian?nama_produk='.$search);?>">Terbaru</a></li>
										<?php if($active == 'harga'):?>
										<li class="text">
											<select onchange="sort(this.value)" id="form-sort" class="form-control">
												<option value="">.:Urutkan:.</option>
												<option value="ASC">TERENDAH</option>
												<option value="DESC">TERTINGGI</option>
											</select>
										</li>
										<script>
											$("#form-sort").val('<?php echo $sort;?>')
										</script>
										<?php endif;?>
									</ul>
								</div>
								<!-- END col-6 -->
							</div>
							<!-- END row -->
						</div>
						<!-- END search-toolbar -->
						<!-- BEGIN search-item-container -->
						<div class="search-item-container" id="searchItem">
							<!-- BEGIN item-row -->
							<div class="item-row">
							<?php foreach($produk as $key):?>
								<?php if(!empty($key->foto_produk)):?>
									<!-- BEGIN item -->
									<div class="item item-thumbnail">
										<a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>" class="item-image">
											<img src="<?php echo base_url().$key->foto_produk;?>" alt="" />
											<?php if($key->harga_diskon > 0): ?>
											<?php
												$selisih_harga = $key->harga_jual_online - $key->harga_diskon;
												$percent = ($selisih_harga / $key->harga_jual_online * 100);
											?>
												<div class="discount"><?php echo number_format($percent,0,'.','.');?>% OFF</div>
											<?php endif; ?>
										</a>
										<div class="item-info">
											<h4 class="item-title">
												<a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>"><?php echo $key->nama_produk;?></a>
											</h4>
											<?php if($key->harga_diskon > 0): ?>
												<div class="item-price">Rp <?php echo number_format($key->harga_diskon,0,'.','.');?></div>
												<div class="item-discount-price">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></div>
											<?php else: ?>
												<div class="item-price">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></div>
											<?php endif; ?>
										</div>
									</div>
									<!-- END item -->
								<?php endif;?>
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
					<!-- BEGIN search-sidebar -->
					<div class="search-sidebar">
						<h4 class="title m-b-0">Kategori</h4>
						<ul class="search-category-list">
							<?php foreach($kategoriProduk as $key):?>
								<li><a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php echo $key->nama_kategori_produk;?> <span class="pull-right"><?php echo number_format($key->jmlProduk,0,'.','.');?></span></a></li>
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

  function sort(val){
	location.replace('<?php echo base_url('produk/pencarian?nama_produk'.$search);?>?sort='+val)
  }

  $(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() >= $('#searchItem').height()) {
      loadMoreData(page);
      page+=3;
    }
  });

  function loadMoreData(page) {
    $.ajax({
        url: '<?php echo base_url('produk/pencarian/getData/'.$active);?>',
        data: 'from=' + page + '&nama_produk=<?php echo $search; ?>&limit=3&sort=<?php echo $sort;?>',
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