<nav class="grid justify-center sm:flex sm:justify-between items-center py-2">
  <ul class="flex gap-2 mx-auto sm:ml-0">
      <a href="/">
        <li class="p-2 rounded border-2 border-transparent text-slate-500 hover:text-slate-400 cursor-pointer font-bold text-2xl">RSS</li>
      </a>
  </ul> 
  <ul class="flex gap-2">
    <?php if(array_key_exists('auth', $_SESSION)): ?>
      <?php if(array_key_exists("username", $_SESSION)): ?>
        <a href="#">
          <li class="p-2 rounded text-slate-500 border-2 border-transparent cursor-default font-bold"><?=$_SESSION["username"]?></li>
        </a>
      <?php endif; ?>
      <a href="/account">
        <li class="p-2 rounded text-slate-500 hover:bg-slate-200 border-2 border-slate-400 cursor-pointer font-bold">Kullanıcı paneli</li>
      </a>
      <a href="/logout">
        <li class="p-2 rounded text-slate-500 hover:text-rose-400 border-2 border-transparent cursor-pointer font-bold">Çıkış Yap</li>
      </a>
    <?php else: ?>
      <a href="/login">
        <li class="p-2 rounded bg-green-400 hover:bg-green-500 border-2 border-transparent text-white cursor-pointer font-bold">Giriş Yap</li>
      </a>
    <?php endif; ?>
  </ul> 
</nav>
