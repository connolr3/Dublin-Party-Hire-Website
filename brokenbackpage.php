<?php

    // Relocate user from brokenitems2.php back to brokenitems.php to initiate a new broken items input 

    session_start();
    unset($_SESSION['event_id']);
    echo '<script language="javascript">	

    document.location.replace("brokenitems.php");

    </script>'; 

?>