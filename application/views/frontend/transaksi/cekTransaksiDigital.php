<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-wOe/1jnsupk1IjB5xUB4F6xNOtWlu+lZfaZvbeDfNvD1UC9/uOAiTTtiHJghJGif1Fkq0R7IJoO+ha3fcKXK/6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title><?php echo $title;?></title>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      margin: 10px;
    }
    .card-header {
      background-color: #fff;
      border-bottom: none;
      text-align: center;
      padding: 1rem 0;
    }
    .card-body{
        margin: 12px;
        padding-bottom: 15px;
    }
  </style>
</head>
<body>
<?php foreach($transaksi as $key):?>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h3>ID Transaksi: <?php echo $key->id_transaksi;?></h3>
        <?php $cekOrder = $this->db->query("SELECT * FROM v_order WHERE transaksi = '$key->id_transaksi'")->row();?>
        <center>
            <img src="<?php echo base_url('assets/shield.gif');?>" class="img-responsive" style="width:120px;<?php if($cekOrder->status_digital == 'Sukses'){ echo 'display:block';}else{ echo 'display:none';}?>" alt="" srcset="" id="sukses">
            <img src="<?php echo base_url('assets/wait.gif');?>" class="img-responsive" style="width:120px;<?php if($cekOrder->status_digital == 'Sukses'){ echo 'display:none';}else{ echo 'display:block';}?>" alt="" srcset="" id="wait">
        </center>
      </div>
      <div class="card-body">
        <table>
            <tr>
                <td>Status order</td>
                <td id="status">: <?php echo $cekOrder->status_digital;?></td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>: <?php echo $cekOrder->nomor_digital;?></td>
            </tr>
            <tr>
                <td>Produk</td>
                <td>: <?php echo $cekOrder->nama_produk_digital;?></td>
            </tr>
            <tr>
                <td>Harga</td>
                <td>: <?php echo $cekOrder->selling_price;?></td>
            </tr>
        </table>
      </div>
    </div>
    <div class="card" id="hasil" style="<?php if($cekOrder->status_digital == 'Sukses'){ echo 'display:block';}else{ echo 'display:none';}?>">
        <div class="card-header">
            Berhasil
        </div>
        <div class="card-body" id="response">
            <?php echo $cekOrder->order_notes;?>
        </div>
    </div>
    <div class="card" id="catatan" style="<?php if($cekOrder->status_digital == 'Sukses'){ echo 'display:none';}else{ echo 'display:block';}?>">
        <div class="card-header">
            Catatan :
        </div>
        <div class="card-body">
            Silahkan tunggu beberapa saat apabila status order belum sukses, karena sistem akan memproses transaksi secara otomatis
        </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
        <?php if($cekOrder->status_digital != 'Sukses'):?>
            setInterval(() => {
                $.ajax({
                url:"<?php echo base_url('transaksi/cekTransaksiDigital/status/');?>",
                type:"GET",
                data:{
                    pengguna:"<?php echo $key->created_by;?>",
                    id_transaksi:"<?php echo $key->id_transaksi;?>"
                },success:function(resp){
                    var data = JSON.parse(resp);
                    if(data.data.status === "Sukses"){
                        $('#catatan').css('display','none')
                        $('#wait').css('display','none')
                        $('#hasil').css('display','block')
                        $('#sukses').css('display','block')
                        $('#status').text(": "+data.data.status)
                        $('#response').html('Topup berhasil dilakukan dengan <b>SN '+data.data.sn+'</b>');
                    }
                }
            })
        }, 30000);
        <?php endif;?>
    });
  </script>
<?php endforeach;?>
</body>
</html>
