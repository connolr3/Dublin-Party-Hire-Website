
<!-- 
    Source :
        assigndriver_equipmentlist.php

    TO DO
        Decrement count session variable to move to the previous page
-->
<?php

    session_start();
    $count = $_SESSION['count'] - 1;
    $_SESSION['count'] = $count;
    echo '<script language="javascript">	

    document.location.replace("assigndriver_equipmentlist.php");

    </script>'; 

?>