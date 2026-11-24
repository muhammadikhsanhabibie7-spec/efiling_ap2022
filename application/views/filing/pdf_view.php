<!doctype html>
<html>
    <head>
        <title><?php echo @$title; ?></title>
    </head>
    <body>
<!-- hedaer 2 -->
<htmlpageheader name="myHTMLHeader2Even">
<table width="100%" style="border-bottom: 1px solid #880000; vertical-align: bottom; font-family: sans; font-size: 14pt; color: #880000;"><tr>
<td><img src="<?php echo @$company_logo ?>" width="100px" style="float:left;" /></td>
<td style="text-align:left;width:27%;padding-top:22px;font-size:8pt;color:black;">
<?php echo @$company_address.'<br> '.@$company_country; ?>
</td>
<td width="33%" align="center">
<h4 style="text-align:center;color:black;"><?php echo @$h_title_center ?></h4>
<p align="center"><?php echo @$h_sub_center ?></p></td>
<td width="33%" style="text-align: right;"><span style="font-size:9pt;">Halaman : {PAGENO}</span></td>
</tr></table>
</htmlpageheader>
<!-- ./hedaer 2 -->

<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>

<sethtmlpageheader name="myHTMLHeader2Even" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
<!-- mpdf -->

<div style="text-align: right;font-size: 5pt;">Tanggal Cetak: <?php echo @$tgl_cetak; ?></div>

<?php echo $table; ?>


<!-- <div style="text-align: center; font-style: italic;">Payment terms: payment due in 30 days</div> -->


</body>
</html>