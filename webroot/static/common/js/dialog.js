/**
 * 封装layer.js的使用
 */
var $D = {
    // 错误弹出层
    error: function (message, url) {
        layer.open({
            content: message,
            icon: 2,
            title: '错误提示',
            yes: function (index) {
                if (url) {
                    location.href = url;
                } else {
                    layer.close(index);
                }
            }
        });
    },
    //成功弹出层
    success: function (message, url) {
        layer.open({
            content: message,
            icon: 1,
            yes: function (index) {
                if (url) {
                    location.href = url;
                } else {
                    layer.close(index);
                }
            }
        });
    },
    // 确认弹出层
    confirm: function (message, url) {
        layer.open({
            content: message,
            icon: 3,
            btn: ['是', '否'],
            yes: function (index) {
                if (url) {
                    location.href = url;
                } else {
                    layer.close(index);
                }
            }
        });
    },
    //转场
    loadTo: function (url, timeout, icon_index) {
        var index = layer.load(icon_index ? icon_index : 0);
        setTimeout(function () {
            layer.close(index);
            location.href = url;
        }, timeout ? timeout * 1000 : 2000);
    },
    //确认弹出层
    popup: function (message) {
        layer.open({
            content: message,
            icon: 3,
            btn: ['确定']
        });
    }
};

