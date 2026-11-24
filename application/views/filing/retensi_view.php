<div class="row">
  <div class="col-md-2 col-sm-4 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner"><h3><?php echo (int)$stat['Aktif']; ?></h3><p>Aktif</p></div>
      <div class="icon"><i class="fa fa-check"></i></div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6">
    <div class="small-box bg-yellow">
      <div class="inner"><h3><?php echo (int)$stat['Inaktif']; ?></h3><p>Inaktif</p></div>
      <div class="icon"><i class="fa fa-clock-o"></i></div>
    </div>
  </div>
  <div class="col-md-3 col-sm-4 col-xs-6">
    <div class="small-box bg-orange">
      <div class="inner"><h3><?php echo (int)$stat['Retensi']; ?></h3><p>Siap Retensi</p></div>
      <div class="icon"><i class="fa fa-archive"></i></div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner"><h3><?php echo (int)$stat['Permanen']; ?></h3><p>Permanen</p></div>
      <div class="icon"><i class="fa fa-lock"></i></div>
    </div>
  </div>
  <div class="col-md-2 col-sm-6 col-xs-12">
    <div class="small-box bg-red">
      <div class="inner"><h3><?php echo (int)$stat['Musnah']; ?></h3><p>Musnah</p></div>
      <div class="icon"><i class="fa fa-trash"></i></div>
    </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-archive"></i> Jadwal Retensi Arsip</h3>
    <form method="get" action="<?php echo site_url('filing/retensi'); ?>" class="form-inline pull-right">
      <select name="status" class="form-control input-sm">
        <option value="">-- Semua Status --</option>
        <?php foreach (array('Aktif','Inaktif','Retensi','Permanen','Musnah') as $st) { ?>
          <option value="<?php echo $st; ?>" <?php echo ($filter_status == $st) ? 'selected' : ''; ?>><?php echo $st; ?></option>
        <?php } ?>
      </select>
      <input type="text" name="q" value="<?php echo html_escape($filter_q); ?>" class="form-control input-sm" placeholder="Cari nomor / perihal...">
      <button type="submit" class="btn btn-info btn-sm btn-flat"><i class="fa fa-search"></i> Filter</button>
      <a href="<?php echo site_url('filing/retensi'); ?>" class="btn btn-default btn-sm btn-flat">Reset</a>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr class="bg-gray-light">
          <th width="40">No</th>
          <th>Nomor &amp; Perihal Surat</th>
          <th>Jenis Arsip</th>
          <th>Tgl Surat</th>
          <th>Masa Aktif</th>
          <th>Tgl Inaktif</th>
          <th>Tgl Retensi</th>
          <th>Status</th>
          <?php if (!empty($can_edit)) { ?><th width="100" class="text-center">Aksi</th><?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)) {
          $no = 1;
          foreach ($rows as $r) {
            $cls = 'label-success';
            if ($r->status_arsip == 'Inaktif') $cls = 'label-warning';
            elseif ($r->status_arsip == 'Retensi') $cls = 'label-danger';
            elseif ($r->status_arsip == 'Permanen') $cls = 'label-info';
            elseif ($r->status_arsip == 'Musnah') $cls = 'label-default';
        ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td>
            <span class="label <?php echo ($r->jenis_surat == 'K') ? 'label-warning' : 'label-info'; ?>"><?php echo ($r->jenis_surat == 'K') ? 'K' : 'M'; ?></span>
            <strong><?php echo html_escape($r->no_surat); ?></strong><br>
            <?php echo html_escape($r->judul); ?>
          </td>
          <td><?php echo html_escape($r->arsip_jenis); ?></td>
          <td style="white-space:nowrap;"><?php echo tanggal($r->tanggal); ?></td>
          <td><?php echo (int)($r->masa_aktif ? $r->masa_aktif : 1); ?> tahun</td>
          <td style="white-space:nowrap;"><?php echo !empty($r->tgl_inaktif) ? tanggal($r->tgl_inaktif) : '-'; ?></td>
          <td style="white-space:nowrap;"><?php echo !empty($r->tgl_retensi) ? tanggal($r->tgl_retensi) : '-'; ?></td>
          <td><span class="label <?php echo $cls; ?>"><?php echo html_escape($r->status_arsip); ?></span></td>
          <?php if (!empty($can_edit)) { ?>
          <td class="text-center">
            <button type="button" class="btn btn-xs btn-warning btn-retensi"
              data-id="<?php echo $r->id_arsip_konten; ?>"
              data-surat="<?php echo html_escape($r->no_surat.' - '.$r->judul); ?>"
              data-status="<?php echo html_escape($r->status_arsip); ?>"
              data-masa="<?php echo (int)($r->masa_aktif ? $r->masa_aktif : 1); ?>"
              data-inaktif="<?php echo !empty($r->tgl_inaktif) ? tanggal($r->tgl_inaktif) : ''; ?>"
              data-retensi="<?php echo !empty($r->tgl_retensi) ? tanggal($r->tgl_retensi) : ''; ?>">
              <i class="fa fa-edit"></i> Ubah
            </button>
          </td>
          <?php } ?>
        </tr>
        <?php } } else { ?>
        <tr><td colspan="9" class="text-center text-muted" style="padding:20px;">Belum ada data arsip.</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<?php if (!empty($logs)) { ?>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-history"></i> Riwayat Perubahan Status Retensi Terbaru</h3>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-condensed table-bordered">
      <thead>
        <tr>
          <th width="140">Waktu</th>
          <th>Surat / Arsip</th>
          <th>Perubahan Status</th>
          <th>Keterangan</th>
          <th>Oleh</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $lg) { ?>
        <tr>
          <td><?php echo date('d/m/Y H:i', strtotime($lg->tgl_perubahan)); ?></td>
          <td><b><?php echo html_escape($lg->no_surat); ?></b> - <?php echo html_escape($lg->judul); ?></td>
          <td><span class="label label-default"><?php echo html_escape($lg->status_lama); ?></span> &rarr; <span class="label label-primary"><?php echo html_escape($lg->status_baru); ?></span></td>
          <td><?php echo html_escape($lg->keterangan); ?></td>
          <td><?php echo html_escape($lg->nama_pegawai ? $lg->nama_pegawai : 'Sistem'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>

<div class="modal fade" id="modal-retensi" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?php echo site_url('filing/retensi/update_status'); ?>">
      <div class="modal-content">
        <div class="modal-header bg-orange">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-archive"></i> Kelola Status &amp; Jadwal Retensi</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_arsip_konten" id="ret_id">
          <div class="form-group">
            <label>Arsip / Surat</label>
            <input type="text" id="ret_surat" class="form-control" readonly>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Status Arsip</label>
                <select name="status_arsip" id="ret_status" class="form-control" required>
                  <option value="Aktif">Aktif</option>
                  <option value="Inaktif">Inaktif</option>
                  <option value="Retensi">Retensi</option>
                  <option value="Permanen">Permanen</option>
                  <option value="Musnah">Musnah</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Masa Aktif (Tahun)</label>
                <input type="number" name="masa_aktif" id="ret_masa" class="form-control" min="1" max="50">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Inaktif (dd/mm/yyyy)</label>
                <input type="text" name="tgl_inaktif" id="ret_inaktif" class="form-control tgl-ret">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Retensi (dd/mm/yyyy)</label>
                <input type="text" name="tgl_retensi" id="ret_retensi" class="form-control tgl-ret">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan / Alasan Perubahan</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan perubahan status retensi..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  if ($.fn.datepicker) $('.tgl-ret').datepicker({ format: 'dd/mm/yyyy', autoclose: true });
  $('.btn-retensi').click(function(){
    $('#ret_id').val($(this).data('id'));
    $('#ret_surat').val($(this).data('surat'));
    $('#ret_status').val($(this).data('status'));
    $('#ret_masa').val($(this).data('masa'));
    $('#ret_inaktif').val($(this).data('inaktif'));
    $('#ret_retensi').val($(this).data('retensi'));
    $('#modal-retensi').modal('show');
  });
});
</script>
