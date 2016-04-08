<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends Auth_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('project_model');
        $this->load->model('task_model');
    }

    public function index()
    {
        $this->data['opened_projects'] = $this->project_model->where(['user_id'=>$_SESSION['user_id'],'closed'=>'0'])->with_tasks('fields:time_spent,closed')->order_by('updated_at,created_at', 'desc')->get_all();

        $this->data['closed_projects'] = $this->project_model->where(['user_id'=>$_SESSION['user_id'],'closed'=>'1'])->with_tasks('fields:time_spent,closed')->order_by('updated_at,created_at', 'desc')->get_all();

        $this->form_validation->set_rules('title','Title','trim|required');
        $this->form_validation->set_rules('due','Due date','trim');
        if($this->form_validation->run()===false)
        {
            $this->render('projects/index_view');
        }
        else
        {
            $title = $this->input->post('title');
            $due_date = implode('-',array_reverse(explode('-',$this->input->post('due'))));
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

    public function status($project_id)
    {
        $project = $this->project_model->where(['user_id'=>$_SESSION['user_id'],'id'=>$project_id])->get();
        if($project === FALSE)
        {
            $this->postal->add('You are not allowed to alter that project','error');
            redirect();
        }
        else
        {
            $status = $project->closed;
            $new_status = ($status == '1') ? '0' : '1';
            if(($this->project_model->update(['closed'=>$new_status],$project_id)!==FALSE))
            {
                $this->postal->add('The project has been '.(($new_status=='1') ? 'closed' : 'opened'),'success');

                if($new_status=='1')
                {
                    if(($this->task_model->where('project_id',$project_id)->update(['closed'=>$new_status])!==FALSE)) {
                        $this->postal->add('The tasks of this project have also been closed', 'success');
                    }
                    else
                    {
                        $this->postal->add('The tasks however couldn\'t be closed','error');
                    }
                }
            }
            else
            {
                $this->postal->add('There was a problem when trying to '.(($new_status=='1') ? 'close' : 'open').' project','error');
            }
        }
        redirect('projects');
    }
}