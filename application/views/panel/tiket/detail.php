<?php foreach($isi as $key):?>
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
    <div class="col-md-8">
      <!-- begin panel -->
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <div class="col-md-8">
            <table class="table table-striped table-bordered table-hover">
              <tr>
                <td width="20%">
                  <h5>Kode Tiket</h5>
                </td>
                <td>
                  <h5><?php echo $key->kode_tiket;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>Status Tiket</h5>
                </td>
                <td>
                  <select class="form-control" name="status_tiket" id="status_tiket"
                    onchange="updateStatus(this.value)">
                    <option value="open">OPEN</option>
                    <option value="process">PROCESS</option>
                    <option value="closed">CLOSED</option>
                  </select>
                  <script>
                    $('#status_tiket').val('<?php echo $key->status_tiket;?>')

                    function updateStatus(val) {
                      $.ajax({
                        url: "<?php echo base_url('panel/tiket/updateTiket');?>",
                        type: "POST",
                        data: {
                          id_tiket: "<?php echo $key->id_tiket;?>",
                          status_tiket: val
                        },
                        success: function (resp) {
                          if (resp != 'false') {
                            Swal.fire(
                              'Berhasil!',
                              'Status tiket berhasil diubah',
                              'success'
                            ).then(function () {
                              location.reload();
                            })
                          } else {
                            Swal.fire(
                              'Gagal',
                              'Terjadi kesalahan',
                              'danger'
                            )
                          }
                        }
                      })
                    }
                  </script>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>Tgl Dibuat</h5>
                </td>
                <td>
                  <h5><?php echo tgl_indo($key->created_time);?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>Judul Pesan</h5>
                </td>
                <td>
                  <h5><?php echo $key->judul_tiket;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>Isi Pesan</h5>
                </td>
                <td>
                  <h5><?php echo $key->isi_tiket;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>Lampiran</h5>
                </td>
                <td>
                  <?php if(!empty($key->lampiran_tiket)):?>
                  <img src="<?php echo base_url().$key->lampiran_tiket;?>" class="img-responsive"
                    style="width:320px;height:280px;">
                  <?php endif;?>
                </td>
              </tr>

            </table>
          </div>
          <div class="col-md-4">
            <table class="table table-striped table-bordered table-hover">
              <tr>
                <td width="20%">
                  <h5>Dibuat Oleh</h5>
                </td>
                <td>
                  <h5><?php echo $key->nama_tiket;?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>No WA</h5>
                </td>
                <td>
                  <h5><?php echo strtoupper($key->no_wa);?></h5>
                </td>
              </tr>
              <tr>
                <td width="20%">
                  <h5>Kategori</h5>
                </td>
                <td>
                  <h5 style="font-style:italic"><?php echo strtoupper($key->kategori_tiket);?></h5>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <!-- end panel -->
    </div>
    <!-- end col-6 -->
    <div class="col-md-4">
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="index-2">
        <div class="panel-heading">
          <h4 class="panel-title">Pesan</h4>
        </div>
        <div class="panel-body bg-silver">
          <div data-scrollbar="true" id="isiChat" data-height="540px">
          </div>
        </div>
        <div class="panel-footer">
          <form action="<?php echo base_url('panel/tiket/updateTiket/balas');?>" method="POST" enctype="multipart/form-data">
            <div class="input-group">
              <input type="hidden" name="id_tiket" value="<?php echo $key->id_tiket;?>">
              <input type="text" class="form-control input-sm" name="pesan" placeholder="Masukkan pesan kamu">
              <span class="input-group-btn">
                <button class="btn btn-success btn-sm" type="button" onclick="showLampiran()"><i class="fa fa-plus"></i></button>
              </span>
            </div>
            <script>
              function showLampiran() {
                var content = document.getElementById("formLampiran");
                if (content.style.display === "none") {
                  content.style.display = "block";
                } else {
                  content.style.display = "none";
                  $('#formLampiran').val('')
                }
              }
            </script>
              <input name="lampiran" style="display: none;" id="formLampiran" class="form-control"
                placeholder="Masukkan Lampiran File bila diperlukan" type="file"
                accept="image/png, image/jpg, image/jpeg">
            <button class="btn btn-primary btn-sm btn-block" type="submit">Kirim</button>
          </form>
        </div>
      </div>
      <!-- end panel -->
    </div>
  </div>
  <!-- end row -->
</div>
<!-- end #content -->
<script>
  function batal(id){
    var result = confirm("Apakah kamu akan membatalkan pengiriman pesan?");
    if (result === true) {
      $.ajax({
        url:"<?php echo base_url('panel/tiket/updateTiket/batal/');?>",
        type:"POST",
        data:{
          id:id
        },success:function(resp){
          if(resp!='false'){
            location.reload()
          }else{
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
      url:"<?php echo base_url('panel/tiket/isiTiket/'.$key->id_tiket);?>",
      type:"POST",
      data:{
        pengguna:'<?php echo $key->pengguna;?>'
      },
      success:function(resp){
        $('#isiChat').html('')
        $('#isiChat').append(resp)      
      }
    })
  }
</script>

<script type="text/javascript">
<?php $koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);?>
<?php foreach($koneksi as $kon):?>
client=new Paho.MQTT.Client('<?php echo $kon->mqtt_broker;?>',15675,"webApps<?php echo strtotime(DATE('Y-m-d H:i:s'));?>");const options={useSSL:!0,userName:'mahkota',password:'1sampai12',onSuccess:onConnect,onFailure:onFailure};
<?php endforeach;?>
client.onConnectionLost=onConnectionLost,client.onMessageArrived=onMessageArrived,client.connect(options);function onConnect(){console.log("mqtt connected"),client.subscribe('tiket/update/<?php echo my_simple_crypt($key->id_tiket,'e');?>'),client.subscribe('tiket/update/<?php echo $key->id_tiket;?>')}function onConnectionLost(responseObject){0!==responseObject.errorCode&&console.log("onConnectionLost:"+responseObject.errorMessage)}
function onFailure(errorMessage){console.log("Failed to connect:",errorMessage.errorMessage)}
function onMessageArrived(message){console.log("onMessageArrived:"+message.payloadString),"update"===message.payloadString&&loadChat()}
</script>
<?php endforeach;?>