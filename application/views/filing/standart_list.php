
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
                <form action="<?php echo site_url('filing/Anjab_alat/list_data/'.$search); ?>" class="form-inline pull-right" method="post">
                    
                    <?php
                     if (!empty($extra_filter)) {
                        echo '<div class="form-group">';
                        echo $extra_filter;
                        echo '</div>';
                     }
                      ?>

                    <div class="input-group">
                        <input style="min-width:16em" type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('filing/Anjab_alat'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Cari</button>
                        </span>
                    </div>
                </form>
            </div>

            
        </div>
        <div class="table-responsive">

        <?php echo $table; ?>

        </div>
            <br/>
            <div class="col-md-12">
                <?php if (!empty($total_rows)) { ?>
                    <div style="margin-left:-14px;"  class="pull-left">
                        <nav><ul class="pagination">
                        <li><a>Total Data : </a></li><li><a><strong><?php echo $total_rows?></strong></a></li>
                        </ul></nav>
                    </div>
                <?php } ?>
                <div style="margin-right:-14px;" class="text-right pull-right"><?php echo $pagination ?></div>
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


    