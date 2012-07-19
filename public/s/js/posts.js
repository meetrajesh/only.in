;(function($){
  function vote(el, score) {
    var $post = $(el).parents('.post').first();
    return $.getJSON($(document).data('api_url') + '/post_vote', {
      api_key: $(document).data('api_key'),
      post_id: $post.attr('data-id'),
      vote: score
    }, function(data){
      $post.find('.post-votebox > span').html(data.score);
    });
  }

  $('.post-upvote').on('click', function(){
    vote(this, 1);
  });

  $('.post-downvote').on('click', function(){
    vote(this, -1);
  });
})(jQuery);