<?php foreach($transaksi as $key):?>
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
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <div class="panel-heading-btn">
            <a href="<?php echo base_url('panel/membership/pendaftaranMember');?>" class="btn btn-xs btn-danger"><i class="fa fa-backward"></i> Kembali</a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <div class="col-md-9">
            <h4>Detail Transaksi <span class="pull-right">ID : <?php echo $key->id_transaksi;?></span> </h4>
            <hr>
            <center>
              <h5>BUKTI PEMBAYARAN</h5>
              <?php if(empty($key->bukti_transfer)): ?>
                <b class="text-danger">Tidak ada bukti pembayaran</b>
              <?php else: ?>
              <img src="<?php echo base_url().$key->bukti_transfer;?>" class="img-responsive width-100" alt="">
              <br>
              <a href="<?php echo base_url().$key->bukti_transfer;?>" target="_blank" class="btn btn-info btn-xs">Download</a>
              <?php endif; ?>
              <br>
              <br>
            </center>
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID Order</th>
                  <th>Tipe Member</th>
                  <th>Durasi</th>
                  <th>Harga</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($order as $row):?>
                <tr>
                  <td><?php echo $row->id_order;?></td>
                  <td><?php echo $row->nama_pesanan;?></td>
                  <td><?php echo $row->waktu_berlaku;?></td>
                  <td align="right">Rp<?php echo number_format($row->selling_price,0,'.','.');?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td align="left">Total</td>
                  <td align="right" colspan="3">Rp<?php echo number_format($key->total,0,'.','.');?></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="col-md-3">
            <h4>Status : <span class="pull-right">
                <?php if($key->payment_status == 'payed'): ?>
                    <b class="text-success">Lunas</b>
                <?php elseif($key->payment_status == 'pending'): ?>
                    <b class="text-warning">Pending</b>
                <?php elseif($key->payment_status == 'process'): ?>
                    <b class="text-primary">Diproses</b>
                <?php elseif($key->payment_status == 'cancel'): ?>
                    <b class="text-danger">Dibatalkan</b>
                <?php elseif($key->payment_status == 'refund'): ?>
                    <b class="text-danger">Dikembalikan</b>
                <?php endif; ?>
            </span></h4>
            <a href="<?php echo base_url('panel/membership/updatePendaftaranMember/accept/'.$key->id_transaksi);?>" class="btn btn-primary btn-block">Terima</a>
            <a href="<?php echo base_url('panel/membership/updatePendaftaranMember/reject/'.$key->id_transaksi);?>" onclick="return confirm('Apakah kamu yakin ingin menolak transaksi pendaftaran member ini?')" class="btn btn-danger btn-block">Tolak</a>
          </div>
        </div>
      </div>
      <!-- end panel -->
    </div>
    <!-- end col-12 -->
  </div>
  <!-- end row -->
</div>
<!-- end #content -->
<?php endforeach;?>
