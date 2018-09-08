var myCrop;
require(["jquery", 'hammer', 'tomPlugin', "tomLib", 'hammer.fake', 'hammer.showtouch'], function($, hammer, plugin, T) {
    document.addEventListener("touchmove", function(e) {

        })
        //初始化图片大小128*128
    var opts = {
            cropWidth: 256,
            cropHeight: 256
        },
        $file = $("#file"),
        previewStyle = {
            x: 0,
            y: 0,
            scale: 1,
            rotate: 0,
            ratio: 1
        },
        transform = T.prefixStyle("transform"),
        $previewResult = $("#previewResult"),
        $previewBox = $(".preview-box"),
        $rotateBtn = $("#rotateBtn"),
        $getFile = $("#getFile"),
        $preview = $("#preview"),
        $uploadPage = $("#uploadPage"),
        $mask = $(".upload-mask"),
        maskCtx = $mask[0].getContext("2d");

    //这是插件调用主体
    myCrop = T.cropImage({
        bindFile: $file,
        enableRatio: false, //是否启用高清,高清得到的图片会比较大
        canvas: $(".photo-canvas")[0], //放一个canvas对象
        cropWidth: opts.cropWidth, //剪切大小
        cropHeight: opts.cropHeight,
        bindPreview: $preview, //绑定一个预览的img标签
        useHammer: true, //是否使用hammer手势，否的话将不支持缩放
        oninit: function() {

        },
        onLoad: function(data) {
            //用户每次选择图片后执行回调
            $("body").css("overflow", "hidden");
            resetUserOpts();
            previewStyle.ratio = data.ratio;
            $preview.attr("src", data.originSrc).css({
                width: data.width,
                height: data.height
            }).css(transform, 'scale(' + 1 / previewStyle.ratio + ')');
            myCrop.setCropStyle(previewStyle)
        }
    });

    function resetUserOpts() {
        $(".photo-canvas").hammer('reset');
        previewStyle = {
            scale: 1,
            x: 0,
            y: 0,
            rotate: 0
        };
        $previewResult.attr("src", '');
        $preview.attr("src", '')
    }
    $(".photo-canvas").hammer({
            gestureCb: function(o) {
                //每次缩放拖拽的回调
                $.extend(previewStyle, o);
                $preview.css(transform, "translate3d(" + previewStyle.x + 'px,' + previewStyle.y + "px,0) rotate(" + previewStyle.rotate + "deg) scale(" + (previewStyle.scale / previewStyle.ratio) + ")")
            }
        })
        //选择图片
    $rotateBtn.on("click", function() {
            if (previewStyle.rotate == 360) {
                previewStyle.rotate = 0;
            } else {
                previewStyle.rotate += 90;
            }
            myCrop.setCropStyle(previewStyle)
            $preview.css(transform, "translate3d(" + previewStyle.x + 'px,' + previewStyle.y + "px,0) rotate(" + previewStyle.rotate + "deg) scale(" + (previewStyle.scale / previewStyle.ratio) + ")")
        })
        //获取图片并关闭弹窗返回到表单界面
    $getFile.on("click", function() {
            $("body").css("overflow", "auto");
            var src;
            $uploadPage.hide();
            myCrop.setCropStyle(previewStyle)
                //自定义getCropFile({type:"png",background:"red",lowDpi:true})
            src = myCrop.getCropFile({});
            $previewResult.attr("src", src)
                //you can upload new img file :cheers:)
        })
        //上传文件按钮&&关闭弹窗按钮
    $(document).delegate("#file", "click", function() {
        $uploadPage.show();
    }).delegate("#closeCrop", "click", function() {
        $uploadPage.hide();
        resetUserOpts();
        myCrop.setCropStyle(previewStyle);

    })
    $file.one("click", function() {
        $uploadPage.show();
        $mask.prop({
            width: $mask.width(),
            height: $mask.height()
        })
        maskCtx.fillStyle = "rgba(0,0,0,0.7)";
        maskCtx.fillRect(0, 0, $mask.width(), $mask.height());
        maskCtx.fill();
        maskCtx.clearRect(($mask.width() - opts.cropWidth) / 2, ($mask.height() - opts.cropHeight) / 2, opts.cropWidth, opts.cropHeight)
    })
});


$(document).ready(() => {
    $.ajax({
        type: "GET",
        url: "../../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            $(".userPic").attr('src', e.admin_pic + "?" + num);
            $(".online-user").html(e.admin_name);
            admin_type = e.admin_type;
            if (admin_type == 1) {
                admin_type = "超级管理员";
            } else if ($admin_type == 2) {
                admin_type = "管理员";
            } else if ($admin_type == 3) {
                admin_type = "财务管理";
            } else if ($admin_type == 4) {
                admin_type = "港库管理";
            }
            $(".user-type").html(admin_type);
        },
        error: (err) => {
            console.log(err)
        }
    })



    $("#addEmployee-form").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            name: {
                required: true,
                chinese: true,
            },
            age: {
                required: true,
                digits: true,
                rangelength: [1, 2]
            },
            salary: {
                required: true,
                digits: true,
                rangelength: [3, 5]
            },
            phone: {
                required: true,
                phone: true
            }
        },
        messages: { //验证错误信息

            name: {
                required: "请输入姓名",
                chinese: "请输入正确的姓名"
            },
            age: {
                required: "请输入年龄",
                digits: "请输入正确的年龄",
                rangelength: "请输入正确的年龄"
            },
            salary: {
                required: "请输入工资",
                digits: "请输入正确的工资",
                rangelength: "请输入正确的工资",
            },
            phone: {
                required: "请输入手机号码",
                phone: "请输入正确的手机号码"
            }
        },
        submitHandler: function(form) { //通过之后回调
            var userPicData = $("#previewResult")[0].src;
            if (userPicData.indexOf("data:") < 0) {
                userPicData = '';
            }
            var name = $("#name").val();
            var gender = $("input[name='gender']:checked").val();
            var age = $("#age").val();
            var salary = $("#salary").val();
            var phone_num = $("#phone_num").val();
            var employee_type = $("#employee_type").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../php/admin.add.php",
                dataType: "JSON",
                data: {
                    "request": "add_employee",
                    "admin_id": admin_id,
                    "name": name,
                    "gender": gender,
                    "age": age,
                    "salary": salary,
                    "phone_num": phone_num,
                    "employee_type": employee_type,
                    "userPicData": userPicData
                },
                success: (e) => {
                    console.log(e)
                },
                error: (err) => {
                    console.log(err)
                }
            })
        }
    });
    $.validator.addMethod("chinese", function(value, element) {
        var chinese = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
        return this.optional(element) || (chinese.test(value));
    }, "");

    $.validator.addMethod("phone", function(value, element) {
        var phone = /^1[34578]\d{9}$/;
        return this.optional(element) || (phone.test(value));
    }, "");
});