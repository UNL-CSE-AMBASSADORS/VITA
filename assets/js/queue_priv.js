var REFRESH_SEC = 15,
	displayDate = new Date(),
	monthStrings = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
	refreshing = null;

$(document).ready(refresh);

function refresh() {
	displayQueueDate();
	populateQueue();

	clearTimeout(refreshing);
	refreshing = setTimeout(refresh, REFRESH_SEC * 1000);
}

function listen() {
	$('.date-back').unbind('click');
	$('.date-back').click(function() {
		displayDate.setDate(displayDate.getDate() - 1);
		refresh();
	});

	$('.date-forward').unbind('click');
	$('.date-forward').click(function() {
		displayDate.setDate(displayDate.getDate() + 1);
		refresh();
	});

	$('.queue-record').unbind('click');
	$('.queue-record').click(function() {
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
				$('.details-email').html(r[0].emailAddress);
				$('.details-phone').html(r[0].phoneNumber);
				$('.details-site-name').html(r[0].title);
				$('.details-time').html(`${h}:${m}`);
				$('.details-id').show();
				listen();
			}
		});
	});
}

function displayQueueDate() {
	var y = displayDate.getFullYear(),
		m = displayDate.getMonth(),
		d = displayDate.getDate();
	if (d < 10) d = `0${d}`;
	$('.date').html(`${monthStrings[m]} ${d}, ${y}`);
}

function populateQueue() {
	var y = displayDate.getFullYear(),
		m = displayDate.getMonth() + 1,
		d = displayDate.getDate();
	if (m < 10) m = `0${m}`;

	$.get({
		data: `displayDate=${y}-${m}-${d}`,
		url: '/server/queue.php',
		dataType: 'json',
		cache: false,
		success: function(r) {
			$('.queue-table').html("<div class='flex box empty-queue-message'>Queue is empty</div>");
			$('.empty-queue-message').toggle(r.length === 0);

			for (var i = 0; i < r.length; i++) {
				var t = new Date(r[i].scheduledTime),
					hr = t.getHours() % 12,
					mn = t.getMinutes();
				if (mn < 10) mn = `0${mn}`;

				var record = {
					id: r[i].appointmentId,
					position: i + 1,
					name: `${r[i].firstName} ${r[i].lastName}.`,
					isPresent: false,
					isOnTime: new Date().getTime() < new Date(r[i].scheduledTime).getTime(),
					time: `${hr}:${mn}`
				};

				$('.queue-table').append(Mustache.render($('.queue-record-template').html(), record));
			}

			listen();
		}
	});
}
