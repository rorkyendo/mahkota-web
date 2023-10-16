<!-- begin #content -->
<div id="content" class="content">
  <!-- begin row -->
  <div class="row">
    <div class="col-md-8">
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <h4 class="panel-title">Produk</h4>
        </div>
        <div class="panel-body">
          <div class="col-md-12">
            <input type="text" class="form-control" name="barcodeProduk" id="barcodeProduk" placeholder="Masukkan atau Scan barcode disini.." onchange="cariProduk(this.value)">
            <hr>
          </div>
          <form action="<?php echo base_url('panel/transaksi/pesan');?>" method="GET">
            <div class="col-md-6">
              <select name="kategori" id="kategori" class="form-control select2">
                  <option value="">.:Pilih Kategori Produk:.</option>
                  <?php foreach($kategori as $key):?>
                    <option value="<?php echo $key->id_kategori_produk;?>"><?php echo $key->nama_kategori_produk;?></option>
                  <?php endforeach;?>
              </select>
            </div>
            <script>
              $('#kategori').val('<?php echo $id_kategori;?>')
            </script>
            <div class="col-md-6">
              <select name="brand" id="brand" class="form-control select2">
                  <option value="">.:Pilih Brand:.</option>
                  <?php foreach($brand as $key):?>
                    <option value="<?php echo $key->id_brand;?>"><?php echo $key->nama_brand;?></option>
                  <?php endforeach;?>
              </select>
            </div>
            <script>
              $('#brand').val('<?php echo $id_brand;?>')
            </script>
            <div class="col-md-12">
              <br>
              <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Masukkan nama produk disini..">
              <script>
                $('#keyword').val('<?php echo $keywords;?>')
              </script>
            </div>
            <div class="col-md-12">
              <br>
              <button type="submit" class="btn btn-template btn-block">Cari Barang</button>
            </div>
          </form>
      </div>
    </div>
    <div class="row" id="daftarProduk">
      <?php foreach($produk as $key):?>
        <div class="col-md-4">
          <div class="panel">
            <div class="panel-body">
              <center>
                <b><small><?php echo $key->nama_produk;?></small></b>
              </center>
              <?php if(!empty($key->foto_produk)): ?>
                <img src="<?php echo base_url().$key->foto_produk;?>" class="img-responsive img-thumbnail" style="height:120px;margin-left:auto;margin-right:auto;display:block;" alt="">
              <?php else: ?>
                <img src="<?php echo base_url().$logo;?>" class="img-responsive img-thumbnail" style="height:120px;margin-left:auto;margin-right:auto;display:block;" alt="">
              <?php endif; ?>
              <div class="col-md-12">
                <br />
                <b>Lokasi</b>
                <b class="pull-right"><?php echo $key->kode_lokasi_penyimpanan;?> <?php echo $key->nama_lokasi_penyimpanan;?></b>
                <br/>
                <b>Harga</b>
                <?php if(!empty($key->harga_diskon)): ?>
                  <b class="pull-right"><strike>Rp <?php echo number_format($key->harga_jual,0,'.','.');?></strike></b>
                  <br>
                  <b class="pull-right">Rp <?php echo number_format($key->harga_jual-$key->harga_diskon,0,'.','.');?></b>
                <?php else: ?>
                  <b class="pull-right">Rp <?php echo number_format($key->harga_jual,0,'.','.');?></b>
                <?php endif; ?>
                <br />
                <br />
                <center>
                  <label>Qty</label>
                </center>
                <input type="number" class="form-control" id="qty<?php echo $key->id_produk;?>">
                <br />
                <br />
                <button type="button" class="btn btn-warning btn-md btn-block" onclick="pesan('<?php echo $key->id_produk;?>')">Tambah Pesanan</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach;?>
    </div>
    <div class="ajax-load text-center" style="display:none">
      <p>Loading Data..</p>
    </div>
  </div>
  <?php foreach($tempTransaksi as $key):?>
    <!-- begin col-4 -->
    <div class="col-md-4">
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <div class="panel-heading-btn">
            <a href="<?php echo base_url('panel/transaksi/batalPesan/'.my_simple_crypt($key->id_temp_transaksi,'e'));?>" class="btn btn-xs red-sin text-white"><i class="fa fa-times"></i> Batal</a>
          </div>
          <h4 class="panel-title">Detail Transaksi</h4>
        </div>
        <div class="panel-body">
        <table class="table table-striped table-bordered">
            <tr>
              <td colspan="2">
                <h6 class="text-center">Scan QR</h6>
                <div class="col-md-12" id="video-container">
                  <video id="qr-video" class="img-responsive"></video>
                </div>
              </td>
            </tr>
            <tr>
              <td>Barcode Member</td>
              <td>
                <input type="text" name="barcode" id="barcode" class="form-control" readonly>
              </td>
            </tr>
            <tr>
              <td>Pelanggan</td>
              <?php if(empty($key->pelanggan)): ?>
                <td>
                  <input value="<?php echo $key->bill_name;?>" onchange="gantiBillName(this.value)" type="text" class="form-control" id="bill_name">
                </td>
              <?php else: ?>
                <td><?php echo $key->nama_lengkap_pelanggan;?></td>
              <?php endif; ?>
            </tr>
            <tr>
              <td>Sales</td>
              <td>
                <select class="form-control" id="sales" onchange="gantiSales(this.value)">
                    <option value="">.:Pilih Sales:.</option>
                    <?php foreach($sales as $row):?>
                      <option value="<?php echo $row->id_pengguna;?>"><?php echo $row->nip;?> | <?php echo $row->nama_lengkap;?></option>
                    <?php endforeach;?>
                </select>
                <script>
                  $('#sales').val('<?php echo $key->sales;?>')
                </script>
              </td>
            </tr>
        </table>
      </div>
    </div>
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <h4 class="panel-title">Daftar Pesanan</h4>
        </div>
        <div class="panel-body">
          <table id="tablePesanan" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th style="width:50%">Produk</th>
                <th>Qty</th>
                <th>Harga</th>
              </tr>
            </thead>
            <tbody id="daftarPesanan">
              <?php $totalPesanan=0; foreach($pesanan as $key):?>
                <tr>
                  <td><button class="btn btn-danger btn-xs" onclick="hapusPesanan('<?php echo $key->id_order;?>','<?php echo $key->qty;?>','<?php echo $key->nama_produk;?>')"><i class="fa fa-times"></i></button> <?php echo $key->nama_produk;?></td>
                  <td><?php echo $key->qty;?></td>
                  <td><?php echo number_format($key->selling_price,0,'.','.');?></td>
                  <td class="hidden"><?php echo number_format($key->qty*$key->selling_price,0,'.','.');?></td>
                </tr>
              <?php $totalPesanan += $key->qty*$key->selling_price; endforeach;?>
            </tbody>
            <tfoot>
              <tr>
                <td>Total</td>
                <td colspan="2" align="right" id="total">Rp <?php echo number_format($totalPesanan,0,'.','.');?></td>
              </tr>
            </tfoot>
          </table>
          <div class="row">
            <form action="<?php echo base_url('panel/transaksi/bayar');?>" method="POST">
            <div class="col-md-12">
              <label>Pembayaran :</label>
              <input type="number" class="form-control" id="total_pembayaran" name="total_pembayaran" placeholder="Masukkan jumlah pembayaran.." onchange="getKembalian(this.value)" required>
            </div>
            <script>
              function getKembalian(total_bayar){
                var total = $('#total').text();
                total = total.replace("Rp ","");
                total = Number(total.split(".").join(""))
                if(Number(total_bayar) > Number(total)){
                  var kembalian = Number(total_bayar) - Number(total)
                }else{
                 var kembalian = 0; 
                }
                $('#kembalian').val(kembalian)
              }
            </script>
            <div class="col-md-12">
              <label>Kembalian :</label>
              <input type="number" class="form-control" id="kembalian" name="kembalian" readonly>
              <input type="hidden" name="total" value="<?php echo $totalPesanan;?>">
              <input type="hidden" name="id_temp_transaksi" value="<?php echo $tempTransaksi[0]->id_temp_transaksi;?>">
            </div>
            <div class="col-md-12">
              <label>Jenis Pembayaran :</label>
              <select name="payment_by" id="payment_by" class="form-control" required>
                <option value="">.:Pilih Jenis Pembayaran:.</option>
                <?php foreach($jenis_pembayaran as $key):?>
                <option value="<?php echo $key->id_jenis_pembayaran;?>"><?php echo $key->jenis_pembayaran;?></option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="col-md-12">
              <hr>
              <button type="submit" class="btn btn-xs btn-success pull-right"><i class="fa fa-money"></i> Bayar</button>
              <a href="#" onclick="tunda()" class="btn btn-xs btn-warning pull-right" style="margin-right:10px"><i class="fa fa-shopping-cart"></i> Tunda</a>
              <a href="<?php echo base_url('panel/transaksi/batalPesan/'.my_simple_crypt($tempTransaksi[0]->id_temp_transaksi,'e'));?>"
                class="btn btn-xs btn-danger pull-right" style="margin-right:10px"><i class="fa fa-times"></i> Batal</a>
            </div>
            </form>
          </div>
          </div>
      </div>
  </div>
  <?php endforeach;?>
  <!-- end col-12 -->
