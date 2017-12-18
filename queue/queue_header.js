$(document).ready(function() {
	loadAllSites();
});

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
			for (let i = 0; i < response.length; i++) {
				siteSelect.options.add(new Option(response[i].title, response[i].siteId));
			}
		},
		error: function(response) {
			alert("Unable to load sites. Please refresh the page in a few minutes.");
		}
	});
}
