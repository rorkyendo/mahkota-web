<?php foreach($transaksi as $key):?>
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
          <div class="panel-heading-btn">
            <a href="<?php echo base_url('panel/transaksi/daftarTransaksi');?>" class="btn btn-xs red-sin text-white"><i class="fa fa-backward"></i> Kembali</a>
            <?php if($key->payment_status != 'payed'):?>
            <a href="<?php echo base_url('panel/transaksi/editTransaksi/'.$key->id_transaksi);?>" class="btn btn-xs btn-inverse"><i class="fa fa-edit"></i> Edit</a>
            <?php endif;?>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <div class="col-md-8">
            <table class="table table-striped table-bordered table-hover">
              <tr>
                <td width="20%">
                  <h5>Nama Toko</h5>
                </td>
                <td>
                  <h5><?php echo $key->nama_toko;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>IDTRANSAKSI</h5>
                </td>
                <td>
                  <h5><?php echo $key->id_transaksi;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>SALES</h5>
                </td>
                <td>
                  <h5><?php echo $key->nama_lengkap_sales;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>GARANSI</h5>
                </td>
                <td>
                  <?php if($key->dengan_asuransi == 'Y'): ?>
                    <h5 class="text-success">GARANSI TUKAR ITEM</h5>
                    <?php else: ?>
                      <h5 class="text-danger">TIDAK ADA GARANSI</h5>
                  <?php endif; ?>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <table class="table table-striped table-bordered table-hover">
              <tr>
                <td width="40%">
                  <h5>Status Pembayaran</h5>
                </td>
                <td>
                  <?php if($key->payment_status == 'pending'): ?>
                  <h5 class="text-warning">PENDING</h5>
                  <?php elseif($key->payment_status == 'process'): ?>
                  <h5 class="text-info">PROCESS</h5>
                  <?php elseif($key->payment_status == 'payed'): ?>
                  <h5 class="text-success">LUNAS</h5>
                  <?php elseif($key->payment_status == 'refund'): ?>
                  <h5 class="text-danger">DIKEMBALIKAN</h5>
                  <?php else: ?>
                  <h5 class="text-danger">DIBATALKAN</h5>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  Transaksi Dibuat
                </td>
                <td>
                  <?php echo $key->created_time;?>
                </td>
              </tr>
              <?php
                try {
                  $xenditInfo = cekInvoiceXendit($key->trx_id);
                } catch (\Throwable $th) {
                  $xenditInfo = '';
                }
                if(!empty($key->trx_id)):?>
                <?php if($xenditInfo['status'] == 'PAID'):?>
                  <tr>
                    <td width="20%">Pembayaran Via</td>
                    <td><?php echo $xenditInfo['payment_method'];?></td>
                  </tr>
                  <tr>
                    <td width="20%">Channel</td>
                    <td><?php echo $xenditInfo['payment_channel'];?></td>
                  </tr>
                  <tr>
                     <td width="20%">Fee</td>
                     <td>
                        <?php foreach($xenditInfo['fees'] as $f):?>
                          <?php echo $f['type'];?>
                          (Rp <?php echo $f['value'];?>)
                          <br>
                        <?php endforeach;?>
                     </td>
                  </tr>
                  <tr>
                      <td width="20%">Dibayarkan Pada</td>
                      <td><?php echo DATE("Y-m-d H:i:s",strtotime($xenditInfo['paid_at']));?></td>
                  </tr>
                  <?php endif;?>
                  <tr>
                      <td width="20%">Pembayaran Dibuat</td>
                      <td><?php echo DATE("Y-m-d H:i:s",strtotime($xenditInfo['created']));?></td>
                  </tr>
                  <tr>
                      <td width="20%">Batas Pembayaran</td>
                      <td><?php echo DATE("Y-m-d H:i:s",strtotime($xenditInfo['expiry_date']));?></td>
                  </tr>
                  <tr>
                      <td>Cek Invoice</td>
                      <td><a class="btn btn-primary btn-md" href="<?php echo $xenditInfo['invoice_url'];?>" target="_blank">Klik disini</a></td>
                  </tr>
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
              <?php if(!empty($key->bukti_transfer)):?>
              <tr>
                <td width="20%">
                  <h5>BUKTI PEMBAYARAN</h5>
                </td>
                <td>
                  <img src="<?php echo base_url().$key->bukti_transfer;?>" class="img-responsive">
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <td colspan="2" style="text-align:right">
                  <button class="btn btn-primary btn-xs" onclick="cetakBT()"><i class="fa fa-bluetooth"></i> Bluetooth</button>
                  <a class="btn btn-success btn-xs" href="<?php echo base_url('panel/transaksi/cetak/pdf/'.my_simple_crypt($key->id_transaksi,'e'));?>"><i class="fa fa-print"></i> PDF</a>
                  <button class="btn btn-info btn-xs" onclick="cetakStruk()"><i class="fa fa-print"></i> Struk</button>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-12">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th colspan="5" style="text-align:center;background-color:#D78473;color:white;">DETAIL BELANJA</th>
                </tr>
                <tr>
                  <th width="10%">ID Order</th>
                  <th style="text-align:left">Produk</th>
                  <th style="text-align:right">Harga</th>
                  <th width="15%" style="text-align:right">Qty</th>
                  <th style="text-align:right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $totalPesanan = 0;
                  foreach($order as $row):?>
                <tr>
                  <td>
                    <?php if($key->payment_status!='payed'): ?>
                      <button class="btn btn-danger btn-xs" onclick="hapusPesanan('<?php echo $row->id_order;?>','<?php echo $row->qty;?>','<?php echo $row->nama_produk;?>')"><i class="fa fa-times"></i></button>
                    <?php endif; ?>
                    <?php echo $row->id_order;?>
                  </td>
                  <td style="text-align:left"><?php echo $row->nama_produk;?></td>
                  <td style="text-align:right">
                    <del>
                      <?php echo number_format($row->harga_jual_online,0,'.','.');?>
                    </del>
                    <?php echo number_format($row->selling_price,0,'.','.');?>
                  </td>
                  <td style="text-align:right"><?php echo number_format($row->qty,0,'.','.');?></td>
                  <td style="text-align:right"><?php echo number_format($row->subtotal,0,'.','.');?></td>
                </tr>
                <?php 
                $totalPesanan += $row->subtotal;
                endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" style="background-color:#D78473;color:white;">Total Belanja</td>
                  <td style="text-align:right;"><?php echo number_format($totalPesanan,0,'.','.');?></td>
                </tr>
                <?php if(!empty($key->ongkir)): ?>
                  <tr>
                    <td colspan="4" style="background-color:#D78473;color:white;">Ongkir</td>
                    <td style="text-align:right;"><?php echo number_format($key->ongkir,0,'.','.');?></td>
                  </tr>
                <?php endif; ?>
                <?php if(!empty($key->potongan_belanja)): ?>
                  <tr>
                    <td colspan="4" style="background-color:#D78473;color:white;">Potongan Belanja</td>
                    <td style="text-align:right;"><?php echo number_format($key->potongan_belanja,0,'.','.');?></td>
                  </tr>
                <?php endif; ?>
                <?php if(!empty($key->biaya_asuransi)): ?>
                  <tr>
                    <td colspan="4" style="background-color:#D78473;color:white;">Biaya Asuransi</td>
                    <td style="text-align:right;"><?php echo number_format($key->biaya_asuransi,0,'.','.');?></td>
                  </tr>
                <?php endif; ?>
                <tr>
                  <td colspan="4" style="background-color:#D78473;color:white;">Total</td>
                  <?php if($key->payment_status == 'payed'): ?>
                    <td style="text-align:right;"><?php echo number_format($key->total,0,'.','.');?></td>
                  <?php else: ?>
                    <td style="text-align:right;"><?php echo number_format($totalPesanan+$key->ongkir-$key->potongan_belanja,0,'.','.');?></td>
                    <?php $totalPesanan = $totalPesanan+$key->ongkir+$key->biaya_asuransi-$key->potongan_belanja;?>
                  <?php endif; ?>
                </tr>
                <tr>
                  <td colspan="4" style="background-color:#D78473;color:white;">Dibayar</td>
                  <td style="text-align:right;"><?php echo number_format($key->total_pembayaran,0,'.','.');?></td>
                </tr>
                <tr>
                  <td colspan="4" style="background-color:#D78473;color:white;">Kembalian</td>
                  <td style="text-align:right;"><?php echo number_format($key->kembalian,0,'.','.');?></td>
                </tr>
              </tfoot>
            </table>
            <?php if($key->payment_status == 'pending'): ?>
              <form action="<?php echo base_url('panel/transaksi/updateTransaksi/bayar/'.my_simple_crypt($key->id_transaksi,'e'));?>" method="POST">
                <div class="row">
                  <label>Pembayaran :</label>
                  <input type="number" class="form-control" id="total_pembayaran" name="total_pembayaran" placeholder="Masukkan jumlah pembayaran.." onchange="getKembalian(this.value)" required>
                </div>
                <script>
                  function getKembalian(total_bayar) {
                    var total = "<?php echo $totalPesanan;?>";
                    if (Number(total_bayar) > Number(total)) {
                      var kembalian = Number(total_bayar) - Number(total)
                    } else {
                      var kembalian = 0;
                    }
                    $('#kembalian').val(kembalian)
                  }
                </script>
                <div class="row">
                  <label>Kembalian :</label>
                  <input type="number" class="form-control" id="kembalian" name="kembalian" readonly>
                  <input type="hidden" name="total" value="<?php echo $totalPesanan;?>">
                </div>
                <div class="row">
                  <label>Jenis Pembayaran :</label>
                  <select name="payment_by" id="payment_by" class="form-control" required>
                    <option value="">.:Pilih Jenis Pembayaran:.</option>
                    <?php foreach($jenis_pembayaran as $row):?>
                    <option value="<?php echo $row->id_jenis_pembayaran;?>"><?php echo $row->jenis_pembayaran;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="row">
                  <br>
                    <button class="btn btn-md btn-success btn-block" type="submit"><i class="fa fa-money"></i> Bayar</button>
                    <a href="<?php echo base_url('panel/transaksi/updateTransaksi/batal/'.my_simple_crypt($key->id_transaksi,'e'));?>" class="btn btn-md btn-danger btn-block"><i class="fa fa-times"></i> Batal</a>
                </div>
              </form>
            <?php endif; ?>
            <?php if($key->payment_status == 'process'):?>
                    <a class="btn btn-md btn-success btn-block" href="<?php echo base_url('panel/transaksi/updateTransaksi/process/'.my_simple_crypt($key->id_transaksi,'e'));?>"><i class="fa fa-checked"></i> Terima</a>
                    <br>
            <?php endif; ?>
          </div>
          <?php if(!empty($key->pelanggan)): ?>
            <div class="col-md-8">
            <div id="tableContainer" style="display:none;">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th colspan="2" style="text-align:center;background-color:#D78473;color:white;">DETAIL PELANGGAN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>PELANGGAN</td>
                    <td>
                      <?php echo $key->nama_lengkap_pelanggan;?>
                    </td>
                  </tr>
                  <?php if(!empty($key->informasi_pengguna)): ?>
                  <tr>
                    <td>NAMA PENERIMA</td>
                    <td>
                      <?php echo $key->bill_name;?>
                    </td>
                  </tr>
                  <tr>
                    <td>ALAMAT PENERIMA</td>
                    <td>
                      <?php echo $key->bill_address;?>
                    </td>
                  </tr>
                  <?php endif;?>
                </tbody>
              </table>
            </div>
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th colspan="2" style="text-align:center;background-color:#D78473;color:white;">DETAIL PELANGGAN</th>
                    <button id="printButton" class="btn btn-sm btn-success">Print Table</button>
                    <script>
                      // JavaScript function to handle printing
                      function printTable() {
                        var printContents = document.getElementById("tableContainer").innerHTML;
                        var originalContents = document.body.innerHTML;

                        document.body.innerHTML = printContents;
                        window.print();

                        document.body.innerHTML = originalContents;
                      }

                      // Attach the click event to the print button
                      document.getElementById("printButton").addEventListener("click", printTable);
                    </script>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>PELANGGAN</td>
                    <td>
                      <?php echo $key->nama_lengkap_pelanggan;?>
                    </td>
                  </tr>
                  <?php if(!empty($key->informasi_pengguna)): ?>
                  <tr>
                    <td>NAMA PENERIMA</td>
                    <td>
                      <?php echo $key->bill_name;?>
                    </td>
                  </tr>
                  <tr>
                    <td>ALAMAT PENERIMA</td>
                    <td>
                      <?php echo $key->bill_address;?>
                    </td>
                  </tr>
                  <tr>
                    <td>KONTAK PENERIMA</td>
                    <td>
                      <?php echo $key->bill_hp;?>
                    </td>
                  </tr>
                  <tr>
                    <td>PENGIRIMAN</td>
                    <td>
                        <?php echo $key->courier;?> - <?php echo $key->courier_service;?> (<?php echo $key->courier_desc;?>)<br>
                        <?php if(!empty($key->courier_est)): ?>
                            <?php echo $key->courier_est;?> Hari<br>
                        <?php endif; ?>
                        Biaya Ongkir: Rp<?php echo number_format($key->ongkir,0,'.','.');?><br>
                    </td>
                  </tr>
                  <tr>
                    <td>NO RESI</td>
                    <td>
                      <?php if(!empty($key->no_resi) && cekModul('updateResi') == FALSE): ?>
                        <?php echo $key->no_resi;?>
                      <?php else: ?>
                        <?php if(cekModul('updateResi') == TRUE): ?>
                        <div class="input-group">
                            <input type="text" class="form-control" name="no_resi" onchange="updateResi(this.value)" value="<?php echo $key->no_resi;?>" placeholder="Masukkan no resi">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                        <script>
                          function updateResi(val){
                            $.ajax({
                              url:"<?php echo base_url('panel/transaksi/updateResi/');?>",
                              type:"POST",
                              data:{
                                no_resi:val,
                                id_transaksi:"<?php echo $key->id_transaksi;?>"
                              },success:function(resp){
                                if(resp!='false'){
                                  Swal.fire(
                                      'Terima kasih!',
                                      'no resi berhasil diupdate',
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
                                    'Gagal!',
                                    'Terjadi kesalahan',
                                    'error'
                                );                                
                              }
                            })
                          }
                        </script>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <button class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#lacakPaket">LACAK PENGIRIMAN</button>
                    </td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
          <?php if(!empty($key->rekening)): ?>
            <div class="col-md-4">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th colspan="2" style="text-align:center;background-color:#D78473;color:white;">DETAIL PEMBAYARAN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>JENIS PEMBAYARAN</td>
                    <td>
                      <?php echo strtoupper($key->tipe_pembayaran);?>
                    </td>
                  </tr>
                  <tr>
                    <td>PEMBAYARAN</td>
                    <td>
                      <?php echo $key->kode_rekening;?> (<?php echo $key->nama_rekening;?>)
                      <br><?php echo $key->no_rekening;?> an <?php echo $key->an_rekening;?>
                    </td>
                  </tr>
                  <tr>
                    <td>BUKTI PEMBAYARAN</td>
                    <td>
                      <img src="<?php echo base_url().$key->bukti_transfer;?>" class="img-responsive width-100" alt="">
                      <a href="<?php echo base_url().$key->bukti_transfer;?>" target="_blank" class="btn btn-info btn-xs">Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>DITERIMA OLEH</td>
                    <td>
                          <?php echo $key->nama_penerima;?>
                    </td>
                  </tr>
                  <tr>
                    <td>DITOLAK OLEH</td>
                    <td>
                          <?php echo $key->nama_penolak;?>
                    </td>
                  </tr>
                  <?php if($key->payment_status!='payed'): ?>
                    <?php if(cekModul('konfirmasiPembayaran') == TRUE): ?>
                    <tr>
                      <td>Aksi</td>
                      <td>
                        <a href="<?php echo base_url('panel/transaksi/konfirmasiPembayaran/cancel/'.$key->id_transaksi);?>" onclick="return confirm('Apakah kamu yakin akan menolak pembayaran ini?')" class="btn btn-xs btn-danger">Tolak</a>
                        <?php if($key->payment_status != 'payed'): ?>
                          <a href="<?php echo base_url('panel/transaksi/konfirmasiPembayaran/payed/'.$key->id_transaksi);?>" onclick="return confirm('Apakah kamu yakin akan menerima pembayaran ini?')" class="btn btn-xs btn-success">Terima</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endif; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- end col-12 -->
</div>
<!-- end row -->
</div>
<!-- end #content -->

<script>
    async function hapusPesanan(id_order,qty,nama_produk){
    const { value: newQty } = await Swal.fire({
      title: nama_produk,
      input: 'number',
      inputPlaceholder: 'Masukkan Qty',
      inputValue:qty,
      showCancelButton:true
    })

    if (newQty) {
      $.ajax({
        url:"<?php echo base_url('panel/transaksi/editOrder');?>",
        type:"POST",
        data:{
          id_order:id_order,
          qty:newQty
        },success:function(resp){
          Swal.fire(
            'Sukses',
            'Pesanan Berhasil diubah',
            'success'
          ).then(function(){
            location.reload()
          });
          getPesanan();
        },error:function(){
          Swal.fire(
            'Error',
            'Terjadi kesalahan',
            'error'
          );
        }
      })
    }
  }
</script>

<!-- cetak script-->
<script>
  function cetakBT(){

  }

  function cetakStruk(){

  }
</script>

<!-- Modal -->
<div class="modal fade" id="lacakPaket" tabindex="-1" role="dialog" aria-labelledby="lacakPaketTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="lacakPaketTitle">LACAK PENGIRIMAN</h5>
      </div>
      <div class="modal-body">
      <?php if(!empty($key->no_resi)): ?>
        <?php
            $no_resi = $key->no_resi;
            $cekResi = $this->db->query("SELECT * FROM ms_resi WHERE transaksi = '$key->id_transaksi'")->row();
            if($cekResi){
              if ($key->courier == 'tiki' || $key->courier=='jne') {
                $response2 = json_decode($cekResi->response);
              }else{
                $response = json_decode($cekResi->response);
              }
            }else{
              if ($key->courier == 'tiki' || $key->courier=='jne') {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.binderbyte.com/v1/track?api_key=67f7c1b4624816d424d14f24d034a585cad60ddbbace9d5e6d544e0f4714fc76&courier='.$key->courier.'&awb='.$key->no_resi,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                ));
  
                $response2 = curl_exec($curl);
                $response2 = json_decode($response2);
  
                curl_close($curl);
              }else{
                $curl = curl_init();
  
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "waybill=$no_resi&courier=".$key->courier,
                  CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: 84e274d66f7553eaee7fc9ac5b27ab09"
                  ),
                ));
    
                $response = curl_exec($curl);
                $err = curl_error($curl);
    
                curl_close($curl);
    
                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                  $response = json_decode($response);
                }
              }
            }
        ?>
        <?php if(!empty($response)):?>
          <?php if(empty($response->rajaongkir->result)):?>
            <div class="alert alert-danger">Mohon maaf respon sedang tidak tersedia untuk kurir <?php echo $response->rajaongkir->query->courier;?></div>
          <?php else:?>
          <ul class="timeline">
            <?php foreach($response->rajaongkir->result->manifest as $manifest):?>
              <li>
                <div class="timeline-body">
                  <div class="timeline-content">
                      <?php echo $manifest->manifest_description;?>  
                  </div>
                </div>
              </li>
            <?php endforeach;?>
          </ul>
          <?php endif;?>
        <?php endif;?>
        <?php if(!empty($response2)):?>
          <ul class="timeline">
            <?php foreach($response2->data->history as $manifest):?>
              <li>
                <div class="timeline-body">
                  <div class="timeline-content">
                      <?php echo $manifest->date;?> <?php echo $manifest->desc;?>  
                  </div>
                </div>
              </li>
            <?php endforeach;?>
          </ul>
        <?php endif;?>

      <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach;?>