</div>
<!-- end row -->
</div>
<!-- end #content -->
<script type="module">
  var sound = new Audio("<?php echo base_url('assets/audio/');?>beep.mp3");
  import QrScanner from "<?php echo base_url('assets/plugins/qr-scanner/qr-scanner.min.js');?>";

    const video = document.getElementById('qr-video');
    const videoContainer = document.getElementById('video-container');
    const camQrResult = document.getElementById('barcode');

    function setResult(label, result) {
        console.log(result.data);
        $('#barcode').val(result.data)
        sound.play();
        getMember(result.data)
    }

    // ####### Web Cam Scanning #######
    const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
        highlightScanRegion: true,
        highlightCodeOutline: true,
    });

    const updateFlashAvailability = () => {
        scanner.hasFlash().then(hasFlash => {
        });
    };

    scanner.start().then(() => {
        updateFlashAvailability();
    });

    // for debugging
    window.scanner = scanner;
</script>
<script type="text/javascript">
    function loadMoreData(page) {
    var kategori = $('#kategori').val()
    var brand = $('#brand').val()
    var keyword = $('#keyword').val()
    $('.ajax-load').show();
    let data = {
      kategori:kategori,
      brand:brand,
      keyword:keyword,
      from:page,
      limit:6
    }
    $.ajax({
      url:"<?php echo base_url('panel/transaksi/getProduk');?>",
      type:"POST",
      data:{
        data:JSON.stringify({data})
      },
      success:function(resp){
        if(resp!='false'){
          $('.ajax-load').hide();
          $('#daftarProduk').append(resp)
        }else{
          $('.ajax-load').text('Tidak ada produk lain');
        }
      },error:function(){
        Swal.fire(
          'Error',
          'Terjadi kesalahan',
          'error'
        );
      }
    })
  }
