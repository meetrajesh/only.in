;(function($) {
  $(document).ready(function() {
    $('#main-search').autocomplete({
      minLength: 0,
      appendTo: $('#main-search').parent(),
      source: function(request, response) {
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
      },
      focus: function(ev, ui) {
        $('#main-search').val(ui.item.label);
        return false;
      },
      select: function(ev, ui) {
        $('#main-search').val(ui.item.label);
        window.location = '/' + ui.item.value.permalink;
        return false;
      }
    });

    $('#main-search').on('keyup', function(ev){
      var val = $(this).val();
      if (val.length > 0) {
        window.location = '/' + val;
      }
    });
  });
})(jQuery);