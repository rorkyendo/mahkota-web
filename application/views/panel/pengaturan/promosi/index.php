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
            <select class="form-control select2" id="status" onchange="cariStatus(this.value)">
              <option value="">.:Pilih Status:.</option>
              <option value="Y">Aktif</option>
              <option value="N">Tidak Aktif</option>
            </select>
          </div>
          <script type="text/javascript">
            function cariStatus(val) {
              location.replace('<?php echo base_url(changeLink('panel/pengaturan/daftarPromosi?status=')); ?>' + val)
            }
          </script>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <?php if(cekModul('tambahPromosi') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/pengaturan/tambahPromosi/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Promosi</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>Urutan</th>
                <th>Jenis Promosi</th>
                <th>Tipe Promosi</th>
                <th>Lokasi Promosi</th>
                <th>Promosi Sampai</th>
                <th>Judul Promosi</th>
                <th>Teks Promosi</th>
                <th>Kategori Produk</th>
                <th>Brand</th>
                <th>Produk</th>
                <th>Status Promosi</th>
                <th>Url Promosi</th>
                <th>File Promosi</th>
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
        "url": '<?php echo site_url(changeLink('panel/pengaturan/daftarPromosi/cari')); ?>',
        "type": "POST",
        "data":{
          "status":"<?php echo $status;?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "urutan_promosi",
          width: 10,
        },
        {
          "data": "jenis_promosi",
          width: 100,
          render: function(data, type, row) {
            if (row.jenis_promosi == '1') {
              return "Video";
            }else if (row.jenis_promosi == '2') {
              return "Teks";
            }else if (row.jenis_promosi == '3') {
              return "Gambar";
            }else{
              return "Tidak dipilih";
            }
          }
        },
        {
          "data": "tipe_promosi",
          width: 100,
          render: function(data, type, row) {
            if (row.tipe_promosi == '1') {
              return "Layanan";
            }else if (row.tipe_promosi == '2') {
              return "Diskon";
            }else if (row.tipe_promosi == '3') {
              return "Promo Hari ini";
            }else{
              return "Tidak dipilih";
            }
          }
        },
        {
          "data": "lokasi_promosi",
          width: 100
        },
        {
          "data": "waktu_promosi",
          width: 100
        },
        {
          "data": "judul_promosi",
          width: 100
        },
        {
          "data": "text_promosi",
          width: 100
        },
        {
          "data": "nama_kategori_produk",
          width: 100
        },
        {
          "data": "nama_brand",
          width: 100
        },
        {
          "data": "nama_produk",
          width: 100
        },
        {
          "data": "status_promosi",
          width: 100,
          render: function(data, type, row) {
            if (row.status_promosi == 'Y') {
              return "<b class='text-success'>Aktif</b>"
            }else{
              return "<b class='text-danger'>Tidak aktif</b>"
            }
          }
        },
        {
          "data": "url_promosi",
          width: 100
        },
        {
          "data": "file_promosi",
          width: 100,
          render: function(data, type, row) {
            if (row.file_promosi) {
              if(row.jenis_promosi=='1'){
                return "<video width='100%' controls><source src='<?php echo base_url();?>"+row.file_promosi+"' type='video/mp4'></video>"
              }else{
                return "<img src='<?php echo base_url();?>"+row.file_promosi+"' class='img-responsive'>"
              }
            }else{
              return "<b class='text-danger'>Tidak ada video</b>"
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