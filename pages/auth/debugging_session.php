<?php
session_start();
echo "<h1>Session Debugger</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "<br><a href='../student/student-dashboard.php'>Go to Student Dashboard</a>";
?>