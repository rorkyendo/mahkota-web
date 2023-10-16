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
          <div class="col-md-3">
            <select class="form-control" id="status" onchange="cariStatus(this.value)">
              <option value="">.:Pilih Status:.</option>
              <option value="active">Aktif</option>
              <option value="deleted">Tidak Aktif</option>
            </select>
          </div>
          <script type="text/javascript">
            function cariStatus(val) {
              location.replace('<?php echo base_url(changeLink('panel/membership/daftarTipeMembership?status=')); ?>'+val)
            }
          </script>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <?php if(cekModul('tambahTipeMembership') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/membership/tambahTipeMembership/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Tipe Membership</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>Kode Tipe Member</th>
                <th>Nama Tipe Member</th>
                <th>Gambar Depan</th>
                <th>Gambar Belakang</th>
                <th>Biaya Pendaftaran</th>
                <th>Biaya Upgrade</th>
                <th>Potongan Member</th>
                <th>Waktu Berlaku (Hari)</th>
                <th>Status</th>
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
        "url": '<?php echo site_url(changeLink('panel/membership/daftarTipeMembership/cari')); ?>',
        "type": "POST",
        "data": {
          "status": "<?php echo $status; ?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [
        {
          "data": "kode_tipe_member",
          width: 100,
        },
        {
          "data": "nama_tipe_member",
          width: 100,
          render: function(data, type, row, meta) {
            return row.nama_tipe_member+"<br/><img src='<?php echo base_url();?>"+row.icon_member+"' class='img-responsive w-20'>"
          }
        },
        {
          "data": null,
          width: 100,
          render: function(data, type, row, meta) {
            return "<img src='<?php echo base_url();?>"+row.cover_depan+"' class='img-responsive w-100'>"
          }
        },
        {
          "data": null,
          width: 100,
          render: function(data, type, row, meta) {
            return "<img src='<?php echo base_url();?>"+row.cover_belakang+"' class='img-responsive w-100'>"
          }
        },
        {
          "data": "biaya_pendaftaran",
          width: 100,
          render: function(data, type, row, meta) {
            return new Intl.NumberFormat(['bal','id']).format(row.biaya_pendaftaran);
          }
        },
        {
          "data": "biaya_upgrade",
          width: 100,
          render: function(data, type, row, meta) {
            return new Intl.NumberFormat(['bal','id']).format(row.biaya_upgrade);
          }
        },
        {
          "data": "potongan_member",
          width: 100,
          render: function(data, type, row, meta) {
            return row.potongan_member+' %';
          }
        },
        {
          "data": "waktu_berlaku",
          width: 100
        },
        {
          "data": "status_tipe",
          width: 100,
          render: function(data, type, row) {
            if (row.status_tipe == 'active') {
              return '<b class="text-success">Aktif</b>';
            }else{
              return '<b class="text-danger">Tidak aktif</b>';
            }
          }
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