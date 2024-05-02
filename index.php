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

    //Add the variables
    $name = "";
    $checkboxes = "";

    //If the page has been POSTed
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }
        if (isset($_POST['checks'])) {
            $checkboxes = $_POST['checks'];
        }

        //Add the data to the session array
        $f3->set('SESSION.name', $name);
        $f3->set('SESSION.checks', $checkboxes);

        // Reroute to summary page
        $f3->reroute('summary');
    }

    // Get the data from the model
    // add it to the F3 hive
    $checks = GetChecks();
    $f3->set('checks', $checks);

    // Render a view page
    $view = new Template();
    echo $view->render('views/survey.html');
});




// Run Fat-Free
$f3->run();