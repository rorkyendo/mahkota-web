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
            <a href="<?php echo base_url('panel/produk/sinkronProdukDigital');?>" onclick="sinkronProduk()" class="btn btn-xs btn-success pull-right"><i class="fa fa-download"></i> Sinkron Produk</a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>NO</th>
                <th>Brand</th>
                <th>Nama Produk</th>
                <th>Kode Produk</th>
                <th>Harga Modal</th>
                <th>Harga Jual</th>
                <th>Kategori</th>
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
        "url": '<?php echo site_url(changeLink('panel/produk/produkDigital/cari')); ?>',
        "type": "POST",
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": null,
          width: 10,
          "sortable": false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1 + "<a href='<?php echo base_url('panel/produk/hapusProdukDigital/');?>"+row.id_produk_digital+"' class='btn btn-danger btn-xs' onclick='return confirm(\"Apakah kamu yakin akan menghapus produk digital ini?\");'><i class='fa fa-times'></i></a>";
          }
        },
        {
          "data": "brand",
          width: 100,
        },
        {
          "data": "nama_produk_digital",
          width: 100,
        },
        {
          "data": "kode",
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
            return "<input type='number' value='" + row.harga_jual + "' onchange=\"updateHargaJual(this.value,'"+row.id_produk_digital+"')\" class='form-control'><br/><font color='red'><small>*Silahkan ganti harga jual dengan yang baru</small></font>";
          }
        },
        {
          "data": "kategori",
          width: 100,
        }]
    });
  });
</script>
<!-- Modal -->
<script>
  function updateHargaJual(val,id){
    $.ajax({
      url:"<?php echo base_url('panel/produk/updateProdukDigital/');?>"+id,
      type:"POST",
      data:{
        harga_jual:val
      },success:function(){
        swal({  
          title: 'Berhasil!',
          text: 'Harga jual berhasil diupdate',
          type: 'success'
        }).then(function () {
            location.reload();
        });
      }
    })
  }
</script>