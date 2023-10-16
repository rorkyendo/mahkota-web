<?php if($produkRedeem): ?>
    <div class="item-row">
        <?php foreach($produkRedeem as $key):?>
        <!-- BEGIN item -->
        <div class="item item-thumbnail">
            <a href="<?php echo base_url('produk/redeemPoint/'.$key->id_produk_redeem);?>" class="item-image">
                <img src="<?php echo base_url().$key->gambar_produk_redeem;?>" alt="" />
            </a>
            <div class="item-info">
                <h4 class="item-title">
                    <a
                        href="<?php echo base_url('produk/item/'.$key->id_produk_redeem);?>"><?php echo $key->nama_produk_redeem;?></a>
                </h4>
                <div class="item-price"><?php echo number_format($key->harga_point,0,'.','.');?> POINT</div>
            </div>
        </div>
        <!-- END item -->
        <?php endforeach;?>
    </div>
<?php endif; ?>
