<footer class="main">
    <section class="newsletter p-30 text-white wow fadeIn animated">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-9 mb-md-3 mb-lg-0">
                    <div class="row align-items-center">
                        <div class="col flex-horizontal-center">
                            <img class="icon-email" src="<?php echo base_url('assets/frontend2/');?>fonts/icon-email.svg" alt>
                            <h4 class="font-size-20 mb-0 ml-3">Daftar Sekarang Juga</h4>
                        </div>
                        <div class="col my-2 my-md-0 des">
                            <h5 class="font-size-15 ml-4 mb-0">Dapatkan berbagai penawaran menarik dan diskon meriah!!!</strong></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <a class="btn bg-dark text-white" href="<?php echo base_url('auth/register');?>">Daftar</a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding footer-mid">
        <div class="container pt-15 pb-20">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="widget-about font-md mb-md-5 mb-lg-0">
                        <div class="logo logo-width-1 wow fadeIn animated">
                            <a href="<?php echo base_url();?>"><img src="<?php echo base_url().$appsProfile->logo;?>" alt="logo"></a>
                        </div>
                        <h5 class="mt-20 mb-10 fw-600 text-grey-4 wow fadeIn animated">Kontak Kami</h5>
                        <p class="wow fadeIn animated">
                            <strong>Alamat: </strong><?php echo $appsProfile->address;?>
                        </p>
                        <p class="wow fadeIn animated">
                            <strong>No.Telp: </strong><?php echo $appsProfile->telephon;?>
                        </p>
                        <p class="wow fadeIn animated">
                            <strong>Buka: </strong>09:00 - 22:00, Senin - Minggu
                        </p>
                        <h5 class="mb-10 mt-30 fw-600 text-grey-4 wow fadeIn animated">Follow Us</h5>
                        <div class="mobile-social-icon wow fadeIn animated mb-sm-5 mb-md-0">
                            <a href="<?php echo $appsProfile->facebook;?>"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-facebook.svg" alt></a>
                            <a href="<?php echo $appsProfile->twitter;?>"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-twitter.svg" alt></a>
                            <a href="<?php echo $appsProfile->instagram;?>"><img src="<?php echo base_url('assets/frontend2/');?>fonts/icon-instagram.svg" alt></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="widget-title wow fadeIn animated">Install App</h5>
                    <div class="row">
                        <div class="col-md-8 col-lg-12">
                            <p class="wow fadeIn animated">From App Store or Google Play</p>
                            <div class="download-app wow fadeIn animated">
                                <a href="https://apps.apple.com/us/app/mahkota-store/id6443806299" class="hover-up mb-sm-4 mb-lg-0"><img class="active"
                                        src="<?php echo base_url('assets/frontend2/');?>images/app-store.jpg" alt></a>
                                <a href="https://play.google.com/store/apps/details?id=com.mahkotastore" class="hover-up"><img src="<?php echo base_url('assets/frontend2/');?>images/google-play.jpg" alt></a>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-12 mt-md-3 mt-lg-0">
                            <p class="mb-20 wow fadeIn animated">Secured Payment Gateways</p>
                            <img class="wow fadeIn animated" src="<?php echo base_url('assets/frontend2/');?>images/payment-method.png" alt>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container pb-20 wow fadeIn animated">
        <div class="row">
            <div class="col-12 mb-20">
                <div class="footer-bottom"></div>
            </div>
            <div class="col-lg-4">
                <p class="float-md-left font-sm text-muted mb-0">&copy; <?php echo DATE('Y');?>, <strong class="text-brand"><?php echo $appsProfile->apps_name;?></strong></p>
            </div>
            <div class="col-lg-8">
                <p class="text-lg-end text-start font-sm text-muted mb-0">
                    <?php echo $appsProfile->address;?>
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- Preloader Start -->
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

    function addToCart(produk){
        $.ajax({
            url:"<?php echo base_url('produk/addToChart/');?>"+produk,
            type:"POST",
            data:{
                qty:1
            },success:function(resp){
                if(resp!='false'){
                    Swal.fire(
                        'Berhasi!',
                        'Belanjaan anda berhasil ditambahkan ke keranjang belanja',
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

    function login(){
        $("#login").modal('show');
    }
</script>
<!-- Login Modal -->
<div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login</h5>
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
        $('#login').click('show');
    </script>
<?php endif; ?>
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
    <script>
        function detailProduk(idProduk){
            $.ajax({
                url:"<?php echo base_url('produk/detailProduk/');?>"+idProduk,
                type:"POST",
                success:function(resp){
                    $('#preview').html(resp);
                    $("#quickViewModal").modal('show')
                }
            })
        }
    </script>
    <div id="preview"></div>
</body>

</html>