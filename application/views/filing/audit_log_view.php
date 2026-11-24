<?php
$qs_raw = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
$qs = $qs_raw !== '' ? '?'.$qs_raw : '';

if (!function_exists('_audit_badge_class')) {
  function _audit_badge_class($aksi) {
    switch (strtoupper((string) $aksi)) {
      case 'HAPUS': return 'label-danger';
      case 'LOGIN_GAGAL': return 'label-danger';
      case 'REGISTER_GAGAL': return 'label-danger';
      case 'TOLAK': return 'label-danger';
      case 'TAMBAH': return 'label-success';
      case 'REGISTER': return 'label-success';
      case 'TERIMA': return 'label-success';
      case 'VALIDASI': return 'label-success';
      case 'KEMBALI': return 'label-info';
      case 'LOGIN': return 'label-info';
      case 'UBAH': return 'label-warning';
      case 'TINDAK_LANJUT': return 'label-primary';
      case 'PINDAH': return 'label-warning';
      case 'RESET_PW': return 'label-warning';
      case 'LOGOUT': return 'label-default';
      default: return 'label-default';
    }
  }
}

if (!function_exists('_audit_short_ua')) {
  function _audit_short_ua($ua) {
    $ua = (string) $ua;
    if ($ua === '') return '-';
    $browser = 'Browser';
    if (stripos($ua, 'Edg/') !== false || stripos($ua, 'Edge/') !== false) $browser = 'Edge';
    elseif (stripos($ua, 'OPR/') !== false || stripos($ua, 'Opera') !== false) $browser = 'Opera';
    elseif (stripos($ua, 'Chrome/') !== false) $browser = 'Chrome';
    elseif (stripos($ua, 'Firefox/') !== false) $browser = 'Firefox';
    elseif (stripos($ua, 'Safari/') !== false) $browser = 'Safari';

    $os = '';
    if (stripos($ua, 'Windows') !== false) $os = 'Windows';
    elseif (stripos($ua, 'Android') !== false) $os = 'Android';
    elseif (stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false) $os = 'iOS';
    elseif (stripos($ua, 'Mac OS') !== false) $os = 'macOS';
    elseif (stripos($ua, 'Linux') !== false) $os = 'Linux';

    return $os !== '' ? $browser.' / '.$os : $browser;
  }
}

$build_page_url = function($p) use ($filter_mulai, $filter_selesai, $filter_modul, $filter_aksi, $filter_device, $filter_q) {
  $params = array();
  if ($filter_mulai !== '') $params['tgl_mulai'] = $filter_mulai;
  if ($filter_selesai !== '') $params['tgl_selesai'] = $filter_selesai;
  if ($filter_modul !== '') $params['modul'] = $filter_modul;
  if ($filter_aksi !== '') $params['aksi'] = $filter_aksi;
  if ($filter_device !== '') $params['device'] = $filter_device;
  if ($filter_q !== '') $params['q'] = $filter_q;
  $params['page'] = $p;
  return site_url('filing/audit_log').'?'.http_build_query($params);
};
?>

<div class="callout callout-info" style="margin-bottom:15px;">
  <h4 style="margin-top:0;"><i class="fa fa-shield"></i> Pelacakan Cookie Perangkat &amp; Log Permanen (Anti-Hapus)</h4>
  <p style="margin-bottom:4px;">
    Setiap perangkat/browser yang mengakses sistem otomatis ditandai dengan <strong>Cookie Pelacak Perangkat</strong> (<code>efil_track_id</code>, <em>HttpOnly</em> berlaku 1 tahun) dan seluruh aktivitas (tambah, ubah, terima/ACC, tolak, hapus, login, logout) dicatat permanen di database server tanpa tombol hapus.
  </p>
  <p style="margin-bottom:0;font-size:12.5px;">
    Cookie Perangkat Anda saat ini:
    <a href="<?php echo site_url('filing/audit_log?device='.urlencode($current_device_cookie)); ?>" class="label label-primary" style="font-size:12px;" title="Klik untuk melihat semua aktivitas dari perangkat ini">
      <i class="fa fa-laptop"></i> <?php echo html_escape($current_device_cookie); ?>
    </a>
    <span style="margin-left:8px;opacity:0.9;">(Klik kode <code>DEV-...</code> pada baris mana pun untuk melacak semua aksi dari komputer/browser yang sama, meski berganti akun.)</span>
  </p>
</div>

