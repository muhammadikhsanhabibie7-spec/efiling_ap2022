<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-book"></i> Ajukan Peminjaman Arsip</h3>
  </div>
  <form method="post" action="<?php echo site_url('filing/peminjaman/save'); ?>">
    <div class="box-body">
      <div class="row">
        <div class="col-md-7">
          <div class="form-group">
            <label>Pilih Arsip / Surat yang Dipinjam <span class="text-danger">*</span></label>
            <select name="id_arsip_konten" class="form-control select2-pjm" required>
              <option value="">-- Pilih Arsip / Surat --</option>
              <?php foreach ($arsip_list as $a) {
                $sel = ($selected_arsip == $a->id_arsip_konten) ? 'selected' : '';
                $tag = ($a->jenis_surat == 'K') ? '[K]' : '[M]';
                echo '<option value="'.$a->id_arsip_konten.'" '.$sel.'>'.$tag.' '.html_escape($a->no_surat.' - '.$a->judul).' ('.tanggal($a->tanggal).')</option>';
              } ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Peminjam <span class="text-danger">*</span></label>
                <input type="text" name="nama_peminjam" class="form-control" value="<?php echo html_escape($default_nama); ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Bagian / Divisi / Unit Peminjam</label>
                <input type="text" name="unit_peminjam" class="form-control" value="<?php echo html_escape($default_unit); ?>" placeholder="Contoh: Bagian Akademik / HRD / Keuangan" list="list-bidang-pjm">
                <datalist id="list-bidang-pjm">
                  <?php foreach ($bidang_list as $b) { ?>
                    <option value="<?php echo html_escape($b->nama_bidang); ?>">
                  <?php } ?>
                </datalist>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Keperluan Peminjaman <span class="text-danger">*</span></label>
            <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Dipinjam oleh bagian Keuangan untuk audit / referensi SOP / juknis" required></textarea>
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-group">
            <label>Tanggal Pinjam <span class="text-danger">*</span></label>
            <input type="text" name="tgl_pinjam" class="form-control tgl-pjm" value="<?php echo date('d/m/Y'); ?>" required>
          </div>
          <div class="form-group">
            <label>Lama Pinjam (Hari) <span class="text-danger">*</span></label>
            <input type="number" name="lama_pinjam" class="form-control" min="1" max="90" value="7" required>
            <small class="text-muted">Tanggal batas pengembalian akan dihitung otomatis berdasarkan lama pinjam.</small>
          </div>
          <div class="callout callout-info">
            <h5><i class="fa fa-info-circle"></i> Alur Konfirmasi</h5>
            <p style="font-size:12.5px;margin-bottom:0;">
              Pengajuan peminjaman akan masuk ke notifikasi lonceng dengan status <strong>Menunggu Konfirmasi</strong> untuk diverifikasi oleh Admin dan dipantau oleh Super Admin.
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="box-footer">
      <a href="<?php echo site_url('filing/peminjaman'); ?>" class="btn btn-default btn-flat"><i class="fa fa-arrow-left"></i> Kembali</a>
      <button type="submit" class="btn btn-primary btn-flat pull-right"><i class="fa fa-send"></i> Kirim Pengajuan</button>
    </div>
  </form>
</div>

<script>
$(document).ready(function(){
  if ($.fn.select2) $('.select2-pjm').select2();
  if ($.fn.datepicker) $('.tgl-pjm').datepicker({ format: 'dd/mm/yyyy', autoclose: true });
  $('form').on('submit', function(){
    var $f = $(this);
    if ($f.data('submitted')) return false;
    $f.data('submitted', true);
    $f.find('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');
  });
});
</script>
