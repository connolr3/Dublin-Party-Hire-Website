
<!-- 
    Source :
        assigndriver.php

    TO DO
        Decrement count session variable to move to the previous page
-->
<?php

    session_start();
    $date = $_SESSION['date'];
    $date = date('Y-m-d', strtotime($date . " -1 days"));
    $_SESSION['date'] = $date;

    echo '<script language="javascript">	

    document.location.replace("assigndriver.php");

    </script>'; 

?>