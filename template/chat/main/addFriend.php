<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    .bg{
        background:#f9fbfd;
    }
</style>
<div class="container-fluid">
    <form name="addFriend" onsubmit="return false;">
        <input type="hidden" name="to_user_id" value="<?php echo $user_info['id']; ?>"/>
    <div class="row">
        <div class="col-xs-6 col-md-4  bg">
            <div>
                <img src="<?php echo $user_info['avatar']; ?>" class="img-rounded" width="110" height="110">
                <h5><?php echo $user_info['remark_name']; ?></h5>
                <h6>性别：<?php echo $user_info['sex']==1 ? '男' :'女' ?></h6>
                <h6>年龄：<?php echo $user_info['age'];?></h6>
                <h6>所在地: <?php echo $user_info['address']; ?></h6>
            </div>
        </div>
        <div class="col-xs-12 col-md-8">
            <div>
                <h5>请输入验证信息</h5>
                <h5>
                    <textarea class="form-control input-sm" name="check_msg"></textarea>
                </h5>
                <h5>分组</h5>
                <select class="form-control" name="group_id">
                    <option value="-1">选择分组</option>
                    <?php foreach($group_list as $item): ?>
                    <option value="<?php echo $item['group_id']; ?>"><?php echo $item['group_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel-footer text-right" style="padding: 5px 15px;">
            <button class="btn btn-primary btn-xs" type="button" onclick="add_friend()">添加</button>
        </div>
    </div>
    </form>
</div>
<script>
    function add_friend() {

        var data_arr = $("form[name='addFriend']").serializeArray();
        var data = {};
        for(var i in data_arr) {
            data[data_arr[i]['name']] = data_arr[i]['value'];
        }
        var user_id = "<?php echo $self_user_id; ?>";
        var addFriendUrl = '{"a":"main/chat","m":"addFriendCheckMsg","to_user_id":'+data.to_user_id+',"user_id":'+user_id+',"group_id":'+data.group_id+',"check_msg":"'+data.check_msg+'"}';
        parent.chat.clientSendMsg(addFriendUrl);
        layer.close(chat.index);
        layer.msg("您的好友申请已经提交,等待审核中...");
    }
</script>