<?php

if(!array_key_exists('auth', $_SESSION)) {
  header('Location: '.url("login"));
  exit;
}

$show_view = false;

switch($_SERVER['REQUEST_METHOD']) {
  case "POST":
    if(array_key_exists("rss_create", $_POST)) {
      $id = get_user_id($_SESSION["auth"]);

      $rss_url = $_POST["rss_url"];

      if($id) {
        $json = create_post_request(["rss_url" => $rss_url], "/users/".$id."/rss");
        if($json->new_rss) {
          $notification_msg = "RSS linki başarı ile eklendi";
          $rss_array = $_SESSION["user_rss"];
          array_push($rss_array, $json->new_rss);
          $_SESSION["user_rss"] = $rss_array;
          $users_data = $_SESSION["users_data"];
          $show_view = true;
        }
      }
    }
    if(array_key_exists("rss_delete", $_POST)) {
      $id = get_user_id($_SESSION["auth"]);

      $rss_url = $_POST["rss_url"];

      if($id) {
        $json = create_post_request(["rss_url" => $rss_url], "/users/".$id."/rss/delete");
        if($json->msg) {
          $notification_msg = "RSS linki başarı ile silindi";
          $rss_array = $_SESSION["user_rss"];
          $selected = current(array_filter($rss_array, function($e) {
            return $e->rss_url === $_POST["rss_url"];
          }));
          $key = array_search($selected, $rss_array);
          if ($key !== false) unset($rss_array[$key]);
          $_SESSION["user_rss"] = $rss_array;
          $users_data = $_SESSION["users_data"];
          $show_view = true;
        }
        if($json->error) {
          $error_msg = "Silinecek RSS linki bulunamadı.";
        }
      }
    }
    if(array_key_exists("create_user", $_POST)) {
      $id = get_user_id($_SESSION["auth"]);

      if($id){
        $json = create_post_request([
            "username" => $_POST["user_name"],
            "password" => $_POST["user_password"]
          ], "/users");
        if($json->id) {
          $notification_msg = "Kullanıcı başarı ile oluşturuldu.";
          $rss_array = $_SESSION["user_rss"];
          $users_data = $_SESSION["users_data"];
          array_push($users_data, $json);
          $_SESSION["users_data"] = $users_data;
          $show_view = true;
        }
        if($json->error) {
          $error_msg = $json->error;
        }
      }
    }
    if(array_key_exists("delete_user", $_POST)) {
      $id = get_user_id($_SESSION["auth"]);

      if($id) {
        $json = create_get_request("", "/users/".$id);
          if($json->user && $_POST["user_name"] === $json->user->username) {
              $error_msg = "kendi hesabını silemezsin.";
          } else {
            $json = create_post_request(["username" => $_POST["user_name"]], "/users/delete");
            if($json->msg) {
              $notification_msg = "kullanıcı silindi.";
              $rss_array = $_SESSION["user_rss"];
              $users_data = $_SESSION["users_data"];
              $selected = current(array_filter($users_data, function($e) {
                return $e->username === $_POST["user_name"];
              }));
              $key = array_search($selected, $users_data);
              if($key !== false) unset($users_data[$key]);
              $_SESSION["users_data"] = $users_data;
              $show_view = true;
          }
          if($json->error) {
            $error_msg = "kullanıcı silinemedi.";
          }
        }
      }
    }
    break;
  case "GET":
    $id = get_user_id($_SESSION["auth"]);
    if($id) {
      $json = create_get_request("", "/users/".$id."/rss");
      $rss_array = $json->rss;
      $_SESSION["user_rss"] = $rss_array;
    }

    $json = create_get_request("", "/users");
    $users_data = $json->users;

    if($users_data) {
      $_SESSION["users_data"] = $users_data;
    }

    $show_view = true;
    break;
}

?>

<?php if($error_msg): ?>
  <div class="bg-rose-200 text-slate-700 font-bold p-4 rounded m-4"><?=$error_msg?></div>
  <a class="bg-slate-100 text-slate-700 font-bold p-4 rounded m-4" href="/account">geri dön.</a>
<?php endif; ?>

<?php if($show_view): ?>
<?php include("./components/nav.php"); ?>

<?php if($notification_msg): ?>
  <div class="bg-green-200 text-slate-700 font-bold p-4 rounded m-4"><?=$notification_msg?></div>
<?php endif; ?>

