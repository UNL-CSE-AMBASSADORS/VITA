$(document).ready(function() {
    loadQueue();
    window.setInterval(loadQueue, 5 * 1000);
});

let loadQueue = function() {
    $.post({
        url: '../server/queue.php',
        dataType: 'json',
        success: function(result) {
            $('.queue-table').html(""); // Clear any data in the queue right now
			
            for(let i = 0; i < result.length; i++) {
                let appointment = result[i];
				
				let fullName = `${appointment.firstName} ${appointment.lastName}`;
				let time = formatScheduledTime(appointment.scheduledTime);
				
				$('.queue-table').append("<div class='queue-table-record'>" +
					"<div class='wrap-left queue-position-wrap'>" +
						"<div class='queue-position-field'>" + (i + 1) + "</div>" +
					"</div>" +
					"<div class='wrap-left queue-name-wrap'>" +
						"<div class='queue-name-field'>" + fullName + ".</div>" +
					"<div class='wrap-right queue-time-wrap'>" +
						"<div class='queue-time-field'>" + time + "</div>"+
					"</div>" +
				"</div>");
            }
        }
    });
}

let formatScheduledTime = function(scheduledTime) {
	let date = new Date(scheduledTime);
	
	let hour = date.getHours() % 12 || 12;
	let minute = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
	let period = date.getHours() < 12 ? 'am' : 'pm';
	
	return `${hour}:${minute} ${period}`;
}
