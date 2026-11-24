<?php 

 $st = get_stationer();
 $s = $this->session->userdata('login_state');
 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $st['aplikasi_s'].' - '.$st['aplikasi'] ?></title>
<style type="text/css">
.by{
  vertical-align: bottom;
  height: 34px;
  border: 1px rgba(191, 191, 191, 0.6) solid;
}
</style>
    <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/admin-lte.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/skins.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/general.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/plugins/font-awesome/css/font-awesome.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/plugins/datepicker/datepicker3.css' ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().'assets/plugins/daterangepicker/daterangepicker-bs3.css' ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().'assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css' ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url().'assets/plugins/select2/select2.css' ?>" rel="stylesheet" type="text/css" />

  <link href="<?php echo base_url()?>assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" rel="stylesheet" type="text/css" />

<!-- datatables -->
<!--<link href="<?php echo base_url()?>assets/plugins/datatables/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />-->

<link href="<?php echo base_url()?>assets/plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<!-- ./datatables -->

<!-- ./ css -->
	<!-- <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>  -->

  <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery-1.12.3.min.js'?>"></script>

	<script type="text/javascript" src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/app.min.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/daterangepicker/daterangepicker.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'assets/plugins/datepicker/bootstrap-datepicker.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/select2/select2.js' ?>"></script>
	
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/input-mask/jquery.inputmask.js' ?>" ></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/input-mask/jquery.inputmask.date.extensions.js' ?>" ></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/input-mask/jquery.inputmask.extensions.js' ?>" ></script>
	
	<script type="text/javascript" src="<?php echo base_url().'assets/js/general.js' ?>"></script>

  <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>

  <!-- datatables -->
  <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/datatables/media/js/jquery.dataTables.js"></script>

  <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/datatables/media/js/dataTables.bootstrap.js"></script>
  <!-- ./datatables -->
		
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
   
	  </head>
  
  
  <body class="skin-blue sidebar-mini">
	  <?php 

		$on = get_role($this->session->userdata("id_pegawai"));
		$dircut = !empty($dircut) ? $dircut : $this->uri->segment(1);
		$app = @$on['aplikasi'][$dircut];
	
		$username = $this->session->userdata('username');
		$nama = $this->session->userdata('nama');
		$id_operator = $this->session->userdata('id');

		?>
	  
	  
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"></span>
		  <?php
			$path_inst_logo = !empty($st['pemerintah_logo']) ? FCPATH.'logo/'.$st['pemerintah_logo'] : null;
			$logo_instansi = (file_exists($path_inst_logo) and !empty($st['pemerintah_logo'])) ? base_url().'logo/'.$st['pemerintah_logo'] : base_url().'assets/logo/brand.png';
			?>
          <p class="brand-instansi"><img src="<?php echo $logo_instansi ?>" /></p>
          <span class="logo-lg"><?php echo $st['pemerintah'] ?></span>
          <p class="text-instansi"><b><?php echo $st['instansi'] ?></b></p>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
	        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
	      <a class="logo-i"><?php if (!empty($app)) { 
			$app_logo = !file_exists('./assets/logo/'.$app['direktori'].'.png') ? base_url().'assets/logo/inventori.png' : base_url().'assets/logo/'.$app['direktori'].'.png';
				?>
          	<img src="<?php echo $app_logo ?>"/><span class="logo-lg"><?php echo $st['aplikasi_s'] ?></span>
			<?php } ?>
	      </a><!-- Sidebar toggle button-->
			<?php
	        $ava = $this->session->userdata('avatar');
			$ava = (!empty($ava) and file_exists('.uploads/kepegawaian/pasfoto/'.$ava))?base_url().'uploads/kepegawaian/pasfoto/'.$ava : base_url().'assets/images/avatar.gif'; 
			$nama = $this->session->userdata('nama');
			?>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
				<?php if ($s == "root") { ?>
				 <a>
                  <img src="<?php echo $ava; ?>" class="user-image"/>
                  <span class="hidden-xs">Root</span>
                </a>
				<?php } else { ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	              <div class="user-image">
                  <img src="<?php echo $ava; ?>" alt="<?php echo $nama ?>"/>
	              </div>
                  <span class="hidden-xs"><?php echo $nama?></span>
                  <span class="clear"></span>
                </a>
				<?php } ?>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
				  	         <div class="img-circle">
                      <img src="<?php echo $ava ?>" alt="<?php echo $nama  ?>" />
				  	         </div>
                    <p><?php echo $nama ?></p>
				          </li>
                  <li class="user-body">
                    <div class="text-center">
                      <?php echo $this->session->userdata('nama_role');?>
                    </div>
                  </li>
				<?php if ($st['demo'] == 2) { ?>
                  <!-- Menu Body -->
				  	<li class="user-footer">
                      <?php echo anchor(
	                    	'home/akun',
							'<i class="fa fa-lock"></i> Akun &amp; Password',
							'class="btn btn-default btn-profil pull-left"'); ?>				 
					</li>
				<?php } ?>
                </ul>
              </li>
  
              <!-- Control Sidebar Toggle Button -->
              <li>
				<?php 
				if (count($on['aplikasi']) == 1 or $s == "root") { ?>
				<a href="<?php echo site_url('login/process_logout') ?>" class="btn-off"><i class="fa fa-power-off"></i></a>
				<?php } else { ?>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-power-off"></i></a>
				<?php } ?>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar" >
        <div class="user-panel hidden-xs">
            <div class="pull-left image">
              <img src="<?php echo $logo_instansi ?>" alt="<?php echo $st['instansi'] ?>" />
            </div>
            <div class="pull-left info">
              <p><?php echo $st['instansi_code'] ?></p>
            </div>
          </div>
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
	        <ul class="sidebar-menu" style="margin-bottom:130px;">
			
			<?php
			
			if ($s == "root") {
				
				$this->load->view('umum/rootnav_view');
				
			} else { ?>

		    <li><?php echo anchor($app['direktori'].'/home','<i class="fa fa-home"></i> <span>Beranda</span>') ?></li>
			<?php 
			$id_peg = $this->session->userdata('id_pegawai');
			if ($app['id_aplikasi'] == 1) get_ref_nav($id_peg);
			else get_nav($id_peg,1,$app['id_aplikasi']);
			}
			?></ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
	                <h1>
            <?php echo (!empty($title))?$title:null; ?>
            <?php echo (!empty($descript)) ? '<small>'.$descript.'</small>' : null; ?>
          </h1>
          
            <?php  
	        $warning = $this->session->userdata('peringatan');
	         	if (!empty($warning) and count($warning) > 0) {
	          	echo '<div class="alert alert-danger alert-dismissable" style="margin-top: 10px">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-ban"></i> Peringatan! </h4>';
                    foreach($warning as $w) {
	                    echo '<p>'.$w.'</p>';          
                    }
                    echo '</div>'; 
              	} 
              	
              	$ok = $this->session->flashdata('ok');
              	$fail = $this->session->flashdata('fail');
              	$msg = array();
              	if (!empty($ok)) {
	              	$msg['head'] = 'Pesan';
	              	$msg['box'] = 'alert-success';
	              	$msg['icon'] = 'fa-check';
	              	$msg['pesan'] = $ok;
              	}
              	
              	if (!empty($fail)) {
	              	$msg['head'] = 'Peringatan';
	              	$msg['box'] = 'alert-danger';
	              	$msg['icon'] = 'fa-ban';
	              	$msg['pesan'] = $fail;
              	}
              	
              	
              	if (!empty($msg)) {
              	?>
              	<div class="alert <?php echo $msg['box'] ?> alert-dismissable" style="margin-top: 10px">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa <?php echo $msg['icon'] ?>"></i> <?php echo $msg['head'] ?></h4><p><?php echo $msg['pesan'] ?></p></div> 
			  <?php } ?>
          <ol class="breadcrumb">
                <?php
                if (isset($breadcrumb)) {
				if ($s != 'root') echo '<li>'.anchor('home','<i class="fa fa-dashboard"></i>Beranda</a>').'</li>';
				else echo '<li>'.anchor('','<i class="fa fa-dashboard"></i>').'</li>';
                foreach($breadcrumb as $bre => $val) {
                    echo "<li>".anchor($bre,$val)."</li>";
                }
                }       
                ?>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
	        <div class="row on-hide" id="form-box">
		        <div class="col-md-10">
	        	  <div class="box box-warning">
		        	  <!--div class="box-header with-border">
                  <i class="fa fa-paper-plane"></i>
                  <h3 class="box-title" id="form-title"></h3>
                </div-->
					      <div class="box-body">
						        <div class="overlay hide" id="form-overlay">
						          <i class="fa fa-refresh fa-spin"></i>
						        </div>
						        <div id="form-content"></div>
					       </div>
	        	  </div>
		        </div>
	        </div>
           <?php if(@$content)$this->load->view($content);?>
       	</section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Versi Enterprise</b> 1.0
        </div>
        &copy; 2016 | <a href="http://www.mediamultikaryatama.id">CV. Media Multi Karyatama</a>.</strong>
      </footer>
      
      <!-- Control Sidebar -->      
      <aside class="control-sidebar control-sidebar-dark">                
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
          
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content"></div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
    <!-- Modal -->
