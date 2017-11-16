$(function() {
	function moveItems(origin, dest) {
		$(origin).find(':selected').appendTo(dest);
	}
	$('#left').click(function() {
		moveItems('#sbTwo', '#sbOne');
	});
	$('#right').on('click', function() {
		moveItems('#sbOne', '#sbTwo');
	});
});
