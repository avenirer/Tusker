<div class="row">
    <style>
        .timer_container .timer, .timer_container .timer .days, .timer_container .timer .hours, .timer_container .timer .minutes, .timer_container .timer .seconds {font-size:16px; line-height: 16px; -webkit-transition: all 1s ease-in-out;
            -moz-transition: all 1s ease-in-out;
            -o-transition: all 1s ease-in-out;
            transition: all 1s ease-in-out;}
        .timer_container.active .timer, .timer_container.active .timer .days, .timer_container.active .timer .hours, .timer_container.active .timer .minutes, .timer_container.active .timer .seconds {color:#5cb85c; line-height:120px;}
        .timer_container.active .timer .days {font-size: 120px;}
        .timer_container.active .timer .hours {font-size: 80px;}
        .timer_container.active .timer .minutes {font-size: 40px;}
        .timer_container.active .timer .seconds {font-size:16px; color: #b4ceb4;}
        .add-task {-webkit-transition: all 1s ease-in-out; -moz-transition: all 1s ease-in-out; -o-transition: all 1s ease-in-out; transition: all 1s ease-in-out;}
    </style>
    <div class="col-lg-12"><h1><?php echo $project->title;?></h1></div>
    <div class="col-lg-12" style="text-align:right">
        <?php echo anchor('projects', 'Back to projects','class="btn btn-primary back-projects"');?>
        <?php echo anchor('tasks/list-project/'.$project->id,'List all tasks','class="btn btn-primary back-projects"');?>
    </div>
    <div class="col-lg-7">
        <h2>Open tasks</h2>
        <table class="table table-striped table-condensed">
            <thead><tr><th style="max-width: 150px;">&nbsp;</th><th>Task</th></tr></thead>
            <tbody>
    <?php
    if($project->tasks)
    {

        foreach($project->tasks as $task)
        {
            $time_spent = $task->time_spent;
            $days = floor($time_spent/86400);
            $hours = floor(($time_spent%86400)/3600);
            $minutes = floor(($time_spent%3600)/60);
            $seconds = floor(($time_spent%60));

            $ect = $task->ect;
            $ect_days = floor($ect/86400);
            $ect_hours = floor(($ect%86400)/3600);
            $ect_minutes = floor(($ect%3600)/60);
            //$ect_seconds = floor(($ect%60));
            ?>
                <tr>
                    <td><input type="text" value="<?php echo $task->status;?>" id="knob_<?php echo $task->id;?>" class="dial" data-width="100" data-height="100" data-step="5" data-displayPrevious="true" data-fgcolor="#337ab7" data-angleOffset="-125" data-anglearc="250" /></td>
                    <td>
                        <div id="countup<?php echo $task->id;?>" class="timer_container">
                            <?php echo $task->title;?> <span class="glyphicon glyphicon-time" data-toggle="tooltip" data-placement="top" title="<?php echo $ect_days.'d '.$ect_hours.'h '.$ect_minutes.'m';?>">&nbsp;</span><span class="label label-primary">Due: <?php echo implode('-', array_reverse(explode('-',$task->due)));?></span>
                            <div class="timer"><span class="days"><?php echo $days;?></span>d <span class="hours"><?php echo (($hours<10) ? '0'.$hours : $hours);?></span>h <span class="minutes"><?php echo (($minutes<10) ? '0'.$minutes : $minutes);?></span>m <span class="seconds"><?php echo (($seconds<10) ? '0'.$seconds : $seconds);?></span>s</div>
                            <div style="text-align:right;"><a href="#" class="start btn btn-success btn-sm" data-target="<?php echo $task->id;?>">Start</a> <a href="#" class="stop btn btn-danger btn-sm" data-target="<?php echo $task->id;?>" style="display:none;">Stop</a> <?php echo anchor('#','Finished','class="btn btn-info btn-sm finished" data-target="'.$task->id.'" style="display:none;"');?> <?php echo anchor('#','Details','class="btn btn-primary btn-sm details" data-target="'.$task->id.'"');?></div>
                        </div>
                        <div class="details-show" style="display:none; text-align:right;">
                            <div class="panel panel-default" style="margin: 20px 0;">
                                <div class="panel-body" style="text-align:left;">
                                    <?php echo nl2br($task->details);?>
                                </div>
                            </div>
                            <?php echo anchor('tasks/edit/'.$task->id,'Edit task details','class="btn btn-primary edit-task"');?>
                        </div>
                    </td>
                </tr>
            <?php
        }

    }
    ?>
            </tbody>
        </table>
    </div>
    <?php
    if(in_array('w',$rights) || in_array('w+',$rights)) {
        ?>
        <div class="col-lg-5">
            <div class="add-task">
                <h2>Add task</h2>
                <div class="row">
                    <?php
                    echo form_open('tasks/create');
                    ?>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php
                            echo form_label('Task title:', 'title');
                            echo form_input('title', set_value('title'), 'class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <?php echo form_label('Estimated completion time (ECT):'); ?>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('ect_days', set_value('ect_days', 0), 'class="form-control"'); ?>
                                <div class="input-group-addon">days</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('ect_hours', set_value('ect_hours', 0), 'class="form-control"'); ?>
                                <div class="input-group-addon">hours</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('ect_minutes', set_value('ect_minutes', 0), 'class="form-control"'); ?>
                                <div class="input-group-addon">minutes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php
                            echo form_label('Due date:', 'due');
                            echo form_input('due', set_value('due', date('d-m-Y')), 'class="form-control datepick"' . (isset($project->due) ? ' data-date-end-date="' . implode('-', array_reverse(explode('-', $project->due))) . '"' : ''));
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php
                            echo form_label('Details:', 'details');
                            echo form_textarea(['name' => 'details', 'rows' => '5'], set_value('details'), 'class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        echo form_hidden('project_id', $project->id);
                        echo form_submit('', 'Create new task', 'class="btn btn-small btn-primary"');
                        ?>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
            <div id="task-history" style="display:none;">
                <h2>Task history</h2>
                <?php echo form_open('#', 'id="add-comment"', ['task_id' => '']); ?>
                <div class="form-group">
                    <?php echo form_label('Add comment', 'comment'); ?>
                    <?php echo form_textarea(['name' => 'comment', 'rows' => '5'], '', 'class="form-control"'); ?>
                    <?php /* echo form_hidden('task_id','');*/
                    ?>
                </div>
                <?php echo form_submit('', 'Save comment', 'class="btn btn-small btn-primary btn-block"'); ?>
                <div class="history"></div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="col-lg-12">
        <h2>Closed tasks</h2>
        <div class="finished-tasks">
        </div>
    </div>
</div>
