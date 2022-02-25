<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/0103b45dcf.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <title>haruharu</title>
  <?php wp_head(); ?>
</head>
<body>
  <div class="container">
  <header class="header">
    <div class="header_logo">
      <h1>
        <a href="/">
          <img src="<?php echo get_template_directory_uri(); ?>/images/logo1.png" alt="">
        </a>
      </h1>
    </div>
    <div class="header_nav">
      <ul class="header_nav_list pc_only">
        <li class="header_nav_item"><a href="/about">About</a></li>
        <li class="header_nav_item"><a href="/contact">Contact</a></li>
      </ul>
      <div class="sp_only">
        <div id="hum_btn" class="hum_btn">
          <span id="hum_bar"></span>
          <span id="hum_bar"></span>
        </div>
        <div id="mask" class="mask"></div>
        <ul id="hum_list" class="hum_list">
          <li class="hum_item"><a href="/about">About</a></li>
          <li class="hum_item"><a href="/contact">Contact</a></li>
          <li class="hum_item"><a href="https://portfolio.haru-haru0.com/" target="_blank">ポートフォリオ</a></li>
            <li class="hum_item"><a href="https://twitter.com/haruharu_0622x" target="_blank">Twitter</a></li>
            <li class="hum_item"><a href="https://haruki0622.github.io/autools/" target="_blank">自動化ツールLP</a></li>
        </ul>
      </div>
    </div>
  </header>