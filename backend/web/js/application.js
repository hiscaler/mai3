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
(function (artDialog) {
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
$(function () {
    $('ul.tabs-common li a').click(function () {
        var $t = $(this),
            $widget = $t.parent().parent().parent().parent();   
        $t.parent().siblings().removeClass('active');
        $t.parent().addClass('active');
        $widget.find('.tab-pane').hide();
        $widget.find('#' + $t.attr('data-toggle')).show();

        return false;
    });
});