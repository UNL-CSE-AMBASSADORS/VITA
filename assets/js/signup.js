$(document).ready(function() {
  // loadQuestions();

  // to be deleted at a later point
  validateSignupForm();

  // Since non-required fields are "valid" when they are empty, we need an
  // alternate way to keep labels raised when there is content in their
  // associated input field
  $(".vita-form-textfield input").blur(function() {
    var isBlank = $.trim($(this).val()).length > 0;
    $label = $(this).siblings(".vita-form-label").toggleClass( "vita-form-label__floating", isBlank );
  });

});

function validateSignupForm() {
  $("#vitaSignupForm").validate({
    rules: {
      "firstName": "required",
      "lastName": "required",
      email: {
        required: true,
        email: true
      },
      phone: {
        required: true,
        phoneUS: true
      }
    },
    messages: {
      email: {
        required: "We need your email address to confirm your appointment",
        email: "Your email address must be in the format of name@domain.com"
      }
    }
  });
}

// Form submission
$('#vitaSignupForm').submit(function(e) {
  if (!$(this).valid()) {
    return false;
  }

  // AJAX Code To Submit Form.
  $.ajax({
    url: "/server/signup.php",
    type: "post",
    dataType: 'json',
    data: $(this).serialize(),
    cache: false,
    success: function(submitResponse){
      console.log(submitResponse);
      window.location = "/";
    }
  });

  return true;
});

// To be deleted at a later point
function validateSampleForm() {
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
