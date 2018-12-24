function downloadSiteSchedule() {
	const fileUrl = "/server/download/downloadSchedule.php";
	let iframe = document.getElementById("hiddenDownloader");
	if (iframe == null) {
		iframe = document.createElement('iframe');
		iframe.id = "hiddenDownloader";
		iframe.style.visibility = 'hidden';
		iframe.style.display = 'none';
		document.body.appendChild(iframe);
	}

	iframe.src = fileUrl;
}