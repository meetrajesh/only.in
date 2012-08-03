;(function($) {
  $(document).ready(function() {
    var $can_load_more = true;
    var $end_of_content = false;
    var $page = 1;

    $(window).scroll(function() {
      if (!$can_load_more || $end_of_content || $(document).data('view_data').tab == "") {
          return;
      }
      $can_load_more = false;

      setTimeout(function() {
        $can_load_more = true;
      }, 2000);

      if (($(window).scrollTop() + 1500) > $(document).height() - $(window).height()) {
          return $.getJSON($(document).data('api_url') + '/posts_get', {
            api_key: $(document).data('api_key'),
            subin_slug: $(document).data('view_data').subin_slug,
            tab: $(document).data('view_data').tab,
            page: $page + 1
          }, function(data) {
            if (data.html != "") {
              $('#all-posts').append(data.html);
              $page += 1;
            } else {
              $end_of_content = true;
            }
        });
      }
    });
  });
})(jQuery);