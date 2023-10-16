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
                <h4 class="text-brand mb-20">Haloo 👋🏻</h4>
                <h5 class="mb-20">
                    Pertanyaan yang kamu buat sudah masuk dalam daftar kami <br />silahkan menunggu jawaban dari admin
                    <?php echo $appsProfile->agency;?> pada menu ini 😊
                </h5>
            </div>
        </div>
    </section>
    <section class="pt-50 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-12 m-auto">
                    <table class="table table-bordered table-striped" id="table">
                        <thead>
                            <tr>
                                <td>KODE</th>
                                <td>PESAN</th>
                                <td>STATUS</th>
                                <td>TGL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
  var table;

  $(document).ready(function() {
    table = $('#table').DataTable({
      responsive: {
        breakpoints: [{
          name: 'not-desktop',
          width: Infinity
        }]
      },
      "bStateSave": true,
      "filter": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "pageLength": 100,
      "lengthChange": true,
      // Load data for the table's content from an Ajax source
        "ajax": {
            "url": '<?php echo base_url(changeLink('tiket/daftarTiket/cari')); ?>',
            "type": "POST",
        },
      //Set column definition initialisation properties.
      "columns": [{
          "data": "kode_tiket",
          width: 5,
          render: function(data, type, row) {
            return "<a href='<?php echo base_url('tiket/cekTiket/');?>"+row.kode_tiket+"' class='btn btn-primary btn-sm'>"+row.kode_tiket+"</a>";
          }
        },
        {
          "data": "judul_tiket",
          width: 100,
        },
        {
          "data": "status_tiket",
          width: 10,
          render: function(data, type, row) {
            if(row.status_tiket=='open'){
                return '<b class="text-primary">'+row.status_tiket.toUpperCase()+'</b>';
            }else if(row.status_tiket=='process'){
                return '<b class="text-success">'+row.status_tiket.toUpperCase()+'</b>';
            }else{
                return '<b>'+row.status_tiket.toUpperCase()+'</b>';
            }
          }
        },
        {
          "data": "created_time",
          width: 100,
        },
      ],
    });
  });
</script>