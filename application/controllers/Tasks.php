<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends Auth_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('task_model');
        $this->load->model('task_history_model');
        $this->load->model('project_model');
    }


    public function index($project_id = NULL)
    {

        if(!isset($project_id) || $this->form_validation->is_natural_no_zero($project_id)===FALSE)
        {
            $this->postal->add('The project doesn\'t exist','error');
            redirect('projects');
        }
        $project = $this->project_model->where('user_id',$this->current_user->id)->with_tasks('fields:id,title,ect,due,time_spent,details,status,closed|order_inside:updated_at DESC|where:`closed`=\'0\'')->get($project_id);
        if($project===FALSE)
        {
            redirect('projects');
        }
        $this->data['project'] = $project;
        $this->data['before_closing_body'] = $this->load->view('tasks/timer_view.php',$this->data,TRUE);
        $this->render('tasks/index_view');
    }

    public function list_project($project_id = NULL)
    {

        if(!isset($project_id) || $this->form_validation->is_natural_no_zero($project_id)===FALSE)
        {
            $this->postal->add('The project doesn\'t exist','error');
            redirect('projects');
        }
        $project = $this->project_model->where('user_id',$this->current_user->id)->with_tasks(array('fields'=>'id,title,ect,due,time_spent,details,status,closed,updated_at', 'order_inside'=>'updated_at DESC', 'with'=>array('relationship'=>'history','fields'=>'comment,created_at','order_inside'=>'created_at DESC')))->get($project_id);

        if($project===FALSE)
        {
            redirect('projects');
        }
        $this->data['project'] = $project;
        $this->render('tasks/list_project_view');
    }

    public function get_finished_tasks($project_id)
    {
        $this->data['project'] = $this->project_model->where('user_id',$this->current_user->id)->with_tasks('fields:id,title,ect,due,time_spent,details,status,closed|order_inside:updated_at DESC|where:`closed`=\'1\'')->get($project_id);

        if($this->input->is_ajax_request())
        {
            if($this->data['project'])
            {
                $this->load->view('ajax/tasks/finished_tasks_view',$this->data);
            }
            else
            {
                echo 'No finished tasks';
            }
        }
    }
    
    public function create($project_id = NULL)
    {
        $this->form_validation->set_rules('title','Title','trim|required');
        $this->form_validation->set_rules('project_id','Project ID','trim|is_natural_no_zero|required');
        $this->form_validation->set_rules('ect_days','ECT days','trim|is_natural');
        $this->form_validation->set_rules('ect_hours','ECT hours','trim|is_natural');
        $this->form_validation->set_rules('ect_minutes','ECT minutes','trim|is_natural');
        $this->form_validation->set_rules('due','Due date','trim');
        $this->form_validation->set_rules('details','ECT minutes','trim');
        if($this->form_validation->run()===FALSE)
        {
            $project_id = (isset($project_id) ? $project_id : $this->input->post('project_id'));
            if(!isset($project_id))
            {
                redirect('projects');
            }
            $this->data['project'] = $this->project_model->where(['user_id'=>$_SESSION['user_id'],'id'=>$project_id])->get();
            $this->render('tasks/create_view');
        }
        elseif($this->project_model->where(['user_id'=>$_SESSION['user_id'],'id'=>$this->input->post('project_id')])->get()===FALSE)
        {
            $this->postal->add('You don\'t have the right to modify that project or add tasks to it');
            redirect('tasks/index/'.$project_id);
        }
        else
        {
            $title = $this->input->post('title');
            $project_id = $this->input->post('project_id');
            $ect = ($this->input->post('ect_days')*86400) + ($this->input->post('ect_hours')*3600) + ($this->input->post('ect_minutes')*60);
            $due_date = $this->input->post('due');
            $due_date = implode('-', array_reverse(explode('-',$due_date)));
            $details = strip_tags($this->input->post('details'));
            if($this->task_model->where(['title'=>$title, 'user_id'=>$_SESSION['user_id'], 'project_id'=>$project_id])->get())
            {
                $this->postal->add('A task with that name already exists','error');
            }
            elseif($this->task_model->insert(array('title'=>$title, 'project_id'=>$project_id, 'ect'=>$ect, 'due'=>$due_date, 'details'=>$details, 'user_id'=>$_SESSION['user_id'])) && $this->project_model->update(array('updated_at'=>date('Y-m-d H:i:s'), 'updated_by'=>$_SESSION['user_id']),$project_id))
            {
                $this->postal->add('The new task was added to your project','success');
            }
            else
            {
                $this->postal->add('Couldn\'t add the task','error');
            }
            redirect('tasks/index/'.$project_id);
        }

    }

    public function edit($task_id)
    {
        $task = $this->task_model->get($task_id);
        if($task===FALSE)
        {
            $this->postal->add('The task doesn\'t exist','error');
            redirect('projects');
        }
        $project = $this->project_model->get($task->project_id);
        $this->data['project'] = $project;
        if($project===FALSE)
        {
            $this->postal->add('The project doesn\'t exist','error');
            redirect('projects');
        }
        if($task->user_id !== $_SESSION['user_id'] || $project->user_id!==$_SESSION['user_id'])
        {
            $this->postal->add('You don\'t have the right to access this task','error');
            redirect('projects');
        }

        $this->form_validation->set_rules('id','Task ID','trim|is_natural_no_zero|required');
        $this->form_validation->set_rules('title','Task title','trim|required');
        $this->form_validation->set_rules('project_id','Project','trim|is_natural_no_zero');
        $this->form_validation->set_rules('ect_days','ECT days','trim|is_natural|required');
        $this->form_validation->set_rules('ect_hours','ECT hours','trim|is_natural|required');
        $this->form_validation->set_rules('ect_minutes','ECT minutes','trim|is_natural|required');
        $this->form_validation->set_rules('due','Due date','trim|required');
        $this->form_validation->set_rules('details','Details','trim');

        if($this->form_validation->run()===FALSE) {
            $this->data['all_user_projects'] = $this->project_model->where('user_id', $_SESSION['user_id'])->as_dropdown('title')->order_by('title', 'ASC')->get_all();
            $this->data['task'] = $task;
            $this->render('tasks/edit_view');
        }
        else
        {
            $task_id = $this->input->post('id');
            $title = $this->input->post('title');
            $project_id = $this->input->post('project_id');
            $ect = ($this->input->post('ect_days')*86400) + ($this->input->post('ect_hours')*3600) + ($this->input->post('ect_minutes')*60);
            $due_date = $this->input->post('due');
            $due_date = implode('-', array_reverse(explode('-',$due_date)));
            $details = $this->input->post('details');
            if($this->task_model->where(array('id'=>$task_id, 'user_id'=>$_SESSION['user_id']))->get()===FALSE)
            {
                $this->postal->add('You are not allowed to modify the task','error');
                redirect('projects');
            }
            if($this->task_model->update(array('title'=>$title,'project_id'=>$project_id,'ect'=>$ect,'due'=>$due_date,'details'=>$details),$task_id))
            {
                $this->postal->add('The task has been modified','success');
                redirect('tasks/index/'.$project_id);
            }
            else
            {
                $this->postal->add('Couldn\'t modify the task','error');
                redirect('tasks/index/'.$task->project_id);
            }
        }


    }
    
    

    public function update_time()
    {
        if($this->input->is_ajax_request()===FALSE)
        {
            redirect('projects');
        }
        $this->form_validation->set_rules('task_id','Task ID','trim|is_natural_no_zero|required');
        $this->form_validation->set_rules('time_spent','Time spent','trim|is_natural_no_zero|required');
        $this->form_validation->set_rules('status','Status','trim|is_natural');
        if($this->form_validation->run()===FALSE)
        {
            $message = $this->form_validation->error_string();
        }
        else
        {
            $task_id = $this->input->post('task_id');
            $time_spent = $this->input->post('time_spent');
            $user_id = $_SESSION['user_id'];
            $status = $this->input->post('status');
            $task = $this->task_model->where(['user_id'=>$user_id, 'id'=>$task_id])->get();
            if($task===FALSE)
            {
                $message = 'You don\'t have the right to change the task';
            }
            else
            {
                $update_data = array();
                $update_data['time_spent'] = $time_spent;
                $update_data['status'] = $status;
                if($status=="100")
                {
                    $update_data['closed'] = '1';
                }
                if($this->task_model->update($update_data,$task_id) && $this->project_model->update(array('updated_at'=>date('Y-m-d H:i:s'), 'updated_by'=>$_SESSION['user_id']),$task->project_id))
                {
                    $message = 'Task updated';
                }
                else
                {
                    $message = 'Couldn\'t save the task...';
                }
            }
        }
        $data = array('message'=>$message);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function update_status()
    {
        if($this->input->is_ajax_request()===FALSE)
        {
            redirect('projects');
        }
        $this->form_validation->set_rules('task_id','Task ID','trim|is_natural_no_zero|required');
        $this->form_validation->set_rules('status','Status','trim|is_natural');
        if($this->form_validation->run()===FALSE)
        {
            $message = $this->form_validation->error_string();
        }
        else
        {
            $task_id = $this->input->post('task_id');
            $user_id = $_SESSION['user_id'];
            $status = $this->input->post('status');
            $task = $this->task_model->where(['user_id'=>$user_id, 'id'=>$task_id])->get();
            if($task===FALSE)
            {
                $message = 'You don\'t have the right to change the task';
            }
            else
            {
                $update_data = array();
                $update_data['status'] = $status;
                if($status=='100')
                {
                    $update_data['closed'] = '1';
                }
                if($this->task_model->update($update_data,$task_id) && $this->project_model->update(array('updated_at'=>date('Y-m-d H:i:s'), 'updated_by'=>$_SESSION['user_id']),$task->project_id))
                {
                    $message = 'Task updated';
                }
                else
                {
                    $message = 'Couldn\'t save the task...';
                }
            }
        }
        $data = array('message'=>$message);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function unfinish($task_id)
    {
        if($this->form_validation->is_natural_no_zero($task_id)===FALSE)
        {
            redirect('projects');
        }

        $task_id = (int) $task_id;
        $user_id = $_SESSION['user_id'];
        $task = $this->task_model->where(['user_id'=>$user_id, 'id'=>$task_id])->get();
        if($task===FALSE)
        {
            $this->postal->add('You don\'t have the right to change the task','error');
        }
        else
        {
            if($this->task_model->update(['status'=>'95','closed'=>'0'],$task_id) && $this->project_model->update(array('updated_at'=>date('Y-m-d H:i:s'), 'updated_by'=>$_SESSION['user_id']),$task->project_id))
            {
                $this->postal->add('I don\'t understand how can a task be finished and then... unfinished. But, OK.','success');
            }
            else
            {
                $this->postal->add('Couldn\'t un-finish the task...','error');
            }
        }
        redirect('tasks/index/'.$task->project_id);

    }

    public function task_history($task_id = NULL)
    {
        $this->form_validation->set_rules('task_id','Task ID','trim|is_natural_no_zero|required');
        if($this->form_validation->run()===FALSE)
        {
            redirect('projects');
        }
        $task_id = isset($task_id) ? (int) $task_id : $this->input->post('task_id');
        $user_id = $_SESSION['user_id'];
        $task = $this->task_model->where(['user_id'=>$user_id, 'id'=>$task_id])->get();
        if($task===FALSE)
        {
            $this->postal->add('You don\'t have the right to change the task','error');
            redirect('project');
        }

        if($this->input->is_ajax_request())
        {

            $task_history = $this->task_history_model->where(['task_id'=>$task_id])->order_by('created_at', 'DESC')->get_all();
            $data['history'] = $task_history;
            $history = $this->load->view('ajax/tasks/history_view',$data,TRUE);
            $data = array('history'=>$history);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    }

    public function add_history($task_id = NULL)
    {
        $this->form_validation->set_rules('task_id','Task ID','trim|is_natural_no_zero|required');
        $this->form_validation->set_rules('comment','Comment','trim|required');
        $this->form_validation->set_rules('time_spent','Time spent','trim|is_natural|required');
        $this->form_validation->set_rules('status','Status','trim|is_natural|required');
        if($this->form_validation->run()===FALSE)
        {
            if($this->input->is_ajax_request()) {
                $data['errors'] = $this->form_validation->error_string();
            }
        }
        $task_id = isset($task_id) ? (int) $task_id : $this->input->post('task_id');
        $user_id = $_SESSION['user_id'];
        $task = $this->task_model->where(['user_id'=>$user_id, 'id'=>$task_id])->get();
        if($task===FALSE)
        {
            if($this->input->is_ajax_request()) {
                $data['errors'] = 'You don\'t have the right to change the task';
            }
            else {
                $this->postal->add('You don\'t have the right to change the task', 'error');
                return true;
            }
        }
        else {
            $task_id = $this->input->post('task_id');
            $comment = $this->input->post('comment');
            $time_spent = $this->input->post('time_spent');
            $status = $this->input->post('status');
            if($this->task_history_model->insert(['task_id'=>$task_id,'comment'=>$comment,'time_spent'=>$time_spent,'status'=>$status,'user_id'=>$user_id])===FALSE)
            {
                $data['errors'] = 'Couldn\'t add the comment...';
            }


        }

        if($this->input->is_ajax_request())
        {

            $task_history = $this->task_history_model->where(['task_id'=>$task_id])->order_by('created_at', 'DESC')->get_all();
            $data['history'] = $task_history;
            $history = $this->load->view('ajax/tasks/history_view',$data,TRUE);
            $data = array('history'=>$history);

        }

        if($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    }
}