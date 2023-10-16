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
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/laporan/laporanPenjualan')); ?>">
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
            <div class="col-md-4">
              <label for="">Produk</label>
              <select name="produk" id="produk" class="form-control select2" style="width:100%">
                <option value="">.:Pilih Produk:.</option>
                <?php foreach($produk as $key):?>
                <option value="<?php echo $key->id_produk;?>"><?php echo $key->nama_produk;?></option>
                <?php endforeach;?>
              </select>
              <script>
                $('#produk').val('<?php echo $id_produk;?>');
              </script>
            </div>
            <div class="col-md-4">
              <label for="">Status Penjualan</label>
              <select name="status_order" id="status_order" class="form-control">
                <option value="">.:Pilih Status Penjualan:.</option>
                <option value="add">Dijual</option>
                <option value="refund">Dikembalikan</option>
              </select>
              <script>
                $('#status_order').val('<?php echo $status_order;?>');
              </script>
            </div>
            <div class="col-md-4">
              <label for="">Jenis Transaksi</label>
              <select name="jenis_transaksi" id="jenis_transaksi" class="form-control">
                <option value="">.:Pilih Jenis Transaksi:.</option>
                <option value="add">Produk</option>
                <option value="jasa">Jasa</option>
                <option value="member">Member</option>
              </select>
              <script>
                $('#jenis_transaksi').val('<?php echo $jenis_transaksi;?>');
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
                  <th>ID Order / Transaksi</th>
                  <th>Pelanggan</th>
                  <th>Sales</th>
                  <th>Jenis</th>
                  <th>Produk</th>
                  <th>Modal</th>
                  <th>Jual</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                  <th>Status</th>
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
        "url": '<?php echo site_url(changeLink('panel/laporan/laporanPenjualan/cari')); ?>',
        "type": "POST",
        "data":{
          "start_date":"<?php echo $start_date;?>",
          "end_date":"<?php echo $end_date;?>",
          "sales":"<?php echo $id_sales;?>",
          "payment_status":"<?php echo $payment_status;?>",
          "produk":"<?php echo $id_produk;?>",
          "status_order":"<?php echo $status_order;?>",
          "jenis_transaksi":"<?php echo $jenis_transaksi;?>",
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_order",
          width: 10,
          render: function(data, type, row) {
            return "<a class='btn btn-xs btn-primary' href='<?php echo base_url('panel/transaksi/detailTransaksi/');?>"+row.transaksi+"'>"+row.id_order+" / "+row.transaksi+"</a>";
          }
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
          "data": "jenis_transaksi",
          width: 100
        },
        {
          "data": "nama_produk",
          width: 100
        },
        {
          "data": "capital_price",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.capital_price);
          }
        },
        {
          "data": "selling_price",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.selling_price);
          }
        },
        {
          "data": "qty",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat().format(row.qty);
          }
        },
        {
          "data": "subtotal",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.subtotal);
          }
        },
        {
          "data": "status_order",
          width: 100,
          render: function(data, type, row) {
            if(row.status_order == 'add'){
              return "<b class='text-success'>Terjual</b>";
            }else if(row.status_order == 'refund'){
              return "<b class='text-danger'>Dikembalikan</b>";
            }else if(row.status_order == 'belum_lunas'){
              return "<b class='text-warning'>Pending</b>";
            }
          }
        }]
    });
  });
</script>