<script src="<?php echo base_url('assets/'); ?>plugins/highcharts-7.1.2/code/highcharts.js"></script>
<script src="<?php echo base_url('assets/'); ?>plugins/highcharts-7.1.2/code/modules/data.js"></script>
<script src="<?php echo base_url('assets/'); ?>plugins/highcharts-7.1.2/code/modules/exporting.js"></script>
<!-- begin #content -->
<div id="content" class="content">
	<!-- begin row -->
	<div class="col-md-12">
		<div class="row">
			<?php echo $this->session->flashdata('notif'); ?>
			<h3>Selamat <?php echo waktu(date('H')); ?> : <b><?php echo $this->session->userdata('nama_lengkap'); ?></b><br/>
		</div>
	</div>
	<div class="row">
			<div class="col-md-12">
				<?php if($this->session->userdata('hak_akses') == 'superuser' || $this->session->userdata('hak_akses') == 'superowner' || $this->session->userdata('hak_akses') == 'superadmin'): ?>
					<select name="uuid_toko" id="uuid_toko" class="form-control select2" onchange="pilihToko(this.value)">
						<option value="">.:Pilih Toko:.</option>
						<?php foreach($toko as $key):?>
							<option value="<?php echo $key->uuid_toko;?>"><?php echo $key->nama_toko;?></option>
						<?php endforeach;?>
					</select>
					<script>
						function pilihToko(val){
							location.replace('<?php echo base_url();?>panel/dashboard/setToko/'+val);
						}
						$('#uuid_toko').val('<?php echo $this->session->userdata('uuid_toko');?>')
					</script>
				<?php endif; ?>
				<br>
				<br>
			</div>
				<!-- begin col-3 -->
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-green">
						<div class="stats-icon"><i class="fa fa-desktop"></i></div>
						<div class="stats-info">
							<h4>JUMLAH TRANSAKSI</h4>
							<p>0</p>	
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-blue">
						<div class="stats-icon"><i class="fa fa-book"></i></div>
						<div class="stats-info">
							<h4>JUMLAH PRODUK</h4>
							<p><?php echo number_format(count($produk),0,'.','.');?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo base_url('panel/produk/daftarProduk');?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<?php if($this->session->userdata('hak_akses') == 'superuser' || $this->session->userdata('hak_akses') == 'superowner' || $this->session->userdata('hak_akses') == 'superadmin'): ?>
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-purple">
						<div class="stats-icon"><i class="fa fa-users"></i></div>
						<div class="stats-info">
							<h4>JUMLAH PENGGUNA</h4>
							<p><?php echo number_format(count($pengguna),0,'.','.');?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo base_url('panel/masterData/daftarPengguna');?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<?php endif;?>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<?php if($this->session->userdata('hak_akses') == 'superuser' || $this->session->userdata('hak_akses') == 'superowner' || $this->session->userdata('hak_akses') == 'superadmin'): ?>
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-red">
						<div class="stats-icon"><i class="fa fa-building"></i></div>
						<div class="stats-info">
							<h4>JUMLAH TOKO</h4>
							<p><?php echo number_format(count($toko),0,'.','.');?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo base_url('panel/masterData/daftarToko');?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<?php endif;?>
				<!-- end col-3 -->
		<div class="col-md-6">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">Transaksi Terakhir</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>ID Transaksi</th>
									<th>Pelanggan</th>
									<th>Total</th>
									<th>Status</th>
									<th>Toko</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
				
		<div class="col-md-6">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">MENU</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="panel">
								<div class="panel-body bg-silver">
									<div class="row">
										<div class="col-md-4 col-sm-6 col-xs-6">
												<a href="<?php echo base_url('panel/dashboard');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-laptop"></i><br><small class="text-white">Dashboard</small></a>
											<br>
										</div>
										<?php if(cekModul('daftarTransaksi') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/transaksi/daftarTransaksi');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-shopping-cart"></i><br><small class="text-white">Transaksi</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('daftarProduk') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/produk/daftarProduk');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-book"></i><br><small class="text-white">Produk</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('daftarInventori') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/inventori/daftarInventori');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-cubes"></i><br><small class="text-white">Inventori</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('logistikMasuk') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/logistik/logistikMasuk');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-truck"></i><br><small class="text-white">Logistik Masuk</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('daftarMembership') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/membership/daftarMembership');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-id-card"></i><br><small class="text-white">Membership</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('daftarPengguna') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/masterData/daftarPengguna');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-users"></i><br><small class="text-white">Pengguna</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('daftarHakAkses') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/masterData/daftarHakAkses');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-user-secret"></i><br><small class="text-white">Hak Akses</small></a>
											<br>
										</div>
										<?php endif; ?>
										<?php if(cekModul('identitasAplikasi') == true): ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('panel/pengaturan/identitasAplikasi');?>" class="btn btn-md btn-block blue-rasp text-white"><i class="fa fa-cogs"></i><br><small class="text-white">Pengaturan</small></a>
											<br>
										</div>
										<?php endif; ?>
										<div class="col-md-4 col-sm-6 col-xs-6">
											<a href="<?php echo base_url('auth/logout');?>" class="btn btn-md btn-block red-sin text-white"><i class="fa fa-sign-out"></i><br><small class="text-white">Keluar</small></a>
											<br>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end row -->
</div>
<!-- end #content -->