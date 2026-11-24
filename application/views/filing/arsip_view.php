<style>

	.direktori { list-style-type: none; margin: 0; padding: 5px; }
	.tabel-direktori { width: 100% }
	.tabel-direktori td { padding: 4px 3px; }
	.tabel-direktori td a { color: #000; }

	.count-file-label {
		    top: -7px !important;
    		right: -9px !important;
	}

	.bg-folder-child {
		background-color: #ffffff;
		border-color: #a9a9a9;
	}
	.bg-folder-par {
		background-color: #ffffff;
		border-color: #a9a9a9;
	}

	.bg-folder-active {
		background-color: #00a65a;
	    border-color: #367fa9;
	}

	a.btn.btn-xs.bg-folder-par {
		max-height: 22px;
	}
	a.btn.btn-xs.bg-folder-active {
		max-height: 22px;
	}
	a.btn.btn-xs.bg-folder-child {
		max-height: 22px;
	}


</style>
<?php echo @$css_load; ?>

<script type="text/javascript">

	$(document).ready(function(){
		$('.heading').parent().find('td').addClass('headings');
		$('.bg-active').parent().find('td').css({'background':'#eee','color':'#111'});

		$('.cek-arsip-all').click(function() {
			if ($(this).is(':checked')) {
				$('.cek-arsip').prop('checked', true);
				$('.btn-centang-all, #for_pindah').show();
			} else {
				$('.cek-arsip').prop('checked', false);
				$('.btn-centang-all, #for_pindah').hide();
			} 
		});
		
		$('.cek-arsip').click(function(){
			var ju = 0;
			$('.cek-arsip').each(function() { if($(this).is(':checked')) ju+=1; });
			
			if (ju > 0) 
				$('.btn-centang-all, #for_pindah').show();             
			else $('.btn-centang-all, #for_pindah').hide();
		});
		
		$('.btn-centang-all').click(function() {
			
			$('.form-title').html('<i class="fa fa-exchange"></i> &nbsp; Konfirmasi Pindah Konten Arsip');
			$('.form-delete-msg').html('Apakah ingin memindahkan konten arsip yang tercentang?<br>Aksi ini tak dapat dibatalkan!');
			$('.form-delete-url').hide();
			$('.form-delete-btn').html('<button class="btn btn-success" type="button">Pindahkan</button');
			$('.form-delete-btn').show();
			$('#modal-delete').modal('show');
			return false;
			
		});

		
		$('.form-delete-btn').click(function() {
			
			$('#form_pindah').submit();
			
		});

	$('.tree-toggle').click(function () {
	$(this).parent().children('ul.tree').toggle(200);
	});

	$('.tree-toggle-tabkiri').click(function () {
	$(this).parent().children('ul.tree').toggle(200);
	 icon = $(this).find("i");
  	icon.toggleClass("fa-caret-down fa-caret-right");

	});

	$(function(){
	$('.tree-toggle').parent().children('ul.tree').toggle(200);
	$('.tree-toggle-tabkiri').parent().children('ul.tree').toggle(200);

		// form filter
	$('#sort_by, #order_by, #mulai, #selesai').change(function(){
		$.ajax({
                url: '<?php echo site_url('filing/pengarsipan/set_filter');?>',
                async: false,
                type: 'POST',
                data: $('#form_filter').serialize(),
                dataType: 'json',
                success: function(data) {
                	console.log(data);
                	//alert(data.get_se_filter.mulai+','+data.get_se_filter.selesai);
                	window.location.reload();
                }
            
            });
	});
			
	// ./form filter

	})

	// script set all konten to role konten:
	$('#bt_set_role_konten').click(function(){
		$('.form-warning-msg').html('<p>Jika tidak ada pilihan bidang, Semua <b style="color:red;">Konten Arsip</b> dalam <b>Folder Arsip</b> ini akan dimasukkan kedalam <b>kewenangan bidang</b> <b style="color:#337ab7;">"<?php echo $bidang; ?>"</b> berdasar metode yang dipilih. Akibat aksi ini, bidang lain (yang posisinya setara dengan bidang ini/bidang dibawahnya) tidak dapat melihat lagi data <b style="color:red;">konten arsip</b> yang telah dimasukkan kedalam kewenangan.</p><p><b>Tetap melanjutkan ?</b> </p>');
		$('#bt_set_role_konten_ok, #bt_cancel, .modal-close-head').show();
		$('#modal-form').html('<form action="#" id="form_konten_role" method="post" accept-charset="utf-8"><div class"form-group"><label for="method">Pilih Metode</label><select id="method" name="method" class="form-control combo-box">'
			+'<option value="1">Hanya arsip bebas saja (belum/tidak memiliki kewenangan)</option>'
			+'<option value="2">Arsip Konten dipilih</option>'
			+'</select></div>'
			+'<br/><div class"form-group"><label for="id_unit">Pilih Unit</label><select id="id_unit" name="id_unit" class="form-control combo-box">'
			+'</select></div>'
			+'<br/><div class"form-group"><label for="id_bidang">Pilih Bidang</label><select id="id_bidang" name="id_bidang" class="form-control combo-box">'
			+'</select></div>'
			+'</form><hr>').show();
		$('#id_unit').html($('#drop_unit').html());
		$('#modal-warning').modal('show');

		$('#id_unit').change(function(){
			// alert('okee');
	   		if ($(this).val()) {
			$.ajax({
				type 	: 'GET', 
				url  	: "<?php echo site_url('filing/pengarsipan/cb_bidang') ?>/"+$(this).val(),
				success	: function(data){
					// $('#id_bidang').select2('val',null);
					$('#id_bidang').html('');
					$('#id_bidang').html(data);
				}
			});
			} else {
				// $('#id_bidang').select2('val',null);
				$('#id_bidang').html('');
				$('#id_bidang').html('<option value=""> -- Pilih Bidang -- </option>');
			}
		});

	});

	// $(function(){

	// });
	

	$('#bt_set_role_konten_ok').click(function(){
		$('#bt_set_role_konten_ok, #bt_cancel, .modal-close-head').hide();
		$('.form-warning-msg').html('<p class="text-center"><img src="<?php echo base_url("assets/images/loading2.gif"); ?>" />&nbsp; <b>Mohon Tunggu.. Proses ini munkin butuh waktu beberapa menit.</b></p><p class="text-center" style="color:red;">Jangan melakukan aksi apapun sampai proses ini selesai.</p>');
		$('#modal-warning').modal({backdrop: 'static', keyboard: false});

		var konten_ids = $('input[name="cek[]"]').map(function(){
			if ($(this).is(':checked')) {
				return this.value;
			}
			
		}).get();

		$.ajax({
            url: '<?php echo site_url('filing/pengarsipan/set_konten_role/'.$id_arsip_jenis.'/'.$konten_role.'/'.$konten_bebas);?>',
                async: false,
                type: 'POST',
               data: {method: $('#method').val(), id_bidang : $('#id_bidang').val(), konten:konten_ids },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    if (data === 1) {
                    	$('#modal-form').hide();
                        $('.form-warning-msg').html('<p style="color:green;">Set kewenangan Konten Arsip Berhasil dilakukan ...</p>');
                        $('#bt_set_role_konten_ok, #bt_cancel').hide();
                        $('#bt_set_role_konten_ok').parent().append('<button onclick="javascript:f_refresh();" id="bt_refresh" type="button" class="btn btn-success">Tutup & Segarkan Halaman</button>');
                    }else{
                    	$('#modal-form').hide();
                        $('.form-warning-msg').html('<p style="color:red;">Set kewenangan Konten Arsip Gagal dilakukan ...</p><hr> <p>Beberapa hal mungkin terjadi:</p><p>*Pastikan "Folder Arsip" telah dipilih.</p><p>*jika metode arsip bebas yang digunakan, mungkin sudah tidak ada konten arsip bebas lagi dalam jenis ini.</p>');
                        $('#bt_cancel').show();
                    }
         
                }

        });
		
	});


	});

	function f_refresh(){
		window.location.reload();
	}

	// $.(function(){
		function get_sub_aj(par_id, pindah_jenis){
		$.ajax({
				type 	: 'GET', 
				dataType : 'JSON', 
				url  	: '<?php echo site_url('filing/pengarsipan/ajax_get_sub_aj'); ?>/'+ par_id.value,
				success	: function(data){
					//$('#pindah_arsip_jenis').append(data);
					var n = $('.element').length;
					var pos_sub = n-2;

					if (data.st == 1) {
						
						//if (n < 5) {
						var thecontent = '<div class="clearfix"></div><div class="element"><div class="form-group"><div class="input-group"><span class="input-group-addon"><input type="radio" name="pindah_jenis" id="pindah_jenis'+n+'" value="'+n+'"></span><select id="id_arsip_jenis'+n+'" name="id_arsip_jenis'+n+'" onchange="javascript:get_sub_aj(this, \$(\'#pindah_jenis'+n+'\'));" class="form-control combo-box">'+data.cb+'</select><div class="input-group-btn"><button onclick="javascript:bt_close_aj(this);" type="button" class="btn btn-md"><i class="fa fa-times"></i></button></div></div></div></div>';
						$('#pindah_arsip_jenis').append(thecontent);
						//}

						$('#jml_aj').val($('.element').length);
						// change attribut;
						$(par_id).attr('disabled', 'disabled');

					//	alert(pos_sub);
					}else if(data.st == 0){
						// $('#id_arsip_jenis'+pos_sub).parents('.element').fadeOut(400);
						// $('#id_arsip_jenis'+pos_sub).parents('.element').remove();
						// $('#jml_aj').val($('.element').length);
						// alert(pos_sub);
					}

					$(pindah_jenis).attr('value', par_id.value);
					
					return false;

				}
			});
	}

	// button close arsip jenis
	function bt_close_aj(item){
		var n = ($('.element').length - 1) - 1;
		$(item).parents('.element').fadeOut(400);
		$(item).parents('.element').remove();

		$('#jml_aj').val($('.element').length);
		$('#id_arsip_jenis'+n).removeAttr('disabled');
		//alert(n);
	}
	// ./button close arsip jenis
	// });

	
	<?php echo  @$script ?>
