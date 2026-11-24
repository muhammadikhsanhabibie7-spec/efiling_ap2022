<script type='text/javascript'>

	$(document).ready(function(){
		$('.combo-box').select2();
	
	//	$(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
		$('.tgl').datepicker({
						language: 'id',
						format:'dd/mm/yyyy',
						autoclose: true
					});
		
		$("#add_element").click(function(){
			var n = $('.rec-element').length;
			if (n < 5) {
			var thecontent = '<div class="control-group rec-element"><div class="controls"><p><input type="file" name="mFile'+n+'" class="pull-left"><span class="pull-right"><a href="#" title="Hilangkan file upload" class="btn btn-sm btn-warning del-element"><i class="fa fa-minus"></i></a></span></p><div class="clear"></div></div></div>';
			$('#element_fillhere').append(thecontent);
			}
			$('#jumlah').val($('.rec-element').length);
			return false;
		});

		var isSubmittingArsip = false;

		$('#form_arsip').on('submit', function(e) {
			if (isSubmittingArsip) {
				e.preventDefault();
				return false;
			}
			// Arahkan tombol Enter ke alur validasi .btn-simpan
			e.preventDefault();
			$('.btn-simpan').trigger('click');
			return false;
		});

		$('.btn-simpan').click(function(e) {
			e.preventDefault();
			if (isSubmittingArsip) {
				return false;
			}

			var $btn = $(this);
			var noSurat = $.trim($('.no_surat').val());
			var judul = $.trim($('.judul').val());
			var tanggal = $.trim($('.tanggal').val());

			if (noSurat === "" || judul === "" || tanggal === "") {
				$('.form-warning-msg').html('Perihal, Tanggal & Nomor Arsip tidak boleh kosong..!');
				$('#modal-warning').modal('show');
				return false;
			}

			isSubmittingArsip = true;
			$btn.addClass('disabled').css('pointer-events', 'none').html('<i class="fa fa-spinner fa-spin"></i> &nbsp; Menyimpan...');

			$.ajax({
				dataType: "JSON",
				method: "POST",
				url: '<?php echo site_url("filing/pengarsipan/cek_nosurat/");?>',
				data: {nosurat: noSurat, id: $('input[name="id_arsip_konten"]').val()},
				success: function(data){
					if (data.status === 1) {
						isSubmittingArsip = false;
						$btn.removeClass('disabled').css('pointer-events', '').html('<i class="fa fa-save"></i> &nbsp; Simpan');
						$('.form-warning-msg').html('Nomor Surat <b>'+data.nosurat+'</b> sudah digunakan !..');
						$('#modal-warning').modal('show');
					} else {
						$('.panel-btn-simpan').html('<div class="box-body"><div class="overlay">'+
							'<i class="fa fa-refresh fa-spin"></i>'+
							'</div></div>'+
							'<div class="box-footer"><span style="color:red;"><b>Proses upload sedang berlangsung, mohon tidak melakukan aksi apapun.</b></span></div>');
						document.getElementById('form_arsip').submit();
					}
				},
				error: function(){
					isSubmittingArsip = false;
					$btn.removeClass('disabled').css('pointer-events', '').html('<i class="fa fa-save"></i> &nbsp; Simpan');
					$('.form-warning-msg').html('Gagal memeriksa nomor surat. Silakan coba lagi.');
					$('#modal-warning').modal('show');
				}
			});

			return false;
		});
		
		$('.pilih-arsip').change(function() {
			var y = $(this).val();
			if (y) {
				
				$('#load').show();
				  $.ajax({
				      url: '<?php echo site_url('filing/pengarsipan/form_info')?>/'+y,
				      cache: false,
				      success: function(msg) {
				         $('#box-flex').html(msg);
						 $('#load').hide();
						
				      },error:function(error){
						$('#load').hide();
						$('#load-error').html('<i class="fa fa-error"></i> ERROR : '+error).show();
						}
				   });
				
			}
			
		});
		
		<?php if (!empty($def)) { ?> 
	
		$('#load').show();
		  $.ajax({
		      url: '<?php echo site_url('filing/pengarsipan/form_info/'.$def->id_arsip_jenis.'/'.$def->id_arsip_konten)?>',
		      cache: false,
		      success: function(msg) {
		         $('#box-flex').html(msg);
				 $('#load').hide();
				
		      },error:function(error){
				$('#load').hide();
				$('#load-error').html('<i class="fa fa-error"></i> ERROR : '+error).show();
				}
		   });
		
	
	<?php } ?>
		
	});
	
	$(document).on('click','a.del-element',function (e) {
		e.preventDefault();
		$(this).parents('.rec-element').fadeOut(400);
		$(this).parents('.rec-element').remove();
		$('#jumlah').val($('.rec-element').length);
	});
		
	
</script>

<?php echo form_open_multipart('filing/pengarsipan/save_arsip','id="form_arsip"');?>
<?php echo form_hidden('id_arsip_konten',@$def->id_arsip_konten);?>
<?php echo form_hidden('form_token', !empty($form_token) ? $form_token : '');?>
<div class="row">
	<div class="col-lg-7">

<div class="box" id="box-main">


