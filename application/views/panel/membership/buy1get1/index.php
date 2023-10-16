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
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/membership/buy1get1')); ?>">
            <div class="col-md-6">
              <label for="">Dari Tgl</label>
              <input type="date" class="form-control" name="start_date" value="<?php echo $start_date;?>">
            </div>
            <div class="col-md-6">
              <label for="">Sampai Tgl</label>
              <input type="date" class="form-control" name="end_date" value="<?php echo $end_date;?>">
            </div>
            <div class="col-md-12">
              <br>
              <button type="submit" class="btn btn-md btn-block btn-template text-white"><i class="fa fa-search"></i> Cari Data</button>
            </div>
          </form>
          <div class="col-md-12">
            <br>
            <br>
            <table class="table table-bordered table-striped table-hover" style="width:100%" id="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tgl Pengajuan</th>
                  <th>Pelanggan</th>
                  <th>Kode Klaim</th>
                  <th>Foto Struk</th>
                  <th>Tgl Klaim</th>
                  <th>Status</th>
                  <th>Produk</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>
    </div>
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
      "order": [[1,'DESC']], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/membership/buy1get1/cari')); ?>',
        "type": "POST",
        "data":{
          "start_date":"<?php echo $start_date;?>",
          "end_date":"<?php echo $end_date;?>",
        }
      },
      //Set column definition initialisation properties.
      "columns": [
        {
          "data": "id_buy1get1",
          width: 10
        },
        {
          "data": "created_time",
          width: 100
        },
        {
          "data": "nama_pelanggan",
          width: 100,
          render: function(data, type, row) {
            return row.nama_pelanggan;
          }
        },
        {
          "data": "kode_klaim",
          width: 100
        },
        {
          "data": "foto_struk",
          width: 100,
          render: function(data, type, row) {
            return '<a href="<?php echo base_url(); ?>' + row.foto_struk + '" target="_blank"><img src="<?php echo base_url(); ?>' + row.foto_struk + '" width="100px"></a>';
          }
        },
        {
          "data": "tgl_klaim",
          width: 100
        },
        {
          "data": "status_klaim",
          width: 100,
          render: function(data, type, row) {
            if (row.status_klaim == 'pending') {
                return '<span class="label label-warning">Pending</span>';
            } else if (row.status_klaim == 'dikonfirmasi') {
                return '<span class="label label-primary">Dikonfirmasi</span>';
            } else if (row.status_klaim == 'diklaim') {
                return '<span class="label label-success">Diklaim</span>';
            }
          }
        },
        {
          "data": "nama_produk_klaim",
          width: 100,
        },
        {
          "data": null,
          width: 100,
          render: function(data, type, row) {
            var konfirmasi = '';
            var hapus = '';
            var redeem = '';
            if (row.status_klaim == 'pending') {
                konfirmasi = '<a href="<?php echo base_url(changeLink('panel/membership/buy1get1/konfirmasi/')); ?>' + row.id_buy1get1 + '" class="btn btn-xs btn-primary" onclick="return confirm(\'Apakah kamu yakin akan mengkonfirmasi data ini?\')"><i class="fa fa-check"></i> Konfirmasi</a>';
                hapus = '<a href="<?php echo base_url(changeLink('panel/membership/buy1get1/hapus/')); ?>' + row.id_buy1get1 + '" class="btn btn-xs btn-danger" onclick="return confirm(\'Apakah kamu yakin akan menghapus data ini?\')"><i class="fa fa-times"></i> Hapus</a>';
            } else if (row.status_klaim == 'dikonfirmasi') {
                // modal dengan showModal onclick function set id_buy1get1
                redeem = '<a href="#" class="btn btn-xs btn-success" onclick="showModal(\'' + row.id_buy1get1 + '\',\'' + row.foto_struk + '\')"><i class="fa fa-check"></i> Redeem</a>';
            }
            return konfirmasi + ' ' + hapus + ' ' + redeem;
          }
        }
        ]
    });
  });
</script>

<script type="text/javascript">
  function showModal(id_buy1get1,foto_struk) {
    $('#modalRedeem').modal('show');
    $('#id_buy1get1').val(id_buy1get1);
    $('#foto_struk_redeem').prop('src','<?php echo base_url();?>'+foto_struk);
    $('#link_produk_redeem').prop('href','<?php echo base_url();?>'+foto_struk);
  }

  function submitRedeemForm() {
    var id_buy1get1 = $('#id_buy1get1').val();
    var produk_klaim = $('#produk_klaim').val();

    $.ajax({
      type: "POST",
      url: "<?php echo base_url(changeLink('panel/membership/buy1get1/redeem')); ?>",
      data: {
        id_buy1get1: id_buy1get1,
        produk_klaim: produk_klaim
      },
      success: function(response) {
        if(response == 'success') {
          alert('Redeem berhasil');
          $('#modalRedeem').modal('hide');
          table.ajax.reload();
        } else {
          alert('Redeem gagal');
        }
      },
      error: function(xhr, status, error) {
        // Handle error response here, for example, show an error message
        alert('Redeem failed: ' + xhr.responseText);
      }
    });
  }
</script>

<!-- Modal -->
<div class="modal fade" id="modalRedeem" role="dialog" aria-labelledby="modalRedeemLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="modalRedeemLabel">Redeem Produk</h5>
      </div>
      <div class="modal-body">
        <form id="formRedeem" class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/membership/buy1get1/redeem')); ?>">
          <input type="hidden" name="id_buy1get1" id="id_buy1get1" value="">
          <div class="col-md-12">
            <center>
              <a id="link_produk_redeem" target="_blank"><img src="" class="img-responsive" style="width:120px;height:240px;" id="foto_struk_redeem" alt="" srcset=""></a>
            </center>
            <br>
          </div>
          <div class="form-group">
            <label for="produk_klaim" class="col-md-3 control-label">Produk Redeem</label>
            <div class="col-md-9">
              <select class="form-control select2" name="produk_klaim" id="produk_klaim" style="width:100%" required>
                <option value="">Silahkan Pilih Produk</option>
                <?php foreach ($produk as $item) { ?>
                  <option value="<?php echo $item->id_produk; ?>"><?php echo $item->nama_produk; ?> (Rp<?php echo number_format($item->harga_jual_online,0,'.','.'); ?>)</option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitRedeemForm()">Redeem</button>
      </div>
    </div>
  </div>
</div>


