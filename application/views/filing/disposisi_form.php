<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-share-square-o"></i> <?php echo html_escape($title); ?></h3>
  </div>
  <form method="post" action="<?php echo site_url('filing/disposisi/save'); ?>">
    <input type="hidden" name="id_disposisi" value="<?php echo !empty($def->id_disposisi) ? (int)$def->id_disposisi : ''; ?>">
    <div class="box-body">
      <div class="row">
        <div class="col-md-7">
          <div class="form-group">
            <label>Pilih Surat / Arsip <span class="text-danger">*</span></label>
            <select name="id_arsip_konten" class="form-control select2-disp" required>
              <option value="">-- Pilih Surat / Arsip --</option>
              <?php foreach ($arsip_list as $a) {
                $sel = ($selected_arsip == $a->id_arsip_konten) ? 'selected' : '';
                $tag = ($a->jenis_surat == 'K') ? '[K]' : '[M]';
                echo '<option value="'.$a->id_arsip_konten.'" '.$sel.'>'.$tag.' '.html_escape($a->no_surat.' - '.$a->judul).' ('.tanggal($a->tanggal).')</option>';
              } ?>
            </select>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal Disposisi <span class="text-danger">*</span></label>
                <input type="text" name="tgl_disposisi" class="form-control tgl-disp" value="<?php echo !empty($def->tgl_disposisi) ? tanggal($def->tgl_disposisi) : date('d/m/Y'); ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>No. Agenda Disposisi</label>
                <input type="text" name="no_agenda" class="form-control" value="<?php echo !empty($def->no_agenda) ? html_escape($def->no_agenda) : ''; ?>" placeholder="Opsional">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Sifat Disposisi</label>
                <select name="sifat" class="form-control">
                  <?php foreach (array('Biasa','Penting','Segera','Rahasia') as $sf) {
                    $sel = (!empty($def->sifat) && $def->sifat == $sf) ? 'selected' : '';
                    echo '<option value="'.$sf.'" '.$sel.'>'.$sf.'</option>';
                  } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Diteruskan ke Unit Organisasi / Bidang</label>
                <select name="tujuan_bidang" class="form-control select2-disp">
                  <option value="">-- Pilih Unit / Bidang --</option>
                  <?php foreach ($bidang_list as $b) {
                    $sel = (!empty($def->tujuan_bidang) && $def->tujuan_bidang == $b->id_bidang) ? 'selected' : '';
                    echo '<option value="'.$b->id_bidang.'" '.$sel.'>'.html_escape($b->nama_bidang).'</option>';
                  } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Diteruskan ke Pegawai / PIC</label>
                <select name="tujuan_pegawai" class="form-control select2-disp">
                  <option value="">-- Pilih Pegawai (Opsional) --</option>
                  <?php foreach ($pegawai_list as $p) {
                    $sel = (!empty($def->tujuan_pegawai) && $def->tujuan_pegawai == $p->id_pegawai) ? 'selected' : '';
                    echo '<option value="'.$p->id_pegawai.'" '.$sel.'>'.html_escape($p->nama).'</option>';
                  } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Tujuan Tambahan / Perwakilan (Opsional)</label>
            <input type="text" name="diteruskan_kepada" class="form-control" value="<?php echo !empty($def->diteruskan_kepada) ? html_escape($def->diteruskan_kepada) : ''; ?>" placeholder="Misal: Kaprodi / Staf Administrasi / Panitia">
          </div>

          <div class="form-group">
            <label>Tembusan (Mengetahui)</label>
            <input type="text" name="tembusan" class="form-control" value="<?php echo !empty($def->tembusan) ? html_escape($def->tembusan) : ''; ?>" placeholder="Misal: Wadir 1, Kepala Kampus (bersifat mengetahui)">
            <small class="text-muted">Pihak yang perlu mengetahui isi surat/disposisi (bukan pelaksana langsung).</small>
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-group">
            <label>Pilihan Cepat Instruksi Disposisi</label>
            <div style="background:#f9fafc;border:1px solid #d2d6de;padding:10px;border-radius:4px;">
              <?php
              $presets = array(
                'Tindak lanjuti / proses sesuai ketentuan',
                'Hadir mewakili pimpinan',
                'Buat surat balasan / tanggapan',
                'Koordinasikan dengan unit terkait',
                'Pelajari dan laporkan hasilnya',
                'Untuk diketahui & diarsipkan'
              );
              foreach ($presets as $pr) { ?>
                <div class="checkbox" style="margin:4px 0;">
                  <label><input type="checkbox" name="instruksi_preset[]" value="<?php echo html_escape($pr); ?>"> <?php echo html_escape($pr); ?></label>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label>Detail Instruksi Disposisi <span class="text-danger">*</span></label>
            <textarea name="instruksi" class="form-control" rows="3" placeholder="Tuliskan instruksi khusus atau lengkapi pilihan di atas..."><?php echo !empty($def->instruksi) ? html_escape($def->instruksi) : ''; ?></textarea>
          </div>

          <div class="form-group">
            <label>Catatan Tambahan</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan pimpinan/pengirim disposisi (opsional)"><?php echo !empty($def->catatan) ? html_escape($def->catatan) : ''; ?></textarea>
          </div>

          <hr>
          <div class="form-group">
            <label>Status Tindak Lanjut (TL)</label>
            <select name="status_tl" class="form-control">
              <?php foreach (array('Menunggu','Diproses','Selesai') as $st) {
                $sel = (!empty($def->status_tl) && $def->status_tl == $st) ? 'selected' : '';
                echo '<option value="'.$st.'" '.$sel.'>'.$st.'</option>';
              } ?>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan Tindak Lanjut (TL)</label>
            <textarea name="tindak_lanjut" class="form-control" rows="2" placeholder="Diisi setelah ada tindak lanjut (opsional)"><?php echo !empty($def->tindak_lanjut) ? html_escape($def->tindak_lanjut) : ''; ?></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="box-footer">
      <a href="<?php echo site_url('filing/disposisi'); ?>" class="btn btn-default btn-flat"><i class="fa fa-arrow-left"></i> Kembali</a>
      <button type="submit" class="btn btn-primary btn-flat pull-right"><i class="fa fa-save"></i> Simpan Disposisi</button>
    </div>
  </form>
</div>

<script>
$(document).ready(function(){
  if ($.fn.select2) $('.select2-disp').select2();
  if ($.fn.datepicker) $('.tgl-disp').datepicker({ format: 'dd/mm/yyyy', autoclose: true });
  $('form').on('submit', function(){
    var $f = $(this);
    if ($f.data('submitted')) return false;
    $f.data('submitted', true);
    $f.find('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
  });
});
</script>
