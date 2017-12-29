$(document).ready(function() {
	// Get all of the appointment information stored Date->Site->Time
	loadAllAppointments();
	// Listen for changes to Date and Site fields to conditionally hide/show other fields
	updateSitesDatesAndTimes();
});

/** Class representing a map of dates => sites => times => remaining spots. */
class DateSiteTime {
	constructor() {
		this.dates = new Object(); // array of DateSiteMap objects
		this.siteTitles = new Map();
	}

	addMap(datesMap) {
		this.dates = datesMap;
	}

	hasDate(dateObj) {
		return dateObj.toISOString().substring(0, 10) in this.dates;
	}

	hasTimeSlotsRemaining(dateObj) {
		let date = dateObj.toISOString().substring(0, 10);
		return this.dates[date]["hasAvailability"];
	}

	updateGlobalSites(dateInput) {
		let dateObj = new Date(dateInput);
		let date = dateObj.toISOString().substring(0, 10);
		let localSites = this.dates[date]["sites"];
		sites = [];
		for (let site in localSites) {
			sites.push({
				"siteId": site,
				"title": localSites[site]["site_title"],
				"hasTimeSlotsRemaining": localSites[site]["hasAvailability"]
			});
		}
	}

	updateGlobalTimes(dateInput, site) {
		let dateObj = new Date(dateInput);
		let date  = dateObj.toISOString().substring(0, 10);
		times = this.dates[date]["sites"][site]["times"];
	}
}

// Load all of the appointments and store in a global variable
// Default is to only load the current year
function loadAllAppointments(year=(new Date()).getFullYear()) {
	var request = $.ajax({
		url: "/server/api/appointments/getTimes.php",
		type: "GET",
		dataType: "JSON",
		data: ({
			"year": year
		}),
		cache: false
	})
	.done(function(data) {
		dateSitesTimes.addMap(data.dates);
		$("#dateInput").datepicker({
			// Good example: https://stackoverflow.com/a/1962849/7577035
			// called for every date before it is displayed
			beforeShowDay: function(date) {
				if (dateSitesTimes.hasDate(date)) {
					if (dateSitesTimes.hasTimeSlotsRemaining(date)) {
						return [true, ''];
					} else {
						return [false, 'full'];
					}
				} else {
					return [false, ''];
				}
			},
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 100);
				}, 0);
			}
		});
	});
}

// This function will watch the date selector and the site selector for changes.
function updateSitesDatesAndTimes() {
	let dateInput = $("#dateInput");
	let siteSelect = $("#sitePicker select");

	// When the date selector changes...
	// 1. Display the site picker
	// 2. Update the site picker with the sites related to the selected date.
	dateInput.change(function() {
		$("#sitePicker").show();
		$("#timePicker").hide();
		// Clear any previously selected input
		$("#timePickerSelect").html('');
		$("#sitePickerSelect").html('');
		var date = this.value;

		dateSitesTimes.updateGlobalSites(date);
		siteSelect.append($('<option disabled selected value="" style="display:none"> -- select an option -- </option>'));
		for(let site in sites) {
			if (!sites[site]["hasTimeSlotsRemaining"]) continue;
			siteSelect.append($('<option>', {
				value: sites[site]["siteId"],
				text : sites[site]["title"]
			}));
		}
	});

	// When the value of the site selector is updated...
	// 1. Display the date picker
	// 2. Update the options for the date selector to disable options that are not available at this site.
	siteSelect.change(function() {
		$("#timePicker").show();
		// Clear any previously selected date
		$("#timePickerSelect").html('');
		var site = this.value;
		var date = $("#dateInput").val();

		dateSitesTimes.updateGlobalTimes(date, site);
		var timeSelect = $("#timePicker select")
		timeSelect.append($('<option disabled selected value="" style="display:none"> -- select an option -- </option>'));
		for(const time in times) {
			timeSelect.append($('<option>', {
				value: time,
				text : time + (times[time] > 0 ? '' : ' - FULL'),
				disabled: times[time] > 0 ? false : true
			}));
		}
	});
}

let dateSitesTimes = new DateSiteTime(),
	sites = [], 
	times = [];