/**
 * Created by apple on 2016/12/11.
 */
$().ready(function () {
    //
    var $ajaxForm = $("#ajax-form");

    //事件
    $ajaxForm.find('select[name="superior_company_name"]').bind('change',function () {
        var level = $(this).find('option:selected').attr("data-level");
        $ajaxForm.find('#superior-level').html(level);
    });
    //
    $.validator.setDefaults({
        highlight: function (element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            element.closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            if (element.is(":radio") || element.is(":checkbox")) {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.appendTo(element.parent());
            }
        },

        errorClass: "help-block m-b-none",
        validClass: "help-block m-b-none",

        submitHandler: function() {
            var preData = {};
            $ajaxForm.find('.need-submit-element').each(function () {
                if($(this).attr("name")){
                    preData[$(this).attr("name")] = $(this).val();
                }
            });

            preData.level = $("#superior-level").html(); //上级

            if(preData.name!= $ajaxForm.find('input[name="name"]').attr("data-name-check")) {
                var index = layer.load(2);
                $.post("", preData).done(function (r) {
                    if (r.code == 0) {
                        var optionHtml = '<option data-level="0" value="无">无</option>';
                        for (var i = 0; i < r.data.length; i++) {
                            optionHtml += '<option ' +
                                ($ajaxForm.find('select[name="superior_company_name"]').val() == r.data[i].name ? 'selected="selected"' : ' ') +
                                ' data-level="' + r.data[i].level + '" value="' + r.data[i].name + '">' + r.data[i].name + '</option>';
                        }
                        $ajaxForm.find('select[name="superior_company_name"]').html(optionHtml);
                    } else {
                        $ajaxForm.find('input[name="name"]').attr("data-name-check",r.data.name);
                        $ajaxForm.find('input[name="name"]').attr("data-msg-check",r.msg);
                    }
                    layer.msg(r.msg);
                    layer.close(index);

                }).fail(function () {
                    layer.msg('系统异常或网络错误');
                }).complete(function () {
                    //
                });
            }else{
                layer.msg($ajaxForm.find('input[name="name"]').attr("data-msg-check"));
            }
        }
    });

    $ajaxForm.validate();
});