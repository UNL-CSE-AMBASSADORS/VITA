$(document).ready(function() {
  loadQuestions();

  // to be deleted at a later point
  validateSampleForm();

});

var loadQuestions = function() {
  $.getScript('/assets/js/form.js', function() {
  });

  $.getJSON({
    url: '/server/form.php?retrieve=questions&subheadings[]=Contact Information&subheadings[]=Language Information&subheadings[]=Background Information',
    success: function(result) {
      var containingClass = 'vita-signup-form';
      $('.' + containingClass).html(""); // Clear any data in the form right now

      // Create arrays that will be used for validation
      var rules = [];
      var messages = [];

      // Open form tag
      var formId = 'vitaSignupForm';
      startForm(containingClass, formId, "/");
      newFormTitle(containingClass, 'Sign Up for a VITA Appointment');

      // For each question in the form
      var currentSubheading = result[0].subheading;
      for(var i = 0; i < result.length; i++) {
        // If the question is in a new subheading, add that subheading to the form
        if (result[i].subheading != currentSubheading) {
          newSubheading(containingClass, result[i].subheading);
          currentSubheading = result[i].subheading;
        }
        // Set the css id to match the tag from the question table, which will also be in the answer table
        var id = result[i].tag;
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
          newSelect(containingClass, formId, id, result[i].string, result[i].questionId, result[i].hint, result[i].required);
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

      // Since non-required fields are "valid" when they are empty, we need an
      // alternate way to keep labels raised when there is content in their
      // associated input field
      var isBlank = $.trim($(this).val()).length > 0;
      $label = $(this).closest(".vita-form-textfield").find(".vita-form-label").toggleClass( "vita-form-label__floating", isBlank );

      // Form submission
      $('#vitaSignupForm').submit(function() {
        if (!$(this).valid()) {
          return false;
        }

        var data = $(this).serialize();
        console.log(data);

        // AJAX Code To Submit Form.
  			$.ajax({
  				type: "post",
  				url: "/server/signup.php",
  				data: data,
  				cache: false,
  				success: function(submitResponse){
            alert(submitResponse);
            // alert('Your message has been sent.');
  				}
  			});
        return true;
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
    // There should really be some form of validation for the validationType
    // One option would be to make a new table with all of the possible validation types
    // which would be both a foreign key for the validationType field in the questionInformation table
    // and a way to validate at this point too. If the validationType has a typo for any
    // given field, the front-end form validation fails to run at all for any of the fields.
    rule[question.validationType] = true;
  }
  return rule;
}

var questionMessage = function(question) {
  var id = question.tag;
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
