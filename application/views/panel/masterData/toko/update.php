<?php foreach($toko as $row):?>
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
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white" data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/masterData/updateToko/doUpdate/'.$row->uuid_toko)); ?>"  enctype="multipart/form-data">
            <div class="col-md-6">
              <h4 class="text-center">Logo Toko</h4>
              <center>
                <?php if(!empty($row->logo_toko)): ?>
                  <img src="<?php echo base_url().$row->logo_toko; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <label class="col-md-2 control-label">Logo Toko</label>
                <div class="col-md-10">
                  <input type="file" name="logo_toko" class="form-control" id="logo_toko" accept="image/*" />
                </div>
              </div>
            </div>
            <script type="text/javascript">
              function readURL(input) {
                if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);
                }
              }
              $("#logo_toko").change(function() {
                readURL(this);
              });
            </script>
            <div class="col-md-6">
              <h4 class="text-center">Preview</h4>
              <center>
                <?php if(!empty($row->etalase_toko)): ?>
                <img src="<?php echo base_url().$row->etalase_toko; ?>" class="img-responsive" alt="preview" id="preview2"
                  style="height:150px">
                <?php else: ?>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview2"
                  style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <label class="col-md-2 control-label">Etalase Toko</label>
                <div class="col-md-10">
                  <input type="file" name="etalase_toko" class="form-control" id="etalase_toko" accept="image/*" />
                </div>
              </div>
            </div>
            <script type="text/javascript">
              function readURL2(input) {
                if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                    $('#preview2').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);
                }
              }
              $("#etalase_toko").change(function () {
                readURL2(this);
              });
            </script>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Urutan Toko</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" value="<?php echo $row->urutan_toko;?>" placeholder="Masukkan Urutan Toko" name="urutan_toko" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Toko</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" value="<?php echo $row->nama_toko;?>" placeholder="Masukkan Nama Toko" name="nama_toko" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Email Owner</label>
                <div class="col-md-10">
                  <input type="email" class="form-control" value="<?php echo $row->email_owner;?>" placeholder="Masukkan Email Owner" name="email_owner" required />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Alamat Toko</label>
                <div class="col-md-10">
                  <textarea name="alamat_toko" id="alamat_toko" class="form-control" cols="30" rows="10"><?php echo $row->alamat_toko;?></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-2 control-label">Provinsi</label>
                <div class="col-md-10">
                  <select name="provinsi" id="provinsi" class="form-control select2" onchange="cariOrigin(this.value)" required>
                    <option value="">.:Pilih Provinsi:.</option>
                    <?php foreach($provinsi as $key):?>
                      <option value="<?php echo $key->province_id;?>"><?php echo $key->province;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <script>
                $("#provinsi").val('<?php echo $row->provinsi;?>')
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Kota/Kabupaten</label>
                <div class="col-md-10">
                  <select name="origin" id="origin" class="form-control select2">
                    <option value="">.:Pilih Kota/Kabupaten:.</option>
                  </select>
                </div>
              </div>              
              <div class="form-group">
                <label class="col-md-2 control-label">No WA</label>
                <div class="col-md-10">
                  <input type="text" value="<?php echo $row->no_wa;?>" class="form-control" onkeypress="onlyNumberKey(event)" placeholder="Masukkan No WA" name="no_wa" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">No Telp</label>
                <div class="col-md-10">
                  <input type="text" value="<?php echo $row->no_telp;?>" class="form-control" onkeypress="onlyNumberKey(event)" placeholder="Masukkan No Telp" name="no_telp" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Biaya Asuransi</label>
                <div class="col-md-10">
                  <input type="number" value="<?php echo $row->biaya_asuransi;?>" class="form-control" placeholder="Masukkan Biaya Asuransi" name="biaya_asuransi" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Min Radius</label>
                <div class="col-md-10">
                  <input type="number" value="<?php echo $row->min_radius;?>" class="form-control" placeholder="Masukkan Min Radius" name="min_radius" />
                  <font color="red">*Untuk absensi dalam satuan meter</font>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <hr>
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari lokasi disini" onchange="cariLokasi(this.value)" />
                <div class="input-group-btn">
                  <button class="btn btn-primary btn-md" type="button"><i class="fa fa-search"></i></button>
                </div>
              </div>
              <br>
              <div class="form-group">
                <div id="map-wrapper">
                  <div id="map" style="width:auto;height:320px;"></div>
                  <div id="button-wrapper">
                    <button onclick="getLocation()" type="button" class="btn btn-xs btn-primary"><i class="fa fa-map-marker"></i> Lokasi saya</button>
                    <font color="red">Tekan tombol kotak pada map jika map tidak terload secara penuh</font>
                  </div>
                </div>
              </div>
              <input type="hidden" value="lat" name="lat" id="lat" value="<?php echo $row->lat_toko;?>">
              <input type="hidden" value="lng" name="lng" id="lng" value="<?php echo $row->lng_toko;?>">
            </div>
            <div class="form-group">
              <div class="col-md-12">
            <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/masterData/daftarToko/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
