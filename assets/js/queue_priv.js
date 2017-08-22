var REFRESH_SEC = 15, refreshing = null, openedAppointmentId = 0;

$(document).ready(refresh);

$('.date-wrap').on('click', '.date-back', hideDetails);
$('.date-wrap').on('click', '.date-forward', hideDetails);
$('.details-controls').on('click', '.details-close', hideDetails);

// Cancels the selected appointment
$('.details-controls').on('click', '.details-cancel', function() {
	if (confirm('Are you sure you would like to cancel this appointment? Click OK to cancel this appointment.')) {
		$.get({
			data: `id=${openedAppointmentId}&action=cancel`,
			url: '/server/queue_priv.php',
			success: function(r) {
				hideDetails();
				populateQueue();
			}
		});
	}
});

// Retrieves and displays the data associated with the selected appointment
$('.queue-table').on('click', '.queue-record', function() {
	openedAppointmentId = $(this).data('appointmentId');

	// Adds a soft highlight to the last selected appointment
	$('.queue-record').removeClass('theme-light');
	$(this).addClass('theme-light');

	$.get({
		data: `id=${openedAppointmentId}&action=display`,
		url: '/server/queue_priv.php',
		dataType: 'json',
		cache: false,
		success: function(r) {
			let t = new Date(r[0].scheduledTime), h = t.getHours() % 12, m = t.getMinutes();
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

function refresh() {
	displayQueueDate();
	populateQueue();

	clearTimeout(refreshing);
	refreshing = setTimeout(refresh, REFRESH_SEC * 1000);
}

function hideDetails() {
	$('.queue-record').removeClass('theme-light');
	$('.details').css('display', 'none');
	openedAppointmentId = 0;
}