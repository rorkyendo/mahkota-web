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
          <?php if(cekModul('tambahRedeemPoint') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/membership/tambahRedeemPoint/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Redeem Point</a>
          <?php endif; ?>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/membership/redeemPoint')); ?>">
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
            </div>
          </form>
          <div class="col-md-12">
            <br>
            <br>
            <table class="table table-bordered table-striped table-hover" style="width:100%" id="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Pelanggan</th>
                  <th>Nama Toko</th>
                  <th>Nama Produk</th>
                  <th>Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>
    </div>
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
        "url": '<?php echo site_url(changeLink('panel/membership/redeemPoint/cari')); ?>',
        "type": "POST",
        "data":{
          "start_date":"<?php echo $start_date;?>",
          "end_date":"<?php echo $end_date;?>",
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_redeem_point",
          width: 10
        },
        {
          "data": "nama_lengkap",
          width: 100,
          render: function(data, type, row) {
            return row.nama_lengkap+" ("+row.nama_tipe_member+")";
          }
        },
        {
          "data": "nama_toko",
          width: 100
        },
        {
          "data": "nama_produk_redeem",
          width: 100
        },
        {
          "data": "harga_point",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.harga_point));
          }
        },
        {
          "data": "action",
          width: 100
      }]
    });
  });
</script>