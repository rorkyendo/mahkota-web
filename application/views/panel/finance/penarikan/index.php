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
          <?php if(cekModul('tambahPenarikan') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/finance/tambahPenarikan/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Penarikan</a>
          <?php endif; ?>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/finance/daftarPenarikan')); ?>">
            <div class="col-md-6">
              <label for="">Dari Tgl</label>
              <input type="date" class="form-control" name="start_date" value="<?php echo $start_date;?>">
            </div>
            <div class="col-md-6">
              <label for="">Sampai Tgl</label>
              <input type="date" class="form-control" name="end_date" value="<?php echo $end_date;?>">
            </div>
            <?php if(cekModul('konfirmasiPenarikan') == TRUE): ?>
            <div class="col-md-8">
              <label for="">Toko</label>
              <select name="uuid_toko" id="uuid_toko" class="form-control select2">
                <option value="">.:Pilih Toko:.</option>
                <?php foreach($toko as $key):?>
                  <option value="<?php echo $key->uuid_toko;?>"><?php echo $key->nama_toko;?></option>
                <?php endforeach;?>
              </select>
              <script>
                $('#uuid_toko').val('<?php echo $uuid_toko;?>');
              </script>
            </div>
            <?php endif; ?>
            <div class="col-md-4">
              <label for="">Status</label>
              <select name="status_penarikan" id="status_penarikan" class="form-control">
                <option value="">.:Pilih Status Penarikan:.</option>
                <option value="pending">Pending</option>
                <option value="sukses">Sukses</option>
              </select>
              <script>
                $('#status_penarikan').val('<?php echo $status;?>');
              </script>
            </div>
            <div class="col-md-12">
              <br>
              <button type="submit" class="btn btn-md btn-block btn-template text-white"><i class="fa fa-search"></i> Cari Data</button>
              <br />
              <br />
            </div>
          </form>
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Toko</th>
                <th>Nama Perequest</th>
                <th>Tgl Request</th>
                <th>Nama Penerima</th>
                <th>Tgl terima</th>
                <th>Status Penarikan</th>
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
      "filter": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/finance/daftarPenarikan/cari')); ?>',
        "type": "POST",
        "data":{
          status_penarikan:"<?php echo $status;?>",
          uuid_toko:"<?php echo $uuid_toko;?>",
          start_date:"<?php echo $start_date;?>",
          end_date:"<?php echo $end_date;?>"
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
          "data": "nama_toko",
          width: 100,
        },
        {
          "data": "nama_perequest",
          width: 100
        },
        {
          "data": "request_date",
          width: 100
        },
        {
          "data": "nama_accept",
          width: 100
        },
        {
          "data": "accept_date",
          width: 100
        },
        {
          "data": "status_penarikan",
          width: 100,
          render: function(data, type, row) {
            if(row.status_penarikan == 'sukses'){
              return "<b class='text-success'>Sukses</b>";
            }else if(row.status_penarikan == 'pending'){
              return "<b class='text-warning'>Pending</b>";
            }else{
              return "<b class='text-danger'>Ditolak</b>";
            }
          }
        },
        {
          "data": null,
          width: 100,
          render: function(data, type, row) {
            return '';
          }
        },
      ],
    });
  });
</script>