<?php if(!empty($produk)):?>
<?php foreach($produk as $key):?>
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend2/'); ?>css/main.css">
    <link href="<?php echo base_url('assets/'); ?>plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" />
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
    <!-- Quick view -->
    <div class="modal fade show" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $key->nama_produk;?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="detail-gallery">
                                <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                <!-- MAIN SLIDES -->
                                <div class="product-image-slider">
                                    <figure class="border-radius-10">
                                        <img src="<?php echo base_url().$key->foto_produk;?>" alt="product image">
                                    </figure>
                                    <?php if(!empty($key->foto_produk_2)):?>
                                    <figure class="border-radius-10">
                                        <img src="<?php echo base_url().$key->foto_produk_2;?>" alt="product image">
                                    </figure>
                                    <?php endif;?>
                                    <?php if(!empty($key->foto_produk_3)):?>
                                    <figure class="border-radius-10">
                                        <img src="<?php echo base_url().$key->foto_produk_3;?>" alt="product image">
                                    </figure>
                                    <?php endif;?>
                                    <?php if(!empty($key->foto_produk_4)):?>
                                    <figure class="border-radius-10">
                                        <img src="<?php echo base_url().$key->foto_produk_4;?>" alt="product image">
                                    </figure>
                                    <?php endif;?>
                                </div>
                                <!-- THUMBNAILS -->
                                <div class="slider-nav-thumbnails pl-15 pr-15">
                                    <div><img src="<?php echo base_url().$key->foto_produk;?>" alt="product image"></div>
                                    <?php if(!empty($key->foto_produk_2)):?>
                                        <div><img src="<?php echo base_url().$key->foto_produk_2;?>" alt="product image"></div>
                                    <?php endif;?>
                                    <?php if(!empty($key->foto_produk_3)):?>
                                        <div><img src="<?php echo base_url().$key->foto_produk_3;?>" alt="product image"></div>
                                    <?php endif;?>
                                    <?php if(!empty($key->foto_produk_4)):?>
                                        <div><img src="<?php echo base_url().$key->foto_produk_4;?>" alt="product image"></div>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="detail-info">
                                <h3 class="title-detail mt-30"><?php echo $key->nama_produk;?></h3>
                                <div class="product-detail-rating">
                                    <div class="pro-details-brand">
                                        <span> Brand: <a href="<?php echo base_url('produk/brand/'.url_title($key->nama_brand,'dash',true));?>"><?php echo $key->nama_brand;?></a></span>
                                    </div>
                                    <div class="product-rate-cover text-end">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width:100%">
                                            </div>
                                        </div>
                                        <!-- <span class="font-small ml-5 text-muted"> (25 reviews)</span> -->
                                    </div>
                                </div>
                                <div class="clearfix product-price-cover">
                                    <div class="product-price primary-color float-left">
                                        <?php if($key->harga_diskon > 0): ?>
                                            <?php
                                                $selisih_harga = $key->harga_jual_online - $key->harga_diskon;
                                                $percent = ($selisih_harga / $key->harga_jual_online * 100);
                                            ?>
                                            <ins><span class="text-brand">Rp <?php echo number_format($key->harga_diskon,0,'.','.');?></span></ins>
                                            <ins><span class="old-price font-md ml-15">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></span></ins>
                                            <span class="save-price  font-md color3 ml-15"><?php echo number_format($percent,0,'.','.');?>% Off</span>
                                        <?php else: ?>
                                            <ins><span class="text-brand">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></span></ins>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="bt-1 border-color-1 mt-15 mb-15"></div>
                                <div class="short-desc mb-30">
                                    <?php echo substr(strip_tags($key->detail_produk),0,250);?>...
                                    <a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>">Lihat Selengkapnya</a>
                                </div>
                                <div class="bt-1 border-color-1 mt-30 mb-30"></div>
                                <div class="detail-extralink">
                                    <form action="<?php echo base_url('produk/addToChart/'.$key->id_produk);?>" method="post">
                                    <input type="number" name="qty" value="1" min="1" class="form-control">
                                    <hr>
                                    <div class="product-extra-link2">
                                        <button type="submit" class="button button-add-to-cart">Add to cart</button>
                                    </div>
                                    </form>
                                </div>
                                <ul class="product-meta font-xs color-grey mt-50">
                                    <li class="mb-5">Kategori: <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php echo $key->nama_kategori_produk;?></a></li>
                                    <li>Toko : <a href="<?php echo base_url('informasi/toko/detail/'.$key->slug_toko);?>"><?php echo $key->nama_toko;?></a></li>
                                </ul>
                            </div>
                            <!-- Detail Info -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- Vendor JS-->
	<script src="<?php echo base_url('assets/frontend2/');?>js/images-loaded.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/isotope.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/scrollup.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery.vticker-min.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery.theia.sticky.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/jquery.elevatezoom.js"></script>
	<!-- Template  JS -->
	<script src="<?php echo base_url('assets/frontend2/');?>js/main.js"></script>
	<script src="<?php echo base_url('assets/frontend2/');?>js/shop.js"></script>
<?php endforeach;?>
<?php endif;?>