<?php
$instansi = !empty($st['instansi']) ? $st['instansi'] : 'POLITEKNIK LP3I JAKARTA';
$pemerintah = !empty($st['pemerintah']) ? $st['pemerintah'] : '';
$alamat = !empty($st['alamat']) ? $st['alamat'] : (!empty($st['ibukota']) ? $st['ibukota'] : '');
$is_excel = ($mode === 'excel');
$path_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
$logo_url = (!$is_excel && $path_logo && file_exists($path_logo)) ? base_url().'logo/'.$st['pemerintah_logo'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?php echo html_escape($judul_agenda); ?></title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10.5pt; color: #000; margin: 20px; }
    .kop { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 14px; }
    .kop img { height: 60px; margin-right: 14px; }
    .kop-text { flex: 1; text-align: center; }
    .kop-text h2 { margin: 0; font-size: 15pt; text-transform: uppercase; }
    .kop-text p { margin: 2px 0 0; font-size: 10pt; }
    .judul-wrap { text-align: center; margin-bottom: 14px; }
    .judul-wrap h3 { margin: 0; font-size: 13pt; }
    .judul-wrap p { margin: 4px 0 0; font-size: 10pt; }
    table.agenda { width: 100%; border-collapse: collapse; }
    table.agenda th, table.agenda td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
    table.agenda th { background: #e8eef5; font-weight: bold; text-align: center; }
    .no-print { text-align: right; margin-bottom: 12px; }
    @media print { .no-print { display: none; } body { margin: 8px; } }
  </style>
</head>
<body <?php if (!$is_excel) echo 'onload="window.print()"'; ?>>
  <?php if (!$is_excel) { ?>
  <div class="no-print">
    <button onclick="window.print()" style="padding:6px 14px;cursor:pointer;">Cetak</button>
    <button onclick="window.close()" style="padding:6px 12px;cursor:pointer;">Tutup</button>
  </div>
  <div class="kop">
    <?php if ($logo_url) { ?><img src="<?php echo $logo_url; ?>" alt="Logo"><?php } ?>
    <div class="kop-text">
      <h2><?php echo html_escape($instansi); ?></h2>
      <?php if ($pemerintah) echo '<p><strong>'.html_escape($pemerintah).'</strong></p>'; ?>
      <?php if ($alamat) echo '<p>'.html_escape($alamat).'</p>'; ?>
    </div>
  </div>
  <?php } else { ?>
  <table width="100%">
    <tr><td colspan="7" align="center"><strong><?php echo html_escape($instansi); ?></strong></td></tr>
    <?php if ($pemerintah) { ?><tr><td colspan="7" align="center"><?php echo html_escape($pemerintah); ?></td></tr><?php } ?>
    <tr><td colspan="7" align="center"><strong><?php echo html_escape($judul_agenda); ?></strong></td></tr>
    <tr><td colspan="7" align="center">Periode: <?php echo html_escape($periode_label); ?></td></tr>
    <tr><td colspan="7"></td></tr>
  </table>
  <?php } ?>

  <?php if (!$is_excel) { ?>
  <div class="judul-wrap">
    <h3><?php echo html_escape($judul_agenda); ?></h3>
    <p>Periode: <strong><?php echo html_escape($periode_label); ?></strong></p>
  </div>
  <?php } ?>

  <table class="agenda" border="1">
    <thead>
      <tr>
        <th width="35">No</th>
        <th>Nomor Surat</th>
        <th width="95">Tanggal Surat</th>
        <th width="85">M / K</th>
        <th>Dari / Kepada</th>
        <th>Perihal</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($rows)) {
        $no = 1;
        foreach ($rows as $r) {
          $mk = ($r->jenis_surat == 'K') ? 'K (Keluar)' : 'M (Masuk)';
          $dk = array();
          if (!empty($r->pengirim)) $dk[] = 'Dari: '.$r->pengirim;
          if (!empty($r->penerima)) $dk[] = 'Kepada: '.$r->penerima;
      ?>
      <tr>
        <td align="center"><?php echo $no++; ?></td>
        <td><?php echo html_escape($r->no_surat); ?></td>
        <td align="center"><?php echo !empty($r->tanggal) ? tanggal($r->tanggal) : '-'; ?></td>
        <td align="center"><?php echo $mk; ?></td>
        <td><?php echo !empty($dk) ? nl2br(html_escape(implode("\n", $dk))) : '-'; ?></td>
        <td><?php echo html_escape($r->judul); ?></td>
        <td><?php echo !empty($r->keterangan) ? nl2br(html_escape($r->keterangan)) : '-'; ?></td>
      </tr>
      <?php } } else { ?>
      <tr><td colspan="7" align="center">Tidak ada data surat.</td></tr>
      <?php } ?>
    </tbody>
  </table>

  <?php if (!$is_excel) { ?>
  <div style="margin-top:20px;font-size:9.5pt;text-align:right;">
    Dicetak pada: <?php echo date('d/m/Y H:i'); ?>
  </div>
  <?php } ?>
</body>
</html>
