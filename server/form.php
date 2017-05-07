<?php
	require 'config.php';
	$conn = $DB_CONN;

	$retrieve = $_GET['retrieve'];

	if ($retrieve == "questions") {
		// TODO make this handle multiple locations, if necessary
		$questionStatement = $conn->prepare('SELECT questionId, string, inputType, placeholder, tag, subheading, required, archived FROM Question
			WHERE (archived != true)
			ORDER BY subheading');
		$questionStatement->execute();
		$results = $questionStatement->fetchAll();

		$questions = [];
		foreach ($results as $result) {
			$questions[] = $result;
		}

		echo json_encode($questions);

		$questionStatement = null;
	} else if ($retrieve == "options") {
		$questionId = $_REQUEST['questionId'];

		$optionsStatement = $conn->prepare('SELECT answerId, string, archived FROM Answer
			WHERE questionId = ' . $questionId);
		$optionsStatement->execute();
		$results = $optionsStatement->fetchAll();

		$options = [];
		foreach ($results as $result) {
			$options[] = $result;
		}

		// $options = array("1","2","3");

		echo json_encode($options);
	}
