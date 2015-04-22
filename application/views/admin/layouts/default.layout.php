<?php $this->template->component('head'); ?>
<body>

    <div class="container-fluid content-container">
        <div class="row-fluid wrapper">
            <div class="col-md-2 sidebar">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="<?php echo admin_url(); ?>"> <i class="fa fa-fw fa-dashboard"></i><span>Dashboard</span></a></li>
                    <li><a href="<?php echo admin_url('users'); ?>"> <i class="fa fa-fw fa-group"></i><span>Users</span></a></li>
                </ul>
            </div>
            <div class="col-md-10 content">
                <header>
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle">
                                        <i class="fa fa-user"></i>
                                        <span>Logged in as <?php echo authenticated()->firstname.' '.authenticated()->lastname; ?></span>
                                        <b class="caret"></b>
                                    </a>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="#">Profile</a></li>
                                        <li><a href="<?php echo admin_url('system/preferences'); ?>">Preferences</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </header>
                <div class="padded">
                    <?php echo $this->template->content(); ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/dist/js/libraries.js"></script>
    <script src="/dist/js/compiled.js"></script>

</body>
</html>