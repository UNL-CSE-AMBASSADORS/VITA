define('notificationUtilities', [], function() {

	function notificationUtilities() {
		return {
			giveNotice: function(title, message, affirmative = true) {
				WDN.initializePlugin('notice');
				const body = angular.element(document.querySelector('body'));
				body.append(`
					<div class="wdn_notice ${affirmative ? 'affirm' : 'negate'}" data-overlay="maincontent" style="position: fixed; top: 10%;">
						<div class="close">
							<a href="#">Close this notice</a>
						</div>
						<div class="message">
							<p class="title">${title}</p>
							<p>${message}</a>
							</p>
						</div>
					</div>`);
			}
		};
	}

	notificationUtilities.$inject = [];

	return notificationUtilities;
});