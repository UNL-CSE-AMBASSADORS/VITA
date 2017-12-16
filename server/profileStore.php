
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
     echo json_encode($data);
     
     GLOBAL $DB_CONN;
     $response = array();
     $response['success'] = false;

     $DB_CONN->beginTransaction();

     try {

          $userUpdate = "UPDATE User
               SET User.firstName = ?,User.lastName = ?,User.phoneNumber = ?,User.email = ?,User.preparesTaxes = ?
               WHERE User.userId = ? ;";
               echo "test";
          $userParams = array(
               $data['firstName'],
               $data['lastName'],
               $data['phoneNumber'],
               $data['email']
);
echo "test";


          $stmt = $DB_CONN->prepare($userUpdate);
          $stmt->execute($userParams);
          $profileUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $userabilityId = $DB_CONN->lastInsertId();


          $abilityUpdate = "UPDATE UserAbility
               SET UserAbility.abilityId = (SELECT abilityId FROM Ability WHERE lookupName = ? )
               WHERE UserAbility.userId = ?;";

          $userabilityParams = array(
               $data['abilityLookupName'],
               $data['userId']
          );

          $stmt = $DB_CONN->prepare($abilityUpdate);
          $stmt->execute($userabilityParams);
          $profileAbility = $stmt->fetchAll(PDO::FETCH_ASSOC);

//500 issue is here, will work on fixing this later, after figure out the other issue

          // $shiftUpdate= "UPDATE UserShift
          //      SET UserShift.ShiftId = ?
          //      WHERE shiftId = ?;";

          // $shiftParams = array(
          //      $data['UserShiftId']
          // );
          // $stmt = DB_CONN->prepare($shiftUpdate);
          // $stmt->execute($shiftParams);
          // $profileShift = $stmt->fetchAll(PDO::FETCH_ASSOC);
          //
          //
          $profile = array(
                'success' => true,
                'profileUser' => $profileUser,
                'profileAbility' => $profileAbility
                // 'profileShift' => $profileShift
           );

           echo json_encode($profile);
     }

     catch (Exception $e) {
          $DB_CONN->rollback();

          // TODO
          // mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
     }
}
