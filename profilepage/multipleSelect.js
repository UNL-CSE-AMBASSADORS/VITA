$(function() {
	function moveItems(origin, dest) {
		$(origin).find(':selected').appendTo(dest);
	}

	function moveAllItems(origin, dest) {
		$(origin).children().appendTo(dest);
	}
	$('#left').click(function() {
		moveItems('#sbTwo', '#sbOne');
	});
	$('#right').on('click', function() {
		moveItems('#sbOne', '#sbTwo');
	});
	$('#leftall').on('click', function() {
		moveAllItems('#sbTwo', '#sbOne');
	});
	$('#rightall').on('click', function() {
		moveAllItems('#sbOne', '#sbTwo');
	});
});
