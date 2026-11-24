<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-yellow">
      <span class="info-box-icon"><i class="fa fa-bell-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Menunggu Konfirmasi</span>
        <span class="info-box-number"><?php echo (int) $stat['Menunggu']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-blue">
      <span class="info-box-icon"><i class="fa fa-book"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Sedang Dipinjam</span>
        <span class="info-box-number"><?php echo (int) $stat['Dipinjam']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-red">
      <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Terlambat (Telat)</span>
        <span class="info-box-number"><?php echo (int) $stat['Telat']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-green">
      <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Dikembalikan</span>
        <span class="info-box-number"><?php echo (int) $stat['Dikembalikan']; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="row">
      <div class="col-md-5" style="margin-bottom:8px;">
        <?php if (!empty($can_add)) { ?>
          <a href="<?php echo site_url('filing/peminjaman/form'); ?>" class="btn btn-primary btn-flat">
            <i class="fa fa-plus"></i> Ajukan Peminjaman Arsip
          </a>
        <?php } ?>
      </div>
      <div class="col-md-7">
        <form method="get" action="<?php echo site_url('filing/peminjaman'); ?>" class="form-inline pull-right">
          <select name="status" class="form-control input-sm">
            <option value="">-- Semua Status --</option>
            <?php foreach (array('Menunggu','Dipinjam','Telat','Dikembalikan','Ditolak') as $st) { ?>
              <option value="<?php echo $st; ?>" <?php echo ($filter_status == $st) ? 'selected' : ''; ?>><?php echo $st; ?></option>
            <?php } ?>
          </select>
          <input type="text" name="q" value="<?php echo html_escape($filter_q); ?>" class="form-control input-sm" placeholder="Cari peminjam / surat / keperluan...">
          <button type="submit" class="btn btn-info btn-sm btn-flat"><i class="fa fa-search"></i> Filter</button>
          <a href="<?php echo site_url('filing/peminjaman'); ?>" class="btn btn-default btn-sm btn-flat">Reset</a>
        </form>
      </div>
    </div>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr class="bg-gray-light">
          <th width="40">No</th>
          <th>Arsip / Surat</th>
          <th>Peminjam &amp; Bagian</th>
          <th>Keperluan</th>
          <th>Tgl Pinjam / Batas Kembali</th>
          <th>Status</th>
          <th>Verifikasi &amp; Kontrol</th>
          <th width="160" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)) {
          $no = 1;
          foreach ($rows as $r) {
            $cls = 'label-default';
            if ($r->status_pinjam == 'Menunggu') $cls = 'label-warning';
            elseif ($r->status_pinjam == 'Dipinjam') $cls = 'label-info';
            elseif ($r->status_pinjam == 'Telat') $cls = 'label-danger';
            elseif ($r->status_pinjam == 'Dikembalikan') $cls = 'label-success';
        ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td>
            <span class="label <?php echo ($r->jenis_surat == 'K') ? 'label-warning' : 'label-info'; ?>"><?php echo ($r->jenis_surat == 'K') ? 'K' : 'M'; ?></span>
            <strong><?php echo html_escape($r->no_surat); ?></strong><br>
            <?php echo html_escape($r->judul); ?>
          </td>
          <td>
            <strong><?php echo html_escape($r->nama_peminjam); ?></strong>
            <?php if (!empty($r->unit_peminjam)) echo '<br><small class="text-muted">'.html_escape($r->unit_peminjam).'</small>'; ?>
          </td>
          <td><?php echo nl2br(html_escape($r->keperluan)); ?></td>
          <td style="white-space:nowrap;">
            Pinjam: <b><?php echo tanggal($r->tgl_pinjam); ?></b><br>
            Batas: <b><?php echo tanggal($r->tgl_kembali); ?></b> (<?php echo (int)$r->lama_pinjam; ?> hr)
            <?php if (!empty($r->tgl_dikembalikan)) echo '<br><span class="text-green">Kembali: '.tanggal($r->tgl_dikembalikan).'</span>'; ?>
          </td>
          <td><span class="label <?php echo $cls; ?>"><?php echo html_escape($r->status_pinjam); ?></span></td>
          <td>
            <?php if (!empty($r->nama_verifikator)) { ?>
              <small>Admin: <b><?php echo html_escape($r->nama_verifikator); ?></b></small><br>
              <?php if (!empty($r->catatan_verifikasi)) echo '<small class="text-muted">'.html_escape($r->catatan_verifikasi).'</small><br>'; ?>
              <?php if ((int)$r->divalidasi_superadmin === 1) { ?>
                <span class="label label-success"><i class="fa fa-shield"></i> Terkontrol Super Admin</span>
              <?php } else { ?>
                <span class="label label-default"><i class="fa fa-clock-o"></i> Belum Validasi Super Admin</span>
              <?php } ?>
            <?php } else { ?>
              <small class="text-muted">Belum diverifikasi</small>
            <?php } ?>
          </td>
          <td class="text-center" style="white-space:nowrap;">
            <?php if ($r->status_pinjam == 'Menunggu' && !empty($can_verify)) { ?>
              <button type="button" class="btn btn-xs btn-success btn-verify"
                data-id="<?php echo $r->id_peminjaman; ?>"
                data-aksi="Dipinjam"
                data-info="<?php echo html_escape($r->nama_peminjam.' - '.$r->no_surat); ?>">
                <i class="fa fa-check"></i> ACC
              </button>
              <button type="button" class="btn btn-xs btn-danger btn-verify"
                data-id="<?php echo $r->id_peminjaman; ?>"
                data-aksi="Ditolak"
                data-info="<?php echo html_escape($r->nama_peminjam.' - '.$r->no_surat); ?>">
                <i class="fa fa-times"></i> Tolak
              </button>
            <?php } ?>
            <?php if (in_array($r->status_pinjam, array('Dipinjam','Telat')) && !empty($can_verify)) { ?>
              <a href="<?php echo site_url('filing/peminjaman/kembalikan/'.$r->id_peminjaman); ?>" class="btn btn-xs btn-primary" onclick="return confirm('Catat arsip sudah dikembalikan?');">
                <i class="fa fa-undo"></i> Kembali
              </a>
            <?php } ?>
            <?php if (!empty($is_superadmin) && in_array($r->status_pinjam, array('Dipinjam','Ditolak','Dikembalikan','Telat')) && (int)$r->divalidasi_superadmin === 0) { ?>
              <a href="<?php echo site_url('filing/peminjaman/validasi_superadmin/'.$r->id_peminjaman); ?>" class="btn btn-xs btn-info" title="Konfirmasi Validasi Super Admin">
                <i class="fa fa-shield"></i> Validasi
              </a>
            <?php } ?>
            <?php if (!empty($can_del)) { ?>
              <a href="<?php echo site_url('filing/peminjaman/delete/'.$r->id_peminjaman); ?>" class="btn btn-xs btn-default text-red" onclick="return confirm('Hapus data peminjaman ini?');" title="Hapus">
                <i class="fa fa-trash"></i>
              </a>
            <?php } ?>
          </td>
        </tr>
        <?php } } else { ?>
        <tr>
          <td colspan="8" class="text-center text-muted" style="padding:24px;">
            Belum ada data peminjaman arsip. Klik <strong>Ajukan Peminjaman Arsip</strong> untuk mencatat peminjaman.
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Verifikasi -->
<div class="modal fade" id="modal-verify" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?php echo site_url('filing/peminjaman/konfirmasi'); ?>">
      <div class="modal-content">
        <div class="modal-header bg-blue">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="v_title">Konfirmasi Peminjaman Arsip</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_peminjaman" id="v_id">
          <input type="hidden" name="aksi" id="v_aksi">
          <div class="form-group">
            <label>Peminjaman</label>
            <input type="text" id="v_info" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Catatan Verifikasi (Opsional)</label>
            <textarea name="catatan_verifikasi" class="form-control" rows="3" placeholder="Tambahkan catatan persetujuan atau alasan penolakan..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-flat" id="v_btn"><i class="fa fa-check"></i> Konfirmasi</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.btn-verify').click(function(){
    var aksi = $(this).data('aksi');
    $('#v_id').val($(this).data('id'));
    $('#v_aksi').val(aksi);
    $('#v_info').val($(this).data('info'));
    $('#v_title').text(aksi === 'Dipinjam' ? 'Setujui (ACC) Peminjaman Arsip' : 'Tolak Pengajuan Peminjaman Arsip');
    $('#v_btn').removeClass('btn-primary btn-success btn-danger').addClass(aksi === 'Dipinjam' ? 'btn-success' : 'btn-danger');
    $('#modal-verify').modal('show');
  });
});
</script>
