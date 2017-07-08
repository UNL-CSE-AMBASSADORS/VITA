<?php

function getLitmusQuestions() {
  require 'config.php';
	$conn = $DB_CONN;

  $queryStatement = $conn->prepare('SELECT lq.litmusQuestionId, pa.possibleAnswerId, pa.text AS possibleAnswerText,
        lq.string AS litmusQuestionText, lq.required, lq.tag
        FROM PossibleAnswer pa
        JOIN LitmusQuestion lq ON pa.litmusQuestionId = lq.litmusQuestionId
        WHERE lq.archived = FALSE AND pa.archived = FALSE
        ORDER BY lq.orderIndex, pa.orderIndex');
  $queryStatement->execute();
  $resultSet = $queryStatement->fetchAll();

  $currentQuestionStartIndex = 0;
  for ($i = 0; $i < sizeof($resultSet); $i++) {
    if ($resultSet[$currentQuestionStartIndex]['litmusQuestionId'] != $resultSet[$i]['litmusQuestionId']) {
      addSelection(array_slice($resultSet, $currentQuestionStartIndex, $i - $currentQuestionStartIndex));
      $currentQuestionStartIndex = $i;
    }
  }
  addSelection(array_slice($resultSet, $currentQuestionStartIndex));

}

function addSelection($questionOptions) {
  $vitaFormRequired = "";
  $requiredClass = "";
  if ($questionOptions[0]['required'] == true) {
    $vitaFormRequired = "vita-form-required";
    $requiredClass = 'class="required"';
  }

  $selectInput = '
      <div class="vita-form-select">
        <label for="'.$questionOptions[0]['tag'].'" class="vita-form-label '.$vitaFormRequired.'">'.$questionOptions[0]['litmusQuestionText'].'</label>
        <div>
          <select id="'.$questionOptions[0]['tag'].'" '.$requiredClass.' name="'.$questionOptions[0]['litmusQuestionId'].'">';
	foreach	($questionOptions as $option)	{
  	$selectInput .= '
            <option value="'.$option['possibleAnswerId'].'">'.$option['possibleAnswerText'].'</option>';
	}
	$selectInput .= '
          </select>
          <div class="vita-form-select__arrow"></div>
        </div>
      </div>';
	echo $selectInput;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // form data is stored in $_POST;

    //TODO

    echo json_encode($_POST);

}
