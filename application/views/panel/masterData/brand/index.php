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
            <select class="form-control" id="kategori" onchange="cariBrand(this.value)">
              <option value="">.:Pilih Kategori:.</option>
              <?php foreach($kategori as $key):?>
                <option value="<?php echo $key->id_kategori_produk;?>"><?php echo $key->nama_kategori_produk;?></option>
              <?php endforeach;?>
            </select>
          </div>
          <script type="text/javascript">
            function cariBrand(val) {
              location.replace('<?php echo base_url(changeLink('panel/masterData/daftarBrand?id_kategori=')); ?>' + val)
            }
          </script>
          <script>
            $('#kategori').val('<?php echo $id_kategori; ?>')
          </script>
          <?php if(cekModul('tambahBrand') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/masterData/tambahBrand/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Brand</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Gambar Brand</th>
                <th>Nama Brand</th>
                <th>Kategori</th>
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
        "url": '<?php echo site_url(changeLink('panel/masterData/daftarBrand/cari')); ?>',
        "type": "POST",
        "data":{
          "kategori":"<?php echo $id_kategori;?>"
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
          "data": "nama_brand",
          width: 10,
          render: function(data, type, row) {
            if (row.gambar_brand) {
              return "<img src='<?php echo base_url();?>"+row.gambar_brand+"' class='img-responsive' style='height:50px;width:50px;'>";
            }else{
              return "<b class='text-danger'>Tidak ada gambar</b>";
            }
          }
        },
        {
          "data": "nama_brand",
          width: 100,
        },
        {
          "data": "nama_kategori_produk",
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