<?php

    // Log out for all pages
    // Distroy all session variables

    session_start();
    $_SESSION = array();
    session_destroy();
    echo '<script language="javascript">	

    document.location.replace("index.php");

    </script>';
    
?>