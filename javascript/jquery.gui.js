(function($) {

  $.gui = function(options) {

    var opts = $.extend({}, $.gui.defaults, options);

    $("div[@id^='"+opts.namespace+"']").each(function() {
      $this = $(this);

      for(i=0; i<opts.depends.length; i++) {
        var o = $.extend(true, {}, $.gui.itemdefaults, opts.depends[i]);

        $(o.element, $this).
        eq(o.number).
        each(
            function() {
                o.data.namespace = $this;
                $(this).bind("click", o.data, function(e) {
                    if($(this).attr("checked")) {
                        $.each(e.data.onSelect.hide, function(i,v) { $.gui.hide(v, e.data.namespace, e.data.onSelect.signal, e.data); });
                        $.each(e.data.onSelect.show, function(i,v) { $.gui.show(v, e.data.namespace, e.data.onSelect.signal, e.data); });
                    }
                    else {
                        $.each(e.data.onDeselect.hide, function(i,v) { $.gui.hide(v, e.data.namespace, e.data.onDeselect.signal, e.data); });
                        $.each(e.data.onDeselect.show, function(i,v) { $.gui.show(v, e.data.namespace, e.data.onDeselect.signal, e.data); });
                    }
                });
                $(this).triggerHandler('click',o.data);
            }
        );
      }
    });
    return $this;
  };

  $.gui.show = function(el, ns, signals, data) {
    if(el.indexOf("#")>=0) {
        $(el).show();
        propagate(signals,ns,data);
    }
    else {
        $(el, ns).show();
        propagate(signals,ns,data);
    }
  }

  $.gui.hide = function(el, ns, signals, data) {
    if(el.indexOf("#")>=0) {
        $(el).hide();
        propagate(signals,ns,data);
    }
    else {
        $(el, ns).hide();
        propagate(signals,ns,data);
    }
  }

  function propagate(signals,ns,data) {
        $.each(signals, function(i,v) {
            if(v.indexOf('#')>=0)
                $(v).each(function() { $(this).triggerHandler('click', data)});
            else
                $(v, ns).each(function() { $(this).triggerHandler('click', data)});
        });
  }

  //
  // plugin defaults
  //
  $.gui.defaults = {
    namespace: 'section'
  };

  $.gui.itemdefaults = {
      number: 0,
      data: {
          onDeselect: { hide:[], show:[], signal:[] },
          onSelect:   { hide:[], show:[], signal:[] }
      }
  }
})(jQuery);

/**
 * Aufruf der Funktionen:
 *
$(function () {
    jQuery.gui({depends: [
//      { element:"", number: 0, data: { onDeselect:{hide:[], show:[]}, onSelect:{hide:[], show:[]}} },
        { element:"*[@name='config[war][begin][mode]']",       data: { onSelect:  { hide:[], show:[] } } },
        { element:"*[@name='config[war][end][opt_kololost]']", data: { onDeselect:{ show:['#left-column','.modeAcceptBlock']}, onSelect:{hide:['#left-column','.modeAcceptBlock']}} }
    ]});
});
 *
 */
