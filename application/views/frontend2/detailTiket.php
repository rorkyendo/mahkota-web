<?php foreach($isi as $key):?>
<main class="main">
  <div class="page-header breadcrumb-wrap">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?php echo base_url();?>" rel="nofollow">Home</a>
        <span></span> <?php echo $title;?>
      </div>
    </div>
  </div>
  <section class="section-border pt-50 pb-50 bg-green">
    <div class="container">
      <div class="text-center">
        <h4 class="text-brand mb-20">Kode Tiket <?php echo $kode_tiket;?></h4>
        <h5 class="mb-20">
          <?php echo $key->judul_tiket;?>
        </h5>
        <small><?php echo strtoupper($key->status_tiket);?></small>,
        <small><?php echo $key->created_time;?></small>
      </div>
    </div>
  </section>
  <section class="pt-50 pb-50">
    <div class="container">
      <div class="row">
        <div class="col-12 m-auto">
          <div class="row">
            <div class="card col-md-8" style="border-radius: 15px; background-color:aliceblue">
              <form action="<?php echo base_url('tiket/balas/'.my_simple_crypt($key->kode_tiket,'e'));?>" method="post"
                enctype="multipart/form-data">
                <div class="row">
                  <div class="col-12">
                    <div class="input-group">
                      <textarea class="form-control" id="textAreaExample" rows="4" name="pesan"
                        placeholder="Masukkan pesan disini.." onclick></textarea>
                      <button class="input-group-text btn-primary btn-md" type="button" onclick="showLampiran()"
                        id="btnLampiran">Tambah Lampiran</button>
                    </div>
                  </div>
                  <script>
                    function showLampiran() {
                      var content = document.getElementById("formLampiran");
                      if (content.style.display === "none") {
                        content.style.display = "block";
                        $('#btnLampiran').text('Hapus Lampiran')
                      } else {
                        content.style.display = "none";
                        $('#btnLampiran').text('Tambah Lampiran')
                        $('#lampiran').val('')
                      }
                    }
                  </script>
                  <div class="col-md-12" style="display: none;" id="formLampiran">
                    <input name="lampiran" id="lampiran" class="form-control"
                      placeholder="Masukkan Lampiran File bila diperlukan" type="file"
                      accept="image/png, image/jpg, image/jpeg">
                  </div>
                </div>
            </div>
            <div class="col-md-4">
              <button class="btn btn-success btn-xl" type="submit">Kirim</button>
              </form>
            </div>
            <div class="col-12">
              <br />
            </div>
            <div class="card scroll-card" id="chat1"
              style="border-radius: 15px;background:url('<?php echo base_url('assets/bgChat.png');?>');background-size:cover;background-position:center;">
            </div>
          </div>

        </div>
      </div>
    </div>
    </div>
  </section>
</main>
<script>
  function batal(id) {
    var result = confirm("Apakah kamu akan membatalkan pengiriman pesan?");
    if (result === true) {
      $.ajax({
        url: "<?php echo base_url('tiket/batalKirim/');?>",
        type: "POST",
        data: {
          id: id
        },
        success: function (resp) {
          if (resp != 'false') {
            location.reload()
          } else {
            console.log('Gagal dihapus')
          }
        }
      })
    }
  }

  $(document).ready(function(){
    loadChat();
  })

  function loadChat(){
    $.ajax({
      url:"<?php echo base_url('tiket/chat/'.$key->kode_tiket);?>",
      success:function(resp){
        $('#chat1').html('')
        $('#chat1').append(resp)      
      }
    })
  }
</script>

<script type="text/javascript">
<?php $koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);?>
<?php foreach($koneksi as $kon):?>
client=new Paho.MQTT.Client('<?php echo $kon->mqtt_broker;?>',15675,"webApps<?php echo strtotime(DATE('Y-m-d H:i:s'));?>");const options={useSSL:!0,userName:'mahkota',password:'1sampai12',onSuccess:onConnect,onFailure:onFailure};
<?php endforeach;?>
client.onConnectionLost=onConnectionLost,client.onMessageArrived=onMessageArrived,client.connect(options);function onConnect(){console.log("mqtt connected"),client.subscribe('tiket/update/<?php echo my_simple_crypt($key->id_tiket,'e');?>')}function onConnectionLost(responseObject){0!==responseObject.errorCode&&console.log("onConnectionLost:"+responseObject.errorMessage)}
function onFailure(errorMessage){console.log("Failed to connect:",errorMessage.errorMessage)}
function onMessageArrived(message){console.log("onMessageArrived:"+message.payloadString),"update"===message.payloadString&&loadChat()}
</script>
<?php endforeach;?>