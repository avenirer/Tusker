<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends MY_Model
{
    public $table = 'tasks';
    //public $timestamps = FALSE;
    public function __construct()
    {
        parent::__construct();
        $this->has_one['project'] = array('foreign_model'=>'Project_model','foreign_table'=>'projects','foreign_key'=>'id','local_key'=>'project_id');
        $this->has_many['history'] = array('foreign_model'=>'Task_history_model','foreign_table'=>'task_histories','foreign_key'=>'task_id','local_key'=>'id');
        $this->has_one['user'] = array('foreign_model'=>'User_model','foreign_table'=>'users','foreign_key'=>'id','local_key'=>'user_id');
    }

    public $rules = array(
        'update' => array(
            'title' => array('field'=>'title','label'=>'Title','rules'=>'trim|required'),
            'page_title' => array('field'=>'page_title','label'=>'Page title','rules'=>'trim'),
            'admin_email' => array('field'=>'admin_email','label'=>'Admin email','rules'=>'trim|valid_email|required'),
            'contact_email' => array('field'=>'contact_email', 'label'=>'Contact email', 'rules'=>'trim|valid_email')
        )
    );
}