<!DOCTYPE html>
<html>
<head>
    <title>Detail Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .logo {
            width: 200px;
            margin-bottom: 20px;
        }
        .card {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 5px;
        }
        h3 {
            font-size: 24px;
            margin-top: 0;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="text-center">
                <img src="<?php echo base_url('assets/img/logo.png'); ?>" class="logo" alt="Logo">
                <h3>Segera lakukan pembayaran</h3>
                <?php if ($cekTransaksi): ?>
                    <p>Ke nomor rekening berikut ini:</p>
                    <h3><?php echo $via.' '.$channel; ?></h3>
                    <h3><?php echo $va; ?></h3>
                <?php else: ?>
                    <h3>Transaksi tidak ditemukan</h3>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
