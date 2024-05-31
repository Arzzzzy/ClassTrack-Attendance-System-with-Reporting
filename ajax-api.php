<?php 
session_start();
require_once('classes/actions.class.php');

// Instantiate the Actions class
$actionClass = new Actions();
$action = $_GET['action'] ?? ""; // Get the action parameter from the URL
$response = []; // Initialize the response array

// Switch statement to handle different actions
switch($action){
    case 'save_class':
        // Call the save_class method and store the response
        $response = $actionClass->save_class();
        break;
    case 'delete_class':
        // Call the delete_class method and store the response
        $response = $actionClass->delete_class();
        break;
    case 'save_student':
        // Call the save_student method and store the response
        $response = $actionClass->save_student();
        break;
    case 'delete_student':
        // Call the delete_student method and store the response
        $response = $actionClass->delete_student();
        break;
    case 'save_attendance':
        // Call the save_attendance method and store the response
        $response = $actionClass->save_attendance();
        break;
    default:
        // Default response if the action is undefined
        $response = ["status" => "error", "msg" => "Undefined API Action!"];
        break;
}

echo json_encode($response);
?>
