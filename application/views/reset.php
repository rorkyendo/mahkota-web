		<!-- BEGIN #my-account -->
		<div id="about-us-cover" class="section-container">
			<!-- BEGIN container -->
			<div class="container">
				<!-- BEGIN breadcrumb -->
				<ul class="breadcrumb mb-3">
					<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
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
							<h4>Reset PIN</h4>
							<p class="mb-2 mb-lg-4">
								Ubah password kamu, dan nikmati kembali kemudahan berbelanja di aplikasi kami!
							</p>
						</div>
					</div>
					<!-- END account-sidebar -->
					<!-- BEGIN account-body -->
					<div class="account-body">
						<!-- BEGIN row -->
						<div class="row">
							<!-- BEGIN col-6 -->
							<div class="col-md-12">
							<h4><i class="fa fa-key fa-fw text-primary"></i> Lupa PIN</h4>
							<?php echo $this->session->flashdata('notif');?>
							<form action="<?php echo base_url('auth/reset/'.$resetpass_token.'/doReset');?>" method="POST">
								<label class="control-label">PIN <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="password" class="form-control" name="password" minlength="6" maxlength="6" onkeypress="onlyNumberKey(this.event)" value="<?php echo set_value('password'); ?>" placeholder="PIN" id="pass" onkeyup="cekPIN()" required />
										<?php echo form_error('password'); ?>
									</div>
								</div>
								<label class="control-label">Ulangi PIN <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="password" class="form-control" placeholder="Ulangi PIN" minlength="6" maxlength="6" value="<?php echo set_value('re_password'); ?>" onkeyup="cekPIN()" id="re_pass" name="re_password" required />
										<font color="red" id="notifrepass">
											<?php echo form_error('password'); ?>
										</font>
									</div>
								</div>
								<script type="text/javascript">
									function cekPIN() {
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
										<button type="submit" id="btnSimpan" class="btn btn-primary btn-block">Kirim</button>
									</div>
								</form>
								<div class="d-none d-lg-block d-md-block">
									<br />
									<br />
									<br />
									<br />
									<br />
									<br />
									<br />
									<br />
									<br />
									<br />
								</div>
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