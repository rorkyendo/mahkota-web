<?php foreach($profile as $row):?>
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
                        <h4><i class="fa fa-user text-primary"></i> Hapus Akun</h4>
                        <?php echo $this->session->flashdata('notif');?>
                        <form action="<?php echo base_url('user/deleteAccount/doDelete/');?>" method="POST">
                            <label class="control-label">Silahkan ketik <b>HAPUS AKUN</b> untuk menghapus akun anda</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="delete" placeholder="HAPUS AKUN" required/>
                                </div>
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
                            <li><a href="<?php echo base_url('user/updatePassword');?>">Update Password</a></li>
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