<script>
  var map, newMarker, markerLocation;
  var map = L.map('map', {
    fullscreenControl: {
        pseudoFullscreen: true
    }
  }).setView([-2.200000, 115.816666], 12);

    var roadMutant = L.gridLayer
      .googleMutant({
        maxZoom: 24,
        type: "roadmap",
      })
      .addTo(map);

    var satMutant = L.gridLayer.googleMutant({
      maxZoom: 24,
      type: "satellite",
    });

    var terrainMutant = L.gridLayer.googleMutant({
      maxZoom: 24,
      type: "terrain",
    });

    var hybridMutant = L.gridLayer.googleMutant({
      maxZoom: 24,
      type: "hybrid",
    });

    var styleMutant = L.gridLayer.googleMutant({
      styles: [
        { elementType: "labels", stylers: [{ visibility: "off" }] },
        { featureType: "water", stylers: [{ color: "#444444" }] },
        { featureType: "landscape", stylers: [{ color: "#eeeeee" }] },
        { featureType: "road", stylers: [{ visibility: "off" }] },
        { featureType: "poi", stylers: [{ visibility: "off" }] },
        { featureType: "transit", stylers: [{ visibility: "off" }] },
        { featureType: "administrative", stylers: [{ visibility: "off" }] },
        {
          featureType: "administrative.locality",
          stylers: [{ visibility: "off" }],
        },
      ],
      maxZoom: 24,
      type: "roadmap",
    });

    var trafficMutant = L.gridLayer.googleMutant({
      maxZoom: 24,
      type: "roadmap",
    });
    trafficMutant.addGoogleLayer("TrafficLayer");

    var transitMutant = L.gridLayer.googleMutant({
      maxZoom: 24,
      type: "roadmap",
    });
    transitMutant.addGoogleLayer("TransitLayer");

    var grid = L.gridLayer({
      attribution: "Debug tilecoord grid",
    });

    grid.createTile = function (coords) {
      var tile = L.DomUtil.create("div", "tile-coords");
      tile.style.border = "1px solid black";
      tile.style.lineHeight = "256px";
      tile.style.textAlign = "center";
      tile.style.fontSize = "20px";
      tile.innerHTML = [coords.x, coords.y, coords.z].join(", ");

      return tile;
    };

    L.control
      .layers(
        {
          Roadmap: roadMutant,
          Aerial: satMutant,
          Terrain: terrainMutant,
          Hybrid: hybridMutant,
          Styles: styleMutant,
          Traffic: trafficMutant,
          Transit: transitMutant,
        },
        {
          "Tilecoord grid": grid,
        },
        {
          collapsed: false,
        }
      )
      .addTo(map);

  L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    attribution: '&copy Mahkota Store | Powered By <a href="https://medandigitalinnovation.com">Medio<a/>',
    subdomains:['mt0','mt1','mt2','mt3']
  }).addTo(map);

  // Comment out the below code to see the difference.
  $('#modalOutlet').on('shown.bs.modal', function() {
    map.invalidateSize();
  });
  
  $(function(){
      newMarkerGroup = new L.LayerGroup();
      map.on('click', addMarker);
  });

  function addMarker2(lat,lng){
      // Add marker to map at click location; add popup window
      if (newMarker != undefined) {
        map.removeLayer(newMarker)
      }
      newMarker = new L.Marker([lat,lng], {draggable:true});
      map.addLayer(newMarker);
      newMarker.bindPopup("Lat :<br>"+newMarker._latlng.lat+"<br>Lng :<br>"+newMarker._latlng.lng).openPopup();
      $("#lat").val(newMarker._latlng.lat)
      $("#lng").val(newMarker._latlng.lng)
      this.markers.push(newMarker);
  }


  function addMarker(e){
      // Add marker to map at click location; add popup window
      if (newMarker != undefined) {
      map.removeLayer(newMarker)
      }
      newMarker = new L.Marker(e.latlng, {draggable:true});
      map.addLayer(newMarker);
      newMarker.bindPopup("Lat :<br>"+newMarker._latlng.lat+"<br>Lng :<br>"+newMarker._latlng.lng).openPopup();
      $('#lat').val(newMarker._latlng.lat);
      $('#lng').val(newMarker._latlng.lng);
      this.markers.push(newMarker);
  }
