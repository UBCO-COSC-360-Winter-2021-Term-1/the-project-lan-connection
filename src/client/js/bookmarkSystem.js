
function clickedBookmark(obj) {
  // Bookmark
  if ($(obj).children('i').eq(0).hasClass('far fa-bookmark')) {
    var clickBtnValue = 'bookmarked';
    $(obj).children('i').eq(0).removeClass("far fa-bookmark");
    $(obj).children('i').eq(0).addClass("fas fa-bookmark");
  }
  // Unbookmark
  else {
    var clickBtnValue = 'unbookmarked';
    $(obj).children('i').eq(0).removeClass("fas fa-bookmark");
    $(obj).children('i').eq(0).addClass("far fa-bookmark");
  }

  var postId = $(obj).attr("data-value");
  // Post bookmark info to bookmarkSystem.php and update the database from there
  $.ajax({
    type: "POST",
    url: "../php/bookmarkSystem.php",
    data: {
      action: clickBtnValue,
      pid: postId
    },
    success: function () {
      console.log('Successful bookmark');
    },
    error: function (xhr, status, error) {
      console.error(xhr);
    }
  });

}