<?php
// Simple logging function for debugging
function debug_log($message) {
    $log_file = STORAGE_PATH . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
?>
