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
            if ($.css(this, 'position') === 'static') {
                this.style.position = 'relative';
            }
            if ($.browser.msie) {
                this.style.zoom = 1;
            }
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
    $('#header-account-manage li.change-tenant a:first').on('click', function () {
        $(this).parent().find('ul').show();
    });
    $(document).on('pjax:error', function (xhr, textStatus, error, options) {
        console.log(xhr);
        console.log(textStatus);
        console.log(error);
        console.log(options);
        layer.alert(textStatus.responseText);
    });
    $('.ajax').on('click', function () {
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $this.remove();
                } else {
                    layer.alert(response.error.message);
                }
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            }
        });
        
        return false;
    });
});

function clone(myObj) {
    if (typeof (myObj) != 'object' || myObj == null)
        return myObj;
    var newObj = new Object();
    for (var i in myObj) {
        newObj[i] = clone(myObj[i]);
    }

    return newObj;
}

var yadjet = yadjet || {};
yadjet.icons = yadjet.icon || {};
yadjet.icons.boolean = [
    '/images/no.png',
    '/images/yes.png'
];
yadjet.utils = yadjet.utils || {
    getCsrfParam: function () {
        return $('meta[name=csrf-param]').attr('content');
    },
    getCsrfToken: function () {
        return $('meta[name=csrf-token]').attr('content');
    }
};
yadjet.actions = yadjet.actions || {
    toggle: function (selector, url) {
        var dataExt = arguments[2] ? arguments[2] : {};
        var trData = arguments[3] ? arguments[3] : [];
        $(selector).on('click', function (event) {
            event.stopPropagation();
            var $this = $(this);
            var $tr = $this.parent().parent();
            var data = {
                id: $tr.attr('data-key'),
                _csrf: yadjet.utils.getCsrfToken()
            };
            for (var key in dataExt) {
                data[key] = dataExt[key];
            }
            console.info(trData);
            for (var key in trData) {
                // `data-key` To `dataKey`
                var t = trData[key].toLowerCase();
                t = t.replace(/\b\w+\b/g, function (word) {
                    return word.substring(0, 1).toUpperCase() + word.substring(1);
                });
                t = t.replace('-', '');
                t = t.substring(0, 1).toLowerCase() + t.substring(1);
                data[t] = $tr.attr('data-' + trData[key]);
            }
            console.info(data);
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function (xhr) {
                    $this.hide().parent().addClass('running-c-c');
                }, success: function (response) {
                    if (response.success) {
                        var data = response.data;
                        $this.attr('src', yadjet.icons.boolean[data.value ? 1 : 0]);
                        if (data.updatedAt) {
                            $tr.find('td.rb-updated-at').html(data.updatedAt);
                        }
                        if (data.updatedBy) {
                            $tr.find('td.rb-updated-by').html(data.updatedBy);
                        }
                        if (data.onOffDatetime) {
                            $tr.find('td.rb-on-off-datetime').html(data.onOffDatetime);
                        }
                    } else {
                        layer.alert(response.error.message);
                    }
                    $this.show().parent().removeClass('running-c-c');
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                    $this.show().parent().removeClass('running-c-c');
                }
            });

            return false;
        });
    },
    gridColumnConfig: function () {
        jQuery(document).on('click', '#menu-buttons li a.grid-column-config', function () {
            var $this = $(this);
            $.ajax({
                type: 'GET',
                url: $this.attr('href'),
                beforeSend: function (xhr) {
                    $.fn.lock();
                }, success: function (response) {
                    layer.open({
                        title: '表格栏位设定',
                        content: response,
                        lock: true,
                        padding: '10px',
                        yes: function () {
                            $.pjax.reload({container: '#' + $this.attr('data-reload-object')});
                        }
                    });
                    $.fn.unlock();
                }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                    $.fn.unlock();
                }
            });

            return false;
        });
    }
};

yadjet.actions.gridColumnConfig();

var Mai3 = Mai3 || {};
Mai3.urls = Mai3.urls || {};
Mai3.urls = {
    baseUrl: undefined,
    product: {
        create: undefined,
        view: undefined,
        update: undefined,
        delete: undefined        
    },
    item: {
        delete: undefined
    },
    meta: {
        validators: undefined,
        metaValidators: undefined,
        objectMeta: undefined
    }
};
Mai3.reference = Mai3.reference || {};
Mai3.reference = {
    product: {
        snPrefix: null,
        name: null,
        price: {
            market: 0,
            shop: 0,
            member: 0
        }
    }
};

