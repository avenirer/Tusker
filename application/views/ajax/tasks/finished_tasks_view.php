<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<table class="table table-striped table-condensed">
    <thead><tr><th>Task</th></tr></thead>
    <tbody>
    <?php
    if(!empty($project->tasks))
    {
        foreach($project->tasks as $task)
        {
            $time_spent = $task->time_spent;
            $days = floor($time_spent/86400);
            $hours = floor(($time_spent%86400)/3600);
            $minutes = floor(($time_spent%3600)/60);
            $seconds = floor(($time_spent%60));
            ?>
            <tr>
                <td>
                    <div>
                        <?php echo $task->title;?> (ECT: ...)
                        <div style="text-align:right;"><a href="<?php echo base_url().'tasks/unfinish/'.$task->id;?>" class="not-finished btn btn-warning btn-sm" data-target="<?php echo $task->id;?>">Not really finished...</a> <?php echo anchor('#','Details','class="btn btn-primary btn-sm details" data-target="'.$task->id.'"');?></div>
                    </div>
                    <div class="details-show" style="display:none;">
                        <div class="panel panel-default" style="margin: 20px 0;">
                            <div class="panel-body" style="text-align:left;">
                                <?php echo nl2br($task->details);?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>