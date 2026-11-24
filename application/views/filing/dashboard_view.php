<style>
	.direktori { list-style-type: none; margin: 0; padding: 5px; }
	.tabel-direktori { width: 100% }
	.tabel-direktori td { padding: 4px 3px; }
	.tabel-direktori td a { color: #000; }
	.info-box-link { display: block; color: inherit; text-decoration: none !important; }
	.info-box-link:hover .info-box { opacity: 0.92; box-shadow: 0 3px 10px rgba(0,0,0,0.18); }
	.stat-bar-row { margin-bottom: 10px; }
	.stat-bar-label { display:flex; justify-content:space-between; font-size:12.5px; margin-bottom:3px; }
	.dash-grid-5 {
		display: grid;
		grid-template-columns: repeat(5, minmax(0, 1fr));
		gap: 15px;
		margin-bottom: 15px;
	}
	.dash-grid-5 .info-box,
	.dash-grid-5 .small-box {
		margin-bottom: 0;
	}
	.dash-grid-5 .info-box-content {
		padding: 6px 10px;
	}
	.dash-grid-5 .info-box-text {
		font-size: 12.5px;
	}
	.dash-grid-5 .small-box h3 {
		font-size: 32px;
	}
	.dash-grid-5 .small-box p {
		font-size: 13.5px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	@media (max-width: 1199px) {
		.dash-grid-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
	}
	@media (max-width: 767px) {
		.dash-grid-5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	}
	@media (max-width: 480px) {
		.dash-grid-5 { grid-template-columns: 1fr; }
	}
</style>

<!-- Baris 1 (5 Kotak): Ringkasan Surat, Disposisi, Peminjaman & Persetujuan Akun -->
<div class="dash-grid-5">
  <div>
    <a class="info-box-link" href="<?php echo site_url('filing/laporan/agenda?jenis_surat=M'); ?>">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-inbox"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Surat Masuk (M)</span>
          <span class="info-box-number"><?php echo (int) @$jml_surat_masuk; ?></span>
          <span class="progress-description">Lihat di Buku Agenda</span>
        </div>
      </div>
    </a>
  </div>

  <div>
    <a class="info-box-link" href="<?php echo site_url('filing/laporan/agenda?jenis_surat=K'); ?>">
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-paper-plane-o"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Surat Keluar (K)</span>
          <span class="info-box-number"><?php echo (int) @$jml_surat_keluar; ?></span>
          <span class="progress-description">Lihat di Buku Agenda</span>
        </div>
      </div>
    </a>
  </div>

  <div>
    <a class="info-box-link" href="<?php echo site_url('filing/disposisi'); ?>">
      <div class="info-box bg-blue">
        <span class="info-box-icon"><i class="fa fa-share-square-o"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Disposisi</span>
          <span class="info-box-number"><?php echo (int) @$jml_disposisi; ?></span>
          <span class="progress-description"><?php echo (int) @$jml_disposisi_menunggu; ?> menunggu TL</span>
        </div>
      </div>
    </a>
  </div>

  <div>
    <a class="info-box-link" href="<?php echo site_url('filing/peminjaman'); ?>">
      <div class="info-box bg-red">
        <span class="info-box-icon"><i class="fa fa-book"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Peminjaman Arsip</span>
          <span class="info-box-number"><?php echo (int) @$jml_peminjaman; ?></span>
          <span class="progress-description"><?php echo (int) @$jml_peminjaman_menunggu; ?> menunggu ACC</span>
        </div>
      </div>
    </a>
  </div>

  <div>
    <?php if (!empty($is_superadmin)) { ?>
    <a class="info-box-link" href="<?php echo site_url('filing/persetujuan_akun'.((int)@$jml_akun_menunggu > 0 ? '?status=pending' : '')); ?>">
      <div class="info-box bg-purple">
        <span class="info-box-icon"><i class="fa fa-user-plus"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Persetujuan Akun</span>
          <span class="info-box-number"><?php echo (int) @$jml_akun_total; ?></span>
          <span class="progress-description"><?php echo (int) @$jml_akun_menunggu; ?> menunggu ACC</span>
        </div>
      </div>
    </a>
    <?php } else { ?>
    <div class="info-box bg-purple">
      <span class="info-box-icon"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pengguna Aktif</span>
        <span class="info-box-number"><?php echo (int) @$jml_akun_aktif; ?></span>
        <span class="progress-description">Akun aktif di sistem</span>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<!-- Baris 2 (5 Kotak): Statistik Berkas, Retensi & Log Aktivitas -->
<div class="dash-grid-5">
  <div>
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?php echo (int) @$butir_arsip; ?></h3>
        <p>Butir File Arsip</p>
      </div>
      <div class="icon"><i class="fa fa-files-o"></i></div>
      <a href="<?php echo site_url('filing/pengarsipan'); ?>" class="small-box-footer">Kelola Pengarsipan <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div>
    <div class="small-box bg-light-blue">
      <div class="inner">
        <h3><?php echo (int) @$konten_arsip; ?></h3>
        <p>Total Konten Arsip</p>
      </div>
      <div class="icon"><i class="fa fa-list"></i></div>
      <a href="<?php echo site_url('filing/laporan/agenda'); ?>" class="small-box-footer">Buka Buku Agenda <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div>
    <div class="small-box bg-teal">
      <div class="inner">
        <h3><?php echo (int) @$jenis_arsip; ?></h3>
        <p>Jenis / Subjek Arsip</p>
      </div>
      <div class="icon"><i class="fa fa-folder-open-o"></i></div>
      <a href="<?php echo site_url('filing/jenis_arsip'); ?>" class="small-box-footer">Referensi Jenis Arsip <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div>
    <div class="small-box bg-orange">
      <div class="inner">
        <h3><?php echo (int) @$jml_retensi; ?></h3>
        <p>Arsip Inaktif / Retensi</p>
      </div>
      <div class="icon"><i class="fa fa-archive"></i></div>
      <a href="<?php echo site_url('filing/retensi'); ?>" class="small-box-footer">Jadwal Retensi <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div>
    <?php if (!empty($can_audit)) { ?>
    <div class="small-box bg-maroon">
      <div class="inner">
        <h3><?php echo (int) @$jml_audit_log; ?></h3>
        <p>Log Aktivitas (Audit)</p>
      </div>
      <div class="icon"><i class="fa fa-history"></i></div>
      <a href="<?php echo site_url('filing/audit_log'); ?>" class="small-box-footer">Lihat Audit Log <i class="fa fa-arrow-circle-right"></i></a>
    </div>
    <?php } else { ?>
    <div class="small-box bg-maroon">
      <div class="inner">
        <h3><?php echo (int) @$jml_bidang; ?></h3>
        <p>Bidang / Unit Kerja</p>
      </div>
      <div class="icon"><i class="fa fa-university"></i></div>
      <a href="<?php echo site_url('filing/unit'); ?>" class="small-box-footer">Referensi Unit Kerja <i class="fa fa-arrow-circle-right"></i></a>
    </div>
    <?php } ?>
  </div>
</div>

<?php if (!empty($is_superadmin)) { ?>
<!-- Persetujuan Registrasi Akun Baru (Khusus Super Admin) -->
<div class="row">
  <div class="col-md-12">
    <div class="box <?php echo ((int) @$jml_akun_menunggu > 0) ? 'box-warning' : 'box-success'; ?>">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-user-plus <?php echo ((int) @$jml_akun_menunggu > 0) ? 'text-yellow' : 'text-green'; ?>"></i>
          &nbsp; Persetujuan Registrasi Akun Baru (ACC Super Admin)
        </h3>
        <div class="box-tools pull-right" style="top:6px;">
          <span class="label <?php echo ((int) @$jml_akun_menunggu > 0) ? 'label-warning' : 'label-success'; ?>" style="font-size:12px;margin-right:4px;">
            <?php echo (int) @$jml_akun_menunggu; ?> Menunggu ACC
          </span>
          <?php if ((int) @$jml_akun_ditolak > 0) { ?>
          <span class="label label-danger" style="font-size:12px;margin-right:6px;">
            <?php echo (int) @$jml_akun_ditolak; ?> Ditolak
          </span>
          <?php } ?>
          <a href="<?php echo site_url('filing/persetujuan_akun'); ?>" class="btn btn-xs btn-primary btn-flat">
            <i class="fa fa-shield"></i> Kelola Semua Persetujuan
          </a>
        </div>
      </div>
      <div class="box-body <?php echo !empty($pending_accounts) ? 'no-padding table-responsive' : ''; ?>">
        <?php if (!empty($pending_accounts)) { ?>
        <table class="table table-bordered table-condensed table-hover" style="margin-bottom:0;">
          <thead>
            <tr class="bg-gray-light">
              <th width="35" class="text-center">No</th>
              <th>Nama &amp; Email</th>
              <th>NIP / Username</th>
              <th>Unit Kerja &amp; Bidang</th>
              <th>Usulan Role</th>
              <th width="165">Tetapkan Role</th>
              <th width="140" class="text-center">Aksi ACC</th>
            </tr>
          </thead>
          <tbody>
            <?php $no_p = 1; foreach ($pending_accounts as $pa) {
              $sel_role = !empty($pa->reg_role_usulan) ? (int) $pa->reg_role_usulan : 82;
            ?>
            <tr>
              <td class="text-center"><?php echo $no_p++; ?></td>
              <td>
                <strong><?php echo html_escape($pa->nama); ?></strong>
                <?php if (!empty($pa->email)) echo '<br><small class="text-muted">'.html_escape($pa->email).'</small>'; ?>
                <?php if (!empty($pa->reg_created_at)) echo '<br><small class="text-info"><i class="fa fa-clock-o"></i> '.html_escape(date('d/m/Y H:i', strtotime($pa->reg_created_at))).'</small>'; ?>
              </td>
              <td>
                <span class="label label-default">NIP: <?php echo html_escape($pa->nip); ?></span><br>
                <small><b><?php echo html_escape($pa->username); ?></b></small>
              </td>
              <td>
                <?php echo !empty($pa->unit) ? html_escape($pa->unit) : '-'; ?>
                <?php if (!empty($pa->nama_bidang)) echo '<br><small class="text-muted">'.html_escape($pa->nama_bidang).'</small>'; ?>
              </td>
              <td>
                <span class="label label-info"><?php echo html_escape(!empty($pa->role_usulan_nama) ? $pa->role_usulan_nama : 'Operator'); ?></span>
              </td>
              <td>
                <form method="post" action="<?php echo site_url('filing/persetujuan_akun/proses'); ?>" id="dash-acc-form-<?php echo (int) $pa->id_pegawai; ?>" style="margin:0;">
                  <input type="hidden" name="id_pegawai" value="<?php echo (int) $pa->id_pegawai; ?>">
                  <input type="hidden" name="aksi" value="acc">
                  <input type="hidden" name="redirect_to" value="dashboard">
                  <select name="id_role" class="form-control input-sm" required>
                    <?php if (!empty($filing_roles)): foreach ($filing_roles as $fr): ?>
                      <option value="<?php echo (int) $fr->id_role; ?>" <?php echo ((int) $fr->id_role === $sel_role) ? 'selected' : ''; ?>>
                        <?php echo html_escape($fr->nama_role); ?>
                      </option>
                    <?php endforeach; endif; ?>
                  </select>
                </form>
              </td>
              <td class="text-center" style="white-space:nowrap;">
                <button type="submit" form="dash-acc-form-<?php echo (int) $pa->id_pegawai; ?>" class="btn btn-xs btn-success btn-flat" onclick="return confirm('Setujui (ACC) akun <?php echo html_escape(addslashes($pa->nama)); ?> dengan role yang dipilih?');">
                  <i class="fa fa-check"></i> ACC
                </button>
                <button type="button" class="btn btn-xs btn-danger btn-flat btn-dash-tolak"
                  data-id="<?php echo (int) $pa->id_pegawai; ?>"
                  data-nama="<?php echo html_escape($pa->nama); ?>"
                  data-user="<?php echo html_escape($pa->username.' / '.$pa->nip); ?>">
                  <i class="fa fa-times"></i> Tolak
                </button>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } else { ?>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
          <span class="text-muted"><i class="fa fa-check-circle text-green"></i> Tidak ada pendaftaran akun baru yang menunggu persetujuan (ACC) saat ini.</span>
          <a href="<?php echo site_url('filing/persetujuan_akun'); ?>" class="text-blue" style="font-size:12.5px;font-weight:600;">Lihat riwayat persetujuan &amp; daftar role akun &rarr;</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tolak Akun dari Beranda -->
<div class="modal fade" id="modal-dash-tolak-akun" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?php echo site_url('filing/persetujuan_akun/proses'); ?>">
      <div class="modal-content">
        <div class="modal-header bg-red">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-times-circle"></i> Tolak Pendaftaran Akun</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pegawai" id="dash_tolak_id">
          <input type="hidden" name="aksi" value="tolak">
          <input type="hidden" name="redirect_to" value="dashboard">
          <div class="form-group">
            <label>Nama Pengguna</label>
            <input type="text" id="dash_tolak_nama" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Username / NIP</label>
            <input type="text" id="dash_tolak_user" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Alasan Penolakan</label>
            <textarea name="catatan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan pendaftaran..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-times"></i> Tolak Pendaftaran</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
$(document).ready(function(){
  $('.btn-dash-tolak').on('click', function(){
    $('#dash_tolak_id').val($(this).data('id'));
    $('#dash_tolak_nama').val($(this).data('nama'));
    $('#dash_tolak_user').val($(this).data('user'));
    $('#modal-dash-tolak-akun').modal('show');
  });
});
</script>
<?php } ?>

<!-- Baris 3: Grafik Statistik Arsip (Akumulasi Surat Masuk vs Keluar + Komposisi Modul) -->
<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-bar-chart text-blue"></i> &nbsp; Grafik Akumulasi Surat Masuk (M) vs Surat Keluar (K) &mdash; 6 Bulan Terakhir</h3>
      </div>
      <div class="box-body">
        <canvas id="chartSuratBulanan" style="width:100%;height:240px;"></canvas>
        <div class="text-center" style="margin-top:8px;font-size:12px;">
          <span style="display:inline-block;width:12px;height:12px;background:#00c0ef;margin-right:4px;vertical-align:middle;"></span> Surat Masuk (M) &nbsp;&nbsp;
          <span style="display:inline-block;width:12px;height:12px;background:#f39c12;margin-right:4px;vertical-align:middle;"></span> Surat Keluar (K)
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pie-chart text-aqua"></i> &nbsp; Akumulasi Aktivitas Arsip</h3>
      </div>
      <div class="box-body">
        <?php
          $max_val = max(1, (int)@$jml_surat_masuk, (int)@$jml_surat_keluar, (int)@$jml_disposisi, (int)@$jml_peminjaman, (int)@$jml_retensi);
          $bars = array(
            array('label' => 'Surat Masuk (M)', 'val' => (int)@$jml_surat_masuk, 'cls' => 'progress-bar-aqua'),
            array('label' => 'Surat Keluar (K)', 'val' => (int)@$jml_surat_keluar, 'cls' => 'progress-bar-yellow'),
            array('label' => 'Total Disposisi', 'val' => (int)@$jml_disposisi, 'cls' => 'progress-bar-primary'),
            array('label' => 'Total Peminjaman', 'val' => (int)@$jml_peminjaman, 'cls' => 'progress-bar-red'),
            array('label' => 'Arsip Inaktif/Retensi', 'val' => (int)@$jml_retensi, 'cls' => 'progress-bar-green'),
          );
          foreach ($bars as $b) {
            $pct = round(($b['val'] / $max_val) * 100);
        ?>
        <div class="stat-bar-row">
          <div class="stat-bar-label">
            <span><?php echo $b['label']; ?></span>
            <strong><?php echo $b['val']; ?></strong>
          </div>
          <div class="progress progress-sm active" style="margin-bottom:0;">
            <div class="progress-bar <?php echo $b['cls']; ?>" style="width: <?php echo max($pct, $b['val'] > 0 ? 6 : 0); ?>%"></div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<!-- Baris 4: Statistik Berkas per Jenis Arsip + Aktivitas Disposisi & Peminjaman Terbaru -->
<div class="row">
  <div class="col-lg-7">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><i style="color:#ff0f2c;" class="fa fa-line-chart"></i> &nbsp; Statistik Berkas per Jenis / Subjek Arsip</h3>
      </div>
      <div class="box-body no-padding table-responsive">
        <?php echo $tabel_statistik; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-share-square-o text-blue"></i> &nbsp; Disposisi Terbaru</h3>
        <a href="<?php echo site_url('filing/disposisi'); ?>" class="btn btn-xs btn-default pull-right">Lihat Semua</a>
      </div>
      <div class="box-body no-padding">
        <table class="table table-condensed table-striped">
          <thead>
            <tr><th>Surat</th><th>Tujuan</th><th>Status TL</th></tr>
          </thead>
          <tbody>
            <?php if (!empty($recent_disposisi)) {
              foreach ($recent_disposisi as $rd) {
                $tuj = !empty($rd->nama_bidang) ? $rd->nama_bidang : (!empty($rd->diteruskan_kepada) ? $rd->diteruskan_kepada : '-');
                $cls = ($rd->status_tl == 'Selesai') ? 'label-success' : (($rd->status_tl == 'Diproses') ? 'label-info' : 'label-warning');
            ?>
            <tr>
              <td><b><?php echo html_escape($rd->no_surat); ?></b><br><small><?php echo html_escape(shrot_word((string)$rd->judul, 6)); ?></small></td>
              <td><small><?php echo html_escape($tuj); ?></small></td>
              <td><span class="label <?php echo $cls; ?>"><?php echo html_escape($rd->status_tl); ?></span></td>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="3" class="text-center text-muted" style="padding:12px;">Belum ada data disposisi.</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-book text-red"></i> &nbsp; Peminjaman Arsip Terbaru</h3>
        <a href="<?php echo site_url('filing/peminjaman'); ?>" class="btn btn-xs btn-default pull-right">Lihat Semua</a>
      </div>
      <div class="box-body no-padding">
        <table class="table table-condensed table-striped">
          <thead>
            <tr><th>Peminjam</th><th>Surat</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php if (!empty($recent_peminjaman)) {
              foreach ($recent_peminjaman as $rp) {
                $cls = 'label-default';
                if ($rp->status_pinjam == 'Menunggu') $cls = 'label-warning';
                elseif ($rp->status_pinjam == 'Dipinjam') $cls = 'label-info';
                elseif ($rp->status_pinjam == 'Telat') $cls = 'label-danger';
                elseif ($rp->status_pinjam == 'Dikembalikan') $cls = 'label-success';
            ?>
            <tr>
              <td><b><?php echo html_escape($rp->nama_peminjam); ?></b><br><small class="text-muted"><?php echo html_escape($rp->unit_peminjam); ?></small></td>
              <td><small><?php echo html_escape($rp->no_surat); ?></small></td>
              <td><span class="label <?php echo $cls; ?>"><?php echo html_escape($rp->status_pinjam); ?></span></td>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="3" class="text-center text-muted" style="padding:12px;">Belum ada data peminjaman.</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  var canvas = document.getElementById('chartSuratBulanan');
  if (!canvas || !canvas.getContext) return;
  var labels = <?php echo json_encode(isset($chart_labels) ? $chart_labels : array()); ?>;
  var masuk = <?php echo json_encode(isset($chart_masuk) ? $chart_masuk : array()); ?>;
  var keluar = <?php echo json_encode(isset($chart_keluar) ? $chart_keluar : array()); ?>;

  function drawChart() {
    var dpr = window.devicePixelRatio || 1;
    var w = canvas.parentNode.clientWidth || 500;
    var h = 240;
    canvas.width = w * dpr;
    canvas.height = h * dpr;
    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
    var ctx = canvas.getContext('2d');
    ctx.scale(dpr, dpr);
    ctx.clearRect(0, 0, w, h);

    var padL = 38, padR = 16, padT = 20, padB = 34;
    var plotW = w - padL - padR;
    var plotH = h - padT - padB;

    var maxVal = 4;
    for (var i = 0; i < labels.length; i++) {
      if (masuk[i] > maxVal) maxVal = masuk[i];
      if (keluar[i] > maxVal) maxVal = keluar[i];
    }
    maxVal = Math.ceil(maxVal / 2) * 2;

    // Grid lines
    ctx.font = '11px Arial';
    ctx.fillStyle = '#666';
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 1;
    var steps = 4;
    for (var s = 0; s <= steps; s++) {
      var val = Math.round((maxVal / steps) * s);
      var y = padT + plotH - (plotH * (s / steps));
      ctx.beginPath();
      ctx.moveTo(padL, y);
      ctx.lineTo(w - padR, y);
      ctx.stroke();
      ctx.textAlign = 'right';
      ctx.fillText(val, padL - 6, y + 4);
    }

    // Bars
    var n = labels.length || 1;
    var groupW = plotW / n;
    var barW = Math.min(26, Math.max(10, (groupW - 18) / 2));

    for (var i = 0; i < n; i++) {
      var cx = padL + groupW * i + groupW / 2;
      var hM = (masuk[i] / maxVal) * plotH;
      var hK = (keluar[i] / maxVal) * plotH;

      // Masuk bar
      ctx.fillStyle = '#00c0ef';
      ctx.fillRect(cx - barW - 2, padT + plotH - hM, barW, hM);
      if (masuk[i] > 0) {
        ctx.fillStyle = '#007399';
        ctx.textAlign = 'center';
        ctx.fillText(masuk[i], cx - barW/2 - 2, padT + plotH - hM - 4);
      }

      // Keluar bar
      ctx.fillStyle = '#f39c12';
      ctx.fillRect(cx + 2, padT + plotH - hK, barW, hK);
      if (keluar[i] > 0) {
        ctx.fillStyle = '#b76e00';
        ctx.textAlign = 'center';
        ctx.fillText(keluar[i], cx + barW/2 + 2, padT + plotH - hK - 4);
      }

      // Label
      ctx.fillStyle = '#444';
      ctx.textAlign = 'center';
      ctx.fillText(labels[i], cx, h - 10);
    }
  }

  drawChart();
  window.addEventListener('resize', drawChart);
})();
</script>
