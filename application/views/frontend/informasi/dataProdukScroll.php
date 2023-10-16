<?php foreach($produk as $rp):?>
<div class="col-lg-2 col-md-4 mb-2">
    <!-- BEGIN item -->
    <div class="item item-thumbnail">
        <a href="<?php echo base_url('produk/item/'.url_title($rp->nama_toko,"dash",true).'/'.$rp->slug_produk.'/'.$rp->id_produk);?>" class="item-image">
            <img src="<?php echo base_url().$rp->foto_produk;?>" alt="" />
            <?php if($rp->harga_diskon > 0): ?>
            <?php
                $selisih_harga = $rp->harga_jual_online - $rp->harga_diskon;
                $percent = ($selisih_harga / $rp->harga_jual_online * 100);
            ?>
                <div class="discount"><?php echo number_format($percent,0,'.','.');?>% OFF</div>
            <?php endif; ?>
        </a>
        <div class="item-info">
            <h4 class="item-title">
                <a href="<?php echo base_url('produk/item/'.url_title($rp->nama_toko,"dash",true).'/'.$rp->slug_produk.'/'.$rp->id_produk);?>"><?php echo $rp->nama_produk;?></a>
            </h4>
            <?php if($rp->harga_diskon > 0): ?>
                <div class="item-price">Rp <?php echo number_format($rp->harga_diskon,0,'.','.');?></div>
                <div class="item-discount-price">Rp <?php echo number_format($rp->harga_jual_online,0,'.','.');?></div>
            <?php else: ?>
                <div class="item-price">Rp <?php echo number_format($rp->harga_jual_online,0,'.','.');?></div>
            <?php endif; ?>
        </div>
    </div>
    <!-- END item -->
</div>
<?php endforeach;?>