<!-- Ringkasan Statistik -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-history"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Log Tercatat</span>
        <span class="info-box-number"><?php echo number_format($stat['total'], 0, ',', '.'); ?></span>
        <span class="progress-description" style="font-size:11px;color:#666;"><?php echo number_format($stat['perangkat_unik'], 0, ',', '.'); ?> perangkat terdeteksi</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Aktivitas Hari Ini</span>
        <span class="info-box-number"><?php echo number_format($stat['hari_ini'], 0, ',', '.'); ?></span>
        <span class="progress-description" style="font-size:11px;color:#666;"><?php echo date('d/m/Y'); ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-trash"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Aksi Hapus</span>
        <span class="info-box-number"><?php echo number_format($stat['hapus'], 0, ',', '.'); ?></span>
        <span class="progress-description" style="font-size:11px;">
          <a href="<?php echo site_url('filing/audit_log?aksi=HAPUS'); ?>">Filter aksi hapus &rarr;</a>
        </span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-warning"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Percobaan Login Gagal</span>
        <span class="info-box-number"><?php echo number_format($stat['login_gagal'], 0, ',', '.'); ?></span>
        <span class="progress-description" style="font-size:11px;">
          <a href="<?php echo site_url('filing/audit_log?aksi=LOGIN_GAGAL'); ?>">Filter login gagal &rarr;</a>
        </span>
      </div>
    </div>
  </div>
</div>

