$(document).ready(function() {
	loadAllSites();
	$("#dateInput").val(getCurrentDateString());
});

let downloadAppointmentSchedule = function() {
	let siteId = getSelectedSiteId();
	let date = getSelectedDate();	
	clickDownloadLink(`/server/management/documents/appointmentsSchedule.php?date=${date}&siteId=${siteId}`);
};

let downloadVolunteerSchedule = function() {
	let siteId = getSelectedSiteId();
	let date = getSelectedDate();
	clickDownloadLink(`/server/management/documents/volunteerSchedule.php?date=${date}&siteId=${siteId}`);
};

let getSelectedSiteId = function() {
	let siteSelect = document.getElementById("siteSelect");
	let siteSelectedOption = siteSelect.options[siteSelect.selectedIndex];
	return siteSelectedOption.value;
}

let getSelectedDate = function() {
	return document.getElementById("dateInput").value;
}

let clickDownloadLink = function(url) {
	let downloadLink = document.createElement("a");
	downloadLink.setAttribute("href", url);
	downloadLink.setAttribute("target", "_blank");
	downloadLink.style.display = "none";
	document.body.append(downloadLink);
	downloadLink.click();
}

let loadAllSites = function() {
	$.ajax({
		url: "/server/api/sites/getAll.php",
		type: "GET",
		dataType: "JSON",
		data: ({
			"siteId": true,
			"title": true
		}),
		cache: false,
		success: function(response) {
			let siteSelect = document.getElementById("siteSelect");
			siteSelect.options.add(new Option("All Sites", -1));
			for (let i = 0; i < response.length; i++) {
				siteSelect.options.add(new Option(response[i].title, response[i].siteId));
			}
		},
		error: function(response) {
			alert("Unable to load sites. Please refresh the page in a few minutes.");
		}
	});
}

let getCurrentDateString = function() {
	let now = new Date();
	let day = ("0" + now.getDate()).slice(-2);
	let month = ("0" + (now.getMonth() + 1)).slice(-2);
	return `${now.getFullYear()}-${month}-${day}`;
}
