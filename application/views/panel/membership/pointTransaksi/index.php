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
            <?php if(cekModul('tambahTransaksiMember') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/membership/tambahTransaksiMember/')); ?>"
              class="btn btn-xs btn-primary pull-right">Tambah Transaksi Member</a>
            <?php endif; ?>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/membership/transaksiMember')); ?>">
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
              <br>
            </div>
          </form>
            <table class="table table-bordered table-striped table-hover" style="width:100%" id="table">
              <thead>
                <tr>
                  <th>ID Transaksi</th>
                  <th>Pelanggan</th>
                  <th>Nama Toko</th>
                  <th>Total Pembayaran</th>
                  <th>Total Point</th>
                  <th>Jenis Transaksi</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
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
      "filter": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/membership/transaksiMember/cari')); ?>',
        "type": "POST",
        "data":{
          "start_date":"<?php echo $start_date;?>",
          "end_date":"<?php echo $end_date;?>",
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_transaksi",
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
          "data": "jumlah_transaksi",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.jumlah_transaksi));
          }
        },
        {
          "data": "jumlah_point",
          "className":"text-right",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.jumlah_point));
          }
        },
        {
          "data": "jenis_transaksi",
          width: 100,
          render: function(data, type, row) {
            return row.jenis_transaksi.toUpperCase();
          }
        },
        {
          "data": "status_transaksi",
          width: 100,
          render: function(data, type, row) {
            if(row.status_transaksi == 'add'){
              return "<b class='text-success'>Berhasil</b>"
            }else{
              return "<b class='text-danger'>Batal</b>"
            }
          }
        },
        {
          "data": "action",
          width: 10
        }]
    });
  });
</script>