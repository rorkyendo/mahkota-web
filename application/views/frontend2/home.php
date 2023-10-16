<main class="main">
        <section class="home-slider position-relative pt-25 pb-20">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="position-relative">
                            <div class="hero-slider-1 style-3 dot-style-1 dot-style-1-position-1">
                            <?php $no=0; foreach($slider as $key):?>
                                <div class="single-hero-slider single-animation-wrap">
                                    <div class="container">
                                        <div class="slider-1-height-3 slider-animated-1">
                                            <div class="slider-img">
                                                <a href="<?php echo base_url().$key->url_slider;?>"><img src="<?php echo base_url().$key->gambar_slider_2;?>" alt></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                            <div class="slider-arrow hero-slider-1-arrow style-3"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-md-none d-lg-block">
                        <?php if(!empty($diskon)):?>
                        <?php $x=1; for($i=0;$i<2;$i++):?>
                            <?php if(empty($diskon[$i]) && $i==0):?>
                            <div class="banner-img banner-1 wow fadeIn  animated home-3">
                                <img class="border-radius-10" src="<?php echo base_url('assets/frontend2/cooming-soon-1.png')?>" alt>
                            </div>                    
                            <?php elseif(empty($diskon[$i]) && $i==1):?>
                            <div class="banner-img banner-2 wow fadeIn  animated home-3">
                                <img class="border-radius-10" src="<?php echo base_url('assets/frontend2/cooming-soon-2.png')?>" alt>
                            </div>
                            <?php else:?>
                            <div class="banner-img banner-<?php echo $x;?> wow fadeIn  animated home-3">
                                <a href="<?php echo $diskon[$i]->url_promosi;?>">
                                    <img class="border-radius-10" src="<?php echo base_url().$diskon[$i]->file_promosi;?>" alt>
                                </a>
                            </div>
                            <?php endif;?>                        
                        <?php $x++; endfor;?>
                        <?php else:?>
                            <div class="banner-img banner-1 wow fadeIn  animated home-3">
                                <img class="border-radius-10" src="<?php echo base_url('assets/frontend2/cooming-soon-1.png')?>" alt>
                            </div>
                            <div class="banner-img banner-2 wow fadeIn  animated home-3">
                                <img class="border-radius-10" src="<?php echo base_url('assets/frontend2/cooming-soon-2.png')?>" alt>
                            </div>
                        <?php endif;?>                        
                    </div>
                </div>
            </div>
        </section>
        <section class="featured section-padding">
            <div class="container">
                <div class="row">
                    <?php foreach($layanan as $key):?>
                        <?php if($key->lokasi_promosi == 'web'):?>
                        <?php if($key->judul_promosi == "Top Up Pulsa" && ($this->session->userdata("LoggedIN") == TRUE)):?>
                                <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                                    <a href="<?php echo base_url('transaksi/pulsa');?>">
                                        <div class="banner-features wow fadeIn animated hover-up animated">
                                            <img src="<?php echo base_url().$key->file_promosi;?>" alt>
                                            <h4 class="bg-1"><?php echo $key->judul_promosi;?></h4>
                                        </div>
                                    </a>
                                </div>
                        <?php else:?>
                            <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                                <div class="banner-features wow fadeIn animated hover-up animated">
                                    <img src="<?php echo base_url().$key->file_promosi;?>" alt>
                                    <h4 class="bg-1"><?php echo $key->judul_promosi;?></h4>
                                </div>
                            </div>
                        <?php endif;?>
                    <?php endif;?>
                    <?php endforeach;?>
                </div>
            </div>
        </section>
        <section class="product-tabs section-padding wow fadeIn animated">
            <div class="container">
                <div class="tab-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <?php $x=1; foreach($kategori_produk as $key):?>
                            <li class="nav-item" role="presentation">
                                <?php if($x==1):?>
                                    <button class="nav-link active" id="nav-tab-<?php echo $key->id_kategori_produk;?>" data-bs-toggle="tab" data-bs-target="#tab-<?php echo $key->id_kategori_produk;?>" type="button" role="tab" aria-controls="tab-one" aria-selected="true"><?php echo $key->nama_kategori_produk;?></button>
                                <?php else:?>
                                    <button class="nav-link" id="nav-tab-<?php echo $key->id_kategori_produk;?>" data-bs-toggle="tab" data-bs-target="#tab-<?php echo $key->id_kategori_produk;?>" type="button" role="tab" aria-controls="tab-one" aria-selected="true"><?php echo $key->nama_kategori_produk;?></button>
                                <?php endif;?>
                            </li>
                        <?php $x++; endforeach;?>
                    </ul>
                    <a href="#" class="view-more d-none d-md-flex">View More<i class="fi-rs-angle-double-small-right"></i></a>
                </div>
                <!--End nav-tabs-->
                <div class="tab-content wow fadeIn animated" id="myTabContent">
                    <?php $x=1; foreach($kategori_produk as $key):?>
                    <?php if($x==1):?>
                        <div class="tab-pane fade show active" id="tab-<?php echo $key->id_kategori_produk;?>" role="tabpanel" aria-labelledby="tab-<?php echo $key->id_kategori_produk;?>">
                    <?php else:?>
                        <div class="tab-pane fade show" id="tab-<?php echo $key->id_kategori_produk;?>" role="tabpanel" aria-labelledby="tab-<?php echo $key->id_kategori_produk;?>">
                    <?php endif;?>
                        <div class="row product-grid-4">
                            <?php $getProduk = $this->GeneralModel->limit_by_multi_id_general_order_by('v_produk', 'kategori_produk', $key->id_kategori_produk, 'tampil_toko', 'Y', 'id_produk', 'DESC', 8);?>
                            <?php foreach($getProduk as $row):?>
                                <div class="col-lg-3 col-md-4 col-6 col-sm-12">
                                    <div class="product-cart-wrap mb-30">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">
                                                    <img class="default-img" src="<?php echo base_url().$row->foto_produk;?>" alt>
                                                    <img class="hover-img" src="<?php echo base_url().$row->foto_produk_2;?>" alt>
                                                </a>
                                            </div>
                                            <div class="product-action-1">
                                                <a aria-label="Quick view" class="action-btn hover-up" onclick="detailProduk('<?php echo $row->id_produk;?>')"><i class="fi-rs-eye"></i></a>
                                                <a aria-label="Compare" class="action-btn hover-up" href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><i class="fi-rs-shuffle"></i></a>
                                            </div>
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <?php if($row->harga_diskon > 0): ?>
                                                <?php
                                                    $selisih_harga = $row->harga_jual_online - $row->harga_diskon;
                                                    $percent = ($selisih_harga / $row->harga_jual_online * 100);
                                                ?>
                                                    <span class="hot">-<?php echo number_format($percent,0,'.','.');?>%</span>
                                                <?php else:?>
                                                    <span class="new">Good</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php $row->kategori_produk;?></a>
                                            </div>
                                            <h2><a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><?php echo $row->nama_produk;?></a></h2>
                                            <div class="rating-result" title="100%">
                                                <span>
                                                    <span>100%</span>
                                                </span>
                                            </div> 
                                            <div class="product-price">
                                            <?php if($row->harga_diskon > 0): ?>
                                                <span>Rp <?php echo number_format($row->harga_diskon,0,'.','.');?></span>
                                                <span class="old-price">Rp <?php echo number_format($row->harga_jual_online,0,'.','.');?></span>
                                            <?php else: ?>
                                                <span>Rp <?php echo number_format($row->harga_jual_online,0,'.','.');?></span>
                                            <?php endif; ?>
                                            </div>
                                            <div class="product-action-1 show">
                                                <a aria-label="Add To Cart" class="action-btn hover-up" onclick="addToCart('<?php echo $row->id_produk;?>')"><i class="fi-rs-shopping-bag-add"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    <!--En tab one (Featured)-->
                    <?php $x++; endforeach;?>
                </div>
                <!--End tab-content-->
            </div>
        </section>
        <section class="banner-2 section-padding pb-0">
            <div class="container">
                <?php if(!empty($diskon[2])):?>
                <div class="banner-img banner-big wow fadeIn animated f-none">
                    <a href="<?php echo base_url().$diskon[2]->url_promosi;?>">
                        <img src="<?php echo base_url().$diskon[2]->file_promosi;?>" alt>
                    </a>
                    <div class="banner-text d-md-block d-none">
                        <h4 class="mb-15 mt-40 text-brand"><?php echo $diskon[2]->judul_promosi;?></h4>
                        <h1 class="fw-600 mb-20"><?php echo $diskon[2]->text_promosi;?></h1>
                    </div>
                </div>
                <?php else:?>
                <div class="banner-img banner-big wow fadeIn animated f-none">
                    <img src="<?php echo base_url('assets/frontend2/cooming-soon-3.png')?>" alt>
                </div>
                <?php endif;?>
            </div>
        </section>
        <section class="popular-categories section-padding mt-15">
            <div class="container wow fadeIn animated">
                <h3 class="section-title mb-20"><span>Top</span> Kategori</h3>
                <div class="carausel-6-columns-cover position-relative">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-arrows"></div>
                    <div class="carausel-6-columns" id="carausel-6-columns">
                        <?php foreach($kategori_produk as $key):?>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><img src="<?php echo base_url().$key->gambar_kategori_produk;?>" alt></a>
                            </figure>
                            <h5><a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php echo $key->nama_kategori_produk;?></a></h5>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </section>
        <section class="banners mb-20">
            <div class="container">
                <div class="row">
                    <?php for($i=3;$i<6;$i++):?>
                    <?php if(!empty($diskon[$i])):?>
                        <div class="col-lg-4 col-md-6">
                            <div class="banner-img wow fadeIn animated">
                                <a href="<?php echo base_url().$diskon[$i]->url_promosi;?>">
                                    <img src="<?php echo base_url('assets/frontend2/');?>images/banner-1.png" alt>
                                </a>
                                <div class="banner-text">
                                    <span><?php $diskon[$i]->judul_promosi;?></span>
                                    <h4><?php $diskon[$i]->text_promosi;?></h4>
                                </div>
                            </div>
                        </div>
                    <?php else:?>
                        <div class="col-lg-4 col-md-6">
                            <div class="banner-img wow fadeIn animated">
                                <img src="<?php echo base_url('assets/frontend2/cooming-soon-4.png');?>" alt>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php endfor;?>
                </div>
            </div>
        </section>
        <section class="pt-25 pb-20">
            <div class="container wow fadeIn animated">
                <h3 class="section-title mb-20"><span>Produk</span> Terbaru</h3>
                <div class="carausel-6-columns-cover position-relative">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-2-arrows"></div>
                    <div class="carausel-6-columns carausel-arrow-center" id="carausel-6-columns-2">
                        <?php $getProduk = $this->GeneralModel->limit_by_id_general_order_by('v_produk', 'tampil_toko', 'Y', 'id_produk', 'DESC', 8);?>
                        <?php foreach($getProduk as $row):?>
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">
                                        <img class="default-img" src="<?php echo base_url().$row->foto_produk;?>" alt>
                                        <img class="hover-img" src="<?php echo base_url().$row->foto_produk_2;?>" alt>
                                    </a>
                                </div>
                                <div class="product-action-1">
                                    <a aria-label="Quick view" class="action-btn small hover-up" onclick="detailProduk('<?php echo $row->id_produk;?>')"><i class="fi-rs-eye"></i></a>
                                    <a aria-label="Compare" class="action-btn small hover-up" href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>" tabindex="0"><i class="fi-rs-shuffle"></i></a>
                                </div>
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <?php if($row->harga_diskon > 0): ?>
                                        <?php
                                            $selisih_harga = $row->harga_jual_online - $row->harga_diskon;
                                            $percent = ($selisih_harga / $row->harga_jual_online * 100);
                                        ?>
                                            <span class="hot">-<?php echo number_format($percent,0,'.','.');?>%</span>
                                    <?php else:?>
                                        <span class="new">Good</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <div class="product-category">
                                    <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php $row->kategori_produk;?></a>
                                </div>
                                <h2><a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><?php echo $row->nama_produk;?></a></h2>
                                <div class="rating-result" title="100%">
                                    <span>
                                        <span>100%</span>
                                    </span>
                                </div> 
                                <div class="product-price">
                                <?php if($row->harga_diskon > 0): ?>
                                    <span>Rp <?php echo number_format($row->harga_diskon,0,'.','.');?></span>
                                    <span class="old-price">Rp <?php echo number_format($row->harga_jual_online,0,'.','.');?></span>
                                <?php else: ?>
                                    <span>Rp <?php echo number_format($row->harga_jual_online,0,'.','.');?></span>
                                <?php endif; ?>
                                </div>
                                <div class="product-action-1 show">
                                    <a aria-label="Add To Cart" class="action-btn hover-up" onclick="addToCart('<?php echo $row->id_produk;?>')"><i class="fi-rs-shopping-bag-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </section>
        <section class="deals section-padding">
            <div class="container">
                <div class="row">
                    <?php for($i=0;$i<2;$i++):?>
                    <?php if(!empty($promosiHariIni[$i])):?>
                        <div class="col-lg-6 deal-co">
                            <div class="deal wow fadeIn animated mb-md-4 mb-sm-4 mb-lg-0" style="background-image: url(&#x27;<?php echo base_url().$promosiHariIni[$i]->file_promosi;?>&#x27;);">
                                <div class="deal-top">
                                    <h2 class="text-brand"><?php echo $promosiHariIni[$i]->judul_promosi;?></h2>
                                </div>
                                <div class="deal-bottom">
                                    <p><?php echo $promosiHariIni[$i]->text_promosi;?></p>
                                    <div class="deals-countdown" id="countDown<?php echo $i;?>" data-countdown="<?php echo DATE("Y/m/d",strtotime($promosiHariIni[$i]->waktu_promosi));?>"></div>
                                    <a href="<?php echo base_url().$promosiHariIni[$i]->url_promosi;?>" class="btn hover-up">Lihat Promosi <i class="fi-rs-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php else:?>
                        <div class="col-lg-6 deal-co">
                            <div class="deal wow fadeIn animated mb-md-4 mb-sm-4 mb-lg-0" style="background-image: url(&#x27;<?php echo base_url('assets/frontend2/cooming-soon-5.png');?>&#x27;);">
                            </div>
                        </div>
                    <?php endif;?>
                    <?php endfor;?>
                </div>
            </div>
        </section>
        <section class="section-padding">
            <div class="container pb-20">
                <h3 class="section-title mb-20 wow fadeIn animated"><span>Brand</span> Pilihan</h3>
                <div class="carausel-6-columns-cover position-relative wow fadeIn animated">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-3-arrows"></div>
                    <div class="carausel-6-columns text-center" id="carausel-6-columns-3">
                        <?php foreach($brand as $key):?>
                        <div class="brand-logo">
                            <a href="<?php echo base_url('produk/brand/'.url_title($key->nama_brand,'dash',true));?>"><img src="<?php echo base_url().$key->gambar_brand;?>" alt></a>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-grey-9 section-padding">
            <div class="container pt-15 pb-25">
                <div class="heading-tab d-flex">
                    <div class="heading-tab-left wow fadeIn animated">
                        <h3 class="section-title mb-20"><span>Paling</span> Dicari</h3>
                    </div>
                    <div class="heading-tab-right wow fadeIn animated">
                        <ul class="nav nav-tabs right no-border" id="myTab-1" role="tablist">
                            <?php $x=1; foreach($kategori_produk as $key):?>
                            <li class="nav-item" role="presentation">
                                <?php if($x==1):?>
                                    <button class="nav-link active" id="nav-tab-<?php echo $key->id_kategori_produk;?>" data-bs-toggle="tab" data-bs-target="#tab-two-<?php echo $key->id_kategori_produk;?>" type="button" role="tab" aria-controls="tab-<?php echo $key->id_kategori_produk;?>" aria-selected="true"><?php echo $key->nama_kategori_produk;?></button>
                                <?php else:?>
                                    <button class="nav-link" id="nav-tab-<?php echo $key->id_kategori_produk;?>" data-bs-toggle="tab" data-bs-target="#tab-two-<?php echo $key->id_kategori_produk;?>" type="button" role="tab" aria-controls="tab-<?php echo $key->id_kategori_produk;?>" aria-selected="true"><?php echo $key->nama_kategori_produk;?></button>
                                <?php endif;?>
                            </li>
                            <?php $x++; endforeach;?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 d-none d-lg-flex">
                        <?php if(!empty($diskon[6])):?>
                            <div class="banner-img style-2 wow fadeIn animated">
                                <img src="<?php echo base_url().$diskon[6]->file_promosi;?>" alt>
                                <div class="banner-text">
                                    <span><?php echo $diskon[6]->judul_promosi;?></span>
                                    <h4 class="mt-5"><?php echo $diskon[6]->text_promosi;?></h4>
                                    <a href="<?php echo base_url().$diskon[6]->url_promosi;?>" class="text-white">Selengkapnya <i class="fi-rs-arrow-right"></i></a>
                                </div>
                            </div>
                        <?php else:?>
                            <div class="banner-img style-2 wow fadeIn animated">
                                <img src="<?php echo base_url('assets/frontend2/');?>cooming-soon-6.png" alt>
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="tab-content wow fadeIn animated" id="myTabContent-1">
                            <?php $x=1; foreach($kategori_produk as $key):?>
                            <?php if($x==1):?>
                                <div class="tab-pane fade show active" id="tab-two-<?php echo $key->id_kategori_produk;?>" role="tabpanel" aria-labelledby="tab-<?php echo $key->id_kategori_produk;?>">
                            <?php else:?>
                                <div class="tab-pane fade show" id="tab-two-<?php echo $key->id_kategori_produk;?>" role="tabpanel" aria-labelledby="tab-<?php echo $key->id_kategori_produk;?>">
                            <?php endif;?>
                            <?php $getProduk = $this->GeneralModel->limit_by_multi_id_general_order_by('v_produk', 'kategori_produk', $key->id_kategori_produk, 'tampil_toko', 'Y', 'id_produk', 'DESC', 6);?>
                                <div class="carausel-4-columns-cover arrow-center position-relative">
                                    <div class="slider-arrow slider-arrow-2 carausel-4-columns-arrow" id="carausel-4-columns-<?php echo $key->id_kategori_produk;?>-arrows"></div>
                                        <div class="carausel-4-columns carausel-arrow-center" id="carausel-4-columns-<?php echo $key->id_kategori_produk;?>">
                                            <?php foreach($getProduk as $row):?>
                                                <div class="product-cart-wrap">
                                                    <div class="product-img-action-wrap">
                                                        <div class="product-img product-img-zoom">
                                                            <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">
                                                                <img class="default-img" src="<?php echo base_url().$row->foto_produk;?>" alt>
                                                                <img class="hover-img" src="<?php echo base_url().$row->foto_produk_2;?>" alt>
                                                            </a>
                                                        </div>
                                                        <div class="product-action-1">
                                                            <a aria-label="Quick view" class="action-btn hover-up" onclick="detailProduk('<?php echo $row->id_produk;?>')"><i class="fi-rs-eye"></i></a>
                                                            
                                                            <a aria-label="Compare" class="action-btn hover-up" href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><i class="fi-rs-shuffle"></i></a>
                                                        </div>
                                                        <div class="product-badges product-badges-position product-badges-mrg">
                                                            <?php if($row->harga_diskon > 0): ?>
                                                            <?php
                                                                $selisih_harga = $row->harga_jual_online - $row->harga_diskon;
                                                                $percent = ($selisih_harga / $row->harga_jual_online * 100);
                                                            ?>
                                                                <span class="hot">-<?php echo number_format($percent,0,'.','.');?>%</span>
                                                            <?php else:?>
                                                                <span class="new">Good</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="product-content-wrap">
                                                        <div class="product-category">
                                                            <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>"><?php $row->kategori_produk;?></a>
                                                        </div>
                                                        <h2><a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><?php echo $row->nama_produk;?></a></h2>
                                                        <div class="rating-result" title="100%">
                                                            <span>
                                                                <span>100%</span>
                                                            </span>
                                                        </div> 
                                                        <div class="product-price">
                                                        <?php if($row->harga_diskon > 0): ?>
                                                            <span>Rp <?php echo number_format($row->harga_diskon,0,'.','.');?></span>
                                                            <span class="old-price">Rp <?php echo number_format($row->harga_jual_online,0,'.','.');?></span>
                                                        <?php else: ?>
                                                            <span>Rp <?php echo number_format($row->harga_jual_online,0,'.','.');?></span>
                                                        <?php endif; ?>
                                                        </div>
                                                        <div class="product-action-1 show">
                                                            <a aria-label="Add To Cart" class="action-btn hover-up" onclick="addToCart('<?php echo $row->id_produk;?>')"><i class="fi-rs-shopping-bag-add"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            <?php $x++; endforeach;?>
                        <!--End tab-content-->
                    </div>
                    <!--End Col-lg-9-->
                </div>
            </div>
        </section>
        <!-- <section class="section-padding">
            <div class="container pt-25 pb-20">
                <div class="row">
                    <div class="col-lg-6">
                        <h3 class="section-title mb-20"><span>From</span> blog</h3>
                        <div class="post-list mb-4 mb-lg-0">
                            <article class="wow fadeIn animated">
                                <div class="d-md-flex d-block">
                                    <div class="post-thumb d-flex mr-15">
                                        <a class="color-white" href="blog-post-fullwidth.html">
                                            <img src="<?php echo base_url('assets/frontend2/');?>images/blog-2.jpg" alt>
                                        </a>
                                    </div>
                                    <div class="post-content">
                                        <div class="entry-meta mb-10 mt-10">
                                            <a class="entry-meta meta-2" href="blog-category-fullwidth.html"><span class="post-in font-x-small">Fashion</span></a>
                                        </div>
                                        <h4 class="post-title mb-25 text-limit-2-row">
                                            <a href="blog-post-fullwidth.html">Qualcomm is developing a Nintendo Switch-like console, report says</a>
                                        </h4>
                                        <div class="entry-meta meta-1 font-xs color-grey mt-10 pb-10">
                                            <div>
                                                <span class="post-on">14 April 2022</span>
                                                <span class="hit-count has-dot">12M Views</span>
                                            </div>
                                            <a href="blog-post-right.html">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="wow fadeIn animated">
                                <div class="d-md-flex d-block">
                                    <div class="post-thumb d-flex mr-15">
                                        <a class="color-white" href="blog-post-fullwidth.html">
                                            <img src="<?php echo base_url('assets/frontend2/');?>images/blog-1.jpg" alt>
                                        </a>
                                    </div>
                                    <div class="post-content">
                                        <div class="entry-meta mb-10 mt-10">
                                            <a class="entry-meta meta-2" href="blog-category-fullwidth.html"><span class="post-in font-x-small">Healthy</span></a>
                                        </div>
                                        <h4 class="post-title mb-25 text-limit-2-row">
                                            <a href="blog-post-fullwidth.html">Not even the coronavirus can derail 5G's global momentum</a>
                                        </h4>
                                        <div class="entry-meta meta-1 font-xs color-grey mt-10 pb-10">
                                            <div>
                                                <span class="post-on">14 April 2022</span>
                                                <span class="hit-count has-dot">12M Views</span>
                                            </div>
                                            <a href="blog-post-right.html">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="banner-img banner-1 wow fadeIn animated">
                                    <img src="<?php echo base_url('assets/frontend2/');?>images/banner-5.jpg" alt>
                                    <div class="banner-text">
                                        <span>Accessories</span>
                                        <h4>Save 17% on <br>Autumn Hat</h4>
                                        <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>">Shop Now <i class="fi-rs-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="banner-img mb-15 wow fadeIn animated">
                                    <img src="<?php echo base_url('assets/frontend2/');?>images/banner-6.jpg" alt>
                                    <div class="banner-text">
                                        <span>Big Offer</span>
                                        <h4>Save 20% on <br>Women's socks</h4>
                                        <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>">Shop Now <i class="fi-rs-arrow-right"></i></a>
                                    </div>
                                </div>
                                <div class="banner-img banner-2 wow fadeIn animated mb-0">
                                    <img src="<?php echo base_url('assets/frontend2/');?>images/banner-7.jpg" alt>
                                    <div class="banner-text">
                                        <span>Smart Offer</span>
                                        <h4>Save 20% on <br>Eardrop</h4>
                                        <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>">Shop Now <i class="fi-rs-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <section class="mb-50">
            <div class="container pt-50">
                <div class="row">
                    <?php if(!empty($diskon[7])):?>
                    <div class="col-12">
                        <div class="banner-bg wow fadeIn animated" style="background-image: url(&#x27;<?php echo base_url().$diskon[7]->file_promosi;?>&#x27;)">
                            <div class="banner-content">
                                <h5 class="text-grey-4 mb-15"><?php echo $diskon[7]->judul_promosi;?></h5>
                                <h2 class="fw-600"><?php echo $diskon[7]->text_promosi;?></h2>
                            </div>
                        </div>
                    </div>
                    <?php else:?>
                    <div class="col-12">
                        <div class="banner-bg wow fadeIn animated" style="background-image: url(&#x27;<?php echo base_url('assets/frontend2/');?>cooming-soon-7.png&#x27;)">
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </section>
        <!-- <section class="mb-45">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-sm-5 mb-md-0">
                        <div class="banner-img wow fadeIn animated mb-md-4 mb-lg-0">
                            <img src="<?php echo base_url('assets/frontend2/');?>images/banner-10.jpg" alt>
                            <div class="banner-text">
                                <span>Shoes Zone</span>
                                <h4>Save 17% on <br>All Items</h4>
                                <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,'dash',true));?>">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-sm-5 mb-md-0">
                        <h4 class="section-title style-1 mb-30 wow fadeIn animated">Deals & Outlet</h4>
                        <div class="product-list-small wow fadeIn animated">
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-3.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Fish Print Patched T-shirt</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-4.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Vintage Floral Print Dress</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-5.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Multi-color Stripe Circle Print T-Shirt</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-sm-5 mb-md-0">
                        <h4 class="section-title style-1 mb-30 wow fadeIn animated">Top Selling</h4>
                        <div class="product-list-small wow fadeIn animated">
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-6.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Geometric Printed Long Sleeve Blosue</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-7.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Print Patchwork Maxi Dress</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-8.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Daisy Floral Print Straps Jumpsuit</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title style-1 mb-30 wow fadeIn animated">Hot Releases</h4>
                        <div class="product-list-small wow fadeIn animated">
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-9.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Floral Print Casual Cotton Dress</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-1.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Ruffled Solid Long Sleeve Blouse</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                            <article class="row align-items-center">
                                <figure class="col-md-4 mb-0">
                                    <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>"><img src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-2.jpg" alt></a>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h4 class="title-small">
                                        <a href="<?php echo base_url('produk/item/'.url_title($row->nama_toko,"dash",true).'/'.$row->slug_produk.'/'.$row->id_produk);?>">Multi-color Print V-neck T-Shirt</a>
                                    </h4>
                                    <div class="product-price">
                                        <span>$238.85 </span>
                                        <span class="old-price">$245.8</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
    </main>