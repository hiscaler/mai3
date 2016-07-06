Date.prototype.fromUnixTimestamp = function(value) {
    return new Date(parseFloat(value) * 1000);
};
Date.prototype.format = function(format) {
    var o = {
        "M+": this.getMonth() + 1, //month 
        "d+": this.getDate(), //day 
        "h+": this.getHours(), //hour 
        "m+": this.getMinutes(), //minute 
        "s+": this.getSeconds(), //second 
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter 
        "S": this.getMilliseconds() //millisecond 
    };
    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length === 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
};
Number.prototype.toFixed = function(d) {
    var s = this + "";
    if (!d) {
        d = 0;
    }
    if (s.indexOf(".") == -1) {
        s += ".";
    }
    s += new Array(d + 1).join("0");
    if (new RegExp("^(-|\\+)?(\\d+(\\.\\d{0," + (d + 1) + "})?)\\d*$").test(s)) {
        var s = "0" + RegExp.$2, pm = RegExp.$1, a = RegExp.$3.length, b = true;
        if (a == d + 2) {
            a = s.match(/\d/g);
            if (parseInt(a[a.length - 1]) > 4) {
                for (var i = a.length - 2; i >= 0; i--) {
                    a[i] = parseInt(a[i]) + 1;
                    if (a[i] == 10) {
                        a[i] = 0;
                        b = i != 1;
                    } else {
                        break;
                    }
                }
            }
            s = a.join("").replace(new RegExp("(\\d+)(\\d{" + d + "})\\d$"), "$1.$2");

        }
        if (b) {
            s = s.substr(1);
        }
        return (pm + s).replace(/\.$/, "");
    }
    return this + "";
};

/**
 * Lock UI
 */
(function($) {
    $.fn.lock = function() {
        return this.unlock().each(function() {
            if ($.css(this, 'position') === 'static')
                this.style.position = 'relative';
            if ($.browser.msie)
                this.style.zoom = 1;
            $(this).append('<div id="widget-lock-ui" class="lock-ui" style="position:absolute;width:100%;height:100%;top:0;left:0;z-index:1000;background-color:#000;cursor:wait;opacity:.7;filter: alpha(opacity=70);"><div>');
        });
    };
    $.fn.unlock = function() {
        return this.each(function() {
            $('#widget-lock-ui', this).remove();
        });
    };
})(jQuery);

$(function() {
    $('#header-account-manage li.children a:first').toggle(function() {
        $(this).parent().addClass('drop').find('ul').show();
    }, function() {
        $(this).parent().removeClass('drop').find('ul').hide();
    });
});
// Art dialog default settings
(function(artDialog) {
    artDialog['okValue'] = '确定';
    artDialog['cancelValue'] = '取消';
    artDialog['title'] = '提示信息';
})($.dialog.defaults);

var yadjet = yadjet || {};
yadjet.urls = yadjet.urls || {};
yadjet.urls = {
    'baseUrl': undefined,
    'enterpriseView': undefined,
    'deletePatrolImage': undefined,
    'updatePatrolImageDescription': undefined,
    api: {
        enterprise: undefined,
        accident: undefined,
        patrol: undefined,
        rootCheckBase: undefined
    }
};
yadjet.regions = yadjet.regions || {};
$(function() {
    $('#map-render').css({height: $(document).height() - 90}).removeClass('lock-ui');
    // 短信发送目标设置
    $(document).on('change', '#smsruleform-target_type', function () {
        switch (parseInt($(this).val())) {
            case 1:
                $('#block-target-type-select').show();
                $('#block-target-type-query').hide();                
                break;
                
            case 2:
                $('#block-target-type-select, #block-target-type-query').hide();
                break;
                
            case 3:
                $('#block-target-type-select').hide();
                $('#block-target-type-query').show();
                break;
        }
    });
    
    // 短信发送时间设置
    $(document).on('change', '#smsruleform-time_type', function () {
        switch (parseInt($(this).val())) {
            case 1:
                $('#block-time-type-one-off').show();
                $('#block-time-type-timing-cycle').hide();                
                break;
                
            case 2:
                 $('#block-time-type-one-off').hide();
                $('#block-time-type-timing-cycle').show();
                break;
        }
    });
    
    // 添加巡查日志图片上传行
    $(document).on('click', '#btn-add-new-patrol-image-row', function () {
        var $t = $(this);
        if (!$t.hasClass('disabled')) {
            var $row = $('tr#row-0');
            if ($row.length) {
                $cloneRow = $row.clone();
                $cloneRow.find('input').attr('value', '').find(':first').focus();
                $('#patrol-images table tbody').append($cloneRow);

                // 重新计算行号以及元素 id
                $('#patrol-images table tbody tr').each(function (i) {
                    $(this).attr('id', 'row-' + i).find('td:first span').html(i + 1).end().find('td.btns a').addClass('dynamic-inserted');                 
                });
               
            } else {
                layer.alert('不存在参考行。', {icon: -1});
            }
        }
        
        return false;
    });
    
    // 删除巡查日志相关图片
    $(document).on('click', '.btn-delete-image', function () {
        if (confirm('是否删除该图片？')) {
            var $t = $(this),
                id = $t.attr('data-key');
            $.ajax({
                type: 'POST',
                url: yadjet.urls.deletePatrolImage,
                data: {
                    id: id
                },
                dataType: 'json',
                beforeSend: function (xhr) {
                    $.fn.lock();
                }, success: function (response) {
                    if (response.success) {
                        $t.parent().parent().remove();
                    } else {
                        layer.alert(response.error.message, {icon: -1});
                    }
                    $.fn.unlock();
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText, {icon: -1});
                    $.fn.unlock();
                }
            });
        }
        
        return false;
    });
    
    // 更新巡查日志图片描述文字
    $(document).on('blur', '.update-image-description', function () {
        var $t = $(this),
            id = $t.attr('data-key'),
            originalValue = $t.attr('data-original'),
            value = $t.val();
        if (value != originalValue) {
            $.ajax({
                type: 'POST',
                url: yadjet.urls.updatePatrolImageDescription,
                data: {
                    id: id,
                    description: value
                },
                dataType: 'json',
                beforeSend: function (xhr) {
                    $.fn.lock();
                }, success: function (response) {
                    if (!response.success) {
                        layer.alert(response.error.message, {icon: -1});
                    }
                    $.fn.unlock();
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText, {icon: -1});
                    $.fn.unlock();
                }
            });
        }
        
        return false;
    });
    
    // 百度地图标注
    jQuery(document).on('click', '.btn-baidu-map-picker', function () {
        var $t = $(this);
            $.ajax({
                type: 'GET',
                url: $t.attr('href'),
                data: {
                    address: $t.attr('data-address'),
                    geo: $t.attr('data-map')
                },
                beforeSend: function (xhr) {
                    $.fn.lock();
                }, success: function (response) {
                    $.dialog({
                        title: '百度地图',
                        content: response,
                        lock: true,
                        padding: '10px',
                        ok: function () {
                            var mapData = $('#geo').val();
                            if (mapData.indexOf(',') !== -1) {
                                mapData = mapData.split(',');
                               $.ajax({
                                    type: 'POST',
                                    url: $t.attr('data-update-map-url'),
                                    data: {
                                        lon: mapData[0],
                                        lat: mapData[1]
                                    }, success: function (response) {
                                        if (response.success) {
                                            $t.removeClass('unpick').addClass('is-picked');
                                        } else {
                                            $.alert('更新地图数据失败。');
                                        }
                                    }
                                }); 
                            }
                        }
                    });
                    $.fn.unlock();
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                    $.fn.unlock();
                }
            });

            return false;
        });
    
});