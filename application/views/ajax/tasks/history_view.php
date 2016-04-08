<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<h2>Task history</h2>
<?php
if(!empty($history))
{
    foreach($history as $event)
    {
        echo '<div class="well well-sm">';
        echo $event->comment;
        echo '</div>';
    }
}
/*
echo '<pre>';
print_r($task);
echo '</pre>';
*/