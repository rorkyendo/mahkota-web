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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/produk/tambahProduk/doCreate/')); ?>"  enctype="multipart/form-data">
            <div class="col-md-3">
              <h4 class="text-center">Gambar 1</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview_1" id="preview_1" style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <div class="col-md-12">
                  <input type="file" name="foto_produk" class="form-control" id="foto_produk" accept="image/*" />
                </div>
              </div>
              <script type="text/javascript">
                function readURL(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview_1').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#foto_produk").change(function() {
                  readURL(this);
                });
              </script>
            </div>
            <div class="col-md-3">
              <h4 class="text-center">Gambar 2</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview_2" id="preview_2" style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <div class="col-md-12">
                  <input type="file" name="foto_produk_2" class="form-control" id="foto_produk_2" accept="image/*" />
                </div>
              </div>
              <script type="text/javascript">
                function readURL2(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview_2').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#foto_produk_2").change(function() {
                  readURL2(this);
                });
              </script>
            </div>
            <div class="col-md-3">
              <h4 class="text-center">Gambar 3</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview_3" id="preview_3" style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <div class="col-md-12">
                  <input type="file" name="foto_produk_3" class="form-control" id="foto_produk_3" accept="image/*" />
                </div>
              </div>
              <script type="text/javascript">
                function readURL3(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview_3').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#foto_produk_3").change(function() {
                  readURL3(this);
                });
              </script>
            </div>
            <div class="col-md-3">
              <h4 class="text-center">Gambar 4</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview_4" id="preview_4" style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <div class="col-md-12">
                  <input type="file" name="foto_produk_4" class="form-control" id="foto_produk_4" accept="image/*" />
                </div>
              </div>
              <script type="text/javascript">
                function readURL4(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview_4').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#foto_produk_4").change(function() {
                  readURL4(this);
                });
              </script>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Barcode</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Barcode Produk" name="barcode" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Produk</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Produk" name="nama_produk" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Deskripsi Produk</label>
                <div class="col-md-10">
                  <textarea name="detail_produk" id="detail_produk" class="form-control" cols="30" rows="10"></textarea>
                </div>
                <script>
                    CKEDITOR.replace('detail_produk');
                </script>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">PPN</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan PPN" name="ppn" id="ppn" step="0.01" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Komisi</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Komisi" name="komisi" id="komisi" step="0.01" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Potongan Member GOLD %</label>
                <div class="col-md-10">
                  <input type="number" name="potongan_member_gold" class="form-control" placeholder="Masukkan potongan member GOLD %">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Potongan Member BLUE %</label>
                <div class="col-md-10">
                  <input type="number" name="potongan_member_blue" class="form-control" placeholder="Masukkan potongan member BLUE %">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Jenis Point</label>
                <div class="col-md-10">
                  <select name="jenis_point" id="jenis_point" class="form-control" onchange="jenisPoint(this.value)">
                    <option value="">.:Pilih Jenis Point:.</option>
                    <option value="persen">(%) Persen</option>
                    <option value="saldo">Nominal</option>
                  </select>
                </div>
              </div>
              <script>
                function jenisPoint(val){
                  if(val=='persen'){
                    $("#notePoint").text('Jumlah point akan dikalkulasikan dengan harga jual online');
                    $('#point_produk').val('')
                  }else if(val=='saldo'){
                    $("#notePoint").text('Silahkan masukkan nominla jumlah point yang akan didapatkan untuk produk ini');
                    $('#point_produk').val('')
                  }else{
                    $('#point_produk').val('')
                  }
                }
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Point</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Point Produk" name="point_produk" id="point_produk" step="0.01" />
                  <font color="red" id="notePoint"></font>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Berat Produk (gr)</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Berat Produk (gr)" name="berat_produk" id="berat_produk" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Dengan COP</label>
                <div class="col-md-10">
                  <select name="status_cop" id="status_cop" class="form-control" onchange="statusCOP(this.value)" required>
                    <option value="">.:Pilih Status COP:.</option>
                    <option value="Y">Y</option>
                    <option value="N">N</option>
                  </select>
                </div>
              </div>
              <script>
                function statusCOP(val){
                  if (val=='Y') {
                    $('#formInventori').addClass('hidden')
                    $('#formCOP').removeClass('hidden')
                    $('#inventori').val('');                    
                  }else{
                    $('#formCOP').addClass('hidden')
                    $('#formInventori').removeClass('hidden')
                    $('#cop').val('');
                  }
                }
              </script>
              <div class="form-group hidden" id="formCOP">
                <label class="col-md-2 control-label">COP</label>
                <div class="col-md-10">
                  <select name="cop" id="cop" class="form-control select2" style="width: 100%;">
                    <option value="">.:Pilih COP:.</option>
                    <?php foreach($cop as $key):?>
                      <option value="<?php echo $key->id_cop;?>"><?php echo $key->nama_cop;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group" id="formInventori">
                <label class="col-md-2 control-label">Inventori</label>
                <div class="col-md-10">
                  <select name="inventori" id="inventori" class="form-control select2" onchange="getHarga(this.value)" style="width: 100%;">
                    <option value="">.:Pilih Inventori:.</option>
                    <?php foreach($inventori as $key):?>
                      <option value="<?php echo $key->id_inventori;?>"><?php echo $key->nama_inventori;?></option>
                    <?php endforeach;?>
                  </select>
                  <font color="red">*Kosongkan bila tidak menggunakan inventori</font>
                </div>
                <script>
                  function getHarga(id_inventori){
                    $.ajax({
                      url:"<?php echo base_url('panel/produk/getHarga');?>",
                      type:"GET",
                      data:{
                        id_inventori:id_inventori
                      },success:function(resp){
                        if(resp!='false'){
                          var data = JSON.parse(resp)
                          $.each(data,function(key,val){
                            $('#harga_modal').val(val.harga_modal)
                            $('#harga_jual').val(val.harga_jual)
                            $('#harga_jual_grosir').val(val.harga_jual_grosir)
                            $('#harga_jual_online').val(val.harga_jual_online)
                          })
                        }else{
                          Swal.fire(
                            'Oopps..',
                            'Data harga tidak ditemukan',
                            'error'
                          )
                        }
                      }
                    })
                  }
                </script>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Modal</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Modal" name="harga_modal" id="harga_modal" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Jual</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Jual" name="harga_jual" id="harga_jual" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Jual Grosir</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Jual" name="harga_jual_grosir" id="harga_jual_grosir" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Jual Online</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Jual Online" name="harga_jual_online" id="harga_jual_online" step="0.01" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Harga Diskon</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Harga Diskon" name="harga_diskon" id="diskon" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Kategori Produk</label>
                <div class="col-md-10">
                  <select name="kategori_produk" id="kategori_produk" class="form-control select2" style="width:100%" onchange="getBrand(this.value)" required>
                    <option value="">.:Pilih Kategori Produk:.</option>
                    <?php foreach($kategoriProduk as $key):?>
                      <option value="<?php echo $key->id_kategori_produk;?>"><?php echo $key->nama_kategori_produk;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Pilih Brand</label>
                <div class="col-md-10">
                  <select name="brand" id="brand" class="form-control select2" style="width:100%" required>
                    <option value="">.:Pilih Brand:.</option>
                  </select>
                </div>
              </div>
              <script>
                function getBrand(val){
                  $.ajax({
                    url:"<?php echo base_url('panel/produk/getBrand');?>",
                    type:"GET",
                    data:{
                      kategori:val
                    },success:function(resp){
                      var data = JSON.parse(resp);
                      $.each(data,function(key,val){
                        $("#brand").append('<option value="'+val.id_brand+'">'+val.nama_brand+'</option>');
                      })
                    }
                  })
                }
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Wajib Asuransi</label>
                <div class="col-md-10">
                  <select name="wajib_asuransi" id="wajib_asuransi" class="form-control" style="width:100%" required>
                    <option value="">.:Pilih Status Asuransi:.</option>
                    <option value="Y">Y</option>
                    <option value="N">N</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Tampil Online</label>
                <div class="col-md-10">
                  <select name="tampil_toko" id="tampil_toko" class="form-control" style="width:100%" required>
                    <option value="">.:Pilih Status Tampil:.</option>
                    <option value="Y">Y</option>
                    <option value="N">N</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/produk/daftarProduk/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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