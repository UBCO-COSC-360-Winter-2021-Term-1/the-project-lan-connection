
/*
  far fa-thumbs-up : HOLLOW
  fas fa-thumbs-up : FILLED

  far fa-thumbs-down : HOLLOW
  fas fa-thumbs-down : FILLED
*/

// User clicked Like
function clickedLike(obj) {

  // Like
  if ($(obj).children('i').eq(0).hasClass('far fa-thumbs-up')) {
    var clickBtnValue = 'liked';
    $(obj).children('i').eq(0).removeClass("far fa-thumbs-up");
    $(obj).children('i').eq(0).addClass("fas fa-thumbs-up");

    if ($(obj).siblings('.dislike').eq(0).children('i').eq(0).hasClass('fas fa-thumbs-down')) {
      $(obj).siblings('.dislike').eq(0).children('i').eq(0).removeClass('fas fa-thumbs-down');
      $(obj).siblings('.dislike').eq(0).children('i').eq(0).addClass('far fa-thumbs-down');
    }
  }
  // Unlike
  else {
    var clickBtnValue = 'unliked';
    $(obj).children('i').eq(0).removeClass("fas fa-thumbs-up");
    $(obj).children('i').eq(0).addClass("far fa-thumbs-up");
  }

  var postId = $(obj).attr("data-value");

  // Post like info to likeSystem.php and update the database from there
  $.ajax({
    type: "POST",
    url: "../php/likeSystem.php",
    data: {
      action: clickBtnValue,
      pid: postId
    },
    success: function (data) {
      console.log('Likes, dislikes: ' + data);
    },
    error: function (xhr, status, error) {
      console.error(xhr);
    }
  }).done(function (res) {
    var result = $.parseJSON(res);
    $(obj).siblings('.like-counter').eq(0).text(result[0]);
    $(obj).siblings('.dislike-counter').eq(0).text(result[1]);
  });


}

// User clicked dislike
function clickedDislike(obj) {

  // Dislike
  if ($(obj).children('i').eq(0).hasClass('far fa-thumbs-down')) {
    var clickBtnValue = 'disliked';
    $(obj).children('i').eq(0).removeClass("far fa-thumbs-down");
    $(obj).children('i').eq(0).addClass("fas fa-thumbs-down");

    if ($(obj).siblings('.like').children('i').eq(0).hasClass('fas fa-thumbs-up')) {
      $(obj).siblings('.like').children('i').eq(0).removeClass('fas fa-thumbs-up');
      $(obj).siblings('.like').children('i').eq(0).addClass('far fa-thumbs-up');
    }
  }
  // Undislike
  else {
    var clickBtnValue = 'undisliked';
    $(obj).children('i').eq(0).removeClass("fas fa-thumbs-down");
    $(obj).children('i').eq(0).addClass("far fa-thumbs-down");
  }

  var postId = $(obj).attr("data-value");

  $.ajax({
    type: "POST",
    url: "../php/likeSystem.php",
    data: {
      action: clickBtnValue,
      pid: postId
    },
    success: function (data) {
      console.log('Likes, dislikes: ' + data);
    },
    error: function (xhr, status, error) {
      console.error(xhr);
    }
  }).done(function (res) {
    var result = $.parseJSON(res);
    $(obj).siblings('.like-counter').text(result[0]);
    $(obj).siblings('.dislike-counter').text(result[1]);
  });

}

// User clicked bookmark
function clickedBookmark(obj) {
  // Bookmark
  if ($(obj).children('i').eq(0).hasClass('far fa-bookmark')) {
    $(obj).children('i').eq(0).removeClass("far fa-bookmark");
    $(obj).children('i').eq(0).addClass("fas fa-bookmark");
  }
  // Unbookmark
  else {
    $(obj).children('i').eq(0).removeClass("fas fa-bookmark");
    $(obj).children('i').eq(0).addClass("far fa-bookmark");
  }
}
