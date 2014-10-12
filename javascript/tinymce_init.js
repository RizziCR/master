try {

tinyMCE_GZ.init({
        plugins : 'inlinepopups,bbcodeets',
        themes : 'advanced',
        languages : 'en,de',
        disk_cache : true,
        debug : false
});

tinyMCE.init({
        theme : "advanced",
        mode : "textareas",
        plugins : "inlinepopups,bbcodeets",
        theme_advanced_buttons1 : "bold,italic,underline,undo,redo,link,unlink,image,forecolor,formatselect,removeformat,cleanup,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "bottom",
        theme_advanced_toolbar_align : "center",
        theme_advanced_blockformats : "p,blockquote",
        content_css : "css/bbcode.css",
        entity_encoding : "raw",
        add_unload_trigger : false,
        remove_linebreaks : false,
        inline_styles : false,
        convert_fonts_to_spans : false
});

} catch(e) {}
