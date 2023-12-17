<?php
  // You can simulate a slow server with sleep
  // sleep(2);

  session_start();

  if(!isset($_SESSION['favorites'])) { $_SESSION['favorites'] = []; }

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

  if(!is_ajax_request()) { exit; }

  // extract $id
  $raw_id = isset($_POST['id']) ? $_POST['id'] :'';

  if(preg_match("/blog-post-(\d+)/", $raw_id, $matches)) {
    $id = $matches[1];

    // store in $_SESSION['favorites']
    if(!in_array($id, $_SESSION["favorites"])) {
      $_SESSION["favorites"][] = $id;
    }

    // return true/false
    echo 'true';
  } else {
    echo 'false';
  }


  

?>
