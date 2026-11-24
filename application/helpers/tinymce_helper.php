<?php
//ver 0.3
  function tinymce($simple_theme="",$id='',$skin=''){ 
      $simple_theme=('1'==$simple_theme?"simple":(''==$simple_theme?"advanced":$simple_theme)); 
      $id=($id?$id:'tarea');
      $skin=($skin?$skin:'bootstrap');
      echo "<!-- TinyMCE --><script type='text/javascript'> 
      tinyMCE.init({ 
        mode : 'textareas', 
        theme : '$simple_theme', "
      ."editor_selector : '$id', "
//  elements : '$id', textareas
//      ."skin : 'o2k7', 
      ."skin : '$skin', 
        plugins : 'autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups', 
        theme_advanced_buttons1 : 'undo,redo,|,cut,copy,paste,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,image,insertdate,inserttime,|,template,code,|,print,preview,|,fullscreen', 
        theme_advanced_buttons2 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,charmap,advhr', 
        theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,|,nonbreaking,pagebreak,insertlayer,moveforward,movebackward,absolute', 
        theme_advanced_toolbar_location : 'top', theme_advanced_toolbar_align : 'left', 
        theme_advanced_statusbar_location : 'bottom', theme_advanced_resizing : true,  
        content_css : 'js/tiny_mce/css/word.css',
        template_external_list_url : '".site_url("templ_js")."',
        external_link_list_url : '".base_url("js/tiny_mce/lists/link_list.js")."',
        external_image_list_url : '".base_url("js/tiny_mce/lists/image_list.js")."',
        media_external_list_url : '".base_url("js/tiny_mce/lists/media_list.js")."'}
        ); 

   /* \$(document).on(\'focusin\', function(e) {
        if (\$(e.target).closest(\".mce-window\").length) {
            e.stopImmediatePropagation();
        }
    });
       */ 
        </script> <!-- /TinyMCE -->"; 
  }
?>
