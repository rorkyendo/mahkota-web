<header class="header-area header-style-3 header-height-2">
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-4">
                    <div class="header-info">
                        <ul>
                            <li><i class="fi-rs-smartphone"></i> <a href="#"><?php echo $appsProfile->telephon;?></a></li>
                            <li><i class="fi-rs-marker"></i><a href="#"><?php echo $appsProfile->agency;?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-4">
                    <div class="text-center">
                        <div id="news-flash" class="d-inline-block">
                            <ul>
                                <?php foreach($text as $key):?>
                                    <li><a href="<?php echo base_url().$key->url_promosi;?>"><?php echo $key->judul_promosi;?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="header-info header-info-right">
                        <ul>
                            <?php if($this->session->userdata('LoggedIN') == TRUE):?>
                                <?php if($this->session->userdata('hak_akses') != 'member'):?>
                                    <li>
                                        <a href="<?php echo base_url('panel/dashboard');?>"><i class="fi-rs-computer"></i> Panel</a>
                                    </li>
                                <?php endif;?>
                                <li><i class="fi-rs-user"></i><a href="<?php echo base_url('user/profile');?>"><?php echo $this->session->userdata('nama_lengkap');?></a></li>
                                <li><i class="fi-rs-power"></i><a href="<?php echo base_url('auth/logout');?>">Keluar</a></li>
                            <?php else:?>
                                <li><i class="fi-rs-user"></i><a href="#" onclick="login()">Daftar / Masuk</a></li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a href="<?php echo base_url();?>"><img src="<?php echo base_url().$appsProfile->logo;?>" alt="logo"></a>
                </div>
                <div class="header-right">
                    <div class="search-style-2">
                        <form action="<?php echo base_url('produk/pencarian');?>">
                            <select class="select-active" name="kategori">
                                <option value="">Semua Kategori</option>
                                <?php foreach($kategori_produk as $key):?>
                                    <option value="<?php echo $key->nama_kategori_produk;?>"><?php echo $key->nama_kategori_produk;?></option>
                                <?php endforeach;?>
                            </select>
                            <input type="text" name="nama_produk" placeholder="Cari kebutuhan kamu disini...">
                        </form>
                    </div>
                    <div class="header-action-right">
                        <div class="header-action-2">
                            <div class="header-action-icon-2">
                                <a class="mini-cart-icon" href="<?php echo base_url('transaksi/cart');?>">
                                    <img alt="Keranjang" src="<?php echo base_url('assets/frontend2/');?>fonts/icon-cart.svg">
                                    <span class="pro-count blue"><?php echo number_format(COUNT($countOrder),0,'.','.');?></span>
                                </a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                    <ul>
                                        <?php $total=0; foreach($countOrder as $key):?>
                                        <li>
                                            <div class="shopping-cart-img">
                                                <a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->produk);?>"><img alt="<?php echo $key->nama_produk;?>" src="<?php echo base_url().$key->foto_produk;?>"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->produk);?>"><?php echo $key->nama_produk;?></a></h4>
                                                <h3><span><?php echo number_format($key->qty,0,'.','.');?> × </span>Rp <?php echo number_format($key->selling_price,0,'.','.');?></h3>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#" onclick="removeFromCart('<?php echo $key->temp_transaksi;?>','<?php echo $key->id_order;?>')"><i class="fi-rs-cross-small"></i></a>
                                            </div>
                                        </li>
                                        <?php 
                                        $total += $key->qty*$key->selling_price;
                                        endforeach;?>
                                    </ul>
                                    <div class="shopping-cart-footer">
                                        <div class="shopping-cart-total">
                                            <h4>Total <span>Rp <?php echo number_format($total,0,'.','.');?></span></h4>
                                        </div>
                                        <div class="shopping-cart-button">
                                            <a href="<?php echo base_url('transaksi/cart');?>" class="outline">Lihat Keranjang</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container">
            <div class="header-wrap header-space-between position-relative  main-nav">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="<?php echo base_url();?>"><img src="<?php echo base_url().$appsProfile->logo;?>" alt="logo"></a>
                </div>
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-categori-wrap d-none d-lg-block">
                        <a class="categori-button-active" href="#">
                            <span class="fi-rs-apps"></span> Kategori & Brand
                        </a>
                        <div class="categori-dropdown-wrap categori-dropdown-active-large">
                            <ul>
                                <?php foreach ($kategori_produk as $key):?>
                                    <?php $getBrand = $this->GeneralModel->get_by_id_general('ms_brand','kategori',$key->id_kategori_produk);?>
                                <li class="has-children">
                                    <a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,"dash",true));?>"><i class="<?php echo $key->icon_kategori_produk;?>"></i> <?php echo strtoupper($key->nama_kategori_produk);?></a>
                                    <div class="dropdown-menu">
                                        <ul class="mega-menu d-lg-flex">
                                            <li class="mega-menu-col col-lg-7">
                                                <ul class="d-lg-flex">
                                                    <li class="mega-menu-col col-lg-6">
                                                        <ul>
                                                            <li><span class="submenu-title">Hot & Trending</span></li>
                                                            <?php foreach ($getBrand as $row) :?>
                                                                <li><a class="dropdown-item nav-link nav_item" href="<?php echo base_url('produk/brand/'.url_title($row->nama_brand,'dash',true));?>"><?php echo $row->nama_brand;?></a></li>
                                                            <?php endforeach;?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block">
                        <nav>
                            <ul>
                                <?php foreach ($navigasiMenu as $key):?>
                                    <?php if($key->nama_menu == 'Profile' && $this->session->userdata('LoggedIN') == FALSE):?>
                                    <?php elseif($key->nama_menu == 'Registrasi' && $this->session->userdata('LoggedIN') == TRUE):?>
                                    <?php else:?>
                                        <li><a class="<?php if($title == $key->nama_menu) echo 'active';?>" href="<?php echo base_url().$key->url;?>"><?php echo $key->nama_menu;?></a></li>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="hotline d-none d-lg-block">
                    <p><i class="fi-rs-headset"></i><span>Hubungi Kami</span> <?php echo $appsProfile->telephon;?> </p>
                </div>
                <p class="mobile-promotion">Happy <span class="text-brand">Mother's Day</span>. Big Sale Up to 40%</p>
                <div class="header-action-right d-block d-lg-none">
                    <div class="header-action-2">
                        <div class="header-action-icon-2">
                            <a class="mini-cart-icon" href="<?php echo base_url('transaksi/cart');?>">
                                <img alt="Evara" src="<?php echo base_url('assets/frontend2/');?>fonts/icon-cart.svg">
                                <span class="pro-count white"><?php echo number_format(COUNT($countOrder),0,'.','.');?></span>
                            </a>
                            <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                <ul>
                                    <?php $total=0;foreach($countOrder as $key):?>
                                    <li>
                                        <div class="shopping-cart-img">
                                            <a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->produk);?>"><img alt="<?php echo $key->nama_produk;?>" src="<?php echo base_url('assets/frontend2/');?>images/thumbnail-3.jpg"></a>
                                        </div>
                                        <div class="shopping-cart-title">
                                            <h4><a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->produk);?>"><?php echo $key->nama_produk;?></a></h4>
                                            <h3><span><?php echo number_format($key->qty,0,'.','.');?> × </span>Rp <?php echo number_format($key->selling_price,0,'.','.');?></h3>
                                        </div>
                                        <div class="shopping-cart-delete">
                                            <a href="#" onclick="removeFromCart('<?php echo $key->temp_transaksi;?>','<?php echo $key->id_order;?>')"><i class="fi-rs-cross-small"></i></a>
                                        </div>
                                    </li>
                                    <?php 
                                        $total += $key->qty * $key->selling_price;
                                        endforeach;
                                    ?>
                                </ul>
                                <div class="shopping-cart-footer">
                                    <div class="shopping-cart-total">
                                        <h4>Total <span>Rp <?php echo number_format($total,0,'.','.');?></span></h4>
                                    </div>
                                    <div class="shopping-cart-button">
                                        <a href="<?php echo base_url('transaksi/cart');?>">Lihat Keranjang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-action-icon-2 d-block d-lg-none">
                            <div class="burger-icon burger-icon-white">
                                <span class="burger-icon-top"></span>
                                <span class="burger-icon-mid"></span>
                                <span class="burger-icon-bottom"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>