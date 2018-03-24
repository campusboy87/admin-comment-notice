jQuery(document).on('heartbeat-tick', function (event, data) {

    // Проверим, есть ли наши данные и если нет - остановим скрипт.
    if (data.acn === undefined) {
        return;
    }

    console.log(data.acn);

    var $menu = jQuery('#menu-comments').find('.wp-menu-name');
    var $bar = jQuery('#wp-admin-bar-comments').find('a');

    jQuery($menu).html(data.acn.menu);
    jQuery($bar).html(data.acn.bar);
});