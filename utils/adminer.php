<?php
require_once('../lib/setup.php');
$cfg = setup();
// Check if some DB is not available.
$disabledmysql = empty($cfg->mysqlserver) ? 'disabled' : '';
$disabledpg = empty($cfg->pgserver) ? 'disabled' : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Adminer Quick Form</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 50px;
        }
        form {
            margin: 0;
            padding: 0;
            display: inline;
        }
        .connection {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: fit-content;
        }
        input[type='submit'] {
            font-size: 20px;
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type='submit']:hover {
            background-color: #45a049;
        }
        input[type='submit']:disabled,
        input[type='submit']:disabled:hover {
            background-color: #ccc;
            cursor: not-allowed;
        }
        h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Adminer database selector</h1>
    <div class="connection">
        <form action='adminer-4.8.1.php' method='post'>
            <input type='hidden' name='auth[driver]' value='server'>
            <input type='hidden' name='auth[server]' value='<?php echo $cfg->mysqlserver; ?>'>
            <input type='hidden' name='auth[username]' value='<?php echo $cfg->mysqluser; ?>'>
            <input type='hidden' name='auth[password]' value='<?php echo $cfg->mysqlpassword; ?>'>
            <input type='hidden' name='auth[db]' value=''>
            <input type='hidden' name='auth[permanent]' value='0'>
            <input type='hidden' name='auth[permanent]' value='1'>
            <input type='submit' value='Connect MySQL' <?php echo $disabledmysql; ?>>
        </form>
        <form action='adminer-4.8.1.php' method='post'>
            <input type='hidden' name='auth[driver]' value='pgsql'>
            <input type='hidden' name='auth[server]' value='<?php echo $cfg->pgserver; ?>'>
            <input type='hidden' name='auth[username]' value='<?php echo $cfg->pguser; ?>'>
            <input type='hidden' name='auth[password]' value='<?php echo $cfg->pgpassword; ?>'>
            <input type='hidden' name='auth[db]' value=''>
            <input type='hidden' name='auth[permanent]' value='0'>
            <input type='hidden' name='auth[permanent]' value='1'>
            <input type='submit' value='Connect PostgreSQL' <?php echo $disabledpg; ?>>
        </form>
    </div>
</body>
</html>
