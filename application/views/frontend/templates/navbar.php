        <!-- BEGIN #top-nav -->
        <div id="top-nav" class="top-nav">
            <!-- BEGIN container -->
            <div class="container">
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <?php foreach($menuAtas as $key):?>
                            <li><a href="<?php echo base_url().$key->url;?>"><?php echo $key->nama_menu;?></a></li>
                        <?php endforeach;?>
                    </ul>
                    <?php if($this->session->userdata('hak_akses') != 'member' && $this->session->userdata('LoggedIN') == TRUE): ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?php echo base_url('panel/dashboard');?>"><i class="fa fa-desktop"></i> Panel</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END container -->
        </div>
        <!-- END #top-nav -->
        <!-- BEGIN #header -->
        <div id="header" class="header">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN header-container -->
                <div class="header-container">
                    <!-- BEGIN navbar-toggle -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- END navbar-toggle -->
                    <!-- BEGIN header-logo -->
                    <div class="header-logo">
                        <a href="<?php echo base_url();?>">
                            <img src="<?php echo base_url().$appsProfile->icon;?>" style="width:50px;margin-left:10px;margin-right:10px;" class="d-none d-md-block d-lg-block img-responsive">
                            <span class="brand-text d-none d-md-block d-lg-block">
                                <?php echo $appsProfile->apps_name;?>
                            </span>
                        </a>
                    </div>
                    <!-- END header-logo -->
                    <!-- BEGIN header-nav -->
                    <div class="header-nav">
                        <div class=" collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav">
                                <?php foreach($navigasiMenu as $key):?>
                                    <?php if($key->url!='#'): ?>
                                        <li class="<?php if($title == 'Home'){ echo "active";}?> d-sm-block d-xs-block d-md-none"><a href="<?php echo base_url().$key->url;?>"><?php echo $key->nama_menu;?></a></li>
                                    <?php else: ?>
                                        <li class="dropdown dropdown-hover <?php if($title == $key->nama_menu){ echo "active";}?> d-sm-block d-xs-block d-md-none">
                                            <a href="<?php echo $key->url;?>" data-toggle="dropdown">
                                                <?php echo $key->nama_menu;?>
                                                <b class="caret"></b>
                                                <span class="arrow top"></span>
                                            </a>
                                            <div class="dropdown-menu">
                                                <?php $turunanMenu = $this->GeneralModel->get_by_id_general_order_by('ms_menu','menu_induk',$key->id,'urutan','ASC');?>
                                                <?php foreach($turunanMenu as $tm):?>
                                                    <a class="dropdown-item" href="<?php echo base_url().$tm->url;?>"><?php echo $tm->nama_menu;?></a>
                                                <?php endforeach;?>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach;?>
                                <li class="<?php if($title == 'Toko'){ echo "active";}?> d-sm-block d-xs-block d-md-none"><a
                                        href="<?php echo base_url('informasi/toko');?>">Toko</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form action="<?php echo base_url('produk/pencarian');?>" method="GET">
                            <div class="input-group mt-3">
                                <input type="text" placeholder="Cari di Mahkota Store" name="nama_produk" class="form-control bg-silver-lighter" />
                                <div class="input-group-append">
                                    <button class="btn btn-inverse" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END header-nav -->
                    <!-- BEGIN header-nav -->
                    <div class="header-nav">
                        <ul class="nav pull-right">
                            <li class="dropdown dropdown-hover">
                                <a href="<?php echo base_url('transaksi/cart');?>" class="header-cart" data-toggle="dropdown">
                                    <i class="fa fa-shopping-bag"></i>
                                    <span class="total"><?php echo number_format(COUNT($countOrder),0,'.','.');?></span>
                                    <span class="arrow top"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-cart p-0">
                                    <div class="cart-header">
                                        <h4 class="cart-title"><?php echo number_format(COUNT($countOrder),0,'.','.');?> Item dikeranjang belanja</h4>
                                    </div>
                                    <div class="cart-body">
                                        <ul class="cart-item">
                                            <?php $total=0; foreach($countOrder as $key):?>
                                            <li>
                                                <div class="cart-item-image">
                                                    <img src="<?php echo base_url().$key->foto_produk;?>" alt="" /></div>
                                                <div class="cart-item-info">
                                                    <span class="badge badge-secondary"><?php echo number_format($key->qty,0,'.','.');?></span>
                                                    <h4><?php echo $key->nama_produk;?></h4>
                                                    <del>
                                                        <i><p class="price">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></p></i>
                                                    </del>
                                                    <p class="price">Rp <?php echo number_format($key->selling_price,0,'.','.');?></p>
                                                    <?php $total+=$key->selling_price;?>
                                                </div>
                                                <div class="cart-item-close">
                                                    <a href="#" onclick="removeFromCart('<?php echo $key->temp_transaksi;?>','<?php echo $key->id_order;?>')" data-toggle="tooltip" data-title="Remove">&times;</a>
                                                </div>
                                            </li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                    <div class="cart-footer">
                                        <h6 class="cart-title">Total Belanja <span class="pull-right">Rp <?php echo number_format($total,0,'.','.');?></span></h6>
                                    </div>
                                    <script>
                                        function removeFromCart(tempTransaksi,id_order){
                                            $.ajax({
                                                url:"<?php echo base_url('produk/removeFromCart');?>",
                                                type:"POST",
                                                data:{
                                                    temp_transaksi:tempTransaksi,
                                                    id_order:id_order,
                                                },success:function(resp){
                                                    if(resp!='false'){
                                                        Swal.fire(
                                                            'Dihapus!',
                                                            'Belanjaan anda berhasil dihapus dari keranjang belanja',
                                                            'success'
                                                        ).then(function(){
                                                            location.reload();
                                                        })
                                                    }else{
                                                        Swal.fire(
                                                            'Gagal!',
                                                            'Terjadi Kesalahan',
                                                            'error'
                                                        );
                                                    }
                                                },error:function(){
                                                        Swal.fire(
                                                            'Gagal!',
                                                            'Terjadi Kesalahan',
                                                            'error'
                                                        );
                                                }
                                            })
                                        }
                                    </script>
                                    <div class="cart-footer">
                                        <div class="row row-space-10">
                                            <?php if($this->session->userdata("LoggedIN") == TRUE): ?>
                                            <div class="col-md-12">
                                                <a href="<?php echo base_url('transaksi/cart');?>"
                                                    class="btn btn-default btn-template btn-block">Lihat keranjang</a>
                                            </div>
                                            <?php else: ?>
                                            <div class="col-12">
                                                <a href="#" data-toggle="modal" data-target="#login"
                                                    class="btn btn-template btn-block">Daftar / Masuk</a>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="divider d-none d-md-block d-lg-block"></li>
                            <?php if($this->session->userdata('LoggedIN') == TRUE): ?>
                            <li class="dropdown dropdown-hover">
                                <a href="#" data-toggle="dropdown" class="d-none d-lg-block d-md-block">
                                    <b><?php echo $this->session->userdata('nama_lengkap');?></b>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?php echo base_url('user/profile');?>">Profile</a>
                                    <a class="dropdown-item" href="<?php echo base_url('auth/logout');?>">Logout</a>
                                </div>
                            </li>
                            <?php else: ?>
                            <li class="d-none d-md-block d-lg-block">
                                <a href="#" data-toggle="modal" data-target="#login">
                                    <span><i class="fa fa-sign-in-alt"></i></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- END header-nav -->
                </div>
                <!-- END header-container -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #header -->
        <!-- Login Modal -->
        <div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
					    <form method="post" action="<?php echo base_url('auth/login/do_login'); ?>">
                            <?php echo $this->session->flashdata('notif'); ?>
                            <div class="form-group">
                                <label for="no_telp">Nomor WA</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control input-line" onkeypress="onlyNumberKey(event)" placeholder="Masukkan Nomor WA" required="true"/>
                            </div>
                            <div class="form-group">
                                <label for="pin">PIN</label>
                                <input type="password" id="password" name="password" class="form-control input-line" placeholder="Masukkan PIN" minlength="6" maxlength="6" required="true" />
                            </div>
                            <small>Lupa pin? Silahkan <a href="<?php echo base_url('auth/forget');?>">klik disini</a> untuk reset pin</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo base_url('auth/register');?>" class="btn btn-secondary">Daftar</a>
                        <button type="submit" class="btn btn-primary">Masuk</button>
					</form>
                    </div>
                </div>
            </div>
        </div>

        <?php if($this->session->flashdata('wrong')): ?>
            <script>
                $('#login').modal('show');
            </script>
        <?php endif; ?>