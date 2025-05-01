<?php
session_start();

// Debug
error_log("Requête reçue pour marquer comme lu: " . print_r($_GET, true));

if (isset($_GET['index'])) {
    $index = (int)$_GET['index'];
    $driverId = 1;
    
    if (isset($_SESSION['driver_notifications'][$driverId][$index])) {
        $_SESSION['driver_notifications'][$driverId][$index]['read'] = true;
        
        // Debug
        error_log("Notification marquée comme lue: index=$index");
        
        echo json_encode(['success' => true]);
        exit;
    }
}

// Debug
error_log("Échec du marquage comme lu");
echo json_encode(['success' => false]);