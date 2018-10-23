$(document).ready(() => {
    /**
     * 切换查询方式
     */
    $("#presence-type-select").change(() => {
        var presenceTypeSelect = $("#presence-type-select").val();
        if (presenceTypeSelect == "day") {
            addDateSelect();
        } else if (presenceTypeSelect == "week") {
            addWeekSelect();
        } else if (presenceTypeSelect == "month") {
            addMonthSelect();
        } else if (presenceTypeSelect == "season") {
            addSeasonSelect();
        } else if (presenceTypeSelect == "employee") {
            addEmployeeSelect();
        }
    });
    /**
     * 点击查询按钮时下一步
     */
    $(".presence-search-btn").click(() => {
        var presenceTypeSelect = $("#presence-type-select").val();
        //按日查询
        if (presenceTypeSelect == "day") {
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
                    $(".presence-select-date").append(html);
                } else {
                    $date = $(".search-top .date-select").val();
                    if ($date == "") {
                        window.wxc.xcConfirm("请选择日期", window.wxc.xcConfirm.typeEnum.warning);
                    } else {
                        searchByDate($param, $date);
                    }
                }
            }

        } else if (presenceTypeSelect == "week") {
            var $param = $("input:radio[name=week]:checked").val()
            searchByWeek($param);
        } else if (presenceTypeSelect == "month") {
            var $param = $("input:radio[name=month]:checked").val()
            if ($param == "this" || $param == "last") {
                searchByMonth($param);
            } else if ($param == "select") {
                //选择月份
                if ($(".search-top .month-select").length == 0) {
                    var html = '<input type="month" name="" class="month-select form-control">';
                    $(".presence-select-month").append(html);
                } else {
                    $month = $(".search-top .month-select").val();
                    if ($month == "") {
                        window.wxc.xcConfirm("请选择月份", window.wxc.xcConfirm.typeEnum.warning);
                    } else {
                        searchByMonth($param, $month);
                    }
                }
            }

        } else if (presenceTypeSelect == "season") {
            var $param = $("input:radio[name=season]:checked").val()
            searchBySeason($param);
        } else if (presenceTypeSelect == "employee") {
            var $employee_name = $("input.employee-input").val()
            searchByEmployee($employee_name)
        }
    });
});

//换成按日查询
function addDateSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="day" value="today"' +
        'checked="checked" />今天' +
        '</label>' +
        '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="day" value="yesterday" />昨天' +
        '</label>' +
        '<label class="search-radio presence-select-date">' +
        '<input type="radio" class=".radio-inline" name="day" value="select" />选择日期' +
        '</label>';
    $(".search-radio-wrap").append(html);
}
//换成按周查询
function addWeekSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="week" value="this"' +
        'checked="checked" />本周' +
        '</label>' +
        '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="week" value="last" />上周' +
        '</label>';
    $(".search-radio-wrap").append(html);
}
//换成按月查询
function addMonthSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="month" value="this"' +
        'checked="checked" />本月' +
        '</label>' +
        '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="month" value="last" />上月' +
        '</label>' +
        '<label class="search-radio presence-select-month">' +
        '<input type="radio" class=".radio-inline" name="month" value="select" />选择月份' +
        '</label>';
    $(".search-radio-wrap").append(html);
}
//换成按季度查询
function addSeasonSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="season" value="this"' +
        'checked="checked" />本季度' +
        '</label>' +
        '<label class="search-radio">' +
        '<input type="radio" class=".radio-inline" name="season" value="last" />上季度' +
        '</label>';
    $(".search-radio-wrap").append(html);
}

