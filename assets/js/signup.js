$(document).ready(function() {
  loadQuestions();

  // to be deleted at a later point
  validateSampleForm();

});

var loadQuestions = function() {
  $.getScript('/assets/js/form.js', function() {
  });

  $.getJSON({
    url: '/server/form.php?retrieve=questions',
    success: function(result) {
      var containingClass = 'vita-signup-form';
      $('.' + containingClass).html(""); // Clear any data in the form right now

      // Create arrays that will be used for validation
      var rules = [];
      var messages = [];

      // Open form tag
      startForm(containingClass, 'vitaSignupForm');
      newFormTitle(containingClass, 'Sign Up for a VITA Appointment');

      // For each question in the form
      var currentSubheading = result[0].subheading;
      for(var i = 0; i < result.length; i++) {
        // If the question is in a new subheading, add that subheading to the form
        if (result[i].subheading != currentSubheading) {
          newSubheading(containingClass, result[i].subheading);
          currentSubheading = result[i].subheading;
        }
        // Add the question to the form
        var id = 'vita' + result[i].questionId;
        // Textfield
        if (result[i].inputType.toLowerCase() == "text" || result[i].inputType.toLowerCase() == "email") {
          newTextField(containingClass, id, result[i].string, result[i].inputType, result[i].hint, result[i].required);
          if (result[i].required || result[i].validationType != null) {
            rules[id] = questionRules(result[i]);
          }
          if (result[i].errorMessage != null) {
            messages[id] = questionMessage(result[i]);
          }
        }
        // Select
        else if (result[i].inputType.toLowerCase() == "select") {
          newSelect(containingClass, id, result[i].string, result[i].questionId, result[i].hint, result[i].required);
          if (result[i].required || result[i].validationType != null) {
            rules[id] = questionRules(result[i]);
          }
          if (result[i].errorMessage != null) {
            messages[id] = questionMessage(result[i]);
          }
        } else {
          // Possibly do nothing if the question is of an undefined type?
          $('.' + containingClass + ' form').append("<div>" + result[i].inputType + "</div>");
        }
      }

      submitButton(containingClass);
      // Close form tag
      endForm(containingClass);
      // Validate the Form using the newly created rules and messages object
      var validationObject = [];
      validationObject["rules"] = rules;
      validationObject["messages"] = messages;
      validateForm(validationObject);

      //Since non-required fields are "valid" when they are empty, we need an
      //alternate way to keep labels raised when there is content in their
      //associated input field
      $( ".vita-form-textfield input" ).focusout(function() {
        if($.trim($(this).val()).length > 0) {
          $label = $(this).closest(".vita-form-textfield").find(".vita-form-label").addClass( "vita-form-label__floating" );
        } else {
          $label = $(this).closest(".vita-form-textfield").find(".vita-form-label").removeClass( "vita-form-label__floating" );
        }
      });
    }
  });

};

var questionRules = function(question) {
  var rule = [];
  if (question.required == true) {
    rule["required"] = true;
  }
  if (question.validationType != null) {
    rule[question.validationType] = true;
  }
  return rule;
}

var questionMessage = function(question) {
  var id = 'vita' + question.questionId;
  var message = [];
  if (question.errorMessage != null) {
    message[id] = question.errorMessage;
  }
  return message;
}

var validateForm = function(validationObject) {
  $("#vitaSignupForm").validate(validationObject);
};

// To be deleted at a later point
var validateSampleForm = function() {
  var validationString = '{ "rules": {' +
  '    "firstName": "required",' +
  '    "lastName": "required",' +
  '    "email": {' +
  '      "required": true,' +
  '      "email": true' +
  '    },' +
  '    "phone": {' +
  '      "required": true,' +
  '      "phoneUS": true' +
  '    },' +
  '    "address1": "required",' +
  '    "city": "required",' +
  '    "state": {' +
  '      "required": true,' +
  '      "stateUS": true' +
  '    },' +
  '    "zip": {' +
  '      "required": true,' +
  '      "zipcodeUS": true' +
  '    },' +
  '    "date": {' +
  '      "required": true,' +
  '      "date": true' +
  '    }' +
  '  },' +
  '  "messages": {' +
  '    "email": {' +
  '      "required": "We need your email address to confirm your appointment",' +
  '      "email": "Your email address must be in the format of name@domain.com"' +
  '    }' +
  '  }' +
  '}';

  var validateJson = JSON.parse(validationString);

  $("#vitaSignupForm").validate(validateJson);
};
