<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends Auth_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('project_model');
    }

    public function index()
    {
        $this->data['projects'] = $this->project_model->where('user_id',$this->current_user->id)->with_tasks('fields:time_spent,closed')->order_by('updated_at,created_at', 'desc')->get_all();

        $this->form_validation->set_rules('title','Title','trim|required');
        $this->form_validation->set_rules('due','Due date','trim');
        if($this->form_validation->run()===false)
        {
            $this->render('projects/index_view');
        }
        else
        {
            $title = $this->input->post('title');
            $due_date = $this->input->post('due');
            $user_id = $this->current_user->id;
            if($this->project_model->where(['title'=>$title, 'user_id'=>$user_id])->get())
            {
                $this->postal->add('A project with that name already exists','error');
            }
            elseif($this->project_model->insert(array('title'=>$title, 'due'=>$due_date, 'user_id'=>$user_id)))
            {
                $this->postal->add('The new project was added to your projects','success');
            }
            else
            {
                $this->postal->add('Couldn\t add the project','error');
            }
            redirect('projects');
        }
    }
    
    public function create()
    {

    }

    public function close($project_id)
    {
        #TODO Closing the project...
    }
}