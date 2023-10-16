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
          <?php if(cekModul('tambahTemplatePesan') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/broadcast/tambahTemplatePesan/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Template Pesan</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>Tgl Kirim</th>
                <th>Isi Pesan</th>
                <th>No Tujuan</th>
                <th>Nama Penerima</th>
                <th>Pengirim</th>
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
        "url": '<?php echo site_url(changeLink('panel/broadcast/riwayatKirim/cari')); ?>',
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "created_time",
          width: 100,
        },
        {
          "data": "isi_pesan",
          width: 100
        },
        {
          "data": "no_wa",
          width: 100,
        },
        {
          "data": "nama_penerima",
          width: 100,
        },
        {
          "data": "pengirim",
          width: 100,
        },
      ],
    });
  });
</script>