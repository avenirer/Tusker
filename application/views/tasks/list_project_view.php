<div class="row">
    <div class="col-lg-12" style="text-align:right">
        <?php echo anchor('projects', 'Back to projects','class="btn btn-primary back-projects"');?>
        <?php echo anchor('tasks/index/'.$project['id'],'Active tasks','class="btn btn-primary back-projects"');?>
    </div>
    <div class="col-lg-12">
        <h1>
            <?php echo $project['title'];?>
        </h1>
        <h2>Tasks</h2>
        <?php
        if($project['tasks'])
        {
            foreach($project['tasks'] as $task_id=>$task)
            {
                if(in_array('r+',$rights) || ( in_array('r',$rights) && $task['user_id']==$_SESSION['user_id'])) {
                    $time_spent = $task['time_spent'];
                    $days = floor($time_spent / 86400);
                    $hours = floor(($time_spent % 86400) / 3600);
                    $minutes = floor(($time_spent % 3600) / 60);
                    $seconds = floor(($time_spent % 60));

                    $ect = $task['ect'];
                    $ect_days = floor($ect / 86400);
                    $ect_hours = floor(($ect % 86400) / 3600);
                    $ect_minutes = floor(($ect % 3600) / 60);
                    //$ect_seconds = floor(($ect%60));
                    ?>
                    <div class="row">
                        <div class="col-lg-10">
                            <h3><?php echo $task['title']; ?></h3>
                            <h4>Task details:</h4>
                            <div
                                class="well well-sm"><?php echo(isset($task['details']) ? $task['details'] : 'No details provided'); ?></div>
                        </div>
                        <div class="col-lg-2">
                            <div class="well well-sm text-center" style="color: #337ab7;">
                                <div style="font-size:16px; font-weight:bold;"><?php echo $task['status']; ?>
                                    % <?php echo ($task['closed'] == '1') ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-unchecked"></span>'; ?></div>
                                <div><span class="glyphicon glyphicon-pencil"></span>
                                    <?php
                                    $updated_at = explode(' ', $task['updated_at']);
                                    $updated_at_date = implode('.', array_reverse(explode('-', $updated_at[0])));
                                    $updated_at_time = explode(':', $updated_at[1]);
                                    echo $updated_at_date . ' - ' . $updated_at_time[0] . ':' . $updated_at_time[1];
                                    ?>
                                </div>
                                <?php
                                if ($ect != 0) {
                                    ?>
                                    <div><span
                                            class="glyphicon glyphicon-bullhorn"></span> <?php echo (($ect_days != 0) ? $ect_days . ' days, ' : '') . (($ect_hours != 0) ? $ect_hours . ' hours, ' : '') . (($ect_minutes != 0) ? $ect_minutes . ' minutes' : ''); ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div><span
                                        class="glyphicon glyphicon-time"></span> <?php echo (($days != 0) ? $days . ' days, ' : '') . (($hours != 0) ? $hours . ' hours, ' : '') . (($minutes != 0) ? $minutes . ' minutes' : ''); ?>
                                </div>
                                <?php echo anchor('tasks/unfinish/' . $task_id, 'Reopen', 'class="btn btn-warning btn-block"'); ?>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <?php
                }
            }

        }
        ?>
    </div>
</div>
