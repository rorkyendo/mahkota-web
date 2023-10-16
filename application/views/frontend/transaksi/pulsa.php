<!-- BEGIN #my-account -->
<div id="about-us-cover" class="section-container">
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN account-container -->
        <div class="account-container">
            <!-- END account-sidebar -->
            <!-- BEGIN account-body -->
            <div class="account-body">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-6 -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phoneNumber">Nomor Telepon:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+62</span>
                                </div>
                                <input type="number" class="form-control" id="phoneNumber" onkeyup="checkProvider()" name="phoneNumber" placeholder="Masukkan nomor telepon" pattern="[0-9]*" inputmode="numeric">
                                <font color="red" id="notif"></font>
                            </div>
                            <center>
                                <br>
                                <div class="col-md-3" style="box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); padding: 10px; background-color: #ffffff; border-radius: 5px;">
                                    <span id="logo"></span>
                                </div>
                            </center>
                        </div>
                        <div class="row" id="isiProduk"></div>
                    </div>
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
    function checkProvider() {
        $("#isiProduk").html('');
        var phoneNumberInput = document.getElementById("phoneNumber").value;
        const providerResult = document.getElementById("providerResult");
        phoneNumberInput = "+62" + phoneNumberInput;
        const phoneNumberWithoutPrefix = phoneNumberInput.substring(3); // Menghapus +62
        // Definisi pola untuk masing-masing provider
        console.log(phoneNumberWithoutPrefix);
        // Define patterns for each provider
        const providerPatterns = [
            { provider: "TELKOMSEL", logo: "<?php echo base_url('assets/img/logo/');?>telkomsel.png", pattern: /^(8(11|12|13|21|22|52|53|51))/ },
            { provider: "XL", logo: "<?php echo base_url('assets/img/logo/');?>xl.png", pattern: /^(8(17|18|19|59|77|78))/ },
            { provider: "INDOSAT", logo: "<?php echo base_url('assets/img/logo/');?>indosat.png", pattern: /^(8(14|15|16|55|56|57|58|59|62|63|64))/ },
            { provider: "TRI", logo: "<?php echo base_url('assets/img/logo/');?>3.png", pattern: /^(8(89|95|96|97|98|99))/ },
            { provider: "SMARTFREN", logo: "<?php echo base_url('assets/img/logo/');?>smartfren.png", pattern: /^(8(1|2|3|4|5|6|7|8))/ },
            { provider: "by.U", logo: "<?php echo base_url('assets/img/logo/');?>byu.png", pattern: /^(8(41|42|43))/ },
            { provider: "AXIS", logo: "<?php echo base_url('assets/img/logo/');?>axis.png", pattern: /^(8(31|32|33|38))/ },
        ];

        let matchedProvider = null;
        let unknownProvider = true;
        // Pengecekan provider berdasarkan pola
        for (const { provider, logo, pattern } of providerPatterns) {
            if (phoneNumberWithoutPrefix.match(pattern)) {
                matchedProvider = provider;
                getProduct(matchedProvider);
                $('#logo').html("<img src='" + logo + "' class='w-50'>");
                unknownProvider = false;
                break;
            }
        }

        if (unknownProvider) {
            $('#logo').html("<font color='red'>Nomor Tidak diketahui</font>");
        }
    }
</script>
<script>
    function getProduct(provider){
        $.ajax({
            url:"<?php echo base_url('transaksi/pulsa/');?>",
            type:"GET",
            data:{
                "brand":provider
            },success:function(resp){
                var data = JSON.parse(resp);
                $.each(data,function(key,val){
                    $('#isiProduk').append('<div class="col-6" style="margin-bottom:10px;"><button class="btn btn-default bg-white text-black btn-block" onclick="pesanProduk(\''+val.id_produk_digital+'\')">'+val.nama_produk_digital+
                    '<br/><font color="green">Rp '+new Intl.NumberFormat(['bal','ID']).format(val.harga_jual)+'</font></button></div>')
                })
            }
        })
    }

    function pesanProduk(id){
        var noHp = "+62"+$('#phoneNumber').val()
        if(noHp > 10){
            $.ajax({
                url:"<?php echo base_url('transaksi/pesanProdukDigital/');?>",
                type:"POST",
                data:{
                    "id_produk_digital":id,
                    "nomor":noHp
                },success:function(resp){
                    if(resp!='fail'){
                        swal({  
                            title: 'Berhasil!',
                            text: 'Silahkan lanjutkan ke pembayaran',
                            type: 'success'
                        }).then(function () {
                            var newURL = '<?php echo base_url('transaksi/bayar/');?>' + resp;
                            window.location.href = newURL;
                        });
                    }else{
                        swal(
                            'Gagal',
                            'Terjadi kesalahan, Periksa kembali nomor yang anda input',
                            'error'
                        );
                    }
                },error:function(){
                    swal(
                        'Gagal',
                        'Terjadi kesalahan, Periksa kembali nomor yang anda input',
                        'error'
                    );
                }
            })
        }else{
            $("#notif").text('Nomor HP kurang dari 10 karakter')
        }
    }
</script>