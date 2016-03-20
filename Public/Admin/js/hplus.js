//region hplus
$(document).ready(function () {

    // MetsiMenu
    $('#side-menu').metisMenu();

    // Collapse ibox function
    $('.collapse-link').click(function () {
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        var content = ibox.find('div.ibox-content');
        content.slideToggle(200);
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        ibox.toggleClass('').toggleClass('border-bottom');
        setTimeout(function () {
            ibox.resize();
            ibox.find('[id^=map-]').resize();
        }, 50);
    });

    // Close ibox function
    $('.close-link').click(function () {
        var content = $(this).closest('div.ibox');
        content.remove();
    });

    // Small todo handler
    $('.check-link').click(function () {
        var button = $(this).find('i');
        var label = $(this).next('span');
        button.toggleClass('fa-check-square').toggleClass('fa-square-o');
        label.toggleClass('todo-completed');
        return false;
    });

    // Append config box / Only for demo purpose
    //$.get("skin-config.html", function (data) {
    //    $('body').append(data);
    //});

    // minimalize menu
    $('.navbar-minimalize').click(function () {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    })

    // tooltips
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // Move modal to body
    // Fix Bootstrap backdrop issu with animation.css
    $('.modal').appendTo("body")

    // Full height of sidebar
    function fix_height() {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");
    }

    fix_height();

    // Fixed Sidebar
    // unComment this only whe you have a fixed-sidebar
    //    $(window).bind("load", function() {
    //        if($("body").hasClass('fixed-sidebar')) {
    //            $('.sidebar-collapse').slimScroll({
    //                height: '100%',
    //                railOpacity: 0.9,
    //            });
    //        }
    //    })

    $(window).bind("load resize click scroll", function () {
        if (!$("body").hasClass('body-small')) {
            fix_height();
        }
    })

    $("[data-toggle=popover]")
        .popover();
});


// For demo purpose - animation css script
function animationHover(element, animation) {
    element = $(element);
    element.hover(
        function () {
            element.addClass('animated ' + animation);
        },
        function () {
            //wait for animation to finish before removing classes
            window.setTimeout(function () {
                element.removeClass('animated ' + animation);
            }, 2000);
        });
}

// Minimalize menu when screen is less than 768px
$(function () {
    $(window).bind("load resize", function () {
        if ($(this).width() < 769) {
            $('body').addClass('body-small')
        } else {
            $('body').removeClass('body-small')
        }
    })
})

function SmoothlyMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
            function () {
                $('#side-menu').fadeIn(500);
            }, 100);
    } else if ($('body').hasClass('fixed-sidebar')) {
        $('#side-menu').hide();
        setTimeout(
            function () {
                $('#side-menu').fadeIn(500);
            }, 300);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}

// Dragable panels
function WinMove() {
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable(
        {
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8
        })
        .disableSelection();
};
//endregion

