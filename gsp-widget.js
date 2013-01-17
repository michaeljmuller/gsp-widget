
jQuery(document).ready(function(){
    jQuery.ajax({
        url:'http://themullers.org/mike/wp-content/plugins/gsp-widget/gsp-widget-content.php',
        dataType:'html'}
    ).done(function(data) {
        jQuery('#gsp-content').replaceWith(data);
    });
});
