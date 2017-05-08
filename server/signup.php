<?php

$values = [];
foreach ($_REQUEST as $key => $value) {
  $values += [htmlspecialchars($key) => htmlspecialchars($value)];
  // echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
}
echo json_encode($values);
// $firstName = $_REQUEST['firstName'];
// $lastName = $_REQUEST['lastName'];
// $email = $_REQUEST['email'];
// $phone = $_REQUEST['phone'];
// $english = $_REQUEST['english'];
// $language = $_REQUEST['language'];
// $pharmacist = $_REQUEST['pharmacist'];
// $gamble = $_REQUEST['gamble'];
// $military = $_REQUEST['military'];
