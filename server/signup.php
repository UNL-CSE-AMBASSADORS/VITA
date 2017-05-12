<?php

$values = [];
foreach ($_REQUEST as $key => $value) {
  $values += [htmlspecialchars($key) => htmlspecialchars($value)];

  // Add this content to the database

  // Trigger appointment notification system
  
}
echo json_encode($values);
