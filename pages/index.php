<?php

if(!array_key_exists('auth', $_SESSION)) {
  header('Location: '.url("login"));
  exit;
}

$show_view = false;

switch($_SERVER['REQUEST_METHOD']) {
  case "POST":
    break;
  case "GET":
    $show_view = true;

    $id = get_user_id($_SESSION["auth"]);

    if($id) {
      $json = create_get_request("", "/users/".$id."/rss");
      $rss_array = $json->rss;
    }

    $query = $_SERVER['QUERY_STRING'];

    $qs = array_map(function($e) {
      $qs = explode("=", $e);
      if ($qs[0] === "url") return $qs[1]; 
    }, explode("&", explode("?", $query)[0]))[0];
  
    $qs = urldecode($qs);

    if(is_string($qs)) {
      $rss_url = $qs;
      $json = create_post_request(["url" => $rss_url], "/rss");
      $rss_data = $json->items;
    }

    break;
}

?>

<?php if($show_view): ?>
<?php include("./components/nav.php"); ?>
<div class="flex flex-col lg:flex-row mx-auto max-w-[1080px]">
  <div class="flex flex-col gap-2 p-4">
    <?php if(is_array($rss_array)): ?>
      <?php foreach($rss_array as $rss_item): ?>
      <form method="GET">
        <input name="url" type="hidden" value="<?=$rss_item->rss_url?>" />
        <button class="flex gap-2 items-center bg-slate-300 hover:bg-slate-400 border-2 border-slate-400 text-gray-800 font-bold p-4 rounded w-full text-sm" type="submit">
          <img src="https://www.google.com/s2/favicons?domain=<?=$rss_item->rss_url?>&sz=128" class="w-8 h-8" />
          <div class="truncate text-ellipsis">
            <?=$rss_item->rss_url?>
          </div>
        </button>
      </form>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="container mx-auto">
    <?php if($rss_data): ?>
      <?php foreach($rss_data as $rss): ?>
        <div class="flex flex-col p-4">
          <div class="rss__content">
            <div class="text-xl"><?=$rss->title?></div>
            <div class="text-sm flex flex-col"><?=$rss->content?></div>
            <div class="text-sm flex flex-col"><?=$rss->description?></div>
            <a href="<?=$rss->link?>" target="_blank" class="text-sm flex flex-col hover:text-slate-400 overflow-clip"><?=$rss->link?></a>
          </div>
        </div>
      <?php endforeach;?>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
