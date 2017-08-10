var REFRESH_SEC = 15,
	displayDate = new Date(),
	monthStrings = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
	refreshing = null;

$('.details-controls').on('click', '.details-close', function() {
	$('.details').css('display', 'none');
});

$('.queue-table').on('click', '.queue-record', function() {
	$.get({
		data: 'id=' + $(this).find('.queue-record-id').html(),
		url: '/server/queue_priv.php',
		dataType: 'json',
		cache: false,
		success: function(r) {
			var time = new Date(r[0].scheduledTime),
				h = time.getHours() % 12,
				m = time.getMinutes();
			if (h === 0) h = 12;
			if (m < 10) m = `0${m}`;

			$('.details-name').html(`${r[0].firstName} ${r[0].lastName}`);
			$('.details-email').html(r[0].emailAddress ? r[0].emailAddress : 'None');
			$('.details-phone').html(r[0].phoneNumber ? r[0].phoneNumber : 'None');
			$('.details-site-name').html(r[0].title);
			$('.details-time').html(`${h}:${m}`);
			$('.details').css('display', 'flex');
		}
	});
});

$(document).ready(refresh);

function refresh() {
	displayQueueDate();
	populateQueue();

	clearTimeout(refreshing);
	refreshing = setTimeout(refresh, REFRESH_SEC * 1000);
}