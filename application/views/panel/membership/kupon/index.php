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
          <?php if(cekModul('tambahKupon') == TRUE): ?>
            <a href="<?php echo base_url(changeLink('panel/membership/tambahKupon/')); ?>" class="btn btn-xs btn-primary pull-right">Tambah Voucher</a>
          <?php endif; ?>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="get" action="<?php echo base_url(changeLink('panel/membership/daftarKupon')); ?>">
            <div class="col-md-6">
              <label for="">Dari Tgl</label>
              <input type="date" class="form-control" name="start_date" value="<?php echo $start_date;?>">
            </div>
            <div class="col-md-6">
              <label for="">Sampai Tgl</label>
              <input type="date" class="form-control" name="end_date" value="<?php echo $end_date;?>">
            </div>
            <div class="col-md-8">
              <label for="">Tipe Member</label>
              <select name="id_tipe_member" id="id_tipe_member" class="form-control">
                <option value="">.:Pilih Tipe Member:.</option>
                <?php foreach($tipe_member as $key):?>
                  <option value="<?php echo $key->id_tipe_member;?>"><?php echo $key->nama_tipe_member;?></option>
                <?php endforeach;?>
              </select>
              <script>
                $('#id_tipe_member').val('<?php echo $id_tipe_member;?>');
              </script>
            </div>
            <div class="col-md-4">
              <label for="">Status</label>
              <select name="status" id="status" class="form-control">
                <option value="">.:Pilih Status:.</option>
                <option value="Y">Aktif</option>
                <option value="N">Tidak Aktif</option>
              </select>
              <script>
                $('#status').val('<?php echo $status;?>');
              </script>
            </div>
            <div class="col-md-12">
              <br>
              <button type="submit" class="btn btn-md btn-block btn-template text-white"><i class="fa fa-search"></i> Cari Data</button>
              <br />
              <br />
            </div>
          </form>
          <br />
          <table id="table" class="table table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th>Gambar Voucher</th>
                <th>Nama Voucher</th>
                <th>Kode Voucher</th>
                <th>Tampil Voucher</th>
                <th>Qty</th>
                <th>Waktu Berlaku</th>
                <th>Jenis Voucher</th>
                <th>Potongan/Disc</th>
                <th>Min Belanja</th>
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
      "filter": true,
      "processing": true,  //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [[5,"DESC"]], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo site_url(changeLink('panel/membership/daftarKupon/cari')); ?>',
        "type": "POST",
        "data": {
          "start_date": "<?php echo $start_date; ?>",
          "end_date": "<?php echo $end_date; ?>",
          "tipe_membership": "<?php echo $id_tipe_member; ?>",
          "status": "<?php echo $status; ?>"
        }
      },
      //Set column definition initialisation properties.
      "columns": [
        {
          "data": null,
          width: 100,
          "sort":false,
          render: function(data, type, row, meta) {
            return "<img src='<?php echo base_url();?>"+row.gambar_kupon+"' class='img-responsive w-100'>"
          }
        },
        {
          "data": "nama_kupon",
          width: 100
        },
        {
          "data": "kode_kupon",
          width: 100
        },
        {
          "data": "tampil_kupon",
          width: 100,
          render: function(data, type, row) {
            if(row.status == 'Y'){
              return "<b class='text-success'>Tampil</b>";
            }else{
              return "<b class='text-danger'>Tidak tampil</b>";
            }
          }

        },
        {
          "data": "jml_kupon",
          width: 100,
          render: function(data, type, row, meta) {
            return new Intl.NumberFormat(['id']).format(row.jml_kupon);
          }
        },
        {
          "data": "berlaku_hingga",
          width: 100,
        },
        {
          "data": "jenis_kupon",
          width: 100,
          render: function(data, type, row) {
            return row.jenis_kupon;
          }
        },
        {
          "data": null,
          width: 100,
          render: function(data, type, row) {
            if (row.jenis_kupon == 'potongan'){
              return new Intl.NumberFormat(['id','bal']).format(row.potongan);
            }else{
              return row.diskon+"%";
            }
          }
        },
        {
          "data": "min_belanja",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['id','bal']).format(row.min_belanja);
          }
        },
        {
          "data": "status",
          width: 100,
          render: function(data, type, row) {
            if(row.status == 'Y'){
              return "<b class='text-success'>Aktif</b>";
            }else{
              return "<b class='text-danger'>Tidak aktif</b>";
            }
          }
        },
        {
          "data": "action",
          width: 100
        },
      ],
    });
  });
</script>