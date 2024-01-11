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
      $show_view = true;
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
  <div class="bg-slate-200 py-4 px-4 sm:px-12 flex h-full items-center justify-center flex-1 overflow-y-auto">
    <form action='/login' method='POST' autocomplete="off" class="w-full flex flex-col p-4 gap-4 rounded-xl bg-white rounded-lg shadow">
      <h1 class="text-2xl text-slate-600 font-black p-2 w-full">Kullanıcı Girişi</h1>
      <?php if(is_string($error)): ?>
        <span class="p-2 w-full font-black text-red-500 bg-slate-100 rounded"><?=$error?></span>
      <?php endif; ?>
      <div class="flex flex-col">
        <label class="text-xl px-2 font-medium text-slate-700" for="username">Kullanıcı</label>
        <input class="text-xl p-2 bg-slate-200 shadow border-2 rounded" name='username' type="text" placeholder="kullanıcı adınızı giriniz.">
      </div>
      <div class="flex flex-col">
        <label class="text-xl px-2 font-medium text-slate-700" for="password">Şifre</label>
        <input class="text-xl p-2 bg-slate-200 shadow border-2 rounded" name='password' type="password" placeholder="şifrenizi giriniz.">
      </div>
      <button class="flex mx-auto mr-0 p-2 bg-sky-600/75 hover:bg-sky-700/75 bg-gray-200 text-white font-bold rounded" type="submit">Giriş yap</button>
    </form>
  </div>
  <div class="hidden lg:flex flex-1">
  </div>
</div>
<?php endif;?>
