/**
 * plugin name:modal
 * description:用于调用当前模态窗口
 * author:wujinfeng
 * create time:2015/5/7
 */

; (function (win, $) {
    "use strict";
    var JqueryModal = function (element, options) {
        this.$element = $(element);
        this.$modalElement = undefined;
        this.options = $.extend({}, JqueryModal.DEFAULTS, options);
    };
    //version
    JqueryModal.VERSION = '1.0.0';
    //default options
    JqueryModal.DEFAULTS = {
        id: "",
        //form: { id: "", url: "" },
        form: undefined,
        isStatic: true,
        type: "normal",
        isModal: true,
        title: "标题",
        subTitle: "",
        content: "",
        href: "#",
        save: "保存",
        close: "关闭",        
        savebutton: true,
        closebutton: true,
        onSaveClick: function (jqModal) { },
        onCloseClick: function (jqModal) { },
        onBeforeClose: function (jqModal) { }
    };
    //modal type
    JqueryModal.MODALTYPE = {
        "small": "modal-sm",
        "normal": "",
        "large": "modal-lg"
    };

    JqueryModal.prototype = {
        __init: function () {

            if (this.options.id == "" || this.options.id == null || this.options.id == undefined)
                this.options.id = rand(10).toString();

            var hasForm = this.options.form !== undefined && this.options.form.url !== undefined;

            var modalHTML = "";
            modalHTML += "<div class=\"modal inmodal fade\" id=\"" + this.options.id + "\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none;\">";
            modalHTML += "  <div class=\"modal-dialog " + JqueryModal.MODALTYPE[this.options.type] + "\">";
            modalHTML += "      <div class=\"modal-content\">";
            modalHTML += "         <div class=\"modal-header\">";
            modalHTML += "              <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">×</span><span class=\"sr-only\">Close</span></button>";
            modalHTML += "              <h4 class=\"modal-title\">" + this.options.title + "</h4>";
            modalHTML += "              <small class=\"font-bold\">" + this.options.subTitle + "</small>";
            modalHTML += "         </div>";
            modalHTML += "         <small class=\"font-bold\">";
            modalHTML += hasForm ? "<form action=\"" + this.options.form.url + "\" class=\"form-horizontal\" data-ajax=\"true\" data-ajax-method=\"Post\" data-ajax-success=\"ajaxJsonComplete\" data-ajax-failure=\"ajaxJsonComplete\" id=\"" + this.options.form.id + "\" method=\"post\" role=\"form\" novalidate=\"novalidate\">    " : "";
            modalHTML += "            <div class=\"modal-body\"></div>";
            modalHTML += "            <div class=\"modal-footer\">";
            if (this.options.closebutton)
                modalHTML += "              <button type=\"button\" class=\"btn btn-white\" data-dismiss=\"modal\">" + this.options.close + "</button>";
            if (this.options.savebutton)
                modalHTML += "              <button type=\"" + (hasForm ? "submit" : "button") + "\" class=\"btn btn-primary\" data-save=\"modal\">" + this.options.save + "</button>";
            modalHTML += "            </div>";
            modalHTML += hasForm ? "</form>" : "";
            modalHTML += "         </small>";
            modalHTML += "      </div>";
            modalHTML += "     <small class=\"font-bold\"></small>";
            modalHTML += "  </div>";
            modalHTML += "  <small class=\"font-bold\"></small>";
            modalHTML += "</div>";

            $(document.body).append(modalHTML);
            this.$modalElement = $("#" + this.options.id);

            var that = this;

            //bind event
            this.$modalElement.find(".modal-footer button[data-save='modal']").on("click", function (event) {
                that.options.onSaveClick(that);
            });

            this.$modalElement.find(".modal-footer button[data-dismiss='modal']").on("click", function (event) {
                that.options.onCloseClick(that);
            });

            this.$modalElement.on('hide.bs.modal', function (event) {
                $(this).find('.modal-body').empty();
                that.options.onBeforeClose(that);
            });

            this.$modalElement.on('show.bs.modal', function (event) {
                var currentModal = $(this);
                var href = !/#/.test(that.options.href) && that.options.href;
                currentModal.find('.modal-body').empty();
                if (href) {
                    currentModal.find('.modal-body').load(href, function () {
                        //让jquery.validate生效
                        $('form').validateBootstrap(true);
                    });
                }
                else {
                    currentModal.find('.modal-body').text(that.options.content);
                }
                currentModal.find('.modal-title').text(that.options.title);
                currentModal.find('.modal-header small .font-bold').text(that.options.subTitle);
                currentModal.find(".modal-footer button[data-dismiss='modal']").text(that.options.close);
                currentModal.find(".modal-footer button[data-save='modal']").text(that.options.save);
            });
            this.$modalElement.find(".modal-footer button[data-save='modal']").data('JqueryModal', this);
            this.$modalElement.find("form").data('JqueryModal', this);
            this.$modalElement.modal({ backdrop: this.options.isStatic, keyboard: true, show: true, remote: false });
        }
        , show: function () {
            if (this.$modalElement == undefined)
                this.__init();
            else
                this.$modalElement.modal('show');
        }
        , close: function () {
            if (this.$modalElement && this.$modalElement.length > 0)
                this.$modalElement.modal('hide');
        }
    };

    var rand = (function () {
        var today = new Date();
        var seed = today.getTime();
        function rnd() {
            seed = (seed * 9301 + 49297) % 233280;
            return seed / (233280.0);
        };
        return function rand(number) {
            return Math.ceil(rnd(seed) * Math.pow(10, number));
        };
    })();

    function Plugin(option) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('JqueryModal');
            var options = typeof option == 'object' && option;

            if (!data) $this.data('JqueryModal', (data = new JqueryModal(this, options)));

            if (option == 'close') data.close();
            else data.show();
        });
    }

    $.fn.jqModal = Plugin;
    $.fn.jqModal.Constructor = JqueryModal;
}(window, window.jQuery));