//换成按员工查询
function addEmployeeSelect() {
    $(".search-radio-wrap").empty();
    var html = '<label class="search-radio">' +
        '<input type="text" name="" class="employee-input form-control" placeholder="请输入员工姓名">' +
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
        var gender = $dataArray[i].gender;
        if (gender == 1) {
            gender = "男";
        } else if (gender == 0) {
            gender = "女";
        }
        var $employee_type = $dataArray[i].employee_type;
        if ($employee_type == 1) {
            $employee_type = "管理人员";
        } else if ($employee_type == 2) {
            $employee_type = "服务员";
        } else if ($employee_type == 3) {
            $employee_type = "前台";
        } else if ($employee_type == 4) {
            $employee_type = "厨师";
        } else if ($employee_type == 5) {
            $employee_type = "保洁";
        } else if ($employee_type == 6) {
            $employee_type = "仓库管理员";
        } else if ($employee_type == 7) {
            $employee_type = "会计";
        } else if ($employee_type == 8) {
            $employee_type = "其他";
        }
        var hasPresented = '';
        if ($dataArray[i].hasPresented == 1) {
            hasPresented = '是';
        } else if ($dataArray[i].hasPresented == 0) {
            hasPresented = '否';
        }
        html += '<tr>' +
            '<td>' + count + '</td>' +
            '<td>' + $dataArray[i].name + '</td>' +
            '<td>' + gender + '</td>' +
            '<td>' + $employee_type + '</td>' +
            '<td>' + $dataArray[i].sign_time + '</td>' +
            '<td>' + hasPresented + '</td>' +
            '<td><a class="table-update-btn update-employee" href = "javascript:void(0);" onclick="update_presence("' +
            $dataArray[i].employee_id +
            '")"><i class="iconfont icon-update"></i></a></td>' +
            '</tr>';
        i++;
    }
    return html;
}


/**
 * 按日期查询
 * @param {*} param 查询方式
 * @param {*} date 日期
 */
function searchByDate(param, date) {
    if (!date) {
        //今天或昨天
        $url = "../../php/admin/presence.search.php?request=date&param=" + param + "&admin_id=" + getUserInfo().admin_id;
    } else {
        //选择日期
        $url = "../../php/admin/presence.search.php?request=date&param=" + param + "&date=" + date + "&admin_id=" + getUserInfo().admin_id;
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
                var html = '<table class="presenceListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>姓名</th>' +
                    '<th>性别</th>' +
                    '<th>员工类型</th>' +
                    '<th>签到时间</th>' +
                    '<th>是否签到</th>' +
                    '<th>操作</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                html += fetchHTML(e.data);
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".presenceListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".presenceListTable").displayInfo();' +
                    '$(".presenceListTable").show();' +
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
 * 按周查询
 * @param {*} param 查询方式
 */
function searchByWeek(param) {
    $.ajax({
        type: "GET",
        url: "../../php/admin/presence.search.php?request=week&param=" + param + "&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (e.data.length == 0) {
                var html = '<h4>未查询到数据</h4>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {
                var html = '<div class="col-xs-12" style="text-align: center;">' +
                    '<h3 style="color: #000;">出勤率</h3>' +
                    '<h2 style="color: #1296db;">' + e.ratio + '</h2>' +
                    '<hr></div>' +
                    '<h4 style="text-align: center;">未签到员工列表</h4>' +
                    '<table class="presenceListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>姓名</th>' +
                    '<th>性别</th>' +
                    '<th>员工类型</th>' +
                    '<th>签到时间</th>' +
                    '<th>是否签到</th>' +
                    '<th>操作</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                html += fetchHTML(e.data);
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".presenceListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".presenceListTable").displayInfo();' +
                    '$(".presenceListTable").show();' +
                    '})' +
                    '</script>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            }
        },
        error: (err) => {
            console.log(err)
        }
    })
}

/**
 * 按月份查询
 * @param {*} param 查询方式
 * @param {*} month 月份
 */
function searchByMonth(param, month) {
    if (!month) {
        //this/last
        $url = "../../php/admin/presence.search.php?request=month&param=" + param + "&admin_id=" + getUserInfo().admin_id;
    } else {
        //选择月份
        $url = "../../php/admin/presence.search.php?request=month&param=" + param + "&month=" + month + "&admin_id=" + getUserInfo().admin_id;
    }
    $.ajax({
        type: "GET",
        url: $url,
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (e.data.length == 0) {
                var html = '<h4>未查询到数据</h4>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {
                var html = '<div class="col-xs-12" style="text-align: center;">' +
                    '<h3 style="color: #000;">出勤率</h3>' +
                    '<h2 style="color: #1296db;">' + e.ratio + '</h2>' +
                    '<hr></div>' +
                    '<h4 style="text-align: center;">未签到员工列表</h4>' +
                    '<table class="presenceListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>姓名</th>' +
                    '<th>性别</th>' +
                    '<th>员工类型</th>' +
                    '<th>签到时间</th>' +
                    '<th>是否签到</th>' +
                    '<th>操作</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                html += fetchHTML(e.data);
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".presenceListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".presenceListTable").displayInfo();' +
                    '$(".presenceListTable").show();' +
                    '})' +
                    '</script>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            }
        },
        error: (err) => {
            console.log(err)
        }
    })
}

/**
 * 按季度查询
 * @param {*} param 查询方式
 */
function searchBySeason(param) {
    $.ajax({
        type: "GET",
        url: "../../php/admin/presence.search.php?request=season&param=" + param + "&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (e.data.length == 0) {
                var html = '<h4>未查询到数据</h4>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {
                var html = '<div class="col-xs-12" style="text-align: center;">' +
                    '<h3 style="color: #000;">出勤率</h3>' +
                    '<h2 style="color: #1296db;">' + e.ratio + '</h2>' +
                    '<hr></div>' +
                    '<h4 style="text-align: center;">未签到员工列表</h4>' +
                    '<table class="presenceListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>姓名</th>' +
                    '<th>性别</th>' +
                    '<th>员工类型</th>' +
                    '<th>签到时间</th>' +
                    '<th>是否签到</th>' +
                    '<th>操作</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                html += fetchHTML(e.data);
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".presenceListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".presenceListTable").displayInfo();' +
                    '$(".presenceListTable").show();' +
                    '})' +
                    '</script>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            }
        },
        error: (err) => {
            console.log(err)
        }
    })
}

