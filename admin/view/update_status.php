<?php
// Database connection function (Replace with your actual connection code)
require("../../config.php");

// Function to update the order status in the database
function updateOrderStatus($status, $orderId) {
    $conn = connectdb();
    if ($conn) {
        try {
            // Update the order status in the 'tbl_order' table
            $sql = "UPDATE tbl_order SET trangthai = :status WHERE id = :orderId";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array(':status' => $status, ':orderId' => $orderId));
            return true; // Return true if the update was successful
        } catch(PDOException $e) {
            // Handle database errors
            echo "Update failed: " . $e->getMessage();
            return false; // Return false if the update failed
        }
    } else {
        // Handle connection errors
        return false;
    }
}

// Include any necessary files or configurations
// For example, database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data sent with the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract status and order ID from the JSON data
    $status = isset($data['status']) ? $data['status'] : null;
    $orderId = isset($data['orderId']) ? $data['orderId'] : null;

    if ($status !== null && $orderId !== null) {
        // Update the order status in the database
        $success = updateOrderStatus($status, $orderId);

        if ($success) {
            // Return a success response
            $response = array('success' => true, 'message' => 'Order status updated successfully');
            echo json_encode($response);
        } else {
            // Return an error response
            $response = array('success' => false, 'message' => 'Failed to update order status');
            echo json_encode($response);
        }
    } else {
        // Return an error response if status or orderId is missing
        $response = array('success' => false, 'message' => 'Status or orderId is missing');
        echo json_encode($response);
    }
}