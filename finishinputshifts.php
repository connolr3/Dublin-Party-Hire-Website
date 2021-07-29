<?php

session_start();

unset($_SESSION['van']);
unset($_SESSION['shift_id']);
unset($_SESSION['event_idInput']);

echo '<script language="javascript">	

document.location.replace("assigndriver.php");

</script>';

?>