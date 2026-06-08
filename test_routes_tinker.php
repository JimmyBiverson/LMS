<?php
$routeNames = ['dashboard', 'instructor.dashboard', 'admin.dashboard', 'org.dashboard'];
foreach ($routeNames as $name) {
    try {
        echo $name . ': ' . route($name) . PHP_EOL;
    } catch (Exception $e) {
        echo $name . ': ERROR - ' . $e->getMessage() . PHP_EOL;
    }
}
