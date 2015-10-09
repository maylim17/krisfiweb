<?php

require_once __DIR__ . '/db_config.php';

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

/* change character set to utf8 */
if (!$mysqli->set_charset("utf8")) {
  echo "Error loading character set utf8: ".$mysqli->error;
}

?>