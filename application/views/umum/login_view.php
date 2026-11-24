
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $st['aplikasi_s'].' - '.$st['aplikasi'] ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/admin-lte.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/login.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/plugins/font-awesome/css/font-awesome.min.css' ?>" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <style>
	    #box_message { margin-top: 20px; font-size: 130% }
	    .exlcm { margin-right: 10px; }
	</style>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>
		<script type="text/JavaScript">
        $(document).ready(function(){
	        
	        $(".btn-login").click(function() {
		        $("#login_form").submit();
	        })

	        $("#toggle_pw").on("click", function(){
	          var pw = document.getElementById("password");
	          if (pw.type === "password") { pw.type = "text"; $(this).removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close"); }
	          else { pw.type = "password"; $(this).removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open"); }
	        });
	        
            $('#email').focus();
			$("#login_form").submit(function() {
                if ($("#email").val()=="" || $("#password").val()==""){
                    $("#box_message").html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> &nbsp; Username / NIP atau Password harus diisi!').addClass('alert alert-danger');
                        $("#email").focus();
                    } else {
						$.ajax({
							type: "POST",
							url: $(this).attr('action'),
							data: $(this).serialize(),
							dataType: "json",
							success: function(msg) {
								if (parseInt(msg.sign) == 404) {
									$("#box_message").html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> &nbsp;'+msg.text).addClass('alert alert-danger').show();
									$("#ajaxcaptcha").html(msg.captcha);
									$("#cicaptcha").val("");
								}
								else if (parseInt(msg.sign) == 102)  window.location = '<?php echo site_url() ?>'+msg.aplikasi;
								//else if (parseInt(msg.sign) == 400) $("#box_message").html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> &nbsp; Email atau Password Salah! Silakan Ulangi').addClass('alert alert-danger').show();
								else window.location = '<?php echo site_url() ?>'
							}
						});
			
				}
				return false;
				
			});
		});
        </script>


  </head>
  <?php 
  $ok = $this->session->flashdata('ok');
  $fail = $this->session->flashdata('fail')
  ?>
  
<body class="login-page" <?php if (!empty($st['main_color'])) echo 'style="background: '.$st['main_color'].'"'; ?>>
    <div class="login-wrapper">
    	<div class="login-drop">
	    	<!-- <img src ="<?php echo base_url().'assets/backdrop/backdrop.jpg'; ?>"/> -->
	    </div>
	          
    	<div class="center-block footer">
	    	<div class="login-box login-box-ex">
	 	<!-- login-instansi -->
       			<div class="login-instansi">
					<?php
					$path_inst_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
					$logo_instansi = (!empty($path_inst_logo) and file_exists($path_inst_logo)) ? base_url().'logo/'.$st['pemerintah_logo'] : base_url().'assets/logo/brand.png';
					?>
	        		<div class="logo-brand "><img src="<?php echo $logo_instansi ?>" /></p></div><p class="top-brand">
	        		<?php if (!empty($st['pemerintah'])) echo $st['pemerintah']; ?><br>
	        		<span><?php echo $st['instansi'] ?></span></p>      
      			</div>
      			<div class="login-box-body">
	     			<?php echo form_open('/login/login_process', array('name' => 'login_form ', 'id' => 'login_form', 'autocomplete' => 'off'))?>
					<input type="hidden" name="next_target" value="<?php echo html_escape(!empty($next_target) ? $next_target : ''); ?>">
		 			<div id="box_message"></div>
				 	<?php 
					if (!empty($next_label)) echo '<div style="background:rgba(245,158,11,0.18);border:1px solid rgba(252,211,77,0.55);color:#fef3c7;border-radius:4px;padding:8px 10px;font-size:12px;margin-bottom:12px;"><i class="fa fa-compass"></i> &nbsp;Setelah login, langsung membuka: <strong>'.html_escape($next_label).'</strong></div>';
					if (!empty($ok)) echo '<div class="alert alert-success"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> &nbsp; '.$ok.'</div>';
					if (!empty($fail)) echo '<div class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> &nbsp; '.$fail.'</div>';
					?>
		            <div class="form-group has-feedback">
			           <?php echo form_input('username',$this->session->flashdata('welcome'),'class="form-control" id="email" placeholder="Username atau NIP / ID Pegawai"')?>
		            	<span class="glyphicon glyphicon-user form-control-feedback"></span>
		          	</div>
			        <div class="form-group has-feedback">
			            <?php echo form_password('password',null,'class="form-control" id="password" placeholder="Password" autocomplete="new-password"')?>
			            <span id="toggle_pw" class="glyphicon glyphicon-eye-open form-control-feedback" style="pointer-events:auto;cursor:pointer;color:#555;" title="Lihat / sembunyikan password"></span>
			        </div>
				<!-- IF CAPTCHA -->
					<?php
					// if (!empty($captcha) && $captcha == 1) {
						?>
						  <div class="form-group has-feedback">
									    <label style="color:#ffffff;">Bukan Robot? <span class="asterix" style="color:#ffd166;">*</span></label>

									  <div>
									  	 <div class="input-group input-group-lg">
									        <input id="cicaptcha" name="cicaptcha" type="text" class="form-control" placeholder="Masukkan Kode">
									        <span class="input-group-addon" style="padding:4px 6px;background:#ffffff;"><div id="ajaxcaptcha"><?php echo $cicaptcha_html;?></div></span>
									      </div>
									  	
									  </div>
						 </div>

						<?php
					// }
					 ?>
							        
				<!-- ./CAPTCHA -->

		          	<div class="form-group has-feedback visible-lg visible-md visible-sm">
		              	<button type="submit" class="btn btn-success btn-flat btn-lgn">Login</button>
		            </div>
		          	<div class="row visible-xs">
			         	<div class="col-md-10 col-md-offset-1">
		          		<button type="submit" class="btn btn-success btn-block btn-flat btn-lgn" style="margin-bottom: 20px">Login</button>
			         	</div>
		           	</div>
 					<?php echo form_close()?>
					<div style="margin-top:12px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.22);display:flex;justify-content:space-between;align-items:center;font-size:12.5px;color:#e2e8f0;">
						<a href="<?php echo site_url(); ?>" style="color:#e2e8f0;"><i class="fa fa-home"></i> Beranda</a>
						<span>Belum punya akun? <a href="<?php echo site_url('register'); ?>" style="color:#ffd166;font-weight:700;">Register</a></span>
					</div>
      			</div><!-- /.login-box-body -->
      			<div class="login-logo">
				
					<?php
						if (!empty($st['aplikasi_logo'])) {
							$logo = (file_exists(FCPATH.'logo/'.$st['aplikasi_logo'])) ? base_url().'assets/logo/'.$st['aplikasi_logo'] : base_url().'assets/logo/logo.png';
						} else {
							$logo = base_url().'assets/logo/logo.png';
						}
					?>
	        		<div class="logo-brand"><img src="<?php echo $logo ?>"/></div>
	        		<?php echo $st['aplikasi_s'] ?>
	        		<p style="line-height: 0.3em;margin-top: 6px;"><small><?php echo $st['aplikasi'] ?></small></p>
      			</div><!-- /.login-logo -->
    		</div><!-- /.login-box -->
			<p class="text-center">&copy; <?php echo $st['copyright'].'. '.$st['aplikasi'].'<br>'.$st['instansi'].(!empty($st['pemerintah'])?', '.$st['pemerintah']:null) ?></p>
	    </div>
	</div>
</body>
</html>
<?php /*

     
*/ ?>