</script>
<script>
  $(document).ready(function () {
    scanner.start();
  })

  function getMember(barcode){
    $('#bill_name').val('')
    $.ajax({
      url:'<?php echo base_url('panel/transaksi/scanBarcode');?>',
      type:'GET',
      data:{
        barcode:barcode,
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>'
        },success:function(resp){
        if(resp!='false'){
          var data = JSON.parse(resp);
          $.each(data,function(key,val){
            $('#bill_name').val(val.nama_lengkap)
            gantiBillName(val.nama_lengkap);
          })
        }else{
          Swal.fire(
          'Gagal!',
          'Data tidak ditemukan',
          'error'
          );
        }
      },error:function(){
        Swal.fire(
          'Gagal!',
          'Terjadi Kesalahan',
          'error'
        );
      }
    })
  }

  var page = 1;
  $(window).scroll(function() {
    if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
      page+=1;
      loadMoreData(page);
    }
  });
</script>
<script type="text/javascript">
  function pesan(id_produk){
    var qty = $('#qty'+id_produk).val();
    $.ajax({
      url:"<?php echo base_url('panel/transaksi/tambahPesanan');?>",
      type:"POST",
      data:{
        id_produk:id_produk,
        qty:qty,
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>'
      },success:function(resp){
        Swal.fire(
          'Ditambahkan ke keranjang',
          'Pesanan berhasil ditambahkan ke keranjang',
          'success'
        );
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

  function getPesanan(){
    $.ajax({
      url:"<?php echo base_url('panel/transaksi/getPesanan');?>",
      type:"POST",
      data:{
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>'
      },success:function(resp){
        var data = JSON.parse(resp)
        var totalPesanan = 0;
        $('#daftarPesanan').html('')
        $.each(data,function(key,val){
          var onclick = "hapusPesanan('"+val.id_order+"','"+val.qty+"','"+val.nama_produk+"')"
          $('#daftarPesanan').append('<tr><td><button class="btn btn-danger btn-xs" onclick="'+onclick+'"><i class="fa fa-times"></i></button>'+val.nama_produk+'</td>'+
          '<td>'+val.qty+'</td>'+
          '<td>'+new Intl.NumberFormat(['id']).format(val.selling_price)+'</td>'+
          '<td class="hidden">'+new Intl.NumberFormat(['id']).format(val.qty*val.selling_price)+'</td>'+
          '</tr>')
          totalPesanan += val.qty*val.selling_price
        })
        $('#total').text('Rp '+new Intl.NumberFormat(['id']).format(totalPesanan))
      },error:function(){
        Swal.fire(
          'Error',
          'Terjadi kesalahan',
          'error'
        );
      }
    })    
  }

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
          );
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

  function cariProduk(barcode){
    var qty = 1;
    $.ajax({
      url:"<?php echo base_url('panel/transaksi/tambahPesanan');?>",
      type:"POST",
      data:{
        barcodeProduk:barcode,
        qty:qty,
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>'
      },success:function(resp){
        Swal.fire(
          'Ditambahkan ke keranjang',
          'Pesanan berhasil ditambahkan ke keranjang',
          'success'
        );
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
</script>
<script>
  function gantiBillName(val){
    $.ajax({
      url:"<?php echo base_url('panel/transaksi/gantiBillName');?>",
      type:"POST",
      data:{
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>',
        bill_name:val,
      },success:function(){
        Swal.fire(
          'Penukaran Nama Pelanggan',
          'Nama Pelanggan berhasil ditukar',
          'success'
        );
      },error:function(){
        Swal.fire(
          'Gagal',
          'Terjadi kesalahan',
          'error'
        );
      }
    })
  }

  function gantiSales(val){
    $.ajax({
      url:"<?php echo base_url('panel/transaksi/gantiSales');?>",
      type:"POST",
      data:{
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>',
        sales:val,
      },success:function(){
        Swal.fire(
          'Penukaran Sales',
          'Sales berhasil ditukar',
          'success'
        );
      },error:function(){
        Swal.fire(
          'Gagal',
          'Terjadi kesalahan',
          'error'
        );
      }
    })
  }

  function tunda(){
    var total_pembayaran = $('#total_pembayaran').val();
    var kembalian = $('#kembalian').val();
    var payment_by = $('#payment_by').val();
    var total = $('#total').text();
    total = total.replace("Rp ","");
    total = Number(total.split(".").join(""))

    $.ajax({
      url:"<?php echo base_url('panel/transaksi/tunda');?>",
      type:"POST",
      data:{
        id_temp_transaksi:'<?php echo $tempTransaksi[0]->id_temp_transaksi;?>',
        total_pembayaran:total_pembayaran,
        total:total,
        kembalian:kembalian,
        payment_by:payment_by
      },success:function(resp){
        var data = JSON.parse(resp)
        Swal.fire(
          'Ditunda',
          'Pesanan untuk transaksi ini berhasil di tunda dan masuk ke detail transaksi',
          'success'
        ).then(function(){
            location.replace('<?php echo base_url('panel/transaksi/detailTransaksi/');?>'+data.transaksi);
        });
      },error:function(){

      }
    })
  }
</script>