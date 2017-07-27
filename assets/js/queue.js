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

            for (var i = 0; i < result.length; i++) {
				var time = result[i].scheduledTime.split(' ');
				var hour = time[1].split(':')[0];
				var min = time[1].split(':')[1];
				hour = hour > 12 ? hour - 12 : hour;
                $('.queue-table').append(
					"<div class='queue-record'>" +
        				"<div class='wrap-left queue-position-wrap'>" + (i + 1) + "</div>" +
        				"<div class='wrap-left queue-name-wrap'>" + result[i].firstName + " " + result[i].lastName + ".</div>" +
        				"<div class='wrap-right queue-time-wrap'>" + hour + ":" + min + "</div>" +
					"</div>"
				);
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
