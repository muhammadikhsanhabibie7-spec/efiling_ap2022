
<div class="row">
    <div class="col-md-12">
        <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo @$title; ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">

        <div class="row" style="margin-bottom: 10px">
        <?php if (!empty($tombol) or !empty($extra_tombol)) { ?>
            
                    <div class="col-md-2" style="margin-bottom: 10px"><?php echo @$tombol?></div>
                    <?php if (!empty($extra_tombol)) echo '<div class="col-md-2">'.$extra_tombol.'</div>'; ?>
              
        <?php } ?>

            <div class="col-md-2 text-center">
                <div style="margin-top: 4px;"  id="message">
                    <span><?php echo $this->session->flashdata('message') <> '' ? $this->session->flashdata('message') : ''; ?></span>
                </div>
            </div>

            <div class="col-md-6 pull-right" style="margin-bottom: 10px;">
                <form action="<?php echo site_url('bahan/log_users/list_data/'.@$search); ?>" class="form-inline pull-right" method="post">
                    
                    <?php
                     if (!empty($extra_filter)) {
                        echo '<div class="form-group">';
                        echo $extra_filter;
                        echo '</div>';
                        echo '<button class="btn btn-primary" type="submit">Filter</button>';
                     }
                      ?>
                </form>
            </div>

        </div>
        <div class="">

        <?php echo $table; ?>

        </div>

          </div>
    </div> <!-- ./box -->

</div>
<script type="text/javascript">
    $(document).ready(function() {    
    <?php echo @$script; ?>

    });

    $(function(){
    <?php echo @$include_script;?>
    });

    <?php echo @$function_script;?>
</script>


    