<?php
    require_once('../templates/config.php');

    if ($dbport == null) {
        $dbport = 3306;
    }

    $prelogin = false;
    if (array_key_exists('key',$_POST)) {
        if (base64_decode($_POST['key']) == $secretkey) {
            $prelogin = true;
        }
    }
    if(!$prelogin){
        echo 'FAIL';
    } else {
?>

<table>
    <thead><tr>
        <th>ID</th>
        <th>Submitted By</th>
        <th>Email</th>
        <th>Submission Time</th>
        <th>Status</th>
        <th>Options</th>
    </tr></thead>
    <tbody>
        <?php
            $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
            $sql = "SELECT * FROM mailtition";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                echo '<tr><td>' . $row['id'] . '</td><td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td><td><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a></td><td>' . $row['reg_date'] . '</td><td>';
                switch ($row['status']){
                    case 1:
                        echo 'Pending';
                        break;
                    case 2:
                        echo 'Ready';
                        break;
                    case 3:
                        echo 'Sent';
                        break;
                    case 4:
                        echo 'Failed';
                        break;
                    case 5:
                        echo 'Moderated Out';
                        break;
                    case 6:
                        echo 'Deleted';
                        break;
                    default:
                        echo 'Corrupt';
                }
                echo '</td><td><a title="View Letter" onclick="loadWindowedContent(\'preview\',\'id=' . $row['id'] . '\');">View Letter</a><a title="Delete" onclick="deleteLetter(' . $row['id'] . ');">Delete</a><a title="Send" onclick="sendLetter(' . $row['id'] . ');">Send</a></td></tr>';
            }
            
        ?>
    </tbody>
</table>
<script type="application/javascript">
    setBreadcrumb(1,'<a title="View Submissions" onclick="loadContent(\'submissions\',null);">View Submissions</a>');
    setBreadcrumb(2, '');
</script>

<?php
    }
?>