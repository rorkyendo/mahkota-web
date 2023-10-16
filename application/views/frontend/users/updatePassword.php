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
                        <h4><i class="fa fa-key text-primary"></i> Update PIN</h4>
                        <?php echo $this->session->flashdata('notif');?>
                        <form action="<?php echo base_url('user/updatePassword/doUpdate');?>" method="POST">
                        <label class="control-label">PIN <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" class="form-control" onkeypress="onlyNumberKey(event)" minlength="6" maxlength="6" name="password" value="<?php echo set_value('password'); ?>" placeholder="PIN" id="pass" onkeyup="cekPassword()" required />
                                <?php echo form_error('password'); ?>
                            </div>
                        </div>
                        <label class="control-label">Ulangi PIN <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" class="form-control" onkeypress="onlyNumberKey(event)" minlength="6" maxlength="6" placeholder="Ulangi PIN" value="<?php echo set_value('re_password'); ?>" onkeyup="cekPassword()" id="re_pass" name="re_password" required />
                                <font color="red" id="notifrepass">
                                    <?php echo form_error('password'); ?>
                                </font>
                            </div>
                        </div>
                        <script type="text/javascript">
                            function cekPassword() {
                                var repass = $('#re_pass').val()
                                var pass = $('#pass').val()
                                if (repass != pass || pass != repass) {
                                    $('#notifrepass').prop('color', 'red');
                                    $('#notifrepass').text('Ulangi PIN tidak sama dengan PIN');
                                    $('#btnSimpan').attr('disabled', true);
                                } else {
                                    $('#notifrepass').prop('color', 'green');
                                    $('#notifrepass').text('Ulangi PIN sama dengan PIN');
                                    $('#btnSimpan').removeAttr('disabled');
                                }
                            }
                        </script>
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
                            <li><a href="<?php echo base_url('user/updateProfile');?>">Update Profile</a></li>
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
