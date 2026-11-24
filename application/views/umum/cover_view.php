<?php
$st = !empty($st) ? $st : get_stationer();
$main_color = !empty($st['main_color']) ? $st['main_color'] : '#0d055f';
$path_inst_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
$logo_instansi = ($path_inst_logo && file_exists($path_inst_logo)) ? base_url().'logo/'.$st['pemerintah_logo'] : base_url().'assets/logo/brand.png';
$app_name = !empty($st['aplikasi']) ? $st['aplikasi'] : 'Elektronik Filing';
$app_short = !empty($st['aplikasi_s']) ? $st['aplikasi_s'] : 'e-Filing';
$instansi = !empty($st['instansi']) ? $st['instansi'] : 'POLITEKNIK LP3I JAKARTA';
$pemerintah = !empty($st['pemerintah']) ? $st['pemerintah'] : 'Kampus Pengembangan';

$cs = !empty($cover_stats) ? $cover_stats : array(
  'total_konten' => 0, 'surat_masuk' => 0, 'surat_keluar' => 0,
  'arsip_aktif' => 0, 'arsip_retensi' => 0, 'total_folder' => 0,
  'total_disposisi' => 0, 'disposisi_menunggu' => 0, 'disposisi_diproses' => 0, 'disposisi_proses' => 0, 'disposisi_selesai' => 0,
  'total_peminjaman' => 0, 'pinjam_menunggu' => 0, 'pinjam_dipinjam' => 0, 'pinjam_kembali' => 0, 'pinjam_ditolak' => 0,
  'total_jenis' => 0, 'total_unit' => 0, 'total_bidang' => 0,
  'jenis_dist' => array(),
  'sifat_dist' => array('Biasa' => 0, 'Penting' => 0, 'Segera' => 0, 'Rahasia' => 0),
  'retensi_dist' => array('Aktif' => 0, 'Inaktif' => 0, 'Retensi' => 0, 'Permanen' => 0, 'Musnah' => 0),
  'monthly_trend' => array()
);

