/**
 * Lock UI
 */
;
(function ($) {
    $.fn.lock = function () {
        this.unlock();
        $('body').append('<div id="widget-lock-ui" class="lock-ui" style="position:fixed;width:100%;height:100%;top:0;left:0;z-index:1000;background-color:#000;cursor:wait;opacity:.7;filter: alpha(opacity=70);"><div>');
    };
    $.fn.unlock = function () {
        $('#widget-lock-ui').remove();
    };
})(jQuery);

$(document).ready(function () {
    var sparklineCharts = function () {
        $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 52], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#1ab394',
            fillColor: "transparent"
        });

        $("#sparkline2").sparkline([32, 11, 25, 37, 41, 32, 34, 42], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#1ab394',
            fillColor: "transparent"
        });

        $("#sparkline3").sparkline([34, 22, 24, 41, 10, 18, 16, 8], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#1C84C6',
            fillColor: "transparent"
        });
    };

    var sparkResize;

    $(window).resize(function (e) {
        clearTimeout(sparkResize);
        sparkResize = setTimeout(sparklineCharts, 500);
    });

    sparklineCharts();




    var data1 = [
        [0, 4], [1, 8], [2, 5], [3, 10], [4, 4], [5, 16], [6, 5], [7, 11], [8, 6], [9, 11], [10, 20], [11, 10], [12, 13], [13, 4], [14, 7], [15, 8], [16, 12]
    ];
    var data2 = [
        [0, 0], [1, 2], [2, 7], [3, 4], [4, 11], [5, 4], [6, 2], [7, 5], [8, 11], [9, 5], [10, 4], [11, 1], [12, 5], [13, 2], [14, 5], [15, 2], [16, 0]
    ];
    $("#flot-dashboard5-chart").length && $.plot($("#flot-dashboard5-chart"), [
        data1, data2
    ],
        {
            series: {
                lines: {
                    show: false,
                    fill: true
                },
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 1,
                    fill: 0.4
                },
                points: {
                    radius: 0,
                    show: true
                },
                shadowSize: 2
            },
            grid: {
                hoverable: true,
                clickable: true,
                borderWidth: 2,
                color: 'transparent'
            },
            colors: ["#1ab394", "#1C84C6"],
            xaxis: {
            },
            yaxis: {
            },
            tooltip: false
        }
    );

    $(document).on('click', 'a.btn-search', function () {
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
            if (element.attr('id')==='specification-valuesdata-0-id') {
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

});