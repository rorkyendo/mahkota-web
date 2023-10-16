<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendaftaran Member</title>
    <style>
      .xlText {
          mso-number-format: "\@";
      }
      td{
        font-size:16px;
      }
    </style>
</head>
<body>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan-Pendaftaran-Member.xls");
?>
<?php
$headerTxt = '<table cellspacing="0" cellpadding="0">';
$headerTxt .= '<tr>';
$headerTxt .= '<td width="100%" style="font-weight:bold;text-align:center">';
$headerTxt .= 'LAPORAN PENDAFTARAN MEMBER';
$headerTxt .= "</td>";
$headerTxt .= "</tr>";
$headerTxt .= '<tr>';
$headerTxt .= '<td width="100%" style="font-weight:bold;text-align:center">';
$headerTxt .= tgl_indo($start_date).' - '. tgl_indo($end_date); 
$headerTxt .= "</td>";
$headerTxt .= "</tr>";
$headerTxt .= "</table>";
echo $headerTxt;
?>
<?php
$html = '';
  $html .= '<br/><br/><table border="1px" style="width:100%;font-size:10px;" cellspacing="0">
      <thead>
        <tr style="font-weight:bold">
          <th align="center" width="5%">ID Transaksi</th>
          <th align="center" width="10%">Nama Lengkap</th>
          <th align="center" width="10%">No WA</th>
          <th align="center" width="10%">Status Pembayaran</th>
          <th align="center" width="10%">Harga</th>
          <th align="center" width="10%">Waktu Transaksi</th>
        </tr>
      </thead>
      <tbody>';
      $no=1;
      $total = 0;
      foreach ($pendaftaran as $row) {
          $total += $row->total;
          $html .= '<tr nobr="true">';
          $html .= '<td width="5%">' . $row->id_transaksi . '</td>';
          $html .= '<td width="10%" class="xlText">'.$row->nama_lengkap_pelanggan.'</td>';
          $html .= '<td width="10%" class="xlText">' . $row->no_telp_pelanggan . '</td>';
          if ($row->payment_status == 'payed') {
            $html .= '<td width="10%">Lunas</td>';
          }elseif ($row->payment_status == 'pending') {
            $html .= '<td width="10%">Pending</td>';
          }elseif ($row->payment_status == 'process') {
            $html .= '<td width="10%">Diproses</td>';
          }elseif ($row->payment_status == 'cancel') {
            $html .= '<td width="10%">Dibatalkan</td>';
          }elseif ($row->payment_status == 'refund') {
            $html .= '<td width="10%">Dikembalikan</td>';
          }
          $html .= '<td width="10%">Rp.' . number_format($row->total,0,'.','.').'</td>';
          $html .= '<td width="10%">' . tgl_indo($row->created_time) . '</td>';
          $html .= '</tr>';
      }      
$html.='</tbody>';
$html.='<tfoot>';
          $html .= '<tr nobr="true">';
          $html .= '<td colspan="4">Total</td>';
          $html .= '<td colspan="2">Rp.'.number_format($total,0,'.','.').'</td>';
          $html .= '</tr>';
$html.='</tfoot>
      </table>';
echo $html;
?>
</body>
</html>