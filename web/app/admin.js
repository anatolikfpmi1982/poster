define(function(require, exports, module) {
    require('jquery');
    require('bootstrap');
    require('magnific-popup');
    require('bootstrap-multiselect');
    require('customSelect');
    require('redactor');
    require('redactor-fullscreen');
    require('redactor-fontcolor');
    require('redactor-fontsize');
    require('redactor-fontfamily');
    require('redactor-table');
    require('redactor-definedlinks');
    require('bootstrap-fileinput');

    var twig = require('twig');

    $(document).ready(function () {

        $('#editor-wis').redactor({
            plugins: ['fullscreen', 'fontcolor', 'fontsize', 'fontfamily', 'table', 'definedlinks'],
            minHeight: 200,
            maxHeight: 200
        });

        function showHide() {

            $('.js-tooltipe-click').each(function(){

                if ($(this).hasClass('active')) {
                   
                    $(this).next().find('.dl-horizontal').show();
                } else  {
                    $(this).next().find('.dl-horizontal').hide();
                }                
            })
        };

        $('.js-tooltipe-click').click(function(event){
            event.preventDefault();

            $(this).toggleClass('active');

            $('.js-tooltipe-click').not(this).removeClass('active');

            showHide();
        });

        if ($('div.content div.table-responsive table.table thead tr th').length) {
            $('div.content div.table-responsive table.table thead tr th').click(function () {
                if ($(this).attr('data-from') && $(this).data('from').length) {
                    location.href = $(this).data('from');
                }
            });
        }

        if ($('#places-filter').length) {
            $('#places-filter').keypress(function (e) {
                if (e.keyCode == 13) {
                    buildFilterQuery($(this));
                }
            });

            if ($('#places-filter select').length) {
                $('#places-filter select').change(function () {
                    var parentThis = $(this).parents('#places-filter');
                    if ($(this).attr('name') == 'filter[region]') {
                        if (parentThis.find("select[name='filter[city]']").length) {
                            parentThis.find("select[name='filter[city]']").val('');
                        }
                        if (parentThis.find("select[name='filter[district]']").length) {
                            parentThis.find("select[name='filter[district]']").val('');
                        }
                    }
                    else if ($(this).attr('name') == 'filter[city]') {
                        if (parentThis.find("select[name='filter[district]']").length) {
                            parentThis.find("select[name='filter[district]']").val('');
                        }
                    }
                    buildFilterQuery(parentThis);
                });
            }

            function buildFilterQuery(fields) {
                var getStr = '';
                $.each(fields.find('input'), function (key, el) {
                    if ($(el).val() != '') {
                        getStr += "&" + $(el).attr('name') + '=' + $(el).val();
                    }
                });
                $.each(fields.find('select'), function (key, el) {
                    if ($(el).val() != '') {
                        getStr += "&" + $(el).attr('name') + '=' + $(el).val();
                    }
                });
                window.location.search = '?page=1&sort=id&sorter=asc' + getStr;
            }
        }

        $(".js-customSelect-tmpl").each(function () {
            $(this).customSelect();
        })

        function loadCss(url) {
            var link = document.createElement("link");
            link.type = "text/css";
            link.rel = "stylesheet";
            link.href = url;
            document.getElementsByTagName("head")[0].appendChild(link);
        }

        loadCss('/djs/vendor/bootstrap-fileinput/fileinput.min.css');

        if(!$('#img').data('img')) {
            $("#img").fileinput({
                showCaption: false,
                showUpload: false
            });
        } else {
            $("#img").fileinput({
                showCaption: false,
                showUpload: false,
                initialPreview: [
                    "<img style='height:120px' src='" + $('#img').data('img') + "'>"
                ]
            });
        }

        $('.status-button-list').click(function(){
            var entity_name = $(this).data('entity_type');
            var entity_value = $(this).data('entity_value');
            var entity_id = $(this).data('entity_id');

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ' /admin/' + entity_name + '/setStatus',
                data : {status : entity_value, entity_id : entity_id},
                success: function (data) {
                    if(data.result == 1) {
                        if(entity_value == 0) {
                            $('#entity_status_list_' + entity_id).removeClass('btn-danger').addClass('btn-success');
                            $('#entity_status_list_' + entity_id).data('entity_value', 1);
                        } else {
                            $('#entity_status_list_' + entity_id).removeClass('btn-success').addClass('btn-danger');
                            $('#entity_status_list_' + entity_id).data('entity_value', 0);
                        }
                    }
                },
                failure: function () {
                    alert('Соединение сброшено. Попробуйте позже.');
                }
            }).error(function(data){
                $('#print-wrap').html(data.status);
            });
        });

    })
});