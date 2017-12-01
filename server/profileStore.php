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
      SET  User.firstName = ?,User.lastName = ?,User.phoneNumber = ?,User.email = ?,User.preparesTaxes = ?
       WHERE User.userId = ? ;"
    $userParams = array(
      $data('userId'),
      $data['firstName'],
      $data['lastName'],
      $data['email'],
      $data['phoneNumber']
      $data['preparesTaxes']
    );
    $stmt = $DB_CONN->prepare($userUpdate);
    $stmt->execute($userParams);
    $profileUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userabilityId = $DB_CONN->lastInsertId();


    $abilityUpdate = "UPDATE UserAbility
    SET  user.abilityId = ?
    WHERE abilityId IN (SELECT "spanish_speaking" FROM Ability );"

  $userabilityParams = array(
    $data['abilityId'],
  );

  $stmt = $DB_CONN->prepare($abilityInsert);
  $stmt->execute($userabilityParams);
  $profileAbility = $stmt->fetchAll(PDO::FETCH_ASSOC);


  $shiftUpdate= "UPDATE UserShift
  SET User.ShiftId = ?
  WHERE shiftId = ?;"

$shiftParams = $data('UserShiftId');
$stmt = DB_CONN->prepare($shiftUpdate);
$stmt->execute($shiftParams);
$profileShift = $stmt->fetchAll(PDO::FETCH_ASSOC);


$profile = array(
     'success' => true,
     'profileUser' => $profileUser,
     'profileAbility' => $profileAbility,
     'profileShift' => $profileShift
);

echo json_encode($profile);
  }

 catch (Exception $e) {
  $DB_CONN->rollback();

  // TODO
  // mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
}
}
