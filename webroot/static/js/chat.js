/**
 * Created by huguijian on 16/9/7.
 */
var chatStatus = {
    singleChat  : 1,
    groupChat   : 2,
    joinFriend  : 3,
    joinSwarm   : 4,
    removeFriend: 5,
    quitSwram   : 6,
    loginOut    : 7,
    removeGroup : 8,
    addGroup    : 9,
    sendCheckMsg :10,
    createSwarm : 11,
    deleteSwarm : 12,
    removeFriendForSwarm : 13,
    joinSwarmSuccess : 14,
    changeLine : 15

};


var chat = {
    ws    : null,
    layim : null,
    index : null,
    index_new_swarm : [],
    index_new : [],
    user_id : null,
    init : function(layim,host,port,user_id){
        this.ws = new WebSocket('ws://'+host+':'+port);
        this.user_id = user_id;
        chat.layim = layim;
        //连接服务器
        this.ws.onopen = function(event) {
            console.info("已连接到服务器!");
        }
        //关闭服务器
        this.ws.onclose = function(event) {
           //window.location.href = $_CONFIG.loginUrl;
            console.log('服务器已经关闭!',event);
        };
        this.clientOnMsg();
        //初始化生成用户登陆数据
        setTimeout(function(){
            chat.clientSendMsg($_CONFIG.myInfoUrl);
        },1000);


    },
    clientOnMsg   : function() {//客户端接收信息
        //监听收到的信息
        chat.ws.onmessage = function(event) {
            eval("var data="+event.data);
            if(data.chat_status==chatStatus.singleChat || data.chat_status==chatStatus.groupChat) {
                chat.chatMsg(data);
            }else if(data.chat_status==chatStatus.sendCheckMsg) {//加好友发送验证信息
                chat.chatCheckMsg(data);
            }else if(data.chat_status==chatStatus.joinFriend) {//加好友

                var data = {
                    type : 'friend',
                    avatar : data.avatar,
                    username : data.username,
                    id       : data.user_id,
                    groupid  : data.group_id,
                    sign     : data.sign
                };
                chat.layim.addList(data);

            }else if(data.chat_status==chatStatus.removeFriend) {//删除好友

                chat.deleFriend(data.user_id);

            }else if(data.chat_status==chatStatus.changeLine) {//好友在线与离线切换

                chat.changeLine(data);

            }else if(data.chat_status==chatStatus.removeGroup) {//删除分组

                chat.deleFriend(data.content.user_id);

            }else if(data.chat_status==chatStatus.removeGroup) {//删除分组

                //chat.deleGroup(data.content.group_id);

            }else if(data.chat_status==chatStatus.joinSwarm) {//加群聊信息提示

                chat.joinSwarm(data);

            }else if(data.chat_status==chatStatus.joinSwarmSuccess) {//加群聊成功

                chat.joinSwarmSuccess(data);

            }else if(data.chat_status==chatStatus.quitSwram) {//退群

                chat.exitSwram(data);

            }else if(data.chat_status==chatStatus.addGroup) {//添加分组

                chat.addGroup(data);

            }else if(data.chat_status==chatStatus.createSwarm) {//创建群

                chat.createSwarm(data);

            }else if(data.chat_status==chatStatus.deleteSwarm) {//删除群

                chat.deleteSwarm(data);

            }else if(data.chat_status==chatStatus.removeFriendForSwarm) {//您被管理员踢出群提示信息

                chat.removeFriendForSwarm(data);

            }else if(data.chat_status==chatStatus.loginOut) {//退出

                location.href = $_CONFIG.loginUrl;

            }else{
                chat.msg("返回数据有误!");
            }

        };
    },
    clientSendMsg : function(data){//客户端发送信息
        chat.ws.send(data);
    },
    chatMsg       : function(data) {//单聊和群聊
        setTimeout(function(){
            var obj = {
                username: data.username
                ,avatar: data.avatar
                ,id: data.id
                ,type: data.type
                ,content: data.msg
                ,timestamp: ''
            };
            chat.layim.getMessage(obj);
        },1000);
    },
    loginOut : function() {
        //询问框
        layer.confirm('您确定要退出？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            chat.clientSendMsg($_CONFIG.loginOutUrl);
        }, function(){
            layer.close();

        });
    },
    saveSign : function(text) {
        var sign_text = text;
        layer.prompt({
            title: '修改你的个性签名',
            value : text,
            formType: 2 //prompt风格，支持0-2
        }, function(text,index){
            $.ajax({
                'type' : 'POST',
                'url'  : $_CONFIG.saveSignUrl,
                'data' : {sign:text},
                'dataType' : 'JSON',
                'success' : function(data){
        
                    $(".layui-layim-remark").text(text);
                    layer.close(index);
                }
            });

        });
    },
    addFriend : function(to_user_id){
        $.ajax({
            type : 'post',
            url : $_CONFIG.addFriendUrl,
            data : {to_user_id:to_user_id},
            success : function(data){
               chat.index =  layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['420px', '300px'], //宽高
                    content: data
                });
            }
        });

    },
    deleFriend : function(user_id) {//渲染面板
        chat.layim.removeList({
            type: 'friend' //或者group
            ,id: user_id //好友或者群组ID
        });
    },
    deleGroup : function(group_id) {//渲染面板
        $(window.document).find("iframe").each(function(i,v) {
            var iframe_name = $(this.contentWindow.document).find("body").attr("rel");
            if (iframe_name == 'find_iframe') {
                $(this.contentWindow.document).find(":input[name='group_id']").find("option[value=" + group_id + "]").remove();
            }
        });
    },
    removeFriendForSwarm : function(data){
        layer.alert(data.msg);
        chat.layim.removeList({
            type : 'group',
            id   : data.swarm_id
        });
    },
    addGroup : function(group_data){

        var html  = '<tr>';
            html += '<td>'+group_data.group_id+'</td>';
            html += '<td>'+group_data.group_name+'</td>';
            html += '<td>'+group_data.friend_num+'</td>';
            html += '<td><span style="cursor:pointer;" onclick="deleteGroup(this,'+group_data.group_id+')" class="glyphicon glyphicon-remove"></span></td>';
            html += '</tr>';

        $(window.document).find("iframe").each(function(i,v){
            var iframe_name = $(this.contentWindow.document).find("body").attr("rel");
            if(iframe_name=='find_iframe') {
                $(this.contentWindow.document).find("tbody[id='group_list']").append(html);
                $(this.contentWindow.document).find(":input[id='group_name']").val("");
                var option = '';
                option += '<option value="' + group_data.group_id + '">' + group_data.group_name + '</option>';
                $(this.contentWindow.document).find(":input[name='group_id']").append(option);
            }
        });

    },
    createSwarm : function(swarm_data){
        var html  = '<tr>';
        html += '<td>'+swarm_data.swarm_id+'</td>';
        html += '<td>'+swarm_data.swarm_name+'</td>';
        html += '<td></td>';
        html += '<td><span style="cursor:pointer;" onclick="deleteSwarm(this,'+swarm_data.swarm_id+')" class="glyphicon glyphicon-remove"></span></td>';
        html += '</tr>';

        $(window.document).find("iframe").each(function(i,v){
            var iframe_name = $(this.contentWindow.document).find("body").attr("rel");
            if(iframe_name=='find_iframe') {
                $(this.contentWindow.document).find("tbody[id='swarm_list']").append(html);
                $(this.contentWindow.document).find(":input[id='swarm_name']").val("");
                $(this.contentWindow.document).find(":input[id='swarm_avatar']").val("");
                var option = '';
                option += '<option value="' + swarm_data.swarm_id + '">' + swarm_data.swarm_name + '</option>';
                $(this.contentWindow.document).find(":input[name='swarm_id']").append(option);
            }
        });
        var new_swarm_data = {
            type: 'group',
            avatar: swarm_data.avatar,
            groupname: swarm_data.swarm_name,
            id: swarm_data.swarm_id
        }
        chat.layim.addList(new_swarm_data);
    },
    deleteSwarm : function(swarm_data) {//渲染面板
        $(window.document).find("iframe").each(function(i,v){
            var iframe_name = $(this.contentWindow.document).find("body").attr("rel");
            if(iframe_name=='find_iframe') {
                $(this.contentWindow.document).find(":input[name='swarm_id']").find("option[value=" + swarm_data.swarm_id + "]").remove();
            }
        });
        chat.layim.removeList({
            type: 'group' //或者group
            ,id: swarm_data.swarm_id //好友或者群组ID
        });

    },
    msg : function(content) {
        layer.msg(content);
    },
    chatCheckMsg : function(data){
        var new_index = "index_news_"+data.user_id;
        var temp_user_id = data.user_id;
        $.ajax({
            type : 'post',
            url : $_CONFIG.checkMsgFriendUrl,
            data : {to_user_id:data.user_id,check_msg:data.check_msg,to_group_id:data.group_id},
            success : function(data){
                chat.index =  layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['420px', '300px'], //宽高
                    content: data,
                    shade : 0,
                    success : function(dom,index){
                        var temp = [];
                            temp[new_index] = index;
                            temp['to_user_id'] = temp_user_id;
                        chat.index_new.push(temp);
                    }
                });
            }
        });

    },
    joinSwarm : function(data) {
        var new_index = "index_new_swarm"+data.user_id;
        var temp_user_id = data.user_id;
        $.ajax({
            type : 'post',
            url : $_CONFIG.checkMsgFriendForSwarmUrl,
            data : {to_user_id:data.user_id,created_user_id:data.created_user_id,check_msg:data.check_msg,to_swarm_id:data.swarm_id},
            success : function(data){
                chat.index =  layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['420px', '300px'], //宽高
                    content: data,
                    shade : 0,
                    success : function(dom,index){
                        var temp = [];
                        temp[new_index] = index;
                        temp['to_user_id'] = temp_user_id;
                        chat.index_new_swarm.push(temp);
                    }
                });
            }
        });
    },
    joinSwarmSuccess : function(data){
        var swarm_data = {
            type: 'group' //列表类型，只支持friend和group两种
            ,avatar: data.avatar //群组头像
            ,groupname: data.swarm_name //群组名称
            ,id: data.swarm_id //群组id
        };
        layer.alert(data.msg);
        chat.layim.addList(swarm_data);
        $(window.document).find("iframe").each(function(i,v){
            var iframe_name = $(this.contentWindow.document).find("body").attr("rel");
            if(iframe_name=='find_iframe') {
                $(this.contentWindow.document).find('button[id="swarm_status_'+data.swarm_id+'"]').replaceWith('<button type="button" class="btn btn-xs clear-pointer btn-success" id="swarm_status_'+data.swarm_id+'">已加</button>');
                $(this.contentWindow.document).find('button[id="swarm_operate_'+data.swarm_id+'"]').replaceWith('<button type="button" class="btn btn-primary  btn-xs" id="swarm_operate_'+data.swarm_id+'" onclick="exitSwarm('+data.user_id+','+data.swarm_id+');">退群</button>');
            }
        });

    },
    exitSwram : function(data){
        var swarm_data = {
            type : 'group',
            id   : data.swarm_id
        };
        chat.layim.removeList(swarm_data);
        layer.alert(data.msg);
        $(window.document).find("iframe").each(function(i,v){
            var iframe_name = $(this.contentWindow.document).find("body").attr("rel");
            if(iframe_name=='find_iframe') {
                $(this.contentWindow.document).find('button[id="swarm_status_' + data.swarm_id + '"]').replaceWith('<button type="button" class="btn btn-xs clear-pointer" id="swarm_status_' + data.swarm_id + '">未加</button>');
                $(this.contentWindow.document).find('button[id="swarm_operate_' + data.swarm_id + '"]').replaceWith('<button type="button" class="btn btn-primary  btn-xs" id="swarm_operate_' + data.swarm_id + '" onclick="joinSwarm(' + data.user_id + ',' + data.swarm_id + ');">加入</button>');
            }
        });
    },
    layerClose : function(index) {
        layer.close(index);
    },
    getHistoryMsg : function(id,type){
        var cache_data = $.parseJSON(localStorage.layim);
            cache_data = cache_data[chat.user_id];
            var true_cache_data = null;
            if(type=='friend') {
                true_cache_data = cache_data.chatlog["friend"+id];

            }else if(type=='group') {
                true_cache_data = cache_data.chatlog["group"+id];
            }
            return true_cache_data;
    },
    changeLine : function(data){
        if(data.line=='online') {

            $("#layim-friend"+data.user_id).find("img").removeClass("icon_img");

        }else if(data.line=='hide') {

            $("#layim-friend"+data.user_id).find("img").addClass("icon_img");

        }
    }

};

