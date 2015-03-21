<!DOCTYPE html>
<html>
    <head>
        <title>Mailtition Setup</title>
    </head>
    <body>
        <h1>Mailtition Setup</h1>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Action</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('templates/config.php');

                if ($dbport == null){
                    $dbport = 3306;
                }
                
                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname, $dbport);
                if (!$conn) {
                    echo '<tr><td>Test</td><td>Database Connection</td><td>Failed</td></tr>';
                    end;
                }
                echo '<tr><td>Test</td><td>Database Connection</td><td>Passed</td></tr>';
                $sql = 'CREATE TABLE mailtition (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, reg_date TIMESTAMP, letter VARCHAR(6000), status INT(1))';
                if ($conn->query($sql) === true) {
                    echo '<tr><td>Test</td><td>Create Table</td><td>Created</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </body>
</html>