</script>

<!-- dropdown for js -->
<?php
$cb_unit = $this->general_model->combo_box(array(
	'tabel'=>'ref_unit',
	'key'=>'id_unit',
	'val'=>array('unit')));

 echo form_dropdown('drop_unit',$cb_unit,null,'id="drop_unit" class="on-hide"') ?>
<!-- ./dropdown for js -->
<div class="row">
<div class="col-md-3">

<div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Folder Arsip</h3>
        </div>
        <div class="box-body no-padding" style="overflow: auto">
          <?php echo @$table_jenis; ?>
        </div><!-- /.box-body -->
      </div>
	</div>
<div class="col-md-9">

<div class="box" id="box-main">


<?php echo @$tombol_filter?>


	<?php if (!empty($tombol) or !empty($extra_tombol)) { ?>
    <div class="box-header with-border">
	    <div class="row">
			<div class="col-md-7" style="margin-bottom: 10px"><?php echo @$tombol?></div>
			<?php if (!empty($extra_tombol)) echo '<div class="col-md-5">'.$extra_tombol.'</div>'; ?>
			<?php echo @$extra_filter;?>
	    </div>
    </div><!-- /.box-header -->
	<?php } ?>
    <div class="box-body" style="overflow: auto">

<?php
echo @$graph_area;
echo $tabel;

