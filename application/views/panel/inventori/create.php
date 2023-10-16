<!-- begin #content -->
<div id="content" class="content">
  <!-- begin breadcrumb -->
  <ol class="breadcrumb pull-right">
    <li><a href="javascript:;">Home</a></li>
    <li><a href="javascript:;"><?php echo $title; ?></a></li>
    <li class="active"><?php echo $subtitle; ?></li>
  </ol>
  <!-- end breadcrumb -->
  <!-- begin page-header -->
  <h1 class="page-header"><?php echo $title; ?></h1>
  <!-- end page-header -->

  <!-- begin row -->
  <div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white" data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/inventori/tambahInventori/doCreate/')); ?>"  enctype="multipart/form-data">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Barcode</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Barcode Inventori" name="barcode" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Inventori</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Kode Inventori" name="kode_inventori" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Inventori</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Inventori" name="nama_inventori" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Satuan</label>
                <div class="col-md-10">
                  <select name="kode_satuan" id="kode_satuan" class="form-control select2" required>
                    <option value="">.:Pilih Kode Satuan:.</option>
                    <?php foreach($satuan as $key):?>
                      <option value="<?php echo $key->kode_satuan;?>"><?php echo $key->kode_satuan;?> | <?php echo $key->keterangan;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Qty</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Qty" name="qty" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Gudang</label>
                <div class="col-md-10">
                  <select name="gudang" id="gudang" class="form-control select2" onchange="cariLokasi(this.value)" required>
                    <option value="">.:Pilih Gudang:.</option>
                    <?php foreach($gudang as $key):?>
                      <option value="<?php echo $key->id_gudang;?>"><?php echo $key->kode_gudang;?> | <?php echo $key->nama_gudang;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Lokasi Penyimpanan</label>
                <div class="col-md-10">
                  <select name="lokasi_penyimpanan" id="lokasi_penyimpanan" class="form-control select2" required>
                    <option value="">.:Pilih Lokasi Penyimpanan:.</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Beli (Keseluruhan)</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Beli Keseluruhan" name="harga_beli" readonly/>
                  <font color="red">*Harga beli akan otomatis terupdate ketika memasukkan logistik</font>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Modal (Satuan)</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Modal Satuan" name="harga_modal" id="harga_modal" required/>
                  <font color="red">*Harga modal akan terupdate otomatis ketika memasukkan logistik</font>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Jual Toko</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Jual Toko" name="harga_jual" id="harga_jual" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Jual Grosir</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Jual Grosir" id="harga_jual_grosir" name="harga_jual_grosir" step="0.01"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Jual Online</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Jual Online" id="harga_jual_online" name="harga_jual_online" step="0.01"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/inventori/daftarInventori/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
              </div>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end col-12 -->
</div>
<!-- end row -->
</div>
<!-- end #content -->
<script>
  function cariLokasi(val){
    $('#lokasi_penyimpanan').html('.:Pilih Lokasi Penyimpanan:.');
    $.ajax({
      url:"<?php echo base_url('panel/inventori/getLokasiPenyimpanan');?>",
      type:"GET",
      data:{
        gudang:val
      },success:function(data){
        if(data!='false'){
          var data = JSON.parse(data);
          $.each(data,function(key,val){
            $('#lokasi_penyimpanan').append('<option value="'+val.id_lokasi_penyimpanan+'">'+val.kode_lokasi_penyimpanan+' | '+val.nama_lokasi_penyimpanan+'</option>');
          })
        }else{
            Swal.fire(
              'Oopps..',
              'Data lokasi penyimpanan tidak ditemukan',
              'error'
            )
        }
      }
    })
  }
</script>