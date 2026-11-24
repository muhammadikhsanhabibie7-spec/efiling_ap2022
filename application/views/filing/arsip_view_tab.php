<?php echo @$css_load; ?>
<?php echo @$include_script;?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.heading').parent().find('td').addClass('headings');
	});
	<?php echo  @$script ?>
</script>

 <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
	    <?php 
		    $no = 1;
		    foreach($jenis->result() as $je) { 
	    	$act = ($je->id_arsip_jenis == $arsip_aktif) ? 'class="active"':null;
	    	$p = ($je->id_arsip_jenis == $arsip_aktif) ? 'label-primary':'label-default';

	    	?>
			  	<li <?php echo $act ?>>
			  		<?php 
				  		$se = in_de(array(
					  		'arsip_judul' => $je->arsip_jenis,
					  		'id_arsip_jenis' => $je->id_arsip_jenis
				  		));
				  		
				  		$jml = null;
				  		foreach($arsip_jenis->result() as $ars) {
				  			if ($ars->id_arsip_jenis ==  $je->id_arsip_jenis) $jml = ' &nbsp; <span class="label '.$p.' pull-right">'.$ars->jml.'</span>';
				  		}
				  		
				  		echo anchor(
				  			'filing/pengarsipan/list_data/'.$se,
				  			$je->arsip_jenis.$jml,'id="#'.$je->id_arsip_jenis.'"'); ?>
			  	</li>
			  <?php		    $no+=1;
		    } ?>
    </ul>
    <div class="tab-content">

    	<?php echo @$tombol_filter?>



      <div class="tab-pane active" id="<?php echo $arsip_aktif ?>">
	       <div class="row">
			<div class="col-md-7" style="margin-bottom: 10px"><?php echo @$tombol?></div>
			<?php if (!empty($extra_tombol)) echo '<div class="col-md-5">'.$extra_tombol.'</div>'; ?>
	    </div>
	       <?php echo $tabel; ?>
<?php if (!empty($links) or !empty($total)) { ?>
    <div class="box-footer">
<div class="stat-info">
<?php if (!empty($links)) { ?><div class="pull-left" style="margin: 0;margin-right: 10px"><?php echo $links?></div><?php } ?>
<?php if (!empty($total)) { ?><div class="pull-left"><ul class="pagination"><li><a>Total</a></li><li><a><strong><?php echo $total?></strong></a></li></ul></div><?php } ?>
</div>
<div class="clear"></div>
<?php if (!empty($filter)) $this->load->view($filter); ?>
</div>
<?php } ?>

      </div>
    </div>
 </div>


