<?php

if(array_key_exists('auth', $_SESSION)) {
  header('Location: '.url());
  exit;
}

$show_view = false;

switch($_SERVER['REQUEST_METHOD']) {
  case "POST":
    $payload = [
      "username" => $_POST['username'],
      "password" => $_POST['password']
    ];

    $json = create_post_request($payload, "/login");

    if($json->error_msg) {
      $error = 'Kullanıcı adı ya da Şifre yanlış!';
    }

    if($json->token) {
      $_SESSION['auth'] = $json->token;
      $_SESSION["username"] = $payload["username"]; 
      header('Location: '.url());
    }
    break;
  case "GET":
    $show_view = true;
    break;
}

?>

<?php if($show_view): ?>
<div class="flex w-full fixed bottom-0 h-full z-[1]">
  <div class="hidden lg:flex flex-1">
  </div>
  <div class="dark:bg-slate-800/25 bg-gray-200 py-4 px-4 sm:px-12 flex h-full items-center justify-center flex-1 overflow-y-auto">
    <form action='/login' method='POST' autocomplete="off" class="w-full flex flex-col space-y-8 p-4 rounded-xl dark:bg-slate-800 bg-white rounded-lg shadow">
      <h1 class="self-center dark:text-slate-200 text-xl font-black dark:bg-slate-800 p-2 w-full text-center">Kullanıcı Girişi</h1>
      <?php if(is_string($error)): ?>
        <span class="text-red-500"><?=$error?></span>
      <?php endif; ?>
      <div class="flex flex-col">
        <label class="ml-2 dark:text-gray-200 font-medium" for="username">Kullanıcı</label>
        <input class="p-2 dark:bg-slate-900/25 bg-gray-200 dark:text-slate-200 shadow outline-none border-l-[5px] dark:border-l-slate-900" name='username' type="text">
      </div>
      <div class="flex flex-col">
        <label class="ml-2 dark:text-gray-200 font-medium" for="password">Şifre</label>
        <input class="p-2 dark:bg-slate-900/25 bg-gray-200 dark:text-slate-200 shadow outline-none border-l-[5px] dark:border-l-slate-900" name='password' type="password">
      </div>
      <button class="p-2 dark:bg-blue-600 bg-sky-600/75 bg-gray-200 dark:text-gray-200 text-white" type="submit">Giriş yap</button>
    </form>
  </div>
  <div class="hidden lg:flex flex-1">
  </div>
</div>
<?php endif;?>
