<?php get_header(); ?>

<?php // ブログ記事を表示する start ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div class="single_container">
    <h1 class="blog_detail__title"><?php the_title(); ?></h1>
    <?php if( function_exists( 'aioseo_breadcrumbs' ) ) aioseo_breadcrumbs(); ?>
    <div class="blog__detail_info">
      <p class="blog_detail__date">投稿:<span><?php the_date(); ?></span></p>
      <p class="blog_detail__date">更新:<span><?php the_modified_date(); ?></span></p>
    </div>
    <ul class="snsshare">
      <li class="twitter"><a href="http://twitter.com/share?text=<?php echo urlencode(the_title_attribute('echo=0')); ?>&url=<?php the_permalink(); ?>" rel="nofollow" target="_blank"><i class="fab fa-twitter"></i></a></li>
      <li class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
      <li class="line"><a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode(get_permalink()); ?>" target="_blank"><i class="fab fa-line"></i></a></li>
    </ul>
    <?php // アイキャッチ画像を表示する start ?>
    <?php if(has_post_thumbnail()): ?>
    <div class="blog_detail__image">
        <img src="<?php the_post_thumbnail_url('full'); ?>">
    </div>
    <?php endif; ?>
    <?php // アイキャッチ画像を表示する end ?>
    <?php // 本文を表示する start ?>
    <div class="blog_detail__body">
        <div class="blog_body"><?php the_content(); ?></div>
    </div>
    <?php // 本文を表示する end ?>
    <?php endwhile; endif; ?>
    <?php // ブログ記事を表示する end ?>
  </div>
<?php get_footer(); ?>