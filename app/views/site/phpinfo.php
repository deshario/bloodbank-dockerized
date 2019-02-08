<html>
    <head>
        <title>PHP INFO</title>
    </head>
    <body>
        <?php echo 'Docker IP => '.$_SERVER['REMOTE_ADDR']; ?>
        <!-- Get IP from REMOTE_ADDR to add in allowded ip in gii -->
        <?php phpinfo(); ?>
    </body>
</html>
