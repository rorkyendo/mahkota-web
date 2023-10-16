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
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/tiket/daftarTiket')); ?>">
            <div class="col-md-4">
              <label for="">Pengguna</label>
              <select name="id_pengguna" id="id_pengguna" class="form-control select2">
                <option value="">.:Pilih Pengguna:.</option>
                <?php foreach($pengguna as $key):?>
                  <option value="<?php echo $key->id_pengguna;?>"><?php echo $key->nama_lengkap;?> / <?php echo $key->username;?></option>
                <?php endforeach;?>
              </select>
              <script>
                $('#id_pengguna').val('<?php echo $id_pengguna;?>');
              </script>
            </div>
            <div class="col-md-4">
              <label for="">Kategori Tiket</label>
              <select name="kategori_tiket" id="kategori_tiket" class="form-control">
                <option value="">.:Pilih Kategori Tiket:.</option>
                <option value="Produk">Informasi Produk</option>
                <option value="Layanan dan Keluhan">Layanan dan Keluhan</option>
              </select>
              <script>
                $('#kategori_tiket').val('<?php echo $kategori_tiket;?>');
              </script>
            </div>
            <div class="col-md-4">
              <label for="">Status Tiket</label>
              <select name="status" id="status" class="form-control">
                <option value="">.:Pilih Kategori Tiket:.</option>
                <option value="open">OPEN</option>
                <option value="process">PROCESS</option>
                <option value="closed">CLOSED</option>
              </select>
              <script>
                $('#status').val('<?php echo $status;?>');
              </script>
            </div>
            <div class="col-md-6">
              <label for="">Dari Tgl</label>
              <input type="date" class="form-control" name="start_date" value="<?php echo $start_date;?>">
            </div>
            <div class="col-md-6">
              <label for="">Sampai Tgl</label>
              <input type="date" class="form-control" name="end_date" value="<?php echo $end_date;?>">
            </div>
            <div class="col-md-12">
              <br>
              <button type="submit" class="btn btn-md btn-block btn-template text-white"><i class="fa fa-search"></i> Cari Data</button>
              <br />
            </div>
          </form>
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                  <td>KODE</th>
                  <td>PESAN</th>
                  <td>STATUS</th>
                  <td>TGL</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <?php echo $this->session->flashdata('notif'); ?>
        </div>
      </div>
      <!-- end panel -->
    </div>
    <!-- end col-12 -->
  </div>
  <!-- end row -->
</div>
<!-- end #content -->
<script type="text/javascript">
  var table;

  $(document).ready(function() {
    table = $('#table').DataTable({
      responsive: {
        breakpoints: [{
          name: 'not-desktop',
          width: Infinity
        }]
      },
      "bStateSave": true,
      "filter": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
        "ajax": {
            "url": '<?php echo base_url(changeLink('panel/tiket/daftarTiket/cari')); ?>',
            "type": "POST",
            "data":{
              id_pengguna:"<?php echo $id_pengguna?>",
              status:"<?php echo $status;?>",
              kategori_tiket:"<?php echo $kategori_tiket;?>",
              start_date:"<?php echo $start_date;?>",
              end_date:"<?php echo $end_date;?>",
            }
        },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "kode_tiket",
          width: 5,
          render: function(data, type, row) {
            return "<a href='<?php echo base_url('panel/tiket/detailTiket/');?>"+row.kode_tiket+"' class='btn btn-primary btn-sm'>"+row.kode_tiket+"</a>";
          }
        },
        {
          "data": "judul_tiket",
          width: 100,
        },
        {
          "data": "status_tiket",
          width: 10,
          render: function(data, type, row) {
            if(row.status_tiket=='open'){
                return '<b class="text-primary">'+row.status_tiket.toUpperCase()+'</b>';
            }else if(row.status_tiket=='process'){
                return '<b class="text-success">'+row.status_tiket.toUpperCase()+'</b>';
            }else{
                return '<b>'+row.status_tiket.toUpperCase()+'</b>';
            }
          }
        },
        {
          "data": "created_time",
          width: 100,
        },
      ],
    });
  });
</script>