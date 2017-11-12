<?php

require_once 'config.php';

storeProfile(_$POST);

function storeProfile($data) {
  GLOBAL $DB_CONN;
  $response = array();
  $response['success'] = false;

  $DB_CONN -> beginTransaction();

  try {
    $clientInsert = "INSERT INTO vita.user
    (
      firstName,
      lastName,
      phone,
      email
    )
    VALUES
    (
      ?,
      ?,
      ?,
      ?
    );";
    $userparams = array(
      $data['firstName'],
      $data['lastName'],
      $data['email'],
      $data['phone']
    );
    $stmt = $DB_CONN -> prepare($userInsert)
    $stmt -> execute($userParams);

    $userabilityID = $DB_CONN -> lastInsterId();

    $abilityInsert = "INSERT INTO vita.userability
    (
    languageSkills,
    taxSkills,
    taxSkillsType
  )
  VALUES
  (
    ?,
    ?,
    ?
  );";

  $userabilityParams = array(
    $data['languageSkills'],
    $data['taxSkills'],
    $data['taxSkillsType']
  );

  $stmt = $DB_CONN -> prepare($abilityInsert)
  $stmt -> execute($userabilityParams);

  $appointmentID = $DB_CONN -> lastInsterId();

  $appointmentInsert = "INSERT INTO vita.servicedappointment
  (
    shiftsAvailable,
    shiftsWorking,
    siteId
  )
  VALUES
  ?,
  ?,
  ?
);";
$appointmentParams = array(
  $data['shiftsAvailable'],
  $data['shiftsWorking'],
  $data['taxSkillsType']
);
$stmt = DB_CONN -> prepare($appointmentInsert)
$stmt -> execute($appointmentParams);

  }
getProfile(_$GET);
function getProfile($data) {
  GLOBAL $DB_CONN
  $response = array();
  $response['success'] = false;

  $DB_CONN -> beginTransaction();
  try {
    "SELECT FROM vita.user
    (  firstName,
      lastName,
      phone,
      email
    )
    VALUES
    (
      ?,
      ?,
      ?,
      ?
    );";

  );

  }
}

}
