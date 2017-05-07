<?php
	require 'config.php';
	$conn = $DB_CONN;

	// echo "<script>console.log( 'Pre-Load' );</script>";


	// $table = $_REQUEST['table'];

	// TODO make this handle multiple locations, if necessary
	// $subheadingStatement = $conn->prepare('SELECT subheading FROM Question GROUP BY subheading');
	// $subheadingStatement->execute();
	// $subheadings = $subheadingStatement->fetchAll();

	// $questionStatement = $conn->prepare('SELECT questionId, string, inputType, placeholder, tag, subheading, required, archived FROM Question
	// 	WHERE (archived != true)');
	// $questionStatement->execute();
	// $questions = $questionStatement->fetchAll();

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	// $groupedQuestions = [][];
	// foreach ($results as $result) {
	// 	$groupedQuestions[$result['subheading']][] = $result;
	// }

	// echo "<script>console.log( 'Array: " . $groupedQuestions . "' );</script>";
	// echo "<script>console.log( 'JSON Encoded Array: " . json_encode($groupedQuestions) . "' );</script>";

	$subheadings = array("1", "2", "3");
	echo json_encode($subheadings);

	// $subheadingStatement->close();
	// $questionStatement->close();
	$conn->close();
