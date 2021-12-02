
// Triggered if admin chooses to delete a user's post or account
// This function displays a warning/confirmation message
function deleteFunc(obj) {

  var uname = $(obj).attr("data-uname");
  var action = $(obj).attr("name");

  // Display the alert and hide the three dot icon
  $(obj).parent().siblings('.alert').css("display", "block");

  // Replace text depending on which button the admin clicked
  if (action == 'deletePost') {
    $(obj).parent().siblings('.alert').children('strong').text("Delete " + uname + "'s post?");
    $(obj).parent().siblings('.alert').children('.deleteUser').toggle();
  }
  else {
    $(obj).parent().siblings('.alert').children('strong').text("Delete " + uname + "'s account?");
    $(obj).parent().siblings('.alert').children('.deletePost').toggle();
  }

}

// Called if the admin confirms their action to delete user/post
function deleteContent(obj) {

  var postId = $(obj).attr("data-pid");
  var uname = $(obj).attr("data-uname");
  // Action can be: deletePost OR deleteUser
  var action = $(obj).attr("name");

  // If admin's action is to delete a post
  if (action == 'deletePost') {
    $.ajax({
      type: "POST",
      url: "../php/deletePost.php",
      data: {
        pid: postId
      },
      success: function (res) {
        location.reload();
        alert("Successful post deletion");
      },
      error: function (xhr, status, error) {
        console.error(xhr);
      }
    });
  }
  // If admin's action is to delete a user
  else {
    $.ajax({
      type: "POST",
      url: "../php/deleteUser.php",
      data: {
        uname: uname
      },
      success: function () {
        location.reload();
        alert("Successful user deletion");
      },
      error: function (xhr, status, error) {
        console.error(xhr);
      }
    });
  }

}

// Called if the admin cancels their action with the no button
function exitAlert(obj) {
  $(obj).parent().css("display", "none");
}