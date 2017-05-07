function startFormTag(containingClass, id) {
  $('.' + containingClass).append("<form class='cmxform' id=" + id + " method='get' action='' autocomplete='off'>");
}

function endFormTag(containingClass, id) {
  $('.' + containingClass).append("</form>");
}
