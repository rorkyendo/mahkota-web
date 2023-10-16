<?php 
    $appsProfile = $this->SettingsModel->get_profile();
?>
<?php foreach($produk as $key):?>
<div class="col-md-4">
    <div class="panel">
        <div class="panel-body">
            <center>
                <b><small><?php echo $key->nama_produk;?></small></b>
            </center>
            <?php if(!empty($key->foto_produk)): ?>
            <img src="<?php echo base_url().$key->foto_produk;?>" class="img-responsive img-thumbnail" style="height:120px;margin-left:auto;margin-right:auto;display:block;" alt="">
            <?php else: ?>
            <img src="<?php echo base_url().$appsProfile->logo;?>" class="img-responsive img-thumbnail" style="height:120px;margin-left:auto;margin-right:auto;display:block;" alt="">
            <?php endif; ?>
            <div class="col-md-12">
                <br />
                <b>Lokasi</b>
                <b class="pull-right"><?php echo $key->kode_lokasi_penyimpanan;?> <?php echo $key->nama_lokasi_penyimpanan;?></b>
                <br />
                <b>Harga</b>
                <?php if(!empty($key->harga_diskon)): ?>
                  <b class="pull-right"><strike>Rp <?php echo number_format($key->harga_jual,0,'.','.');?></strike></b>
                  <br>
                  <b class="pull-right">Rp <?php echo number_format($key->harga_jual-$key->harga_diskon,0,'.','.');?></b>
                <?php else: ?>
                  <b class="pull-right">Rp <?php echo number_format($key->harga_jual,0,'.','.');?></b>
                <?php endif; ?>
                <br />
                <br />
                <center>
                    <label>Qty</label>
                </center>
                <input type="number" class="form-control" id="qty<?php echo $key->id_produk;?>">
                <br />
                <br />
                <button type="button" class="btn btn-warning btn-md btn-block"
                    onclick="pesan('<?php echo $key->id_produk;?>')">Tambah Pesanan</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>
