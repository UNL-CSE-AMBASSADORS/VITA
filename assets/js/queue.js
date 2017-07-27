var REFRESH_SEC = 5;

$(document).ready(function() {
	keepTime();
    loadQueue();
    window.setInterval(loadQueue, REFRESH_SEC * 1000);
});

var loadQueue = function() {
    $.post({
        url: '../server/queue.php',
        dataType: 'json',
        success: function(result) {
			$('.queue-table').html("<div class='flex empty-queue-message'>Queue is empty</div>");
			$('.empty-queue-message').toggle(result.length === 0);
			$('.queue-size-count').html(result.length);

            for (var i = 0; i < result.length; i++) {
				var time = result[i].scheduledTime.split(' ')[1].split(':');
				var record = {
					position: i + 1,
					name: result[i].firstName + ' ' + result[i].lastName + '.',
					time: time[0] % 12 + 1 + ':' + time[1]
				};

				var template = $('.queue-record-template').html();
                $('.queue-table').append(Mustache.render(template, record));
            }
        }
    });
}

function keepTime() {
    var now = new Date();
	var hour = now.getHours();
	var min = now.getMinutes();

	if (hour > 12) {
		hour -= 12;
		$('.clock-am').addClass('inactive-period');
		$('.clock-pm').removeClass('inactive-period');
	} else {
		$('.clock-am').removeClass('inactive-period');
		$('.clock-pm').addClass('inactive-period');
	}

	hour = hour < 10 ? '0' + hour : hour;
	min = min >= 10 ? min : '0' + min;
	$('.clock-time').html(hour + ':' + min);
    window.setInterval(keepTime, REFRESH_SEC * 1000);
}
