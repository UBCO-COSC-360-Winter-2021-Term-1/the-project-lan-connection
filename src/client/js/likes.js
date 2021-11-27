$(document).ready(function() {

  $('.like').on('click', function() {
    var pid = $(this).data('id');
    $clicked_btn = $(this);
    if ($clicked_btn.hasClass('far fa-thumbs-up')) {
      action = 'like';
    } else if($clicked_btn.hasClass('fas fa-thumbs-up')){
      action = 'unlike';
    }

    $.ajax({
      url: '../html/home.php',
      type: 'post',
      data: {
        'action': action,
        'post_id': pid
      },
      success: function(data) {
        res = JSON.parse(data);

        if (action == "like") {
          $clicked_btn.removeClass('far fa-thumbs-up');
          $clicked_btn.addClass('fas fa-thumbs-up');
        } 
        else if(action == "unlike") {
          $clicked_btn.removeClass('fas fa-thumbs-up');
          $clicked_btn.addClass('far fa-thumbs-up');
        }
        // display the number of likes and dislikes
        $('.like-counter').text(res.likes);
        }
    });

  });

});