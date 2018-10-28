$(document).ready(() => {
    /**
     * 切换查询方式
     */
    $("#order-type-select").change(() => {
        var orderTypeSelect = $("#order-type-select").val();
        if (orderTypeSelect == "current") {
            addCurrentSelect();
        } else if (orderTypeSelect == "day") {
            addDateSelect();
        } else if (orderTypeSelect == "order") {
            addOrderSelect()
        }
    });
    /**
     * 点击查询按钮时下一步
     */
    $(".order-search-btn").click(() => {
        var orderTypeSelect = $("#order-type-select").val();
        //按日查询
        if (orderTypeSelect == "current") {
            serachByCurrent();
        } else if (orderTypeSelect == "day") {
            var $param = $("input:radio[name=day]:checked").val()
            if ($param == "today" || $param == "yesterday") {
                //今天或昨天
                if ($(".search-top .date-select").length > 0) {
                    $(".search-top .date-select").remove();
                }
                searchByDate($param);
            } else if ($param == "select") {
                //选择日期
                if ($(".search-top .date-select").length == 0) {
                    var html = '<input type="date" name="" class="date-select form-control">';
                    $(".order-select-date").append(html);
                } else {
                    $date = $(".search-top .date-select").val();
                    if ($date == "") {
                        $("input.date-select").addClass("error");

                    } else {
                        $("input.date-select").removeClass("error");
                        searchByDate($param, $date);
                    }
                }
            }
        } else if (orderTypeSelect == "order") {
            var $order_id = $("input.order-input").val().replace(/\s+/g, "");
            if ($order_id == "") {
                $("input.order-input").addClass("error");
            } else {
                $("input.order-input").removeClass("error");
                searchByOrder($order_id)
            }
        }
    });
});

function addCurrentSelect() {
    $(".search-radio-wrap").empty();
}

function addDateSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="day" value="today"' +
        'checked="checked" />今天' +
        '</label>' +
        '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="day" value="yesterday" />昨天' +
        '</label>' +
        '<label class="search-radio order-select-date">' +
        '<input type="radio" class=".radio-inline" name="day" value="select" />选择日期' +
        '</label>';
    $(".search-radio-wrap").append(html);
}

function addOrderSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="text" name="" class="order-input form-control" placeholder="请输入订单号">' +
        '</label>';
    $(".search-radio-wrap").append(html);
}


/**
 * 将返回签到信息转换为html表格
 * @param {*} $dataArray 
 * @return html
 */
function fetchHTML($dataArray) {
    var i = 0;
    var html = '';
    while ($dataArray[i]) {
        var count = i + 1;

        html += '<tr>' +
            '<td>' + count + '</td>' +
            '<td>' + $dataArray[i].order_id + '</td>' +
            '<td>' + $dataArray[i].table_num + '</td>' +
            '<td>' + ($dataArray[i].tol_price == null ? "-- --" : $dataArray[i].tol_price) + '</td>' +
            '<td>' + ($dataArray[i].pay_status == 1 ? "已付款" : "未付款") + '</td>' +
            '<td>' + ($dataArray[i].pay_method == 1 ? "现金" : $dataArray[i].pay_method == 2 ? "支付宝" : $dataArray[i].pay_method == 3 ? "微信" : "-- --") + '</td>' +
            '<td>' + $dataArray[i].pay_time == null ? "-- --" : $dataArray[i].pay_time + '</td>' +
            '<td>' + $dataArray[i].order_note == null ? "-- --" : $dataArray[i].order_note + '</td>' +
            '</tr>';
        i++;
    }
    return html;
}

function serachByCurrent() {
    $.ajax({
        type: "GET",
        url: "../../php/admin/order.search.php?request=current&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            console.log(e);
            if (e.data.length == 0) {
                var html = '<h4>未查询到数据</h4>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {
                var html = '<table class="orderListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>订单号</th>' +
                    '<th>餐桌</th>' +
                    '<th>总价</th>' +
                    '<th>是否已付款</th>' +
                    '<th>付款方式</th>' +
                    '<th>付款时间</th>' +
                    '<th>备注</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                html += fetchHTML(e.data);
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".orderListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".orderListTable").displayInfo();' +
                    '$(".orderListTable").show();' +
                    '})' +
                    '</script>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            }
        },
        error: (err) => {
            console.log(err);
        }
    })
}

/**
 * 按日期查询
 * @param {*} param 查询方式
 * @param {*} date 日期
 */
function searchByDate(param, date) {
    if (!date) {
        //今天或昨天
        $url = "../../php/admin/order.search.php?request=date&param=" + param + "&admin_id=" + getUserInfo().admin_id;
    } else {
        //选择日期
        $url = "../../php/admin/order.search.php?request=date&param=" + param + "&date=" + date + "&admin_id=" + getUserInfo().admin_id;
    }
    $.ajax({
        type: "GET",
        url: $url,
        dataType: "JSON",
        success: (e) => {
            console.log(e);
            if (e.data.length == 0) {
                var html = '<h4>未查询到数据</h4>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {
                var html = '<table class="orderListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>订单号</th>' +
                    '<th>餐桌</th>' +
                    '<th>总价</th>' +
                    '<th>是否已付款</th>' +
                    '<th>付款方式</th>' +
                    '<th>付款时间</th>' +
                    '<th>备注</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                html += fetchHTML(e.data);
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".orderListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".orderListTable").displayInfo();' +
                    '$(".orderListTable").show();' +
                    '})' +
                    '</script>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            }
        },
        error: (err) => {
            console.log(err);
        }
    })
}


function searchByOrder(order_id) {
    $.ajax({
        type: "GET",
        url: "../../php/admin/order.search.php?request=order&order_id=" + order_id + "&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            console.log(e);
            if (e.data.length == 0) {
                var html = '<h4>未查询到数据</h4>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {
                var html = '<div class="order-info-box col-xs-12">' +
                    '<div class="order-info-wrap">' +
                    '<div class="order-id"><h3>' + e.data.order_id + '</h3></div>' +
                    '</div>' +
                    '<div class="order-info-box">' +
                    '<h5><b>餐桌号： </b>' + e.data.table_num + '</h5>' +
                    '<h5><b>总价： </b>' + e.data.tol_price + '</h5>' +
                    '<h5><b>付款状态： </b>' + e.data.pay_status + '</h5>' +
                    '<h5><b>付款方式： </b>' + e.data.pay_method + '</h5>' +
                    '<h5><b>付款时间： </b>' + e.data.pay_time + '</h5>' +
                    '<h5><b>备注： </b>' + e.data.order_note + '</h5>' +
                    '</div><hr><h4>点菜列表</h4>';
                html += '<div style="padding:0 15px;"><table>' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>图片</th>' +
                    '<th>菜名</th>' +
                    '<th>价格（下单时）</th>' +
                    '<th>状态</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                for (var i = 0; e.dish_list[i]; i++) {
                    var count = i + 1;
                    html += '<tr>' +
                        '<td>' + count + '</td>' +
                        '<td><img src="' + e.dish_list[i].dish_pic + '"/>></td>' +
                        '<td>' + e.dish_list[i].dish_name + '</td>' +
                        '<td>' + e.dish_list[i].dish_price + '</td>' +
                        '<td>' + (e.dish_list[i].sal_status == 1 ? "已下单" : e.dish_list[i].sal_status == 2 ? "正在做" : e.dish_list[i].sal_status == 3 ? "做完了" : "-- --") + '</td>' +
                        '</tr>';
                }
                html += '</tbody>' +
                    '</table></div>' +
                    '</div>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            }
        },
        error: (err) => {
            console.log(err);
        }
    })
}