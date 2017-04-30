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
});
