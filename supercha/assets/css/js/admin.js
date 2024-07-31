jQuery(document).ready(function($) {
    // Custom admin JavaScript
    $('.notice.is-dismissible').each(function() {
        var $this = $(this);
        $this.find('.notice-dismiss').on('click', function() {
            $this.fadeOut();
        });
    });
});
