<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
  header("Location: /unauthorized");
  die();
}

require_once 'config.php';



storeProfile($_POST);

function storeProfile($data) {
  GLOBAL $DB_CONN;
  $response = array();
  $response['success'] = false;

  $DB_CONN->beginTransaction();

  try {
    $userUpdate = "UPDATE User
      SET  firstName = ?,lastName = ?,phoneNumber = ?,email = ?,preparesTaxes = ?
       WHERE userId = ? ;";
    $userParams = array(
      $data('userId'),
      $data['firstName'],
      $data['lastName'],
      $data['emailNumber'],
      $data['phoneNumber']
      $data['preparesTaxes']
    );
    $stmt = $DB_CONN->prepare($userUpdate)
    $stmt->execute($userParams);

    $userabilityId = $DB_CONN->lastInsertId();


    $abilityUpdate = "UPDATE UserAbility
    SET  user.abilityId = ?
    WHERE abilityId IN (SELECT "spanish_speaking" FROM Ability );"

  $userabilityParams = array(
    $data['abilityId'],
  );

  $stmt = $DB_CONN->prepare($abilityInsert)
  $stmt->execute($userabilityParams);


  $shiftUpdate= "UPDATE UserShift
  SET UserShiftId = ?
  WHERE shiftId = ?;";

$shiftParams = array(
  $data['shiftId'],
  $data('UserShiftId'),
);
$stmt = DB_CONN->prepare($shiftUpdate)
$stmt->execute($shiftParams);

  }

 catch (Exception $e) {
  $DB_CONN->rollback();

  // TODO
  // mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
}
}
