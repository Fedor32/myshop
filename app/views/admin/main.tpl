<? extract($this->data); ?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>Панель администратора</title>

        <meta name="description" content="Common Buttons &amp; Icons" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?=ASSETS;?>admin/font-awesome/4.7.0/css/font-awesome.min.css" />

        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/fonts.googleapis.com.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

        <!--[if lte IE 9]>
            <link rel="stylesheet" href="<?=ASSETS;?>admin/css/ace-part2.min.css" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/ace-skins.min.css" />
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/ace-rtl.min.css" />

        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/jquery-ui.custom.min.css" />
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/jquery.gritter.min.css" />
        <link rel="stylesheet" href="<?=ASSETS;?>admin/css/dropzone.min.css" />
        <!--[if lte IE 9]>
          <link rel="stylesheet" href="<?=ASSETS;?>admin/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
        <script src="<?=ASSETS;?>admin/js/ace-extra.min.js"></script>

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lte IE 8]>
        <script src="<?=ASSETS;?>admin/js/html5shiv.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/respond.min.js"></script>
        <![endif]-->
        <!--[if !IE]> -->
        <script src="<?=ASSETS;?>admin/js/jquery-2.1.4.min.js"></script>
        <!-- <![endif]-->

        <!--[if IE]>
        <script src="<?=ASSETS;?>admin/js/jquery-1.11.3.min.js"></script>
        <![endif]-->
        
        <script src="<?=ASSETS;?>admin/js/bootbox.js"></script>
        
        <script src="<?=ASSETS;?>ckeditor/ckeditor.js"></script>


    </head>

    <body class="no-skin">
        <div id="navbar" class="navbar navbar-default ace-save-state">
            <div class="navbar-container ace-save-state" id="navbar-container">
                <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                    <span class="sr-only">Toggle sidebar</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>
                </button>

                <div class="navbar-header pull-left">
                    <a href="/" class="navbar-brand">
                        <small>
                            <i class="fa fa-leaf"></i>
                            <?php echo $user->login.' / '.Auth::$roles[$user->role];?>
                        </small>
                    </a>
                </div>

                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                        
                        <li class="purple dropdown-modal">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                                <span class="badge badge-important">8</span>
                            </a>

                            <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    <i class="ace-icon fa fa-exclamation-triangle"></i>
                                    8 Notifications
                                </li>

                                <li class="dropdown-content">
                                    <ul class="dropdown-menu dropdown-navbar navbar-pink">
                                        <li>
                                            <a href="#">
                                                <div class="clearfix">
                                                    <span class="pull-left">
                                                        <i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
                                                        New Comments
                                                    </span>
                                                    <span class="pull-right badge badge-info">+12</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <i class="btn btn-xs btn-primary fa fa-user"></i>
                                                Bob just signed up as an editor ...
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <div class="clearfix">
                                                    <span class="pull-left">
                                                        <i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
                                                        New Orders
                                                    </span>
                                                    <span class="pull-right badge badge-success">+8</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <div class="clearfix">
                                                    <span class="pull-left">
                                                        <i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
                                                        Followers
                                                    </span>
                                                    <span class="pull-right badge badge-info">+11</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="dropdown-footer">
                                    <a href="#">
                                        See all notifications
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="green dropdown-modal">
                            <a  href="/admin/messages">
                                <i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
                                <span class="badge badge-success">5</span>
                            </a>
                        </li>

                        <li class="light-blue dropdown-modal">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <span class="user-info">
                                    <i class="fa fa-user fa-lg"></i>
                                    <?php echo $user->login;?>
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <li>
                                    <a href="profile.html">
                                        <i class="ace-icon fa fa-user"></i>
                                        Профиль
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="/admin/logout">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        Выход
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- /.navbar-container -->
        </div>

        <div class="main-container ace-save-state" id="main-container">
            <script type="text/javascript">
                try{ace.settings.loadState('main-container')}catch(e){}
            </script>

            <div id="sidebar" class="sidebar responsive ace-save-state">
                <script type="text/javascript">
                    try{ace.settings.loadState('sidebar')}catch(e){}
                </script>

                <ul class="nav nav-list">

                <?php include_once TEMPLATES.'admin/main_menu.tpl';?>

                </ul><!-- /.nav-list -->

                <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                    <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                </div>
            </div>

            <div class="main-content">
                <div class="main-content-inner">


                    <div class="page-content">


                        <div class="page-header">
                            <h1>
                                <?php echo $mysection['pagename'];?>
                                <small>
                                    <i class="ace-icon fa fa-angle-double-right"></i>
                                    <?php echo $mysection['pagerem'];?>
                                </small>
                            </h1>
                        </div><!-- /.page-header -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
<?php echo $content;?>

                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <div class="footer">
                <div class="footer-inner">
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder">Ace</span>
                            Application &copy; 2013-2014
                        </span>

                        &nbsp; &nbsp;
<? echo 'Памяти использовано: ',round(memory_get_usage()/1024/1024,2),' MB';?>
                    </div>
                </div>
            </div>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->
        

        <script src="<?=ASSETS;?>admin/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->

        <!-- page specific plugin scripts -->
        <script src="<?=ASSETS;?>admin/js/jquery.dataTables.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/jquery.dataTables.bootstrap.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/dataTables.buttons.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/buttons.flash.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/buttons.html5.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/buttons.print.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/buttons.colVis.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/dataTables.select.min.js"></script>

        <!-- ace scripts -->
        <script src="<?=ASSETS;?>admin/js/ace-elements.min.js"></script>
        <script src="<?=ASSETS;?>admin/js/ace.min.js"></script>

        <script src="<?=ASSETS;?>admin/js/jquery.gritter.min.js"></script>

        <script src="<?=ASSETS;?>admin/js/admin.js"></script>



        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function($) {
                $('#loading-btn').on(ace.click_event, function () {
                    var btn = $(this);
                    btn.button('loading')
                    setTimeout(function () {
                        btn.button('reset')
                    }, 2000)
                });

                $('#id-button-borders').attr('checked' , 'checked').on('click', function(){
                    $('#default-buttons .btn').toggleClass('no-border');
                });
            })
        </script>
        <script type="text/javascript">
            $('[data-rel=tooltip]').tooltip();
        </script>
    </body>
</html>
