<?php foreach($profile as $key):?>
<!-- BEGIN #about-us-content -->
    <div class="section-container bg-white">
        <!-- BEGIN container -->
        <div class="container">
            <!-- BEGIN about-us-content -->
            <div class="about-us-content text-center">
                <h2 class="title text-center">Hi, <?php echo $key->nama_lengkap;?>!</h2>
                <p class="desc text-center">
                    Lengkapi profile kamu agar kami dapat mengenali mu!
                    <br><br>
                    <button class="btn btn-template btn-md" data-toggle="modal" data-target="#membercard"><i class="fa fa-id-card"></i> MEMBERCARD</button>
                </p>
                <h5 class="text-center">
                    <?php if(empty($member)): ?>
                        Kamu adalah member <b>UMUM</b>
                    <?php else: ?>
                        Kamu adalah member <b><?php echo $member[0]->nama_tipe_member;?></b>
                    <?php endif; ?>
                      <br>Point kamu berjumlah <b><?php echo number_format($key->point,0,'.','.');?> POINT</b>
                      <br>
                </h5>
                <hr>
                <small>Tukarkan point kamu dengan produk menarik di toko kami<br> S&K berlaku. <a href="<?php echo base_url('produk/redeemPoint');?>">Klik untuk menukarkan</a></small>
            </div>
            <!-- END about-us-content -->
        </div>
        <!-- END container -->
    </div>
<!-- END #about-us-content -->
<!-- BEGIN #my-account -->
<div id="about-us-cover" class="section-container">
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN account-container -->
        <div class="account-container">
            <!-- BEGIN account-sidebar -->
            <div class="account-sidebar">
                <div class="account-sidebar-cover">
                    <img src="<?php echo base_url('assets/img/registerBG.jpg');?>" alt="" />
                </div>
                <div class="account-sidebar-content">
                    <img src="<?php echo base_url('assets/img/user.png');?>" class="user-img" alt="" />
                    <br>
                    <br>
                    <h4><?php echo $key->nama_lengkap;?></h4>
                    <p class="mb-2 mb-lg-4">
                        <i class="fa fa-envelope"></i> <?php echo $key->email;?><br>
                        <i class="fab fa-whatsapp"></i> <?php echo $key->no_telp;?>
                    </p>
                </div>
            </div>
            <!-- END account-sidebar -->
            <!-- BEGIN account-body -->
            <div class="account-body">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-6 -->
                    <div class="col-md-12">
                        <?php echo $this->session->flashdata('notif');?>
                        <h4><i class="fa fa-universal-access fa-fw text-primary"></i> Pengaturan Akun</h4>
                        <ul class="nav nav-list">
                            <li><a href="<?php echo base_url('user/updateProfile');?>">Update Profile</a></li>
                            <li><a href="<?php echo base_url('user/updatePassword');?>">Update Password</a></li>
                            <li><a href="<?php echo base_url('user/address');?>">Tambah Alamat penerima</a></li>
                            <li><a href="<?php echo base_url('user/deleteAccount');?>">Hapus Akun</a></li>
                        </ul>
                        <h4><i class="fa fa-shopping-cart text-primary"></i> Daftar Transaksi</h4>
                        <form action=""></form>
                        <table class="table table-bordered table-striped" id="table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Tgl Transaksi</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Tipe Transaksi</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                        </table>
                    <br>
                    <br>
                    </div>
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
<!-- The Modal -->
<div class="modal" id="membercard">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <?php if($member): ?>
                    <center>
                    <div id="container">
                        <img src="<?php echo base_url().$member[0]->barcode;?>" class="barcode" />
                        <!-- <img src="<?php echo base_url().$member[0]->qrcode;?>" class="qr"/> -->
                        <img src="<?php echo base_url().$member[0]->cover_depan;?>" class="img-responsive" alt="">
                    </div>
                    </center>
                <?php else: ?>
                    <h4 class="text-center">Kamu belum bergabung menjadi member</h4>
                    <h4 class="text-center">Bergabung sekarang juga dan nikmati promo khusus member!</h4>
                <?php endif; ?>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <?php if(empty($member)): ?>
                    <a href="<?php echo base_url('user/registerMember');?>" class="btn btn-template btn-md">Gabung Member</a>
                <?php endif; ?>
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>
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
      "order": [[0,'desc'],[1,'desc']], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": '<?php echo base_url(changeLink('user/profile/cari')); ?>',
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_transaksi",
          width: 100,
        },
        {
          "data": "created_time",
          width: 100,
        },
        {
          "data": "jenis_transaksi",
          width: 100,
          render: function(data, type, row) {
            return row.jenis_transaksi.toUpperCase();
          }
        },
        {
          "data": "tipe_transaksi",
          width: 100,
          render: function(data, type, row) {
            return row.tipe_transaksi.toUpperCase();
          }
        },
        {
          "data": "payment_status",
          width: 100,
          render: function(data, type, row) {
            if (row.payment_status == 'payed') {
                return '<b class="text-success">Lunas</b>';
            }else if(row.payment_status == 'pending'){
                return  '<b class="text-warning">Pending</b>';
            }else if(row.payment_status == 'process'){
                return  '<b class="text-primary">Diproses</b>';
            }else if(row.payment_status == 'cancel'){
                return   '<b class="text-danger">Dibatalkan</b>';
            }else if(row.payment_status == 'refund'){
                return    '<b class="text-danger">Dikembalikan</b>';
            }
            return row.payment_status.toUpperCase();
          }
        },
        {
          "data": "total",
          width: 100,
          render: function(data, type, row) {
            return "Rp"+new Intl.NumberFormat(['ID','bal']).format(row.total);
          }
        },
        {
          "data": "action",
          width: 5,
          render: function(data, type, row) {
            if (row.payment_status != 'payed') {
              return row.action;
            }else{
              return "<a href='<?php echo base_url('transaksi/detail/');?>"+row.id_transaksi+"' class='btn btn-sm btn-info'>Detail</a>";
            }
          }
        },
      ],
    });
  });
</script>