<div class="box-body">
		<p><?php echo 
				form_hidden('arsip_judul',$arsip_judul).
				form_hidden('id_arsip_jenis',$j).
				form_label('Jenis Surat (Masuk / Keluar)').
				form_dropdown('jenis_surat', array('M' => 'M - Surat Masuk', 'K' => 'K - Surat Keluar'), !empty($def->jenis_surat) ? $def->jenis_surat : 'M', 'class="form-control" required'); ?></p>
		<p><?php echo form_label('Nomor Surat / Arsip').form_input('no_surat',@$def->no_surat,'class="form-control no_surat" required') ?></p>
		<p><?php echo form_label('Tanggal Surat / Arsip').form_input('tanggal',!empty($def->tanggal)?tanggal($def->tanggal):date('d/m/Y'),'class="form-control tgl tanggal" required') ?></p>
		<p><?php echo form_label('Perihal / Judul Surat').form_input('judul',@$def->judul,'class="form-control judul" required') ?></p>
		<div class="row">
			<div class="col-md-6">
				<p><?php echo form_label('Dari (Pengirim / Asal Instansi)').form_input('pengirim',@$def->pengirim,'class="form-control" placeholder="Contoh: PT / Instansi / Unit Pengirim"') ?></p>
			</div>
			<div class="col-md-6">
				<p><?php echo form_label('Kepada (Penerima / Tujuan)').form_input('penerima',@$def->penerima,'class="form-control" placeholder="Contoh: Bagian / Jabatan / Instansi Tujuan"') ?></p>
			</div>
		</div>
		<p><?php echo form_label('Keterangan').form_textarea('keterangan',@$def->keterangan,'class="form-control" rows="2" placeholder="Catatan atau keterangan tambahan (opsional)"') ?></p>
		<div class="row">
			<div class="col-md-6">
				<p><?php echo form_label('Masa Aktif Arsip (Tahun)').form_input(array('name'=>'masa_aktif','type'=>'number','min'=>'1','max'=>'50','value'=>!empty($def->masa_aktif)?$def->masa_aktif:1,'class'=>'form-control')) ?></p>
			</div>
			<div class="col-md-6">
				<p><?php echo form_label('Status Arsip').form_dropdown('status_arsip', array('Aktif'=>'Aktif','Inaktif'=>'Inaktif','Retensi'=>'Retensi','Permanen'=>'Permanen','Musnah'=>'Musnah'), !empty($def->status_arsip)?$def->status_arsip:'Aktif', 'class="form-control"') ?></p>
			</div>
		</div>
		<?php echo $kolom_tambahan ?>

		<p> <div class="form-group">
                <label>
                <div class="input-group">
                 
                  <input value="1" name="tipe_arsip_konten" type="checkbox" class="incheck flat-red" <?php echo ((!empty($def->tipe_arsip_konten) && ($def->tipe_arsip_konten == 1)) ? 'checked': null); ?> >
                   &nbsp; Konten Arsip Bebas
                  </div>
                </label>
               
              </div></p>

</div>
</div>

	</div>
	<div class="col-lg-5">
		
	<?php if (!empty($arsip) and $arsip->num_rows() > 0) { ?>
		  <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Arsip</h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-striped">
				  	<?php 
					  	$no = 1;
					  	foreach($arsip->result() as $ars) {
						  
						$del_in = in_de(array(
							'id_arsip_konten' => $def->id_arsip_konten,
							'id_arsip_jenis' => $j,
							'arsip' => $ars->arsip,
							'id_arsip' => $ars->id_arsip,
							'direktori' => $ars->direktori));
						$del_msg = 'Apakah Anda ingin menghapus arsip ini?';
						 
						$del_btn = ($del_role) ? anchor('#','<i class="fa fa-times-circle"></i>','class="btn-delete" style="font-size: 0.9em" act="'.site_url('filing/pengarsipan/delete_berkas_form/'.$del_in).'" msg="'.$del_msg.'"') : null;
						  	
					  	echo '<tr>
					  		<td width="20">'.$no.'</td>
					  		<td>'.anchor(
					  			base_url().'uploads/arsip/'.$ars->direktori.'/'.$ars->arsip,
					  			'<i class="fa fa-file-text-o"></i> &nbsp; '.substr($ars->arsip,0,25),
					  			'class="link-normal" target="_blank" title="'.$ars->arsip.'"').'</td>
					  		<td>'.$del_btn.'</td>
					  	</tr>';
					  	$no+=1;
				  	} ?>
                  </table>
                </div>
		  </div>
		
		
		
	<?php } 
		
	if ($up_role) {	
	?>
		
	<div class="box" id="box-main">
		<div class="box-header with-border">
			<h4 class="title"><i class="fa fa-upload"></i> &nbsp; Unggah Arsip</h4>
		</div>
		<div class="box-body">
		
			<div id="element_fillhere">
				<div class="control-group rec-element">
				  <div class="controls panel-upload">
					<p>
						<input class="userfile" type="file" name="userFiles[]" class="pull-left" required multiple/>
					</p>   
					<div class="clear"></div>
				  </div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="panel panel-btn-simpan" id="box-main">
		<div class="box-header with-border">
		<?php 
			echo anchor('filing/pengarsipan/list_data/'.in_de($j),'<i class="fa fa-arrow-left"></i> Kembali','class="btn btn-default pull-left"'); 
			echo anchor('#','<i class="fa fa-save"></i> &nbsp; Simpan','class="btn btn-simpan btn-success pull-right"'); ?>
			<div class="clear"></div>
		</div>
	</div>


</div> <!-- eof row fluid -->
</div>
</div>
<?php echo form_close();?>
<link href="<?php echo base_url().'assets/plugins/iCheck/all.css';?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/iCheck/icheck.min.js';?>"></script>

<script type="text/javascript">
	$('input[type="checkbox"].incheck').iCheck({
			checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
		});
</script>


<div class="modal fade" id="modal-warning" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	    <div class="modal-header"><button class="close" aria-label="Close" data-dismiss="modal" type="button">
		<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title form-title"><i style="color:red;" class="fa fa-times-circle"></i> &nbsp; Peringatan</h4></div>
        <div class="modal-body"><span class="form-warning-msg"></span></div>
        <div class="modal-footer">
		<button class="btn btn-primary pull-right" data-dismiss="modal" type="button">Ok</button>
		</div>
    </div>
  </div>
</div>