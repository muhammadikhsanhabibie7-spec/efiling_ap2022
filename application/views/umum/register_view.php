<?php
$st = !empty($st) ? $st : get_stationer();
$main_color = !empty($st['main_color']) ? $st['main_color'] : '#0d055f';
$path_inst_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
$logo_instansi = ($path_inst_logo && file_exists($path_inst_logo)) ? base_url().'logo/'.$st['pemerintah_logo'] : base_url().'assets/logo/brand.png';
$app_name = !empty($st['aplikasi']) ? $st['aplikasi'] : 'Elektronik Filing';
$app_short = !empty($st['aplikasi_s']) ? $st['aplikasi_s'] : 'e-Filing';
$instansi = !empty($st['instansi']) ? $st['instansi'] : 'POLITEKNIK LP3I JAKARTA';
$pemerintah = !empty($st['pemerintah']) ? $st['pemerintah'] : 'Kampus Pengembangan';

$bidang_map = array();
if (!empty($bidangs)) {
	foreach ($bidangs as $b) {
		$uid = (int) $b->id_unit;
		if (!isset($bidang_map[$uid])) $bidang_map[$uid] = array();
		$bidang_map[$uid][] = array(
			'id_bidang'   => (int) $b->id_bidang,
			'nama_bidang' => $b->nama_bidang
		);
	}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Akun - <?php echo html_escape($app_short.' | '.$instansi); ?></title>
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
    .reg-nav {
      padding: 16px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(255,255,255,0.12);
      background: rgba(0,0,0,0.14);
    }
    .reg-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      color: #ffffff;
      text-decoration: none !important;
    }
    .reg-brand img {
      height: 44px;
      width: auto;
      background: #fff;
      padding: 4px 6px;
      border-radius: 8px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    .reg-brand-text h4 {
      margin: 0;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.4px;
    }
    .reg-brand-text small {
      color: rgba(255,255,255,0.78);
      font-size: 12px;
    }
    .reg-main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 28px 20px;
    }
    .reg-layout {
      width: 100%;
      max-width: 1080px;
      display: grid;
      grid-template-columns: 420px 1fr;
      gap: 24px;
      align-items: start;
    }
    .reg-guide-col {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .reg-warning-box {
      background: rgba(245, 158, 11, 0.16);
      border: 1px solid rgba(252, 211, 77, 0.55);
      border-left: 4px solid #f59e0b;
      border-radius: 12px;
      padding: 18px 20px;
      backdrop-filter: blur(6px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.22);
    }
    .reg-warning-title {
      display: flex;
      align-items: center;
      gap: 9px;
      margin: 0 0 10px;
      font-size: 15px;
      font-weight: 700;
      color: #fde68a;
      letter-spacing: 0.2px;
    }
    .reg-warning-list {
      margin: 0;
      padding-left: 18px;
      font-size: 12.8px;
      line-height: 1.55;
      color: rgba(255, 255, 255, 0.92);
    }
    .reg-warning-list li {
      margin-bottom: 6px;
    }
    .reg-warning-list li:last-child {
      margin-bottom: 0;
    }
    .reg-warning-list strong {
      color: #fef08a;
    }
    .reg-steps-box {
      background: rgba(255, 255, 255, 0.10);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 12px;
      padding: 18px 20px;
      backdrop-filter: blur(6px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.22);
    }
    .reg-steps-title {
      display: flex;
      align-items: center;
      gap: 9px;
      margin: 0 0 12px;
      font-size: 15px;
      font-weight: 700;
      color: #ffffff;
    }
    .reg-steps-title i {
      color: #fcd34d;
    }
    .reg-steps-list {
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .reg-steps-list li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 12.8px;
      line-height: 1.5;
      color: rgba(255, 255, 255, 0.88);
      margin-bottom: 10px;
    }
    .reg-steps-list li:last-child {
      margin-bottom: 0;
    }
    .reg-step-num {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: #f59e0b;
      color: #0b192c;
      font-size: 11.5px;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .reg-steps-list strong {
      color: #ffffff;
    }
    .reg-steps-list code {
      background: rgba(255, 255, 255, 0.16);
      color: #fde68a;
      padding: 1px 5px;
      border-radius: 4px;
      font-size: 12px;
    }
    .reg-card {
      width: 100%;
      background: #ffffff;
      color: #1e293b;
      border-radius: 12px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.32);
      overflow: hidden;
    }
    .reg-card-header {
      background: #1d3b53;
      color: #ffffff;
      padding: 20px 26px;
      border-bottom: 3px solid #f59e0b;
    }
    .reg-card-header h3 {
      margin: 0 0 4px;
      font-size: 20px;
      font-weight: 700;
    }
    .reg-card-header p {
      margin: 0;
      font-size: 13px;
      color: rgba(255,255,255,0.82);
    }
    .reg-card-body {
      padding: 24px 26px 20px;
    }
    .reg-card-body label {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 5px;
    }
    .reg-card-body .req {
      color: #dc2626;
    }
    .reg-card-body .form-control {
      height: 38px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      box-shadow: none;
      font-size: 13.5px;
    }
    .reg-card-body .form-control:focus {
      border-color: #0b3b66;
      box-shadow: 0 0 0 2px rgba(11,59,102,0.15);
    }
    .role-note {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      color: #075985;
      border-radius: 6px;
      padding: 9px 12px;
      font-size: 12.5px;
      margin-bottom: 16px;
    }
    .captcha-group .input-group-addon {
      padding: 2px 6px;
      background: #ffffff;
      border-color: #cbd5e1;
    }
    .btn-register {
      background: #f59e0b;
      color: #0b192c;
      font-weight: 700;
      font-size: 14.5px;
      padding: 10px 20px;
      border-radius: 7px;
      border: none;
      width: 100%;
      transition: background 0.15s ease;
    }
    .btn-register:hover, .btn-register:focus {
      background: #fbbf24;
      color: #0b192c;
    }
    .btn-register:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }
    .reg-card-footer {
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      padding: 14px 26px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
      color: #475569;
      flex-wrap: wrap;
      gap: 8px;
    }
    .reg-card-footer a {
      font-weight: 600;
      color: #0b3b66;
    }
    .reg-footer {
      text-align: center;
      padding: 14px 20px;
      font-size: 12px;
      color: rgba(255,255,255,0.72);
      border-top: 1px solid rgba(255,255,255,0.12);
      background: rgba(0,0,0,0.14);
    }
    #reg_message {
      display: none;
      margin-bottom: 16px;
      font-size: 13px;
    }
    @media (max-width: 920px) {
      .reg-layout {
        grid-template-columns: 1fr;
        max-width: 640px;
      }
    }
    @media (max-width: 767px) {
      .reg-nav { padding: 12px 16px; }
      .reg-main { padding: 20px 14px; }
      .reg-card-body { padding: 18px 18px 16px; }
      .reg-card-header { padding: 16px 18px; }
      .reg-warning-box, .reg-steps-box { padding: 15px 16px; }
    }
  </style>
