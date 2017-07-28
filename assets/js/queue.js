var REFRESH_SEC = 15;

(function loadQueue() {
    $.get({
        url: '../server/queue.php',
		dataType: 'json',
        success: function(result) {
			$('.queue-table').html("<div class='flex empty-queue-message'>Queue is empty</div>");
			$('.empty-queue-message').toggle(result.length === 0);
			$('.queue-size-count').html(result.length);

            for (var i = 0; i < result.length; i++) {
				var time = result[i].scheduledTime.split(' ')[1].split(':');
				var hour = time[0] % 12 + 1;
				var min = time[1];
				if (hour < 10) hour = '0' + hour;

				var record = {
					position: i + 1,
					name: result[i].firstName + ' ' + result[i].lastName + '.',
					isLate: new Date(result[i].scheduledTime).getTime() < new Date().getTime(),
					time: hour + ':' + min
				};

				$('.queue-table').append(Mustache.render($('.queue-record-template').html(), record));
				time = null; hour = null; min = null; record = null;
			}

			result = null;
        }
    }).then(function() {
		setTimeout(loadQueue, REFRESH_SEC * 1000);
	});
})();

(function keepTime() {
	var hour = new Date().getHours();
	var min = new Date().getMinutes();

	$('.clock-am').toggleClass('inactive-period', hour >= 12);
	$('.clock-pm').toggleClass('inactive-period', hour < 12);

	hour %= 12;
	if (hour === 0) hour = 12;
	if (min < 10) min = '0' + min;

	$('.clock-time').html(hour + ':' + min);

	hour = null; min = null;
	setTimeout(keepTime, REFRESH_SEC * 1000);
})();