<div class="modal fade" id="modal-profil" tabindex="-1" role="dialog" aria-hidden="true"></div>
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	    <div class="modal-header"><button class="close" aria-label="Close" data-dismiss="modal" type="button">
		<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title form-title"><i class="fa fa-trash"></i> &nbsp; Konfirmasi Hapus</h4></div>
        <div class="modal-body"><span class="form-delete-msg"></span></div>
        <div class="modal-footer">
		<button class="btn btn-default pull-left" data-dismiss="modal" type="button">Batal</button>
		<a class="form-delete-url"><button class="btn btn-danger" type="button">Hapus</button></a>
		<a class="form-delete-btn" style="display: none"><button class="btn btn-danger" type="button">Hapus</button></a>
		</div>
    </div>
  </div>
</div>


<?php 
if (count($app) > 1) { ?>
<aside class="control-sidebar control-sidebar-dark">                
        <!-- Create the tabs -->
        <!--<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li class=""><a data-toggle="tab" href="#control-sidebar-theme-demo-options-tab" aria-expanded="false"><i class="fa fa-wrench"></i></a></li>
          <li class=""><a data-toggle="tab" href="#control-sidebar-home-tab" aria-expanded="false"><i class="fa fa-home"></i></a></li>
          <li class="active"><a data-toggle="tab" href="#control-sidebar-settings-tab" aria-expanded="true"><i class="fa fa-gears"></i></a></li>
        </ul>-->
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div id="control-sidebar-settings-tab" class="tab-pane active">
            <h3 class="control-sidebar-heading">
				<div class="logo-brand">
				<img src="<?php echo base_url().'assets/logo/'.$st['aplikasi_logo'] ?>"/>
	        		<?php echo $st['aplikasi_s'] ?>
	        		<p><small><?php echo $st['aplikasi'] ?></small></p>
				</div>
			</h3>
			<h5>Pindah Aplikasi</h5>
            <ul class="control-sidebar-menu">
	          <?php foreach($on['aplikasi'] as $k => $v) {   
		          	if ($v['direktori'] != $this->uri->segment(1)) {
	          ?>
              <li>

                <a href="<?php echo site_url('home/aplikasi/'.$v['direktori']); ?>">
	              <div class="menu-icon" style="background: <?php echo $v['warna'] ?>">
		              
					  <?php $ava_app = (file_exists('./assets/logo/'.$v['direktori'].'.png')? $v['direktori'].'.png':'logo.png'); ?>
		              <img src="<?php echo base_url().'assets/logo/'.$ava_app ?>" style="width: 26px;"/>
	     
		              
	              </div>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading"><?php echo $v['nama_aplikasi'] ?></h4>
                    <p><?php echo $v['nama_role'] ?></p>
                  </div>
                </a>
              </li>
              <?php }} ?>
           </ul><!-- /.control-sidebar-menu -->
            <h3></h3><p class="text-right">
	        <a href="<?php echo site_url('login/role_choice') ?>" class="btn btn-warning"><i class="fa fa-recycle fa-btn"></i> Aplikasi</a>&nbsp;
            <?php echo anchor('login/process_logout','<i class="fa fa-power-off"></i> &nbsp; Logout','class="btn-off btn btn-danger"'); ?>
            </p>
          </div>
 
          
        </div>
      </aside>
<?php } ?>

