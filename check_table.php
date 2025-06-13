<?php
require_once 'model/querymanager.php';

try {
    $query = "SHOW TABLES LIKE 'recharge'";
    $result = QueryManager::getonerow($query);
    
    if ($result) {
        echo "Table exists\n";
        
        // Check table structure
        $query = "DESCRIBE recharge";
        $result = QueryManager::getMultipleRow($query);
        
        echo "Table structure:\n";
        while ($row = mysqli_fetch_assoc($result)) {
            print_r($row);
        }
    } else {
        echo "Table does not exist";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 