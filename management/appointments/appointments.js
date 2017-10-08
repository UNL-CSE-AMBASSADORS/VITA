let defaultColumnNames = ["Scheduled Time", "First Name", "Last Name", "Phone Number", "Email Address", "Appointment ID"];

let downloadCsv = function() {
	let siteSelect = document.getElementById("siteSelect");
	let siteSelectedOption = siteSelect.options[siteSelect.selectedIndex];
	let date = document.getElementById("dateInput").value;
	let filteringData = {
		"date": date,
		"siteId": siteSelectedOption.value
	};

	$.ajax({
		url: "/server/management/appointments.php",
		type: "POST",
		dataType: "JSON",
		data: (filteringData),
		cache: false,
		success: function(response) {
			let fileName = `Appointments_${filteringData.date}_${siteSelectedOption.innerHTML}.csv`;
			let csvString = convertArrayOfObjectsToCsv(response, defaultColumnNames);

			exportAsCsv(csvString, fileName);
		},
		error: function(response) {
			alert("Unable to get data");
		}
	});
}

// Perhaps we should consider some sort of JS utility file for this CSV exporting stuff?
// Got this code from https://halistechnology.com/2015/05/28/use-javascript-to-export-your-data-as-csv/ and modified it slightly
let exportAsCsv = function(csvString, fileName) {
	if (csvString == null) return;
	
	if (!csvString.match(/^data:text\/csv/i)) {
		csvString = "data:text/csv;charset=utf-8," + csvString;
	}

	let data = encodeURI(csvString);

	let link = document.createElement("a");
	link.setAttribute("href", data);
	link.setAttribute("download", fileName);
	link.click();
}

// Got this code from https://halistechnology.com/2015/05/28/use-javascript-to-export-your-data-as-csv/ and modified it slightly
let convertArrayOfObjectsToCsv = function(data, columnHeaders) { 
	let columnDelimiter = ",";
	let lineDelimiter = "\n";
	let columnHeadersString = columnHeaders.join(columnDelimiter);

	if (data == null || !data.length) {
		return columnHeadersString; // If there is no data, we will just return a string containing the headers for the CSV so that it could still be exported
	}

	let result = "";	
	let keys = Object.keys(data[0]);	

	result += columnHeadersString;
	result += lineDelimiter;

	data.forEach(function(item) {
		for (let i = 0; i < keys.length; i++) {
			result += (item[keys[i]] == undefined) ? "" : item[keys[i]]; // Avoid it printing "null", instead we print an empty string
			result += columnDelimiter;
		}
		result += lineDelimiter;
	});

	return result;
}