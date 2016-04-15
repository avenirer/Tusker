<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
    public $table = 'users';
    //public $timestamps = FALSE;
    public function __construct()
    {
        parent::__construct();
        $this->has_many['tasks'] = array('foreign_model'=>'Task_model','foreign_table'=>'tasks','foreign_key'=>'user_id','local_key'=>'id');
        $this->has_many_pivot['projects'] = array('foreign_model'=>'Project_model','pivot_table'=>'projects_users','local_key'=>'id','pivot_local_key'=>'user_id','pivot_foreign_key'=>'project_id', 'foreign_key'=>'id', 'get_relate'=>FALSE);
    }
}