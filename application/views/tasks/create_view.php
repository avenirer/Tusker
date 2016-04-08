<div class="row">
    <div class="col-lg-12">
        <?php echo form_open('tasks/create');?>
            <div class="col-lg-6">
                <div class="form-group">
                    <?php
                    echo form_label('Task title:','title');
                    echo form_input('title',set_value('title'),'class="form-control"');
                    ?>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php echo form_label('Estimated completion time (ECT):');?>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('ect_days',set_value('ect_days',0),'class="form-control"');?><div class="input-group-addon">days</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('ect_hours',set_value('ect_hours',0),'class="form-control"');?><div class="input-group-addon">hours</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('ect_minutes',set_value('ect_minutes',0),'class="form-control"');?><div class="input-group-addon">minutes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php
                            echo form_label('Due date:','due');
                            echo form_input('due',set_value('due',date('d-m-Y')), 'class="form-control datepick"')
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <?php
                    echo form_label('Details:','details');
                    echo form_textarea(['name'=>'details','rows'=>'5'],set_value('details'),'class="form-control"');
                    ?>
                </div>
            </div>
            <div class="col-lg-12">
                <?php
                echo form_hidden('project_id',$project->id);
                echo form_submit('','Create new task','class="btn btn-small btn-primary"');
                ?>
            </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
