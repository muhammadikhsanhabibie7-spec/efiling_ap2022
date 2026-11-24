<?php
$instansi = !empty($st['instansi']) ? $st['instansi'] : 'POLITEKNIK LP3I JAKARTA';
$pemerintah = !empty($st['pemerintah']) ? $st['pemerintah'] : '';
$alamat = !empty($st['alamat']) ? $st['alamat'] : (!empty($st['ibukota']) ? $st['ibukota'] : '');
$path_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
$logo_url = ($path_logo && file_exists($path_logo)) ? base_url().'logo/'.$st['pemerintah_logo'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lembar Disposisi - <?php echo html_escape($row->no_surat); ?></title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 12pt; color: #000; margin: 25px; }
    .kop { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 16px; }
    .kop img { height: 68px; margin-right: 16px; }
    .kop-text { flex: 1; text-align: center; }
    .kop-text h2 { margin: 0; font-size: 16pt; text-transform: uppercase; }
    .kop-text p { margin: 3px 0 0; font-size: 10pt; }
    h3.judul { text-align: center; margin: 10px 0 16px; text-decoration: underline; letter-spacing: 1px; }
    table.sheet { width: 100%; border-collapse: collapse; }
    table.sheet td, table.sheet th { border: 1px solid #000; padding: 8px 10px; vertical-align: top; }
    .lbl { width: 22%; font-weight: bold; background: #f5f5f5; }
    .box-area { min-height: 85px; }
    .ttd { width: 100%; margin-top: 30px; }
    .ttd td { width: 50%; text-align: center; vertical-align: top; }
    .no-print { margin-bottom: 14px; text-align: right; }
    @media print { .no-print { display: none; } body { margin: 10px; } }
  </style>
</head>
<body>
  <div class="no-print">
    <button onclick="window.print()" style="padding:7px 16px;cursor:pointer;">Cetak Lembar Disposisi</button>
    <button onclick="window.close()" style="padding:7px 14px;cursor:pointer;">Tutup</button>
  </div>

  <div class="kop">
    <?php if ($logo_url) { ?><img src="<?php echo $logo_url; ?>" alt="Logo"><?php } ?>
    <div class="kop-text">
      <h2><?php echo html_escape($instansi); ?></h2>
      <?php if ($pemerintah) echo '<p><strong>'.html_escape($pemerintah).'</strong></p>'; ?>
      <?php if ($alamat) echo '<p>'.html_escape($alamat).'</p>'; ?>
    </div>
  </div>

  <h3 class="judul">LEMBAR DISPOSISI</h3>

  <table class="sheet">
    <tr>
      <td class="lbl">Nomor Surat</td>
      <td><?php echo html_escape($row->no_surat); ?></td>
      <td class="lbl">Tanggal Disposisi</td>
      <td><?php echo tanggal($row->tgl_disposisi); ?></td>
    </tr>
    <tr>
      <td class="lbl">Tanggal Surat</td>
      <td><?php echo tanggal($row->tgl_surat); ?></td>
      <td class="lbl">No. Agenda / Sifat</td>
      <td><?php echo html_escape(($row->no_agenda ? $row->no_agenda : '-').' / '.$row->sifat); ?></td>
    </tr>
    <tr>
      <td class="lbl">Jenis Surat</td>
      <td><?php echo ($row->jenis_surat == 'K') ? 'K (Surat Keluar)' : 'M (Surat Masuk)'; ?></td>
      <td class="lbl">Asal / Dari</td>
      <td><?php echo html_escape($row->pengirim ? $row->pengirim : '-'); ?></td>
    </tr>
    <tr>
      <td class="lbl">Perihal</td>
      <td colspan="3"><strong><?php echo html_escape($row->judul); ?></strong></td>
    </tr>
    <tr>
      <td class="lbl">Diteruskan Kepada</td>
      <td colspan="3">
        <?php
          $tj = array();
          if (!empty($row->nama_bidang)) $tj[] = $row->nama_bidang;
          if (!empty($row->nama_tujuan)) $tj[] = $row->nama_tujuan;
          if (!empty($row->diteruskan_kepada)) $tj[] = $row->diteruskan_kepada;
          echo !empty($tj) ? html_escape(implode(' / ', $tj)) : '-';
        ?>
      </td>
    </tr>
    <tr>
      <td class="lbl">Tembusan (Mengetahui)</td>
      <td colspan="3"><?php echo !empty($row->tembusan) ? html_escape($row->tembusan) : '-'; ?></td>
    </tr>
    <tr>
      <td colspan="2" class="box-area">
        <strong>Instruksi / Informasi Disposisi:</strong><br><br>
        <?php echo nl2br(html_escape($row->instruksi)); ?>
        <?php if (!empty($row->catatan)) echo '<br><br><em>Catatan: '.nl2br(html_escape($row->catatan)).'</em>'; ?>
      </td>
      <td colspan="2" class="box-area">
        <strong>Tindak Lanjut (TL) &mdash; Status: <?php echo html_escape($row->status_tl); ?></strong><br><br>
        <?php echo !empty($row->tindak_lanjut) ? nl2br(html_escape($row->tindak_lanjut)) : '<em>Belum ada catatan tindak lanjut.</em>'; ?>
        <?php if (!empty($row->tgl_tindak_lanjut)) echo '<br><br><small>Diperbarui: '.date('d/m/Y H:i', strtotime($row->tgl_tindak_lanjut)).'</small>'; ?>
      </td>
    </tr>
  </table>

  <table class="ttd">
    <tr>
      <td>
        Diterima Oleh,<br><br><br><br>
        ( <?php echo !empty($row->nama_tujuan) ? html_escape($row->nama_tujuan) : '....................................'; ?> )
      </td>
      <td>
        Pemberi Disposisi,<br><br><br><br>
        ( <?php echo !empty($row->nama_dari) ? html_escape($row->nama_dari) : '....................................'; ?> )
      </td>
    </tr>
  </table>
</body>
</html>
