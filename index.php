<?php
session_start();

include('lib/helper.php');
include("routes.php");

$current_url = explode("?", $_SERVER['REQUEST_URI'])[0];
?>

<?php if(array_key_exists($current_url, $routes["pages"])): ?>
  <html>
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="./static/style.css">
    </head>
    <body class="bg-slate-300">
      <script src="https://cdn.tailwindcss.com"></script>
      <?php include($routes["pages"][$current_url]); ?>
    </body>
  </html>
<?php endif; ?>
