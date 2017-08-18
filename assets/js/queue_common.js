var displayDate = new Date(),
	monthStrings = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

$('.date-wrap').on('click', '.date-back', function() {
	displayDate.setDate(displayDate.getDate() - 1);
	refresh();
});

$('.date-wrap').on('click', '.date-forward', function() {
	displayDate.setDate(displayDate.getDate() + 1);
	refresh();
});

function displayQueueDate() {
	let year = displayDate.getFullYear(), month = displayDate.getMonth(), day = displayDate.getDate();
	if (day < 10) day = `0${day}`;
	$('.date').html(`${monthStrings[month]} ${day}, ${year}`);
}

function populateQueue() {
	let year = displayDate.getFullYear(), month = displayDate.getMonth() + 1, day = displayDate.getDate();
	if (month < 10) month = `0${month}`;

	$.get({
		data: `displayDate=${year}-${month}-${day}`,
		url: '/server/queue.php',
		dataType: 'json',
		cache: false,
		success: function(r) {
			$('.queue-table').html("<div class='flex box empty-queue-message'>Queue is empty</div>");
			$('.empty-queue-message').toggle(r.length === 0); // Toggles the empty queue message if there are no results
			$('.queue-size-count').html(r.length); // This does nothing in the private queue

			for (let i = 0; i < r.length; i++) {
				let t = new Date(r[i].scheduledTime), h = t.getHours() % 12, m = t.getMinutes();
				if (h === 0) h = 12;
				if (m < 10) m = `0${m}`;

				let record = {
					id: r[i].appointmentId,
					position: i + 1,
					name: `${r[i].firstName} ${r[i].lastName}.`,
					isPresent: false, // TODO: Implement isPresent logic
					isOnTime: new Date().getTime() < t.getTime(),
					time: `${h}:${m}`
				};

				$('.queue-table').append(Mustache.render($('.queue-record-template').html(), record));
			}
		}
	});
}