(function (window, $) {
    var win = window,
        util = {
            //region 包含遮罩ajax请求
            ajax: function (opts) {
                var options,
                    default_options = {
                        beforeSend: function () {
                            $.blockUI({
                                message: '<i class="fa fa-spinner fa-pulse"></i> 努力加载中，请稍等...',
                                css: {width: '15%', left: '40%'},
                                overlayCSS: {
                                    backgroundColor: '#293846',
                                    opacity: 0.6,
                                    cursor: 'wait'
                                }
                            });
                        },
                        method: "POST"
                    };
                options = $.extend(options || {}, default_options, opts);
                return $.ajax(options).complete($.unblockUI);
            }
            //endregion
            //region ajaxJson 服务端ajax 返回的数据,state 回调函数,xhr 参数
            , ajaxJsonComplete: function (ajaxJson, state, xhr) {
                //检测json对象是否存在，表示拦截到系统异常，直接跳转
                if (typeof ajaxJson.code == "undefined") {
                    if (ajaxJson.responseText)
                        window.document.write(ajaxJson.responseText.replace("<body>", "<body class='gray-bg'>"));
                    else
                        util.tips("请求服务器错误！", "error", {timeOut: 3000});
                }
                var message = "";
                var type = "";
                var msgOpts = {};
                //如果存在则解析
                switch (ajaxJson.code) {
                    case 0:
                        type = "error";
                        message = ajaxJson.message;
                        //如果是错误，显示时间延长为3000
                        msgOpts.timeOut = 3000;
                        util.tips(message, type, msgOpts);
                        break;
                    case 1:
                        type = "success";
                        message = ajaxJson.message;
                        if (typeof this == "object" && $(this).prop("tagName") == ("form".toUpperCase())) {
                            //关闭弹窗
                            $(this).jqModal('close');
                        }
                        util.tips(message, type, msgOpts);
                        break;
                    case -1:
                        type = "info";
                        message = ajaxJson.message == undefined ? "正在进行跳转..." : ajaxJson.message;
                        if (ajaxJson.redirect !== "" && ajaxJson.redirect !== null && ajaxJson.redirect !== undefined) {
                            window.location = ajaxJson.redirect;
                        }
                        return;
                }
                //回调函数
                if (ajaxJson.callback && typeof ajaxJson.callback == "string") {
                    win[ajaxJson.callback](ajaxJson.data, ajaxJson, xhr);
                }
            }
            //endregion
            //region 异步加载url内容,更新到target,url 请求地址,data 请求数据,target 更新div的ID
            , reloadContent: function (url, data, target) {
                var that = this;
                var method = data == null ? "GET" : "POST";   //这里的GET,POST会影响到I方法,所以如果data没有数据,就用GET方式的ajax
                that.ajax({url: url, data: data, method: method})
                    .error(function (json, state, xhr) {
                        that.ajaxJsonComplete(json, state, xhr);
                    })
                    .success(function (html, state, xhr) {
                        if (typeof html == "object") {   //请求回来的是一个json,那么久调用ajaxComplete完成解析,并处理
                            that.ajaxJsonComplete(html, state, xhr);
                        }
                        else { //回来的是一段HTML,那么更新到target这个标签
                            if (typeof target == "string") {
                                $("#" + target).html(html);
                            }
                            else if (target instanceof jQuery) {
                                target.html(html);
                            }
                            else if (typeof target == "object") {
                                $(target).html(html);
                            }
                            else {
                                $("#template_content").html(html);   //没有信息的话,就把这个返回信息放到整个页面上展示出来
                            }
                        }
                    });
            }
            //endregion
            //region 弹框提示 ,message 内容,type 类型 error/success/info,opts 选项
            , tips: function (message, type, opts) {
                var options = {
                    positionClass: 'toast-top-center',
                    timeOut: 1000
                };
                toastr.options = $.extend(options || {}, opts);
                toastr[type](message);
            }
            //endregion
            //region 上传控件
            , upload: function (opts) {
                var defaultOptions = {
                        //上传控件容器
                        containerId: "dropzone",
                        //是否启用移除按钮
                        removeLink: true,
                        //请求图片保存的服务端地址
                        saveUrl: "",
                        //请求图片删除的服务端地址
                        deleteUrl: "",
                        //图片保存完成后，需要保存的数据主键地址，格式","分隔
                        saveInput: "",
                        //上传文件的后缀格式
                        fileExtesions: ["jpg", "png", "jpeg"],
                        //默认一次性共可上传10个文件
                        maxFiles: "10",
                        //默认上传文件的大小(10M)
                        maxFilesize: 10,
                    },
                    options = $.extend(defaultOptions, opts);
                var dropzone = new Dropzone("div#" + options.containerId, {
                    url: options.saveUrl,
                    previewTemplate: document.querySelector('#preview-template').innerHTML,
                    //uploadMultiple: true,
                    //addRemoveLinks: options.removeLink,
                    dictRemoveFile: "移除",
                    dictCancelUpload: "取消上传",
                    parallelUploads: 2,
                    thumbnailHeight: 120,
                    thumbnailWidth: 120,
                    maxFilesize: options.maxFilesize,
                    maxFiles: options.maxFiles,
                    filesizeBase: 1000,
                    previewsContainer: document.querySelector("div#dropzone-preview"),
                    thumbnail: function (file, dataUrl) {
                        if (file.previewElement) {
                            file.previewElement.classList.remove("dz-file-preview");
                            var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                            for (var i = 0; i < images.length; i++) {
                                var thumbnailElement = images[i];
                                thumbnailElement.alt = file.name;
                                thumbnailElement.src = dataUrl;
                            }
                            setTimeout(function () {
                                file.previewElement.classList.add("dz-image-preview");
                            }, 1);
                        }
                    },
                    accept: function (file, done) {
                        var fileExtension = (/[.]/.exec(file.name)) ? /[^.]+$/.exec(file.name) : undefined;
                        if (fileExtension && fileExtension.length > 0
                            && $.inArray(fileExtension[0].toLocaleLowerCase(), options.fileExtesions) > -1)
                            done();
                        else {
                            util.tips("选择的文件不符合格式，必须是[" + options.fileExtesions.join(",") + "]后缀图片。", "error");
                            this.removeFile(file);
                            //隐藏或显示上传按钮
                            if (getImageAllElementId().length <= options.maxFiles - 1) {
                                $("#dropzone-upload").show();
                            }
                            else {
                                $("#dropzone-upload").hide();
                            }
                        }
                    },
                    init: function () {
                        if (options.removeLink) this.on("addedfile", function (file) {
                            // 新建一个自定义的删除按钮
                            var removeButton = Dropzone.createElement("<div class='text-center'><button class='btn btn-primary btn-xs' type='button'>移除</button></div>");
                            var _this = this;
                            //绑定点击事件
                            removeButton.addEventListener("click", function (e) {
                                //设置不需要提交form
                                e.preventDefault();
                                e.stopPropagation();
                                $.confirm({
                                    title: "图片移除提示",
                                    text: "确定要移除图片[" + file.name + "]吗？",
                                    confirm: function (button) {
                                        var item = getImageElement(file.name);
                                        if (item) {
                                            removeImageElement(item.id);
                                        }
                                        //移除图片预览
                                        _this.removeFile(file);
                                        //隐藏或显示上传按钮
                                        if (getImageAllElementId().length <= options.maxFiles - 1) {
                                            $("#dropzone-upload").show();
                                        }
                                        else {
                                            $("#dropzone-upload").hide();
                                        }
                                    },
                                    cancel: function (button) {
                                        util.tips("取消移除图片操作。", "info");
                                    },
                                    confirmButton: "是",
                                    cancelButton: "否"
                                });
                            });
                            //添加移除按钮到dom
                            file.previewElement.appendChild(removeButton);
                            //隐藏或显示上传按钮
                            if (getImageAllElementId().length >= options.maxFiles - 1) {
                                $("#dropzone-upload").hide();
                            }
                            else {
                                $("#dropzone-upload").show();
                            }
                        });
                        this.on("complete", function (data) {
                            debugger
                            if (data.xhr !== undefined) {
                                if (data.status == "success") {
                                    var ajaxJson = JSON.parse(data.xhr.responseText),
                                        file = data,
                                        message = ajaxJson.message,
                                        state = "success",
                                        options = {
                                            positionClass: 'toast-top-center',
                                            timeOut: 1000
                                        };
                                    switch (ajaxJson.code) {
                                        case 1:
                                            addImageElement({id: ajaxJson.data[0], name: file.name});
                                            var arr = getImageAllElementId();
                                            if (arr.length > 0) getStockJQ().val(arr.join(','));
                                            //隐藏或显示上传按钮
                                            if (getImageAllElementId().length >= this.options.maxFiles - 1) {
                                                $("#dropzone-upload").hide();
                                            }
                                            else {
                                                $("#dropzone-upload").show();
                                            }
                                            break;
                                        case 0:
                                            state = "error";
                                            options.timeOut = 3000;
                                            break;
                                    }
                                    util.tips(message, state, options);

                                    if (ajaxJson.callback && typeof ajaxJson.callback == "string")
                                        win[ajaxJson.callback](ajaxJson.data, ajaxJson, data.xhr);
                                } else {
                                    util.tips("请求服务器错误！", "error");
                                }
                            }
                        });
                    }
                });

                function addImageElement(item) {
                    var $this = getStockJQ();
                    var files = $this.data('files') || [];

                    if ($.isArray(item)) data.concat(item);
                    else files.push({id: item.id, name: item.name, size: item.size});
                    $this.data("files", files);
                    return files;
                }

                function removeImageElement(id) {
                    var $this = getStockJQ();
                    var files = $this.data('files') || [];

                    for (var i in files) {
                        var file = files[i];
                        if (file.id && file.id == id) {
                            files.splice(i, 1);
                            break;
                        }
                    }
                    var arr = getImageAllElementId();
                    if (arr.length > 0) {
                        $this.val(arr.join(','));
                    } else {
                        $this.val("");
                    }
                }

                function getImageElement(name) {
                    var $this = getStockJQ();
                    var files = $this.data('files') || [];

                    for (var i in files) {
                        var file = files[i];
                        if (file.name && file.name == name) return file;
                    }
                    return undefined;
                }

                function getImageAllElementId() {
                    var $this = getStockJQ(),
                        files = $this.data('files') || [],
                        arr = [];
                    for (var i in files) {
                        var file = files[i];
                        arr.push(file.id);
                    }
                    return arr;
                }

                function getStockJQ() {
                    if (typeof options.saveInput == "string")
                        return $("#" + options.saveInput);
                    if (options.saveInput instanceof jQuery)
                        return options.saveInput;
                    if (options.saveInput instanceof object)
                        return $(options.saveInput);
                }

                return {
                    dropzone: dropzone,
                    addImageElement: addImageElement,
                    removeImageElement: removeImageElement,
                    getImageElement: getImageElement,
                    getImageAllElementId: getImageAllElementId
                };
            }
            //endregion
            //region 富文本编辑器HTMLEncoding转换
            , ecodeHtmlToText: function (html) {
                return $("<div>").text(html).html();
            }
            , ecodeTextToHtml: function (text) {
                return $("<div>").html(text).html();
            }
            //endregion
        };
    win.util = util;
    win.ajaxJsonComplete = util.ajaxJsonComplete;
}(window, window.jQuery));