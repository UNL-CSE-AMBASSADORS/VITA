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

function newTextField(containingClass, id, label, inputType, hint, required) {
  // Prepare optional components
  var hintMessage = "";
  if (hint != null && hint != "") {
    hintMessage = "<span class='vita-form-hint'>" + hint + "</span>";
  }

  var requiredTag = "";
  var requiredVitaClass = "";
  if (required == true) {
    requiredTag = " required";
    requiredVitaClass = " vita-form-required";
  }

  // Add the element
  $("." + containingClass + " form").append("<div class='vita-form-textfield'> <input class='' type='" + inputType + "' name='" + id + "' id='" + id + "'" + requiredTag + ">" +
        "<span class='vita-form-bar'></span>" +
        "<label class='vita-form-label" + requiredVitaClass + "' for='" + id + "'>" + label + "</label>" +
        hintMessage +
        "</div>");
}

function newSelect(containingClass, id, label, questionId, hint, required) {
  // Prepare optional components
  var hintMessage = "";
  if (hint != null && hint != "") {
    hintMessage = "<span class='vita-form-hint'>" + hint + "</span>";
  }

  var requiredClass = "";
  var requiredVitaClass = "";
  if (required == true) {
    requiredClass = " class='required'";
    requiredVitaClass = " vita-form-required";
  }

  // Add the element
  $("." + containingClass + " form").append("<div class='vita-form-select' id='" + id + "-container'>" +
        "<label for='" + id + "' class='vita-form-label" + requiredVitaClass + "'>" + label + "</label>" +
        "<select id='" + id + "'" + requiredClass + "'>");

  addOptions(containingClass, id, questionId);

  $("." + containingClass + " form #" + id + "-container").append("</select>" +
        "<div class='vita-form-select__arrow'></div>" +
        hintMessage +
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
