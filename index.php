<?php

// 328/quiz/index.php

// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require autoload.php
require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');

// Instantiate the F3 base class
$f3 = Base::instance();

// Define a default route
$f3->route('GET /', function() {

    // Render a view page
    $view = new Template();
    echo $view->render('views/home.html');
});

// Define the survey route
$f3->route('GET|POST /survey', function($f3) {
    session_start();

    //Add the variables
    $name = "";
    $checkboxes = "";

    // Get the data from the model
    // add it to the F3 hive
    $checks = GetChecks();
    $f3->set('checks', $checks);

    //If the page has been POSTed
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST['user-name'])) {
            $name = $_POST['user-name'];
        }
        for ($i = 0; $i < count($checks); $i++) {
            $replaced = str_replace(" ", "_", $checks[$i]);
            if(isset($_POST["$replaced"])) {
                if ($checkboxes == "") {
                    $checkboxes = $checks[$i];
                } else {
                    $checkboxes = $checkboxes . ", " . $checks[$i];
                }
            }
        }

        //Add the data to the session array
        $f3->set('SESSION.userName', $name);
        $f3->set('SESSION.checkAllApplied', $checkboxes);

        // Reroute to summary page
        $f3->reroute('summary');
    }


    // Render a view page
    $view = new Template();
    echo $view->render('views/survey.html');
});

// Define the summary route
$f3->route('GET|POST /summary', function($f3) {
    session_start();

    // Render a view page
    $view = new Template();
    echo $view->render('views/summary.html');
});

// Run Fat-Free
$f3->run();