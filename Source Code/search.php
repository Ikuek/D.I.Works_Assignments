<?php

if (($_GET['search'])==='') {
    $err = "検索条件がありません。";
} else {
    $search = htmlspecialchars($_GET['search']);
    $sql = "SELECT * FROM member WHERE name or comment LIKE '%$search%'";
    $stmt = array();
    foreach ($dbh->query($sql) as $row) {
      array_push($stmt,$row);
    }
    $result = count($stmt);
  }

?>