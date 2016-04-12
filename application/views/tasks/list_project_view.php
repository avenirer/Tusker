<div class="row">
    <div class="col-lg-12" style="text-align:right">
        <?php echo anchor('projects', 'Back to projects','class="btn btn-primary back-projects"');?>
        <?php echo anchor('tasks/index/'.$project->id,'Active tasks','class="btn btn-primary back-projects"');?>
    </div>
    <div class="col-lg-12">
        <h1>
            <?php echo $project->title;?>
        </h1>
        <h2>Tasks</h2>
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
                <h3><?php echo $task->title;?></h3>
                <div><?php echo $task->details;?></div>
                <?php
                if($ect!==0) {
                ?>
                    <div>Estimated completion time: <?php echo $task->ect; ?></div>
                <?php
                }
                ?>
                <div>Time spent: <?php echo $days.' days, '.$hours.' hours, '.$minutes.' minutes';?></div>
                <div>Status: <?php echo $task->status;?>%</div>
                <div>Closed: <?php echo $task->closed;?></div>
                <div>Last update: <?php echo $task->updated_at;?></div>
                <?php
                if(isset($task->history) && !empty($task->history))
                {
                    echo '<div>History:</div>';
                    foreach($task->history as $event)
                    {
                        echo '<div>'.$event->comment.'</div>';
                    }
                }
                ?>

                <?php
            }

        }
        ?>
    </div>
</div>
