<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    .bg{
        background:#f9fbfd;
    }
</style>
<div class="container-fluid">
    <form name="okFriend" onsubmit="return false;">
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
                <h5>
                    <?php echo $check_msg; ?>
                </h5>
                <h5>我的分组</h5>
                <select class="form-control" name="group_id_<?php echo $to_user_id; ?>">
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
            <button class="btn btn-primary btn-xs" type="button" onclick="ok_friend_<?php echo $to_user_id; ?>()">添加</button>
            <button class="btn btn-primary btn-xs" type="button" onclick="cancle_friend_<?php echo $to_user_id; ?>(<?php echo $to_user_id; ?>)">取消</button>
        </div>
    </div>
    </form>
</div>
<script>
    function ok_friend_<?php echo $to_user_id; ?>() {
        var to_user_id   = "<?php echo $to_user_id; ?>";
        var my_user_id   = "<?php echo $self_user_id; ?>";
        var my_group_id  = $(":input[name='group_id_<?php echo $to_user_id; ?>']").val();
        var to_group_id  = "<?php echo $to_group_id; ?>";
        var sendOkUrl = '{"a":"main/chat","m":"agreeFriend","my_user_id":'+my_user_id+',"to_user_id":'+to_user_id+',"to_group_id":'+to_group_id+',"my_group_id":'+my_group_id+'}';
        chat.clientSendMsg(sendOkUrl);
        data = null;
        var data = {
            type : 'friend',
            avatar : '<?php echo $user_info['avatar']; ?>',
            username : '<?php echo $user_info['remark_name']; ?>',
            groupid : my_group_id,
            id       : '<?php echo $user_info['id']; ?>',
            sign     : '<?php echo $user_info['sign'];?>'
        };
        chat.layim.addList(data);

        for(i in chat.index_new) {
            if(chat.index_new[i]['to_user_id']==to_user_id) {
                layer.close(chat.index_new[i]['index_news_'+to_user_id]);
            }
        }
    }
    function cancle_friend_<?php echo $to_user_id; ?>(to_user_id){
        for(i in chat.index_new) {
            if(chat.index_new[i]['to_user_id']==to_user_id) {
                layer.close(chat.index_new[i]['index_news_'+to_user_id]);
            }
        }
    }
</script>