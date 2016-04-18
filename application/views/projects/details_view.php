<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-12">
        <h2><?php echo $project->title;?></h2>
        Due date: <?php echo $project->due;?><br />
        Your rights regarding this project<br />
        <?php
        foreach($rights as $right)
        {
            switch($right) {
                case 'l':
                    echo '<strong>You are the project leader</strong><br />';
                    break;
                case 'r':
                    echo 'You have the right to read your own tasks<br />';
                    break;
                case 'r+':
                    echo 'You have the right to read all members\' tasks<br />';
                    break;
                case 'w':
                    echo 'You have the right to create your own tasks<br />';
                    break;
                case 'w+':
                    echo 'You have the right to assign tasks to other members and comment on them<br />';
                    break;
            }
        }
        ?>
    </div>
    <div class="col-lg-6">
        <table class="table table-striped table-condensed">
            <thead>
                <tr><th>User email</th><th>Username</th><th>Rights</th></tr>
            </thead>
            <tbody>
            <?php
            if(!empty($project_members))
            {
                foreach($project_members as $member)
                {
                    echo '<tr>';
                    echo '<td>'.$member->user->email.'</td>';
                    echo '<td>'.$member->user->username.'</td>';
                    echo '<td>'.$member->role.'</td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-lg-6">
        <?php
        if(in_array('l',$rights))
        {
            echo '<h3>Add members</h3>';
            echo form_open('projects/add-members');
            echo '<div class="form-group">';
            echo form_label('Email:','email');
            echo form_error('email');
            echo form_input('email',set_value('email'),'class="form-control"');
            $read_rights = array('-'=>'No reading rights','r'=>'Read own tasks','r+'=>'Read all members\' tasks');
            $write_rights = array('-'=>'No writing rights', 'w'=>'Create own tasks','w+'=>'Assign tasks to other members');

            echo '<div class="form-group">';
            echo form_label('Read rights:','read_rights');
            echo form_error('read_rights');
            echo form_dropdown('read_rights',$read_rights,set_value('read_rights'),'class="form-control"');
            echo '</div>';

            echo '<div class="form-group">';
            echo form_label('Write rights:','write_rights');
            echo form_error('write_rights');
            echo form_dropdown('write_rights',$write_rights,set_value('write_rights'),'class="form-control"');
            echo '</div>';

            echo form_error('project_id');
            echo form_hidden('project_id',$project->id);
            echo form_submit('submit','Add member', 'class="btn btn-primary btn-block"');
            echo form_close();
        }
        ?>
    </div>
</div>
