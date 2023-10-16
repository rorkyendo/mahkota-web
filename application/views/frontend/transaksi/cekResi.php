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
                    <h5 class="text-center">Dimenu ini kamu bisa melacak paket kamu</h5>
                    <h5 class="text-center">#IDTransaksi <?php echo $row->id_transaksi;?> No.Resi <?php echo $row->no_resi;?></h5>
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
                          <?php
                            $cekResi = $this->db->query("SELECT * FROM ms_resi WHERE transaksi = '$row->id_transaksi'")->row();
                            if($cekResi){
                                if ($row->courier == 'tiki' || $row->courier=='jne') {
                                    $response2 = json_decode($cekResi->response);
                                  }else{
                                    $response = json_decode($cekResi->response);
                                  }
                            }else{
                                if($row->courier == 'jne' || $row->courier == 'tiki'){
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => 'https://api.binderbyte.com/v1/track?api_key=67f7c1b4624816d424d14f24d034a585cad60ddbbace9d5e6d544e0f4714fc76&courier='.$row->courier.'&awb='.$no_resi,
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
                                        CURLOPT_URL => "http://pro.rajaongkir.com/api/waybill",
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => "",
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => "POST",
                                        CURLOPT_POSTFIELDS => "waybill=$no_resi&courier=".$row->courier,
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
                          <?php foreach($response->rajaongkir->result->manifest as $manifest):?>
                          <div class="card mt-2">
                              <div class="card-body">
                                <?php echo $manifest->manifest_description;?>  
                              </div>
                          </div>
                          <?php endforeach;?>
                          <?php endif;?>
                          <?php if(!empty($response2)):?>
                          <?php foreach($response2->data->history as $manifest):?>
                          <div class="card mt-2">
                              <div class="card-body">
                                  <?php echo $manifest->date;?> <?php echo $manifest->desc;?>  
                              </div>
                          </div>
                          <?php endforeach;?>
                          <?php endif;?>
                        </div>
                        <div class="col-md-2">
                            <ul class="nav nav-list">
                                <!-- <li><a href="<?php echo base_url('transaksi/konfirmasiPenerimaan/'.$row->id_transaksi);?>" class="btn text-white btn-block btn-md btn-success">Terima Barang</a></li> -->
                                <li><a href="<?php echo base_url('transaksi/detail/'.$row->id_transaksi);?>" class="btn text-white btn-block btn-md btn-danger">Kembali</a></li>
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
        <?php endforeach;?>
    <?php endforeach;?>
<?php endforeach;?>