</script>
<script>
    function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getMaps);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
    }

    function getMaps(marker) {
    lat = marker.coords.latitude;
    long = marker.coords.longitude;
    if (newMarker != undefined) {
        map.removeLayer(newMarker)
    }

    const latlng = {lat:lat,lng:long}
    // this is just a marker placed in that position
    newMarker = L.marker([lat, long], {draggable:true}).addTo(map);
    // move the map to have the location in its center
    map.panTo(new L.LatLng(lat, long));

      $('#lat').val(lat);
      $('#lng').val(long);
    }
</script>
<script>
  cariOrigin('<?php echo $row->provinsi;?>')
  function cariOrigin(val){
    $.ajax({
      url:"<?php echo base_url('api/ongkir/getKota');?>",
      type:"GET",
      data:{
        id_province:val
      },
      success:function(data){
        $('#origin').html('<option value="">.:Pilih Kota/Kabupaten:.</option>');
        for (var i = 0; i < data['data'].length; i++) {
          $('#origin').append('<option value="'+data['data'][i].city_id+'">'+data['data'][i].city_name+'</option>');
        }
        $('#origin').val('<?php echo $row->origin;?>')
      }
    })
  }
</script>
<script>
  $(document).ready(function(){
    if (newMarker != undefined) {
        map.removeLayer(newMarker)
    }
    var lat = '<?php echo $row->lat_toko;?>'
    var long = '<?php echo $row->lng_toko;?>'

    $('#lat').val(lat)
    $('#lng').val(long)
    const latlng = {lat:lat,lng:long}
    // this is just a marker placed in that position
    newMarker = L.marker([lat, long], {draggable:true}).addTo(map);
    // move the map to have the location in its center
    map.panTo(new L.LatLng(lat, long));
  })

  function cariLokasi(val){
      $.ajax({
      url:"<?php echo base_url('api/ongkir/cariLokasi');?>",
      type:"GET",
        data:{
          lokasi:encodeURIComponent(val)
        },
        success:function(response){
          lat = response.data.candidates[0].geometry.location.lat;
          long = response.data.candidates[0].geometry.location.lng;
          if (newMarker != undefined) {
              map.removeLayer(newMarker)
          }
          const latlng = {lat:lat,lng:long}
          // this is just a marker placed in that position
          newMarker = L.marker([lat, long], {draggable:true}).addTo(map);
          // move the map to have the location in its center
          map.panTo(new L.LatLng(lat, long));
          $('#lat').val(lat);
          $('#lng').val(long);
          $('#alamat_toko').val(response.data.candidates[0].formatted_address)
        }
      })
  }
</script>
<?php endforeach;?>
