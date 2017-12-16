
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
     GLOBAL $DB_CONN,$USER;
     $response = array();
     $response['success'] = false;

     $DB_CONN->beginTransaction();

     try {
          $userId = $USER->getUserId();

          $userUpdate = "UPDATE User
               SET User.firstName = ?,User.lastName = ?,User.phoneNumber = ?,User.email = ?
               WHERE User.userId = ? ;";
          $userParams = array(
               $data['firstName'],
               $data['lastName'],
               $data['phoneNumber'],
               $data['email'],
               $userId
);



          $stmt = $DB_CONN->prepare($userUpdate);
          $stmt->execute($userParams);
          $profileUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $userabilityId = $DB_CONN->lastInsertId();


          $abilityUpdate = "UPDATE UserAbility
               SET UserAbility.abilityId = (SELECT Ability.abilityId FROM Ability WHERE Ability.lookupName = ? )
               WHERE UserAbility.userId = ?;";

          $userabilityParams = array(
               $data['abilityLookupName'],
               $userId
          );

          $stmt = $DB_CONN->prepare($abilityUpdate);
          $stmt->execute($userabilityParams);
          $profileAbility = $stmt->fetchAll(PDO::FETCH_ASSOC);



          $shiftUpdate = "UPDATE UserShift
               SET UserShift.ShiftId = ?
               WHERE userId = ?;";

          $shiftParams = array(
               $data['UserShiftId'],
               $userId
          );
          $stmt = $DB_CONN->prepare($shiftUpdate);
          $stmt->execute($shiftParams);
          $profileShift = $stmt->fetchAll(PDO::FETCH_ASSOC);


          $profile = array(
                'success' => true,
                'userInformation' => $profileUser,
                'userAbilityInformation' => $profileAbility,
                'userShiftsInformation' => $profileShift
           );

           echo json_encode($profile);
     }

     catch (Exception $e) {
          $DB_CONN->rollback();

          // TODO
          // mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
     }
}
