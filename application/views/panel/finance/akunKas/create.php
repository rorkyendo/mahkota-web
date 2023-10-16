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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/finance/tambahAkun/doCreate/')); ?>">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Akun Akun Kas</label>
                <div class="col-md-10">
                <select name="kode_induk_group_kas" id="kode_induk_group_kas" class="form-control select2" onchange="cariGroupKas(this.value)">
                  <option value="">.:Pilih Kode Akun Akun Kas:.</option>
                  <?php foreach($indukGroup as $key):?>
                    <option value="<?php echo $key->kode_induk_group_kas;?>"><?php echo $key->kode_induk_group_kas;?> | <?php echo $key->nama_induk_group_kas;?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
              <script>
                function cariGroupKas(val){
                  $('#kode_group_kas').html("<option value=''>.:Pilih Kode Group Akun Kas:.</option>")
                  $.ajax({
                    url:"<?php echo base_url('panel/finance/getGroupKas');?>",
                    type:"POST",
                    data:{
                      kode_induk_group_kas:val
                    },success:function(resp){
                      if (resp!='false') {
                          var data = JSON.parse(resp)
                          $.each(data,function(key,val){
                            $('#kode_group_kas').append("<option value='"+val.kode_group_kas+"'>"+val.kode_group_kas+"|"+val.nama_group_kas+"</option>");
                          })
                      }
                    }
                  })
                }
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Group Akun Kas</label>
                <div class="col-md-10">
                <select name="kode_group_kas" id="kode_group_kas" class="form-control select2">
                  <option value="">.:Pilih Kode Group Akun Kas:.</option>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Akun Kas</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Kode Akun Kas" name="kode_kas" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Akun Kas</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Akun Kas" name="nama_kas" required/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/finance/daftarAkunKas/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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