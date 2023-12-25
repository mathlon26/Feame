<?php
show($_SESSION);
?>
<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
</style>
<?php
$config = new Config;
require_once "{$config->DB_PATH}/DB.php"; // Adjust the path to the DB class file

// Replace with your actual database credentials
$DB_NAME = "";
$DB_USER = "root";
$DB_PASSWORD = "";
$DB_HOST = "localhost";
$db_suffix = "admin"; // assuming class_table --> eg. spotify_admin, google_users, google_session => prefix_suffix

// Instantiate the DB class
$db = new DB($DB_NAME, $DB_USER, $DB_PASSWORD, $DB_HOST);

// Fetch all tables
$tables = $db->Fetch->all('information_schema.tables');
foreach ($tables as $key => $value) {
    if (strpos($value["TABLE_SCHEMA"], "{$config->DB_PREFIX}{$db_suffix}") !== 0) {
        unset($tables[$key]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-top: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        h2 {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="logout_submit" method="post">
            <button type="submit">Logout</button>
        </form>

        <?php foreach ($tables as $table): ?>
            <?php
            $tableName = $table['TABLE_NAME'];
            $columns = $db->Fetch->columns($tableName);
            $data = $db->Fetch->all($tableName);
            ?>
            <h2><?= $tableName ?></h2>
            <table>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?= $column['Field'] ?></th>
                    <?php endforeach; ?>
                    <th>Action</th>
                </tr>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?= $value ?></td>
                        <?php endforeach; ?>
                        <td>Edit</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    </div>
</body>

</html>
