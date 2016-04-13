<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php echo anchor('/', $website->title, 'class="navbar-brand"');?>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><?php echo anchor('','Home');?></li>
                <li><?php echo anchor('projects','Projects');?></li>
                <li><?php echo anchor('about','About');?></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span>
                        <!--<img src="//www.gravatar.com/avatar/<?php echo $_SESSION['gravatar'];?>?s=20" onerror="this.src = '<?php echo base_url().'assets/images/avatar.png';?>';" />--> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('users/profile','Profile');?></li>
                        <li><a href="#">Another action</a></li>

                        <?php
                        if($this->ion_auth->is_admin())
                        {
                            echo '<li role="separator" class="divider"></li>';
                            echo '<li>'.anchor('users', 'Users').'</li>';
                            echo '<li>'.anchor('users/create', 'Add user').'</li>';
                            echo '<li role="separator" class="divider"></li>';
                            echo '<li>'.anchor('master','Main settings').'</li>';

                        }
                        ?>
                        <li role="separator" class="divider"></li>
                        <li><?php echo anchor('user/logout','Logout');?></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>