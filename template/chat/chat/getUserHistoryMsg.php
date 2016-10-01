<html><head>
    <meta name="viewport" content="width=device-width">
    <title>Index</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>layim - layui</title>

    <link rel="stylesheet" href="<?php echo $static_url; ?>/layui/css/layui.css">
    <style>

    </style>
    <script src="<?php echo $static_url; ?>js/jquery.min.js"></script>
</head>
<body>
<script src="<?php echo $static_url; ?>/layui/layui.js"></script>
<script src="<?php echo $static_url; ?>js/laytpl.js"></script>
<div class="layim-chat-main">
    <ul id="view">

    </ul>
</div>
<style type="text/css">
    .layim-chat-main {
        height: auto;
    }
</style>

<script id="getHistoryMsg" type="text/html">
    {{# for(var i = 0, len = d.length; i < len; i++){ }}
        <li class="">
            <div class="layim-chat-user">
                <img src="{{ d[i].avatar }}">
                <cite>{{ d[i].username }}<i>{{getLocalTime(d[i].timestamp)}}</i></cite>
            </div>
            <div class="layim-chat-text">{{parent.chat.layim.content(d[i].content)}}</div>
        </li>
    {{# } }}
</script>
<script type="text/javascript">

    layui.use(['layim', 'laypage'], function (layim) {
        var laypage = layui.laypage, $ = layui.jquery;
    });
    $(function(){
        var data = getUserHistoryMsgList();
            data = data.sort(function(a,b){return b.timestamp- a.timestamp});
        var gettpl = document.getElementById('getHistoryMsg').innerHTML;
        laytpl(gettpl).render(data, function(html){
            document.getElementById('view').innerHTML = html;
        });

    });

    function getUserHistoryMsgList() {
        var id   = '<?php echo $id; ?>';
        var type = '<?php echo $type; ?>';
        var list = parent.chat.getHistoryMsg(id,type);
        return list;
    }

    function getLocalTime(nS) {
        return  new Date(nS).pattern("yyyy-MM-dd hh:mm:ss");
    }

    Date.prototype.pattern=function(fmt) {
        var o = {
            "M+" : this.getMonth()+1, //月份
            "d+" : this.getDate(), //日
            "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
            "H+" : this.getHours(), //小时
            "m+" : this.getMinutes(), //分
            "s+" : this.getSeconds(), //秒
            "q+" : Math.floor((this.getMonth()+3)/3), //季度
            "S" : this.getMilliseconds() //毫秒
        };
        var week = {
            "0" : "/u65e5",
            "1" : "/u4e00",
            "2" : "/u4e8c",
            "3" : "/u4e09",
            "4" : "/u56db",
            "5" : "/u4e94",
            "6" : "/u516d"
        };
        if(/(y+)/.test(fmt)){
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
        }
        if(/(E+)/.test(fmt)){
            fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);
        }
        for(var k in o){
            if(new RegExp("("+ k +")").test(fmt)){
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
            }
        }
        return fmt;
    }


</script>
</body>
</html>