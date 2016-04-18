<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();
    }

    public function datetime($str)
    {
        $date_time = explode(' ',$str);
        if(sizeof($date_time)==2)
        {
            $date = $date_time[0];
            $date_values = explode('-',$date);
            if((sizeof($date_values)!=3) || !checkdate( (int) $date_values[1], (int) $date_values[2], (int) $date_values[0]))
            {
                return FALSE;
            }
            $time = $date_time[1];
            $time_values = explode(':',$time);
            if((int) $time_values[0]>23 || (int) $time_values[1]>59 || (int) $time_values[2]>59)
            {
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }


    /**
     * Exists
     *
     * Check if the input value already exist
     * in the specified database field.
     *
     * @param    string $str
     * @param    string $field
     * @return    bool
     */
    public function exists($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db) ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 1) : FALSE;
    }
}