$(function () {
    $('.tabs-common li a').on('click', function () {
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
            var elementType = $(elements[i]).attr('type');
            attrs = {};
            if (elementType === 'text') {
                attrs.value = '';
            } else if (elements[i].tagName.toLowerCase() == 'select') {
                // @todo
                // $(elements[i])[0].selectedIndex = 5;
            } else if (elementType === 'checkbox') {
                attrs.checked = 'checked';
            }
            id = element.attr('id');
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
    el: '#mai3-app',
    data: {
        original: {},
        _items: [],
        items: [],
        specifications: [],
        rawSpecificationValues: [],
        type: {
            properties: []
        },
        product: {
            properties: []
        },
        // 所有数据验证规则
        validators: {},
        meta: {
            // 某个数据项的验证规则
            validators: {}
        },
        // 对象的 meta 信息
        metaObjects: {}
    },
    methods: {
        // 判断对象是否为空
        isEmptyObject: function (e) {
            var t;  
            for (t in e)  
                return !1;
            return !0;
        },
        checkSpecificationValue: function (event) {
            var $obj = $(event.target),
                specificationId = $obj.attr('data-specification'),
                valueId = $obj.val(),
                rawSpecificationValues = [];
            if ($obj.prop('checked') === true) {
                if (this.rawSpecificationValues[specificationId] === undefined) {
                    this.rawSpecificationValues[specificationId] = {
                        id: specificationId,
                        values: []
                    };
                }
                this.rawSpecificationValues[specificationId]['values'].push({
                    id: valueId,
                    name: $('#label-' + valueId).text()
                });
            } else {
                var index = _.findIndex(this.rawSpecificationValues[specificationId]['values'], function (data) {
                    return data.id == valueId;
                });
                if (index !== -1) {
                    this.rawSpecificationValues[specificationId]['values'].splice(index, 1);
                }
            }

            arrResult = [];
            for (var z = 0, l = this.rawSpecificationValues[specificationId].values.length; z < l; z++) {
                arrResult[arrResult.length] = {
                    _id: [this.rawSpecificationValues[specificationId].values[z].id],
                    id: this.rawSpecificationValues[specificationId].values[z].id,
                    name: this.rawSpecificationValues[specificationId].values[z].name
                };
            }

            for (var i in this.rawSpecificationValues) {
                if (this.rawSpecificationValues[i].id == specificationId) {
                    continue;
                }
                arrResult = CombineArray(arrResult, this.rawSpecificationValues[i].values);
            }

            function CombineArray(arr1, arr2) {
                var arrResultSub = new Array();
                for (var i = 0; i < arr1.length; i++) {
                    for (var k = 0; k < arr2.length; k++) {
                        arrResultSub[arrResultSub.length] = {
                            _id: arr1[i]['_id'].concat([arr2[k].id]),
                            id: arr1[i]['id'] + "," + arr2[k].id,
                            name: arr1[i]['name'] + "、" + arr2[k].name
                        };
                    }
                }
                return arrResultSub;
            }
            console.info(arrResult);
            function generateSkuSn(prefix) {
                var sn = '';
                prefix = prefix === undefined || prefix === null ? '' : prefix;
                for (var i = 1, l = 100; i <= l; i++) {

                }

                return sn;
            }

            // 添加前导零
            function zeroFill(number, width)
            {
                width -= number.toString().length;
                if (width > 0)
                {
                    return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
                }
                return number + ""; // always return a string
            }

            this.items = [];
            // 原来的数据也需要保存
            for (i in this._items) {
                this.items.push(this._items[i]);
            }
            
            console.info(arrResult);
            for (i in arrResult) {
                var exists = false;
                for (j in this._items) {
                    if (_.intersection(this._items[j].specificationValueString.split(','), arrResult[i]._id).length === arrResult[i]._id.length) {
                        this.items.push(this._items[j]);
                        exists = true;
                        break;
                    }
                }
                console.info(exists);
                if (exists) {
                    continue;
                }
                
                this.items.push({
                    _isNew: true,
                    specificationValueArray: arrResult[i]._id,
                    specificationValueString: arrResult[i].id,
                    sn: Mai3.reference.product.snPrefix,
                    _snError: _.isEmpty(this._items) && i === 0 ? false : true,
                    name: Mai3.reference.product.name + ' ' + arrResult[i].name,
                    text: arrResult[i].name,
                    price: {
                        market: Mai3.reference.product.price.market,
                        shop: Mai3.reference.product.price.shop,
                        member: Mai3.reference.product.price.member
                    },
                    default: false,
                    enabled: true
                });
            }
        }
    },
    computed: {
        // 当前 Meta 对象数据验证规则以及规则相关属性定义
        metaValidators: function () {
            var validators = [], validator;
            for (var validatorName in this.validators) {
                validator = clone(this.validators[validatorName]);
                validator.name = validatorName;
                validator.active = false;
                for (var j in this.meta.validators) {
                    if (this.meta.validators[j].name === validatorName) {
                        validator.active = true;
                        validator.options = this.meta.validators[j].options;
                        break;
                    }
                }
                validators.push(validator);
            }

            return validators;
        }
    }
});

Vue.http.options.root = '/root';
Vue.http.headers.common['Authorization'] = 'Basic YXBpOnBhc3N3b3Jk';

// 打开商品属性处理表单
$('a#btn-add-type-property').on('click', function () {
    var $t = $(this);
    layer.open({
        type: 2,
        area: ['500px', '700px'],
        content: [$t.attr('href'), 'no'],
        btn: ['保存', '关闭'],
        yes: function (index, layero) {
        }, btn2: function (index, layero) {
        }
    });
    
    return false;
});