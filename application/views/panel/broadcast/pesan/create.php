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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/broadcast/kirimPesan/doCreate/')); ?>"  enctype="multipart/form-data">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Judul Template</label>
                <div class="col-md-10">
                  <select name="template" id="template" class="form-control select2" onchange="cariTemplate(this.value)">
                    <option value="">.:Pilih Judul Template:.</option>
                    <?php foreach($templatePesan as $key):?>
                      <option value="<?php echo $key->id_template;?>"><?php echo $key->judul_template;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Pengguna</label>
                <div class="col-md-10">
                  <select name="pengguna[]" id="pengguna" class="form-control select2" multiple="multiple">
                    <?php foreach($pengguna as $key):?>
                      <option value="<?php echo $key->id_pengguna;?>"><?php echo $key->nama_lengkap;?></option>
                    <?php endforeach;?>
                  </select>
                  <input id="chkall" type="checkbox">Select All
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Jenis Pesan</label>
                <div class="col-md-10">
                  <select name="jenis_pesan" id="jenis_pesan" class="form-control" onchange="jenisPesan(this.value)">
                    <option value="">.:Pilih Jenis Pesan:.</option>
                    <option value="WA">Broadcast Via WA</option>
                    <option value="NOTIF">Broadcast Via Notif</option>
                  </select>
                </div>
              </div>
              <script>
                function jenisPesan(val){
                  if(val == 'NOTIF'){
                    $('#form-judul').removeAttr('style');
                  }else{
                    $('#form-judul').attr('style','display:none');
                  }
                }
              </script>
              <div class="form-group" id="form-judul">
                <label class="col-md-2 control-label">Judul Notif</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="judul" id="judul">
                  <font color="red">*Jika ingin memanggil nama pengguna sertakan [nama_pengguna] pada kalimat</font>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Isi Pesan</label>
                <div class="col-md-10">
                  <textarea name="isi_pesan" class=" form-control" id="isi_pesan" cols="30" rows="10"></textarea>
                  <font color="red">*Jika ingin memanggil nama pengguna sertakan [nama_pengguna] pada kalimat</font>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Gambar</label>
                <div class="col-md-10">
                  <input type="file" class="form-control" name="gambar" accept="image/*">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/broadcast/daftarTemplatePesan/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
  function cariTemplate(id_template){
   $.ajax({
    url:"<?php echo base_url('panel/broadcast/kirimPesan/getTemplate');?>",
    type:"POST",
    data:{
      id_template:id_template
    },success:function(resp){
      var data = JSON.parse(resp);
      if(resp!='false'){
        $('#isi_pesan').val(data[0].isi_pesan)
      }
    }
   }) 
  }

  $("#chkall").click(function(){
      if($("#chkall").is(':checked')){
          $("#pengguna > option").prop("selected", "selected");
          $("#pengguna").trigger("change");
      } else {
          $("#pengguna > option").removeAttr("selected");
          $("#pengguna").trigger("change");
      }
  });  
</script>