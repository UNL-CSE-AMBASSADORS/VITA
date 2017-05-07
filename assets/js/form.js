function startForm(containingClass, id) {
  $('.' + containingClass).append("<form class='cmxform' id=" + id + " method='get' action='' autocomplete='off'>");
}

function endForm(containingClass, id) {
  $('.' + containingClass).append("</form>");
}

function submitButton(containingClass) {
  $("." + containingClass + " form").append("<input type='submit' value='Submit' class='submit vita-form-button background-primary'>");
}

function newFormTitle(containingClass, title) {
  $("." + containingClass + " form").append("<h1 class='vita-form-title'>" + title + "</h1>");
}

function newSubheading(containingClass, subheading) {
  $("." + containingClass + " form").append("<h2 class='vita-form-subheading'>" + subheading + "</h2>");
}

function newTextField(containingClass, id, label, required) {
  if (required == true) {
    // Required TextField
    $("." + containingClass + " form").append("<div class='vita-form-textfield'> <input class='' type='text' name='" + id + "' id='" + id + "' required>" +
          "<span class='vita-form-bar'></span>" +
          "<label class='vita-form-label vita-form-required' for='" + id + "'>" + label + "</label>" +
          "</div>");
  } else {
    // Optional Textfield
    $("." + containingClass + " form").append("<div class='vita-form-textfield'> <input class='' type='text' name='" + id + "' id='" + id + "'>" +
          "<span class='vita-form-bar'></span>" +
          "<label class='vita-form-label' for='" + id + "'>" + label + "</label>" +
          "</div>");
  }
}

function newSelect(containingClass, id, label, questionId, required) {
  if (required == true) {
    // Required Select
    $("." + containingClass + " form").append("<div class='vita-form-select'>" +
          "<label for='" + id + "' class='vita-form-label vita-form-required'>" + label + "</label>" +
          "<select id='" + id + "' class='required'>");

    addOptions(containingClass, id, questionId);

    $("." + containingClass + " form").append("</select>" +
  	      "<div class='vita-form-select__arrow'></div>" +
          "</div>");
  } else {
    // Optional Select
    $("." + containingClass + " form").append("<div class='vita-form-select'>" +
          "<label for='" + id + "' class='vita-form-label'>" + label + "</label>" +
          "<select id='" + id + "'>");

    addOptions(containingClass, id, questionId);

    $("." + containingClass + " form").append("</select>" +
  	      "<div class='vita-form-select__arrow'></div>" +
          "</div>");
  }
}

function addOptions(containingClass, id, questionId) {
  $.getJSON({
    url: '/server/form.php?retrieve=options&questionId=' + questionId,
    success: function(result) {
      for(var i = 0; i < result.length; i++) {
        $("." + containingClass + " form .vita-form-select #" + id).append("<option>" + result[i].string + "</option>");
      }
    }
  });
}
