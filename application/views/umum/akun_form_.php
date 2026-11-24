<script type="text/JavaScript"> 
	$(document).ready(function(){
		$('#btn-simpan').click(function() {
			$('#form_akun').submit();
		});
		$("#form_akun").submit(function() {
			$('.loading').show();
			$.ajax({
				type: "POST",
				url: $(this).attr('action'),
				data: $(this).serialize(),
				dataType: "json",
				success: function(msg) {
					if (parseInt(msg.sign) == 404) {
						$("#box_message").html(msg.teks).addClass('alert alert-error').show();
					} else {
						$("#box_form, .btn-batal, .btn-simpan").hide();
						$(".btn-ok").show()
						$("#box_message").html(msg.teks).removeClass('alert alert-error').show();
					}
					$('.loading').hide();
				}
			});
			return false;
			
		});
		
		$('#btn-tutup-profil').click(function() {
			$('#modal-profil').modal('hide');
		});
	});
</script>
<div class="modal-dialog">
    <div class="modal-content">


<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4><i class="fa fa-lock"></i> &nbsp; Akun & Password</h4>
		<div class="clear"></div>
	</div>
	<div class="modal-body"><?php if (!empty($row)) { ?>
			<div class="alert alert-confirm" style="display: none"> <i class="fa fa-refresh fa-spin"></i> Proses ...</div>	
			<div id="box_message" style="display: none"></div>

			<div id="box_form">
		
			<div class="row">

				<?php echo form_open("home/update_akun",'id="form_akun"'); ?>
				<div class="col-lg-6"><?php
					echo form_hidden('id_pegawai',$row->id_pegawai);
					echo form_hidden('username_asli',$row->username);	 ?>
					<p><?php echo form_label('Akun').form_input('username',$row->username,'class="form-control"'); ?></p>
					<p><?php echo form_label('Password Lama').form_password('pwd_lama','','class="form-control"'); ?></p>
					<p><button href="#" class="btn btn-success btn-simpan"><i class="fa fa-user"></i> &nbsp; Ubah Akun</button></p>
				</div>
				<?php echo form_close(); ?>
				<?php echo form_open("home/update_akun",'id="form_akun"'); ?>
				<div class="col-lg-6">
					<?php 
					echo form_hidden('id_pegawai',$row->id_pegawai);
					echo form_hidden('username_asli',$row->username); ?>
					<p><?php echo form_label('Password Lama').form_password('pwd_lama','','class="form-control"'); ?></p>
					<p><?php echo form_label('Password Baru').form_password('pwd_baru','','class="form-control"'); ?></p>
					<p><?php echo form_label('Ulang Pass Baru').form_password('pwd_baru2','','class="form-control"'); ?></p>
					<p><button href="#" class="btn btn-success btn-simpan"><i class="fa fa-lock"></i> &nbsp; Ubah Password</button></p>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
			<?php } else { ?>
			<div class="alert">Anda tidak memiliki kewenangan mengubah akun ...</div>
			<?php } ?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-default text-left" id="btn-tutup-profil"><i class="fa fa-remove"></i> Tutup</a>
	</div>
	
    </div>
</div>