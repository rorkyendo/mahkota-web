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
              location.replace('<?php echo base_url(changeLink('panel/inventori/daftarInventori?id_lokasi_penyimpanan'.$id_lokasi_penyimpanan.'&id_gudang=')); ?>' + val)
            }
          </script>
          <script>
            $('#gudang').val('<?php echo $id_gudang; ?>')
          </script>
          <div class="col-md-3">
            <select class="form-control select2" id="lokasi_penyimpanan" onchange="cariLokasiPenyimpanan(this.value)">
              <option value="">.:Pilih Lokasi Penyimpanan:.</option>
              <?php foreach ($lokasiPenyimpanan as $key) : ?>
                <option value="<?php echo $key->id_lokasi_penyimpanan; ?>"><?php echo $key->kode_lokasi_penyimpanan;?> | <?php echo $key->nama_lokasi_penyimpanan; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <script type="text/javascript">
            function cariLokasiPenyimpanan(val) {
              location.replace('<?php echo base_url(changeLink('panel/inventori/daftarInventori?id_gudang='.$id_gudang.'&id_lokasi_penyimpanan=')); ?>' + val)
            }
          </script>
          <script>
            $('#lokasi_penyimpanan').val('<?php echo $id_lokasi_penyimpanan; ?>')
          </script>
          <?php if(cekModul('tambahInventori') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/inventori/tambahInventori/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Inventori</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Barcode</th>
                <th>Nama Inventori</th>
                <th>Qty</th>
                <th>Harga Modal</th>
                <th>Harga Jual Toko</th>
                <th>Harga Jual Grosir</th>
                <th>Harga Jual Online</th>
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
        "url": '<?php echo site_url(changeLink('panel/inventori/daftarInventori/cari')); ?>',
        "type": "POST",
        "data":{
          "uuid_toko":"<?php echo $this->session->userdata('uuid_toko');?>",
          "gudang":"<?php echo $id_gudang;?>",
          "lokasi_penyimpanan":"<?php echo $id_lokasi_penyimpanan;?>",
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
          "data": "barcode",
          width: 100,
          render: function(data, type, row) {
            if (row.barcode_image) {
              return '<img src="<?php echo base_url();?>'+row.barcode_image+'" class="image-responsive">';
            }else{
              return "<b class='text-danger'>Tidak ada gambar</b>";
            }
          }
        },
        {
          "data": "nama_inventori",
          width: 100,
        },
        {
          "data": "qty",
          width: 100,
        },
        {
          "data": "harga_modal",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.harga_modal);
          }
        },
        {
          "data": "harga_jual",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.harga_jual);
          }
        },
        {
          "data": "harga_jual_grosir",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.harga_jual_grosir);
          }
        },
        {
          "data": "harga_jual_online",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(row.harga_jual_online);
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