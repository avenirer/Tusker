<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-6">
        <h2>Latest projects</h2>
        <?php
        if($latest_projects)
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
            foreach($latest_projects as $project) :
            ?>
                <tr<?php echo ($project->due !== '0000-00-00' && (date('Y-m-d')>=$project->due) ? ' class="'.((date('Y-m-d')==$project->due) ? 'warning' : 'danger').'"' : '' );?>>
                    <td><?php echo anchor('tasks/index/'.$project->id, $project->title);?></td>
                    <td><?php echo (($project->due!=='0000-00-00') ? $project->due : 'No due date');?></td>
                    <td><?php echo (!empty($project->tasks)) ? $project->tasks[0]->counted_rows : '0';?></td>
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
                    <tr<?php echo ((date('Y-m-d')>=$task->due) ? ' class="'.((date('Y-m-d')==$task->due) ? 'warning' : 'danger').'"' : '' );?>>
                        <td><?php echo anchor('tasks/index/'.$task->project_id, $task->title);?></td>
                        <td><?php echo $task->due;?></td>
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
</div>