$('.date-wrap').on('click', '.date-back', function() {
	displayDate.setDate(displayDate.getDate() - 1);
	refresh();
});

$('.date-wrap').on('click', '.date-forward', function() {
	displayDate.setDate(displayDate.getDate() + 1);
	refresh();
});

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
			$('.queue-size-count').html(r.length); // This does nothing in the private queue

			for (var i = 0; i < r.length; i++) {
				var t = new Date(r[i].scheduledTime),
					hr = t.getHours() % 12 + 1,
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
		}
	});
}