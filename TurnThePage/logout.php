//La pagina di logout serve a terminare la sessione dell'utente e reindirizzarlo alla pagina di login
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>