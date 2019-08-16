<!DOCTYPE html>
<html>
<head>
<style>
.special {
    border-collapse: collapse;
    width: 58%;
}

.special {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
th {
    text-align: left;
}
</style>
</head>
<body>
<div >
    <b>Combo Putra</b>
    <br>
    Jalan Trunojoyo 10 No. 2 Banyumanik Semarang 50267 - Jawa Tengah<br>
    Telp. (024) 7472345, (085) 357472345 <br> Website : www.comboputra.com
<div>
<!-- remark -->
<table style="width: 100%;">
    <thead>
        <tr>
            <td style="width: 10%">
                <table style="width: 50%;">
                    <tr style="padding: 2px;">
                        <th>No. Nota</th>
                        <td>:</td>
                        <td>5353</td>
                    </tr>
                    <tr>
                        <th>Tanggal </th>
                        <td>:</td>
                        <td>12/2/2</td>
                    </tr>
                    <tr>
                        <th>Kasir </th>
                        <td>:</td>
                        <td>ok</td>
                    </tr>
                </table>
            </td>
            <td style="width: 20%;">
                <table style="width: 50%;">
                    <tr>
                        <th>Customers</th>
                        <td>:</td>
                        <td>Surya</td>
                    </tr>
                    <tr>
                        <th>Alamat </th>
                        <td>:</td>
                        <td>Jl. Semarang Kendal</td>
                    </tr>
                    <tr>
                        <th>Telp / Fax </th>
                        <td>:</td>
                        <td>238482 / 23888</td>
                    </tr>
                </table>
            </td>
        </tr>
    </thead>
</table>
<!-- remark -->
<table class="special" border="1px">
  <tr class="special">
    <th style="width:10%">Nomor</th>
    <th style="width:20%">Nomor Transaksi</th>
    <th style="width:20%">Kode Barang</th>
    <th>Nama Barang</th>
    <th>Jumlah</th>
    <th>Departemen</th>
  </tr>
<?php  
$nomor = 1;
foreach ($table as $key => $value) { ?>
<tr>
    <td class="special"><?php echo  $nomor++ ?></td>
    <td class="special"><?php echo  $value['firstname'] ?></td>
    <td class="special"><?php echo  $value['lastname'] ?></td>
    <td class="special"><?php echo  $value['email'] ?></td>
    <td class="special"><?php echo  $value['qrcode'] ?></td>
    <td class="special"><?php echo  $value['id_control'] ?></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
