<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <a href="<?php echo site_url('filing/persetujuan_akun?status=pending'); ?>" style="color:inherit;text-decoration:none;display:block;">
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Menunggu ACC</span>
          <span class="info-box-number"><?php echo (int) $stat['pending']; ?></span>
          <span class="progress-description">Perlu verifikasi Super Admin</span>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <a href="<?php echo site_url('filing/persetujuan_akun?status=approved'); ?>" style="color:inherit;text-decoration:none;display:block;">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Akun Aktif / ACC</span>
          <span class="info-box-number"><?php echo (int) $stat['approved']; ?></span>
          <span class="progress-description">Dapat login ke sistem</span>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <a href="<?php echo site_url('filing/persetujuan_akun?status=rejected'); ?>" style="color:inherit;text-decoration:none;display:block;">
      <div class="info-box bg-red">
        <span class="info-box-icon"><i class="fa fa-times-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Ditolak</span>
          <span class="info-box-number"><?php echo (int) $stat['rejected']; ?></span>
          <span class="progress-description">Akses login diblokir</span>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <a href="<?php echo site_url('filing/persetujuan_akun'); ?>" style="color:inherit;text-decoration:none;display:block;">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Akun</span>
          <span class="info-box-number"><?php echo (int) $stat['total']; ?></span>
          <span class="progress-description">Seluruh pengguna tercatat</span>
        </div>
      </div>
    </a>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="row">
      <div class="col-md-5" style="margin-bottom:8px;">
        <div class="btn-group">
          <a href="<?php echo site_url('filing/persetujuan_akun'); ?>" class="btn btn-sm btn-flat <?php echo ($filter_status === '') ? 'btn-primary' : 'btn-default'; ?>">Semua (<?php echo (int) $stat['total']; ?>)</a>
          <a href="<?php echo site_url('filing/persetujuan_akun?status=pending'); ?>" class="btn btn-sm btn-flat <?php echo ($filter_status === 'pending') ? 'btn-warning' : 'btn-default'; ?>">Menunggu ACC (<?php echo (int) $stat['pending']; ?>)</a>
          <a href="<?php echo site_url('filing/persetujuan_akun?status=approved'); ?>" class="btn btn-sm btn-flat <?php echo ($filter_status === 'approved') ? 'btn-success' : 'btn-default'; ?>">Aktif (<?php echo (int) $stat['approved']; ?>)</a>
          <a href="<?php echo site_url('filing/persetujuan_akun?status=rejected'); ?>" class="btn btn-sm btn-flat <?php echo ($filter_status === 'rejected') ? 'btn-danger' : 'btn-default'; ?>">Ditolak (<?php echo (int) $stat['rejected']; ?>)</a>
        </div>
      </div>
      <div class="col-md-7">
        <form method="get" action="<?php echo site_url('filing/persetujuan_akun'); ?>" class="form-inline pull-right">
          <?php if ($filter_status !== '') { ?>
            <input type="hidden" name="status" value="<?php echo html_escape($filter_status); ?>">
          <?php } ?>
          <input type="text" name="q" value="<?php echo html_escape($filter_q); ?>" class="form-control input-sm" placeholder="Cari nama / NIP / username / email...">
          <button type="submit" class="btn btn-info btn-sm btn-flat"><i class="fa fa-search"></i> Cari</button>
          <a href="<?php echo site_url('filing/persetujuan_akun'); ?>" class="btn btn-default btn-sm btn-flat">Reset</a>
        </form>
      </div>
    </div>
  </div>

  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr class="bg-gray-light">
          <th width="40" class="text-center">No</th>
          <th>Nama &amp; Email</th>
          <th>NIP / Username</th>
          <th>Unit Kerja &amp; Bidang</th>
          <th>Usulan &amp; Role Aktif</th>
          <th>Status</th>
          <th>Waktu &amp; Verifikasi</th>
          <th width="185" class="text-center">Aksi Super Admin</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)) {
          $no = 1;
          foreach ($rows as $r) {
            $st_int = (int) $r->status;
            if ($st_int === 0) {
              $badge = '<span class="label label-warning"><i class="fa fa-clock-o"></i> Menunggu ACC</span>';
            } elseif ($st_int === 1) {
              $badge = '<span class="label label-success"><i class="fa fa-check"></i> Aktif (ACC)</span>';
            } else {
              $badge = '<span class="label label-danger"><i class="fa fa-times"></i> Ditolak</span>';
            }
            $default_role_id = !empty($r->role_aktif_id) ? (int) $r->role_aktif_id : (!empty($r->reg_role_usulan) ? (int) $r->reg_role_usulan : 82);
        ?>
        <tr class="<?php echo ($st_int === 0) ? 'warning' : ''; ?>">
          <td class="text-center"><?php echo $no++; ?></td>
          <td>
            <strong><?php echo html_escape($r->nama); ?></strong>
            <?php if (!empty($r->email)) { ?>
              <br><small class="text-muted"><i class="fa fa-envelope-o"></i> <?php echo html_escape($r->email); ?></small>
            <?php } ?>
          </td>
          <td>
            <span class="label label-default">NIP: <?php echo html_escape($r->nip); ?></span><br>
            <small>User: <b><?php echo html_escape($r->username); ?></b></small>
          </td>
          <td>
            <?php echo !empty($r->unit) ? html_escape($r->unit) : '<span class="text-muted">-</span>'; ?>
            <?php if (!empty($r->nama_bidang)) { ?>
              <br><small class="text-muted"><?php echo html_escape($r->nama_bidang); ?></small>
            <?php } ?>
          </td>
          <td>
            <?php if (!empty($r->role_usulan_nama)) { ?>
              <small class="text-muted">Usulan: <b><?php echo html_escape($r->role_usulan_nama); ?></b></small><br>
            <?php } ?>
            <?php if (!empty($r->role_aktif_nama)) { ?>
              <span class="label label-primary"><i class="fa fa-shield"></i> <?php echo html_escape($r->role_aktif_nama); ?></span>
            <?php } else { ?>
              <span class="label label-default">Belum ditetapkan</span>
            <?php } ?>
          </td>
          <td><?php echo $badge; ?></td>
          <td>
            <?php if (!empty($r->reg_created_at)) { ?>
              <small>Daftar: <b><?php echo html_escape(date('d/m/Y H:i', strtotime($r->reg_created_at))); ?></b></small><br>
            <?php } ?>
            <?php if (!empty($r->reg_approved_at)) { ?>
              <small class="text-muted">Proses: <?php echo html_escape(date('d/m/Y H:i', strtotime($r->reg_approved_at))); ?>
                <?php if (!empty($r->approver_nama)) echo ' oleh <b>'.html_escape($r->approver_nama).'</b>'; ?>
              </small>
            <?php } elseif ($st_int === 1) { ?>
              <small class="text-muted">Akun bawaan / Operator</small>
            <?php } else { ?>
              <small class="text-muted">Belum diverifikasi</small>
            <?php } ?>
            <?php if (!empty($r->reg_catatan)) { ?>
              <br><small class="text-info"><i>"<?php echo html_escape($r->reg_catatan); ?>"</i></small>
            <?php } ?>
          </td>
          <td class="text-center" style="white-space:nowrap;">
            <?php if ($st_int === 0 || $st_int === 2) { ?>
              <button type="button" class="btn btn-xs btn-success btn-acc-modal"
                data-id="<?php echo (int) $r->id_pegawai; ?>"
                data-nama="<?php echo html_escape($r->nama); ?>"
                data-user="<?php echo html_escape($r->username.' / '.$r->nip); ?>"
                data-role="<?php echo $default_role_id; ?>"
                data-usulan="<?php echo html_escape(!empty($r->role_usulan_nama) ? $r->role_usulan_nama : 'Operator'); ?>">
                <i class="fa fa-check"></i> ACC &amp; Role
              </button>
            <?php } ?>

            <?php if ($st_int === 0) { ?>
              <button type="button" class="btn btn-xs btn-danger btn-tolak-modal"
                data-id="<?php echo (int) $r->id_pegawai; ?>"
                data-nama="<?php echo html_escape($r->nama); ?>"
                data-user="<?php echo html_escape($r->username.' / '.$r->nip); ?>">
                <i class="fa fa-times"></i> Tolak
              </button>
            <?php } ?>

            <?php if ($st_int === 1) { ?>
              <button type="button" class="btn btn-xs btn-info btn-acc-modal"
                data-id="<?php echo (int) $r->id_pegawai; ?>"
                data-nama="<?php echo html_escape($r->nama); ?>"
                data-user="<?php echo html_escape($r->username.' / '.$r->nip); ?>"
                data-role="<?php echo $default_role_id; ?>"
                data-usulan="<?php echo html_escape(!empty($r->role_aktif_nama) ? $r->role_aktif_nama : 'Operator'); ?>"
                title="Ubah Role Akun">
                <i class="fa fa-shield"></i> Ubah Role
              </button>
              <?php if ((int) $r->id_pegawai !== (int) $current_id_pegawai) { ?>
                <button type="button" class="btn btn-xs btn-default text-red btn-tolak-modal"
                  data-id="<?php echo (int) $r->id_pegawai; ?>"
                  data-nama="<?php echo html_escape($r->nama); ?>"
                  data-user="<?php echo html_escape($r->username.' / '.$r->nip); ?>"
                  title="Nonaktifkan / Tolak Akun">
                  <i class="fa fa-ban"></i>
                </button>
              <?php } ?>
            <?php } ?>

            <?php if ($st_int === 0 || $st_int === 2) { ?>
              <a href="<?php echo site_url('filing/persetujuan_akun/hapus/'.$r->id_pegawai); ?>"
                 class="btn btn-xs btn-default text-red"
                 onclick="return confirm('Hapus permanen data pendaftaran akun <?php echo html_escape(addslashes($r->nama)); ?>?');"
                 title="Hapus Permanen">
                <i class="fa fa-trash"></i>
              </a>
            <?php } ?>
          </td>
        </tr>
        <?php } } else { ?>
        <tr>
          <td colspan="8" class="text-center text-muted" style="padding:24px;">
            Tidak ada data registrasi akun untuk filter ini.
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal ACC & Penetapan Role -->
<div class="modal fade" id="modal-acc-akun" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?php echo site_url('filing/persetujuan_akun/proses'); ?>">
      <div class="modal-content">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-check-circle"></i> Setujui (ACC) Akun &amp; Tetapkan Role</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pegawai" id="acc_id">
          <input type="hidden" name="aksi" value="acc">
          <input type="hidden" name="redirect_to" value="list">

          <div class="form-group">
            <label>Nama Pengguna</label>
            <input type="text" id="acc_nama" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Username / NIP</label>
            <input type="text" id="acc_user" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Tetapkan Role / Hak Akses <span class="text-red">*</span></label>
            <select name="id_role" id="acc_role" class="form-control" required>
              <?php if (!empty($filing_roles)): foreach ($filing_roles as $fr): ?>
                <option value="<?php echo (int) $fr->id_role; ?>"><?php echo html_escape($fr->nama_role); ?></option>
              <?php endforeach; endif; ?>
            </select>
            <p class="help-block" style="margin-bottom:0;">Usulan / Role saat ini: <strong id="acc_usulan">-</strong>. Super Admin berwenang menentukan role akhir.</p>
          </div>
          <div class="form-group">
            <label>Catatan Persetujuan (Opsional)</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Misal: Identitas terverifikasi, disetujui sebagai Operator..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Setujui (ACC) &amp; Aktifkan Akun</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Tolak Registrasi -->
<div class="modal fade" id="modal-tolak-akun" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?php echo site_url('filing/persetujuan_akun/proses'); ?>">
      <div class="modal-content">
        <div class="modal-header bg-red">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-times-circle"></i> Tolak Pendaftaran Akun</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pegawai" id="tolak_id">
          <input type="hidden" name="aksi" value="tolak">
          <input type="hidden" name="redirect_to" value="list">

          <div class="form-group">
            <label>Nama Pengguna</label>
            <input type="text" id="tolak_nama" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Username / NIP</label>
            <input type="text" id="tolak_user" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Alasan Penolakan</label>
            <textarea name="catatan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan (misal: NIP tidak sesuai data pegawai / data tidak valid)..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-times"></i> Tolak Akun</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.btn-acc-modal').on('click', function(){
    $('#acc_id').val($(this).data('id'));
    $('#acc_nama').val($(this).data('nama'));
    $('#acc_user').val($(this).data('user'));
    $('#acc_usulan').text($(this).data('usulan'));
    $('#acc_role').val($(this).data('role'));
    $('#modal-acc-akun').modal('show');
  });

  $('.btn-tolak-modal').on('click', function(){
    $('#tolak_id').val($(this).data('id'));
    $('#tolak_nama').val($(this).data('nama'));
    $('#tolak_user').val($(this).data('user'));
    $('#modal-tolak-akun').modal('show');
  });
});
</script>
