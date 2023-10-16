<!-- begin #content -->
<div id="content" class="content">
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
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/membership/tambahTransaksiMember/doCreate/')); ?>">
            <div class="col-md-6" id="video-container">
              <video id="qr-video" class="img-responsive"></video>
            </div>
            <div class="col-md-6">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th colspan="2" style="text-align:center;">Informasi Pelanggan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="2" id="card"></td>
                  </tr>
                  <tr>
                    <td>Nama Pelanggan</td>
                    <td id="nama"></td>
                  </tr>
                  <tr>
                    <td>Kode Pelanggan</td>
                    <td id="kode"></td>
                  </tr>
                  <tr>
                    <td>Point</td>
                    <td id="point"></td>
                  </tr>
                  <tr>
                    <td>Tipe Membership</td>
                    <td id="tipe"></td>
                  </tr>
                  <tr>
                    <td>Masa Aktif</td>
                    <td id="berlaku"></td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td id="status"></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Scan Barcode</label>
                <input type="text" class="form-control" placeholder="Scan barcode disini" onchange="getMember(this.value)" id="barcode" name="barcode" required>
              </div>
              <div class="form-group">
                <label for="">Jenis Transaksi</label>
                <select name="jenis_transaksi" class="form-control" id="jenis_transaksi" required>
                  <option value="">.:Pilih Jenis Transaksi:.</option>
                  <option value="online">ONLINE</option>
                  <option value="offline">OFFLINE/TOKO</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">ID Transaksi</label>
                <input type="text" onkeypress="onlyNumberKey(this.event)" class="form-control" placeholder="ID Transaksi" name="id_transaksi" required>
              </div>
              <div class="form-group">
                <label for="">Jumlah Transaksi</label>
                <input type="number" class="form-control" placeholder="Jumlah Transaksi" name="jumlah_transaksi" id="jumlah_transaksi" required>
              </div>
              <div class="form-group">
                <label for="">Jumlah Point</label>
                <input type="number" class="form-control" placeholder="Jumlah Point" name="jumlah_point" required>
              </div>
              <div class="form-group">
                <label for="">Kode Kupon</label>
                <input type="text" class="form-control" placeholder="Masukkan kode kupon" onchange="getKupon(this.value)" id="kode_kupon" name="kode_kupon">
                <font id="notif"></font>
              </div>
              <div class="form-group">
                <label for="">Waktu Transaksi</label>
                <input type="datetime-local" class="form-control" placeholder="Waktu Transaksi" name="waktu_transaksi" value="<?php echo DATE('Y-m-d H:i:s');?>" required>
              </div>
            </div>
            <div class="col-md-12">
            <hr />
              <div class="form-group">
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/membership/transaksiMember/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
              </div>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
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
        // label.textContent = result.data;
        // camQrResultTimestamp.textContent = new Date().toString();
        // label.style.color = 'teal';
        // clearTimeout(label.highlightTimeout);
        // label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
        $('#barcode').val(result.data)
        sound.play();
        getMember(result.data)
    }

    // ####### Web Cam Scanning #######

    const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
        // onDecodeError: error => {
        //     camQrResult.textContent = error;
        //     camQrResult.style.color = 'inherit';
        // },
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
<script>
  $(document).ready(function(){
    scanner.start();
  })
</script>
<script>
  function getMember(barcode){
    $('#card').html('')
    $('#nama').html('')
    $('#kode').html('')
    $('#point').html('')
    $('#tipe').html('')
    $('#berlaku').html('')
    $('#status').html('')
    $.ajax({
      url:'<?php echo base_url('panel/membership/tambahTransaksiMember/scanBarcode');?>',
      type:'GET',
      data:{
        barcode:barcode
      },success:function(resp){
        if(resp!='false'){
          var data = JSON.parse(resp);
          $.each(data,function(key,val){
            $('#card').html('<img src="<?php echo base_url();?>'+val.cover_depan+'" class="img-responsive w-100" alt="">')
            $('#nama').html(val.nama_lengkap)
            $('#kode').html(val.barcode_member)
            $('#point').html(val.point)
            $('#tipe').html(val.nama_tipe_member)
            $('#berlaku').html(val.expired_date)
            $('#status').html(val.status)
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
</script>
<script>
  function getKupon(val){
    var barcode = $('#barcode').val()
    var jumlah_transaksi = $('#jumlah_transaksi').val()
    if(barcode && Number(jumlah_transaksi) > 0){
      $.ajax({
        url:"<?php echo base_url('panel/membership/cekKupon');?>",
        type:"POST",
        data:{
          kode_kupon:val,
          barcode:barcode,
          jumlah_transaksi:jumlah_transaksi
        },success:function(resp){
          if(resp=='false'){
            Swal.fire(
              'Gagal!',
              'Data tidak ditemukan',
              'error'
            );
            $('#kode_kupon').val('');  
          }else if(resp=='max'){
            Swal.fire(
              'Gagal!',
              'Anda sudah mencapai limit batas penukaran kupon',
              'error'
            );
            $('#kode_kupon').val('');
          }else if(resp=='notenought'){
            Swal.fire(
              'Gagal!',
              'Kupon tidak dapat ditukarkan min belanja belum terpenuhi',
              'error'
            );
            $('#kode_kupon').val('');
          }else{
            var data = JSON.parse(resp)
            $.each(data,function(r,row){
              if(row.jenis_kupon == 'diskon'){
                var notif = 'Anda mendapatkan diskon sebesar '+row.diskon+'% ';
                $('#notif').text(notif)
              }else{
                var notif = 'Anda mendapatkan potongan sebesar Rp'+new Intl.NumberFormat(['bal','id']).format(row.potongan)+' ';
                $('#notif').text(notif)
              }
              Swal.fire(
                'Sukses',
                'Kupon berhasil ditukar, '+notif,
                'success'
              );
            })
          }
        },error:function(){
            Swal.fire(
              'Gagal!',
              'Data tidak ditemukan',
              'error'
            );
            $('#kode_kupon').val('');
        }
      })
    }else{
      Swal.fire(
        'Gagal!',
        'Barcode ataupun jumlah transaksi tidak boleh kosong',
        'error'
      );
      $('#kode_kupon').val('');
    }
  }
</script>