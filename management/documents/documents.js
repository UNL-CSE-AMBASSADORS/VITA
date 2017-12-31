WDN.initializePlugin('jqueryui', [function () {
	require(['jquery'], function($) {
		$(document).ready(function() {
			window.downloadAppointmentSchedule = downloadAppointmentSchedule;
			window.downloadVolunteerSchedule = downloadVolunteerSchedule;

			loadAllSites();

			// https://stackoverflow.com/questions/27850791/input-type-date-format-and-datepicker-issues
			$("#dateInput").datepicker({
				dateFormat : 'mm/dd/yy',
				altFormat  : "yy-mm-dd",
				altField   : '#isoFormattedDate'
			});
			$("#isoFormattedDate").val(getCurrentDateString());
		});

		function downloadAppointmentSchedule() {
			let siteId = getSelectedSiteId();
			let date = getSelectedDate();	
			clickDownloadLink(`/server/management/documents/appointmentsSchedule.php?date=${date}&siteId=${siteId}`);
		};

		function downloadVolunteerSchedule() {
			let siteId = getSelectedSiteId();
			let date = getSelectedDate();
			clickDownloadLink(`/server/management/documents/volunteerSchedule.php?date=${date}&siteId=${siteId}`);
		};

		function getSelectedSiteId() {
			let siteSelect = document.getElementById("siteSelect");
			let siteSelectedOption = siteSelect.options[siteSelect.selectedIndex];
			return siteSelectedOption.value;
		}

		function getSelectedDate() {
			return document.getElementById("isoFormattedDate").value;
		}

		function clickDownloadLink(url) {
			let downloadLink = document.createElement("a");
			downloadLink.setAttribute("href", url);
			downloadLink.setAttribute("target", "_blank");
			downloadLink.style.display = "none";
			document.body.append(downloadLink);
			downloadLink.click();
		}

		function loadAllSites() {
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

		function getCurrentDateString() {
			let now = new Date();
			let day = ("0" + now.getDate()).slice(-2);
			let month = ("0" + (now.getMonth() + 1)).slice(-2);
			return `${now.getFullYear()}-${month}-${day}`;
		}
	});
}]);