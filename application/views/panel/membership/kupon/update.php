<?php foreach($kupon as $row):?>
<!-- begin #content -->
<div id="content" class="content">
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
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white"
              data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white"
              data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white"
              data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post"
            action="<?php echo base_url(changeLink('panel/membership/updateKupon/doUpdate/'.$row->id_kupon)); ?>"
            enctype="multipart/form-data">
            <div class="col-md-12">
              <h4 class="text-center">Gambar Voucher</h4>
              <center>
                <?php if(!empty($row->gambar_kupon)): ?>
                  <img src="<?php echo base_url().$row->gambar_kupon; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <input type="file" name="gambar_kupon" class="form-control" id="gambar_kupon" accept="image/*" />
              </div>
              <script type="text/javascript">
                function readURL(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#gambar_kupon").change(function () {
                  readURL(this);
                });
              </script>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Voucher</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="nama_kupon" value="<?php echo $row->nama_kupon;?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Voucher</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="kode_kupon" value="<?php echo $row->kode_kupon;?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Keterangan Voucher</label>
                <div class="col-md-10">
                  <textarea name="keterangan_kupon" id="keterangan_kupon" class="form-control" cols="30"
                    rows="10"><?php echo $row->keterangan_kupon;?></textarea>
                  <script>
                    CKEDITOR.replace('keterangan_kupon');
                  </script>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Syarat & Ketentuan</label>
                <div class="col-md-10">
                  <textarea name="syarat_ketentuan" id="syarat_ketentuan" class="form-control" cols="30"
                    rows="10"><?php echo $row->syarat_ketentuan;?></textarea>
                  <script>
                    CKEDITOR.replace('syarat_ketentuan');
                  </script>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Tipe Member</label>
                <div class="col-md-10">
                  <select name="tipe_member" id="tipe_member" class="form-control select2" required>
                    <option value="">.:Pilih Tipe Member:.</option>
                    <?php foreach($tipe_member as $key):?>
                    <option value="<?php echo $key->id_tipe_member;?>"><?php echo $key->nama_tipe_member;?></option>
                    <?php endforeach;?>
                  </select>
                  <script>
                    $('#tipe_member').val('<?php echo $row->tipe_member;?>')
                  </script>
                    <font color="red">*Kosongkan untuk memberlakukan voucher ke seluruh member</font>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Tampil Voucher</label>
                <div class="col-md-10">
                  <select name="tampil_kupon" id="tampil_kupon" class="form-control" required>
                    <option value="Y">Tampil</option>
                    <option value="N">Tidak Tampil</option>
                  </select>
                  <font color="red">*Pilih tampil atau sembunyikan kupon pada member</font>
                </div>
                <script>
                  $('#tampil_kupon').val('<?php echo $row->tampil_kupon;?>')
                </script>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Dengan Min.Belanja</label>
                <div class="col-md-10">
                  <select name="status_min_belanja" id="status_min_belanja" onchange="statusMinBelanja(this.value)"
                    class="form-control" required>
                    <option value="Y">Ya</option>
                    <option value="N">Tidak</option>
                  </select>
                </div>
                <script>
                  $('#status_min_belanja').val('<?php echo $row->status_min_belanja;?>')
                </script>
              </div>
              <script>
                function statusMinBelanja(val) {
                  if (val == 'Y') {
                    $('#formBelanja').removeClass('hidden')
                    $('#min_belanja').attr('required', true);
                  } else {
                    $('#formBelanja').addClass('hidden')
                    $('#min_belanja').removeAttr('required');
                    $('#min_belanja').val('');
                  }
                }
              </script>
              <div class="form-group" id="formBelanja">
                <label class="col-md-2 control-label">Min Belanja</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" value="<?php echo $row->min_belanja;?>" name="min_belanja" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Jenis Voucher</label>
                <div class="col-md-10">
                  <select name="jenis_kupon" id="jenis_kupon" onchange="jenisKupon(this.value)" class="form-control"
                    required>
                    <option value="potongan">Potongan</option>
                    <option value="diskon">Diskon</option>
                  </select>
                </div>
                <script>
                  $('#jenis_kupon').val('<?php echo $row->jenis_kupon;?>')
                </script>
              </div>
              <div class="form-group hidden" id="formDiskon">
                <label class="col-md-2 control-label">Diskon</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name="diskon" value="<?php echo $row->diskon;?>"
                    id="diskon">
                  <font color="red">*Dalam Persen</font>
                </div>
              </div>
              <div class="form-group" id="formPotongan">
                <label class="col-md-2 control-label">Potongan</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name="potongan" value="<?php echo $row->potongan;?>"
                    id="potongan">
                  <font color="red">*Dalam Rupiah</font>
                </div>
              </div>
              <script>
                function jenisKupon(val) {
                  if (val == 'diskon') {
                    $('#formDiskon').removeClass('hidden')
                    $('#diskon').attr('required', true);
                    $('#formPotongan').addClass('hidden')
                    $('#potongan').removeAttr('required');
                    $('#potongan').val('');
                  } else {
                    $('#formPotongan').removeClass('hidden')
                    $('#potongan').attr('required', true);
                    $('#formDiskon').addClass('hidden')
                    $('#diskon').removeAttr('required');
                    $('#diskon').val('');
                  }
                }
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Berlaku Hingga</label>
                <div class="col-md-10">
                  <input type="date" class="form-control" value="<?php echo $row->berlaku_hingga;?>" name="berlaku_hingga"
                    required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Jumlah Voucher</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" value="<?php echo $row->jml_kupon;?>" name="jml_kupon"
                    required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Status Voucher</label>
                  <div class="col-md-10">
                    <select name="status" id="status" class="form-control" required>
                      <option value="Y">Aktif</option>
                      <option value="N">Tidak Aktif</option>
                    </select>
                  </div>
              </div>
              <script>
                $('#status').val('<?php echo $row->status;?>')
              </script>
            </div>
            <hr />
            <div class="form-group">
              <div class="col-md-12">
                <button type="submit" class="btn btn-sm btn-success pull-right"
                  style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/membership/daftarKupon/')); ?>"
                  class="btn btn-sm btn-danger pull-right">Batal</a>
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
  $(document).ready(function(){
    statusMinBelanja('<?php echo $row->status_min_belanja;?>')
    jenisKupon('<?php echo $row->jenis_kupon;?>')
  })
</script>
<?php endforeach;?>
