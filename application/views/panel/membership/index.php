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
            <select class="form-control" id="status" onchange="cariStatus(this.value)">
              <option value="">.:Pilih Status:.</option>
              <option value="active">Aktif</option>
              <option value="pending">Pending</option>
              <option value="expired">Expired</option>
              <option value="deleted">Nonaktif</option>
            </select>
          </div>
          <script type="text/javascript">
            function cariStatus(val) {
              location.replace('<?php echo base_url(changeLink('panel/membership/daftarMembership?status=')); ?>'+val)
            }
          </script>
          <script>
            $('#status').val('<?php echo $status; ?>')
          </script>
          <?php if(cekModul('tambahMembership') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/membership/tambahMembership/')); ?>" style="margin-left:10px;" class="btn btn-xs btn-primary pull-right">Tambah Membership</a>
            <a href="#" class="btn btn-xs btn-success pull-right" id="importMember">Import Membership</a>
          <?php endif; ?>
          <br />
          <br />
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>Kode Member</th>
                <th>Nama Member</th>
                <th>Potongan Member</th>
                <th>Waktu Berlaku</th>
                <th>Status</th>
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
        "url": '<?php echo site_url(changeLink('panel/membership/daftarMembership/cari')); ?>',
        "type": "POST",
        "data": {
          "status": "<?php echo $status; ?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [
        {
          "data": "barcode_member",
          width: 100,
        },
        {
          "data": "nama_lengkap",
          width: 100,
        },
        {
          "data": "potongan_member",
          width: 100,
          render: function(data, type, row, meta) {
            return row.potongan_member+' %';
          }
        },
        {
          "data": "expired_date",
          width: 100,
          render: function(data, type, row) {
            return row.expired_date;
          }
        },
        {
          "data": "status",
          width: 100,
          render: function(data, type, row) {
            if (row.status == 'active') {
              return '<b class="text-success">Aktif</b>';
            } else if (row.status == 'pending') {
              return '<b class="text-warning">Pending</b>';
            } else if (row.status == 'expired') {
              return '<b class="text-danger">Expired</b>';
            } else if (row.status == 'deleted') {
              return '<b class="text-danger">Nonaktif</b>';
            }
          }
        },
        {
          "data": "action",
          width: 100,
          render: function(data, type, row) {
            var detail = '<a href="<?php echo base_url(changeLink('panel/membership/detailMembership/')); ?>'+row.id_member+'" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>';
            return row.action +' '+ detail;
          }
        },
      ],
    });
  });

$('#importMember').click(function(){
  $("#import").modal('show')
})
</script>
<!-- Modal -->
<div id="import" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Data Membership</h4>
      </div>
      <div class="modal-body">
        <form class="" action="<?php echo base_url('panel/membership/tambahMembership/import'); ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="">Upload File Disini</label>
            <input type="file" accept="application/msexcel" name="dataMembership" class="form-control">
            <font color="red">*Upload data dengan format xlsx</font><br />
          </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-sm btn-success">Upload</a>
          </form>
      </div>
    </div>

  </div>
</div>