</head>
<body>
  <header class="reg-nav">
    <a href="<?php echo site_url(); ?>" class="reg-brand">
      <img src="<?php echo $logo_instansi; ?>" alt="<?php echo html_escape($instansi); ?>">
      <div class="reg-brand-text">
        <h4><?php echo html_escape($instansi); ?></h4>
        <small><?php echo html_escape($pemerintah).' &bull; '.html_escape($app_name); ?></small>
      </div>
    </a>
    <a href="<?php echo site_url('login'); ?>" class="btn btn-sm btn-warning" style="font-weight:600;color:#0b192c;padding:7px 18px;">
      <i class="fa fa-sign-in"></i> Login
    </a>
  </header>

  <main class="reg-main">
    <div class="reg-layout">
      <aside class="reg-guide-col" aria-label="Peringatan dan Tata Cara Registrasi">
        <div class="reg-warning-box">
          <h4 class="reg-warning-title">
            <i class="fa fa-exclamation-triangle"></i> Peringatan Penting
          </h4>
          <ul class="reg-warning-list">
            <li>Isi data menggunakan <strong>identitas asli</strong> (Nama Lengkap, NIP / ID Pegawai, dan Email aktif).</li>
            <li><strong>NIP / ID Pegawai</strong> (3&ndash;18 karakter, tanpa spasi) dan <strong>Username</strong> (3&ndash;50 karakter) bersifat unik serta tidak boleh sama dengan akun lain.</li>
            <li>Jaga kerahasiaan <strong>Password</strong> Anda (minimal 5 karakter) dan jangan bagikan akun kepada pihak lain.</li>
            <li>Untuk mencegah penyalahgunaan/kecurangan, akun baru <strong>tidak langsung aktif</strong> melainkan harus melalui <strong>verifikasi &amp; persetujuan (ACC) Super Admin</strong> terlebih dahulu.</li>
            <li>Penetapan <strong>Role / Hak Akses final</strong> ditentukan oleh <strong>Super Admin</strong> saat proses ACC. Seluruh aktivitas tercatat di <strong>Audit Log</strong> sistem.</li>
          </ul>
        </div>

        <div class="reg-steps-box">
          <h4 class="reg-steps-title">
            <i class="fa fa-list-ol"></i> Tata Cara Registrasi Akun
          </h4>
          <ol class="reg-steps-list">
            <li>
              <span class="reg-step-num">1</span>
              <span><strong>Isi Nama Lengkap &amp; NIP / ID Pegawai</strong><br>Masukkan nama lengkap Anda (min. 3 karakter) serta NIP / ID Pegawai tanpa spasi (contoh: <em>147258369</em>).</span>
            </li>
            <li>
              <span class="reg-step-num">2</span>
              <span><strong>Masukkan Username &amp; Email</strong><br>Isi Username menggunakan nama lengkap Anda (boleh huruf, angka, spasi, <code>.</code>, <code>_</code>, <code>-</code>) dan alamat email yang valid.</span>
            </li>
            <li>
              <span class="reg-step-num">3</span>
              <span><strong>Pilih Unit Kerja, Bidang &amp; Usulan Role</strong><br>Tentukan <em>Unit Kerja</em>, <em>Bidang</em>, serta <em>Role yang Diajukan</em> (penetapan akhir tetap oleh Super Admin).</span>
            </li>
            <li>
              <span class="reg-step-num">4</span>
              <span><strong>Buat &amp; Konfirmasi Password</strong><br>Masukkan password minimal 5 karakter, kemudian ulangi dengan persis pada kolom <em>Konfirmasi Password</em>.</span>
            </li>
            <li>
              <span class="reg-step-num">5</span>
              <span><strong>Isi Kode Keamanan (Captcha)</strong><br>Ketik 5 digit kode pada kolom <em>Bukan Robot?</em> sesuai gambar di sampingnya.</span>
            </li>
            <li>
              <span class="reg-step-num">6</span>
              <span><strong>Kirim &amp; Tunggu ACC Super Admin</strong><br>Tekan <strong>Daftar Sekarang</strong>, lalu tunggu verifikasi &amp; persetujuan (ACC) Super Admin. Setelah disetujui, Anda dapat login menggunakan <strong>Username</strong> atau <strong>NIP / ID Pegawai</strong>.</span>
            </li>
          </ol>
        </div>
      </aside>

      <div class="reg-card">
      <div class="reg-card-header">
        <h3><i class="fa fa-user-plus"></i> Registrasi Akun <?php echo html_escape($app_short); ?></h3>
        <p>Lengkapi data di bawah ini untuk mengajukan pembuatan akun pengguna baru.</p>
      </div>

      <div class="reg-card-body">
        <div class="role-note">
          <i class="fa fa-shield"></i>
          Pendaftaran akun baru harus <strong>disetujui (ACC) oleh Super Admin</strong> terlebih dahulu beserta penetapan <strong>Role</strong> sebelum akun dapat digunakan untuk login.
        </div>

        <div id="reg_message" class="alert"></div>

        <?php echo form_open('register/process', array('id' => 'register_form', 'autocomplete' => 'off')); ?>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_nama">Nama Lengkap <span class="req">*</span></label>
                <input type="text" name="nama" id="reg_nama" class="form-control" maxlength="50" placeholder="Masukkan nama lengkap" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_nip">NIP / ID Pegawai <span class="req">*</span></label>
                <input type="text" name="nip" id="reg_nip" class="form-control" minlength="3" maxlength="18" placeholder="Masukkan NIP / ID (misal: 147258369)" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_username">Username <span class="req">*</span></label>
                <input type="text" name="username" id="reg_username" class="form-control" minlength="3" maxlength="50" placeholder="Masukkan Dengan Nama Lengkap Anda" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_email">Email <span class="req">*</span></label>
                <input type="email" name="email" id="reg_email" class="form-control" maxlength="80" placeholder="nama@email.com" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_unit">Unit Kerja <span class="req">*</span></label>
                <select name="id_unit" id="reg_unit" class="form-control" required>
                  <option value="">-- Pilih Unit Kerja --</option>
                  <?php if (!empty($units)): foreach ($units as $u): ?>
                    <option value="<?php echo (int) $u->id_unit; ?>"><?php echo html_escape($u->unit); ?></option>
                  <?php endforeach; endif; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_bidang">Bidang <span class="req">*</span></label>
                <select name="id_bidang" id="reg_bidang" class="form-control" required>
                  <option value="">-- Pilih Unit Kerja dulu --</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="reg_role_usulan">Role yang Diajukan <span class="req">*</span></label>
            <select name="id_role_usulan" id="reg_role_usulan" class="form-control" required>
              <?php if (!empty($roles)): foreach ($roles as $rl): ?>
                <option value="<?php echo (int) $rl->id_role; ?>" <?php echo (strtolower($rl->nama_role) === 'operator') ? 'selected' : ''; ?>>
                  <?php echo html_escape($rl->nama_role); ?>
                </option>
              <?php endforeach; else: ?>
                <option value="82" selected>Operator</option>
              <?php endif; ?>
            </select>
            <small style="color:#64748b;font-size:11.5px;display:block;margin-top:4px;">
              <i class="fa fa-info-circle"></i> Role final akan diverifikasi dan ditetapkan oleh <strong>Super Admin</strong> saat menyetujui (ACC) akun Anda.
            </small>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_password">Password <span class="req">*</span></label>
                <input type="password" name="password" id="reg_password" class="form-control" minlength="5" placeholder="Minimal 5 karakter" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="reg_password_confirm">Konfirmasi Password <span class="req">*</span></label>
                <input type="password" name="password_confirm" id="reg_password_confirm" class="form-control" minlength="5" placeholder="Ulangi password" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="reg_captcha">Bukan Robot? <span class="req">*</span></label>
            <div class="input-group captcha-group">
              <input type="text" name="cicaptcha" id="reg_captcha" class="form-control" placeholder="Masukkan 5 digit kode di samping" required>
              <span class="input-group-addon">
                <div id="ajaxcaptcha"><?php echo $cicaptcha_html; ?></div>
              </span>
            </div>
          </div>

          <button type="submit" id="btn_register" class="btn btn-register">
            <i class="fa fa-check-circle"></i> Daftar Sekarang
          </button>
        <?php echo form_close(); ?>
      </div>

      <div class="reg-card-footer">
        <a href="<?php echo site_url(); ?>"><i class="fa fa-arrow-left"></i> Kembali ke Beranda</a>
        <span>Sudah punya akun? <a href="<?php echo site_url('login'); ?>">Login di sini</a></span>
      </div>
      </div>
    </div>
  </main>

  <footer class="reg-footer">
    &copy; <?php echo date('Y'); ?> <?php echo html_escape($app_name); ?> &mdash; <?php echo html_escape($instansi); ?>
    <?php if (!empty($pemerintah)) echo ' ('.html_escape($pemerintah).')'; ?>
  </footer>

  <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.min.js'; ?>"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      var bidangMap = <?php echo json_encode($bidang_map); ?>;

      function populateBidang(unitId) {
        var $bidang = $('#reg_bidang');
        $bidang.empty();
        var list = bidangMap[unitId] || [];
        if (!unitId || list.length === 0) {
          $bidang.append('<option value="">-- Pilih Bidang --</option>');
          return;
        }
        $bidang.append('<option value="">-- Pilih Bidang --</option>');
        for (var i = 0; i < list.length; i++) {
          $bidang.append($('<option>').val(list[i].id_bidang).text(list[i].nama_bidang));
        }
      }

      $('#reg_unit').on('change', function() {
        populateBidang($(this).val());
      });

      $('#reg_nama').focus();

      var isSubmitting = false;
      $('#register_form').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return false;

        var $msg = $('#reg_message');
        var $btn = $('#btn_register');

        if ($('#reg_password').val() !== $('#reg_password_confirm').val()) {
          $msg.removeClass('alert-success').addClass('alert-danger')
              .html('<i class="fa fa-exclamation-circle"></i> Konfirmasi password tidak cocok.').show();
          $('#reg_password_confirm').focus();
          return false;
        }

        isSubmitting = true;
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $msg.hide();

        $.ajax({
          type: 'POST',
          url: $(this).attr('action'),
          data: $(this).serialize(),
          dataType: 'json',
          success: function(res) {
            if (res && parseInt(res.sign, 10) === 200) {
              $msg.removeClass('alert-danger').addClass('alert-success')
                  .html('<i class="fa fa-check-circle"></i> ' + res.text).show();
              window.location = res.redirect || '<?php echo site_url('login'); ?>';
            } else {
              isSubmitting = false;
              $btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Daftar Sekarang');
              var errText = (res && res.text) ? res.text : 'Registrasi gagal. Silakan periksa kembali isian Anda.';
              $msg.removeClass('alert-success').addClass('alert-danger')
                  .html('<i class="fa fa-exclamation-circle"></i> ' + errText).show();
              if (res && res.captcha) {
                $('#ajaxcaptcha').html(res.captcha);
                $('#reg_captcha').val('');
              }
            }
          },
          error: function() {
            isSubmitting = false;
            $btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Daftar Sekarang');
            $msg.removeClass('alert-success').addClass('alert-danger')
                .html('<i class="fa fa-exclamation-circle"></i> Gagal menghubungi server. Silakan coba lagi.').show();
          }
        });

        return false;
      });
    });
  </script>
</body>
</html>
