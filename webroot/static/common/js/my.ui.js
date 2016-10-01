/*公用JS*/
//全选插件
(function ($) {
    $.fn.extend({
        checkboxSelectAll:function(parent) {
            this.each(function(){
                $checkbox = $(this);
                $checkbox.click(function(){

                    var group = $(this).attr('group');
                    var type = $checkbox.is(':checked') ? 'all' : 'none';
                    $.checkbox.selectAll(group,type,parent);
                });
            });
        }
    });

    $.checkbox = {
        selectAll:function(_name,_type,_parent) {
            $parent = $(_parent || document);
            var checkboxList = $parent.find(':checkbox[name="'+_name+'"]');
            switch(_type) {
                case 'all' :
                    checkboxList.each(function(){
                        this.checked = true;    
                    });
                    //添加选中样式
                    //$(checkboxList).parents("tr").addClass('tr-selected');
                break;
                case 'none':
                    checkboxList.each(function(){
                        this.checked = false;   
                    });
                    //删除选中样式
                    //$(checkboxList).parents("tr").removeClass('tr-selected');
                break;
            }
        }
    };
})(jQuery);
var MY_UI = {
	frag : {},
	_msg : {},
    _confirmBoxId : "#my-confirm",
    _alertBoxId   : "#my-alert",
	trim: function(text,specText) {
		if(this.isEmpty(specText)) {
			if(typeof(text) == 'string') {
            return text.replace(/^\s*|\s*$/g,"");
	        }else{
	            return text;
	        }
		}else{
			return text.replace(specText,"");
		}
        
    },
    isEmpty: function(val) {
        switch(typeof(val)) {
            case 'string' :
                return MY_UI.trim(val).length==0 ? true : false;
                break;
            case 'number' :

                return val == 0 ? true : false;
                break;
            case 'object' :
                return val==null ? true : false;
                break;
            default:
                return true;

        }
    },
    isEmail : function(email) {
        var reg = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
        return reg.test( email );
    },
    isTel : function(tel) {
        var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等
        return reg.test( tel );
    },
    isMobile : function(mobile) {
        var reg = /(^0{0,1}[13|15|18|14]{2}[0-9]{9}$)/;
        return reg.test(mobile);
    },
    //要看数组或对象里是否有重复的值如果有返回true
    isRepeat : function(arr) {
        var temp = {};
        for(var i in arr) {
            if(temp[arr[i]])
                return true;
            temp[arr[i]] = true;
        }
        return false;
    },

    getTruePos: function (el) {
        var parentEl = null;
        var parentEls = el.parents("li");
        var elPos = el.offset();
        for (i = 0; i < parentEls.length; i++) {
            if ($(parentEls[i]).css('position') == 'absolute' || $(parentEls[i]).css('position') == 'relative') {
                var parentEl = $(parentEls[i]);
                break;
            }
        }
        // 父元素存在绝对定位
        // 根据父元素确定位置
        if (parentEl !== null) {
            var pelPos = parentEl.offset();
            elPos = {'left': (elPos.left - pelPos.left), 'top': (elPos.top - pelPos.top)};
        }
        return elPos;
    },
    getAbsoultPos : function(el){
    	var elPos = el.offset();
    	return elPos;
    },
    // 获取中间位置
    getCenterPos: function (el, size) {
        var fixTop = 60;
        var offset = el.offset();
        var size = arguments[1] ? size : {'h': el.height(), 'w': el.width()};
        return {
            'top': ($(window).height() - size.h) / 2 + $(window).scrollTop() - fixTop,
            'left': ($(window).width() - size.w) / 2 - offset.left
        };
    },
    // 重新定位光标位置
    setCursor: function (iItem) {
        if ($.browser.msie) {
            var range = iItem.createTextRange();
            range.collapse(false);
            range.select();
        } else {
            var ilength = $(iItem).val().length;
            $(iItem).focus();
            window.setTimeout(function () {
                iItem.setSelectionRange(ilength, ilength);
                iItem.focus();
            }, 0);
        }
    },
    prompt : function(options) {
    	var defaultOpts = {
    		layout: 'center',
    		text: "提示信息",
          	type: 'error',
          	dismissQueue: true,
          	theme: 'agileUI',
          	timeout:true,
            animation: {
                open: {height: 'toggle'},
                close: {height: 'toggle'},
                easing: 'swing',
                speed: 1000
            }
    	};

    	if(typeof(options) =='string') {
    		$.extend(defaultOpts,{text:options});
    	}else{
            
    		$.extend(defaultOpts,options);
    	}
    	switch (defaultOpts.type) {
    		case 'success' :
    			defaultOpts.text  = '<i class="glyph-icon icon-cog mrg5R"></i>'+defaultOpts.text;
    		break;
    		case 'error' :
    			defaultOpts.text = '<i class="glyph-icon icon-cog mrg5R"></i>'+defaultOpts.text;
    		break;
    		case 'warning' :
    			defaultOpts.text = '<i class="glyph-icon icon-cog mrg5R"></i>'+defaultOpts.text;
    		break;
    		default:
    		break;
    	}
    	
    	noty(defaultOpts);
    },
	init : function($pageFrag,$options) {

		var $p = document;
		var op = $.extend({},$options);
        //加载题型xml数据
        if(!this.isEmpty($pageFrag)) {
            $.ajax({
                type:"GET",
                url:$pageFrag,
                dataType:"xml",
                timeout:50000,
                cache:false,
                error:function(xhr) {
                    alert("Error loading XML document: " + $pageFrag + "\nHttp status: " + xhr.status + " " + xhr.statusText);
                },
                success:function(xml) {
                    $(xml).find("_PAGE_").each(function() {
                        var pageId = $(this).attr("id");
                        if (pageId) MY_UI.frag[pageId] = $(this).text();
                    });
                    //if (jQuery.isFunction(op.callback)) op.callback();
                }
            });
        }
		//全选插件
		$(":checkbox.checkAll",$p).checkboxSelectAll($p);
        //列表数据操作邦定
        this._list_form();
        //validate
        $("form.required-validate").each(function(){
            $form = $(this);
            $form.validator({
              onValid: function(validity) {
                $(validity.field).closest('.am-form-group').find('.am-alert').hide();
                 return false;
              },
              onInValid: function(validity) {
                var $field = $(validity.field);
                var $group = $field.closest('.am-u-sm-9');
                var $alert = $group.find('.am-alert');
                // 使用自定义的提示信息 或 插件内置的提示信息
                var msg = $field.data('validationMessage') || this.getValidationMessage(validity);

                if (!$alert.length) {
                  $alert = $('<div class="am-alert am-alert-danger"></div>').hide().
                    appendTo($group);
                }
                $alert.html(msg).show();
              },
              submit : function() {
                var is_submit = this.isFormValid();
                if(is_submit) {
                    return true;
                }else{
                    return false;
                }
              }
            });
        });
        //邦定搜索框
        $(":input[bind-enter]",$p).each(function(i,v){
            var $this   = $(v);
            var url = $this.attr('bind-enter');
            if(url) {
                $this.keypress(function(){
                    if(event.keyCode==13) {
                        event.preventDefault();
                        $("#list-form").attr('action',url);
                        $("#list-form").submit();
                    }
                });
            }else{
                event.preventDefault();
            }

        });

	},
    _list_form : function () {
        var confirm = this.confirm;
        var isEmpty = this.isEmpty;
        var list_form = $("#list-form");
        var $p = document;
        list_form.find("[operation-url]").each(function(i,v){
            var $this = $(v);
            var type  = $this.attr('type');

            if(type=='submit') {
                $this.click(function(event) {
                    //至少选择一条记录
                    if($this.attr('check-ids')=='true') {
                        var id_flag = $(":input[group]").attr('group');
                        var is_checked=false;
                        $(":input[name='"+id_flag+"']").each(function(ii,vv){
                            is_checked = $(vv).is(':checked') ? true : false;
                            if(is_checked) {
                                return false;
                            }

                        });
                        if(!is_checked) {
                            MY_UI.alert("请至少选择一条记录!");
                            return false;
                        }
                    }

                    if (!isEmpty($this.attr('confirm'))) {
                        event.preventDefault();
                        var url = $this.attr('operation-url');
                        list_form.attr("action", url);


                        confirm('submit', list_form, $this.attr('confirm'));
                    }else{
                        var url = $this.attr('operation-url');
                        list_form.attr("action",url);
                    }
                    
                });
            }else{
                $this.click(function(event) {
                    //至少选择一条记录
                    if($this.attr('check-ids')=='true') {
                        var id_flag = $(":input[group]").attr('group');
                        var is_checked=false;
                        $(":input[name='"+id_flag+"']").each(function(ii,vv){
                            is_checked = $(vv).is(':checked') ? true : false;
                            if(is_checked) {
                                return false;
                            }

                        });
                        if(!is_checked) {
                            MY_UI.alert("请至少选择一条记录!");
                            return false;
                        }
                    }
                    if(!isEmpty($this.attr('confirm'))) {
                        var url = $this.attr('operation-url');
                        confirm('operation',url,$this.attr('confirm'));
                    }else{
                        var url = $this.attr('operation-url');
                        self.location.href = url;
                    }
                    
                });
            }
            
        });
    },
    //confirm提示信息
    confirm : function($type,$mixed,$msg) {
        $(MY_UI._confirmBoxId).remove();
        //添加confim
        $("body").append(MY_UI.frag['my-confirm'].replace(/#msg#/g,$msg));
        $('#my-confirm').modal({
            relatedTarget: this,
            onConfirm: function(options) {
                if($type=='submit') {
                    $mixed.submit();
                }else if($type=='operation'){
                    self.location.href = $mixed;
                }
              
            },
            // closeOnConfirm: false,
            onCancel: function() {
              return false;

            }
        });
    },
    //alert提示信息
    alert : function($msg) {
        $(MY_UI._alertBoxId).remove();
        //添加confim
        $("body").append(MY_UI.frag['my-alert'].replace(/#msg#/g,$msg));
        $('#my-alert').modal();
    },
	randomChar : function(l) {
		var  x="0123456789qwertyuioplkjhgfdsazxcvbnm";
	    var  tmp="";
	    var timestamp = new Date().getTime();
	    for(var  i=0;i<  l;i++)  {
	    	tmp  +=  x.charAt(Math.ceil(Math.random()*100000000)%x.length);
	    }
	    return  timestamp+tmp;
	},
	checkForm : function(_form) {
		//return false;
		$form = $(_form);
		$form.submit();
	},
    loadArea : function(ajaxUrl,areaId,areaType) {
        $.post(ajaxUrl,{'areaId':areaId},function(data){
            if(areaType=='city'){
               $('#'+areaType).html('<option value="-1">市/县</option>');
               $('#district').html('<option value="-1">镇/区</option>');
            }else if(areaType=='district'){
               $('#'+areaType).html('<option value="-1">镇/区</option>');
            }
            if(areaType!='null'){
                $.each(data,function(no,items){
                     opt = $("<option/>").text(items.area_name).attr("value", items.area_id);
                    $('#'+areaType).append(opt);
                });
            }
        });
    },
    htmlspecialcharsDecode : function(string, quote_style) {
        //       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
        //      original by: Mirek Slugen
        //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      bugfixed by: Mateusz "loonquawl" Zalega
        //      bugfixed by: Onno Marsman
        //      bugfixed by: Brett Zamir (http://brett-zamir.me)
        //      bugfixed by: Brett Zamir (http://brett-zamir.me)
        //         input by: ReverseSyntax
        //         input by: Slawomir Kaniecki
        //         input by: Scott Cariss
        //         input by: Francois
        //         input by: Ratheous
        //         input by: Mailfaker (http://www.weedem.fr/)
        //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // reimplemented by: Brett Zamir (http://brett-zamir.me)
        //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
        //        returns 1: '<p>this -> &quot;</p>'
        //        example 2: htmlspecialchars_decode("&amp;quot;");
        //        returns 2: '&quot;'

        var optTemp = 0,
            i = 0,
            noquotes = false;
        if (typeof quote_style === 'undefined') {
            quote_style = 2;
        }
        string = string.toString()
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>');
        var OPTS = {
            'ENT_NOQUOTES'          : 0,
            'ENT_HTML_QUOTE_SINGLE' : 1,
            'ENT_HTML_QUOTE_DOUBLE' : 2,
            'ENT_COMPAT'            : 2,
            'ENT_QUOTES'            : 3,
            'ENT_IGNORE'            : 4
        };
        if (quote_style === 0) {
            noquotes = true;
        }
        if (typeof quote_style !== 'number') {
            // Allow for a single string or an array of string flags
            quote_style = [].concat(quote_style);
            for (i = 0; i < quote_style.length; i++) {
                // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
                if (OPTS[quote_style[i]] === 0) {
                    noquotes = true;
                } else if (OPTS[quote_style[i]]) {
                    optTemp = optTemp | OPTS[quote_style[i]];
                }
            }
            quote_style = optTemp;
        }
        if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
            string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
            // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
        }
        if (!noquotes) {
            string = string.replace(/&quot;/g, '"');
        }
        // Put this in last place to avoid escape being double-decoded
        string = string.replace(/&amp;/g, '&');

        return string;
    }
};

