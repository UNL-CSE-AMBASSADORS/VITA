"use strict";

$(document).ready(function () {
	loadAllSites();
});

var downloadSchedule = function downloadSchedule() {
	var siteSelect = document.getElementById("siteSelect");
	var siteSelectedOption = siteSelect.options[siteSelect.selectedIndex];
	var date = document.getElementById("dateInput").value;
	var siteId = siteSelectedOption.value;

	var downloadLink = document.createElement("a");
	downloadLink.setAttribute("href", "/server/management/appointments.php?date=" + date + "&siteId=" + siteId);
	downloadLink.setAttribute("target", "_blank");
	downloadLink.style.display = "none";
	document.body.append(downloadLink);
	downloadLink.click();
};

var loadAllSites = function loadAllSites() {
	$.ajax({
		url: "/server/api/sites/getAll.php",
		type: "GET",
		dataType: "JSON",
		data: {
			"siteId": true,
			"title": true
		},
		cache: false,
		success: function success(response) {
			var siteSelect = document.getElementById("siteSelect");
			siteSelect.options.add(new Option("All Sites", -1));
			for (var i = 0; i < response.length; i++) {
				siteSelect.options.add(new Option(response[i].title, response[i].siteId));
			}
		},
		error: function error(response) {
			alert("Unable to load sites. Please refresh the page in a few minutes.");
		}
	});
};