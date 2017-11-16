<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->hasPermission('can_use_admin_tools')) {
  header("Location: /unauthorized");
  die();
}
require_once 'config.php';
getProfile($USER->getUserId());
function getProfile($data) {
  GLOBAL $DB_CONN:
  $userGet = "SELECT firstName, lastName, phoneNumber, email, preparesTaxes
  FROM User
  WHERE userId = ? ";
  $stmt = $DB_CONN->prepare($userGet);
  $stmt->execute(array($data));
  $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($response);

$userabilityId = $DB_CONN->lastInsertId();
  $userAbilityGet = "SELECT abilityId, abilityId
  FROM UserAbility
  WHERE abilityId = ?";
  $stmt = $DB_CONN->prepare($userAbilityGet);
  $stmt->execute(array($data));
  $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($response);
//TODO shift get information, but I won't implement until I finish the front end.
}
