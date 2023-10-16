<?php foreach($alamat as $row):?>
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
                    <div class="col-md-8">
                        <h4><i class="fa fa-map-marker-alt text-primary"></i> Alamat Penerima</h4>
                        <?php echo $this->session->flashdata('notif');?>
                        <form action="<?php echo base_url('user/updateAddress/doUpdate/'.$row->id_informasi);?>" method="POST">
                            <label class="control-label">Nama Lengkap</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="nama" value="<?php echo $row->nama;?>" placeholder="Nama lengkap penerima" required />
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari lokasi disini" onchange="cariLokasi(this.value)" />
                                        <div class="input-group-append">
                                            <button class="btn btn-inverse" type="button"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <br>
                                        <div id="map-wrapper">
                                            <div id="map" style="width:auto;height:320px;"></div>
                                            <div id="button-wrapper">
                                                <button onclick="getLocation()" type="button" class="btn btn-xs btn-primary"><i
                                                        class="fa fa-map-marker-alt"></i> Lokasi saya</button>
                                                <font color="red">Tekan tombol kotak pada map jika map tidak terload secara penuh</font>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="lat" name="lat" id="lat">
                                    <input type="hidden" value="lng" name="lng" id="lng">
                                </div>
                            </div> 
                            <label class="control-label">Alamat Lengkap</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control" cols="30" rows="10"><?php echo $row->alamat_lengkap;?></textarea>
                                </div>
                            </div>                        
                            <label class="control-label">Provinsi</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <select name="provinsi" id="provinsi" class="form-control" onchange="cariOrigin(this.value)">
                                        <option value="">.:Pilih Provinsi:.</option>
                                        <?php foreach($provinsi as $key):?>
                                            <option value="<?php echo $key->province_id;?>"><?php echo $key->province;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $('#provinsi').val('<?php echo $row->provinsi;?>')
                                $(document).ready(function(){
                                    cariOrigin('<?php echo $row->provinsi;?>')
                                    if (newMarker != undefined) {
                                    map.removeLayer(newMarker)
                                    }
                                    var lat = '<?php echo $row->lat_lokasi;?>'
                                    var long = '<?php echo $row->lng_lokasi;?>'

                                    $('#lat').val(lat)
                                    $('#lng').val(long)
                                    const latlng = {lat:lat,lng:long}
                                    // this is just a marker placed in that position
                                    newMarker = L.marker([lat, long], {draggable:true}).addTo(map);
                                    // move the map to have the location in its center
                                    // map.panTo(new L.LatLng(lat, long));
                                    map.flyTo([lat, long], 18);
                                })
                            </script>
                            <label class="control-label">Kota/Kabupaten</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <select name="kabupaten" id="kabupaten" class="form-control">
                                        <option value="">.:Pilih Kota/Kabupaten:.</option>
                                    </select>
                                </div>
                            </div>
                            <label class="control-label">Kode Pos</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="kode_pos"
                                        onkeypress="onlyNumberKey(this.event)" value="<?php echo $row->kode_pos;?>" placeholder="Masukkan Kode Pos" required />
                                </div>
                            </div>
                            <label class="control-label">No HP/WA</label>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="nomor_hp" value="<?php echo $row->nomor_hp;?>" onkeypress="onlyNumberKey(this.event)" placeholder="No HP/WA" required/>
                                </div>
                            </div>
                            <div class="form-footer">
                                <button type="submit" id="btnSimpan" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- END col-6 -->
                    <!-- BEGIN col-6 -->
                    <div class="col-md-4">
                        <h4><i class="fa fa-universal-access fa-fw text-primary"></i> Pengaturan Akun</h4>
                        <ul class="nav nav-list">
                            <li><a href="<?php echo base_url('user/profile');?>">Profile</a></li>
                            <li><a href="<?php echo base_url('user/updatePassword');?>">Update Password</a></li>
                            <li><a href="<?php echo base_url('user/address');?>">Tambah Alamat penerima</a></li>
                            <li><a href="<?php echo base_url('user/deleteAccount');?>">Hapus Akun</a></li>
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
<!-- END #about-us-cover -->

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
        styles: [{
                elementType: "labels",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "water",
                stylers: [{
                    color: "#444444"
                }]
            },
            {
                featureType: "landscape",
                stylers: [{
                    color: "#eeeeee"
                }]
            },
            {
                featureType: "road",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "poi",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "transit",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "administrative",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "administrative.locality",
                stylers: [{
                    visibility: "off"
                }],
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
        .layers({
            Roadmap: roadMutant,
            Aerial: satMutant,
            Terrain: terrainMutant,
            Hybrid: hybridMutant,
            Styles: styleMutant,
            Traffic: trafficMutant,
            Transit: transitMutant,
        }, {
            "Tilecoord grid": grid,
        }, {
            collapsed: false,
        })
        .addTo(map);

    L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        attribution: '&copy Mahkota Store | Powered By <a href="https://medandigitalinnovation.com">Medio<a/>',
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    // Comment out the below code to see the difference.
    $('#modalOutlet').on('shown.bs.modal', function () {
        map.invalidateSize();
    });

    $(function () {
        newMarkerGroup = new L.LayerGroup();
        map.on('click', addMarker);
    });

    function addMarker2(lat, lng) {
        // Add marker to map at click location; add popup window
        if (newMarker != undefined) {
            map.removeLayer(newMarker)
        }
        newMarker = new L.Marker([lat, lng], {
            draggable: true
        });
        $("#lat").val(lat)
        $("#lng").val(lng)
        cariLokasiGeo(lat, lng)
    }

    function addMarker(e) {
        if (newMarker != undefined) {
            map.removeLayer(newMarker)
        }
        // Add marker to map at click location; add popup window        
        newMarker = new L.Marker(e.latlng, {
            draggable: true
        });
        map.addLayer(newMarker);
        // newMarker.bindPopup("Lat :<br>" + newMarker._latlng.lat + "<br>Lng :<br>" + newMarker._latlng.lng).openPopup();
        $('#lat').val(newMarker._latlng.lat);
        $('#lng').val(newMarker._latlng.lng);
        cariLokasiGeo(newMarker._latlng.lat,newMarker._latlng.lng)
    }
</script>
<script>
    function cariLokasiGeo(lat,lng){
        $.ajax({
            url:"<?php echo base_url('api/ongkir/cariLokasiGeoCode');?>",
            type:"GET",
            data:{
                lat:lat,
                lng:lng
            },success:function(resp){
                if (newMarker != undefined) {
                    map.removeLayer(newMarker)
                }
                newMarker = new L.Marker([lat, lng], {
                    draggable: true
                });
                map.addLayer(newMarker);
                newMarker.bindPopup("(" +lat+ "," + lng +")<br>"+resp.data.display_name).openPopup();
                $('#alamat_lengkap').val(resp.data.display_name)
            }
        })
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

        const latlng = {
            lat: lat,
            lng: long
        }
        // this is just a marker placed in that position
        newMarker = L.marker([lat, long], {
            draggable: true
        }).addTo(map);
        // move the map to have the location in its center
        map.flyTo([lat, long], 18);

        $('#lat').val(lat);
        $('#lng').val(long);
        cariLokasiGeo(lat,long)
    }
</script>
<script>
    function cariOrigin(val) {
        $.ajax({
            url: "<?php echo base_url('api/ongkir/getKota');?>",
            type: "GET",
            data: {
                id_province: val
            },
            success: function (data) {
                $('#kabupaten').html('<option value="">.:Pilih Kota/Kabupaten:.</option>');
                for (var i = 0; i < data['data'].length; i++) {
                    $('#kabupaten').append('<option value="' + data['data'][i].city_id + '">' + data['data'][i]
                        .city_name + '</option>');
                }
                $('#kabupaten').val('<?php echo $row->kabupaten;?>')
            }
        })
    }
</script>
<script>
    function cariLokasi(val) {
        $.ajax({
            url: "<?php echo base_url('api/ongkir/cariLokasi');?>",
            type: "GET",
            data: {
                lokasi: encodeURIComponent(val)
            },
            success: function (response) {
                lat = response.data.candidates[0].geometry.location.lat;
                long = response.data.candidates[0].geometry.location.lng;
                if (newMarker != undefined) {
                    map.removeLayer(newMarker)
                }
                const latlng = {
                    lat: lat,
                    lng: long
                }
                // this is just a marker placed in that position
                newMarker = L.marker([lat, long], {
                    draggable: true
                }).addTo(map);
                // move the map to have the location in its center
                map.flyTo([lat, long], 18);
                // map.panTo(new L.LatLng(lat, long));
                $('#lat').val(lat);
                $('#lng').val(long);
                $('#alamat_lengkap').val(response.data.candidates[0].formatted_address)
            }
        })
    }
</script>
<?php endforeach;?>
