
<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Muhammad Fathony');
$pdf->SetTitle('Laporan Permintaan Barang');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' ', PDF_HEADER_STRING);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 6);
// set page format (read source code documentation for further information)
// MediaBox - width = urx - llx 210 (mm), height = ury - lly = 297 (mm) this is A4
$page_format = array(
    'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 58, 'ury' => 297),
    //'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 210, 'ury' => 297),
    //'BleedBox' => array ('llx' => 5, 'lly' => 5, 'urx' => 205, 'ury' => 292),
    //'TrimBox' => array ('llx' => 10, 'lly' => 10, 'urx' => 200, 'ury' => 287),
    //'ArtBox' => array ('llx' => 15, 'lly' => 15, 'urx' => 195, 'ury' => 282),
    'Dur' => 3,
    'trans' => array(
        'D' => 1.5,
        'S' => 'Split',
        'Dm' => 'V',
        'M' => 'O'
    ),
    'Rotate' => 0,
    'PZ' => 1,
);

// Check the example n. 29 for viewer preferences

// add first page ---
// add a page
$pdf->AddPage('P', $page_format, false, false);


//
$pdf->Ln(3);
$table = '<!DOCTYPE html>
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

<tr>
    <td class="special"><?php echo  $nomor++ ?></td>
    <td class="special"><?php echo  $value ?></td>
    <td class="special"><?php echo  $value ?></td>
    <td class="special"><?php echo  $value ?></td>
    <td class="special"><?php echo  $value ?></td>
    <td class="special"><?php echo  $value ?></td>
  </tr>

</table>
</body>
</html>
';
$pdf->WriteHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, 'L', true);
//Close and output PDF document
ob_clean();
$pdf->Output('lap_permintaan.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
