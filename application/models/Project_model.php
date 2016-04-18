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
        $this->has_many_pivot['users'] = array('foreign_model'=>'User_model','pivot_table'=>'projects_users','local_key'=>'id','pivot_local_key'=>'project_id','pivot_foreign_key'=>'user_id', 'foreign_key'=>'id', 'get_relate'=>FALSE);
    }

    public function get_user_rights($project_id,$user_id = NULL)
    {
        $this->db->select('projects_users.role');
        if(!isset($user_id))
        {
            $user_id = $_SESSION['user_id'];
        }
        elseif(is_numeric($user_id) && $this->ion_auth->is_admin())
        {
            $user_id = $user_id;
        }
        else
        {
            return FALSE;
        }
        if(isset($user_id)) {
            $this->db->where('projects_users.user_id', $user_id);
        }
        $this->db->join('projects_users','projects.id = projects_users.project_id');
        $this->db->where('projects.id',$project_id);
        $query = $this->db->get('projects');

        if($query->num_rows()>0)
        {
            $result = $query->result_array();
            return explode('_',$result[0]['role']);
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * get_user_projects() retrieves all projects that the user is part of. If you want to retrieve projects of another user you need to be in admin group.
     * @param null $user_id
     * @param null $closed
     * @param array $with_tasks - enumerate the fields you want from tasks table. If you want fields to have specific "as" names, you assign keys to array value
     * @return bool
     */
    public function get_user_projects($user_id = NULL, $closed = NULL, $with_tasks = array())
    {
        $this->db->select('projects.id as project_id, projects.title, projects.user_id as project_leader, projects.due as due_date, projects.updated_at');
        if(!isset($user_id))
        {
            $user_id = $_SESSION['user_id'];
        }
        elseif(is_numeric($user_id) && $this->ion_auth->is_admin())
        {
            $user_id = $user_id;
        }
        else
        {
            return FALSE;
        }
        if(isset($user_id)) {
            $this->db->where('projects_users.user_id', $user_id);
        }
        if(isset($closed))
        {
            $this->db->where('projects.closed',$closed);
        }

        if(!empty($with_tasks))
        {
            $with_tasks['model_task_id'] = 'id';
            $task_fields = array();
            foreach($with_tasks as $key => $field)
            {
                $task_field = 'tasks.'.$field;
                if(!is_numeric($key))
                {
                    $task_field .= ' as '.$key;
                }
                else
                {
                    $key = $field;
                }
                $task_fields[$key] = $task_field;
            }
            $fields = implode(',',$task_fields);
            $this->db->select($fields);
            $this->db->join('tasks','projects.id = tasks.project_id','left');
        }
        $this->db->join('projects_users','projects.id = projects_users.project_id');
        $this->db->join('users','projects_users.user_id = users.id');
        $query = $this->db->get('projects');

        if($query->num_rows()>0)
        {
            if(empty($with_tasks)) {
                return $query->result_array();
            }
            else
            {
                $return_array = array();
                foreach($query->result_array() as $row)
                {
                    if(!array_key_exists($row['project_id'],$return_array))
                    {
                        $return_array[$row['project_id']] = array(
                            'title'=>$row['title'],
                            'project_leader'=>$row['project_leader'],
                            'due_date' => $row['due_date'],
                            'updated_at' => $row['updated_at'],
                            'tasks' => array());
                    }
                    foreach($task_fields as $key => $field)
                    {
                        if(array_key_exists($key,$row) && strlen($row[$key])>0) {
                            $return_array[$row['project_id']]['tasks'][$row['model_task_id']][$key] = $row[$key];
                        }
                    }

                }
                return $return_array;
            }
        }
        else
        {
            return FALSE;
        }
    }
}