		<!-- BEGIN #page-header -->
		<div id="page-header" class="section-container page-header-container bg-black">
			<!-- BEGIN page-header-cover -->
			<div class="page-header-cover">
				<!-- <img src="../assets/img/cover/cover-13.jpg" alt="" /> -->
			</div>
			<!-- END page-header-cover -->
			<!-- BEGIN container -->
			<div class="container">
				<h1 class="page-header">Beli pulsa termurah hanya dari aplikasi kami</h1>
			</div>
			<!-- END container -->
		</div>
		<!-- BEGIN #page-header -->

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
							<div class="col-md-12">
								<center>
									<button type="button" class="btn btn-default btn-danger" onclick="produk('TELKOMSEL')">Telkomsel</button>
									<button type="button" class="btn btn-default btn-blue" onclick="produk('XL')">XL</button>
									<button type="button" class="btn btn-default btn-warning" onclick="produk('INDOSAT')">Indosat</button>
									<button type="button" class="btn btn-default btn-black" onclick="produk('THREE')">Three</button>
									<button type="button" class="btn btn-default btn-purple" onclick="produk('AXIS')">Axis</button>
								</center>
								<div class="col-md-12" id="bodyProduk">
										test
								</div>
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

		<script>
			function produk(val){
				$.ajax({
					url:'<?php echo base_url('api/produk/onlineProduk');?>',
					type:'POST',
					data:{
						produk:val
					},
					dataType:'json',
					success:function(resp){
						$('#bodyProduk').html(resp.data)
					}
				})
			}
		</script>