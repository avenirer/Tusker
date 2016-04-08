<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
echo form_open('projects/create');
echo form_input('title',set_value('title'));
echo form_submit('','Create new project');
echo form_close();