<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container"> 
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
            </button>
            <a href="<?php echo site_url('trainer');?>" class="navbar-brand">Dealership Crawler</a>
        </div>
        
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php if (isset($active_menu) && $active_menu=='trainer') echo "class='active'";?>><a href="<?php echo site_url('trainer');?>">Trainer</a></li>           
                <li <?php if (isset($active_menu) && $active_menu=='train_data') echo "class='active'";?>><a href="<?php echo site_url('train_data');?>">Train Data</a></li>           
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="<?php echo site_url('auth/login');?>">
                        <span class="glyphicon glyphicon-user"></span> 
                        <strong>Login</strong>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

