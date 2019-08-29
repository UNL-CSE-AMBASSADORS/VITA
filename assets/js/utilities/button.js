// AngularJS implementation of Bootstrap's jQuery powered buttons
// This creates a directive that uses the same data-toggle attribute as Bootstrap, so the html is the same
define('toggleDirective', [], function() {
	function toggleDirective() {
		function link(scope, element, attributes) {

			if (attributes.toggle === "buttons") {
				const activeClassName = 'dcf-btn-primary';
				const nonActiveClassName = 'dcf-btn-secondary';

				// Note, this makes all data-toggle="buttons" behave like radio buttons
				element.find('label').on('click', function (e) {
					const $button = angular.element(e.target);
					element.children().removeClass(`active ${activeClassName}`).addClass(`${nonActiveClassName}`);
					$button.addClass(`active ${activeClassName}`).removeClass(`${nonActiveClassName}`);
				});
			}
		}

		return {
			restrict: 'A',
			link: link
		}
	}

	return toggleDirective;
});
