<?php foreach($user_list as $vo): ?>
<li>
    <img src="<?php echo $vo['avatar']; ?>" width="100" height="100" class="img-rounded">
    <h6><?php echo $vo['remark_name']; ?></h6>
    <h6>
        <!-- Indicates a successful or positive action -->
        <?php if($vo['on_line']==1): ?>
            <button type="button" class="btn btn-xs clear-pointer btn-success">在线</button>

        <?php else: ?>
            <button type="button" class="btn btn-xs clear-pointer">离线</button>

        <?php endif; ?>
        <button type="button" class="btn btn-primary  btn-xs" onclick="delFriend(this,<?php echo $vo['user_id']; ?>);">删除</button>
    </h6>
</li>
<?php endforeach; ?>
<script>
    function delFriend(obj,user_id) {
        var swarm_id = '<?php echo $swarm_id; ?>';
        var delFriendUrl = '{"a":"main/chat","m":"delFriendForSwarm","user_id":"'+user_id+'","swarm_id":"'+swarm_id+'","my_id":"<?php echo $user_id; ?>"}';
        $(obj).parents("li").remove();
        parent.chat.clientSendMsg(delFriendUrl);
    }

</script>
