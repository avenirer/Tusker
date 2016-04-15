<div class="row">
    <div class="col-lg-12"><h2>Reports</h2></div>
    <?php echo form_open();?>
        <div class="col-lg-3">
            <div class="form-group">
                <?php
                echo form_label('Project:','project_id');
                echo form_dropdown('project_id',$project_select,set_value('project_id'), 'class="form-control"');
                ?>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <?php
                echo form_label('Start date:','start_date');
                echo form_input('start_date',set_value('start_date', date('d-m-Y')), 'class="form-control datepick"');
                ?>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <?php
                echo form_label('End date:','end_date');
                echo form_input('end_date',set_value('end_date', date('d-m-Y')), 'class="form-control datepick"');
                ?>
            </div>
        </div>
        <div class="col-lg-9">
            <?php
            echo form_submit('report','Create report','class="btn btn-small btn-block btn-primary"');
            ?>
        </div>
    <?php
    echo form_close();
    ?>
<?php
if(isset($work)) {
    ?>
    <div class="col-lg-12"><h3>Activity report</h3>
    <?php
    foreach($work as $activity)
    {
        echo '<div class="well well-sm">';
        echo '<h4>'.$activity->title.'<span class="label label-primary pull-right">'.$activity->project->title.'</span></h4>';
        if(strlen($activity->details)>0)
        {
            echo '<p>'.$activity->details.'</p>';
        }
        if(!empty($activity->history))
        {
            echo '<table class="table table-striped table-bordered">';
            echo '<tbody>';
            foreach($activity->history as $comment)
            {
                echo '<tr>';
                echo '<td>'.$comment->created_at.'</td>';
                echo '<td>'.$comment->comment.'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        echo '</div>';
    }
}
?>
    </div>
</div>
