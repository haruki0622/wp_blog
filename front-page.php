  <?php get_header(); ?>
  <main>
    <section class="blog_purpose">
      <h2>自由に生きるノマドエンジニアのブログ</h2>
      <p>プログラミング、Web制作、ノマド、英語の情報を発信しています</p>
    </section>
    <section class="blog_contents">
      <ul>
      <?php if(have_posts()): ?>
          <?php while(have_posts()): the_post(); ?>
            <li>
              <a class="blog_content" href="<?php the_permalink(); ?>">
                <div class="blog_thumbnail">
                  <?php the_post_thumbnail('full'); ?>
                </div>
                <div class="blog_text">
                  <?php
                    $category = get_the_category();
                    $cat_id   = $category[0]->cat_ID;
                    $cat_name = $category[0]->cat_name;
                    $cat_slug = $category[0]->category_nicename;
                  ?>
                  <p class="blog_category"><?php echo $cat_name; ?></p>
                  <p class="blog_title"><?php the_title(); ?></p>
                  <p class="blog_description"><?php echo get_the_excerpt(); ?></p>
                  <!-- カテゴリー名を取得 -->
                </div>
              </a>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
      <?php endif; ?>
      </ul>
    </section>
  </main>
  <?php get_footer(); ?>