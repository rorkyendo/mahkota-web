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
            <select class="form-control select2" id="kode_induk_group_kas" onchange="cariIndukGroupKas(this.value)">
              <option value="">.:Pilih Group Kas:.</option>
              <?php foreach ($indukGroup as $key) : ?>
                <option value="<?php echo $key->kode_induk_group_kas; ?>"><?php echo $key->kode_induk_group_kas;?> | <?php echo $key->nama_induk_group_kas; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <script type="text/javascript">
            function cariIndukGroupKas(val) {
              location.replace('<?php echo base_url(changeLink('panel/finance/daftarGroupAkun?kode_induk_group_kas=')); ?>' + val)
            }
          </script>
          <script>
            $('#kode_induk_group_kas').val('<?php echo $kode_induk_group_kas; ?>')
          </script>
          <?php if(cekModul('tambahGroupAkun') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/finance/tambahGroupAkun/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah  Group Akun</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Kode Induk Group Kas</th>
                <th>Kode Group Kas</th>
                <th>Nama Group Kas</th>
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
        "url": '<?php echo site_url(changeLink('panel/finance/daftarGroupAkun/cari')); ?>',
        "type": "POST",
        "data":{
          "kode_induk_group_kas":"<?php echo $kode_induk_group_kas;?>"
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
          "data": "kode_induk_group_kas",
          width: 100,
        },
        {
          "data": "kode_group_kas",
          width: 100
        },
        {
          "data": "nama_group_kas",
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