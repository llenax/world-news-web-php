<?php
session_start();

include('lib/helper.php');

$routes = [
  "pages" => [
    '/' => 'pages/index.php',
    '/login' => 'pages/login.php',
    '/logout' => 'pages/logout.php',
  ],
  "requests" => [
    "GET" => [
    ],
    "POST" => [
    ],
  ]
];

$current_url = explode("?", $_SERVER['REQUEST_URI'])[0];

if(array_key_exists($current_url, $routes["requests"][$_SERVER['REQUEST_METHOD']])) {
  include($routes["requests"][$_SERVER['REQUEST_METHOD']][$current_url]);
  exit;
}

?>

<?php if(array_key_exists($current_url, $routes["pages"])): ?>
  <html>
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
      <script>
          tailwind.config = {
              darkMode: "class"
          }
      </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous" defer></script>
      <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js" defer></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="./static/style.css">
      <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/custom-forms@0.2.1/dist/custom-forms.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/index.min.css" />
    </head>
    <body class="bg-gray-300">
      <?php if(array_key_exists($current_url, $routes["pages"])): ?>
        <?php include($routes["pages"][$current_url]); ?>
      <?php else: ?>
        <?php include($routes["pages"]['/404']); ?>
      <?php endif; ?>
      <script src="static/js/jquery.session.js" ></script>
    </body>
  </html>
<?php endif; ?>