$jenis_bars = array();
$palette_jenis = array('#0284c7', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899');
if (!empty($cs['jenis_dist'])) {
  foreach ($cs['jenis_dist'] as $idx => $jd) {
    $jenis_bars[] = array(
      'label' => $jd['label'],
      'value' => (int) $jd['value'],
      'color' => $palette_jenis[$idx % count($palette_jenis)]
    );
  }
}

$sifat_map = !empty($cs['sifat_dist']) ? $cs['sifat_dist'] : array();
$sifat_bars = array(
  array('label' => 'Biasa',   'value' => isset($sifat_map['Biasa'])   ? (int) $sifat_map['Biasa']   : 0, 'color' => '#64748b'),
  array('label' => 'Penting', 'value' => isset($sifat_map['Penting']) ? (int) $sifat_map['Penting'] : 0, 'color' => '#0284c7'),
  array('label' => 'Segera',  'value' => isset($sifat_map['Segera'])  ? (int) $sifat_map['Segera']  : 0, 'color' => '#f59e0b'),
  array('label' => 'Rahasia', 'value' => isset($sifat_map['Rahasia']) ? (int) $sifat_map['Rahasia'] : 0, 'color' => '#ef4444')
);

$ret_map = !empty($cs['retensi_dist']) ? $cs['retensi_dist'] : array();
$retensi_bars = array(
  array('label' => 'Aktif',    'value' => isset($ret_map['Aktif'])    ? (int) $ret_map['Aktif']    : 0, 'color' => '#10b981'),
  array('label' => 'Inaktif',  'value' => isset($ret_map['Inaktif'])  ? (int) $ret_map['Inaktif']  : 0, 'color' => '#f59e0b'),
  array('label' => 'Retensi',  'value' => isset($ret_map['Retensi'])  ? (int) $ret_map['Retensi']  : 0, 'color' => '#8b5cf6'),
  array('label' => 'Permanen', 'value' => isset($ret_map['Permanen']) ? (int) $ret_map['Permanen'] : 0, 'color' => '#0284c7'),
  array('label' => 'Musnah',   'value' => isset($ret_map['Musnah'])   ? (int) $ret_map['Musnah']   : 0, 'color' => '#ef4444')
);

$feature_details = array(
  'surat' => array(
    'icon'     => 'fa-envelope-o',
    'title'    => 'Surat Masuk & Keluar',
    'subtitle' => 'Pengarsipan digital terstruktur berbasis subjek, klasifikasi Masuk (M) & Keluar (K), serta unit kerja.',
    'stats'    => array(
      array('label' => 'Total Dokumen Arsip', 'value' => number_format($cs['total_konten'], 0, ',', '.'), 'sub' => 'Dalam '.$cs['total_folder'].' folder subjek'),
      array('label' => 'Surat Masuk (M)', 'value' => number_format($cs['surat_masuk'], 0, ',', '.'), 'sub' => 'Tercatat di sistem'),
      array('label' => 'Surat Keluar (K)', 'value' => number_format($cs['surat_keluar'], 0, ',', '.'), 'sub' => 'Tercatat di sistem')
    ),
    'chartLeft' => array(
      'type'        => 'donut',
      'title'       => 'Proporsi Surat Masuk vs Keluar',
      'centerLabel' => 'Dokumen',
      'items'       => array(
        array('label' => 'Surat Masuk (M)',  'value' => (int) $cs['surat_masuk'],  'color' => '#0284c7'),
        array('label' => 'Surat Keluar (K)', 'value' => (int) $cs['surat_keluar'], 'color' => '#f59e0b')
      )
    ),
    'chartRight' => array(
      'type'  => 'hbar',
      'title' => 'Distribusi per Jenis Arsip',
      'unit'  => 'berkas',
      'items' => $jenis_bars
    ),
    'steps'    => array(
      'Pilih atau buat folder subjek arsip sesuai klasifikasi unit kerja dan jenis dokumen.',
      'Masukkan metadata surat (Nomor Surat, Jenis M/K, Pengirim/Penerima, Masa Aktif) serta unggah lampiran digital (PDF/gambar).',
      'Arsip tersimpan aman dengan validasi anti-duplikat nomor surat dan siap dicari, didisposisi, atau dipinjam.'
    ),
    'roles'    => array('Super Admin', 'Admin Arsip', 'Operator', 'Pimpinan (Pantau)'),
    'primary'  => null,
    'secondary'=> null
  ),
  'disposisi' => array(
    'icon'     => 'fa-share-square-o',
    'title'    => 'Disposisi & Tindak Lanjut',
    'subtitle' => 'Penerusan surat secara berjenjang lengkap dengan instruksi pimpinan, tembusan, status TL, dan cetak lembar disposisi.',
    'stats'    => array(
      array('label' => 'Total Disposisi', 'value' => number_format($cs['total_disposisi'], 0, ',', '.'), 'sub' => 'Riwayat instruksi surat'),
      array('label' => 'Menunggu / Diproses', 'value' => number_format($cs['disposisi_proses'], 0, ',', '.'), 'sub' => 'Sedang berjalan'),
      array('label' => 'Selesai (TL)', 'value' => number_format($cs['disposisi_selesai'], 0, ',', '.'), 'sub' => 'Sudah ditindaklanjuti')
    ),
    'chartLeft' => array(
      'type'        => 'donut',
      'title'       => 'Status Tindak Lanjut (TL)',
      'centerLabel' => 'Disposisi',
      'items'       => array(
        array('label' => 'Selesai',  'value' => (int) $cs['disposisi_selesai'],  'color' => '#10b981'),
        array('label' => 'Diproses', 'value' => (int) $cs['disposisi_diproses'], 'color' => '#0284c7'),
        array('label' => 'Menunggu', 'value' => (int) $cs['disposisi_menunggu'], 'color' => '#f59e0b')
      )
    ),
    'chartRight' => array(
      'type'  => 'hbar',
      'title' => 'Prioritas / Sifat Disposisi',
      'unit'  => 'surat',
      'items' => $sifat_bars
    ),
    'steps'    => array(
      'Pimpinan atau Admin memilih dokumen surat lalu menentukan bidang/pegawai tujuan dan tembusan (mengetahui).',
      'Tetapkan sifat disposisi (Biasa, Penting, Segera, Rahasia) beserta catatan instruksi.',
      'Staf penerima melaporkan hasil tindak lanjut hingga status berubah Selesai, dan Lembar Disposisi dapat dicetak kapan saja.'
    ),
    'roles'    => array('Pimpinan', 'Super Admin', 'Admin Arsip', 'Operator (Tindak Lanjut)'),
    'primary'  => array('label' => 'Login & Buka Disposisi', 'url' => site_url('login?next=disposisi')),
    'secondary'=> null
  ),
  'peminjaman' => array(
    'icon'     => 'fa-book',
    'title'    => 'Peminjaman & Retensi Arsip',
    'subtitle' => 'Sirkulasi peminjaman arsip dengan konfirmasi berjenjang, notifikasi lonceng, serta kontrol otomatis masa aktif & inaktif.',
    'stats'    => array(
      array('label' => 'Total Transaksi Pinjam', 'value' => number_format($cs['total_peminjaman'], 0, ',', '.'), 'sub' => 'Tercatat di sistem'),
      array('label' => 'Menunggu / Dipinjam', 'value' => number_format($cs['pinjam_menunggu'] + $cs['pinjam_dipinjam'], 0, ',', '.'), 'sub' => $cs['pinjam_menunggu'].' menunggu ACC, '.$cs['pinjam_dipinjam'].' dipinjam'),
      array('label' => 'Arsip Aktif / Retensi', 'value' => $cs['arsip_aktif'].' / '.$cs['arsip_retensi'], 'sub' => 'Status jadwal retensi')
    ),
    'chartLeft' => array(
      'type'        => 'donut',
      'title'       => 'Status Transaksi Peminjaman',
      'centerLabel' => 'Pinjaman',
      'items'       => array(
        array('label' => 'Menunggu ACC',    'value' => (int) $cs['pinjam_menunggu'], 'color' => '#f59e0b'),
        array('label' => 'Sedang Dipinjam', 'value' => (int) $cs['pinjam_dipinjam'], 'color' => '#0284c7'),
        array('label' => 'Dikembalikan',    'value' => (int) $cs['pinjam_kembali'],  'color' => '#10b981'),
        array('label' => 'Ditolak',         'value' => (int) $cs['pinjam_ditolak'],  'color' => '#ef4444')
      )
    ),
    'chartRight' => array(
      'type'  => 'hbar',
      'title' => 'Status Jadwal Retensi Arsip',
      'unit'  => 'arsip',
      'items' => $retensi_bars
    ),
    'steps'    => array(
      'Operator atau peminjam mengajukan permintaan pinjam berkas dengan mencantumkan keperluan dan durasi pinjam.',
      'Admin Arsip melakukan verifikasi dan Super Admin memberikan validasi persetujuan (ACC/Tolak) secara real-time.',
      'Sistem otomatis memantau jatuh tempo pengembalian serta transisi status retensi arsip (Aktif ke Inaktif/Retensi).'
    ),
    'roles'    => array('Operator (Ajukan)', 'Admin Arsip (Verifikasi)', 'Super Admin (Validasi & Retensi)', 'Pimpinan (Pantau)'),
    'primary'  => array('label' => 'Login & Buka Peminjaman', 'url' => site_url('login?next=peminjaman')),
    'secondary'=> array('label' => 'Buka Retensi Arsip', 'url' => site_url('login?next=retensi'))
  ),
  'agenda' => array(
    'icon'     => 'fa-bar-chart',
    'title'    => 'Buku Agenda & Statistik',
    'subtitle' => 'Laporan Buku Agenda Tunggal per periode dengan ekspor Excel & Cetak serta grafik visualisasi statistik arsip.',
    'stats'    => array(
      array('label' => 'Dokumen di Agenda', 'value' => number_format($cs['total_konten'], 0, ',', '.'), 'sub' => $cs['surat_masuk'].' Masuk & '.$cs['surat_keluar'].' Keluar'),
      array('label' => 'Jenis Klasifikasi', 'value' => number_format($cs['total_jenis'], 0, ',', '.'), 'sub' => 'Kategori jenis arsip'),
      array('label' => 'Unit & Bidang Kerja', 'value' => $cs['total_unit'].' / '.$cs['total_bidang'], 'sub' => $cs['total_unit'].' Unit Kerja, '.$cs['total_bidang'].' Bidang')
    ),
    'chartLeft' => array(
      'type'   => 'monthly',
      'title'  => 'Tren Surat Masuk & Keluar (6 Bulan)',
      'series' => !empty($cs['monthly_trend']) ? $cs['monthly_trend'] : array()
    ),
    'chartRight' => array(
      'type'  => 'hbar',
      'title' => 'Rekap per Jenis Klasifikasi',
      'unit'  => 'berkas',
      'items' => $jenis_bars
    ),
    'steps'    => array(
      'Tentukan filter periode tanggal, jenis surat (Masuk/Keluar/Semua), atau unit kerja pada menu Buku Agenda.',
      'Tinjau rekapitulasi surat secara terpadu serta pantau tren pengarsipan melalui grafik Statistik Arsip.',
      'Ekspor laporan langsung ke format Excel (.xls) atau cetak dokumen resmi siap arsip & tanda tangan.'
    ),
    'roles'    => array('Super Admin', 'Admin Arsip', 'Pimpinan', 'Operator'),
    'primary'  => array('label' => 'Login & Buka Buku Agenda', 'url' => site_url('login?next=agenda')),
    'secondary'=> array('label' => 'Buka Statistik Arsip', 'url' => site_url('login?next=statistik'))
  )
);

$pct_masuk = $cs['total_konten'] > 0 ? round(($cs['surat_masuk'] / $cs['total_konten']) * 100) : 50;
$pct_tl    = $cs['total_disposisi'] > 0 ? round(($cs['disposisi_selesai'] / $cs['total_disposisi']) * 100) : 0;
$pct_pinj  = $cs['total_peminjaman'] > 0 ? round(($cs['pinjam_dipinjam'] / $cs['total_peminjaman']) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo html_escape($app_short.' - '.$app_name.' | '.$instansi); ?></title>
  <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css'; ?>" rel="stylesheet">
  <link href="<?php echo base_url().'assets/plugins/font-awesome/css/font-awesome.min.css'; ?>" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, <?php echo $main_color; ?> 0%, #0b3b66 55%, #05445E 100%);
      color: #ffffff;
      display: flex;
      flex-direction: column;
    }
    .cover-nav {
      padding: 18px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(255,255,255,0.12);
      background: rgba(0,0,0,0.12);
    }
    .cover-brand {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .cover-brand img {
      height: 50px;
      width: auto;
      background: #fff;
      padding: 4px 6px;
      border-radius: 8px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    .cover-brand-text h4 {
      margin: 0;
      font-size: 16px;
      font-weight: 700;
      letter-spacing: 0.4px;
    }
    .cover-brand-text small {
      color: rgba(255,255,255,0.78);
      font-size: 12px;
    }
    .cover-main {
      flex: 1;
      display: flex;
      align-items: center;
      padding: 40px 20px;
    }
    .hero-badge {
      display: inline-block;
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25);
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      margin-bottom: 18px;
    }
    .hero-title {
      font-size: 40px;
      font-weight: 800;
      line-height: 1.2;
      margin: 0 0 16px;
    }
    .hero-subtitle {
      font-size: 16px;
      line-height: 1.7;
      color: rgba(255,255,255,0.88);
      max-width: 580px;
      margin-bottom: 28px;
    }
    .btn-enter {
      background: #f59e0b;
      color: #0b192c;
      font-weight: 700;
      font-size: 15px;
      padding: 13px 30px;
      border-radius: 8px;
      border: none;
      text-decoration: none !important;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 8px 20px rgba(245,158,11,0.35);
      transition: all 0.2s ease;
    }
    .btn-enter:hover {
      background: #fbbf24;
      color: #0b192c;
      transform: translateY(-2px);
    }
    .feature-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }
    @media (max-width: 767px) {
      .hero-title { font-size: 28px; }
      .feature-grid { grid-template-columns: 1fr; margin-top: 30px; }
      .cover-nav { padding: 14px 18px; }
    }
    .feature-card {
      background: rgba(255,255,255,0.10);
      border: 1px solid rgba(255,255,255,0.18);
      border-radius: 12px;
      padding: 20px;
      backdrop-filter: blur(6px);
      transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      outline: none;
    }
    .feature-card:hover,
    .feature-card:focus {
      transform: translateY(-4px);
      background: rgba(255,255,255,0.16);
      border-color: rgba(252,211,77,0.65);
      box-shadow: 0 12px 28px rgba(0,0,0,0.24);
    }
    .feature-card-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
    }
    .feature-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      background: rgba(245,158,11,0.2);
      color: #fcd34d;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .feature-pill-count {
      font-size: 11.5px;
      font-weight: 700;
      padding: 3px 9px;
      border-radius: 14px;
      background: rgba(255,255,255,0.14);
      border: 1px solid rgba(255,255,255,0.2);
      color: #e2e8f0;
    }
    .feature-card h5 {
      margin: 0 0 6px;
      font-size: 15px;
      font-weight: 700;
    }
    .feature-card p {
      margin: 0;
      font-size: 13px;
      color: rgba(255,255,255,0.82);
      line-height: 1.5;
      flex: 1;
    }
    .feature-mini-bar {
      margin-top: 12px;
      height: 5px;
      border-radius: 99px;
      background: rgba(255,255,255,0.14);
      overflow: hidden;
      display: flex;
    }
    .feature-mini-bar span {
      display: block;
      height: 100%;
    }
    .feature-cta {
      margin-top: 10px;
      padding-top: 9px;
      border-top: 1px solid rgba(255,255,255,0.12);
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      font-weight: 600;
      color: #fcd34d;
    }
    .feature-cta .fa-arrow-right {
      transition: transform 0.2s ease;
    }
    .feature-card:hover .feature-cta .fa-arrow-right {
      transform: translateX(4px);
    }
    .cover-footer {
      text-align: center;
      padding: 16px 20px;
      font-size: 12.5px;
      color: rgba(255,255,255,0.72);
      border-top: 1px solid rgba(255,255,255,0.12);
      background: rgba(0,0,0,0.14);
    }

    /* Feature Detail Modal */
    .fmodal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(5, 16, 34, 0.74);
      backdrop-filter: blur(5px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }
    .fmodal-backdrop.is-open {
      display: flex;
      animation: fmodalFade 0.18s ease-out;
    }
    @keyframes fmodalFade {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .fmodal-dialog {
      width: 100%;
      max-width: 740px;
      background: #ffffff;
      color: #1e293b;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 24px 60px rgba(0,0,0,0.44);
      animation: fmodalPop 0.22s ease-out;
      max-height: 92vh;
      display: flex;
      flex-direction: column;
    }
    @keyframes fmodalPop {
      from { opacity: 0; transform: translateY(16px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .fmodal-header {
      background: linear-gradient(135deg, #0b2447 0%, #143f6b 100%);
      color: #ffffff;
      padding: 18px 24px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 14px;
      border-bottom: 3px solid #f59e0b;
    }
    .fmodal-head-left {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }
    .fmodal-icon {
      width: 46px;
      height: 46px;
      border-radius: 11px;
      background: rgba(245,158,11,0.22);
      color: #fcd34d;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }
    .fmodal-header h4 {
      margin: 0 0 4px;
      font-size: 19px;
      font-weight: 700;
    }
    .fmodal-header p {
      margin: 0;
      font-size: 13px;
      color: rgba(255,255,255,0.82);
      line-height: 1.45;
    }
    .fmodal-close {
      background: rgba(255,255,255,0.14);
      border: none;
      color: #ffffff;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      font-size: 18px;
      line-height: 1;
      cursor: pointer;
      flex-shrink: 0;
      transition: background 0.15s ease;
    }
    .fmodal-close:hover {
      background: rgba(255,255,255,0.26);
    }
    .fmodal-body {
      padding: 18px 24px 20px;
      overflow-y: auto;
    }
    .fmodal-live-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #047857;
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      padding: 4px 10px;
      border-radius: 20px;
      margin-bottom: 12px;
    }
    .fmodal-live-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #10b981;
      display: inline-block;
      box-shadow: 0 0 0 3px rgba(16,185,129,0.22);
    }
    .fmodal-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-bottom: 16px;
    }
    .fmodal-stat-box {
      background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 11px 14px;
    }
    .fmodal-stat-val {
      font-size: 22px;
      font-weight: 800;
      color: #0b3b66;
      line-height: 1.2;
      margin-bottom: 2px;
    }
    .fmodal-stat-lbl {
      font-size: 12px;
      font-weight: 700;
      color: #334155;
    }
    .fmodal-stat-sub {
      font-size: 11px;
      color: #64748b;
      margin-top: 2px;
    }

    /* Visual Chart Grid */
    .fmodal-charts {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 18px;
    }
    @media (max-width: 640px) {
      .fmodal-stats { grid-template-columns: 1fr; }
      .fmodal-charts { grid-template-columns: 1fr; }
    }
    .fmodal-chart-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 11px;
      padding: 14px 16px;
    }
    .fmodal-chart-title {
      font-size: 12px;
      font-weight: 700;
      color: #0f172a;
      margin: 0 0 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .fmodal-chart-title i {
      color: #0284c7;
    }

    /* Donut Chart */
    .donut-wrap {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .donut-svg {
      width: 118px;
      height: 118px;
      flex-shrink: 0;
    }
    .donut-seg {
      transition: stroke-dasharray 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .donut-legend {
      flex: 1;
      min-width: 0;
    }
    .donut-legend-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      margin-bottom: 7px;
      gap: 6px;
    }
    .donut-legend-row:last-child { margin-bottom: 0; }
    .donut-legend-left {
      display: flex;
      align-items: center;
      gap: 7px;
      color: #334155;
      font-weight: 600;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .donut-dot {
      width: 9px;
      height: 9px;
      border-radius: 3px;
      flex-shrink: 0;
    }
    .donut-legend-val {
      font-weight: 700;
      color: #0f172a;
      white-space: nowrap;
    }
    .donut-legend-pct {
      color: #64748b;
      font-weight: 500;
      font-size: 11px;
    }

    /* Horizontal Bar Chart */
    .hbar-row {
      margin-bottom: 9px;
    }
    .hbar-row:last-child { margin-bottom: 0; }
    .hbar-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 11.5px;
      margin-bottom: 4px;
      gap: 8px;
    }
    .hbar-name {
      font-weight: 600;
      color: #334155;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .hbar-val {
      font-weight: 700;
      color: #0f172a;
      white-space: nowrap;
    }
    .hbar-track {
      height: 8px;
      border-radius: 99px;
      background: #e2e8f0;
      overflow: hidden;
    }
    .hbar-fill {
      height: 100%;
      border-radius: 99px;
      width: 0%;
      transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* Monthly Grouped Bar Chart */
    .mchart-legend {
      display: flex;
      align-items: center;
      gap: 14px;
      font-size: 11px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 6px;
    }
    .mchart-legend span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    .mchart-svg {
      width: 100%;
      height: 132px;
      display: block;
    }

    .fmodal-section-title {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #475569;
      margin: 0 0 9px;
    }
    .fmodal-steps {
      list-style: none;
      padding: 0;
      margin: 0 0 16px;
    }
    .fmodal-steps li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13px;
      line-height: 1.5;
      color: #334155;
      margin-bottom: 7px;
    }
    .fmodal-step-num {
      width: 21px;
      height: 21px;
      border-radius: 50%;
      background: #0b3b66;
      color: #ffffff;
      font-size: 11px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .fmodal-roles {
      display: flex;
      flex-wrap: wrap;
      gap: 7px;
    }
    .fmodal-role-pill {
      background: #eef2ff;
      color: #1e3a8a;
      border: 1px solid #c7d2fe;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 16px;
    }
    .fmodal-footer {
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      padding: 13px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }
    .fmodal-actions {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .btn-fmodal-close {
      background: #e2e8f0;
      color: #334155;
      border: none;
      border-radius: 7px;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }
    .btn-fmodal-close:hover {
      background: #cbd5e1;
    }
    .btn-fmodal-secondary {
      background: #ffffff;
      color: #0b3b66;
      border: 1px solid #0b3b66;
      border-radius: 7px;
      padding: 8px 14px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none !important;
    }
    .btn-fmodal-secondary:hover {
      background: #eff6ff;
      color: #0b3b66;
    }
    .btn-fmodal-primary {
      background: #f59e0b;
      color: #0b192c;
      border: none;
      border-radius: 7px;
      padding: 9px 18px;
      font-size: 13.5px;
      font-weight: 700;
      text-decoration: none !important;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    }
    .btn-fmodal-primary:hover {
      background: #fbbf24;
      color: #0b192c;
    }
  </style>
</head>
<body>
  <header class="cover-nav">
    <div class="cover-brand">
      <img src="<?php echo $logo_instansi; ?>" alt="<?php echo html_escape($instansi); ?>">
      <div class="cover-brand-text">
        <h4><?php echo html_escape($instansi); ?></h4>
        <small><?php echo html_escape($pemerintah).' &bull; '.html_escape($app_name); ?></small>
      </div>
    </div>
    <a href="<?php echo site_url('register'); ?>" class="btn btn-sm btn-warning" style="font-weight:600;color:#0b192c;padding:7px 18px;">
      <i class="fa fa-user-plus"></i> Register
    </a>
  </header>

  <main class="cover-main">
    <div class="container">
      <div class="row" style="display:flex;flex-wrap:wrap;align-items:center;">
        <div class="col-md-6">
          <div class="hero-badge"><i class="fa fa-folder-open"></i> Sistem Manajemen Arsip &amp; Persuratan</div>
          <h1 class="hero-title"><?php echo html_escape($app_short); ?> &mdash; <?php echo html_escape($app_name); ?></h1>
          <p class="hero-subtitle">
            Platform terpadu pengelolaan surat masuk dan surat keluar, disposisi &amp; tindak lanjut,
            peminjaman arsip dengan verifikasi berjenjang, jadwal retensi, buku agenda tunggal, serta
            statistik arsip untuk mendukung pembelajaran dan administrasi perkantoran di
            <strong><?php echo html_escape($instansi); ?></strong>.
          </p>
          <a href="<?php echo site_url('login'); ?>" class="btn-enter">
            <i class="fa fa-lock"></i> Masuk ke Aplikasi
            <i class="fa fa-arrow-right"></i>
          </a>
        </div>

        <div class="col-md-6">
          <div class="feature-grid">
            <div class="feature-card" data-feature="surat" role="button" tabindex="0">
              <div class="feature-card-top">
                <div class="feature-icon"><i class="fa fa-envelope-o"></i></div>
                <span class="feature-pill-count"><?php echo number_format($cs['total_konten'], 0, ',', '.'); ?> Arsip</span>
              </div>
              <h5>Surat Masuk &amp; Keluar</h5>
              <p>Pengarsipan digital berbasis subjek &amp; unit kerja lengkap dengan klasifikasi Masuk (M) dan Keluar (K).</p>
              <div class="feature-mini-bar" title="Masuk <?php echo $pct_masuk; ?>% / Keluar <?php echo (100 - $pct_masuk); ?>%">
                <span style="width:<?php echo $pct_masuk; ?>%;background:#38bdf8;"></span>
                <span style="width:<?php echo (100 - $pct_masuk); ?>%;background:#fbbf24;"></span>
              </div>
              <div class="feature-cta">
                <span><i class="fa fa-pie-chart"></i> Lihat Grafik &amp; Detail</span>
                <i class="fa fa-arrow-right"></i>
              </div>
            </div>

            <div class="feature-card" data-feature="disposisi" role="button" tabindex="0">
              <div class="feature-card-top">
                <div class="feature-icon"><i class="fa fa-share-square-o"></i></div>
                <span class="feature-pill-count"><?php echo number_format($cs['total_disposisi'], 0, ',', '.'); ?> Disposisi</span>
              </div>
              <h5>Disposisi &amp; Tindak Lanjut</h5>
              <p>Penerusan surat disertai instruksi, tembusan (mengetahui), pemantauan status TL, dan cetak lembar disposisi.</p>
              <div class="feature-mini-bar" title="Selesai <?php echo $pct_tl; ?>%">
                <span style="width:<?php echo $pct_tl; ?>%;background:#34d399;"></span>
                <span style="width:<?php echo (100 - $pct_tl); ?>%;background:#38bdf8;"></span>
              </div>
              <div class="feature-cta">
                <span><i class="fa fa-pie-chart"></i> Lihat Grafik &amp; Detail</span>
                <i class="fa fa-arrow-right"></i>
              </div>
            </div>

            <div class="feature-card" data-feature="peminjaman" role="button" tabindex="0">
              <div class="feature-card-top">
                <div class="feature-icon"><i class="fa fa-book"></i></div>
                <span class="feature-pill-count"><?php echo number_format($cs['total_peminjaman'], 0, ',', '.'); ?> Pinjaman</span>
              </div>
              <h5>Peminjaman &amp; Retensi</h5>
              <p>Pengajuan pinjam arsip dengan konfirmasi Admin/Super Admin, notifikasi lonceng, dan kontrol masa aktif/inaktif.</p>
              <div class="feature-mini-bar" title="Dipinjam <?php echo $pct_pinj; ?>%">
                <span style="width:<?php echo $pct_pinj; ?>%;background:#38bdf8;"></span>
                <span style="width:<?php echo (100 - $pct_pinj); ?>%;background:#fbbf24;"></span>
              </div>
              <div class="feature-cta">
                <span><i class="fa fa-pie-chart"></i> Lihat Grafik &amp; Detail</span>
                <i class="fa fa-arrow-right"></i>
              </div>
            </div>

            <div class="feature-card" data-feature="agenda" role="button" tabindex="0">
              <div class="feature-card-top">
                <div class="feature-icon"><i class="fa fa-bar-chart"></i></div>
                <span class="feature-pill-count"><?php echo number_format($cs['total_jenis'], 0, ',', '.'); ?> Klasifikasi</span>
              </div>
              <h5>Buku Agenda &amp; Statistik</h5>
              <p>Laporan Buku Agenda Tunggal per periode dengan ekspor Excel &amp; Cetak serta grafik statistik arsip di Beranda.</p>
              <div class="feature-mini-bar">
                <span style="width:35%;background:#38bdf8;"></span>
                <span style="width:28%;background:#fbbf24;"></span>
                <span style="width:22%;background:#34d399;"></span>
                <span style="width:15%;background:#a78bfa;"></span>
              </div>
              <div class="feature-cta">
                <span><i class="fa fa-bar-chart"></i> Lihat Grafik &amp; Detail</span>
                <i class="fa fa-arrow-right"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="cover-footer">
    &copy; <?php echo date('Y'); ?> <?php echo html_escape($app_name); ?> &mdash; <?php echo html_escape($instansi); ?>
    <?php if (!empty($pemerintah)) echo ' ('.html_escape($pemerintah).')'; ?>
  </footer>

  <!-- Modal Detail Fitur & Grafik Statistik Live -->
  <div id="featureModal" class="fmodal-backdrop" aria-hidden="true">
    <div class="fmodal-dialog" role="dialog" aria-modal="true" aria-labelledby="fmodalTitle">
      <div class="fmodal-header">
        <div class="fmodal-head-left">
          <div class="fmodal-icon"><i id="fmodalIcon" class="fa fa-envelope-o"></i></div>
          <div>
            <h4 id="fmodalTitle">Detail Fitur</h4>
            <p id="fmodalSubtitle"></p>
          </div>
        </div>
        <button type="button" class="fmodal-close" id="fmodalCloseBtn" aria-label="Tutup">&times;</button>
      </div>
      <div class="fmodal-body">
        <div class="fmodal-live-tag">
          <span class="fmodal-live-dot"></span> Grafik &amp; Statistik Live dari Database
        </div>
        <div id="fmodalStats" class="fmodal-stats"></div>
        <div id="fmodalCharts" class="fmodal-charts"></div>

        <div class="fmodal-section-title"><i class="fa fa-list-ol"></i> Alur Kerja Fitur</div>
        <ul id="fmodalSteps" class="fmodal-steps"></ul>

        <div class="fmodal-section-title"><i class="fa fa-shield"></i> Hak Akses Role Terkait</div>
        <div id="fmodalRoles" class="fmodal-roles"></div>
      </div>
      <div class="fmodal-footer">
        <button type="button" class="btn-fmodal-close" id="fmodalFooterClose">Tutup</button>
        <div class="fmodal-actions">
          <a href="#" id="fmodalSecondaryBtn" class="btn-fmodal-secondary" style="display:none;"></a>
          <a href="<?php echo site_url('login'); ?>" id="fmodalPrimaryBtn" class="btn-fmodal-primary">
            <i class="fa fa-sign-in"></i> <span id="fmodalPrimaryText">Login &amp; Buka Fitur</span> <i class="fa fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    (function() {
      var featureData = <?php echo json_encode($feature_details); ?>;
      var modal = document.getElementById('featureModal');
      var elIcon = document.getElementById('fmodalIcon');
      var elTitle = document.getElementById('fmodalTitle');
      var elSub = document.getElementById('fmodalSubtitle');
      var elStats = document.getElementById('fmodalStats');
      var elCharts = document.getElementById('fmodalCharts');
      var elSteps = document.getElementById('fmodalSteps');
      var elRoles = document.getElementById('fmodalRoles');
      var elPrimary = document.getElementById('fmodalPrimaryBtn');
      var elPrimaryText = document.getElementById('fmodalPrimaryText');
      var elSecondary = document.getElementById('fmodalSecondaryBtn');

      function escHtml(str) {
        return String(str || '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function renderDonutCard(cfg) {
        var items = cfg.items || [];
        var total = 0;
        for (var i = 0; i < items.length; i++) total += (Number(items[i].value) || 0);

        var r = 44;
        var circ = 2 * Math.PI * r;
        var offset = 0;
        var arcs = '';

        if (total > 0) {
          for (var j = 0; j < items.length; j++) {
            var val = Number(items[j].value) || 0;
            if (val <= 0) continue;
            var segLen = (val / total) * circ;
            arcs += '<circle class="donut-seg" cx="60" cy="60" r="' + r + '" fill="none"'
              + ' stroke="' + escHtml(items[j].color) + '" stroke-width="16"'
              + ' stroke-dasharray="0 ' + circ.toFixed(2) + '"'
              + ' data-target-dash="' + segLen.toFixed(2) + ' ' + circ.toFixed(2) + '"'
              + ' stroke-dashoffset="' + (-offset).toFixed(2) + '"'
              + ' transform="rotate(-90 60 60)" />';
            offset += segLen;
          }
        }

        var svg = '<svg class="donut-svg" viewBox="0 0 120 120">'
          + '<circle cx="60" cy="60" r="' + r + '" fill="none" stroke="#e2e8f0" stroke-width="16"/>'
          + arcs
          + '<text x="60" y="57" text-anchor="middle" font-size="20" font-weight="800" fill="#0b3b66">' + total + '</text>'
          + '<text x="60" y="72" text-anchor="middle" font-size="10" font-weight="600" fill="#64748b">' + escHtml(cfg.centerLabel || 'Total') + '</text>'
          + '</svg>';

        var legend = '';
        for (var k = 0; k < items.length; k++) {
          var v = Number(items[k].value) || 0;
          var pct = total > 0 ? Math.round((v / total) * 100) : 0;
          legend += '<div class="donut-legend-row">'
            + '<span class="donut-legend-left"><span class="donut-dot" style="background:' + escHtml(items[k].color) + '"></span>' + escHtml(items[k].label) + '</span>'
            + '<span class="donut-legend-val">' + v + ' <span class="donut-legend-pct">(' + pct + '%)</span></span>'
            + '</div>';
        }

        return '<div class="fmodal-chart-card">'
          + '<div class="fmodal-chart-title"><i class="fa fa-pie-chart"></i> ' + escHtml(cfg.title) + '</div>'
          + '<div class="donut-wrap">' + svg + '<div class="donut-legend">' + legend + '</div></div>'
          + '</div>';
      }

      function renderHBarCard(cfg) {
        var items = cfg.items || [];
        var maxVal = 0;
        for (var i = 0; i < items.length; i++) {
          var v = Number(items[i].value) || 0;
          if (v > maxVal) maxVal = v;
        }
        var rows = '';
        if (items.length === 0) {
          rows = '<div style="font-size:12px;color:#64748b;padding:12px 0;">Belum ada data klasifikasi.</div>';
        } else {
          for (var j = 0; j < items.length; j++) {
            var val = Number(items[j].value) || 0;
            var pct = maxVal > 0 ? Math.round((val / maxVal) * 100) : 0;
            if (val > 0 && pct < 10) pct = 10;
            rows += '<div class="hbar-row">'
              + '<div class="hbar-meta">'
              + '<span class="hbar-name" title="' + escHtml(items[j].label) + '">' + escHtml(items[j].label) + '</span>'
              + '<span class="hbar-val">' + val + ' ' + escHtml(cfg.unit || '') + '</span>'
              + '</div>'
              + '<div class="hbar-track"><div class="hbar-fill" data-target-width="' + pct + '%" style="background:' + escHtml(items[j].color || '#0284c7') + ';"></div></div>'
              + '</div>';
          }
        }
        return '<div class="fmodal-chart-card">'
          + '<div class="fmodal-chart-title"><i class="fa fa-align-left"></i> ' + escHtml(cfg.title) + '</div>'
          + rows
          + '</div>';
      }

      function renderMonthlyCard(cfg) {
        var series = cfg.series || [];
        var maxVal = 1;
        for (var i = 0; i < series.length; i++) {
          var m = Number(series[i].masuk) || 0;
          var k = Number(series[i].keluar) || 0;
          if (m > maxVal) maxVal = m;
          if (k > maxVal) maxVal = k;
        }
        var svgW = 300;
        var chartTop = 18;
        var chartBottom = 104;
        var chartH = chartBottom - chartTop;
        var padL = 22;
        var usableW = svgW - padL - 8;
        var groupW = series.length > 0 ? (usableW / series.length) : 40;
        var barW = Math.min(14, Math.floor((groupW - 10) / 2));

        var grid = '';
        for (var g = 0; g <= 2; g++) {
          var gy = chartBottom - (chartH * (g / 2));
          var gVal = Math.round(maxVal * (g / 2));
          grid += '<line x1="' + padL + '" y1="' + gy.toFixed(1) + '" x2="' + (svgW - 4) + '" y2="' + gy.toFixed(1) + '" stroke="#e2e8f0" stroke-dasharray="' + (g === 0 ? '' : '3,3') + '" stroke-width="1"/>';
          grid += '<text x="' + (padL - 5) + '" y="' + (gy + 3).toFixed(1) + '" text-anchor="end" font-size="9" fill="#94a3b8">' + gVal + '</text>';
        }

        var bars = '';
        for (var s = 0; s < series.length; s++) {
          var item = series[s];
          var mv = Number(item.masuk) || 0;
          var kv = Number(item.keluar) || 0;
          var mh = mv > 0 ? Math.max(4, (mv / maxVal) * chartH) : 2;
          var kh = kv > 0 ? Math.max(4, (kv / maxVal) * chartH) : 2;
          var gx = padL + s * groupW + (groupW - (barW * 2 + 4)) / 2;
          var my = chartBottom - mh;
          var ky = chartBottom - kh;

          bars += '<rect x="' + gx.toFixed(1) + '" y="' + my.toFixed(1) + '" width="' + barW + '" height="' + mh.toFixed(1) + '" rx="3" fill="' + (mv > 0 ? '#0284c7' : '#cbd5e1') + '"/>';
          if (mv > 0) {
            bars += '<text x="' + (gx + barW / 2).toFixed(1) + '" y="' + (my - 3).toFixed(1) + '" text-anchor="middle" font-size="9" font-weight="700" fill="#0284c7">' + mv + '</text>';
          }
          bars += '<rect x="' + (gx + barW + 4).toFixed(1) + '" y="' + ky.toFixed(1) + '" width="' + barW + '" height="' + kh.toFixed(1) + '" rx="3" fill="' + (kv > 0 ? '#f59e0b' : '#cbd5e1') + '"/>';
          if (kv > 0) {
            bars += '<text x="' + (gx + barW + 4 + barW / 2).toFixed(1) + '" y="' + (ky - 3).toFixed(1) + '" text-anchor="middle" font-size="9" font-weight="700" fill="#d97706">' + kv + '</text>';
          }
          bars += '<text x="' + (gx + barW + 2).toFixed(1) + '" y="120" text-anchor="middle" font-size="9" font-weight="600" fill="#475569">' + escHtml(item.label) + '</text>';
        }

        return '<div class="fmodal-chart-card">'
          + '<div class="fmodal-chart-title"><i class="fa fa-bar-chart"></i> ' + escHtml(cfg.title) + '</div>'
          + '<div class="mchart-legend">'
          + '<span><span class="donut-dot" style="background:#0284c7;"></span>Surat Masuk (M)</span>'
          + '<span><span class="donut-dot" style="background:#f59e0b;"></span>Surat Keluar (K)</span>'
          + '</div>'
          + '<svg class="mchart-svg" viewBox="0 0 ' + svgW + ' 128">' + grid + bars + '</svg>'
          + '</div>';
      }

      function renderChartBox(cfg) {
        if (!cfg) return '';
        if (cfg.type === 'donut') return renderDonutCard(cfg);
        if (cfg.type === 'monthly') return renderMonthlyCard(cfg);
        return renderHBarCard(cfg);
      }

      function animateModalCharts() {
        var fills = elCharts.querySelectorAll('.hbar-fill[data-target-width]');
        for (var i = 0; i < fills.length; i++) {
          fills[i].style.width = fills[i].getAttribute('data-target-width');
        }
        var segs = elCharts.querySelectorAll('.donut-seg[data-target-dash]');
        for (var j = 0; j < segs.length; j++) {
          segs[j].setAttribute('stroke-dasharray', segs[j].getAttribute('data-target-dash'));
        }
      }

      function openFeatureModal(key) {
        var f = featureData[key];
        if (!f) return;

        elIcon.className = 'fa ' + f.icon;
        elTitle.textContent = f.title;
        elSub.textContent = f.subtitle;

        var statsHtml = '';
        for (var i = 0; i < f.stats.length; i++) {
          var s = f.stats[i];
          statsHtml += '<div class="fmodal-stat-box">'
            + '<div class="fmodal-stat-val">' + escHtml(s.value) + '</div>'
            + '<div class="fmodal-stat-lbl">' + escHtml(s.label) + '</div>'
            + '<div class="fmodal-stat-sub">' + escHtml(s.sub) + '</div>'
            + '</div>';
        }
        elStats.innerHTML = statsHtml;

        elCharts.innerHTML = renderChartBox(f.chartLeft) + renderChartBox(f.chartRight);

        var stepsHtml = '';
        for (var j = 0; j < f.steps.length; j++) {
          stepsHtml += '<li><span class="fmodal-step-num">' + (j + 1) + '</span><span>' + escHtml(f.steps[j]) + '</span></li>';
        }
        elSteps.innerHTML = stepsHtml;

        var rolesHtml = '';
        for (var k = 0; k < f.roles.length; k++) {
          rolesHtml += '<span class="fmodal-role-pill"><i class="fa fa-user"></i> ' + escHtml(f.roles[k]) + '</span>';
        }
        elRoles.innerHTML = rolesHtml;

        if (f.primary) {
          elPrimary.href = f.primary.url;
          elPrimaryText.textContent = f.primary.label;
          elPrimary.style.display = 'inline-flex';
        } else {
          elPrimary.style.display = 'none';
        }

        if (f.secondary) {
          elSecondary.href = f.secondary.url;
          elSecondary.textContent = f.secondary.label;
          elSecondary.style.display = 'inline-block';
        } else {
          elSecondary.style.display = 'none';
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        setTimeout(animateModalCharts, 40);
      }

      function closeFeatureModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }

      var cards = document.querySelectorAll('.feature-card[data-feature]');
      for (var c = 0; c < cards.length; c++) {
        (function(card) {
          card.addEventListener('click', function() {
            openFeatureModal(card.getAttribute('data-feature'));
          });
          card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              openFeatureModal(card.getAttribute('data-feature'));
            }
          });
        })(cards[c]);
      }

      document.getElementById('fmodalCloseBtn').addEventListener('click', closeFeatureModal);
      document.getElementById('fmodalFooterClose').addEventListener('click', closeFeatureModal);
      modal.addEventListener('click', function(e) {
        if (e.target === modal) closeFeatureModal();
      });
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) {
          closeFeatureModal();
        }
      });
    })();
  </script>
</body>
</html>
