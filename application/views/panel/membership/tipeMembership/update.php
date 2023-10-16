
<?php foreach($tipeMembership as $key):?>
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
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white" data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/membership/updateTipeMembership/doUpdate/'.$key->id_tipe_member)); ?>" enctype="multipart/form-data">
            <div class="col-md-3">
              <h4 class="text-center">Cover Depan Membercard</h4>
              <center>
                <?php if(!empty($key->cover_depan)): ?>
                  <img src="<?php echo base_url().$key->cover_depan; ?>" class="img-responsive" alt="preview" id="preview"
                    style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview"
                    style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <input type="file" name="cover_depan" class="form-control" id="cover_depan" accept="image/*" />
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
                $("#cover_depan").change(function () {
                  readURL(this);
                });
              </script>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3">
              <h4 class="text-center">Cover Belakang Membercard</h4>
              <center>
                <?php if(!empty($key->cover_belakang)): ?>
                  <img src="<?php echo base_url().$key->cover_belakang; ?>" class="img-responsive" alt="preview" id="preview2"
                    style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview2"
                    style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <input type="file" name="cover_belakang" class="form-control" id="cover_belakang" accept="image/*" />
              </div>
              <script type="text/javascript">
                function readURL2(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      $('#preview2').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#cover_belakang").change(function () {
                  readURL2(this);
                });
              </script>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3">
              <h4 class="text-center">Icon Membership</h4>
              <center>
                <?php if(!empty($key->icon_member)): ?>
                  <img src="<?php echo base_url().$key->icon_member; ?>" class="img-responsive" alt="preview" id="preview3"
                    style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview3"
                    style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <input type="file" name="icon_member" class="form-control" id="icon_member" accept="image/*" />
              </div>
              <script type="text/javascript">
                function readURL3(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      $('#preview3').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#icon_member").change(function () {
                  readURL3(this);
                });
              </script>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Tipe Member</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="kode_tipe_member" required value="<?php echo $key->kode_tipe_member; ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Tipe Member</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="nama_tipe_member" required value="<?php echo $key->nama_tipe_member; ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Biaya Pendaftaran</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name="biaya_pendaftaran" value="<?php echo $key->biaya_pendaftaran;?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Biaya Upgrade</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name="biaya_upgrade" value="<?php echo $key->biaya_upgrade;?>" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Potongan Member (%)</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name="potongan_member" required value="<?php echo $key->potongan_member; ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Masa Berlaku (Hari)</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name="waktu_berlaku" required value="<?php echo $key->waktu_berlaku; ?>">
                </div>
              </div>
              <!-- <div class="form-group">
                <label class="col-md-2 control-label">Update Masa Berlaku ke Member</label>
                <div class="col-md-10">
                  <select class="form-control" name="update_berlaku" required>
                      <option value="">-- Pilih Apakah Masa Berlaku akan diupdate ke Member saat ini? --</option>
                      <option value="Y">YA</option>
                      <option value="N">TIDAK</option>
                  </select>
                  <font color="red">*Jika terpilih YA maka seluruh member dengan kode tipe member yang sama akan diupdate masa berlakunya</font>
                </div>
             </div> -->
              <div class="form-group">
                <label class="col-md-2 control-label">Status Tipe Member</label>
                <div class="col-md-10">
                  <select class="form-control" name="status_tipe" required>
                      <option value="">-- Pilih Status Tipe Member --</option>
                      <option value="1" <?php if($key->status_tipe == 'active'){echo "selected";} ?>>Aktif</option>
                      <option value="0" <?php if($key->status_tipe == 'deleted'){echo "selected";} ?>>Tidak Aktif</option>
                  </select>
                </div>
             </div>
            <hr />
            <div class="form-group">
              <div class="col-md-12">
                <button type="submit" class="btn btn-sm btn-success pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/membership/daftarTipeMembership/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
<?php endforeach;?>
