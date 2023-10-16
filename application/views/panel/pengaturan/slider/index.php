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
              location.replace('<?php echo base_url(changeLink('panel/pengaturan/daftarSlider?status=')); ?>' + val)
            }
          </script>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <?php if(cekModul('tambahSlider') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/pengaturan/tambahSlider/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Slider</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>Urutan</th>
                <th>Slider Mobile</th>
                <th>Slider Web</th>
                <th>Tipe Produk</th>
                <th>Kategori Produk</th>
                <th>Brand</th>
                <th>Produk</th>
                <th>Judul Slider</th>
                <th>Teks Slider</th>
                <th>Posisi Teks</th>
                <th>Status Slider</th>
                <th>Url Slider</th>
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
        "url": '<?php echo site_url(changeLink('panel/pengaturan/daftarSlider/cari')); ?>',
        "type": "POST",
        "data":{
          "status":"<?php echo $status;?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "urutan_slider",
          width: 10,
        },
        {
          "data": "gambar_slider",
          width: 100,
          render: function(data, type, row) {
            if(row.gambar_slider){
              return "<img src='<?php echo base_url();?>"+row.gambar_slider+"' class='img-responsive' alt='"+row.judul_slider+"'>"
            }else{
              return "Tidak ada gambar";
            }
          }
        },
        {
          "data": "gambar_slider_2",
          width: 100,
          render: function(data, type, row) {
            if(row.gambar_slider_2){
              return "<img src='<?php echo base_url();?>"+row.gambar_slider_2+"' class='img-responsive' alt='"+row.judul_slider+"'>"
            }else{
              return "Tidak ada gambar";
            }
          }
        },
        {
          "data": "tipe_produk",
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
          "data": "judul_slider",
          width: 100
        },
        {
          "data": "text_slider",
          width: 100
        },
        {
          "data": "posisi_text",
          width: 100,
          render: function(data, type, row) {
            if (row.posisi_text == 'L') {
              return "Kiri";
            }else if(row.posisi_text == 'R'){
              return "Kanan";
            }else{
              return "Tengah";
            }
          }
        },
        {
          "data": "status_slider",
          width: 100,
          render: function(data, type, row) {
            if (row.status_slider == 'Y') {
              return "<b class='text-success'>Aktif</b>"
            }else{
              return "<b class='text-danger'>Tidak aktif</b>"
            }
          }
        },
        {
          "data": "url_slider",
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