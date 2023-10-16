<?php foreach($profile as $key):?>
    <?php foreach($transaksi as $row):?>
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
                        <div class="col-md-8">
                            <?php if(!empty($row->nama_toko)): ?>
                                <h5 class="text-center"><a class="text-dark" href="<?php echo base_url('informasi/toko/detail/'.$row->slug_toko);?>"><?php echo $row->nama_toko;?></a></h5>
                            <br>
                            <?php endif; ?>
                            <h5 class="pull-right">ID TRANSAKSI : <?php echo $row->id_transaksi;?></h5>
                            <h5>Detail Pesanan</h5>
                            <hr>
                            <?php if($row->payment_status != 'payed'):?>
                                <?php if($row->jenis_transaksi == 'produk' && !empty($row->courier)):?>
                                <form action="<?php echo base_url('transaksi/checkout/'.$row->id_transaksi);?>" enctype="multipart/form-data" method="POST">
                                    <div class="form-footer">
                                        <button type="submit" id="btnSimpan" class="btn btn-template btn-block">BAYAR</button>
                                    </div>
                                </form>
                                <?php elseif($row->jenis_transaksi == 'member' || $row->jenis_transaksi == 'jasa'):?>
                                <form action="<?php echo base_url('transaksi/checkout/'.$row->id_transaksi);?>" enctype="multipart/form-data" method="POST">
                                    <div class="form-footer">
                                        <button type="submit" id="btnSimpan" class="btn btn-template btn-block">BAYAR</button>
                                    </div>
                                </form>
                                <?php endif;?>
                            <?php endif;?>
                            <br>
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
                            <table class="table table-bordered" style="width:100%">
                                <tr>
                                    <td align="left">Total Belanja</td>
                                    <td align="right">Rp <?php echo number_format($cost->total_belanja,0,'.','.');?></td>
                                </tr>
                                <?php if($row->jenis_transaksi == 'produk'): ?>
                                <tr class="bg-template text-white">
                                    <td align="left">Berat Produk</td>
                                    <td align="right"><?php echo number_format($cost->total_berat,0,'.','.');?> Gram</td>
                                </tr>
                                <?php endif; ?>
                            </table>
                            <?php if($row->jenis_transaksi == 'produk'): ?>
                                <?php if(!empty($row->informasi_pengguna)): ?>
                                    <?php if(empty($row->no_resi) && $row->payment_status != 'payed' ): ?>
                                        <button class="btn btn-md btn-template text-white pull-right" id="btnEdit" onclick="editPengiriman()">Edit Pengiriman</button>
                                        <script>
                                            function editPengiriman(){
                                                $('#dataPengiriman').removeAttr('hidden')
                                                $('#btnEdit').text('Batal Edit Pengiriman')
                                                $('#btnEdit').removeAttr('onclick')
                                                $('#btnEdit').attr('onclick','batalEdit()')
                                            }

                                            function batalEdit(){
                                                $('#dataPengiriman').attr('hidden',true)
                                                $('#btnEdit').text('Edit Pengiriman')
                                                $('#btnEdit').removeAttr('onclick')
                                                $('#btnEdit').attr('onclick','editPengiriman()')
                                            }
                                        </script>
                                        <br/>
                                        <br/>
                                        <br/>
                                    <?php endif; ?>
                                        <div class="card bg-template text-white">
                                            <div class="card-body">
                                                <?php echo $row->bill_name;?><br>
                                                <?php echo $row->bill_address;?><br>
                                                <?php echo $row->bill_hp;?><br>
                                                <hr/>
                                                <?php echo $row->courier;?> - <?php echo $row->courier_service;?> (<?php echo $row->courier_desc;?>)<br>
                                                <?php if(!empty($row->courier_est)): ?>
                                                    <?php echo $row->courier_est;?> Hari<br>
                                                <?php endif; ?>
                                                Biaya Ongkir: Rp<?php echo number_format($row->ongkir,0,'.','.');?><br>
                                            </div>
                                        </div>
                                        <br/>
                                        <br/>
                                    <?php if(!empty($row->no_resi)): ?>
                                        <div class="card bg-template text-white">
                                            <div class="card-body">
                                                No.Resi : <?php echo $row->no_resi;?>
                                                <a class="text-white pull-right" href="<?php echo base_url('transaksi/cekResi/'.$row->no_resi.'/'.$row->id_transaksi);?>">>> Cek Pengiriman</a>
                                            </div>
                                        </div>
                                        <br/>
                                        <br/>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div id="dataPengiriman" class="card" <?php if(!empty($row->informasi_pengguna)){ echo "hidden"; }?>>
                                    <div class="card-body" id="formAlamat">
                                        <div class="form-group">
                                            <label for="informasi">Alamat Pengiriman</label>
                                            <select name="informasi_pengguna" id="informasi_pengguna" class="form-control select2" required onchange="cariInformasi(this.value)" id="informasi_pengguna">
                                                <option value="">.:Pilih Alamat Pengiriman:.</option>
                                                <?php foreach($informasiPengguna as $ip):?>
                                                    <option value="<?php echo $ip->id_informasi;?>"><?php echo $ip->alamat_lengkap;?></option>
                                                <?php endforeach;?>
                                            </select>                                    
                                        </div>
                                        <div class="form-group" id="formCourier">
                                            <label for="formCourier">Ekspedisi</label>
                                            <select name="courier" id="courier" class="form-control" onchange="getCost()" required>
                                                <option value="">.:Pilih Eskpedisi Pengiriman:.</option>
                                                <option value="jne">JNE</option>
                                                <option value="jnt">J&T</option>
                                                <option value="anteraja">ANTERAJA</option>
                                                <option value="sicepat">SICEPAT</option>
                                                <option value="tiki">TIKI</option>
                                                <option value="pos">POS</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="formJasa">
                                            <label for="formJasa">Jasa Pengiriman</label>
                                            <select name="courier_service" id="courier_service" class="form-control" onchange="pengiriman(this.value)" required>
                                                <option value="">.:Pilih Jasa Pengiriman:.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    function cariInformasi(idInformasi){
                                        $('#formInfo').remove()
                                        $.ajax({
                                            url:"<?php echo base_url('user/getInformasiPengguna/');?>",
                                            type:"POST",
                                            data:{
                                                'id_informasi':idInformasi
                                            },success:function(resp){
                                                if(resp!='false'){
                                                    var data = JSON.parse(resp);
                                                    $('<div class="card bg-template text-white" id="formInfo"></div>').insertBefore('#formCourier');
                                                    $.each(data,function(key,val){
                                                        $('#formInfo').append('<div class="card-body">'+val.nama+'<br/>'+val.nomor_hp+'<br/>'+val.alamat_lengkap+'</div>')
                                                        $('#formInfo').append('<input type="hidden" value="'+val.kabupaten+'" id="destination">')
                                                    })
                                                    getCost()
                                                }
                                            },error:function(){
                                                console.log(error)
                                            }
                                        })
                                    }
                                </script>
                                <script>
                                    function getCost(){
                                        $('#courier_service').html('<option value="">.:Pilih Jasa Pengiriman:.</option>');
                                        $('#formService').remove()
                                        var destination = $('#destination').val();
                                        var courier = $('#courier').val();
                                        var weight = <?php echo $cost->total_berat;?>;
                                        var origin = <?php echo $row->origin;?>;
                                        if(courier && origin){
                                            swal({
                                                title: 'Tunggu sebentar ya,kami lagi menghitung ongkir kamu..'
                                            });
                                            swal.showLoading();
                                        }
                                        $.ajax({
                                            url:"<?php echo base_url('api/ongkir/getOngkir');?>",
                                            type:"POST",
                                            data:{
                                                destination:destination,
                                                courier:courier,
                                                weight:weight,
                                                origin:origin
                                            },success:function(resp){
                                                swal.close();
                                                if(resp['data']['rajaongkir']['status'].code == '200'){
                                                    console.log(resp['data']['rajaongkir']['results'][0]['costs']);
                                                    $.each(resp['data']['rajaongkir']['results'][0]['costs'],function(key,val){
                                                        $('#courier_service').append('<option value="'+val.service+'#'+val.description+'#'+val.cost[0]['etd']+'#'+val.cost[0]['value']+'">'+val.service+' ('+val.description+') (Estimasi '+val.cost[0]['etd']+' Hari) Rp'+new Intl.NumberFormat(['ban','id']).format(val.cost[0]['value'])+'</option>');
                                                    })
                                                }
                                            }
                                        })
                                    }
                                </script>
                                <script>
                                    function pengiriman(val){
                                        $('#formService').remove()
                                        if(val){
                                            $('#formAlamat').append('<div class="card bg-template text-white" id="formService"></div>');
                                            const data = val.split("#");
                                            if(data[2]){
                                                $('#formService').append('<div class="card-body">'+data[0]+' ('+data[1]+')<br/>Estimasi '+data[2]+' Hari<br/>Biaya Rp'+new Intl.NumberFormat('bal','id').format(data[3])+'</div>')
                                            }else{
                                                $('#formService').append('<div class="card-body">'+data[0]+' ('+data[1]+')<br/>Biaya Rp'+new Intl.NumberFormat('id').format(data[3])+'</div>')
                                            }
                                            var total = Number(data[3])+Number(<?php echo $cost->total_belanja;?>);
                                            $('#totalSeluruh').html('Rp'+new Intl.NumberFormat(['id']).format(total))
                                            updateDataTransaksi(data[0],data[1],data[2],data[3])
                                        }
                                    }

                                    function updateDataTransaksi(courier_service,courier_desc,courier_est,ongkir){
                                        var informasi_pengguna = $('#informasi_pengguna').val();
                                        var courier = $('#courier').val();
                                        if(informasi_pengguna && courier && courier_service && courier_desc && ongkir){
                                            $.ajax({
                                                url:"<?php echo base_url('transaksi/updateTransaksi');?>",
                                                type:"POST",
                                                data:{
                                                    id_transaksi:"<?php echo $row->id_transaksi;?>",
                                                    informasi_pengguna:informasi_pengguna,
                                                    courier:courier,
                                                    courier_service:courier_service,
                                                    courier_desc:courier_desc,
                                                    courier_est:courier_est,
                                                    ongkir:ongkir
                                                },success:function(resp){
                                                    if(resp!='false'){
                                                        Swal.fire(
                                                            'Terima kasih!',
                                                            'Transaksi kamu berhasil diupdate',
                                                            'success'
                                                        ).then(function(){
                                                            location.reload();
                                                        })
                                                    }else{
                                                        swal(
                                                            'Gagal!',
                                                            'Terjadi kesalahan',
                                                            'error'
                                                        );
                                                    }
                                                },error:function(){
                                                    swal(
                                                        'Gagal',
                                                        'Terjadi kesalahan',
                                                        'error'
                                                    );
                                                }
                                            })
                                        }else{
                                            swal({
                                                title: 'Silahkan pilih alamat pengiriman!'
                                            });
                                        }                                        
                                    }
                                </script>
                            <table class="table table-bordered mt-2" style="width:100%">
                            <?php if($row->dengan_asuransi == 'Y'): ?>
                                <tr>
                                    <td align="left">Biaya Asuransi</td>
                                    <td align="right" id="biayaAsuransi">Rp<?php echo number_format($row->biaya_asuransi,0,'.','.');?></td>
                                </tr>
                            <?php endif; ?>
                                <tr>
                                    <td align="left">Total</td>
                                    <td align="right" id="totalSeluruh">Rp<?php echo number_format($row->total,0,'.','.');?></td>
                                </tr>
                            </table>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <ul class="nav nav-list">
                                <li>
                                    Status Pembayaran:
                                    <?php if($row->payment_status == 'payed'): ?>
                                        <b class="text-success">Lunas</b>
                                    <?php elseif($row->payment_status == 'pending'): ?>
                                        <b class="text-warning">Pending</b>
                                    <?php elseif($row->payment_status == 'process'): ?>
                                        <b class="text-primary">Diproses</b>
                                    <?php elseif($row->payment_status == 'cancel'): ?>
                                        <b class="text-danger">Dibatalkan</b>
                                    <?php elseif($row->payment_status == 'refund'): ?>
                                        <b class="text-danger">Dikembalikan</b>
                                    <?php endif; ?>
                                    <hr>
                                </li>
                                <?php 
                                $return = checkTransaction(my_simple_crypt($row->trx_id,'d'));
                                $return = json_decode($return);
                                if(!empty($return->Data)):
                                    if($return->Data->ReferenceId == $row->id_transaksi):
                                ?>
                                    <li>
                                        Pembayaran Via : <?php echo $return->Data->TypeDesc;?>
                                    </li>
                                    <li>
                                        Channel : <?php echo $return->Data->PaymentChannel;?> <b><?php echo $return->Data->PaymentCode;?></b>
                                    </li>
                                    <li>
                                        Fee : Rp <?php echo $return->Data->Fee;?>
                                    </li>
                                    <li>
                                        Dibuat Pada : <?php echo $return->Data->CreatedDate;?>
                                    </li>
                                    <li>
                                        Batas Pembayaran : <?php echo $return->Data->ExpiredDate;?>
                                    </li>
                                    <li>
                                        Dibayarkan Pada : <?php echo $return->Data->SuccessDate;?>
                                    </li>
                                    <li>
                                        <div class="alert alert-info">
                                            Harap lakukan pembayaran sesuai batas waktu pembayaran yang telah ditentukan, jika ingin mengubah metode pembayaran silahkan pilih kembali jenis pembayaran dan klik tombol bayar.
                                        </div>
                                    </li>
                                    <script>
                                        client = new Paho.MQTT.Client('mahkotastore.com', 8883, "appsWeb");
                                        client.onConnectionLost = onConnectionLost;
                                        client.onMessageArrived = onMessageArrived;
                                        client.connect({
                                            onSuccess:onConnect,
                                            userName:"mahkota",
                                            password:"1sampai12"
                                        });

                                        function onConnect() {
                                            console.log("connected");
                                            client.subscribe('notifTransaksi');
                                        };

                                        function onConnectionLost(responseObject) {
                                            if (responseObject.errorCode !== 0)
                                            console.log("onConnectionLost:"+responseObject.errorMessage);
                                        };

                                        function onMessageArrived(message) {
                                            if(message.payloadString == 'berhasil'){
                                                Swal.fire(
                                                    'Terima kasih!',
                                                    'Sistem akan melakukan pengecekan pembayaran kamu..',
                                                    'success'
                                                ).then(function(){
                                                    location.reload();
                                                })
                                            }
                                        };
                                    </script>
                                    <?php endif;?>
                                <?php endif;?>
                                <?php if($row->payment_status!='payed'): ?>
                                    <li><a href="<?php echo base_url('transaksi/bayar/'.$row->id_transaksi);?>" class="btn text-white btn-block btn-md btn-primary">Bayar</a></li>
                                    <li><a href="<?php echo base_url('transaksi/deleteTransaksi/'.$row->id_transaksi);?>" onclick="return confirm('Apakah kamu yakin akan membatalkan transaksi?')" class="btn text-white btn-block btn-md btn-danger">Batalkan Transaksi</a></li>
                                <?php endif; ?>
                                <li><a href="#" class="btn text-white btn-block btn-md btn-success">Konfirmasi Admin</a></li>
                                <li><a href="<?php echo base_url('user/profile');?>" class="btn text-white btn-block btn-md btn-warning">Kembali</a></li>
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
        "url": '<?php echo base_url(changeLink('transaksi/detail/'.$row->id_transaksi.'/cari')); ?>',
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "id_order",
          width: 100,
        },
        {
          "data": "nama_pesanan",
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
            <?php if($row->payment_status != 'payed'):?>
                return row.action;
            <?php else:?>
                return "";
            <?php endif;?>
          }
        },
      ],
    });
  });
</script>
    <?php endforeach;?>
<?php endforeach;?>