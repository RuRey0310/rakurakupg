﻿<ul>
  <li><a class="menu" href="rakurakupg.html">ホーム</a></li>
  <li><a class="menu" href="langc.html">C言語</a></li>
  <li><a class="menu" href="langpython.html">Python</a></li>
  <li>
    <a class="menu" href="#tutorial" onclick="introJs().start()">操作説明</a>
  </li>
  <li><a class="menu" href="board.php">掲示板</a></li>
  <li>
    <a class="menu right" href="Login.php">
      <?php
      $user_id="サインイン";
      echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');?>
    </a>
  </li>
</ul>
  <script>
    let activePage = location.pathname;
    const headerClass = document.querySelectorAll('.menu');
    activePage = activePage.split('/');
    for (let i = 0; i < headerClass.length; i++) {
      if (activePage[2] === headerClass[i].getAttribute('href')) {
        headerClass[i].classList.add('active');
      }
    }
  </script>
</div>
