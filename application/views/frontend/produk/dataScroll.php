<?php if($produk): ?>
<div class="item-row" id="from<?php echo $from;?>">
<?php foreach($produk as $key):?>
        <!-- BEGIN item -->
        <div class="item item-thumbnail">
            <a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>" class="item-image">
                <img src="<?php echo base_url().$key->foto_produk;?>" alt="" />
                <?php if($key->harga_diskon > 0): ?>
                <?php
                    $selisih_harga = $key->harga_jual_online - $key->harga_diskon;
                    $percent = ($selisih_harga / $key->harga_jual_online * 100);
                ?>
                    <div class="discount"><?php echo number_format($percent,0,'.','.');?>% OFF</div>
                <?php endif; ?>
            </a>
            <div class="item-info">
                <h4 class="item-title">
                    <a href="<?php echo base_url('produk/item/'.url_title($key->nama_toko,"dash",true).'/'.$key->slug_produk.'/'.$key->id_produk);?>"><?php echo $key->nama_produk;?></a>
                </h4>
                <?php if($key->harga_diskon > 0): ?>
                    <div class="item-price">Rp <?php echo number_format($key->harga_diskon,0,'.','.');?></div>
                    <div class="item-discount-price">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></div>
                <?php else: ?>
                    <div class="item-price">Rp <?php echo number_format($key->harga_jual_online,0,'.','.');?></div>
                <?php endif; ?>
            </div>
        </div>
    <!-- END item -->
<?php endforeach;?>
</div>
<?php endif; ?>