<!-- Filter -->
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-filter"></i> Filter Jejak Aktivitas</h3>
    <div class="pull-right">
      <a href="<?php echo site_url('filing/audit_log/cetak').$qs; ?>" target="_blank" class="btn btn-info btn-sm btn-flat">
        <i class="fa fa-print"></i> Cetak Laporan Audit
      </a>
      <a href="<?php echo site_url('filing/audit_log/excel').$qs; ?>" class="btn btn-success btn-sm btn-flat">
        <i class="fa fa-file-excel-o"></i> Export Excel (.xls)
      </a>
    </div>
  </div>
  <div class="box-body">
    <form method="get" action="<?php echo site_url('filing/audit_log'); ?>">
      <?php if ($filter_device !== '') { ?>
        <div class="alert alert-warning" style="padding:8px 12px;margin-bottom:10px;">
          <i class="fa fa-laptop"></i> Menampilkan khusus aktivitas dari <strong>Device Cookie: <code><?php echo html_escape($filter_device); ?></code></strong>
          <a href="<?php echo site_url('filing/audit_log'); ?>" class="btn btn-xs btn-default pull-right">Hapus Filter Perangkat</a>
        </div>
        <input type="hidden" name="device" value="<?php echo html_escape($filter_device); ?>">
      <?php } ?>
      <div class="row">
        <div class="col-md-2 col-sm-6">
          <label>Dari Tanggal</label>
          <input type="text" name="tgl_mulai" class="form-control input-sm tgl-audit" value="<?php echo html_escape($filter_mulai); ?>" placeholder="dd/mm/yyyy">
        </div>
        <div class="col-md-2 col-sm-6">
          <label>Sampai Tanggal</label>
          <input type="text" name="tgl_selesai" class="form-control input-sm tgl-audit" value="<?php echo html_escape($filter_selesai); ?>" placeholder="dd/mm/yyyy">
        </div>
        <div class="col-md-2 col-sm-6">
          <label>Modul</label>
          <select name="modul" class="form-control input-sm">
            <option value="">Semua Modul</option>
            <?php foreach ($modul_list as $m) { ?>
              <option value="<?php echo html_escape($m); ?>" <?php echo ($filter_modul === $m) ? 'selected' : ''; ?>><?php echo html_escape($m); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-2 col-sm-6">
          <label>Jenis Aksi</label>
          <select name="aksi" class="form-control input-sm">
            <option value="">Semua Aksi</option>
            <?php foreach ($aksi_list as $ak_key => $ak_lbl) { ?>
              <option value="<?php echo html_escape($ak_key); ?>" <?php echo ($filter_aksi === $ak_key) ? 'selected' : ''; ?>><?php echo html_escape($ak_lbl); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-4 col-sm-12">
          <label>Cari User / Nomor Surat / IP / Cookie / Detail</label>
          <div class="input-group input-group-sm">
            <input type="text" name="q" class="form-control" value="<?php echo html_escape($filter_q); ?>" placeholder="Nama user, username, no surat, IP, DEV-...">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Tampilkan</button>
              <a href="<?php echo site_url('filing/audit_log'); ?>" class="btn btn-default btn-flat">Reset</a>
            </span>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Tabel Audit -->
<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-list-alt"></i> Daftar Rekaman Aktivitas (Read-Only)</h3>
    <div class="pull-right text-muted" style="font-size:12.5px;margin-top:3px;">
      Menampilkan <strong><?php echo count($rows); ?></strong> dari <strong><?php echo number_format($total_filtered, 0, ',', '.'); ?></strong> log (Halaman <?php echo $page; ?> / <?php echo $total_pages; ?>)
    </div>
  </div>
  <div class="box-body table-responsive no-padding">
    <table class="table table-bordered table-striped table-hover" style="font-size:12.5px;">
      <thead>
        <tr class="bg-navy">
          <th width="45" class="text-center">No</th>
          <th width="135">Waktu</th>
          <th width="160">Pengguna &amp; Role</th>
          <th width="105">Modul</th>
          <th width="110" class="text-center">Aksi</th>
          <th>Objek &amp; Detail Perubahan</th>
          <th width="175">Pelacakan Perangkat (Cookie &amp; IP)</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)) {
          $no = $offset + 1;
          foreach ($rows as $r) {
            $badge = _audit_badge_class($r->aksi);
            $ak_text = isset($aksi_list[$r->aksi]) ? $aksi_list[$r->aksi] : $r->aksi;
            $waktu_fmt = !empty($r->waktu) ? date('d/m/Y H:i:s', strtotime($r->waktu)) : '-';
            $ua_short = _audit_short_ua($r->user_agent);
        ?>
        <tr>
          <td class="text-center"><?php echo $no++; ?></td>
          <td style="white-space:nowrap;">
            <strong><?php echo html_escape($waktu_fmt); ?></strong>
          </td>
          <td>
            <strong><?php echo html_escape($r->nama_user); ?></strong><br>
            <small class="text-muted">@<?php echo html_escape($r->username); ?></small>
            <span class="label label-default" style="font-size:10px;"><?php echo html_escape($r->nama_role); ?></span>
          </td>
          <td><span class="label label-primary"><?php echo html_escape($r->modul); ?></span></td>
          <td class="text-center">
            <span class="label <?php echo $badge; ?>"><?php echo html_escape($ak_text); ?></span>
          </td>
          <td>
            <?php if (!empty($r->objek)) { ?>
              <div><strong><?php echo html_escape($r->objek); ?></strong></div>
            <?php } ?>
            <?php if (!empty($r->detail)) { ?>
              <div class="text-muted" style="word-break:break-word;"><?php echo nl2br(html_escape($r->detail)); ?></div>
            <?php } ?>
            <?php if (!empty($r->url_path)) { ?>
              <small style="color:#999;"><code>/<?php echo html_escape($r->url_path); ?></code></small>
            <?php } ?>
          </td>
          <td style="white-space:nowrap;">
            <?php if (!empty($r->device_cookie)) { ?>
              <a href="<?php echo site_url('filing/audit_log?device='.urlencode($r->device_cookie)); ?>" title="Klik untuk melacak semua aktivitas perangkat ini">
                <code style="color:#0d47a1;font-weight:bold;"><?php echo html_escape($r->device_cookie); ?></code>
              </a><br>
            <?php } ?>
            <small><i class="fa fa-globe"></i> IP: <strong><?php echo html_escape($r->ip_address); ?></strong></small><br>
            <small class="text-muted" title="<?php echo html_escape($r->user_agent); ?>">
              <i class="fa fa-desktop"></i> <?php echo html_escape($ua_short); ?>
            </small>
          </td>
        </tr>
        <?php } } else { ?>
        <tr>
          <td colspan="7" class="text-center text-muted" style="padding:28px;">
            <i class="fa fa-info-circle"></i> Belum ada rekaman aktivitas untuk filter yang dipilih.<br>
            <small>Aktivitas akan otomatis tercatat setiap kali pengguna login, menambah, mengubah, memverifikasi, atau menghapus data.</small>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <?php if ($total_pages > 1) { ?>
  <div class="box-footer clearfix">
    <ul class="pagination pagination-sm no-margin pull-right">
      <li class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
        <a href="<?php echo ($page > 1) ? $build_page_url($page - 1) : '#'; ?>">&laquo; Prev</a>
      </li>
      <?php
        $start_p = max(1, $page - 2);
        $end_p = min($total_pages, $page + 2);
        for ($p = $start_p; $p <= $end_p; $p++) {
      ?>
      <li class="<?php echo ($p === $page) ? 'active' : ''; ?>">
        <a href="<?php echo $build_page_url($p); ?>"><?php echo $p; ?></a>
      </li>
      <?php } ?>
      <li class="<?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
        <a href="<?php echo ($page < $total_pages) ? $build_page_url($page + 1) : '#'; ?>">Next &raquo;</a>
      </li>
    </ul>
  </div>
  <?php } ?>
</div>

<script>
$(document).ready(function(){
  if ($.fn.datepicker) $('.tgl-audit').datepicker({ format: 'dd/mm/yyyy', autoclose: true });
});
</script>
