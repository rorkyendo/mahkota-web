        <!-- BEGIN #trending-items -->
        <div id="trending-items" class="section-container">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN section-title -->
                <h4 class="section-title clearfix">
                    PULSA , TOKEN LISRIK & VOUCHER GAME ONLINE
                </h4>
                <!-- END section-title -->
                <div class="slider responsive">
                    <div class="col-4">
                        <!-- BEGIN item -->
                            <div class="item item-thumbnail">
                                <a href="<?php echo base_url('produk/pulsa');?>" class="item-image">
                                    <img src="<?php echo base_url('assets/img/bill.png');?>" class="mx-auto d-block" alt="" />
                                </a>
                                <h6 class="text-center">PULSA & PAKET DATA</h6>
                            </div>
                        <!-- END item -->
                    </div>
                    <div class="col-4">
                        <!-- BEGIN item -->
                            <div class="item item-thumbnail">
                                <a href="<?php echo base_url('produk/tokenListrik');?>" class="item-image">
                                    <img src="<?php echo base_url('assets/img/electricity-bill.png');?>" class="mx-auto d-block" alt="" />
                                </a>
                                <h6 class="text-center">TOKEN LISTRIK</h6>
                            </div>
                        <!-- END item -->
                    </div>
                    <div class="col-4">
                        <!-- BEGIN item -->
                            <div class="item item-thumbnail">
                                <a href="<?php echo base_url('produk/voucherGame');?>" class="item-image">
                                    <img src="<?php echo base_url('assets/img/voucher.png');?>" class="mx-auto d-block" alt="" />
                                </a>
                                <h6 class="text-center">VOUCHER GAME</h6>
                            </div>
                        <!-- END item -->
                    </div>
                </div>
                <!-- END row -->
            </div>
            <!-- END container -->

            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN section-title -->
                <h4 class="section-title clearfix">
                    Kategori
                </h4>
                <!-- END section-title -->
                <div class="slider responsive">
                <?php foreach($kategori_produk as $key):?>
                    <div class="col-3">
                        <!-- BEGIN item -->
                            <div class="item item-thumbnail">
                                <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>" class="item-image">
                                    <img src="<?php echo base_url().$key->gambar_kategori_produk;?>" class="mx-auto d-block" alt="" />
                                </a>
                                <h6 class="text-center"><?php echo $key->nama_kategori_produk;?></h6>
                            </div>
                        <!-- END item -->
                    </div>
                    <?php endforeach;?>
                </div>
                <!-- END row -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #trending-items -->

        <!-- BEGIN #mobile-list -->
        <?php foreach($kategori_produk as $key):?>
        <?php $getProduk = $this->GeneralModel->limit_by_multi_id_general_order_by('v_produk', 'kategori_produk', $key->id_kategori_produk, 'tampil_toko', 'Y', 'id_produk', 'DESC', 6);?>
        <?php $getBrand = $this->GeneralModel->get_by_id_general('ms_brand','kategori',$key->id_kategori_produk);?>
        <div id="mobile-list" class="section-container p-t-0">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN section-title -->
                <h4 class="section-title clearfix">
                    <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>" class="pull-right">SHOW ALL</a>
                    <?php echo $key->nama_kategori_produk;?>
                    <small>Dapatkan penawaran menarik dan termurah untuk produk <?php echo $key->nama_kategori_produk;?> yang kamu cari!</small>
                </h4>
                <!-- END section-title -->
                <!-- BEGIN category-container -->
                <div class="category-container">
                    <!-- BEGIN category-sidebar -->
                    <div class="category-sidebar bg-template d-none d-lg-block d-md-block">
                        <ul class="category-list">
                            <li class="list-header text-white">Daftar Brand</li>
                            <?php foreach($getBrand as $row):?>
                                <li><a href="<?php echo base_url('produk/brand/'.url_title($row->nama_brand,'dash',true));?>" class="text-white"><?php echo $row->nama_brand;?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <!-- END category-sidebar -->
                    <!-- BEGIN category-detail -->
                    <div class="category-detail">
                        <!-- BEGIN category-item -->
                        <div class="category-item list">
                            <!-- BEGIN item-row -->
                            <div class="item-row">
                                <?php foreach($getProduk as $gp):?>
                                <?php if(!empty($gp->foto_produk)):?>
                                <!-- BEGIN item -->
                                <div class="item item-thumbnail">
                                    <a href="<?php echo base_url('produk/item/'.url_title($gp->nama_toko,"dash",true).'/'.$gp->slug_produk.'/'.$gp->id_produk);?>" class="item-image">
                                        <img src="<?php echo base_url().$gp->foto_produk;?>" alt="" />
                                        <?php if($gp->harga_diskon > 0): ?>
                                        <?php
                                            $selisih_harga = $gp->harga_jual_online - $gp->harga_diskon;
                                            $percent = 100 - ($selisih_harga / $gp->harga_jual_online * 100);
                                        ?>
                                            <div class="discount"><?php echo number_format($percent,0,'.','.');?>% OFF</div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="item-info">
                                        <h4 class="item-title">
                                            <a href="<?php echo base_url('produk/item/'.url_title($gp->nama_toko,"dash",true).'/'.$gp->slug_produk.'/'.$gp->id_produk);?>"><?php echo $gp->nama_produk;?></a>
                                        </h4>
                                        <?php if($gp->harga_diskon > 0): ?>
                                            <div class="item-price">Rp <?php echo number_format($selisih_harga,0,'.','.');?></div>
                                            <div class="item-discount-price">Rp <?php echo number_format($gp->harga_jual_online,0,'.','.');?></div>
                                        <?php else: ?>
                                            <div class="item-price">Rp <?php echo number_format($gp->harga_jual_online,0,'.','.');?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif;?>
                                <!-- END item -->
                                <?php endforeach;?>
                            </div>
                            <!-- END item-row -->
                        </div>
                        <!-- END category-item -->
                    </div>
                    <!-- END category-detail -->
                </div>
                <!-- END category-container -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #mobile-list -->
        <?php endforeach;?>