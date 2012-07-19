;(function($){
  function vote(id, score) {
    $.get($(document).data('api_url'), {api_key: $(document).data('api_key')}, function(data){

    });
  }

  $('.post-upvote').on('click', function(){
    var $this = $(this);
    var $post = $this.parents('.post').first();
    vote($post.attr('data-id'), 1);
  });

  $('.post-downvote').on('click', function(){
    var $this = $(this);
    var $post = $this.parents('.post').first();
    vote($post.attr('data-id'), -1);
  });
})(jQuery);