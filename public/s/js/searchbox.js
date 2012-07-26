;(function($) {

  function get_results(request, response) {
    $.getJSON($(document).data('api_url') + '/subin/search', {
          'api_key': $(document).data('api_key'),
          'csrf': $(document).data('csrf'),
          'search_str': request.term
        },
        function(data) {
          response($.map(data, function(item){
            return {
              'label': item.name,
              'value': item
            }
          }));
        }
    );
  }

  function ac_focus(ev, ui) {
    $(this).val(ui.item.label);
    return false;
  }

  function ac_select(ev, ui) {
    $(this).val(ui.item.label);
    return false;
  }

  $(document).ready(function() {
    $('#main-search').autocomplete({
      minLength: 0,
      appendTo: $('#main-search').parent(),
      source: get_results,
      focus: function(ev, ui){
        ac_focus(ev, ui);
        window.location = '/' + ui.item.value.permalink;
        return false;
      },
      select: ac_select
    });

    $('#main-search').on('keyup', function(ev){
      if (ev.keyCode === 13) {
        var val = $(this).val();
        if (val.length > 2) {
          window.location = '/' + val;
        }
      }
    });

    $('#qp-place-field > input').autocomplete({
      minLength: 0,
      appendTo: $('#qp-place-field'),
      source: get_results,
      focus: ac_focus,
      select: ac_select
    })
  });
})(jQuery);