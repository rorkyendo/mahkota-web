<main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="<?php echo base_url();?>" rel="nofollow">Home</a>
                    <span></span> <?php echo $title;?>
                </div>
            </div>
        </div>
        <section class="section-border pt-50 pb-50 bg-green">
            <div class="container">
                <div class="text-center">
                    <h4 class="text-brand mb-20">Selamat Datang 👋🏻</h4>
                    <h3 class="mb-20">
                        Kamu dapat bertanya seputar informasi produk <br/>dan layanan service <?php echo $appsProfile->agency;?> pada menu ini 😊
                    </h3>
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                        </div>
                        <div class="col-lg-8 col-md-8 mb-20">
                            <form action="<?php echo base_url('tiket/cariTiket');?>" method="post">
                                <div class="input-group">
                                        <input name="kode_tiket" class="col-7" placeholder="👩🏻‍💻 Masukkan Kode Tiket untuk mengecek status tiket kamu.." type="text" required>
                                        <button class="input-group-text btn-md" type="submit">Cari Tiket</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-3 col-md-3">
                        </div>
                        <?php if($this->session->userdata('LoggedIN') == TRUE):?>
                            <div class="col-md-12 mb-20">
                                <h6 class="text-center">Berhubung kamu sudah login ke aplikasi, kamu juga dapat melihat daftar riwayat tiket yang kamu buat <a href="<?php echo base_url('tiket/daftarTiket');?>">disini</a></h6>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </section>
        <section class="pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-10 m-auto">
                        <div class="contact-from-area padding-20-row-col wow FadeInUp">
                            <h3 class="mb-10 text-center">Masukkan informasi ✍🏻</h3>
                            <p class="text-muted mb-30 text-center font-sm">Silahkan masukkan informasi kamu agar kami dapat membalas pesan</p>
                            <form class="contact-form-style text-center" id="contact-form" action="<?php echo base_url('tiket/openTiket');?>" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <?php echo $this->session->flashdata('notif');?>
                                    <?php if($this->session->userdata('LoggedIN') == FALSE):?>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-style mb-20">
                                            <input name="nama_tiket" placeholder="Masukkan Nama Lengkap" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-style mb-20">
                                            <input name="email_tiket" placeholder="Masukkan Email" type="email" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-style mb-20">
                                            <input name="no_wa" placeholder="Masukkan No WA" onkeypress="onlyNumberKey(this.event)" type="text" required>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-style mb-20">
                                            <select name="kategori_tiket" id="kategori_tiket" class="form-control" required>
                                                <option value="">.:Pilih Kategori Pesan:.</option>
                                                <option value="Produk">Informasi Produk</option>
                                                <option value="Layanan dan Keluhan">Layanan dan Keluhan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-style mb-20">
                                            <h4 class="text-center">Preview Gambar</h4>
                                                <center>
                                                    <img src="<?php echo base_url().$appsProfile->logo; ?>" class="img-responsive" alt="preview_1" id="preview_1" style="height:150px">
                                                </center>
                                            <br />
                                            <script type="text/javascript">
                                                function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                                    reader.onload = function(e) {
                                                    $('#preview_1').attr('src', e.target.result);
                                                    }
                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                                }
                                                $(document).on('change','#lampiran_tiket',function() {
                                                    readURL(this);
                                                });
                                            </script>
                                            <font color="red">🤳🏻 Masukkan lampiran berupa gambar bila diperlukan</font>
                                            <input name="lampiran_tiket" id="lampiran_tiket" class="form-control" placeholder="Masukkan Lampiran File bila diperlukan" type="file" accept="image/png, image/jpg, image/jpeg">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="input-style mb-20">
                                            <input name="judul_tiket" placeholder="Masukkan Perihal Pesan" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="textarea-style mb-30">
                                            <textarea name="isi_tiket" placeholder="Masukkan isi pesan ✍🏻"></textarea>
                                        </div>
                                        <button class="submit submit-auto-width" type="submit">Send message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>