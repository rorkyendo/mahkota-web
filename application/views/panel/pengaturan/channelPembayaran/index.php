<!-- begin #content -->
<?php if($this->session->userdata('hak_akses') != 'member'){?>
	<div id="content" class="content">
<?php }else{ ?>
	<div id="content" class="content" style="margin-left: 0px;">
<?php } ?>  <!-- begin breadcrumb -->
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
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <?php if(cekModul('createChannelPembayaran') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/pengaturan/createChannelPembayaran/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Channel Pembayaran</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Channel Pembayaran</th>
                <th>Nama Channel Pembayaran</th>
                <th>Metode Pembayaran</th>
                <th>Tipe Pembayaran</th>
                <th>No Rekening</th>
                <th>A/N Rekening</th>
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
      "filter": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/pengaturan/channelPembayaran/cari')); ?>',
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": null,
          width: 10,
          "sortable": false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          "data": "payment_channel",
          width: 100,
        },
        {
          "data": "nama_payment_channel",
          width: 100
        },
        {
          "data": "payment_method",
          width: 100,
        },
        {
          "data": "payment_type",
          width: 100
        },
        {
          "data": "no_rekening",
          width: 100,
        },
        {
          "data": "an_rekening",
          width: 100
        },
        {
          "data": "action",
          width: 100
        },
      ],
    });
  });
</script>