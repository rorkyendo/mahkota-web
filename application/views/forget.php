		<!-- BEGIN #my-account -->
		<div id="about-us-cover" class="section-container">
			<!-- BEGIN container -->
			<div class="container">
				<!-- BEGIN breadcrumb -->
				<ul class="breadcrumb mb-3">
					<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url('auth/forget');?>">Lupa Password</a></li>
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
							<h4>Lupa PIN</h4>
							<p class="mb-2 mb-lg-4">
								Jangan khawatir PIN kamu masih bisa dikembalikan, silahkan masukkan no wa aktif kamu untuk merubah PIN!
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
							<form action="<?php echo base_url('auth/forget/reset');?>" method="POST">
								<label class="control-label">No HP/WA <span class="text-danger">*</span></label>
								<div class="row m-b-15">
									<div class="col-md-12">
										<input type="text" onkeypress="onlyNumberKey(this.event)" class="form-control" name="no_telp" placeholder="Masukkan HP/WA" required/>
									</div>
								</div>
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