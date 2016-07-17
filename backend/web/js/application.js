Date.prototype.fromUnixTimestamp = function (value) {
    return new Date(parseFloat(value) * 1000);
};
Date.prototype.format = function (format) {
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
Number.prototype.toFixed = function (d) {
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
(function ($) {
    $.fn.lock = function () {
        return this.unlock().each(function () {
            if ($.css(this, 'position') === 'static')
                this.style.position = 'relative';
            if ($.browser.msie)
                this.style.zoom = 1;
            $(this).append('<div id="widget-lock-ui" class="lock-ui" style="position:absolute;width:100%;height:100%;top:0;left:0;z-index:1000;background-color:#000;cursor:wait;opacity:.7;filter: alpha(opacity=70);"><div>');
        });
    };
    $.fn.unlock = function () {
        return this.each(function () {
            $('#widget-lock-ui', this).remove();
        });
    };
})(jQuery);
$(function () {
    $('#header-account-manage li.children a:first').toggle(function () {
        $(this).parent().addClass('drop').find('ul').show();
    }, function () {
        $(this).parent().removeClass('drop').find('ul').hide();
    });
});
// Art dialog default settings
//(function (artDialog) {
//    artDialog['okValue'] = '确定';
//    artDialog['cancelValue'] = '取消';
//    artDialog['title'] = '提示信息';
//})($.dialog.defaults);
var yadjet = yadjet || {};
yadjet.urls = yadjet.urls || {};
yadjet.urls = {
    'baseUrl': undefined
};
yadjet.regions = yadjet.regions || {};
$(function () {
    $('ul.tabs-common li a').on('click', function () {
        var $t = $(this),
            $widget = $t.parent().parent().parent().parent();
        $t.parent().siblings().removeClass('active');
        $t.parent().addClass('active');
        $widget.find('.tab-pane').hide();
        $widget.find('#' + $t.attr('data-toggle')).show();
        return false;
    });
});
$(document).on('click', '.search-button a', function () {
    var $t = $(this);
    if ($t.attr('data-toggle') === 'show') {
        $t.attr('data-toggle', 'hide');
        $('.form-search').hide();
    } else {
        $t.attr('data-toggle', 'show');
        $('.form-search').show();
    }

    return false;
});
// 商品相册图片上传
$(document).on('click', '#btn-add-new-goods-image-row', function () {
    var $t = $(this),
        $row = $('tr#row-0');
    if ($row.length) {
        $cloneRow = $row.clone();
        $cloneRow
            .find('input')
            .val('')
            .end()
            .find('.btns')
            .html('<a href="javascript:;" title="删除" aria-label="删除" class="btn-remove-dynamic-row"><span class="glyphicon glyphicon-trash"></span></a>');
        $('#grid-goods-images table tbody').append($cloneRow);
        $('#grid-goods-images table tbody tr').each(function (i) {
            $(this).attr('id', 'row-' + i);
        });
    } else {
        layer.alert('不存在参考行。', {icon: -1});
    }

    return false;
});
$(document).on('click', 'a.btn-remove-dynamic-row', function () {
    $(this).parent().parent().remove();
    return false;
});
// 删除商品图片
$(document).on('click', '.btn-delete-image', function () {
    if (confirm('是否删除该图片？')) {
        var $t = $(this),
            id = $t.attr('data-key');
        $.ajax({
            type: 'POST',
            url: $t.attr('data-url'),
            data: {id: id},
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
// 更新商品图片描述文字
$(document).on('blur', '.update-image-description', function () {
    var $t = $(this),
        id = $t.attr('data-key'),
        originalValue = $t.attr('data-original'),
        value = $t.val();
    if (value != originalValue) {
        $.ajax({
            type: 'POST',
            url: $t.attr('data-url'),
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
// 动态添加属性
$(document).on('click', '#btn-dynamic-add-specifications-row', function () {
    var $t = $(this),
        $tableBody = $t.parent().parent().find('tbody'),
        $cloneRow = $('#row-0').clone(false),
        indexCounter = $('#mai3-index-counter').val(),
        id, name, elements, element, attrs;
    $cloneRow.find('td.btn-render').html('<a class="btn-delete-dynamic-table-row" href="javascript:;" title="删除"><span class="glyphicon glyphicon-trash"></span></a>');
    elements = $cloneRow.find('input,select');
    for (var i = 0, l = elements.length; i < l; i++) {
        element = $(elements[i]);
        if (element.attr('id') === 'specification-valuesdata-0-id') {
            element.val(0).attr({
                id: 'specification-valuesdata-' + indexCounter + '-id',
                name: 'Specification[valuesData][' + indexCounter + '][id]'
            });
        } else {
            attrs = {};
            if (element.prev().is("input")) {
                attrs.value = '';
            } else if (element.prev().is('select')) {
                attrs.index = 0;
            } else if (element.prev().is('checkbox')) {
                attrs.checked = 'checked';
            }
            id = element.attr('id')
            if (typeof id !== typeof undefined && id !== false) {
                attrs.id = id.replace('0', indexCounter);
            }
            name = element.attr('name');
            attrs.name = name.replace('0', indexCounter);
            $(elements[i]).attr(attrs);
        }
    }
    $tableBody.append('<tr id="row-' + $('#mai3-index-counter').val() + '">' + $cloneRow.html() + '</tr>');
    $('#mai3-index-counter').val(parseInt(indexCounter) + 1);
    return false;
});
// 删除表格行记录
$(document).on('click', '.btn-delete-table-row', function () {
    var $t = $(this);
    $.ajax({
        type: 'POST',
        url: $t.attr('href'),
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
    return false;
});
// 删除表格动态行
$(document).on('click', '.btn-delete-dynamic-table-row', function () {
    $(this).parent().parent().remove();
    return false;
});
var vm = new Vue({
    el: '#mai3-item-specifications',
    data: {
        original: {},
        specifications: [],
        specificationValueCombinationList: []
    },
    methods: {
        checkSpecificationValue: function (event) {
            var $obj = $(event.target),
                value = $obj.val();
            if ($obj.prop('checked') === true) {
                var exists = this.specificationValueCombinationList.find(function (data) {
                    return data.id == value ? true : false;
                });
                if (!exists) {
                    this.specificationValueCombinationList.push({id: value, text: $('#label-' + value).text(), price: {member: 1, market: 0.3}});
                }
            } else {
                var index = _.findIndex(this.specificationValueCombinationList, function (data) {
                    return data.id == value;
                });
                if (index !== -1) {
                    this.specificationValueCombinationList.splice(index, 1);
                }
            }
        }    
    }
    
});

Vue.http.options.root = '/root';
Vue.http.headers.common['Authorization'] = 'Basic YXBpOnBhc3N3b3Jk';
