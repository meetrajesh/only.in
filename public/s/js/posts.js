;(function($){
  function vote(id, score) {
    return $.getJSON($(document).data('api_url') + '/post_vote', {
      api_key: $(document).data('api_key'),
      post_id: id,
      vote: score
    });
  }

  $('.post-upvote').on('click', function(){
    var $this = $(this);
    var $post = $this.parents('.post').first();
    $.when(vote($post.attr('data-id'), 1)).then(function(data){
      $post.find('.post-votebox > span').html(data.score);
    });
  });

  $('.post-downvote').on('click', function(){
    var $this = $(this);
    var $post = $this.parents('.post').first();
    $.when(vote($post.attr('data-id'), -1)).then(function(data){
      $post.find('.post-votebox > span').html(data.score);
    });
  });
})(jQuery);