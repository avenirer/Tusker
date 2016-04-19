<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Auth_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('task_model');
        $this->load->model('task_history_model');
        $this->load->model('project_model');
    }


    public function index()
    {
        #TODO nu stiu ce sa pun aici...

    }
    
    public function generate()
    {
        $this->form_validation->set_rules('project_id','Project ID','trim|is_natural|required');
        $this->form_validation->set_rules('start_date','Start date','trim');
        $this->form_validation->set_rules('end_date','End date','trim');

        $projects = $this->project_model->get_user_projects();
        //print_r($projects);
        //exit;
        $project_select = array('0'=>'No project selected');
        if($projects)
        {
            foreach($projects as $project)
            {
                $project_select[$project['project_id']] = $project['project_title'];
            }
        }
        $this->data['project_select'] = $project_select;
        
        if($this->form_validation->run()===TRUE)
        {
            $project_id = $this->input->post('project_id');
            $start = implode('-',array_reverse(explode('-',$this->input->post('start_date'))));
            $end = implode('-',array_reverse(explode('-',$this->input->post('end_date'))));
            $where = array('user_id'=>$_SESSION['user_id'],'updated_at >= '=>$start.' 00:00:00','updated_at <= '=>$end.' 23:59:59');
            if($project_id!=='0')
            {
                $where['project_id'] = $project_id;
            }
            $work = $this->task_model->where($where)->with_project('fields:title')->with_history('fields:created_at,comment')->get_all();
            $this->data['work'] = $work;
        }
        $this->render('reports/form_generate_view');
    }
}