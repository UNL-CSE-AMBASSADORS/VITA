<?php
	require 'config.php';
	$conn = $DB_CONN;

	$retrieve = $_GET['retrieve'];

	if ($retrieve == "questions") {
		$subheadings = [];
		if (isset($_GET['subheadings'])) {
			$getSubheadings = $_GET['subheadings'];
			if (is_array($getSubheadings)) {
				$subheadings = $getSubheadings;
			} else {
				$subheadings[] = $getSubheadings;
			}
		}

		$questionStatement = $conn->prepare('SELECT questionId, string, tag, required, archived, inputType, placeholder, subheading, validationType, hint, errorMessage FROM Question q
			JOIN QuestionInformation qi on qi.questionInformationId = q.questionInformationId
      WHERE (archived != true) and (qi.subheading = ?)
			ORDER BY subheading');

		$questions = [];
		foreach ($subheadings as $subheading) {
			$questionStatement->execute(array($subheading));

			$results = $questionStatement->fetchAll();

			foreach ($results as $result) {
				$questions[] = $result;
			}
		}

		echo json_encode($questions);

		$questionStatement = null;
	} else if ($retrieve == "options") {
		$questionId = $_REQUEST['questionId'];

		$optionsStatement = $conn->prepare('SELECT possibleAnswerId, string, archived FROM PossibleAnswer
			WHERE questionId = ?');
		$optionsStatement->execute(array($questionId));
		$results = $optionsStatement->fetchAll();

		$options = [];
		foreach ($results as $result) {
			$options[] = $result;
		}

		echo json_encode($options);

		$optionsStatement = null;
	}
