
function changeColor(clicked_id) {
  one = document.getElementById('1');
  two = document.getElementById('2');
  three = document.getElementById('3');

  one.classList.remove("classy");
  two.classList.remove("classy");
  three.classList.remove("classy");

  target = document.getElementById(clicked_id);
  target.classList.add("classy");
}

