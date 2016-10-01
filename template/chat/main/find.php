<html><head>
    <meta name="viewport" content="width=device-width">
    <title>find</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $static_url; ?>/layui/css/layui.css">
    <style type="text/css">
        .container-fluid li, .container-fluid ul {list-style-type:none;padding:0px}
        .container-fluid li {float:left;margin-right:15px;height:165px}
        .container-fluid ul {padding-top:17px}
        .one li span{
            font-size:100%;
        }
        .one li h6{
            width:120px;
            padding-top:5px;
            padding-bottom:5px;
        }
        .clear-pointer {
            cursor: default;
            color:#FFFFFF;
        }
        .clear-pointer:hover {
            color:#FFFFFF;
        }
        #change_tab{
            float:left;
        }
        #change_tab li{
            height:50px;
            clear:both;
        }
        #change_tab li a{
            text-decoration: none;
            padding:10px;
        }
        .start_active{
            background-color:#c3c3c3;
        }
        #group_two li{
            clear: both;
            height: 30px;
        }
    </style>
</head>
<body rel="find_iframe">
<script src="<?php echo $static_url; ?>js/jquery.min.js"></script>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li role="presentation" div-id="one" class="active"><a href="#">好友列表</a></li>
    <li role="presentation" div-id="two"><a href="#">分组管理</a></li>
    <li role="presentation" div-id="three"><a href="#">群管理</a></li>
