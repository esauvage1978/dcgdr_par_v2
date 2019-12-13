function switch_sidebar() {
    if ($('body').hasClass('control-sidebar-slide-open')) {
        $('body').removeClass('control-sidebar-slide-open control-sidebar-push-slide');
    } else {
        $('body').addClass('control-sidebar-slide-open control-sidebar-push-slide');
    }
}