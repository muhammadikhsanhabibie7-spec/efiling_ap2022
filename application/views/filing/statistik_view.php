<style>

	.direktori { list-style-type: none; margin: 0; padding: 5px; }
	.tabel-direktori { width: 100% }
	.tabel-direktori td { padding: 4px 3px; }
	.tabel-direktori td a { color: #000; }
</style>

<div class="row">
	<div class="col-lg-12">
		<div class="box">
	        <div class="box-header">
	        <h3 class="box-title"><i class="fa fa-line-chart"></i> &nbsp; Statistik Data</h3>
	        </div><!-- /.box-header -->
	        <div class="box-body no-padding">
		      <?php 
			   
			 //    $this->table->set_template(array('table_open'=>'<table class="table table-condensed"'));
			 //    $this->table->set_heading(array('data' => 'No','width' => 20), 'Jenis Arsip',array('data' => 'Jumlah Konten','width' => 20), array('data' => 'Jumlah Arsip/Upload','width' => 20));
			 //    $no = 0;
			 //    foreach($data_arsip->result() as $row) {
				//     $this->table->add_row(
				//     	$no+=1,
				//     	$row->arsip_jenis,
				//     	'<span class="badge bg-blue">'.$row->jm_konten.'</span>',
				//     	'<span class="badge bg-red">'.$row->jm_upload.'</span>'
				//     	);
			 //    }
				
				// echo $this->table->generate();

		      echo $tabel_statistik;
			   ?>
	        </div>
	      </div>
	</div>
</div>