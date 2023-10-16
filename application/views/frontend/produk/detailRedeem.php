<?php foreach($produkRedeem as $key):?>
<!-- BEGIN #product -->
<div id="product" class="section-container p-t-20">
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN breadcrumb -->
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('produk/redeemPoint');?>">Redeem Point</a></li>
            <li class="breadcrumb-item active"><?php echo $key->nama_produk_redeem;?></li>
        </ul>
        <!-- END breadcrumb -->
        <?php echo $this->session->flashdata('notif');?>
        <!-- BEGIN product -->
        <div class="product">
            <!-- BEGIN product-detail -->
            <div class="product-detail">
                <!-- BEGIN product-image -->
                <div class="product-image">
                    <!-- BEGIN product-main-image -->
                    <div class="product-main-image" data-id="main-image">
                        <img src="<?php echo base_url().$key->gambar_produk_redeem;?>" alt="" />
                    </div>
                    <!-- END product-main-image -->
                </div>
                <!-- END product-image -->
                <!-- BEGIN product-info -->
                <div class="product-info">
                    <!-- BEGIN product-info-list -->
                    <ul class="product-info-list">
                        <b>Detail Produk :</b>
                        <?php echo $key->keterangan_produk_redeem;?>
                        <hr>
                        <b>Syarat dan Ketentuan :</b>
                        <?php echo $key->sk_redeem;?>
                    </ul>
                    <!-- END product-info-list -->
                    <!-- BEGIN product-purchase-container -->
                    <!-- <div class=""> -->
                        <div class="product-price">
                            <div class="price"><?php echo number_format($key->harga_point,0,'.','.');?> POINT</div>
                        </div>
                        <a href="#" data-toggle="modal" data-target="#modalToko" class="btn btn-template btn-theme btn-lg width-200">REDEEM</a>
                    </div>
                    <!-- END product-purchase-container -->
                <!-- </div> -->
                <!-- END product-info -->
            </div>
            <!-- END product-detail -->
            <!-- BEGIN product-tab -->

            <!-- END product-tab -->
        </div>
        <!-- END product -->
    </div>
    <!-- END container -->
</div>
<!-- END #product -->
<div class="modal" id="modalToko">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>
