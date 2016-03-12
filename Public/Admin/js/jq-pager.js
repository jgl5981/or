/**
 * plugin name:pager
 * description:用于显示分页控件，依赖NextPager对象
 * 通过把PageSize,CurrentPageIndex 插入到搜索的Form中提交的方式来实现分页
 * 使用方式: $("#pager").ajaxPager({ formId: "user_search_form", nextPager: @Html.Raw(Sp.Web.Areas.Manage.Core.Tools.JsonPager(Model)) })
 * pager:分页元素ID
 * user_search_form: 搜索表单ID
 * Model:PagerList 封装的对象
 */

;(function ($) {
    "use strict";
    var Pager = function (element, options) {
        this.$element = $(element);
        this.options = $.extend({}, Pager.DEFAULTS, options);
        var page_count = parseInt(this.options.page_count);
        var page_index = parseInt(this.options.page_index);
        this.options.page_index = isNaN(page_index) ? 1 : page_index;
        this.options.page_count = page_count;
        this.options.page_num = Math.ceil(this.options.page_count / this.options.page_size);

        this.tag = 0;
    }

    Pager.VERSION = '1.0.0';

    Pager.DEFAULTS = {
        css: "pagination",
        count: 5,
        formId: "",
        page_index: 1,
        page_size: 10,
        page_count: 1,
        page_num: 1
    };

    Pager.prototype = {

        init: function () {
            var pageCount = this.options.page_num;
            var pageSize = this.options.page_size;
            var pageNum = this.options.page_index;
            var CurrentPageIndex = this.options.page_index;
            var totalCount = this.options.page_count;

            var $ul = $("<ul class='" + this.options.css + "'></ul>");
            $ul.append("<li><a href='#' data-page='1'>首页</a></li>");
            $ul.append("<li><a href='#' data-page='p'>上一页</a></li>");

            var interval = getInterval(this.tag, this.options.count, pageNum);
            this.tag = interval[2];
            if ((pageCount >= interval[0] && pageCount <= interval[1] && pageCount > this.options.count) || interval[0] > 1)
                $ul.append("<li><a href='#' data-page='" + (interval[0] - 1) + "'>...</a></li>");

            for (var i = interval[0]; i < interval[1] && i <= pageCount; i++) {
                $ul.append("<li" + (i == pageNum ? " class='active'" : "") + "><a" + (i == pageNum ? "" : " href='#'") + " data-page='" + i + "'>" + i + "</a></li>");
            }
            if (pageCount > interval[1]) $ul.append("<li><a href='#' data-page='" + interval[1] + "'>...</a></li>");
            $ul.append("<li><a href='#' data-page='n'>下一页</a></li>");
            $ul.append("<li><a href='#' data-page='" + pageCount + "'>尾页</a></li>");
            $ul.append("<li class='active disabled'><a>共" + totalCount + "条</a></li>");

            if (pageNum == 1) {
                $ul.find("a[data-page='1']").removeAttr("href").parent().addClass("disabled");
                $ul.find("a[data-page='p']").removeAttr("href").parent().addClass("disabled");
            }

            if (pageNum == pageCount) {
                $ul.find("a[data-page='n']").removeAttr("href").parent().addClass("disabled");
                $ul.find("a[data-page='" + pageCount + "']").removeAttr("href").parent().addClass("disabled");
            }

            this.$element.append($ul);
            var $that = this.$element;
            this.$element.find("li a[href='#']").on('click', function (event) {
                var $a = $(event.target);
                var page = $a.attr("data-page");
                var _pager = $that.data('bs.pager');
                switch (page) {
                    case "p":
                        _pager.prev();
                        break;
                    case "n":
                        _pager.next();
                        break;
                    default:
                        _pager.goto(page);
                        break;
                }
            });
            $ul.removeData();
            $ul.data(this);
        }
        , prev: function () {
            var pageCount = this.options.page_index;
            pageCount = pageCount - 1 <= 1 ? 1 : pageCount - 1;
            this.goto(pageCount);
        }
        , next: function () {
            this.goto(this.options.page_index + 1);
        }
        , goto: function (num) {
            this.ajax(num);
        }
        , ajax: function (page) {
            var pageCount = this.options.page_num,
                pageSize = this.options.page_size,
                pageNum = this.options.page_index;
            pageNum = page > pageCount ? pageCount : page;
            var $form = $("#" + this.options.formId);
            if ($form.find("input[data-page='page_size']").length <= 0)
                $form.append("<input data-page='page_size' type='hidden' name='page_size' value='' />");
            if ($form.find("input[data-page='page_index']").length <= 0)
                $form.append("<input data-page='page_index' type='hidden' name='page_index' value='' />");
            $form.find("input[data-page='page_size']").val(pageSize);
            $form.find("input[data-page='page_index']").val(pageNum);
            $form.submit();
        }
    }

    function getInterval(tag, count, num) {
        var p = (tag * count + 1), n = ((tag + 1) * count + 1)
        if (num < p) {
            return getInterval(tag - 1, count, num);
        }
        else if (num >= n) {
            return getInterval(tag + 1, count, num);
        }
        return [p, n, tag];
    }

    function Plugin(option) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('bs.pager');
            var options = typeof option == 'object' && option;

            if (!data) $this.data('bs.pager', (data = new Pager(this, options)));
            data.init();
        });
    }

    $.fn.ajaxPager = Plugin;
    $.fn.ajaxPager.Constructor = Pager;
}(window.jQuery));
