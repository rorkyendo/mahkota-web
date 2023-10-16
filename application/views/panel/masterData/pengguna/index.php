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
          <?php echo $this->session->flashdata('notifpass'); ?>
          <?php echo $this->session->flashdata('notif'); ?>
          <div class="col-md-3">
            <select class="form-control select2" id="hak_akses" onchange="cariHakAses(this.value)">
              <option value="">.:Pilih Hak Akses:.</option>
              <?php foreach ($getHakAkses as $key) : ?>
                <option value="<?php echo $key->nama_hak_akses; ?>"><?php echo $key->nama_hak_akses; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <script type="text/javascript">
            function cariHakAses(val) {
              location.replace('<?php echo base_url(changeLink('panel/masterData/daftarPengguna?status='.$status.'uuid_toko='.$uuid_toko.'&hak_akses=')); ?>' + val)
            }
          </script>
          <script>
            $('#hak_akses').val('<?php echo $hak_akses; ?>')
          </script>
          <div class="col-md-3">
            <select class="form-control select2" id="uuid_toko" onchange="cariToko(this.value)">
              <option value="">.:Pilih Toko:.</option>
              <?php foreach ($getToko as $key) : ?>
                <option value="<?php echo $key->uuid_toko; ?>"><?php echo $key->nama_toko; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <script type="text/javascript">
            function cariToko(val) {
              location.replace('<?php echo base_url(changeLink('panel/masterData/daftarPengguna?status='.$status.'&hak_akses='.$hak_akses.'&uuid_toko=')); ?>' + val)
            }
          </script>
          <script>
            $('#uuid_toko').val('<?php echo $uuid_toko; ?>')
          </script>
          <div class="col-md-3">
            <select class="form-control" id="status" onchange="cariStatus(this.value)">
              <option value="">.:Pilih Status:.</option>
              <option value="actived">Aktif</option>
              <option value="deleted">Tidak Aktif</option>
              <option value="pending">Pending</option>
            </select>
          </div>
          <script type="text/javascript">
            function cariStatus(val) {
              location.replace('<?php echo base_url(changeLink('panel/masterData/daftarPengguna?hak_akses='.$hak_akses.'uuid_toko='.$uuid_toko.'&status=')); ?>'+val)
            }
          </script>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <?php if(cekModul('tambahPengguna') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/masterData/tambahPengguna/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Pengguna</a>
          <?php endif;?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Nama Pengguna</th>
                <th>Username</th>
                <th>Hak Akses</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <?php echo $this->session->flashdata('notif'); ?>
          <?php echo $this->session->flashdata('notifpass'); ?>
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
        "url": '<?php echo site_url(changeLink('panel/masterData/daftarPengguna/cari')); ?>',
        "type": "POST",
        "data": {
          "hak_akses": "<?php echo $hak_akses; ?>",
          "toko": "<?php echo $uuid_toko; ?>",
          "status": "<?php echo $status; ?>"
        }
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
          "data": "nama_lengkap",
          width: 100,
        },
        {
          "data": "username",
          width: 100
        },
        {
          "data": "hak_akses",
          width: 100
        },
        {
          "data": "status",
          width: 100,
          render: function(data, type, row) {
            if (row.status == 'actived') {
              return '<b class="text-success">Sudah aktif</b>';
            }else{
              return '<b class="text-danger">Belum aktif</b>';
            }
          }
        },
        {
          "data": "last_login",
          width: 100
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