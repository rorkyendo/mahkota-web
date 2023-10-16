<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $appsProfile->apps_name;?> | Verifikasi OTP</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() . $appsProfile->icon; ?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<style>
		body{background-color:red}
		.height-100{height:100vh}
		.card{width:400px;border:none;height:300px;box-shadow: 0px 5px 20px 0px #d2dae3;z-index:1;display:flex;justify-content:center;align-items:center}
		.card h6{color:red;font-size:20px}.inputs input{width:40px;height:40px}input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button{-webkit-appearance: none;-moz-appearance: none;appearance: none;margin: 0}.card-2{background-color:#fff;padding:10px;width:350px;height:100px;bottom:-50px;left:20px;position:absolute;border-radius:5px}.card-2 .content{margin-top:50px}.card-2 .content a{color:red}.form-control:focus{box-shadow:none;border:2px solid red}.validate{border-radius:20px;height:40px;background-color:red;border:1px solid red;width:140px}
	</style>
</head>
<body>
	<div class="container height-100 d-flex justify-content-center align-items-center"> 
		<div class="position-relative"> <div class="card p-2 text-center"> 
			<h6>Silahkan masukkan kode OTP</h6>
			 <?php echo $this->session->flashdata('notif');?>
			 <div> <span>Kode OTP Telah dikirim ke nomor whatsapp anda</span> </div> 
			 <form action="<?php echo base_url('auth/otp/submitOTP');?>" method="post">
			 <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2"> 
				<input class="m-2 text-center form-control rounded" type="text" name="kode_otp[]" id="first" maxlength="1" /> 
				<input class="m-2 text-center form-control rounded" type="text" name="kode_otp[]" id="second" maxlength="1" /> 
				<input class="m-2 text-center form-control rounded" type="text" name="kode_otp[]" id="third" maxlength="1" /> 
				<input class="m-2 text-center form-control rounded" type="text" name="kode_otp[]" id="fourth" maxlength="1" /> 
				<input class="m-2 text-center form-control rounded" type="text" name="kode_otp[]" id="fifth" maxlength="1" /> 
				<input class="m-2 text-center form-control rounded" type="text" name="kode_otp[]" id="sixth" maxlength="1" /> 
			</div> 
			<div class="mt-4"> 
				<button type="submit" class="btn btn-danger px-4 validate">Verifikasi</button>
				</form> 
			</div> 
		</div> 
		<div class="card-2"> 
			<div class="content d-flex justify-content-center align-items-center"> 
				<span>Kirim ulang dalam &nbsp;</span> <span id="timer">60</span>&nbsp;detik <a href="#" class="text-decoration-none ms-3" id="resend" hidden>(Kirim ulang)</a> 
			</div> 
		</div> 
	</div>
	<script>
		document.addEventListener("DOMContentLoaded", function(event) {
		function OTPInput() {
			const inputs = document.querySelectorAll('#otp > *[id]');
			for (let i = 0; i < inputs.length; i++) { inputs[i].addEventListener('keydown', function(event) { if (event.key==="Backspace" ) { inputs[i].value='' ; if (i !==0) inputs[i - 1].focus(); } else { if (i===inputs.length - 1 && inputs[i].value !=='' ) { return true; } else if (event.keyCode> 47 && event.keyCode < 58) { inputs[i].value=event.key; if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } else if (event.keyCode> 64 && event.keyCode < 91) { inputs[i].value=String.fromCharCode(event.keyCode); if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } } }); } } OTPInput();
		});

		$("#resend").click(function(){
			location.replace("<?php echo base_url('auth/otp/resend');?>")
		})

		setInterval(() => {
			var time = $("#timer").text();
			time-=1
			if (time >= 0) {
				$("#timer").text(time);
			}
			if(time == 0){
				$("#resend").removeAttr('hidden')
			}
		}, 1000);
	</script>
</body>
</html>