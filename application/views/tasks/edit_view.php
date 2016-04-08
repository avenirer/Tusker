<div class="row">
    <div class="col-lg-12">
        <?php echo form_open();?>
        <div class="col-lg-6">
            <div class="form-group">
                <?php
                echo form_label('Task title:','title');
                echo form_input('title',set_value('title', $task->title),'class="form-control"');
                ?>
            </div>

            <div class="form-group">
                <?php
                echo form_label('Assigned to project:','project_id');
                echo form_dropdown('project_id',$all_user_projects,set_value('project_id',$task->project_id), 'class="form-control"');
                ?>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo form_label('Estimated completion time (ECT):');?>
                </div>
                <?php
                $days = floor($task->ect/86400);
                $hours = floor(($task->ect%86400)/3600);
                $minutes = floor(($task->ect%3600)/60);
                ?>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo form_input('ect_days',set_value('ect_days',$days),'class="form-control"');?><div class="input-group-addon">days</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo form_input('ect_hours',set_value('ect_hours',$hours),'class="form-control"');?><div class="input-group-addon">hours</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo form_input('ect_minutes',set_value('ect_minutes',$minutes),'class="form-control"');?><div class="input-group-addon">minutes</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <?php
                        echo form_label('Due date:','due');
                        echo form_input('due',set_value('due',implode('-', array_reverse(explode('-',$task->due)))), 'class="form-control datepick"'.(isset($project->due) ? ' data-date-end-date="'.implode('-', array_reverse(explode('-',$project->due))).'"' : ''))
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <?php
                echo form_label('Details:','details');
                echo form_textarea(['name'=>'details','rows'=>'8'],set_value('details',$task->details),'class="form-control"');
                ?>
            </div>
        </div>
        <div class="col-lg-12">
            <?php
            echo form_hidden('id',$task->id);
            echo form_submit('','Edit task','class="btn btn-primary btn-block"');
            echo anchor('tasks/index/'.$task->project_id, 'Cancel', 'class="btn btn-default btn-block"');
            ?>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
