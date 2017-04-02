$(document).ready(function() {
    window.setInterval(loadQueue, 5 * 1000);
});

var loadQueue = function() {
    $.post({
        url: 'assets/server/queue.php',
        dataType: 'json',
        success: function(result) {
            $('.vita-queue').html(""); // Clear any data in the queue right now

            for(var i = 0; i < result.length; i++) {
                var appointment = result[i];
                var currentTime = new Date();
                // TODO: This might get stored differently in database, might need to work
                var appointmentTime = Date.parse(appointment.date + ' ' + appointment.time);
                var timeDifference = appointmentTime.getTime() - currentTime.getTime();
                var waitTime = Math.round(timeDifference / (60 * 1000));

                $('.vita-queue').append("<div class='vita-queue-entry'>" +
        				"<div class='vita-queue-entry-position'>" + (i + 1) + "</div>" +
        				"<div class='vita-queue-entry-name'>" + appointment.firstName + " " + appointment.lastName + ".</div>" +
        				"<div class='vita-queue-entry-wait'>" + waitTime + " Minutes</div>" +
        			"</div>");
            }
        }
    });
}
