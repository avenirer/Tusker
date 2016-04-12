<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-8">
        <h2>Opened projects</h2>
        <table class="table table-striped table-condensed">
            <thead><tr><th>Project name</th><th>Opened</th><th>Time spent</th><th>Closed</th><th>Time spent</th><th>Due</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead>
            <tbody>
                <?php
                if($opened_projects)
                {

                    foreach($opened_projects as $project)
                    {
                        $open_tasks = 0;
                        $closed_tasks = 0;
                        $open_time_spent = 0;
                        $closed_time_spent = 0;
                        if(!empty($project->tasks))
                        {
                            foreach($project->tasks as $task)
                            {
                                $open_closed = ($task->closed=='0') ? 'open' : 'closed';
                                ${$open_closed.'_tasks'} += 1;
                                ${$open_closed.'_time_spent'} += $task->time_spent;
                            }
                        }
                        echo '<tr>';
                        echo '<td>'.anchor('tasks/index/'.$project->id,$project->title).'</td>';
                        echo '<td>'.$open_tasks.'</td>';
                        $days = floor($open_time_spent/86400);
                        $hours = floor(($open_time_spent%86400)/3600);
                        $minutes = floor(($open_time_spent%3600)/60);
                        echo '<td>'.$days.'d '.$hours.'h '.$minutes.'m</td>';
                        echo '<td>'.$closed_tasks.'</td>';
                        $days = floor($closed_time_spent/86400);
                        $hours = floor(($closed_time_spent%86400)/3600);
                        $minutes = floor(($closed_time_spent%3600)/60);
                        echo '<td>'.$days.'d '.$hours.'h '.$minutes.'m</td>';
                        echo '<td>'.(($project->due!=='0000-00-00') ?  implode('-', array_reverse(explode('-',$project->due))) : 'No due date').'</td>';
                        echo '<td>';
                        //echo anchor('tasks/index/'.$project->id,'Tasks','class="btn btn-xs btn-primary"');
                        echo ' '.anchor('tasks/create/'.$project->id,'<span class="glyphicon glyphicon-plus" data-toggle="tooltip" data-placement="top" title="Add task"></span>','');
                        echo '</td>';
                        echo '<td style="text-align:right;">';
                        echo anchor('projects/status/'.$project->id,'<span class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="top" title="Close project"></span>','');
                        echo '</td>';
                        echo '</tr>';
                    }

                }
                ?>
            </tbody>
        </table>
        <h2>Closed projects</h2>
        <table class="table table-striped table-condensed">
            <thead><tr><th>Project name</th><th>Closed tasks</th><th>Time spent</th><th>Last modified</th><th>&nbsp;</th></tr></thead>
            <tbody>
            <?php
            if($closed_projects)
            {

                foreach($closed_projects as $project)
                {
                    $closed_tasks = 0;
                    $closed_time_spent = 0;
                    if(!empty($project->tasks))
                    {
                        foreach($project->tasks as $task)
                        {
                            $closed_tasks += 1;
                            $closed_time_spent += $task->time_spent;
                        }
                    }
                    echo '<tr>';
                    echo '<td>'.anchor('tasks/index/'.$project->id,$project->title).'</td>';
                    echo '<td>'.$closed_tasks.'</td>';
                    $days = floor($closed_time_spent/86400);
                    $hours = floor(($closed_time_spent%86400)/3600);
                    $minutes = floor(($closed_time_spent%3600)/60);
                    echo '<td>'.$days.'d '.$hours.'h '.$minutes.'m</td>';
                    echo '<td>'.implode('-', array_reverse(explode('-',explode(' ',$project->updated_at)[0]))).'</td>';
                    echo '<td style="text-align:right;">';
                    echo anchor('projects/status/'.$project->id,'<span class="glyphicon glyphicon-fire" data-toggle="tooltip" data-placement="top" title="Open project"></span>','');
                    echo '</td>';
                    echo '</tr>';
                }

            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <?php echo form_open();?>
        <div class="form-group">
            <?php
            echo form_label('Project title:','title');
            echo form_error('title');
            echo form_input('title',set_value('title'),'class="form-control"');
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_label('Due date:','due');
            echo form_input('due',set_value('due',date('d-m-Y')), 'class="form-control datepick"');
            ?>
        </div>

        <?php
        echo form_submit('','Create new project','class="btn btn-small btn-primary btn-block"');
        echo form_close();
        ?>
    </div>
</div>