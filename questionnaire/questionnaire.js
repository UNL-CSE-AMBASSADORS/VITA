require.config({
	paths: {
		jqueryvalidation: '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min'
	}
});

require(['jquery', 'jqueryvalidation'], function ($) {
	window.jQuery = $;

	$(document).ready(function() {
		initializeConditionalFormFields();
		initializeCantHelpListeners();
		initializeButtonToggles();

		// Add in form validation
		$("#vitaQuestionnaireForm").validate({
			errorPlacement: function(error, element) {
				error.insertAfter( element.closest(".error-placement") );
			}
		});
	});

	function initializeButtonToggles() {
		$('input[type="radio"]').click((e) => {
			var activeClassName = 'dcf-btn-primary';
			var nonActiveClassName = 'dcf-btn-secondary';
			var changed = true;
			var $button = $(e.target).closest('.dcf-btn');
			var $parent = $button.closest('[data-toggle="buttons"]');
			if ($parent.length) {
				var $input = $(e.target);
				if ($input.prop('checked')) changed = false;
				$parent.find('.active').removeClass(`active ${activeClassName}`).addClass(`${nonActiveClassName}`);
				$button.addClass(`active ${activeClassName}`).removeClass(`${nonActiveClassName}`);
				$input.prop('checked', $button.hasClass('active'));
				if (changed) $input.trigger('change');
			} else {
				$button.attr('aria-pressed', !$button.hasClass('active'));
				$button.toggleClass(`${activeClassName}`);
				$button.toggleClass(`${nonActiveClassName}`);
				$button.toggleClass('active');
			}
		});
	}

	function initializeCantHelpListeners() {
		let depreciationSchedule = $("#depreciationSchedule");
		let depreciationScheduleValues = depreciationSchedule.find('input:radio[name="1"]');

		let scheduleF = $("#scheduleF");
		let scheduleFValues = scheduleF.find('input:radio[name="2"]');

		let homeBasedNetLoss = $("#homeBasedNetLoss");
		let homeBasedNetLossValues = homeBasedNetLoss.find('input:radio[name="4"]');
		
		let homeBased10000 = $("#homeBased10000");
		let homeBased10000Values = homeBased10000.find('input:radio[name="5"]');
		
		let homeBasedSEP = $("#homeBasedSEP");
		let homeBasedSEPValues = homeBasedSEP.find('input:radio[name="6"]');

		let homeBasedEmployees = $("#homeBasedEmployees");
		let homeBasedEmployeesValues = homeBasedEmployees.find('input:radio[name="7"]');

		let casualtyLosses = $("#casualtyLosses");
		let casualtyLossesValues = casualtyLosses.find('input:radio[name="8"]');

		let theftLosses = $("#theftLosses");
		let theftLossesValues = theftLosses.find('input:radio[name="9"]');

		let buttons = [depreciationSchedule, scheduleF, homeBasedNetLoss, homeBased10000, homeBasedSEP, homeBasedEmployees, casualtyLosses, theftLosses];
		let values = [depreciationScheduleValues, scheduleFValues, homeBasedNetLossValues, homeBased10000Values, homeBasedSEPValues, homeBasedEmployeesValues, casualtyLossesValues, theftLossesValues];

		for (let i = 0; i < buttons.length; i++) {
			values[i].change(function() {
				let value = this.value;
				if (this.checked) {
					let cantHelpText = buttons[i].find('p[class="cant-help-text"]');
					if (value === "1") {
						cantHelpText.show();
					} else {
						cantHelpText.hide();
					}
				}
			});
		}
	}

	function scrollDown(height, animationTime = 0) {
		$('html, body').animate({
			scrollTop: '+=' + height
		}, animationTime);
	}

	function initializeConditionalFormFields() {
		let animationTime = 300;
		
		// All of the questions that require conditions to be viewed.
		let homeBased = $("#homeBased");
		let homeBasedNetLoss = $("#homeBasedNetLoss");
		let homeBased10000 = $("#homeBased10000");
		let homeBasedSEP = $("#homeBasedSEP");
		let homeBasedEmployees = $("#homeBasedEmployees");

		// All the radio buttons
		let homeBasedValues = homeBased.find('input:radio[name="3"]');

		// To help hide everything and selectively show content
		let allUnderHomeBasedValues = homeBasedNetLoss.add(homeBased10000).add(homeBasedSEP).add(homeBasedEmployees);

		// Independent field = #homeBased
		// Dependent field = if yes --> #homeBasedNetLoss,#homeBased10000,#homeBasedSEP,#homeBasedEmployees
		homeBasedValues.change(function() {
			let value = this.value;
			if(this.checked){
				if(value === "1"){
					allUnderHomeBasedValues.slideUp(animationTime);
					allUnderHomeBasedValues.slideDown(animationTime);
					scrollDown(homeBased.height(), animationTime);
				} else if(value === "2"){
					allUnderHomeBasedValues.slideUp(animationTime);
				}
			}
		});
	}

	$('#vitaQuestionnaireForm').submit(function(e) {	
		e.preventDefault();

		// With jQuery UI styling on the radio buttons, a new listener is necessary to update error messages
		// However, this is only necessary after we have tried submitting once first.
		$("input:radio").change(function() {
			$("#vitaQuestionnaireForm").valid();
		});

		if (!$(this).valid()) {
			return false;
		}

		window.location.href = "/signup";

		return false;
	});
});