<div class="modal fade" id="modal-alert" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  	<div class="alert alert-system-switch alert-dismissable alert-dialog" style="margin: 0 auto;">
           <button class="close" aria-label="Close" data-dismiss="modal" type="button">
		<span aria-hidden="true">×</span>
		</button>
            <h4 class="alert-icon"></h4>
            <p class="alert-message"></p>
             <span aria-label="Close" data-dismiss="modal" id="modal-alert-btn" class="btn btn-success btn-outline btn-sm pull-right">
		&nbsp;&nbsp; OK &nbsp;&nbsp;</span>
		<div class="clear"></div>
  	</div>
  </div>
</div>
<script type="text/javascript">
	
	
		function varian(hex, lum) {

		// validate hex string
		hex = String(hex).replace(/[^0-9a-f]/gi, '');
		if (hex.length < 6) {
			hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
		}
		lum = lum || 0;
	
		// convert to decimal and change luminosity
		var rgb = "#", c, i;
		for (i = 0; i < 3; i++) {
			c = parseInt(hex.substr(i*2,2), 16);
			c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
			rgb += ("00"+c).substr(c.length);
		}
	
		return rgb;
	}
	$(document).ready(function() {
		
		$('.skin-blue .sidebar-menu > li.active > a').css('border-left-color',varian('<?php echo $st['main_color'] ?>',0.2));	
		$('.skin-blue .main-header .navbar').css('background-color','<?php echo $st['main_color'] ?>');
		$('.skin-blue .main-header .logo ').css('background-color',varian('<?php echo $st['main_color'] ?>',-0.3)); 
	});
	</script>
	
</body>
</html>