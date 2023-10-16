<?php foreach($profile as $key):?>
    <?php foreach($transaksi as $row):?>
    <!-- BEGIN #about-us-content -->
        <div class="section-container bg-white">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN about-us-content -->
                <div class="about-us-content text-center">
                    <h2 class="title text-center">Hi, <?php echo $key->nama_lengkap;?>!</h2>
                    <hr>
                    <h5 class="text-center">Silahkan lakukan pembayaran untuk menyelesaikan pesanan kamu</h5>
                </div>
                <!-- END about-us-content -->
            </div>
            <!-- END container -->
        </div>
    <!-- END #about-us-content -->
    <!-- BEGIN #my-account -->
    <div id="about-us-cover" class="section-container" style="width:100%;">
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
                        <div class="col-md-12">
                            <iframe src="<?php echo $row->invoice_url;?>" style="width: 100%; height: 200vh;"></iframe>
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
    <?php endforeach;?>
<?php endforeach;?>