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
          <div class="panel-heading-btn">
            <a href="<?php echo base_url('panel/membership/pendaftaranMember/excel?status='.$status."&start_date=".$start_date."&end_date=".$end_date);?>" target="_blank" class="btn btn-xs btn-success"><i class="fa fa-download"></i>  Download Excel</a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form action="<?php echo base_url('panel/membership/pendaftaranMember');?>" method="get">
          <div class="col-md-4">
            <label for="">Dari Tgl</label>
            <input type="date" name="start_date" class="form-control" value="<?php echo $start_date;?>">
          </div>
          <div class="col-md-4">
            <label for="">Sampai Tgl</label>
            <input type="date" name="end_date" class="form-control" value="<?php echo $end_date;?>">
          </div>
          <div class="col-md-4">
            <label for="">Status</label>
            <select class="form-control" name="status" id="status">
              <option value="">.:Pilih Status:.</option>
              <option value="payed">Lunas</option>
              <option value="pending">Pending</option>
              <option value="process">Proses</option>
            </select>
          </div>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <div class="col-md-12">
            <br />
            <button class="btn btn-md btn-block btn-primary" type="submit"><i class="fa fa-search"></i> PENCARIAN</button>
            <br />
            <br />
          </div>
          </form>
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>ID Transaksi</th>
                <th>Nama Lengkap</th>
                <th>No WA</th>
                <th>Status</th>
                <th>Harga</th>
                <th>Waktu Transaksi</th>
                <th>Aksi</th>
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
      "order": [[0,'ASC']], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/membership/pendaftaranMember/cari')); ?>',
        "type": "POST",
        "data": {
          "status": "<?php echo $status; ?>",
          "start_date": "<?php echo $start_date; ?>",
          "end_date": "<?php echo $end_date; ?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [
        {
          "data": "id_transaksi",
          width: 100,
        },
        {
          "data": "nama_lengkap_pelanggan",
          width: 100,
        },
        {
          "data": "no_telp_pelanggan",
          width: 100,
        },
        {
          "data": "payment_status",
          width: 100,
          render: function(data, type, row) {
            if (row.payment_status == 'payed') {
                return '<b class="text-success">Lunas</b>';
            }else if(row.payment_status == 'pending'){
                return  '<b class="text-warning">Pending</b>';
            }else if(row.payment_status == 'process'){
                return  '<b class="text-primary">Diproses</b>';
            }else if(row.payment_status == 'cancel'){
                return   '<b class="text-danger">Dibatalkan</b>';
            }else if(row.payment_status == 'refund'){
                return    '<b class="text-danger">Dikembalikan</b>';
            }
          }
        },
        {
          "data": "total",
          width: 100,
          render: function(data, type, row) {
            return "Rp"+new Intl.NumberFormat(['ID','bal']).format(row.total);
          }
        },
        {
          "data": "created_time",
          width: 100,
        },
        {
          "data": "action",
          width: 100,
          render: function(data, type, row) {
            return row.action;
          }
        },
      ],
    });
  });
</script>