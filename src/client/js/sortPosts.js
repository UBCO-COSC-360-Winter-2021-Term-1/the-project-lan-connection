
// Change the style of the sort buttons upon click
function changeColor(obj) {
  one = document.getElementById('1');
  two = document.getElementById('2');
  three = document.getElementById('3');

  one.classList.remove("classy");
  two.classList.remove("classy");
  three.classList.remove("classy");

  target = document.getElementById(obj);
  target.classList.add("classy");
}

