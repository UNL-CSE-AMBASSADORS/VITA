$(document).ready(function() {
	loadAllSites();
});

let downloadSchedule = function() {
	let siteSelect = document.getElementById("siteSelect");
	let siteSelectedOption = siteSelect.options[siteSelect.selectedIndex];
	let date = document.getElementById("dateInput").value;
	let siteId = siteSelectedOption.value;

	let downloadLink = document.createElement("a");
	downloadLink.setAttribute("href", `/server/management/appointments.php?date=${date}&siteId=${siteId}`);
	downloadLink.setAttribute("target", "_blank");
	downloadLink.style.display = "none";
	document.body.append(downloadLink);
	downloadLink.click();
};

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
			for (let i = 0; i < response.length; $i++) {
				siteSelect.options.add(new Option(response[$i].title, response[$i].siteId));
			}
		},
		error: function(response) {
			alert("Unable to load sites. Please refresh the page in a few minutes.");
		}
	});
}
