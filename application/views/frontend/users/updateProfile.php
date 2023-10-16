<?php foreach($profile as $key):?>
<!-- BEGIN #my-account -->
<div id="about-us-cover" class="section-container">
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN account-container -->
        <div class="account-container">
            <!-- BEGIN account-body -->
            <div class="account-body">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-6 -->
                    <div class="col-md-8">
                        <h4><i class="fa fa-user text-primary"></i> Update Profile</h4>
                        <?php echo $this->session->flashdata('notif');?>
                        <form action="<?php echo base_url('user/updateProfile/doUpdate');?>" enctype="multipart/form-data" method="POST">
                            <h4 class="text-center">Foto Pengguna</h4>
                            <center>
                                <?php if (!empty($row->foto_pengguna)) : ?>
                                <img src="<?php echo base_url() . $row->foto_pengguna; ?>"
                                    class="img-responsive" alt="preview" id="preview"
                                    style="height:150px">
                                <?php else : ?>
                                <img src="<?php echo base_url('assets/img/user.png'); ?>"
                                    class="img-responsive" alt="preview" id="preview"
                                    style="height:120px">
                                <?php endif; ?>
                            </center>
                            <br />
                            <label class="control-label">Foto Pengguna</label>
                            <div class="row row-space-10">
                                <div class="col-md-12 m-b-15">
                                    <input type="file" name="foto_pengguna" class="form-control" id="foto_pengguna" accept="image/*" />
                                </div>
                            </div>
                            <script type="text/javascript">
                                function readURL(input) {
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        reader.onload = function (e) {
                                            $('#preview').attr('src', e.target.result);
                                        }
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                                $("#foto_pengguna").change(function () {
                                    readURL(this);
                                });
                            </script>
                            <label class="control-label">No HP/WA</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="no_telp" value="<?php echo $key->no_telp; ?>" onkeypress="onlyNumberKey(this.event)" placeholder="No HP/WA" disabled/>
                                </div>
                            </div>
                            <label class="control-label">Username</label>
                            <div class="row row-space-10">
                                <div class="col-md-12 m-b-15">
                                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $key->username;?>"/>
                                </div>
                            </div>
                            <label class="control-label">Email</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="email" class="form-control" name="email" value="<?php echo $key->email; ?>" placeholder="Email address"/>
                                </div>
                            </div>
                            <label class="control-label">Nama Lengkap</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="nama_lengkap" value="<?php echo $key->nama_lengkap; ?>" placeholder="Nama Lengkap"/>
                                </div>
                            </div>
                            <label class="control-label">Tgl Lahir</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="date" class="form-control" name="tgl_lahir" value="<?php echo $key->tgl_lahir; ?>" placeholder="Tgl Lahir"/>
                                </div>
                            </div>
                            <label class="control-label">Jenkel</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <select name="jenkel" id="jenkel" class="form-control">
                                        <option value="">.:Pilih Jenkel:.</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <script>
                                    $('#jenkel').val('<?php echo $key->jenkel;?>')
                                </script>
                            </div>
                            <div class="form-footer">
                                <button type="submit" id="btnSimpan" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- END col-6 -->
                    <!-- BEGIN col-6 -->
                    <div class="col-md-4">
                        <h4><i class="fa fa-universal-access fa-fw text-primary"></i> Pengaturan Akun</h4>
                        <ul class="nav nav-list">
                            <li><a href="<?php echo base_url('user/profile');?>">Profile</a></li>
                            <li><a href="<?php echo base_url('user/updatePassword');?>">Update PIN</a></li>
                            <li><a href="<?php echo base_url('user/address');?>">Tambah Alamat penerima</a></li>
                            <li><a href="<?php echo base_url('user/deleteAccount');?>">Hapus Akun</a></li>
                        </ul>
                    </div>
                    <!-- END col-6 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END account-body -->
        </div>
        <!-- END account-container -->
    </div>
    <!-- END container -->
</div>
<!-- END #about-us-cover -->
<?php endforeach;?>