?>
    </div>

    <?php if (!empty($links) or !empty($total)) { ?>
    <div class="box-footer">
<div class="stat-info">
<?php if (!empty($links)) { ?><div class="pull-left" style="margin: 0;margin-right: 10px"><?php echo $links?></div><?php } ?>
<?php if (!empty($total)) { ?><div class="pull-left"><ul class="pagination"><li><a>Total</a></li><li><a><strong><?php echo $total?></strong></a></li></ul></div><?php } ?>
</div>
<div class="clear"></div>
<br/>

<?php form_close(); ?>

<?php if (!empty($filter)) $this->load->view($filter); ?>
</div>
<?php } ?>

</div>

</div>

<?php echo @$include_script;?>

<div class="modal fade" id="modal-warning" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
	    <div class="modal-header"><button class="close modal-close-head" aria-label="Close" data-dismiss="modal" type="button">
		<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title form-title"><i style="color:red;" class="fa fa-times-circle"></i> &nbsp; Peringatan</h4></div>
        <div class="modal-body">
        <div id="modal-form"></div>
        <span class="form-warning-msg"></span>
        </div>
        <div class="modal-footer">
		<button id="bt_cancel" class="btn btn-danger pull-left" data-dismiss="modal" type="button">Batal</button>
		<button id="bt_set_role_konten_ok" class="btn btn-primary pull-right" type="button">Lanjutkan</button>
		</div>
    </div>
  </div>
</div>


