<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            assigndriver.php
        TODO:
            present all shifts assigned on a given day
            allow admin to delete certain shifts
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Shifts</title>
</head>
<body>
    <?php

        session_start();
        include("detail.php");
        $date = $_SESSION['date'];

        // Confirm message for successful deletion
        if (isset($_SESSION['confirm'])) {
            echo '<span style="color:green">'.$_SESSION['confirm'].'</span>';
            unset($_SESSION['confirm']);
        }

        // Present table of shifts and deletion form, if shifts exist
        $q = "SELECT * FROM staff_shifts INNER JOIN staff ON staff_id = employee_id WHERE shift_date = '$date'";
        $run = $db->query($q);

        $nrow = mysqli_num_rows($run);

        if ($nrow > 0) {

            echo '<table>';
            echo '<tr>
                <th>Shift ID</th>
                <th>Employee Name</th>
                <th>Set Clock In</th>
                <th>Set Clock Out</th>
            <tr>';

            for ($i = 0; $i < $nrow; $i++) {
                $row = mysqli_fetch_assoc($run);

                echo '<tr>
                    <td>'.$row['shift_id'].'</td>
                    <td>'.$row['full_name'].'</td>
                    <td>'.$row['set_clock_in'].'</td>
                    <td>'.$row['set_clock_out'].'</td>
                </tr>';
            }

        }


        // reun query to reset mysqli_fetch_assoc
        $rerun = $db->query($q);

        // Form for deletion
        echo '<h4>Delete Shifts</h4>';
        echo '<form action="" method="POST">';
            echo 'Select Shift to Remove : ';
            echo "<select name='shift' id='shift'>";
            for ($i = 0; $i < $nrow; $i++) {
                $row = mysqli_fetch_assoc($rerun);
                echo '<option value='.$row['shift_id'].'>'.$row['full_name'].'. Shift ID: '.$row['shift_id'].'</option>';
            }
            echo "</select>";  
            echo '<br><input type="submit" value="Submit">';      
        echo '</form>';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST['shift'])) {
                $shift = $_POST['shift'];
                // Delete all records of chosen shift from MySQL
                $delete1 = "DELETE FROM staff_shifts WHERE shift_id = '$shift'";
                $delete2 = "DELETE FROM event_hours WHERE shift_id = '$shift'";
                $r1 = $db->query($delete1);
                $r2 = $db->query($delete2);
                // Session confirm
                $_SESSION['confirm'] = "Event ID ".$shift." has been deleted";
                echo '<script language="javascript">	

                document.location.replace("deleteshifts.php");
            
                </script>';
            }

        }
        echo '<br><br>';



    ?>
</body>
</html>