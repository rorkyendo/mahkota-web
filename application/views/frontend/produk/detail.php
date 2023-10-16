<?php foreach($produk as $key):?>
<!-- BEGIN #product -->
<div id="product" class="section-container p-t-20">
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN breadcrumb -->
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('informasi/toko/detail/'.$key->slug_toko);?>"><?php echo $key->nama_toko;?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php echo $key->nama_kategori_produk;?></a></li>
            <li class="breadcrumb-item active"><?php echo $key->nama_produk;?></li>
        </ul>
        <!-- END breadcrumb -->
        <?php echo $this->session->flashdata('notif');?>
        <!-- BEGIN product -->
        <div class="product">
            <!-- BEGIN product-detail -->
            <div class="product-detail">
                <!-- BEGIN product-image -->
                <div class="product-image">
                    <!-- BEGIN product-thumbnail -->
                    <div class="product-thumbnail">
                        <ul class="product-thumbnail-list">
                            <li class="active"><a href="#" data-click="show-main-image" data-url="<?php echo base_url().$key->foto_produk;?>"><img src="<?php echo base_url().$key->foto_produk;?>" alt="" /></a></li>
                            <?php if(!empty($key->foto_produk_2)): ?>
                                <li><a href="#" data-click="show-main-image" data-url="<?php echo base_url().$key->foto_produk_2;?>"><img src="<?php echo base_url().$key->foto_produk_2;?>" alt="" /></a></li>
                            <?php endif; ?>
                            <?php if(!empty($key->foto_produk_3)): ?>
                                <li><a href="#" data-click="show-main-image" data-url="<?php echo base_url().$key->foto_produk_3;?>"><img src="<?php echo base_url().$key->foto_produk_3;?>" alt="" /></a></li>
                            <?php endif; ?>
                            <?php if(!empty($key->foto_produk_4)): ?>
                                <li><a href="#" data-click="show-main-image" data-url="<?php echo base_url().$key->foto_produk_4;?>"><img src="<?php echo base_url().$key->foto_produk_4;?>" alt="" /></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- END product-thumbnail -->
                    <!-- BEGIN product-main-image -->
                    <div class="product-main-image" data-id="main-image">
                        <img src="<?php echo base_url().$key->foto_produk;?>" alt="" />
                    </div>
                    <!-- END product-main-image -->
                </div>
                <!-- END product-image -->
                <!-- BEGIN product-info -->
                <div class="product-info">
                    <!-- BEGIN product-info-header -->
                    <div class="product-info-header">
                    <?php if($key->harga_diskon>0): ?>
                        <?php
                            $selisih_harga = $key->harga_jual_online - $key->harga_diskon;
                            $percent = ($selisih_harga / $key->harga_jual_online * 100);
                        ?>
                        <h1 class="product-title"><span class="badge bg-primary"><?php echo number_format($percent,0,'.','.');?>% OFF</span> <?php echo $key->nama_produk;?></h1>
                    <?php endif; ?>
                    </div>
                    <!-- END product-info-header -->
                    <!-- BEGIN product-warranty -->
                    <div class="product-warranty">
                        <div>TOKO : <a href="<?php echo base_url('informasi/toko/detail/'.$key->slug_toko);?>" style="font-weight:bold;"><?php echo $key->nama_toko;?></a></div>
                    </div>
                    <!-- END product-warranty -->
                    <!-- BEGIN product-info-list -->
                    <ul class="product-info-list">
                        <?php echo $key->detail_produk;?>
                    </ul>
                    <!-- END product-info-list -->
                    <!-- BEGIN product-social -->
                    <div class="product-social">
                        <ul>
                            <li>CONTACT :</li>
                            <li><a href="https://wa.me/<?php echo $key->no_wa;?>" target="_blank" class="whatsapp" data-toggle="tooltip" data-trigger="hover" data-title="Hubungi Toko" data-placement="top"><i class="fab fa-whatsapp"></i></a></li>
                            <li>SHARE :</li>
                            <li><a href="whatsapp://send?text=<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>" target="_blank" class="whatsapp d-md-none" data-toggle="tooltip" data-trigger="hover" data-title="Bagikan WA" data-placement="top"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="https://web.whatsapp.com/send?text=<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>" target="_blank" class="whatsapp d-none d-lg-block" data-toggle="tooltip" data-trigger="hover" data-title="Bagikan WA" data-placement="top"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>" target="_blank" class="facebook" data-toggle="tooltip" data-trigger="hover" data-title="Bagikan FB" data-placement="top"><i class="fab fa-facebook"></i></a></li>
                        </ul>
                    </div>
                    <!-- END product-social -->
                    <!-- BEGIN product-purchase-container -->
                    <!-- <div class=""> -->
                    <?php if($key->harga_diskon>0): ?>
                        <div class="product-discount">
                            <span class="discount">Rp<?php echo number_format($key->harga_jual_online,0,'.','.');?></span>
                        </div>
                        <div class="product-price">
                            <div class="price">Rp<?php echo number_format($key->harga_diskon,0,'.','.');?></div>
                        </div>
                    <?php else: ?>
                        <div class="product-price">
                            <div class="price">Rp<?php echo number_format($key->harga_jual_online,0,'.','.');?></div>
                        </div>
                    <?php endif; ?>
                        <form action="<?php echo base_url('produk/addToChart/'.$key->id_produk);?>" method="POST">
                            <div class="input-group mt-3">
                                <div class="input-group-append">
                                    <button class="btn btn-template" type="button" onclick="kurang()"><i class="fa fa-minus"></i></button>
                                </div>
                                <input type="text" onkeypress="onlyNumberKey(this.event)" value="1" min="1" placeholder="0" id="qty" name="qty" class="form-control bg-silver-lighter col-2" />
                                <div class="input-group-append">
                                    <button class="btn btn-template" type="button" onclick="tambah()"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-template btn-theme btn-lg width-200"><i class="fa fa-plus"></i> Keranjang</button>
                        </form>
                        <script>
                            function tambah(){
                                var val = $('#qty').val()
                                $('#qty').val(Number(val)+1);
                            }

                            function kurang(){
                                var val = $('#qty').val()
                                $('#qty').val(Number(val)-1);
                            }
                        </script>
                    </div>
                    <!-- END product-purchase-container -->
                <!-- </div> -->
                <!-- END product-info -->
            </div>
            <!-- END product-detail -->
            <!-- BEGIN product-tab -->

            <!-- END product-tab -->
        </div>
        <!-- END product -->
        <!-- BEGIN similar-product -->
        <h4 class="m-b-15 m-t-30">Produk Di Toko ini</h4>
        <div class="row row-space-10">
            <?php foreach($randProduk as $rp):?>
            <div class="col-lg-2 col-md-4">
                <!-- BEGIN item -->
                <div class="item item-thumbnail">
                    <a href="<?php echo base_url('produk/item/'.url_title($rp->nama_toko,"dash",true).'/'.$rp->slug_produk.'/'.$rp->id_produk);?>" class="item-image">
                        <img src="<?php echo base_url().$rp->foto_produk;?>" alt="" />
                        <?php if($rp->harga_diskon > 0): ?>
                        <?php
                            $selisih_harga = $rp->harga_jual_online - $rp->harga_diskon;
                            $percent = 100 - ($selisih_harga / $rp->harga_jual_online * 100);
                        ?>
                            <div class="discount"><?php echo number_format($percent,0,'.','.');?>% OFF</div>
                        <?php endif; ?>
                    </a>
                    <div class="item-info">
                        <h4 class="item-title">
                            <a href="<?php echo base_url('produk/item/'.url_title($rp->nama_toko,"dash",true).'/'.$rp->slug_produk.'/'.$rp->id_produk);?>"><?php echo $rp->nama_produk;?></a>
                        </h4>
                        <?php if($rp->harga_diskon > 0): ?>
                            <div class="item-price">Rp <?php echo number_format($selisih_harga,0,'.','.');?></div>
                            <div class="item-discount-price">Rp <?php echo number_format($rp->harga_jual_online,0,'.','.');?></div>
                        <?php else: ?>
                            <div class="item-price">Rp <?php echo number_format($rp->harga_jual_online,0,'.','.');?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- END item -->
            </div>
            <?php endforeach;?>
        </div>
        <!-- END similar-product -->
    </div>
    <!-- END container -->
</div>
<!-- END #product -->
<?php endforeach;?>