/**
 * 按员工查询
 * @param {*} employee_name 员工ID
 */
function searchByEmployee(employee_name) {
    $.ajax({
        type: "GET",
        url: "../../php/admin/presence.search.php?request=employee&employee_name='" + employee_name + "'&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (1) {
                var gender = e.employee_info.gender;
                if (gender == 1) {
                    gender = "男";
                } else if (gender == 0) {
                    gender = "女";
                }
                var $employee_type = e.employee_info.employee_type;
                if ($employee_type == 1) {
                    $employee_type = "管理人员";
                } else if ($employee_type == 2) {
                    $employee_type = "服务员";
                } else if ($employee_type == 3) {
                    $employee_type = "前台";
                } else if ($employee_type == 4) {
                    $employee_type = "厨师";
                } else if ($employee_type == 5) {
                    $employee_type = "保洁";
                } else if ($employee_type == 6) {
                    $employee_type = "仓库管理员";
                } else if ($employee_type == 7) {
                    $employee_type = "会计";
                } else if ($employee_type == 8) {
                    $employee_type = "其他";
                }
                var html = '<div class="employee-info-box col-xs-12 col-md-4">' +
                    '<div class="employee-pic-wrap"><img class="employeePic" src="' + e.employee_info.employee_pic + '" /></div>' +
                    '<div class="employee-info-wrap">' +
                    '<div class="employee-name">' + e.employee_info.name + '</div>' +
                    '<div class="employee-type">' + $employee_type + '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="employee-info-box col-xs-12 col-md-8">' +
                    '<div class="col-xs-0 col-md-12" style="padding: 5px;"></div>' +
                    '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">' +
                    '<h5 class="emp-gender">性别： ' + gender + '</h5>' +
                    '<h5 class="emp-age">年龄： ' + e.employee_info.age + '</h5>' +
                    '</div>' +
                    '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">' +
                    '<h5 class="emp-working-year">工龄： ' + e.employee_info.working_year + '</h5>' +
                    '<h5 class="emp-salary">工资： ' + e.employee_info.salary + '</h5>' +
                    '</div>' +
                    '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">' +
                    '<h5 class="emp-time">入职时间： ' + e.employee_info.employ_time + '</h5>' +
                    '<h5 class="emp-phone">手机号码： ' + e.employee_info.phone_num + '</h5>' +
                    '</div>' +
                    '</div>';
                html += '<div class="col-xs-12"><hr></div><h4 class="col-xs-12" style="text-align: center;">未签到日期</h4>' +
                    '<table class="presenceListTable" style="display: none;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>序号</th>' +
                    '<th>签到时间</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';
                var i = 0;
                while (e.presence_data[i]) {
                    var count = i + 1;
                    html += '<tr>' +
                        '<td>' + count + '</td>' +
                        '<td>' + e.presence_data[i].sign_time.substring(0, 10) + '</td>' +
                        '</tr>'
                    i++;
                }
                html += '</tbody>' +
                    '</table>' +
                    '<script>' +
                    '$(document).ready(() => {' +
                    '$(".presenceListTable").DataTable({' +
                    'autoWidth: true,' +
                    'responsive: true' +
                    '});' +
                    '$(".presenceListTable").displayInfo();' +
                    '$(".presenceListTable").show();' +
                    '})' +
                    '</script>';
                $("#search-result-wrap").empty();
                $("#search-result-wrap").append(html);
            } else {

            }
        },
        error: (err) => {
            console.log(err)
        }
    })
}