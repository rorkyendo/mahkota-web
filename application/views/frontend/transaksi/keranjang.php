<?php foreach($profile as $key):?>
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
                        <div class="col-md-12">
                        <?php foreach($getOrderToko as $row):?>
                            <?php if(!empty($row->uuid_toko)): ?>
                            <div class="card mb-3">
                            <div class="card-body">
                                <div class="col-md-8">
                                    <h5><a href="<?php echo base_url('informasi/toko/detail/'.$row->slug_toko);?>"><?php echo $row->nama_toko;?></a></h5>
                                </div>
                                <div class="col-md-4">
                                    <a href="<?php echo base_url('transaksi/detailKeranjang/'.$row->temp_transaksi.'/'.$row->uuid_toko);?>" class="btn btn-template btn-sm">Selesaikan Pembayaran <i class="fa fa-arrow-right"></i></a>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <hr>
                                    <table class="table table-stripped table-bordered" style="width:100%" id="table<?php echo $row->uuid_toko;?>">
                                        <thead>
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Harga</th>
                                                <th>Qty</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <script type="text/javascript">
                                        var table;

                                        $(document).ready(function() {
                                            table = $('#table<?php echo $row->uuid_toko;?>').DataTable({
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
                                                "url": '<?php echo base_url(changeLink('transaksi/cart/cari')); ?>',
                                                "type": "POST",
                                                "data":{
                                                    uuid_toko:"<?php echo $row->uuid_toko;?>",
                                                    temp_transaksi:"<?php echo $row->temp_transaksi;?>",
                                                }
                                            },
                                            //Set column definition initialisation properties.
                                            "columns": [{
                                                "data": "nama_produk",
                                                    width: 100,
                                                },
                                                {
                                                    "data": "selling_price",
                                                    width: 100,
                                                    render: function(data, type, row) {
                                                        return "<s>Rp"+new Intl.NumberFormat(['ID','bal']).format(row.harga_jual_online)+"</s><br/>Rp"+new Intl.NumberFormat(['ID','bal']).format(row.selling_price);
                                                    }
                                                },
                                                {
                                                    "data": "qty",
                                                    width: 10,
                                                    render: function(data, type, row) {
                                                        return new Intl.NumberFormat(['ID','bal']).format(row.qty);
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
                                </div>
                            </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach;?>
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
<?php endforeach;?>