<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends MY_Model
{
    public $table = 'projects';
    //public $timestamps = FALSE;
    public function __construct()
    {
        parent::__construct();
        $this->has_one['author'] = array('foreign_model'=>'User_model','foreign_table'=>'users','foreign_key'=>'user_id','local_key'=>'id');
        $this->has_many['tasks'] = array('foreign_model'=>'Task_model','foreign_table'=>'tasks','foreign_key'=>'project_id','local_key'=>'id');
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