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
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/laporan/laporanTransaksi')); ?>">
            <div class="col-md-6">
              <label for="">Dari Tgl</label>
              <input type="date" class="form-control" name="start_date" value="<?php echo $start_date;?>">
            </div>
            <div class="col-md-6">
              <label for="">Sampai Tgl</label>
              <input type="date" class="form-control" name="end_date" value="<?php echo $end_date;?>">
            </div>
            <div class="col-md-8">
              <label for="">Sales</label>
              <select name="sales" id="sales" class="form-control select2">
                <option value="">.:Pilih Sales:.</option>
                <?php foreach($sales as $key):?>
                  <option value="<?php echo $key->id_pengguna;?>"><?php echo $key->nama_lengkap;?></option>
                <?php endforeach;?>
              </select>
              <script>
                $('#sales').val('<?php echo $id_sales;?>');
              </script>
            </div>
            <div class="col-md-4">
              <label for="">Status</label>
              <select name="payment_status" id="payment_status" class="form-control">
                <option value="">.:Pilih Status Pembayaran:.</option>
                <option value="payed">Lunas</option>
                <option value="pending">Pending</option>
                <option value="process">Proses</option>
                <option value="refund">Dikembalikan</option>
                <option value="cancel">Dibatalkan</option>
              </select>
              <script>
                $('#payment_status').val('<?php echo $payment_status;?>');
              </script>
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
                  <th>ID Transaksi</th>
                  <th>Pelanggan</th>
                  <th>Sales</th>
                  <th>Nama Toko</th>
                  <th>Total</th>
                  <th>Total Pembayaran</th>
                  <th>Status Pembayaran</th>
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
        "url": '<?php echo site_url(changeLink('panel/laporan/laporanTransaksi/cari')); ?>',
        "type": "POST",
        "data":{
          "start_date":"<?php echo $start_date;?>",
          "end_date":"<?php echo $end_date;?>",
          "sales":"<?php echo $id_sales;?>",
          "payment_status":"<?php echo $payment_status;?>",
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_transaksi",
          width: 10
        },
        {
          "data": "nama_lengkap_pelanggan",
          width: 100
        },
        {
          "data": "nama_lengkap_sales",
          width: 100
        },
        {
          "data": "nama_toko",
          width: 100
        },
        {
          "data": "total",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.total));
          }
        },
        {
          "data": "total_pembayaran",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.total_pembayaran));
          }
        },
        {
          "data": "payment_status",
          width: 100,
          render: function(data, type, row) {
            if(row.payment_status == 'pending'){
              return "<b class='text-warning'>PENDING</b>";
            }else if(row.payment_status == 'payed'){
              return "<b class='text-success'>LUNAS</b>";
            }else if(row.payment_status == 'process'){
              return "<b class='text-info'>PROCESS</b>";
            }else if(row.payment_status == 'refund'){
              return "<b class='text-danger'>Dikembalikan</b>";
            }else if(row.payment_status == 'cancel'){
              return "<b class='text-danger'>Dibatalkan</b>";
            }
          }
        },
        {
          "data": "action",
          width: 100
        }]
    });
  });
</script>