<?php foreach($pengguna as $row):?>
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
            action="<?php echo base_url(changeLink('panel/profile/edit/doEdit/')); ?>"
            enctype="multipart/form-data">
            <div class="col-md-12">
              <h4 class="text-center">Foto Pengguna</h4>
              <center>
                <?php if (!empty($row->foto_pengguna)) : ?>
                <img src="<?php echo base_url() . $row->foto_pengguna; ?>" class="img-responsive" alt="preview"
                  id="preview" style="height:150px">
                <?php else : ?>
                <img src="<?php echo base_url('assets/img/user.png'); ?>" class="img-responsive" alt="preview"
                  id="preview" style="height:120px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <label class="col-md-2 control-label">Foto Pengguna</label>
                <div class="col-md-10">
                  <input type="file" name="foto_pengguna" class="form-control" id="foto_pengguna" accept="image/*" />
                </div>
              </div>
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
              $("#foto_pengguna").change(function () {
                readURL(this);
              });
            </script>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Username</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan username pengguna" name="username"
                    value="<?php echo $row->username;?>" disabled />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">NIK KTP</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan NIK KTP" onkeypress="onlyNumberKey(event)" name="nik"
                    value="<?php echo $row->nik;?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">NIP</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan NIP" name="nip"
                    value="<?php echo $row->nip;?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Lengkap</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Lengkap Pengguna"
                    value="<?php echo $row->nama_lengkap;?>" name="nama_lengkap" required />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Jenkel</label>
                <div class="col-md-10">
                  <select name="jenkel" id="jenkel" class="form-control">
                    <option value="">.:Pilih Jenkel:.</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>
              </div>
              <script>
                $('#jenkel').val('<?php echo $row->jenkel;?>')
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Tgl Lahir</label>
                <div class="col-md-10">
                  <input type="date" class="form-control" placeholder="Masukkan tgl lahir"
                    value="<?php echo $row->tgl_lahir;?>" name="tgl_lahir" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Alamat</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Alamat"
                    value="<?php echo $row->alamat;?>" name="alamat" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Email</label>
                <div class="col-md-10">
                  <input type="email" class="form-control" placeholder="Masukkan email"
                    value="<?php echo $row->email; ?>" name="email" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">No HP / No WA</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" onkeypress="onlyNumberKey(event)"
                    placeholder="Masukkan No HP/WA" value="<?php echo $row->no_telp;?>" name="no_telp" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Buat Password</label>
                <div class="col-md-10">
                  <input type="password" class="form-control" placeholder="Masukkan Password Baru" id="password"
                    onkeyup="cekPassword()" name="password" />
                  <!-- <font color="red" id="notifpass"></font> -->
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Ulangi Password</label>
                <div class="col-md-10">
                  <input type="password" class="form-control" placeholder="Ulangi Password Baru" onkeyup="cekPassword()"
                    id="re_password" name="re_password" />
                  <font color="red" id="notifrepass"></font>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Hak Akses</label>
                <div class="col-md-10">
                  <select name="hak_akses" id="hak_akses" class="form-control" disabled>
                    <option value="">.:Pilih Hak Akses:.</option>
                    <?php foreach ($hakAkses as $key) : ?>
                    <option value="<?php echo $key->nama_hak_akses; ?>"><?php echo $key->nama_hak_akses; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <script>
                  $('#hak_akses').val('<?php echo $row->hak_akses; ?>')
                </script>
              </div>
              <?php if($this->session->userdata('superuser')): ?>
              <div class="form-group">
                <label class="col-md-2 control-label">Toko</label>
                <div class="col-md-10">
                  <select class="form-control" id="uuid_toko" name="uuid_toko">
                    <option value="">.:Pilih Toko:.</option>
                    <?php foreach ($toko as $key) : ?>
                    <option value="<?php echo $key->uuid_toko; ?>"><?php echo $key->nama_toko; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <script>
                $('#uuid_toko').val('<?php echo $row->uuid_toko;?>')
              </script>
              <?php endif; ?>
              <div class="form-group">
                <label class="col-md-2 control-label">Status Akun</label>
                <div class="col-md-10">
                  <select name="status" id="status" class="form-control">
                    <option value="">.:Pilih Status Akun:.</option>
                    <option value="pending">Belum Aktif</option>
                    <option value="deleted">Non aktif</option>
                    <option value="actived">Aktif</option>
                  </select>
                </div>
                <script>
                  $('#status').val('<?php echo $row->status; ?>')
                </script>
              </div>
              <script type="text/javascript">
                function cekPassword() {
                  var repass = $('#re_password').val()
                  var pass = $('#password').val()
                  if (repass != pass || pass != repass) {
                    $('#notifrepass').prop('color', 'red');
                    $('#notifrepass').text('Ulangi password tidak sama dengan password');
                    $('#btnSimpan').attr('disabled', true);
                  } else {
                    $('#notifrepass').prop('color', 'green');
                    $('#notifrepass').text('Ulangi password sama dengan password');
                    $('#btnSimpan').removeAttr('disabled');
                  }
                }
              </script>
            </div>
            <div class="col-md-12">
              <hr />
              <div class="form-group">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-sm btn-success pull-right" id="btnSimpan"
                    style="margin-left:10px">Simpan</button>
                  <a href="<?php echo base_url(changeLink('panel/masterData/daftarPengguna/')); ?>"
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