<?php if(is_array($rss_array)): ?>
  <div class="flex flex-col container mx-auto">
    <div class="flex justify-between mx-2 p-4 bg-slate-200 rounded">
      <div class="font-bold text-slate-700">ID</div>
      <div class="font-bold text-slate-700">URL</div>
      <div></div>
    </div>
    <?php foreach($rss_array as $rss): ?>
        <div class="grid sm:flex justify-between items-center m-2 p-4 bg-slate-100 hover:bg-slate-200/75 rounded">
            <div class="font-bold text-slate-600"><?=$rss->id?></div>
            <div class="font-bold text-slate-600 truncate text-ellipsis"><?=$rss->rss_url?></div>
            <form method="POST">
              <input type="hidden" name="rss_delete" />
              <input type="hidden" name="rss_url" value="<?=$rss->rss_url?>" />
              <button type="submit" class="bg-rose-300 hover:bg-rose-400 text-slate-700 font-bold py-2 px-8 rounded">Sil</button>
            </form>
        </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<details class="bg-slate-200 hover:bg-slate-300 cursor-pointer mx-auto my-2 container">
  <summary class="list-none font-bold text-slate-600 p-4 text-xl">+ RSS URL ekle</summary>
  <div class="flex w-full p-2">
    <form method="POST" class="w-full">
      <div class="flex flex-col w-full bg-slate-100 p-4 rounded gap-4">
      <div class="text-xl font-bold text-slate-700 px-2">RSS adresi</div>
        <input class="text-xl bg-slate-200 p-4 rounded" name="rss_url" type="text" placeholder="eklenecek rss adresini giriniz." />
        <input name="rss_create" type="hidden" />
        <button class="mx-auto mr-0 bg-green-200 hover:bg-green-300 text-slate-700 font-bold text-lg rounded py-2 px-8" type="submit">Ekle</button>
      </div>
    </form>
  </div>
</details>


<details class="bg-slate-200 hover:bg-slate-300 cursor-pointer mx-auto my-2 container">
  <summary class="list-none font-bold text-slate-600 p-4 text-xl">- RSS URL sil</summary>
  <div class="flex w-full p-2">
    <form method="POST" class="w-full">
      <div class="flex flex-col w-full bg-slate-100 p-4 rounded gap-4">
      <div class="text-xl font-bold text-slate-700 px-2">RSS adresi</div>
        <input class="text-xl bg-slate-200 p-4 rounded" name="rss_url" type="text" placeholder="silinecek rss adresini giriniz." />
        <input name="rss_delete" type="hidden" />
        <button class="mx-auto mr-0 bg-rose-200 hover:bg-rose-300 text-slate-700 font-bold text-lg rounded py-2 px-8" type="submit">Sil</button>
      </div>
    </form>
  </div>
</details>

<details class="bg-slate-200 hover:bg-slate-300 cursor-pointer mx-auto my-2 container">
  <summary class="list-none font-bold text-slate-600 p-4 text-xl">+ Kullanıcı oluştur</summary>
  <div class="flex w-full p-2">
    <form method="POST" class="w-full">
      <div class="flex flex-col w-full bg-slate-100 p-4 rounded gap-4">
        <div class="text-xl font-bold text-slate-700 px-2">Kullanıcı adı</div>
        <input class="text-xl bg-slate-200 p-4 rounded" name="user_name" type="text" placeholder="oluşturulacak kullanıcı adını giriniz." />
        <div class="text-xl font-bold text-slate-700 px-2">Şifre</div>
        <input class="text-xl bg-slate-200 p-4 rounded" name="user_password" type="password" placeholder="oluşturulacak şifreyi giriniz." />
        <input name="create_user" type="hidden" />
        <button class="mx-auto mr-0 bg-green-200 hover:bg-green-300 text-slate-700 font-bold text-lg rounded py-2 px-8" type="submit">Ekle</button>
      </div>
    </form>
  </div>
</details>


<details class="bg-slate-200 hover:bg-slate-300 cursor-pointer mx-auto my-2 container">
  <summary class="list-none font-bold text-slate-600 p-4 text-xl">- Kullanıcı sil</summary>
  <div class="flex w-full p-2">
    <form method="POST" class="w-full">
      <div class="flex flex-col w-full bg-slate-100 p-4 rounded gap-4">
        <div class="text-xl font-bold text-slate-700 px-2">Kullanıcı adı</div>
        <select class="text-xl bg-slate-200 p-4 rounded" name="user_name">
          <?php foreach($users_data as $user): ?>
            <option value="<?=$user->username?>"><?=$user->username?></option>
          <?php endforeach;?>
        </select>
        <input name="delete_user" type="hidden" />
        <button class="mx-auto mr-0 bg-rose-200 hover:bg-rose-300 text-slate-700 font-bold text-lg rounded py-2 px-8" type="submit">Sil</button>
      </div>
    </form>
  </div>
</details>

<?php endif; ?>
