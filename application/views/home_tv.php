<?php $st = get_stationer(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $st['aplikasi_s'].' - '.$st['aplikasi'] ?></title>

    <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/admin-lte.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/skins.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/general.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/css/tv.css' ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/plugins/font-awesome/css/font-awesome.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url().'assets/plugins/datepicker/datepicker3.css' ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().'assets/plugins/daterangepicker/daterangepicker-bs3.css' ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().'assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css' ?>" rel="stylesheet" type="text/css" />


	<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/app.min.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/daterangepicker/daterangepicker.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'assets/plugins/datepicker/bootstrap-datepicker.js' ?>"></script>

	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/plugins/select2/select2.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/general.js' ?>"></script>

  <script type="text/javascript" src="<?php echo base_url()?>assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>

  <script type="text/javascript" src="<?php echo base_url().'assets/plugins/input-mask/jquery.inputmask.js' ?>" type="text/javascript"></script>
  <script type="text/javascript" src="<?php echo base_url().'assets/plugins/input-mask/jquery.inputmask.date.extensions.js' ?>" type="text/javascript"></script>
  <script type="text/javascript" src="<?php echo base_url().'assets/plugins/input-mask/jquery.inputmask.extensions.js' ?>" type="text/javascript"></script>
  
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
      $(function(){
          $(".fancybox").fancybox();
      });
    </script>
    <script type="text/javascript">
    $(function() {
      var x;
      setInterval(function() {
    
        if(x == 0) { 
          $('.blink').removeAttr('style'); x = 2;
        } else  {
          if (x = 2) { 
            $('.blink').css('visibility', 'hidden'); 
            x = 0; 
          }
        }
        
      }, 500);
    });
    </script>
   
  </head>
  <body class="skin-blue" onload="startTime()">
    <div class="wrapper">
	    
	    <?php
		    $logo_app = !empty($st['aplikasi_logo'])? base_url().'/assets/logo/'.$st['aplikasi_logo'] : base_url().'assets/logo/logo.png';?>

      <header class="main-header">
	      <nav class="navbar navbar-static-top">
	       <a class="logo-i" style="text-align: left; width: 350px !important;">
            	<img src="<?php echo $logo_app?>"/>
              <span class="logo-lg"><?php echo $st['aplikasi'] ?></span>
  	      </a>
  	       <a class="logo-i jam" style="float: right; text-align: right;  width: 350px !important;">
	  	       <span id="jam"></span><span><?php echo '&nbsp; &nbsp;', date('d/m/Y'); ?></span>
  	       </a>

	      </nav>
      </header>

      <!-- Content Wrapper. Contains page content -->

        
	  <div style="width: 100%; background: #fff; padding: 20px">
           <?php $this->load->view($content); ?>
	  </div>
     </div><!-- ./wrapper -->
    <!-- Modal -->
    <div class="col-lg-12 col-md-12 col-sm-12 marqueetv">
      <script type="text/javascript">

      </script>
      <link rel="stylesheet" id="theme-css" href="<?php echo base_url('assets/plugins/berjalan/css/owl.theme.css')?>" type="text/css" media="all">
      <link rel="stylesheet" id="style-css" href="<?php echo base_url('assets/plugins/berjalan/css/style.css')?>" type="text/css" media="all">
      
      <div class="nine columns">
        <div id="ticker">
			<div class="marquee" id="mycrawler">

	    <?php
      $jml=1;
      foreach($rune->result() as $r) { ?>
				<div>
          <?php if ($jml>1) { ?>
             <h4 style="color: #fff; margin: 0px; line-height:20px;"> | <?php echo $r->text; ?>  | </h4>
          <?php
          }else{ ?>
              <h4 style="color: #fff; margin: 0px; line-height:20px;"><?php echo $r->text; ?></h4>
        <?php }?>
				</div>
			<?php
        $jml+=1;
        }?>       
			</div>
      </div>
      <script type="text/javascript" src="<?php echo base_url('assets/plugins/berjalan/js/jquery.form.min.js')?>"></script>
      <script type="text/javascript" src="<?php echo base_url('assets/plugins/berjalan/js/jquery.pageslide.min.js')?>"></script>
      <script type="text/javascript" src="<?php echo base_url('assets/plugins/berjalan/js/jquery.infinitescroll.min.js')?>"></script>
      <!--script type="text/javascript" src="<?php echo base_url('assets/plugins/berjalan/js/custom.js')?>"></script-->

    </div>
	</div>
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
"use strict";


//////////////////////////////////////////////////////////////////////////
//        Infinite Scroll
//////////////////////////////////////////////////////////////////////////
  var $container = $('#content_masonry');
  $container.infinitescroll({
    navSelector  : "div.pagination-more",            
    nextSelector : "div.more-previous a",                   
    itemSelector : "#content_masonry div.loop-post-content",
   loading: {
          msgText: "",
          finishedMsg: '<span>No more posts to load.</span> <style type="text/css">.pagination-more{ display:none !important;}</style>'
        }
    
  },

            function( newElements ) {
jQuery().waypoint && (jQuery(".appear_animation").waypoint(function () {
        if (!jQuery(this).hasClass("animate_css_stlye animate_start")) {
            var e = jQuery(this);
            setTimeout(function () {
                e.addClass("animate_css_stlye animate_start")
            }, 20)
        }
    }, {
        offset: "85%",
        triggerOnce: !0
    }));

  }); 
  

    jQuery(window).unbind('.infscr');
  jQuery('div.more-previous a').click(function(){jQuery('#content_masonry').infinitescroll('retrieve');
  return false;
  });

//////////////////////////////////////////////////////////////////////////
//        Real time
//////////////////////////////////////////////////////////////////////////

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
setInterval( function() {
  // Create a newDate() object and extract the seconds of the current time on the visitor's
  var seconds = new Date().getSeconds();
  // Add a leading zero to seconds value
  $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
  },1000);
  
setInterval( function() {
  // Create a newDate() object and extract the minutes of the current time on the visitor's
  var minutes = new Date().getMinutes();
  // Add a leading zero to the minutes value
  $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
  
setInterval( function() {
  // Create a newDate() object and extract the hours of the current time on the visitor's
  var hours = new Date().getHours();
  // Add a leading zero to the hours value
  $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000); 

  
//////////////////////////////////////////////////////////////////////////
//        Mobile menu
//////////////////////////////////////////////////////////////////////////
    
    $(".open").pageslide()
//////////////////////////////////////////////////////////////////////////
//        Ticker
//////////////////////////////////////////////////////////////////////////
    
    var marquee = jQuery("#mycrawler"); 
  var time_multiplier = 12;
  var current;
  /*
  marquee.hover(function(){
    current.pause();
  }, function(){
    current.resume();
  })*/
  
    var reset = function() {
    current = jQuery(this);
    var item_width = jQuery(this).outerWidth();
    
    var time = time_multiplier * jQuery(this).outerWidth(); 
        jQuery(this).animate({ 'margin-left': -item_width }, time, 'linear', function(){
      var clone_item = jQuery(this).clone();
      clone_item.css({ 'margin-left': '0' });
      marquee.append(clone_item);
  
      jQuery(this).remove();
      reset.call(marquee.children().filter(':first'));
    }); 
    };
  
    reset.call(marquee.children().filter(':first'));
  
  //////////////////////////////////////////////////////////////////////////
//        Add class
//////////////////////////////////////////////////////////////////////////

jQuery().waypoint && (jQuery(".appear_animation").waypoint(function () {
        if (!jQuery(this).hasClass("animate_css_stlye animate_start")) {
            var e = jQuery(this);
            setTimeout(function () {
                e.addClass("animate_css_stlye animate_start")
            }, 20)
        }
    }, {
        offset: "85%",
        triggerOnce: !0
    }));
  
jQuery().waypoint && (jQuery(" .reviewbox").waypoint(function () {
        if (!jQuery(this).hasClass("animation_bar_width")) {
            var e = jQuery(this);
            setTimeout(function () {
                e.addClass("animation_bar_width")
            }, 20)
        }
    }, {
        offset: "85%",
        triggerOnce: !0
    }));  
    
//////////////////////////////////////////////////////////////////////////
//        Tab
//////////////////////////////////////////////////////////////////////////  
  
    var $tabsNav = $('.tabs'),
        $tabsNavLis = $tabsNav.children('li');
    $tabsNav.each(function () {
        var $this = $(this);
        $this.next().children('.tab-content').stop(true, true).hide()
            .first().show();
       $this.children('li').first().addClass('active').stop(true, true).show();
    });
    $tabsNavLis.on('click', function (e) {
        var $this = $(this);
        $this.siblings().removeClass('active').end()
            .addClass('active');
        $this.parent().next().children('.tab-content').stop(true, true).hide()
            .siblings($this.find('a').attr('href')).fadeIn();
        e.preventDefault();
    });
  
    var $tabsNav = $('.tabs1'),
        $tabsNavLis = $tabsNav.children('li');
    $tabsNav.each(function () {
        var $this = $(this);
        $this.next().children('.tab-content1').stop(true, true).hide()
            .first().show();
       $this.children('li').first().addClass('active').stop(true, true).show();
    });
    $tabsNavLis.on('click', function (e) {
        var $this = $(this);
        $this.siblings().removeClass('active').end()
            .addClass('active');
        $this.parent().next().children('.tab-content1').stop(true, true).hide()
            .siblings($this.find('a').attr('href')).fadeIn();
        e.preventDefault();
    }); 
  
  
      var $tabsNav = $('.hover_tab_post_large'),
        $tabsNavLis = $tabsNav.children('li');
    $tabsNav.each(function () {
        var $this = $(this);
        $this.next().children('.tab-content').stop(true, true).hide()
            .first().show();
       $this.children('li').first().addClass('active').stop(true, true).show();
    });
    $tabsNavLis.on('hover', function (e) {
        var $this = $(this);
        $this.siblings().removeClass('active').end()
            .addClass('active');
        $this.parent().next().children('.tab-content').stop(true, true).hide()
            .siblings($this.find('a').attr('rel')).fadeIn();
        e.preventDefault();
    });



//////////////////////////////////////////////////////////////////////////
//        Go to top
//////////////////////////////////////////////////////////////////////////

  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 500) {
      jQuery("#go-top").fadeIn();
    } else {
      jQuery("#go-top").fadeOut();
    }
  });
  $("#go-top").click(function () {
    jQuery("body,html").animate({ scrollTop: 0 }, 800 );
    return false;
  });   
});

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('jam').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
</script>
    </script>
  </body>
</html>