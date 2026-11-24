<link href="<?php echo base_url().'assets/plugins/iCheck/all.css' ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/iCheck/icheck.min.js'; ?>"></script>

<script type="text/javascript">
	$('.tree-toggle').click(function () {
	$(this).parent().children('ul.tree').toggle(200);
	});
	$(function(){
	$('.tree-toggle').parent().children('ul.tree').toggle(200);
	})
</script>

<?php echo  
form_open($link_save,'id="form_role"').
form_hidden('id_bidang',@$def->id_bidang);
?>
<h3>Pengaturan Kewenangan Jenis Arsip</h3>
<p><?php echo form_label('Nama Bidang').form_input('nama_role',@$def->nama_bidang,'class="form-control" readonly required'); ?></p>
<?php 
if (!empty($def))  {
	$sel = 'disabled="disabled"';
	echo form_hidden('id_aplikasi',@$def->id_aplikasi);
} 
?>
<p>
	
<?php if (!empty($combo_aplikasi)) { ?>
	<label>Aplikasi</label><br><?php echo form_dropdown('id_aplikasi',$combo_aplikasi,@$def->id_aplikasi,'class="combo-box form-control" style="width: 100%" id="pilih" '.@$sel); ?></p>
<?php  } else { echo form_hidden('id_aplikasi',$aplikasi->row()->id_aplikasi); }

$codes = !empty($def->role) ? unserialize($def->role) : null; 

foreach($aplikasi->result() as $app) { 

if (!empty($app_data[$app->id_aplikasi])) {

?>
<div class="<?php echo ((!empty($def) and @$def->id_aplikasi == $app->id_aplikasi) or $aplikasi->num_rows() == 1) ? null : "hide" ?> roled roled-box" id="aplikasi<?php echo  $app->id_aplikasi ?>">
 <ul class="nav nav-list">
 <input type="checkbox" value="" /> <b>Centang Semua</b>
 	<?php echo $app_data[$app->id_aplikasi]; ?>
 </ul>


<div>
</div>




<?php }} ?>
<button class="btn btn-danger btn-md btn-form-cancel" type="button">Batal</button>
<button href="#" class="btn btn-success btn-md pull-right"><i class="fa fa-save"></i> &nbsp; Simpan</button>
<div class="clear"></div>
<?php echo  form_close()?>
	

<script type="text/javascript">
	$(document).ready(function(){
		$('select').select2();
		$('.btn-form-cancel').click(function() {
		   $('#form-content,#form-title').html('');
		   $('#form-box').slideUp();
		   $('#box-main').show();
	   	});
	   	
	   	$('#pilih').change(function() {
			$('.roled').hide();
			$('#aplikasi'+$(this).val()).removeClass('hide').show();
			$('.incheck').each(function() {
				$(this).removeAttr('checked');
			});
		
		});
		
		$('input[type="checkbox"].incheck').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'iradio_minimal-blue'
		});
			
		$('#form-title').html('<?php echo $title; ?>');
	});

// check all in sub:
  // Apparently click is better chan change? Cuz IE?
  $('input[type="checkbox"]').change(function(e) {

  var checked = $(this).prop("checked"),
      container = $(this).parent(),
      siblings = container.siblings();

  container.find('input[type="checkbox"]').prop({
    // indeterminate: false,
    checked: checked
  });

  function checkSiblings(el) {

    var parent = el.parent().parent(),
        all = true;

    el.siblings().each(function() {
      return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
    });

    if (all && checked) {

      parent.children('input[type="checkbox"]').prop({
       // indeterminate: false,
        checked: checked
      });

      checkSiblings(parent);

    } else if (all && !checked) {

      parent.children('input[type="checkbox"]').prop("checked", checked);
      parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
      checkSiblings(parent);

    } else {

      el.parents("li").children('input[type="checkbox"]').prop({
       // indeterminate: true,
        // checked: false,
        checked: true
      });

    }

  }

  checkSiblings(container);
});

  </script>
