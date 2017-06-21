<?php

function getLitmusQuestions() {
  require 'config.php';
	$conn = $DB_CONN;

	$questionStatement = $conn->prepare('SELECT litmusQuestionId, string, orderIndex, tag, required FROM LitmusQuestion
        WHERE archived = FALSE
      	ORDER BY orderIndex');
  $questionStatement->execute();
  $questions = $questionStatement->fetchAll();

  $optionStatement = $conn->prepare('SELECT possibleAnswerId, string, orderIndex from PossibleAnswer
        WHERE litmusQuestionId = ?
        AND archived = FALSE
        ORDER BY orderIndex');

  foreach ($questions as $question) {
    $optionStatement->execute(array($question['litmusQuestionId']));
    $options = $optionStatement->fetchAll();
    requiredSelection($question, $options);
  }
}

function requiredSelection($question, $options) {
	$selectInput = '
      <div class="vita-form-select">
        <label for="'.$question['tag'].'" class="vita-form-label vita-form-required">'.$question['string'].'</label>
        <div>
          <select id="'.$question['tag'].'" class="required" name="'.$question['litmusQuestionId'].'">';
	foreach	($options as $option)	{
		$selectInput .= '
            <option value="'.$option['possibleAnswerId'].'">'.$option['string'].'</option>';
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
