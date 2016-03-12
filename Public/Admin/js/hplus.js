// Custom scripts
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

(function (window, $) {
    var win = window,

        util = {
            //包含遮罩ajax请求
            ajax: function (opts) {
                var options,
                    default_options = {
                        beforeSend: function () {
                            $.blockUI({
                                message: '<i class="fa fa-spinner fa-pulse"></i> 努力加载中，请稍等...',
                                css: { width: '20%', left: '40%' },
                                overlayCSS: {
                                    backgroundColor: '#fff',
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
            //ajaxJson完成处理
            , ajaxJsonComplete: function (ajaxJson, callback, xhr) {
                //检测json对象是否存在，表示拦截到系统异常，直接跳转
                if (typeof ajaxJson.code == "undefined") {
                    if (ajaxJson.responseText)
                        window.document.write(ajaxJson.responseText.replace("<body>", "<body class='gray-bg'>"));
                    else
                        util.tips("请求服务器错误！", "error", { timeOut: 3000 });
                }
                var message = "";
                var type = "";
                var msgOpts = {};
                //如果存在则解析
                switch (ajaxJson.code) {
                    case -1:
                        type = "error";
                        message = ajaxJson.error;
                        //如果是错误，显示时间延长为3000
                        msgOpts.timeOut = 3000;
                        break;
                    case 0:
                        type = "error";
                        message = ajaxJson.message;
                        break;
                    case 1:
                        type = "success";
                        message = ajaxJson.message;
                        if (typeof this == "object" && $(this).prop("tagName") == ("form".toUpperCase())) {
                            //关闭弹窗
                            $(this).jqModal('close');
                        }
                        break;
                    case 2:
                        type = "info";
                        message = ajaxJson.message == undefined ? "正在进行跳转..." : ajaxJson.message;
                        break;
                }

                if (!(message == undefined || message == "" || message == null)) {
                    util.tips(message, type, msgOpts);
                }

                if (ajaxJson.callback && typeof ajaxJson.callback == "string")
                    win[ajaxJson.callback](ajaxJson.data, ajaxJson, xhr);

                if (callback && typeof callback == "function") callback();

                if (ajaxJson.code == 2 && ajaxJson.redirect !== ""
                    && ajaxJson.redirect !== null
                    && ajaxJson.redirect !== undefined)
                    window.location = ajaxJson.redirect;
            }
            , tips: function (message, type, opts) {
                var options = {
                    positionClass: 'toast-top-center',
                    timeOut: 1000
                };
                toastr.options = $.extend(options || {}, opts);
                toastr[type](message);
            }
            , reloadContent: function (url, data, target, callback) {
                var that = this;
                //这里的GET,POST会影响到I方法,所以如果data没有数据,就用GET方式的ajax
                var method = data == null ? "GET" : "POST";
                that.ajax({ url: url, data: data, method : method })
                    .error(function (json, state, xhr) {
                        that.ajaxJsonComplete(json, state, xhr);
                    })
                    .success(function (html, state, xhr) {
                        if (typeof html == "object") {
                            that.ajaxJsonComplete(html, state, xhr);
                        }
                        else {
                            if (target == undefined) {
                                $("#template_content").html(html);
                            }
                            else {
                                if (typeof target == "string") {
                                    $("#" + target).html(html);
                                }
                                else if (target instanceof jQuery) {
                                    target.html(html);
                                } else if (typeof target == "object") {
                                    $(target).html(html);
                                }
                            }
                        }
                        if (typeof callback == "function")
                            callback(xhr);
                    });
            }
            , ecodeHtmlToText: function (html) {
                return $("<div>").text(html).html();
            },
            ecodeTextToHtml: function (text) {
                return $("<div>").html(text).html();
            }
        };
    win.util = util;
    win.ajaxJsonComplete = util.ajaxJsonComplete;
}(window, window.jQuery));