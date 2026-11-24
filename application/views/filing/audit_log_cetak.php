<?php
$instansi = !empty($st['instansi']) ? $st['instansi'] : 'POLITEKNIK LP3I JAKARTA';
$pemerintah = !empty($st['pemerintah']) ? $st['pemerintah'] : '';
$alamat = !empty($st['alamat']) ? $st['alamat'] : (!empty($st['ibukota']) ? $st['ibukota'] : '');
$is_excel = ($mode === 'excel');
$path_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
$logo_url = (!$is_excel && $path_logo && file_exists($path_logo)) ? base_url().'logo/'.$st['pemerintah_logo'] : '';
$judul_laporan = 'LAPORAN LOG AKTIVITAS PENGGUNA (AUDIT TRAIL)';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?php echo html_escape($judul_laporan); ?></title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 9.5pt; color: #000; margin: 16px; }
    .kop { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 12px; }
    .kop img { height: 55px; margin-right: 14px; }
    .kop-text { flex: 1; text-align: center; }
    .kop-text h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
    .kop-text p { margin: 2px 0 0; font-size: 9.5pt; }
    .judul-wrap { text-align: center; margin-bottom: 12px; }
    .judul-wrap h3 { margin: 0; font-size: 12pt; }
    .judul-wrap p { margin: 3px 0 0; font-size: 9.5pt; }
    table.audit { width: 100%; border-collapse: collapse; }
    table.audit th, table.audit td { border: 1px solid #000; padding: 5px 6px; vertical-align: top; }
    table.audit th { background: #e8eef5; font-weight: bold; text-align: center; }
    .no-print { text-align: right; margin-bottom: 10px; }
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
  <div class="judul-wrap">
    <h3><?php echo html_escape($judul_laporan); ?></h3>
    <p>
      Periode: <strong><?php echo html_escape($periode_label); ?></strong>
      <?php if ($filter_modul !== '') echo ' | Modul: <strong>'.html_escape($filter_modul).'</strong>'; ?>
      <?php if ($filter_aksi !== '') echo ' | Aksi: <strong>'.html_escape($filter_aksi).'</strong>'; ?>
      <?php if ($filter_device !== '') echo ' | Device Cookie: <strong>'.html_escape($filter_device).'</strong>'; ?>
    </p>
  </div>
  <?php } else { ?>
  <table width="100%">
    <tr><td colspan="9" align="center"><strong><?php echo html_escape($instansi); ?></strong></td></tr>
    <?php if ($pemerintah) { ?><tr><td colspan="9" align="center"><?php echo html_escape($pemerintah); ?></td></tr><?php } ?>
    <tr><td colspan="9" align="center"><strong><?php echo html_escape($judul_laporan); ?></strong></td></tr>
    <tr><td colspan="9" align="center">Periode: <?php echo html_escape($periode_label); ?></td></tr>
    <tr><td colspan="9"></td></tr>
  </table>
  <?php } ?>

  <table class="audit" border="1">
    <thead>
      <tr>
        <th width="30">No</th>
        <th width="115">Waktu</th>
        <th width="110">Username</th>
        <th width="130">Nama &amp; Role</th>
        <th width="85">Modul</th>
        <th width="85">Aksi</th>
        <th>Objek &amp; Detail Aktivitas</th>
        <th width="115">Device Cookie ID</th>
        <th width="90">IP Address</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($rows)) {
        $no = 1;
        foreach ($rows as $r) {
          $waktu_fmt = !empty($r->waktu) ? date('d/m/Y H:i:s', strtotime($r->waktu)) : '-';
      ?>
      <tr>
        <td align="center"><?php echo $no++; ?></td>
        <td align="center"><?php echo html_escape($waktu_fmt); ?></td>
        <td><?php echo html_escape($r->username); ?></td>
        <td><?php echo html_escape($r->nama_user).' ('.html_escape($r->nama_role).')'; ?></td>
        <td align="center"><?php echo html_escape($r->modul); ?></td>
        <td align="center"><strong><?php echo html_escape($r->aksi); ?></strong></td>
        <td>
          <?php if (!empty($r->objek)) echo '<strong>'.html_escape($r->objek).'</strong><br>'; ?>
          <?php echo nl2br(html_escape($r->detail)); ?>
        </td>
        <td align="center"><?php echo html_escape($r->device_cookie); ?></td>
        <td align="center"><?php echo html_escape($r->ip_address); ?></td>
      </tr>
      <?php } } else { ?>
      <tr><td colspan="9" align="center">Tidak ada data log aktivitas.</td></tr>
      <?php } ?>
    </tbody>
  </table>

  <?php if (!$is_excel) { ?>
  <div style="margin-top:16px;font-size:9pt;text-align:right;">
    Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?> | Total Data: <?php echo number_format(count($rows), 0, ',', '.'); ?>
  </div>
  <?php } ?>
</body>
</html>
