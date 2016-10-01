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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel-footer text-right" style="padding: 5px 15px;">
            <button class="btn btn-primary btn-xs" type="button" onclick="ok_friend_swarm<?php echo $to_user_id; ?>()">同意</button>
            <button class="btn btn-primary btn-xs" type="button" onclick="cancle_friend_swarm<?php echo $to_user_id; ?>(<?php echo $to_user_id; ?>)">取消</button>
        </div>
    </div>
    </form>
</div>
<script>
    function ok_friend_swarm<?php echo $to_user_id; ?>() {
        var to_user_id   = "<?php echo $to_user_id; ?>";
        var my_group_id  = $(":input[name='group_id_<?php echo $to_user_id; ?>']").val();
        var to_swarm_id  = "<?php echo $to_swarm_id; ?>";
        var created_user_id = "<?php echo $created_user_id; ?>";
        var sendOkUrl = '{"a":"main/chat","m":"agreeFriendForSwarm","created_user_id":'+created_user_id+',"to_user_id":'+to_user_id+',"to_swarm_id":'+to_swarm_id+'}';
        chat.clientSendMsg(sendOkUrl);
        for(i in chat.index_new_swarm) {
            if(chat.index_new_swarm[i]['to_user_id']==to_user_id) {
                layer.close(chat.index_new_swarm[i]['index_new_swarm'+to_user_id]);
            }
        }
    }

    function cancle_friend_swarm<?php echo $to_user_id; ?>(to_user_id){
        for(i in chat.index_new_swarm) {
            if(chat.index_new_swarm[i]['to_user_id']==to_user_id) {
                layer.close(chat.index_new_swarm[i]['index_new_swarm'+to_user_id]);
            }
        }
    }
</script>