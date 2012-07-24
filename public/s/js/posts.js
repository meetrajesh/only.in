;(function($) {
  function vote(el, score) {
    var $post = $(el).parents('.post').first();
    return $.getJSON($(document).data('api_url') + '/post_vote', {
      api_key: $(document).data('api_key'),
      post_id: $post.attr('data-id'),
      vote: score,
      csrf: $(document).data('csrf')
    }, function(data) {
      $post.find('.post-votebox > span').html(data.score);
    });
  }

  $('.post-upvote').on('click', function() {
    vote(this, 1);
  });

  $('.post-downvote').on('click', function() {
    vote(this, -1);
  });

  $('#submit-comment').on('click', function() {
    var $post = $('.post').first();
    $.getJSON($(document).data('api_url') + '/comment/create', {
      api_key: $(document).data('api_key'),
      post_id: $post.attr('data-id'),
      comment: $('#new-comment').val(),
      csrf: $(document).data('csrf')
    }, function(data) {
      $('#new-comment').val('');
      $('.comments').find('h3').after(data['comment_html']);
      $post.find('.post-comment-count').html(data['comment_count']);
    });
  });
})(jQuery);