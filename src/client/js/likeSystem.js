
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
    $(obj).children('i').eq(0).removeClass("far fa-thumbs-up");
    $(obj).children('i').eq(0).addClass("fas fa-thumbs-up");

    if ($(obj).siblings('.dislike').eq(0).children('i').eq(0).hasClass('fas fa-thumbs-down')) {
      $(obj).siblings('.dislike').eq(0).children('i').eq(0).removeClass('fas fa-thumbs-down');
      $(obj).siblings('.dislike').eq(0).children('i').eq(0).addClass('far fa-thumbs-down');
    }
  }
  // Unlike
  else {
    $(obj).children('i').eq(0).removeClass("fas fa-thumbs-up");
    $(obj).children('i').eq(0).addClass("far fa-thumbs-up");
  }
}


// User clicked dislike
function clickedDislike(obj) {

  // Dislike
  if ($(obj).children('i').eq(0).hasClass('far fa-thumbs-down')) {
    $(obj).children('i').eq(0).removeClass("far fa-thumbs-down");
    $(obj).children('i').eq(0).addClass("fas fa-thumbs-down");

    if ($(obj).siblings('.like').children('i').eq(0).hasClass('fas fa-thumbs-up')) {
      $(obj).siblings('.like').children('i').eq(0).removeClass('fas fa-thumbs-up');
      $(obj).siblings('.like').children('i').eq(0).addClass('far fa-thumbs-up');
    }
  }
  // Undislike
  else {
    $(obj).children('i').eq(0).removeClass("fas fa-thumbs-down");
    $(obj).children('i').eq(0).addClass("far fa-thumbs-down");
  }

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
