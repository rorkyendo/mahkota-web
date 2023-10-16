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
          <div class="panel-heading-btn">
            <a href="#" onclick="importProduk()" class="btn btn-xs btn-success pull-right"><i class="fa fa-download"></i> Import Produk</a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <div class="col-md-3">
            <select class="form-control select2" id="kategori_produk" onchange="cariKategoriProduk(this.value)">
              <option value="">.:Pilih Kategori Produk:.</option>
              <?php foreach ($kategori_produk as $key) : ?>
                <option value="<?php echo $key->id_kategori_produk; ?>"><?php echo $key->nama_kategori_produk; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <script type="text/javascript">
            function cariKategoriProduk(val) {
              location.replace('<?php echo base_url(changeLink('panel/produk/daftarProduk?status='.$status.'&id_kategori_produk=')); ?>' + val)
            }
          </script>
          <script>
            $('#kategori_produk').val('<?php echo $id_kategori_produk; ?>')
          </script>
          <div class="col-md-3">
            <select class="form-control select2" id="status" onchange="cariStatus(this.value)">
              <option value="">.:Pilih Status:.</option>
              <option value="active">Aktif</option>
              <option value="deactive">Tidak Aktif</option>
            </select>
          </div>
          <script type="text/javascript">
            function cariStatus(val) {
              location.replace('<?php echo base_url(changeLink('panel/produk/daftarProduk?id_kategori_produk='.$id_kategori_produk.'&status=')); ?>' + val)
            }
          </script>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <?php if(cekModul('tambahProduk') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/produk/tambahProduk/')); ?>" class="btn btn-xs btn-primary pull-right" style="margin-left: 10px;">Tambah Produk</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>
                  <button type="button" id="checkAll" class="btn btn-primary btn-xs">Centang semua</button>
                  <button type="button" onclick="deleteAll()" class="btn btn-danger btn-xs">Hapus Produk</button>
                  NO
                </th>
                <th>Barcode</th>
                <th>Nama Produk</th>
                <th>Nama Kategori</th>
                <th>Nama Brand</th>
                <th>Harga Modal</th>
                <th>Harga Jual Eceran</th>
                <th>Harga Jual Grosir</th>
                <th>Harga Jual Online</th>
                <th>Harga Diskon</th>
                <th>Wajib Asuransi</th>
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
        "url": '<?php echo site_url(changeLink('panel/produk/daftarProduk/cari')); ?>',
        "type": "POST",
        "data":{
          "uuid_toko":"<?php echo $this->session->userdata('uuid_toko');?>",
          "kategori_produk":"<?php echo $id_kategori_produk;?>",
          "status":"<?php echo $status;?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": null,
          width: 10,
          "sortable": false,
          render: function(data, type, row, meta) {
            var checkBox = "<input type='checkbox' name='produk[]' value='"+row.id_produk+"' id='produk"+row.id_produk+"'>"
            return checkBox + (meta.row + meta.settings._iDisplayStart + 1);
          }
        },
        {
          "data": "barcode",
          width: 100,
          render: function(data, type, row) {
            if (row.barcode_image) {
              return '<label for="produk'+row.id_produk+'"><img src="<?php echo base_url();?>'+row.barcode_image+'" class="image-responsive"></label>';
            }else{
              return "<label for='produk"+row.id_produk+"'><b class='text-danger'>Tidak ada gambar</b></label>";
            }
          }
        },
        {
          "data": "nama_produk",
          width: 100,
          render:function(data,type,row){
            return "<label for='produk"+row.id_produk+"'>"+row.nama_produk+"</label>";
          }
        },
        {
          "data": "nama_kategori_produk",
          width: 100,
        },
        {
          "data": "nama_brand",
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
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.harga_jual));
          }
        },
        {
          "data": "harga_jual_grosir",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.harga_jual_grosir));
          }
        },
        {
          "data": "harga_jual_online",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.harga_jual_online));
          }
        },
        {
          "data": "harga_diskon",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['bal','ID']).format(parseInt(row.harga_diskon));
          }
        },
        {
          "data": "wajib_asuransi",
          width: 100,
          render: function(data, type, row) {
            if(row.wajib_asuransi == 'Y'){
              return '<b class="text-success">Y</b>';
            }else{
              return '<b class="text-danger">N</b>';
            }
          }
        },
        {
          "data": "action",
          width: 100
        }]
    });
  });
</script>
<script>
  function importProduk(){
    $('#importProduk').modal('show');
  }
</script>

<!-- Modal -->
<div id="importProduk" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Data Produk</h4>
      </div>
      <div class="modal-body">
        <form class="" action="<?php echo base_url('panel/produk/tambahProduk/doImport'); ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="">Upload File Disini</label>
            <input type="file" accept="application/msexcel" name="dataProduk" class="form-control">
            <font color="red">*Upload data dengan format xlsx</font><br />
            <b><a href="<?php echo base_url('assets/excel/ImportProduk.xlsx'); ?>">Download format import data disini</a></b><br />
          </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-sm pac-dream text-white">Upload</a>
          </form>
      </div>
    </div>

  </div>
</div>
<script>
    $('#checkAll').toggle(function(){
        $('input:checkbox').attr('checked','checked');
        $(this).text('Hapus centang');
    },function(){
        $('input:checkbox').removeAttr('checked');
        $(this).text('Centang semua');
    })

    function deleteAll(){
      Swal.fire({
          title: 'Hapus produk?',
          text: "Produk yang sudah dihapus tidak dapat dikembalikan dan akan berpengaruh ke riwayat transaksi!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya hapus'
        }).then((result) => {
          if (result.value == true) {
            $('input[type=checkbox]:checked').each(function(){
              $.ajax({
                url:"<?php echo base_url('panel/produk/hapusProduk/');?>"+$(this).val(),
                type:"POST",
                success:function(){
                  Swal(
                    'Berhasil',
                    'Produk yang ditandai berhasil dihapus',
                    'success'
                  ).then(function(){
                    setTimeout(() => {
                      location.reload()
                    }, 300);
                  })
                }
              })
            })
          }
        })
    }
</script>