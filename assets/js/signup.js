$(document).ready(function() {
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

  // validateForm();

  loadQuestions();

});

var loadQuestions = function() {
  $.getScript('/assets/js/form.js', function() {
  });

  $.getJSON({
    url: '/server/form.php?retrieve=questions',
    success: function(result) {
      var containingClass = 'vita-signup-form';
      $('.' + containingClass).html(""); // Clear any data in the form right now

      startForm(containingClass, 'vitaSignupForm');
      newFormTitle(containingClass, 'Sign Up for a VITA Appointment');

      var currentSubheading = result[0].subheading;
      for(var i = 0; i < result.length; i++) {
        if (result[i].subheading != currentSubheading) {
          newSubheading(containingClass, result[i].subheading);
          currentSubheading = result[i].subheading;
        }
        if (result[i].inputType.toLowerCase() == "text" || result[i].inputType.toLowerCase() == "email") {
          newTextField(containingClass, 'vita' + result[i].questionId, result[i].string, result[i].required);
        } else if (result[i].inputType.toLowerCase() == "select") {
          newSelect(containingClass, 'vita' + result[i].questionId, result[i].string, result[i].questionId, result[i].required);
        } else {
          $('.' + containingClass + ' form').append("<div>" + result[i].inputType + "</div>");
        }
      }

      submitButton(containingClass);
      endForm(containingClass);
    }
  });

}

var validateForm = function() {
  $("#vitaSignupForm").validate({
    rules: {
      firstName: "required",
      lastName: "required",
      email: {
        required: true,
        email: true
      },
      phone: {
        required: true,
        phoneUS: true
      },
      address1: "required",
      city: "required",
      state: {
        required: true,
        stateUS: true
      },
      zip: {
        required: true,
        zipcodeUS: true
      },
      date: {
        required: true,
        date: true
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
