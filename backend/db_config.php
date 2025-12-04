<?php

if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    define ('DB_HOST', 'localhost');
    define ('DB_USER', 'root');
    define ('DB_PASS', '');
    define ('DB_NAME', 'school_portal_db');

} else {
    define ('DB_HOST', 'sql123.infinityfree.com');
    define ('DB_USER', 'if0_40551362');
    define ('DB_PASS', 'Ikinamada56789');
    define ('DB_NAME', 'if0_40551362_school_portal_db');
}

?>