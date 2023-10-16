<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="<?php echo base_url();?>"><img src="<?php echo base_url().$appsProfile->logo;?>" alt="logo"></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="<?php echo base_url('produk/pencarian');?>">
                    <input type="text" name="nama_produk" placeholder="Cari kebutuhan kamu disini...">
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">
                <div class="main-categori-wrap mobile-header-border">
                    <a class="categori-button-active-2" href="#">
                        <span class="fi-rs-apps"></span> Kategori & Brand
                    </a>
                    <div class="categori-dropdown-wrap categori-dropdown-active-small">
                        <ul>
                            <?php foreach($kategori_produk as $key):?>
                                <li><a href="<?php echo base_url('produk/kategori/'.url_title($key->nama_kategori_produk,"dash",true));?>"><i class="<?php echo $key->icon_kategori_produk;?>"></i> <?php echo strtoupper($key->nama_kategori_produk);?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu">
                        <?php foreach ($navigasiMenu as $key):?>
                            <?php if($key->nama_menu == 'Profile' && $this->session->userdata('LoggedIN') == FALSE):?>
                            <?php elseif($key->nama_menu == 'Registrasi' && $this->session->userdata('LoggedIN') == TRUE):?>
                            <?php else:?>
                                <li><a class="<?php if($title == $key->nama_menu) echo 'active';?>" href="<?php echo base_url().$key->url;?>"><?php echo $key->nama_menu;?></a></li>
                            <?php endif;?>
                        <?php endforeach;?>
                        <li class="menu-item-has-children"><span class="menu-expand"></span><a href="#">Kategori & Brand</a>
                            <ul class="dropdown">
                                <?php foreach($kategori_produk as $key):?>
                                <?php $getBrand = $this->GeneralModel->get_by_id_general('ms_brand','kategori',$key->id_kategori_produk);?>
                                <li class="menu-item-has-children"><span class="menu-expand"></span><a href="#"><?php echo $key->nama_kategori_produk;?></a>
                                    <ul class="dropdown">
                                        <?php foreach ($getBrand as $row) :?>
                                            <li><a  href="<?php echo base_url('produk/brand/'.url_title($row->nama_brand,'dash',true));?>"><?php echo $row->nama_brand;?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-header-info-wrap mobile-header-border">
                <div class="single-mobile-header-info mt-30">
                    <a href="#"><?php echo $appsProfile->agency;?></a>
                </div>
                <div class="single-mobile-header-info">
                    <?php if($this->session->userdata('LoggedIN') == TRUE):?>
                        <?php if($this->session->userdata('hak_akses') != 'member'):?>
                                <a href="<?php echo base_url('panel/dashboard');?>"><i class="fi-rs-computer"></i> Panel</a>
                        <?php endif;?>
                        <a href="<?php echo base_url('user/profile');?>"><i class="fi-rs-user"></i> <?php echo $this->session->userdata('nama_lengkap');?></a>
                        <a href="<?php echo base_url('auth/logout');?>"><i class="fi-rs-power"></i> Keluar</a>
                    <?php else:?>
                        <a href="#" onclick="login()"><i class="fi-rs-user"></i> Daftar / Masuk</a>
                    <?php endif;?>
                </div>
                <div class="single-mobile-header-info">
                    <a href="#"><?php echo $appsProfile->telephon;?></a>
                </div>
            </div>
            <div class="mobile-social-icon">
                <h5 class="mb-15 text-grey-4">Follow Us</h5>
                <a href="#"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-facebook.svg" alt></a>
                <a href="#"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-twitter.svg" alt></a>
                <a href="#"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-instagram.svg" alt></a>
                <a href="#"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-pinterest.svg" alt></a>
                <a href="#"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-youtube.svg" alt></a>
            </div>
        </div>
    </div>
</div>