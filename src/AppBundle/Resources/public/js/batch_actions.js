$(function() {
    $('.actions_fields').children().hide();

    $('.actions_fields').siblings('select[name="action"]').on('change', function (e) {
        $('.actions_fields').children().hide();
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Изменить автора') {
            $('.actions_fields').children('#a_author').show();
        }
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Изменить арт/фото') {
            $('.actions_fields').children('#a_art').show();
        }
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Изменить цену') {
            $('.actions_fields').children('#a_price').show();
        }
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Изменить коэффициент') {
            $('.actions_fields').children('#a_ratio').show();
        }
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Изменить показывать') {
            $('.actions_fields').children('#a_show').show();
        }
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Добавить категорию') {
            $('.actions_fields').children('#a_category').show();
        }
        if($('.actions_fields').siblings('.select2-container').text().trim() == 'Удалить категорию') {
            $('.actions_fields').children('#a_category').show();
        }
    });
});
