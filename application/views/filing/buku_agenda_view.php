<?php
$instansi = !empty($st['instansi']) ? $st['instansi'] : 'POLITEKNIK LP3I JAKARTA';
$pemerintah = !empty($st['pemerintah']) ? $st['pemerintah'] : '';
$qs = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
$jml_m = 0; $jml_k = 0;
foreach ($rows as $rr) { if ($rr->jenis_surat == 'K') $jml_k++; else $jml_m++; }
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-filter"></i> Filter Periode &amp; Jenis Surat</h3>
    <div class="pull-right">
      <a href="<?php echo site_url('filing/laporan/agenda/cetak').$qs; ?>" target="_blank" class="btn btn-info btn-sm btn-flat">
        <i class="fa fa-print"></i> Cetak / Print
      </a>
      <a href="<?php echo site_url('filing/laporan/agenda/excel').$qs; ?>" class="btn btn-success btn-sm btn-flat">
        <i class="fa fa-file-excel-o"></i> Download Excel (.xls)
      </a>
    </div>
  </div>
  <div class="box-body">
    <form method="get" action="<?php echo site_url('filing/laporan/agenda'); ?>">
      <div class="row">
        <div class="col-md-2">
          <label>Dari Tanggal</label>
          <input type="text" name="tgl_mulai" class="form-control input-sm tgl-ag" value="<?php echo html_escape($filter_mulai); ?>" placeholder="dd/mm/yyyy">
        </div>
        <div class="col-md-2">
          <label>Sampai Tanggal</label>
          <input type="text" name="tgl_selesai" class="form-control input-sm tgl-ag" value="<?php echo html_escape($filter_selesai); ?>" placeholder="dd/mm/yyyy">
        </div>
        <div class="col-md-2">
          <label>Masuk / Keluar</label>
          <select name="jenis_surat" class="form-control input-sm">
            <option value="">Semua (M &amp; K)</option>
            <option value="M" <?php echo ($filter_jenis_surat == 'M') ? 'selected' : ''; ?>>M - Surat Masuk</option>
            <option value="K" <?php echo ($filter_jenis_surat == 'K') ? 'selected' : ''; ?>>K - Surat Keluar</option>
          </select>
        </div>
        <div class="col-md-3">
          <label>Klasifikasi / Jenis Arsip</label>
          <select name="id_arsip_jenis" class="form-control input-sm">
            <option value="">-- Semua Jenis Arsip --</option>
            <?php foreach ($jenis_list as $jl) { ?>
              <option value="<?php echo $jl->id_arsip_jenis; ?>" <?php echo ($filter_id_jenis == $jl->id_arsip_jenis) ? 'selected' : ''; ?>>
                <?php echo html_escape($jl->arsip_jenis); ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>Kata Kunci</label>
          <div class="input-group input-group-sm">
            <input type="text" name="q" class="form-control" value="<?php echo html_escape($filter_q); ?>" placeholder="No surat / perihal / instansi...">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Tampilkan</button>
              <a href="<?php echo site_url('filing/laporan/agenda'); ?>" class="btn btn-default btn-flat">Reset</a>
            </span>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="box box-solid">
  <div class="box-body table-responsive">
    <!-- Kop / Judul di Atas Tabel -->
    <div style="text-align:center;border-bottom:2px solid #3c8dbc;padding-bottom:10px;margin-bottom:15px;">
      <h4 style="margin:0;font-weight:700;text-transform:uppercase;"><?php echo html_escape($instansi); ?></h4>
      <?php if ($pemerintah) echo '<div style="font-size:13px;color:#555;">'.html_escape($pemerintah).'</div>'; ?>
      <h4 style="margin:6px 0 2px;font-weight:700;color:#0d055f;"><?php echo html_escape($judul_agenda); ?></h4>
      <div style="font-size:13px;color:#444;">Periode: <strong><?php echo html_escape($periode_label); ?></strong></div>
    </div>

    <div style="margin-bottom:10px;">
      <span class="label label-primary">Total: <?php echo count($rows); ?> Surat</span>
      <span class="label label-info">Surat Masuk (M): <?php echo $jml_m; ?></span>
      <span class="label label-warning">Surat Keluar (K): <?php echo $jml_k; ?></span>
    </div>

    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr class="bg-blue">
          <th width="45" class="text-center">No</th>
          <th>Nomor Surat</th>
          <th width="115">Tanggal Surat</th>
          <th width="110" class="text-center">M / K</th>
          <th>Dari / Kepada</th>
          <th>Perihal</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)) {
          $no = 1;
          foreach ($rows as $r) {
            $mk_label = ($r->jenis_surat == 'K')
              ? '<span class="label label-warning">K (Keluar)</span>'
              : '<span class="label label-info">M (Masuk)</span>';
            $dk = array();
            if (!empty($r->pengirim)) $dk[] = '<b>Dari:</b> '.html_escape($r->pengirim);
            if (!empty($r->penerima)) $dk[] = '<b>Kepada:</b> '.html_escape($r->penerima);
        ?>
        <tr>
          <td class="text-center"><?php echo $no++; ?></td>
          <td><strong><?php echo html_escape($r->no_surat); ?></strong></td>
          <td style="white-space:nowrap;"><?php echo !empty($r->tanggal) ? tanggal($r->tanggal) : '-'; ?></td>
          <td class="text-center"><?php echo $mk_label; ?></td>
          <td><?php echo !empty($dk) ? implode('<br>', $dk) : '-'; ?></td>
          <td>
            <?php echo html_escape($r->judul); ?>
            <?php if (!empty($r->arsip_jenis)) echo '<br><small class="text-muted">Subjek: '.html_escape($r->arsip_jenis).'</small>'; ?>
          </td>
          <td><?php echo !empty($r->keterangan) ? nl2br(html_escape($r->keterangan)) : '-'; ?></td>
        </tr>
        <?php } } else { ?>
        <tr>
          <td colspan="7" class="text-center text-muted" style="padding:24px;">
            Tidak ada data surat pada filter / periode yang dipilih.
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function(){
  if ($.fn.datepicker) $('.tgl-ag').datepicker({ format: 'dd/mm/yyyy', autoclose: true });
});
</script>
