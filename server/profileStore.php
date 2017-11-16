<?php

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
      $data['firstName'],
      $data['lastName'],
      $data['emailNumber'],
      $data['phoneNumber']
      $data['preparesTaxes']
    );
    $stmt = $DB_CONN->prepare($userUpdate)
    $stmt->execute($userParams);

    $userabilityId = $DB_CONN->lastInsertId();

//Not exactly sure how to implement language information.. this is what I could understand.
    $abilityUpdate = "UPDATE UserAbility
    SET  abilityId = ?
    WHERE userabilityId = ?;";

  $userabilityParams = array(
    $data['abilityId'],
  );

  $stmt = $DB_CONN->prepare($abilityInsert)
  $stmt->execute($userabilityParams);

  $appointmentId = $DB_CONN->lastInsertId();

  $shiftUpdate= "UPDATE UserShift
  SET shiftId = ?
  WHERE UserShiftID = ?;";

$appointmentParams = $data['shiftId'];
$stmt = DB_CONN->prepare($shiftUpdate)
$stmt->execute($appointmentParams);

  }

 catch (Exception $e) {
  $DB_CONN->rollback();

  // TODO
  // mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
}
}
