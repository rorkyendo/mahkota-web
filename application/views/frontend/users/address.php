<!-- BEGIN #my-account -->
<div id="about-us-cover" class="section-container">
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN account-container -->
        <div class="account-container">
            <!-- BEGIN account-body -->
            <div class="account-body">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-6 -->
                    <div class="col-md-8">
                        <h4><i class="fa fa-map-marker-alt text-primary"></i> Daftar Alamat</h4>
                        <?php echo $this->session->flashdata('notif');?>
                        <a href="<?php echo base_url('user/createAddress');?>" class="btn btn-template btn-sm pull-right"><i class="fa fa-plus"></i> Tambah Alamat</a>
                        <br>
                        <br>
                        <table class="table table-bordered table-stripped" width="100%" id="table">
                            <thead>
                                <tr>
                                    <th>Detail</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- END col-6 -->
                    <!-- BEGIN col-6 -->
                    <div class="col-md-4">
                        <h4><i class="fa fa-universal-access fa-fw text-primary"></i> Pengaturan Akun</h4>
                        <ul class="nav nav-list">
                            <li><a href="<?php echo base_url('user/profile');?>">Profile</a></li>
                            <li><a href="<?php echo base_url('user/updateProfile');?>">Update Profile</a></li>
                            <li><a href="<?php echo base_url('user/updatePassword');?>">Update Password</a></li>
                        </ul>
                    </div>
                    <!-- END col-6 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END account-body -->
        </div>
        <!-- END account-container -->
    </div>
    <!-- END container -->
</div>
<!-- END #about-us-cover -->
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
        "url": '<?php echo base_url(changeLink('user/address/cari')); ?>',
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "alamat_lengkap",
          width: 250,
          render: function(data, type, row) {
            return row.nama+"<br>"+row.alamat_lengkap+"<br>"+row.nomor_hp;
          }
        },
        {
          "data": "action",
          width: 5,
          render: function(data, type, row) {
            return row.action;
          }
        },
      ],
    });
  });
</script>