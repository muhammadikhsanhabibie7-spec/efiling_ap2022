<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-aqua">
      <span class="info-box-icon"><i class="fa fa-share-square-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Disposisi</span>
        <span class="info-box-number"><?php echo (int) $stat['total']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-yellow">
      <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Menunggu TL</span>
        <span class="info-box-number"><?php echo (int) $stat['Menunggu']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-blue">
      <span class="info-box-icon"><i class="fa fa-spinner"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Sedang Diproses</span>
        <span class="info-box-number"><?php echo (int) $stat['Diproses']; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-green">
      <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Selesai TL</span>
        <span class="info-box-number"><?php echo (int) $stat['Selesai']; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="row">
      <div class="col-md-5" style="margin-bottom:8px;">
        <?php if (!empty($can_add)) { ?>
          <a href="<?php echo site_url('filing/disposisi/form'); ?>" class="btn btn-primary btn-flat">
            <i class="fa fa-plus"></i> Tambah Disposisi
          </a>
        <?php } ?>
      </div>
      <div class="col-md-7">
        <form method="get" action="<?php echo site_url('filing/disposisi'); ?>" class="form-inline pull-right">
          <select name="status" class="form-control input-sm">
            <option value="">-- Semua Status TL --</option>
            <option value="Menunggu" <?php echo ($filter_status == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
            <option value="Diproses" <?php echo ($filter_status == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
            <option value="Selesai" <?php echo ($filter_status == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
          </select>
          <input type="text" name="q" value="<?php echo html_escape($filter_q); ?>" class="form-control input-sm" placeholder="Cari no surat / perihal / instruksi...">
          <button type="submit" class="btn btn-info btn-sm btn-flat"><i class="fa fa-search"></i> Filter</button>
          <a href="<?php echo site_url('filing/disposisi'); ?>" class="btn btn-default btn-sm btn-flat">Reset</a>
        </form>
      </div>
    </div>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr class="bg-gray-light">
          <th width="40">No</th>
          <th>Tgl Disposisi</th>
          <th>Surat / Arsip</th>
          <th>Sifat</th>
          <th>Diteruskan Kepada</th>
          <th>Instruksi Disposisi</th>
          <th>Tembusan (Mengetahui)</th>
          <th>Tindak Lanjut (TL)</th>
          <th width="140" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)) {
          $no = 1;
          foreach ($rows as $r) {
            $tujuan = array();
            if (!empty($r->nama_bidang)) $tujuan[] = '<b>'.html_escape($r->nama_bidang).'</b>';
            if (!empty($r->nama_tujuan)) $tujuan[] = html_escape($r->nama_tujuan);
            if (!empty($r->diteruskan_kepada)) $tujuan[] = html_escape($r->diteruskan_kepada);

            $sifat_cls = 'label-default';
            if ($r->sifat == 'Penting') $sifat_cls = 'label-warning';
            elseif ($r->sifat == 'Segera') $sifat_cls = 'label-danger';
            elseif ($r->sifat == 'Rahasia') $sifat_cls = 'label-primary';

            $tl_cls = 'label-warning';
            if ($r->status_tl == 'Diproses') $tl_cls = 'label-info';
            elseif ($r->status_tl == 'Selesai') $tl_cls = 'label-success';
        ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td style="white-space:nowrap;">
            <?php echo tanggal($r->tgl_disposisi); ?>
            <?php if (!empty($r->no_agenda)) echo '<br><small class="text-muted">Agenda: '.html_escape($r->no_agenda).'</small>'; ?>
          </td>
          <td>
            <span class="label <?php echo ($r->jenis_surat == 'K') ? 'label-warning' : 'label-info'; ?>"><?php echo ($r->jenis_surat == 'K') ? 'K' : 'M'; ?></span>
            <strong><?php echo html_escape($r->no_surat); ?></strong><br>
            <?php echo html_escape($r->judul); ?>
            <?php if (!empty($r->pengirim)) echo '<br><small class="text-muted">Dari: '.html_escape($r->pengirim).'</small>'; ?>
          </td>
          <td><span class="label <?php echo $sifat_cls; ?>"><?php echo html_escape($r->sifat); ?></span></td>
          <td><?php echo !empty($tujuan) ? implode('<br>', $tujuan) : '-'; ?></td>
          <td>
            <?php echo nl2br(html_escape($r->instruksi)); ?>
            <?php if (!empty($r->catatan)) echo '<br><small class="text-muted"><i>Catatan: '.html_escape($r->catatan).'</i></small>'; ?>
          </td>
          <td><?php echo !empty($r->tembusan) ? html_escape($r->tembusan) : '<span class="text-muted">-</span>'; ?></td>
          <td>
            <span class="label <?php echo $tl_cls; ?>"><?php echo html_escape($r->status_tl); ?></span>
            <?php if (!empty($r->tindak_lanjut)) { ?>
              <div style="margin-top:4px;font-size:12px;"><?php echo nl2br(html_escape($r->tindak_lanjut)); ?></div>
            <?php } ?>
            <?php if (!empty($r->tgl_tindak_lanjut)) { ?>
              <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($r->tgl_tindak_lanjut)); ?></small>
            <?php } ?>
          </td>
          <td class="text-center" style="white-space:nowrap;">
            <?php if (!empty($can_tl)) { ?>
              <button type="button" class="btn btn-xs btn-success btn-tl"
                data-id="<?php echo $r->id_disposisi; ?>"
                data-status="<?php echo html_escape($r->status_tl); ?>"
                data-tl="<?php echo html_escape($r->tindak_lanjut); ?>"
                data-surat="<?php echo html_escape($r->no_surat.' - '.$r->judul); ?>"
                title="Update Tindak Lanjut (TL)">
                <i class="fa fa-check-square-o"></i> TL
              </button>
            <?php } ?>
            <a href="<?php echo site_url('filing/disposisi/cetak/'.$r->id_disposisi); ?>" target="_blank" class="btn btn-xs btn-info" title="Cetak Lembar Disposisi">
              <i class="fa fa-print"></i>
            </a>
            <?php if (!empty($can_add)) { ?>
              <a href="<?php echo site_url('filing/disposisi/form/'.$r->id_arsip_konten.'/'.$r->id_disposisi); ?>" class="btn btn-xs btn-warning" title="Edit Disposisi">
                <i class="fa fa-pencil"></i>
              </a>
            <?php } ?>
            <?php if (!empty($can_del)) { ?>
              <a href="<?php echo site_url('filing/disposisi/delete/'.$r->id_disposisi); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus data disposisi ini?');" title="Hapus">
                <i class="fa fa-trash"></i>
              </a>
            <?php } ?>
          </td>
        </tr>
        <?php } } else { ?>
        <tr>
          <td colspan="9" class="text-center text-muted" style="padding:24px;">
            Belum ada data disposisi surat. Klik <strong>Tambah Disposisi</strong> untuk membuat disposisi baru.
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Update Tindak Lanjut (TL) -->
<div class="modal fade" id="modal-tl" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?php echo site_url('filing/disposisi/update_tl'); ?>">
      <div class="modal-content">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Update Tindak Lanjut (TL) Disposisi</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_disposisi" id="tl_id_disposisi">
          <div class="form-group">
            <label>Surat / Arsip</label>
            <input type="text" id="tl_surat" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Status Tindak Lanjut (TL)</label>
            <select name="status_tl" id="tl_status" class="form-control" required>
              <option value="Menunggu">Menunggu</option>
              <option value="Diproses">Diproses</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>
          <div class="form-group">
            <label>Catatan / Laporan Tindak Lanjut (TL)</label>
            <textarea name="tindak_lanjut" id="tl_isi" class="form-control" rows="4" placeholder="Tuliskan hasil tindak lanjut atau perkembangan pelaksanaan disposisi..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Simpan TL</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.btn-tl').click(function(){
    $('#tl_id_disposisi').val($(this).data('id'));
    $('#tl_status').val($(this).data('status'));
    $('#tl_isi').val($(this).data('tl'));
    $('#tl_surat').val($(this).data('surat'));
    $('#modal-tl').modal('show');
  });
});
</script>
