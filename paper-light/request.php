<?php

require_once('inc/common.php');
require_once('inc/PapersLight.class.php');

session_start();
if (isset($_SESSION['pl'])) {
    $pl = unserialize($_SESSION['pl']);
} else {
    $pl = new PapersLight($TYPES, $DBHOST, $DBNAME, $DBUSER, $DBPASS);
}

if (isset($_GET['action'])) header('Content-Type: application/json');

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'init') {
        echo json_encode(array('username' => $pl->user));
    }

    else if ($_GET['action'] === 'gettypes') {
        echo json_encode($TYPES);
    }

    else if ($_GET['action'] === 'adminlogin' && isset($_POST['username']) && isset($_POST['password'])) {
        echo json_encode($pl->adminLogin($_POST['username'], $_POST['password']));
    }

    else if ($_GET['action'] === 'getpapers') {
        echo json_encode($pl->getPapers());
    }

    else if ($_GET['action'] === 'addpaper' && isset($_POST['type']) && isset($_POST['paper'])) {
        $paper = json_decode(stripslashes($_POST['paper']));
        echo json_encode($pl->addPaper($_POST['type'], $paper));
    }

    else if ($_GET['action'] === 'updatepaper' && isset($_POST['orig_type']) && isset($_POST['new_type']) && isset($_POST['paper_id']) && isset($_POST['paper'])) {
        $paper = json_decode(stripslashes($_POST['paper']));
        echo json_encode($pl->updatePaper($_POST['orig_type'], $_POST['new_type'], $_POST['paper_id'], $paper));
    }

    else if ($_GET['action'] === 'removepaper' && isset($_POST['type']) && isset($_POST['paper_id'])) {
        echo json_encode($pl->removePaper($_POST['type'], $_POST['paper_id']));
    }
}
