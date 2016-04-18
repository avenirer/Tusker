<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends Auth_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('project_model');
        $this->load->model('task_model');
        $this->load->model('project_user_model');
    }

    public function index()
    {
        $this->data['opened_projects'] = $this->project_model->get_user_projects(NULL, 0, ['time_spent','closed']);

        $this->data['closed_projects'] = $this->project_model->get_user_projects(NULL, 1, ['time_spent','closed']);

        $this->render('projects/index_view');
    }

    public function create()
    {
        $this->form_validation->set_rules('title','Title','trim|required');
        $this->form_validation->set_rules('due','Due date','trim');

        if($this->form_validation->run()===false)
        {
            $this->render('projects/create_view');
        }
        else
        {
            $title = $this->input->post('title');
            $due_date = implode('-',array_reverse(explode('-',$this->input->post('due'))));
            $user_id = $this->current_user->id;
            if($this->project_model->where(['title'=>$title, 'user_id'=>$user_id])->get())
            {
                $this->postal->add('A project with that name already exists','error');
                redirect('projects');
            }
            elseif($project_id = $this->project_model->insert(array('title'=>$title, 'due'=>$due_date, 'user_id'=>$user_id)))
            {
                if($this->project_user_model->insert(array('user_id'=>$_SESSION['user_id'], 'project_id'=>$project_id, 'role'=>'l_r+_w+_c','status'=>'1')))
                {
                    $this->postal->add('The new project was added to your projects','success');
                }
                else {
                    $this->project_model->delete($project_id);
                    $this->postal->add('A problem was encountered while adding the project', 'error');
                }
            }
            else
            {
                $this->postal->add('Couldn\t add the project','error');
            }
            redirect('projects/details/'.$project_id);
        }
    }
    
    public function details($project_id)
    {
        if(!isset($project_id) || !is_numeric($project_id))
        {
            redirect('projects');
        }
        $project = $this->project_model->get($project_id);
        if($project === FALSE)
        {
            redirect('projects');
        }
        $user_rights = $this->project_model->get_user_rights($project->id);

        if($project === FALSE || $user_rights === FALSE)
        {
            redirect('projects');
        }

        $members = $this->project_user_model->where(array('project_id'=>$project_id))->with_user('fields:email,username')->get_all();

        $this->data['project_members'] = $members;
        $this->data['project'] = $project;
        $this->data['rights'] = $user_rights;
        $this->render('projects/details_view');
    }

    public function add_members()
    {
        $this->form_validation->set_rules('project_id','Project ID','trim|is_natural|required');
        $this->form_validation->set_rules('email','Email','trim|valid_email|exists[users.email]|required');
        $this->form_validation->set_rules('read_rights','Read rights','trim|in_list[-,r,r+]|required');
        $this->form_validation->set_rules('write_rights','Write rights','trim|in_list[-,w,w+]|required');

        $project_id = $this->input->post('project_id');

        if(!isset($project_id) || !is_numeric($project_id))
        {
            redirect('projects');
        }
        $project = $this->project_model->get($project_id);
        $user_rights = $this->project_model->get_user_rights($project->id);

        if($project === FALSE || $user_rights === FALSE)
        {
            redirect('projects');
        }

        if($this->form_validation->run()===FALSE)
        {
            $this->data['project'] = $project;
            $this->render('projects/add_members_view');
        }

        else {
            if (!in_array('l', $user_rights)) {
                $this->postal->add('You are not allowed to add members to project', 'error');
                redirect('projects/details/'.$project_id);
            }
            else
            {
                $email = $this->input->post('email');
                $read_rights = $this->input->post('read_rights');
                $write_rights = $this->input->post('write_rights');

                $this->load->model('user_model');
                $member = $this->user_model->where('email',$email)->get();
                if($member===FALSE)
                {
                    $this->postal->add('The email address is not registered','error');
                    redirect('projects/details/'.$project_id);
                }
                elseif($member->id == $_SESSION['user_id'])
                {
                    $this->postal->add('You can\'t add yourself as a member of your own project... Why would you?','error');
                    redirect('projects/details/'.$project_id);
                }
                elseif($this->project_user_model->where(array('user_id'=>$member->id,'project_id'=>$project_id))->get())
                {
                    $this->postal->add('The user is already a member of the project','error');
                    redirect('projects/details/'.$project_id);
                }
                $the_rights = array();
                if($read_rights!='-') $the_rights[] = $read_rights;
                if($write_rights!='-') $the_rights[] = $write_rights;

                $the_rights = implode('_',$the_rights);
                if($this->project_user_model->insert(array('user_id'=>$member->id,'project_id'=>$project_id,'role'=>$the_rights)))
                {
                    $this->postal->add('The member was added successfuly to the project','success');
                }
                else
                {
                    $this->postal->add('Couldn\'t add the member to the project','error');
                }
                redirect('projects/details/'.$project_id);
            }
        }



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