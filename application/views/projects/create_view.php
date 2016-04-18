<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-12">
        <?php
        echo form_open('projects/create');
            echo '<div class="form-group">';
            echo form_label('Title','title');
            echo form_error('title');
            echo form_input('title',set_value('title'),'class="form-control"');
            echo '</div>';
            echo '<div class="form-group">';
            echo form_label('Due date:','due');
            echo form_error('due');
            echo form_input('due',set_value('due',date('d-m-Y')), 'class="form-control datepick"');
            echo '</div>';
            echo '<div class="form-group">';
            echo form_submit('','Create new project','class="btn btn-primary btn-block"');
            echo '</div>';
        echo form_close();
        ?>
    </div>
</div>
