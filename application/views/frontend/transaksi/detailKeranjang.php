<?php foreach($profile as $key):?>
    <?php foreach($transaksi as $row):?>
        <?php foreach($toko as $t):?>
    <!-- BEGIN #about-us-content -->
        <div class="section-container bg-white">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN about-us-content -->
                <div class="about-us-content text-center">
                    <h2 class="title text-center">Hi, <?php echo $key->nama_lengkap;?>!</h2>
                    <hr>
                    <h5 class="text-center">Silahkan lakukan pembayaran untuk menyelesaikan pesanan kamu</h5>
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
                <!-- BEGIN account-body -->
                <div class="account-body">
                    <!-- BEGIN row -->
                    <div class="row">
                        <!-- BEGIN col-6 -->
                        <div class="col-md-12">
                            <?php echo $this->session->flashdata('notif');?>
                        </div>
                        <div class="col-md-10">
                            <h5 class="pull-right"><?php echo $t->nama_toko;?></h5>
                            <h5>Detail Pesanan</h5>
                            <hr>
                            <table class="table table-bordered table-striped" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                            </table>
                            <hr>
                            <form action="<?php echo base_url('transaksi/payment/doPayment/'.$row->id_temp_transaksi.'/'.$uuid_toko);?>" method="POST">
                            <label for="dengan_asuransi">
                              <?php 
                              $biaya_asuransi = 0;
                              if($order): ?>
                                <input type="checkbox" value="Y" checked disabled name="dengan_asuransi">
                                *Transaksi ini wajib menggunakan asuransi (Rp <?php echo number_format($t->biaya_asuransi,0,'.','.');?>)
                              <?php $biaya_asuransi = $t->biaya_asuransi;?>
                              <?php else: ?>
                                <input type="checkbox" value="N" id="dengan_asuransi" onchange="asuransi(this.value)" name="dengan_asuransi">
                                *Centang apabila ingin menggunakan asuransi (Rp <?php echo number_format($t->biaya_asuransi,0,'.','.');?>)
                                <script>
                                  function asuransi(val){
                                    if($('#dengan_asuransi').is(':checked')){
                                      $('#dengan_asuransi').val('Y');
                                    }else{
                                      $('#dengan_asuransi').val('N');
                                    }
                                  }
                                </script>
                              <?php endif; ?>
                            </label>
                            <table class="table table-bordered" style="width:100%">
                                <tr>
                                    <td align="left">Total</td>
                                    <td align="right">Rp <?php echo number_format($subtotal->total_belanja+$biaya_asuransi,0,'.','.');?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-2">
                            <ul class="nav nav-list">
                                <li><a href="<?php echo base_url('informasi/toko/detail/'.$t->slug_toko);?>" class="btn text-white btn-block btn-md btn-success">Lihat Toko</a></li>
                                <li><button type="submit" class="btn text-white btn-block btn-md btn-template">Pembayaran</button></li>
                            </ul>
                        </div>
                        </form>
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
            "url": '<?php echo base_url(changeLink('transaksi/cart/detailCart')); ?>',
            "type": "POST",
            "data":{
                uuid_toko:"<?php echo $uuid_toko;?>",
                temp_transaksi:"<?php echo $row->id_temp_transaksi;?>",
            }
        },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_order",
          width: 100,
        },
        {
          "data": "nama_produk",
          width: 100,
        },
        {
          "data": "qty",
          width: 100,
          render: function(data, type, row) {
            return new Intl.NumberFormat(['ID','bal']).format(row.qty);
          }
        },
        {
          "data": "selling_price",
          width: 100,
          render: function(data, type, row) {
            return "Rp"+new Intl.NumberFormat(['ID','bal']).format(row.selling_price);
          }
        },
        {
          "data": "subtotal",
          width: 100,
          render: function(data, type, row) {
            return "Rp"+new Intl.NumberFormat(['ID','bal']).format(row.subtotal);
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
        <?php endforeach;?>
    <?php endforeach;?>
<?php endforeach;?>