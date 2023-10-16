<?php foreach($profile as $key):?>
<!-- BEGIN #about-us-content -->
    <div class="section-container bg-white">
        <!-- BEGIN container -->
        <div class="container">
            <!-- BEGIN about-us-content -->
            <div class="about-us-content text-center">
                <h2 class="title text-center">Hi, <?php echo $key->nama_lengkap;?>!</h2>
                <hr>
                <h5 class="text-center">Silahkan pilih tipe member sesuai dengan yang kamu inginkan</h5>
            </div>
            <!-- END about-us-content -->
        </div>
        <!-- END container -->
    </div>
<!-- END #about-us-content -->
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
                    <div class="col-md-12">
                        <?php echo $this->session->flashdata('notif');?>
                    </div>
                    <?php foreach($tipeMember as $row):?>
                    <div class="col-md-3">
                        <div class="item item-thumbnail">
                            <a href="#" class="item-image">
                                <?php if(!empty($row->cover_depan)): ?>
                                    <img src="<?php echo base_url().$row->cover_depan;?>" alt="">
                                <?php else: ?>
                                    <img src="<?php echo base_url().$appsProfile->logo;?>" alt="">
                                <?php endif; ?>
                            </a>
                            <div class="item-info">
                                <h4 class="item-title">
                                    <a href=""><?php echo $row->nama_tipe_member;?></a>
                                </h4>
                                <br>
                                <p class="item-desc">
                                    <table class="table">
                                        <tr>
                                            <td align="left">Potongan</td>
                                            <td align="right">
                                                <?php echo $row->potongan_member;?>%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">Masa Berlaku</td>
                                            <td align="right"><?php echo $row->waktu_berlaku;?></td>
                                        </tr>
                                        <tr>
                                            <td align="left">Harga</td>
                                            <td align="right">Rp <?php echo number_format($row->biaya_pendaftaran,0,'.','.');?></td>
                                        </tr>
                                    </table>
                                </p>
                                <div class="item-price">
                                    <a href="<?php echo base_url('user/registerMember/doRegister/'.my_simple_crypt($row->id_tipe_member,'e'));?>" class="btn btn-template btn-sm">DAFTAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
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
<?php endforeach;?>