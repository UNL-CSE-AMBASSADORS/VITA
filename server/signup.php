<?php

function getLitmusQuestions() {
	require 'config.php';
	$conn = $DB_CONN;

	$queryStatement = $conn->prepare('SELECT lq.litmusQuestionId, pa.possibleAnswerId, pa.text AS possibleAnswerText,
				lq.text AS litmusQuestionText, lq.required, lq.lookupName
				FROM PossibleAnswer pa
				JOIN LitmusQuestion lq ON pa.litmusQuestionId = lq.litmusQuestionId
				WHERE lq.archived = FALSE AND pa.archived = FALSE
				ORDER BY lq.orderIndex, pa.orderIndex');
	$queryStatement->execute();
	$resultSet = $queryStatement->fetchAll();

	$currentQuestionStartIndex = 0;
	for ($i = 0; $i < sizeof($resultSet); $i++) {
		if ($resultSet[$currentQuestionStartIndex]['litmusQuestionId'] != $resultSet[$i]['litmusQuestionId']) {
			addRadioSelection(array_slice($resultSet, $currentQuestionStartIndex, $i - $currentQuestionStartIndex));
			$currentQuestionStartIndex = $i;
		}
	}
	addRadioSelection(array_slice($resultSet, $currentQuestionStartIndex));

}

function addSelection($questionOptions) {
	$vitaFormRequired = "";
	$requiredClass = "";
	if ($questionOptions[0]['required'] == true) {
		$vitaFormRequired = "form-required";
		$requiredClass = 'class="required"';
	}

	$selectInput = '
			<div class="form-select">
				<label for="'.$questionOptions[0]['lookupName'].'" class="form-label '.$vitaFormRequired.'">'.$questionOptions[0]['litmusQuestionText'].'</label>
				<div>
					<select id="'.$questionOptions[0]['lookupName'].'" '.$requiredClass.' name="'.$questionOptions[0]['litmusQuestionId'].'">';
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
	if ($questionOptions[0]['required'] == true) {
		$vitaFormRequired = "form-required";
		$requiredClass = 'class="required"';
	}

	$selectInput = '
			<div class="form-radio">
				<label for="'.$questionOptions[0]['lookupName'].'" class="form-label form-label__floating '.$vitaFormRequired.'">'.$questionOptions[0]['litmusQuestionText'].'</label>
				<div>';
	foreach	($questionOptions as $option)	{
		$selectInput .= '
							<label class="form-radio-label" for="'.$option['possibleAnswerId'].'">'.$option['possibleAnswerText'].'</label>
							<input type="radio" id="'.$option['possibleAnswerId'].'" value="'.$option['possibleAnswerId'].'" '.$requiredClass.' name="'.$questionOptions[0]['litmusQuestionId'].'">
					';
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
