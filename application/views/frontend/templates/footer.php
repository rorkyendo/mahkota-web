        <!-- BEGIN #policy -->
        <div id="policy" class="section-container bg-white">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-4 -->
                    <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                        <!-- BEGIN policy -->
                        <div class="policy">
                            <div class="policy-icon"><i class="fa fa-truck"></i></div>
                            <div class="policy-info">
                                <h4>Pengiriman keseluruh kota</h4>
                                <p>Kami mengirimkan barang kemana saja, dalam dan luar sumatra.</p>
                            </div>
                        </div>
                        <!-- END policy -->
                    </div>
                    <!-- END col-4 -->
                    <!-- BEGIN col-4 -->
                    <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                        <!-- BEGIN policy -->
                        <div class="policy">
                            <div class="policy-icon"><i class="fa fa-umbrella"></i></div>
                            <div class="policy-info">
                                <h4>Produk Bergaransi</h4>
                                <p>Kami memberikan garansi untuk setiap pembelian produk yang anda beli.</p>
                            </div>
                        </div>
                        <!-- END policy -->
                    </div>
                    <!-- END col-4 -->
                    <!-- BEGIN col-4 -->
                    <div class="col-lg-4 col-md-4">
                        <!-- BEGIN policy -->
                        <div class="policy">
                            <div class="policy-icon"><i class="fa fa-user-md"></i></div>
                            <div class="policy-info">
                                <h4>Jaminan Produk</h4>
                                <p>Kami memberikan jaminan keaslian untuk setiap produk yang kami jual.</p>
                            </div>
                        </div>
                        <!-- END policy -->
                    </div>
                    <!-- END col-4 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #policy -->

        <!-- BEGIN #subscribe -->
        <div id="subscribe" class="section-container">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-6 -->
                    <div class="col-md-6 mb-4 mb-md-0">
                        <!-- BEGIN subscription -->
                        <div class="subscription">
                            <div class="subscription-intro">
                                <h4> TERHUBUNG DENGAN KAMI</h4>
                                <p>Dapatkan update untuk setiap promo ditoko kami</p>
                            </div>
                        </div>
                        <!-- END subscription -->
                    </div>
                    <!-- END col-6 -->
                    <!-- BEGIN col-6 -->
                    <div class="col-md-6">
                        <!-- BEGIN social -->
                        <div class="social">
                            <div class="social-intro">
                                <h4>IKUTI KAMI</h4>
                                <p>Ikuti akun sosial media kami dan dapatkan update promo terbaru!</p>
                            </div>
                            <div class="social-list">
                                <a href="<?php echo $appsProfile->facebook;?>"><i class="fab fa-facebook"></i></a>
                                <a href="<?php echo $appsProfile->twitter;?>"><i class="fab fa-twitter"></i></a>
                                <a href="<?php echo $appsProfile->instagram;?>"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <!-- END social -->
                    </div>
                    <!-- END col-6 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #subscribe -->

        <!-- BEGIN #footer -->
        <div id="footer" class="footer">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-3 -->
                    <div class="col-lg-6">
                        <h4 class="footer-header">Tentang Kami</h4>
                        <p>
                            <?php echo $appsProfile->about_us;?>
                        </p>
                    </div>
                    <!-- END col-3 -->
                    <!-- BEGIN col-3 -->
                    <div class="col-lg-6">
                        <h4 class="footer-header">Kontak Kami</h4>
                        <address class="mb-lg-4 mb-0">
                            <strong><?php echo $appsProfile->agency;?></strong><br />
                            <?php echo $appsProfile->address;?>
                            <abbr title="Phone">Phone:</abbr> <?php echo $appsProfile->telephon;?><br />
                            <abbr title="Fax">Fax:</abbr> <?php echo $appsProfile->fax;?><br />
                            <abbr title="Email">Email:</abbr> <a
                                href="mailto:<?php echo $appsProfile->email;?>"><?php echo $appsProfile->email;?></a><br />
                        </address>
                    </div>
                    <!-- END col-3 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #footer -->

        <!-- BEGIN #footer-copyright -->
        <div id="footer-copyright" class="footer-copyright">
            <!-- BEGIN container -->
            <div class="container">
                <div class="payment-method">
                    <img src="<?php echo base_url('assets/');?>img/payment/payment-method.png" alt="" />
                </div>
                <div class="copyright">
                    Copyright &copy; 2022 <?php echo $appsProfile->apps_name;?>. All rights reserved.
                </div>
            </div>
            <!-- END container -->
        </div>
        <!-- END #footer-copyright -->
    </div>
    <!-- END #page-container -->

    <!-- begin theme-panel -->
    <?php if($this->session->userdata('LoggedIN') == TRUE): ?>
        <div class="theme-panel">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-arrow-alt-circle-left"></i></a>
            <div class="theme-panel-content">
                <ul class="fa-ul">
                    <li class=" mt-3"><i class="fa fa-li fa-angle-right"></i> <a href="<?php echo base_url('user/profile');?>" class="text-black"> Profile</a></li>
                    <li><i class="fa fa-li fa-angle-right"></i> <a href="<?php echo base_url('user/profile');?>" class="text-black"> Membership</a></li>
                    <li><i class="fa fa-li fa-angle-right"></i> <a href="<?php echo base_url('auth/logout');?>" class="text-black"> Logout</a></li>
                </ul>
            </div>
        </div>
    <?php else:?>
        <div class="theme-panel">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-sign-in-alt"></i></a>
            <div class="theme-panel-content">
                <ul class="fa-ul">
                    <li class=" mt-3"><i class="fa fa-li fa-angle-right"></i> <a href="#" data-toggle="modal" data-target="#login" class="text-black"> Masuk</a></li>
                    <li><i class="fa fa-li fa-angle-right"></i> <a href="<?php echo base_url('auth/register/');?>" class="text-black"> Daftar</a></li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <!-- end theme-panel -->
	<script>
            $('.responsive').slick({
                dots: true,
                autoplay:true,
                infinite: false,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        centerMode:true,
                        centerPadding:'40px',
                        dots: true
                    }
                    },
                    {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        centerMode:true,
                        centerPadding:'40px',
                        slidesToScroll: 2
                    }
                    },
                    {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        centerMode:true,
                        centerPadding:'30px',
                        slidesToScroll: 2
                    }
                    }
                ]
        })

	    function onlyNumberKey(evt) {
	        var theUjian = evt || window.event;

	        // Handle paste
	        if (theUjian.type === 'paste') {
	            key = event.clipboardData.getData('text/plain');
	        } else {
	            // Handle key press
	            var key = theUjian.keyCode || theUjian.which;
	            key = String.fromCharCode(key);
	        }
	        var regex = /[0-9]|\./;
	        if (!regex.test(key)) {
	            theUjian.returnValue = false;
	            if (theUjian.preventDefault) theUjian.preventDefault();
	        }
	    }
	</script>
    </body>
</html>