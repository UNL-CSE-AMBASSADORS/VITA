<?php

// TODO, WILL BE REMOVED AFTER APPOINTMENTS BEGIN THIS SEASON
date_default_timezone_set('America/Chicago'); // Use CST
$now = date('Y-m-d H:i:s');
$signupBeginsDate = '2018-01-15 00:00:00';
if ($now < $signupBeginsDate) {
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		die('Appointment signup does not begin until January 15th, 2018. Please check back then.');
	}
}
// END TODO

function getLitmusQuestions() {
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/config.php";
	$conn = $DB_CONN;

	# smile and wave boys, this will all be getting replaced
	/*
	$queryStatement = $conn->prepare('SELECT q.questionId, pa.possibleAnswerId, pa.text AS possibleAnswerText,
				q.text AS questionText, q.lookupName
				FROM Question q
				JOIN Question q ON pa.questionId = q.questionId
				WHERE q.archived = FALSE AND pa.archived = FALSE');
	$queryStatement->execute();
	$resultSet = $queryStatement->fetchAll();

	$currentQuestionStartIndex = 0;
	for ($i = 0; $i < sizeof($resultSet); $i++) {
		if ($resultSet[$currentQuestionStartIndex]['questionId'] != $resultSet[$i]['questionId']) {
			addRadioSelection(array_slice($resultSet, $currentQuestionStartIndex, $i - $currentQuestionStartIndex));
			$currentQuestionStartIndex = $i;
		}
	}
	addRadioSelection(array_slice($resultSet, $currentQuestionStartIndex));
	*/
}

function addSelection($questionOptions) {
	$vitaFormRequired = "";
	$requiredClass = "";
	/*
	if ($questionOptions[0]['required'] == true) {
		$vitaFormRequired = "form-required";
		$requiredClass = 'class="required"';
	}
	*/

	$selectInput = '
			<div class="form-select">
				<label for="'.$questionOptions[0]['lookupName'].'" class="form-label '.$vitaFormRequired.'">'.$questionOptions[0]['questionText'].'</label>
				<div>
					<select id="'.$questionOptions[0]['lookupName'].'" '.$requiredClass.' name="'.$questionOptions[0]['questionId'].'">';
	foreach	($questionOptions as $option)	{
		$selectInput .= '
						<option value="'.$option['possibleAnswerId'].'">'.$option['possibleAnswerText'].'</option>';
	}
	$selectInput .= '
					</select>
					<div class="form-select__arrow"></div>
				</div>
			</div>';
	echo $selectInput;
}

function addRadioSelection($questionOptions) {
	$vitaFormRequired = "";
	$requiredClass = "";
	/*
	if ($questionOptions[0]['required'] == true) {
		$vitaFormRequired = "form-required";
		$requiredClass = 'class="required"';
	}
	*/

	$selectInput = '
			<div class="form-radio row">
				<label for="'.$questionOptions[0]['lookupName'].'" class="col '.$vitaFormRequired.'">'.$questionOptions[0]['questionText'].'</label>
				<div class="col btn-group" data-toggle="buttons">';
	foreach	($questionOptions as $option)	{
		$selectInput .= '
					<label class="btn btn-outline-secondary" for="'.$option['possibleAnswerId'].'">
						<input type="radio" id="'.$option['possibleAnswerId'].'" value="'.$option['possibleAnswerId'].'" '.$requiredClass.' name="'.$questionOptions[0]['questionId'].'">'.$option['possibleAnswerText'].'
					</label>';
	}
	$selectInput .= '
				</div>
			</div>';
	echo $selectInput;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// form data is stored in $_POST;

		//TODO

		echo json_encode($_POST);

}
