require(['jquery'], function ($) {
	$(document).ready(function () {
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
	});
});
