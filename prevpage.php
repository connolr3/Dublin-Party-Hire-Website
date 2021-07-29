
<!-- 
    Source :
        verifybrokenitems.php

    TO DO
        Decrement count session variable to move to the previous page
-->
<?php

    session_start();
    $count = $_SESSION['count'] - 1;
    $_SESSION['count'] = $count;
    echo '<script language="javascript">	

    document.location.replace("verifybrokenitems.php");

    </script>'; 

?>