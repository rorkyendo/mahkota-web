<!-- begin #content -->
<?php if($this->session->userdata('hak_akses') != 'member'){?>
	<div id="content" class="content">
<?php }else{ ?>
	<div id="content" class="content" style="margin-left: 0px;">
<?php } ?>
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
  <div class="row" id="post-data">
    <!-- begin col-12 -->
    <div class="col-md-12">
      <!-- begin panel -->
      <div class="panel">
        <div class="panel-body">
            <form action="<?php echo base_url('panel/berita/listBerita');?>" method="GET">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Judul Berita</label>
                        <input type="text" class="form-control" name="nama_berita" value="<?php echo $nama_berita;?>" placeholder="Masukkan Nama Berita">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Kategori Berita</label>
                        <select name="kategori_berita" id="kategori_berita" class="form-control">
                          <option value="">.:Pilih Kategori Berita:.</option>
                          <?php foreach($kategori_berita as $key):?>
                            <option value="<?php echo $key->id_kategori;?>"><?php echo $key->nama_kategori_berita;?></option>
                          <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <script>
                  $('#kategori_berita').val('<?php echo $kategoriBerita;?>')
                </script>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-xs blue-rasp text-white col-md-12"><i class="fa fa-search"></i> Cari Berita</button>
                </div>
            </form>
        </div>
      </div>
      <!-- end panel -->
    </div>
    <!-- end col-12 -->
    <?php foreach($getDataBerita as $key):?>
       <div class="col-md-4">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title text-center"><?php echo $key->judul_berita; ?></h4>
            </div>
            <div class="panel-body">
                <?php if($key->thumbnail_berita){?>
                    <img src="<?php echo base_url().$key->thumbnail_berita;?>" class="img-responsive" style="height: 220px;margin-left:auto;margin-right:auto" alt="Thubnail Berita">
                <?php }else{?>
                    <img src="<?php echo base_url().$icon;?>" class="img-responsive" style="height: 220px;margin-left:auto;margin-right:auto" alt="Thubnail Berita">
                <?php } ?>
                <hr/>
                <span class="badge bg-primary rounded-0">
                <?php echo $key->nama_kategori_berita;?>
                </span>
                <span class="badge bg-primary rounded-0">
                <?php echo tgl_indo($key->created_time);?>
                </span>
                <hr/>
                <?php echo $key->abstract_berita;?>
            </div>
            <div class="panel-footer">
                <a href="<?php echo base_url('panel/berita/bacaBerita/'.$key->slug_berita);?>" class="btn blue-rasp text-white btn-md btn-block">Baca Berita</a>
            </div>
          </div>
      </div>
    <?php endforeach;?>
  </div>
  <div class="ajax-load text-center" style="display:none">
    <p>Loading Data..</p>
  </div>
  <!-- end row -->
</div>
<!-- end #content -->

<script type="text/javascript">
  var page = <?php echo $perPage;?>;
  $(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() >= $('#post-data').height()) {
      loadMoreData(page);
      page+=<?php echo $perPage;?>;
    }
  });

  function loadMoreData(page) {
    $.ajax({
        url: '<?php echo base_url('panel/berita/listBerita/paginate');?>',
        data: 'page=' + page + '&nama_berita=<?php echo $nama_berita;?>&kategori_berita=<?php echo $kategoriBerita;?>',
        type: "get",
        beforeSend: function() {
          $('.ajax-load').show();
        }
      })
      .done(function(data) {
        if (data == 0) {
          $('.ajax-load').html("Tidak Ada Berita Lain");
          return;
        }
        $('.ajax-load').hide();
        $("#post-data").append(data);
      })
      .fail(function(jqXHR, ajaxOptions, thrownError) {
        alert('server not responding...');
      });
  }
</script>