		<!-- BEGIN #my-account -->
		<div id="about-us-cover" class="section-container">
			<!-- BEGIN container -->
			<div class="container">
				<!-- BEGIN breadcrumb -->
				<ul class="breadcrumb mb-3">
					<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url('auth/register');?>">Daftar</a></li>
				</ul>
				<!-- END breadcrumb -->
				<!-- BEGIN account-container -->
				<div class="account-container">
					<!-- BEGIN account-sidebar -->
					<div class="account-sidebar">
						<div class="account-sidebar-cover">
							<img src="<?php echo base_url('assets/img/registerBG.jpg');?>" alt="" />
						</div>
						<div class="account-sidebar-content">
							<h4>Daftar</h4>
							<p class="mb-2 mb-lg-4">
								Daftar menjadi pelanggan <?php echo $appsProfile->apps_name;?> sekarang juga!
								<br> dan nikmati promo menarik yang tersedia.
							</p>
							<p class="mb-2 mb-lg-4">
								Semua kebutuhan elektronik kamu tersedia disini!
							</p>
						</div>
					</div>
					<!-- END account-sidebar -->
					<!-- BEGIN account-body -->
					<div class="account-body">
						<!-- BEGIN row -->
						<div class="row">
							<!-- BEGIN col-6 -->
							<div class="col-md-8">
							<h4><i class="fa fa-users fa-fw text-primary"></i> Pendaftaran</h4>
							<?php echo $this->session->flashdata('notif');?>
							<form action="<?php echo base_url('auth/register/doRegister');?>" method="POST">
								<label class="control-label">Nama Lengkap <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="text" class="form-control" name="nama_lengkap" value="<?php echo set_value('nama_lengkap'); ?>" placeholder="Masukkan Nama Lengkap" required />
										<?php echo form_error('nama_lengkap'); ?>
									</div>
								</div>
								<label class="control-label">No WA <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="text" class="form-control" name="no_telp" value="<?php echo set_value('no_telp'); ?>" onkeypress="onlyNumberKey(this.event)" placeholder="No HP/WA (62xxxxxx)" required/>
										<?php echo form_error('no_telp'); ?>
									</div>
								</div>
								<label class="control-label">PIN <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="password" class="form-control" name="password" minlength="6" maxlength="6" onkeypress="onlyNumberKey(this.event)" value="<?php echo set_value('password'); ?>" placeholder="PIN" id="pass" onkeyup="cekPassword()" required/>
										<?php echo form_error('password'); ?>
									</div>
								</div>
								<label class="control-label">Ulangi PIN <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="password" class="form-control" placeholder="Ulangi PIN" minlength="6" maxlength="6" value="<?php echo set_value('re_password'); ?>" onkeyup="cekPassword()" id="re_pass" name="re_password" required/>
										<font color="red" id="notifrepass">
											<?php echo form_error('password'); ?>
										</font>
									</div>
								</div>
								<script type="text/javascript">
									function cekPassword() {
									var repass = $('#re_pass').val()
									var pass = $('#pass').val()
									if (repass != pass || pass != repass) {
										$('#notifrepass').prop('color', 'red');
										$('#notifrepass').text('Ulangi password tidak sama dengan password');
										$('#btnSimpan').attr('disabled', true);
									} else {
										$('#notifrepass').prop('color', 'green');
										$('#notifrepass').text('Ulangi password sama dengan password');
										$('#btnSimpan').removeAttr('disabled');
									}
									}
								</script>
									<div class="form-footer">
										<button type="submit" id="btnSimpan" class="btn btn-primary btn-block">Daftar</button>
									</div>
								</form>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-md-4">
								<h4><i class="fa fa-sign-in-alt fa-fw text-primary"></i> Masuk</h4>
								<ul class="nav nav-list">
									<li><a href="<?php echo base_url('auth/forget');?>" class="btn btn-warning btn-md btn-block text-white"><i class="fa fa-key"></i> Lupa PIN</a></li>
									<!-- <li><a href="#" class="btn btn-info btn-md btn-block text-white"><i class="fab fa-facebook-f"></i> Facebook</a></li>
									<li><a href="#" class="btn btn-danger btn-md btn-block text-white"><i class="fab fa-google"></i> Google</a></li> -->
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