<style>

	.direktori { list-style-type: none; margin: 0; padding: 5px; }
	.tabel-direktori { width: 100% }
	.tabel-direktori td { padding: 4px 3px; }
	.tabel-direktori td a { color: #000; }
</style>
<?php echo @$css_load; ?>


<div class="row">
	<div class="col-sm-3">

<div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Jenis Arsip</h3>
        </div>
        <div class="box-body no-padding">

          <?php echo @$table_jenis; ?>
        </div><!-- /.box-body -->
      </div>
	</div>
	<div class="col-sm-9">

<div class="box" id="box-main">


<?php echo @$tombol_filter?>


	<?php if (!empty($tombol) or !empty($extra_tombol)) { ?>
    <div class="box-header with-border">
	    <div class="row">
			<div class="col-md-7" style="margin-bottom: 10px"><?php echo @$tombol?></div>
			<?php if (!empty($extra_tombol)) echo '<div class="col-md-5">'.$extra_tombol.'</div>'; ?>
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




