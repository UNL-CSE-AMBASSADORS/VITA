var REFRESH_SEC = 15;
var displayDate = new Date();
var monthStrings = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
var refreshing = setTimeout(refresh, REFRESH_SEC * 1000);

$(document).ready(function() {
	refresh();
	$('.date-back').click(function() { displayDate.setDate(displayDate.getDate() - 1); refresh(); });
	$('.date-forward').click(function() { displayDate.setDate(displayDate.getDate() + 1); refresh(); });
});

function refresh() {
	keepTime();
	
	var year = displayDate.getFullYear();
    var month = displayDate.getMonth() + 1;
    var day = displayDate.getDate();
	if (day < 10) day = '0' + day;
	$('.date').html(monthStrings[month - 1] + ' ' + day + ', ' + year);
	if (month < 10) month = '0' + month;

    $.get({
		data: 'displayDate=' + year + '-' + month + '-' + day,
		url: '../server/queue.php',
		dataType: 'json',
		cache: false,
		success: populateQueue
	});

	clearTimeout(refreshing);
	refreshing = setTimeout(refresh, REFRESH_SEC * 1000);
}

function keepTime() {
	var hour = new Date().getHours();
	var min = new Date().getMinutes();

	$('.clock-am').toggleClass('inactive-period', hour >= 12);
	$('.clock-pm').toggleClass('inactive-period', hour < 12);

	hour %= 12;
	if (hour === 0) hour = 12;
	if (min < 10) min = '0' + min;

	$('.clock-time').html(hour + ':' + min);
}

function populateQueue(result) {
	$('.queue-table').html("<div class='flex empty-queue-message'>Queue is empty</div>");
	$('.empty-queue-message').toggle(result.length === 0);
	$('.queue-size-count').html(result.length);

	for (var i = 0; i < result.length; i++) {
		var time = new Date(result[i].scheduledTime);
		var hour = time.getHours() % 12 + 1;
		var min = time.getMinutes();
		if (min < 10) min = '0' + min;

		var record = {
			position: i + 1,
			name: result[i].firstName + ' ' + result[i].lastName + '.',
			isLate: new Date(result[i].scheduledTime).getTime() < new Date().getTime(),
			time: hour + ':' + min
		};

		$('.queue-table').append(Mustache.render($('.queue-record-template').html(), record));
	}
}
