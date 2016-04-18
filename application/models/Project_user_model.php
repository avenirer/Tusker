<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project_user_model extends MY_Model
{
    public $table = 'projects_users';
    //public $timestamps = FALSE;
    public function __construct()
    {
        parent::__construct();
        $this->has_one['user'] = array('foreign_model'=>'User_model','foreign_table'=>'users','foreign_key'=>'id','local_key'=>'user_id');
    }
}