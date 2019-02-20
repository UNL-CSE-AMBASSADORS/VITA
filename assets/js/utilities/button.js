// AngularJS Version
// This creates a directive that uses the same data-toggle attribute, so the html is the same
define('toggleDirective', [], function() {
	function toggleDirective() {
		function link(scope, element, attrs) {

			if (attrs.toggle == "buttons") {
				var activeClassName = 'dcf-btn-primary';
				var nonActiveClassName = 'dcf-btn-secondary';
				
				// Note, this makes all data-toggle="buttons" behave like radio buttons
				element.find('label').on('click', function (e) {
					var $button = angular.element(e.target);
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
