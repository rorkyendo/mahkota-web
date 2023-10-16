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
            <select class="form-control select2" id="gudang" onchange="cariGudang(this.value)">
              <option value="">.:Pilih Gudang:.</option>
              <?php foreach ($gudang as $key) : ?>
                <option value="<?php echo $key->id_gudang; ?>"><?php echo $key->kode_gudang;?> | <?php echo $key->nama_gudang; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <script type="text/javascript">
            function cariGudang(val) {
              location.replace('<?php echo base_url(changeLink('panel/inventori/lokasiPenyimpanan?id_gudang=')); ?>' + val)
            }
          </script>
          <script>
            $('#gudang').val('<?php echo $id_gudang; ?>')
          </script>
          <?php if(cekModul('tambahLokasiPenyimpanan') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/inventori/tambahLokasiPenyimpanan/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Lokasi Penyimpanan</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Kode Lokasi Penyimpanan</th>
                <th>Nama Lokasi Penyimpanan</th>
                <th>Kode Gudang</th>
                <th>Nama Gudang</th>
                <th>Alamat Gudang</th>
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
      "order": [], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/inventori/lokasiPenyimpanan/cari')); ?>',
        "type": "POST",
        "data":{
          "uuid_toko":"<?php echo $this->session->userdata('uuid_toko');?>",
          "id_gudang":"<?php echo $id_gudang;?>",
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
          "data": "kode_lokasi_penyimpanan",
          width: 100,
        },
        {
          "data": "nama_lokasi_penyimpanan",
          width: 100,
        },
        {
          "data": "kode_gudang",
          width: 100,
        },
        {
          "data": "nama_gudang",
          width: 100
        },
        {
          "data": "alamat_gudang",
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