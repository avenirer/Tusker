<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-6">
        <h2>Latest projects</h2>
        <?php
        if($opened_projects)
        {
        ?>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Project</th><th>Due Date</th><th>Unfinished tasks</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($opened_projects as $project_id => $project) :
            ?>
                <tr<?php echo ($project['due_date'] !== '0000-00-00' && (date('Y-m-d')>=$project['due_date']) ? ' class="'.((date('Y-m-d')==$project['due_date']) ? 'warning' : 'danger').'"' : '' );?>>
                    <td><?php echo anchor('tasks/index/'.$project_id, $project['title']);?></td>
                    <td><?php echo (($project['due_date']!=='0000-00-00') ? $project['due_date'] : 'No due date');?></td>
                    <td><?php
                        $unfinished_tasks = 0;
                        if(!empty($project['tasks']))
                        {
                            foreach($project['tasks'] as $task)
                            {
                                if($task['closed']=='0')
                                {
                                    $unfinished_tasks++;
                                }
                            }
                        }
                        echo $unfinished_tasks;
                        ?>
                    </td>
                </tr>
            <?php
            endforeach;
            ?>
            </tbody>
        </table>
        <?php
            
        }
        else
        {
            echo 'No projects started yet...';
        }
        ?>
    </div>
    <div class="col-lg-6">
        <h2>Latest pressing tasks</h2>
        <?php
        if($latest_tasks)
        {
            ?>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>Task</th><th>Due Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($latest_tasks as $task) :
                    ?>
                    <tr<?php echo ($task->due !== '0000-00-00' && (date('Y-m-d')>=$task->due) ? ' class="'.((date('Y-m-d')==$task->due) ? 'warning' : 'danger').'"' : '' );?>>
                        <td><?php echo anchor('tasks/index/'.$task->project_id, $task->title);?></td>
                        <td><?php echo (($task->due!=='0000-00-00') ? $task->due : 'No due date');?></td>
                    </tr>
                    <?php
                endforeach;
                ?>
                </tbody>
            </table>
            <?php

        }
        else
        {
            echo 'No pressing tasks yet...';
        }
        ?>
    </div>
</div>