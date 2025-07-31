<?php
require_once 'model/querymanager.php';

echo "Testing QueryManager methods...\n";

try {
    // Test getonerow method
    echo "Testing getonerow method...\n";
    $result = QueryManager::getonerow("SELECT 1 as test");
    if ($result) {
        echo "✓ getonerow method works\n";
    } else {
        echo "✗ getonerow method failed\n";
    }
    
    // Test getSingleRow method
    echo "Testing getSingleRow method...\n";
    $result = QueryManager::getSingleRow("SELECT 1 as test");
    if ($result) {
        echo "✓ getSingleRow method works\n";
    } else {
        echo "✗ getSingleRow method failed\n";
    }
    
    // Test executeQuerySqli method
    echo "Testing executeQuerySqli method...\n";
    $result = QueryManager::executeQuerySqli("SELECT 1 as test");
    if ($result !== false) {
        echo "✓ executeQuerySqli method works\n";
    } else {
        echo "✗ executeQuerySqli method failed\n";
    }
    
    // Test executeQuery method
    echo "Testing executeQuery method...\n";
    $result = QueryManager::executeQuery("SELECT 1 as test");
    if ($result !== false) {
        echo "✓ executeQuery method works\n";
    } else {
        echo "✗ executeQuery method failed\n";
    }
    
    // Test getMultipleRow method
    echo "Testing getMultipleRow method...\n";
    $result = QueryManager::getMultipleRow("SELECT 1 as test");
    if ($result) {
        echo "✓ getMultipleRow method works\n";
    } else {
        echo "✗ getMultipleRow method failed\n";
    }
    
    echo "\nAll tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "Critical Error: " . $e->getMessage() . "\n";
}
?> 