</ul>
<div class="container-fluid">
    <ul id="one" class="one">
        <?php foreach($user_list as $item): ?>
        <li>
            <img src="<?php echo $item['avatar']; ?>" width="100" height="100" class="img-rounded">
            <h6><?php echo $item['remark_name']; ?></h6>
            <h6>
                <?php if($item['on_line']==1): ?>
                    <button type="button" class="btn btn-xs clear-pointer btn-success">在线</button>
                <?php else: ?>
                <button type="button" class="btn btn-xs clear-pointer">离线</button>
                <?php endif; ?>
                <button type="button" class="btn btn-primary  btn-xs" onclick="parent.chat.addFriend(<?php echo $item['id']; ?>);">加好友</button>
            </h6>

        </li>
        <?php endforeach; ?>
    </ul>
    <ul id="two" style="display:none;">
        <div class="row">

            <ul id="change_tab">
                <li  div-id="group_one"><a href="#" class="start_active">我的好友</a></li>
                <li div-id="group_two"><a href="#">分组操作</a></li>
            </ul>

            <div class="col-xs-10 col-md-10" style="border: 1px solid #cccccc;
    min-height: 400px;">

                <ul id="group_one">
                    <ul style="margin-bottom:20px;">
                        <select class="form-control" name="group_id" onchange="select_group(this.options[this.options.selectedIndex].value)">
                            <option value="-1">请选择分组</option>
                            <?php foreach($group_list as $item): ?>
                                <option value="<?php echo $item['group_id']; ?>"><?php echo $item['group_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </ul>

                    <ul class="one" id="group_friend_list">

                    </ul>
                </ul>
                <ul id="group_two" style="display:none;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>分组名</th>
                            <th>好友数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="group_list">
                        <?php foreach($group_list as $item): ?>
                            <tr>
                                <td><?php echo $item['group_id']; ?></td>
                                <td><?php echo $item['group_name']; ?></td>
                                <td><?php echo $item['friend_num']; ?></td>
                                <td><span style="cursor:pointer;" onclick="deleteGroup(this,<?php echo $item['group_id']; ?>)" class="glyphicon glyphicon-remove"></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="input-group">
                          <input type="text" class="form-control" id="group_name">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="addGroup()">添加</button>
                          </span>
                    </div>
                </ul>
            </div>
        </div>
    </ul>
    <ul id="three" style="display:none;">
        <div class="row">

            <ul id="change_tab">
                <li  div-id="swarm_one"><a href="#" class="start_active">我的群</a></li>
                <li div-id="swarm_two"><a href="#">群管理</a></li>
                <li div-id="swarm_three"><a href="#">群列表</a></li>
            </ul>

            <div class="col-xs-10 col-md-10" style="border: 1px solid #cccccc;
    min-height: 400px;">

                <ul id="swarm_one">
                    <ul style="margin-bottom:20px;">
                        <select class="form-control" name="swarm_id" onchange="select_swarm(this.options[this.options.selectedIndex].value)">
                            <option value="-1">请选择群</option>
                            <?php foreach($swarm_list as $item): ?>
                                <option value="<?php echo $item['swarm_id']; ?>"><?php echo $item['swarm_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </ul>

                    <ul class="one" id="swarm_friend_list">

                    </ul>
                </ul>
                <ul id="swarm_two" style="display:none;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>群名</th>
                            <th>群成员数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="swarm_list">
                        <?php foreach($swarm_list as $item): ?>
                            <tr>
                                <td><?php echo $item['swarm_id']; ?></td>
                                <td><?php echo $item['swarm_name']; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><span style="cursor:pointer;" onclick="deleteSwarm(this,<?php echo $item['swarm_id']; ?>)" class="glyphicon glyphicon-remove"></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <form role="form" onsubmit="return addSwarm()">
                        <div class="form-group">
                            <label for="exampleInputEmail1">群名称</label>
                            <input type="text" class="form-control" id="swarm_name" name="swarm_name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">群图片url</label>
                            <input type="text" class="form-control" id="swarm_avatar" name="avatar">
                        </div>
                        <button type="submit" class="btn btn-default">添加</button>
                    </form>
                </ul>
                <ul id="swarm_three" style="display:none;">
                    <?php foreach($swarm_list_other as $vo): ?>
                    <li>
                        <img src="<?php echo $vo['avatar']; ?>" width="100" height="100" class="img-rounded">
                        <h6><?php echo $vo['swarm_name']; ?></h6>
                        <h6>
                            <?php if($vo['is_join']==false): ?>
                                <button type="button" class="btn btn-xs clear-pointer" id="swarm_status_<?php echo $vo['swarm_id']; ?>">未加</button>
                                <button type="button" class="btn btn-primary  btn-xs" id="swarm_operate_<?php echo $vo['swarm_id']; ?>" onclick="joinSwarm(<?php echo $vo['user_id']; ?>,<?php echo $vo['swarm_id']; ?>);">加入</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-xs clear-pointer btn-success" id="swarm_status_<?php echo $vo['swarm_id']; ?>">已加</button>
                                <button type="button" class="btn btn-primary  btn-xs" id="swarm_operate_<?php echo $vo['swarm_id']; ?>" onclick="exitSwarm(<?php echo $vo['user_id']; ?>,<?php echo $vo['swarm_id']; ?>);">退群</button>
                            <?php endif; ?>
                        </h6>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </ul>
</div>

<script type="text/javascript">
    $(function(){
        $('#myTab li').click(function (e) {
            e.preventDefault()
            $(this).siblings().removeClass("active");
            $(this).addClass("active");
            var show_div_id = $(this).attr('div-id');
            $("#"+show_div_id).show().siblings().hide();
        })

        $("#change_tab li").click(function(e){
            e.preventDefault();
            $(this).siblings().find("a").removeClass("start_active");
            $(this).find("a").addClass("start_active");
            var show_div_id = $(this).attr('div-id');
            $("#"+show_div_id).show().siblings().hide();
        });
    });

    //选择分组
    function select_group(group_id) {
        $.ajax({
            type : 'post',
            url  : "<?php echo \common\Utils::makeUrl('chat','getFriendsByGroupId') ?>",
            data : {group_id:group_id,user_id:'<?php echo $user_id; ?>'},
            success  : function(data){
                $("#group_friend_list").html(data);
            }
        });
    }

    //删除分组
    function deleteGroup(obj,group_id) {
        var delGroupUrl = '{"a":"main/chat","m":"deleteGroup","group_id":"'+group_id+'"}';
        $(obj).parents("tr").remove();
        parent.chat.clientSendMsg(delGroupUrl);
    }

    //添加分组
    function addGroup() {
        var group_name = $("#group_name").val();
        var addGroupUrl = '{"a":"main/chat","m":"addGroup","group_name":"'+group_name+'","user_id":<?php echo $user_id; ?>}';
        parent.chat.clientSendMsg(addGroupUrl);
    }
    //选择群
    function select_swarm(swarm_id) {
        $.ajax({
            type : 'post',
            url  : "<?php echo \common\Utils::makeUrl('chat','getFriendsBySwarmId') ?>",
            data : {swarm_id:swarm_id,user_id:'<?php echo $user_id; ?>'},
            success  : function(data){
                $("#swarm_friend_list").html(data);
            }
        });
    }
    //创建群
    function addSwarm() {
        var swarm_name = $("#swarm_name").val();
        var avatar_url = $("#swarm_avatar").val();
        var addSwarmUrl = '{"a":"main/chat","m":"addSwarm","swarm_name":"'+swarm_name+'","avatar":"'+avatar_url+'","user_id":<?php echo $user_id; ?>}';
        parent.chat.clientSendMsg(addSwarmUrl);
        return false;
    }
    //删除群
    function deleteSwarm(obj,swarm_id) {
        var delSwarmUrl = '{"a":"main/chat","m":"deleteSwarm","swarm_id":"'+swarm_id+'"}';
        $(obj).parents("tr").remove();
        parent.chat.clientSendMsg(delSwarmUrl);
    }
    //加入群
    function joinSwarm(created_user_id,swarm_id) {
        var joinSwarmUrl = '{"a":"main/chat","m":"joinSwarm","user_id":<?php echo $user_id; ?>,"created_user_id":'+created_user_id+',"swarm_id":'+swarm_id+'}';
        parent.chat.clientSendMsg(joinSwarmUrl);
        parent.chat.msg("请求加入群信息已发送管理员,请等待审核...");
    }
    //退群
    function exitSwarm(user_id,swarm_id) {
        var exitSwarmUrl = '{"a":"main/chat","m":"exitSwarm","user_id":<?php echo $user_id; ?>,"swarm_id":'+swarm_id+'}';
        parent.chat.clientSendMsg(exitSwarmUrl);
    }

</script>
</body>
</html>