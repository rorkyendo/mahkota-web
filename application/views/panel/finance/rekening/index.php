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
            <select name="status" id="status" class="form-control">
              <option value="">.:Pilih Status:.</option>
              <option value="Y">Aktif</option>
              <option value="N">Tidak Aktif</option>
            </select>
          </div>
          <script>
            $('#status').val('<?php echo $status;?>')
          </script>
          <?php if(cekModul('tambahRekening') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/finance/tambahRekening/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Rekening</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Rekening</th>
                <th>Nama Rekening</th>
                <th>No Rekening</th>
                <th>An Rekening</th>
                <th>Status Rekening</th>
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
        "url": '<?php echo site_url(changeLink('panel/finance/rekening/cari')); ?>',
        "type": "POST",
        "data":{
          status:"<?php echo $status;?>"
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
          "data": "kode_rekening",
          width: 100,
        },
        {
          "data": "nama_rekening",
          width: 100
        },
        {
          "data": "no_rekening",
          width: 100
        },
        {
          "data": "an_rekening",
          width: 100
        },
        {
          "data": "status_rekening",
          width: 100,
          render: function(data, type, row) {
            if(row.status_rekening == 'Y'){
              return "<b class='text-success'>Aktif</b>";
            }else{
              return "<b class='text-danger'>Tidak Aktif</b>";
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