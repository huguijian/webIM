<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在线聊天IM - 登录</title>

    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/base.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/pages.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/settings.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/background.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/admin_forms.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/sidebar.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/helpers.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/common/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $static_url;?>/assets/css/theme/style.css">


    <!--[if lt IE 9]>
    <script src="<?php echo $static_url;?>/common/js/html5shiv.min.js"></script>
    <script src="<?php echo $static_url;?>/common/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="external-page sb-l-c sb-r-c">
<div id="main" class="animated fadeIn">
    <section id="content_wrapper">
        <div id="canvas-wrapper">
            <canvas id="bg-canvas"></canvas>
        </div>

        <section id="content">
            <div class="admin-form theme-info" id="login1">
                <div class="row mb15 table-layout">
                    <div class="col-xs-6 va-m pln">
                        <a href="dashboard.html" title="Return to Dashboard">
<!--                            <img src="--><?php //echo $static_url;?><!--/assets/img/logo.png" title="智适应在线" class="img-responsive w250">-->
                        </a>
                    </div>
                    <div class="col-xs-6 text-right va-b pr5">
                        <span class="xx_title">IM系统</span>
                    </div>
                </div>

                <div class="panel panel-info mt10 br-n">
                    <div class="panel-heading heading-border bg-white">
                                <span class="panel-title hidden">
                                    <i class="fa fa-sign-in"></i>
                                </span>
                        <div class="section row mn">
                            <div class="col-sm-4">
                                <a href="javascript:;" class="button btn-social xx_bg_color1 text-center btn-block">
                                    <i class="fa fa-bar-chart"></i>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:;" class="button btn-social xx_bg_color2 text-center btn-block">
                                    <i class="fa fa-cogs"></i>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:;" class="button btn-social xx_bg_color3 text-center btn-block">
                                    <i class="fa fa-users"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form method="post" id="login_form" method="post" action="<?php echo \common\Utils::makeUrl('login', 'check');?>" onsubmit="return login();">
                        <div class="panel-body bg-light p30">
                            <div class="row">
                                <div class="col-sm-7 pr30">
                                    <div class="section">
                                        <label for="username" class="field-label text-muted fs18 mb10">用户名</label>
                                        <label for="username" class="field prepend-icon">
                                            <input type="text" name="username" id="username" class="gui-input" placeholder="请输入用户名" tabindex="1">
                                            <label for="username" class="field-icon">
                                                <i class="fa fa-user"></i>
                                            </label>
                                        </label>
                                    </div>
                                    <!-- end section -->

                                    <div class="section">
                                        <label for="password" class="field-label text-muted fs18 mb10">密码</label>
                                        <label for="password" class="field prepend-icon">
                                            <input type="password" name="password" id="password" class="gui-input" placeholder="请输入密码" tabindex="2">
                                            <label for="password" class="field-icon">
                                                <i class="fa fa-lock"></i>
                                            </label>
                                        </label>
                                    </div>
                                    <!-- end section -->

                                </div>
                                <div class="col-sm-5 br-l br-grey pl30">
                                    <p class="mb15 xx_color_info">
                                        <i class="fa fa-arrow-right fa-2x xx_color_gray"></i>
                                    </p>
                                    <p class="mb15 xx_color_info">
                                        <a href="<?php echo \common\Utils::makeUrl('login', 'reg');?>">你可以注册<span class="xx_font_big2">IM</span></a>
                                    </p>
                                    <p class="mb15 xx_color_info">
                                        好友<span class="xx_font_big2"></span>
                                    </p>
                                    <p class="mb15 xx_color_info">
                                       让沟通变得更简单
                                    </p>
                                    <p class="mb15 xx_color_info">
                                        <span class="xx_font_big2">今天</span>，<span class="xx_font_big2">START IM</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer p10 ph15 text-center">
                            <button type="submit" id="btn_submit" class="btn xx_btn-lg btn-primary xx_font_big6" tabindex="3">登 录</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>

    </section>
</div>


<!-- script -->
<script src="<?php echo $static_url;?>/common/js/jquery.min.js"></script>
<script src="<?php echo $static_url;?>/common/layer/layer.js"></script>
<script src="<?php echo $static_url;?>/common/js/dialog.js"></script>
<script src="<?php echo $static_url;?>/assets/js/canvasbg/canvasbg.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 3.3
            }
        });
    });
</script>

<script type="text/javascript">
    function login() {
        var $username = $.trim($("#username").val());
        var $passwords = $.trim($("#password").val());

        if ($username == '' || $passwords == '') {
            $D.error('账户或密码不能为空');
            return false;
        } else {
            return true;

        }
    }
</script>

<!-- //script -->
</body>
</html>
