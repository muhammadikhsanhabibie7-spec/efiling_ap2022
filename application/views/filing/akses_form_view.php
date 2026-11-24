<?php 
	echo 
	form_open('filing/akses/save_data','id="form_akses"').
	form_hidden('id_akses',@$def->id_akses).
	form_hidden('s_url',$s_url);
	
	$cb_bid = !empty($cb_bidang)?$cb_bidang:array('' => ' -- Seluruh Unit Organisasi --');
	?>

<p><?php echo form_label('Jenis Arsip').
	form_dropdown('id_arsip_jenis',$cb_arsip,@$def->id_arsip_jenis,'
		class="combo-box form-control" id="arsip_jenis" style="width: 100%"'); ?></p>
<p><?php echo form_label('Unit Kerja').
	form_dropdown('id_unit',$cb_unit,@$def->id_unit,'class="combo-box form-control" id="id_unit" style="width: 100%"'); ?></p>
<p><?php echo form_label('Unit Organisasi').
	form_dropdown('id_bidang',$cb_bid,@$def->id_bidang,'class="combo-box form-control" id="id_bidang" style="width: 100%"'); ?></p>


<br><button class="btn btn-danger btn-md btn-flat btn-form-cancel" type="button"><i class="fa fa-arrow-left"></i> &nbsp; Batal</button>
<button href="#" class="btn btn-success btn-md btn-flat pull-right"><i class="fa fa-save"></i> &nbsp; Simpan</button>
<div class="clear"></div>
<?php echo form_close(); ?>
<script type='text/javascript'>

	$(document).ready(function(){

		$('.combo-box').select2();
		$('.btn-form-cancel').click(function() {
		   $('#form-content,#form-title').html(null);
		   $('#form-box').slideUp();
		   $('#box-main').show();
	   	});
   		$('#id_unit').change(function(){
	   		if ($(this).val()) {
			$.ajax({
				type 	: 'GET', 
				url  	: '<?php echo site_url('filing/akses/cb_bidang') ?>/'+$(this).val(),
				success	: function(data){
					$('#id_bidang').select2('val',null);
					$('#id_bidang').html(data);
				}
			});
			} else {
				$('#id_bidang').select2('val',null);
				$('#id_bidang').html('<option value=""> -- Seluruh Unit Organisasi -- </option>');
			}
		});
		
		$('#form_akses').submit(function() {
			if (!$('#arsip_jenis').val() || !$('#id_unit').val()) {
				show_error('Isian harus diisi lengkap ...')
				return false;
			}
			
		});
	});
	
</script>
