<?php

function add_stylesheet() {
  wp_enqueue_style(
      'read_common_file', // mainという名前を設定
      get_template_directory_uri().'/css/common.css', // パス
      array(), // style.cssより先に読み込むCSSは無いので配列は空
  );
  wp_enqueue_script( 
    'main_script', 
    get_template_directory_uri() . '/js/prism.js',
  );
  wp_enqueue_script( 
    'script', 
    get_template_directory_uri() . '/js/main.js',
  );
}
add_action('wp_enqueue_scripts', 'add_stylesheet');
  // wp_enqueue_scriptsフックにadd_stylesheet関数を登録
add_theme_support('post-thumbnails');

// 内部リンクのショートコード作成
function linkpage_func ( $atts ) {
  extract( shortcode_atts( array(
      'id' => '', //投稿ID
      'slug' => '', //ページスラッグ
  ), $atts ) );
  $my_url = home_url( '/' );
  if($slug){ //スラッグを指定したときに投稿IDを取得する
      $id = url_to_postid($my_url. $slug);
  }
  $link = get_permalink($id);
  $title = get_the_title($id); //投稿IDで指定した投稿のレコードをデータベースから取得
  return '<div class="internal_link">
            <p>
              <span class="internal_text">参考</span>
              <a href="'. $link .'"' .'target="_blanck">'. $title. '</a>
            </p>
          </div>';
}
add_shortcode('pagelink', 'linkpage_func');

// 要約ショートコード
function list_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
      'title' => '',
	), $atts ) );

	return '<div class="list">
            <p>' . $title . '</p>
            <ul>
            </ul>
          </div>';
}
add_shortcode( 'list', 'list_shortcode');

// リストショートコード
function summary_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'content'  => '',
	), $atts ) );

	return '<div class="summary"><p><span class="summary_point">記事の要約</span>' . $content . '</p></div>';
}
add_shortcode( 'summary', 'summary_shortcode');

// 全てのリンクにtargetを付与する
function autoblank($text) {
  $return = str_replace('<a', '<a target="_blank"', $text);
  return $return;
  }
add_filter('the_content', 'autoblank');

/**
 * 目次ショートコードです。
 *
 * @version 3.0.0
 */
class Toc_Shortcode {

	private $add_script = false;
	private $atts = array();

	public function __construct() {
		add_shortcode( 'toc', array( $this, 'shortcode_content' ) );
		add_action( 'wp_footer', array( $this, 'add_script' ), 999999 );
		add_filter( 'the_content', array( $this, 'change_content' ), 9 );
	}

	function change_content( $content ) {
		return "<div id=\"toc_content\">{$content}</div>";
	}

	public function shortcode_content( $atts ) {
		global $post;

		if ( ! isset( $post ) )
			return '';

		$this->atts = shortcode_atts( array(
			'id' => '',
			'class' => 'toc',
			'title' => '目次',
			'toggle' => true,
			'opentext' => '開く',
			'closetext' => '閉じる',
			'close' => false,
			'showcount' => 2,
			'depth' => 0,
			'toplevel' => 2,
			'scroll' => 'smooth',
		), $atts );

		$this->atts['toggle'] = ( false !== $this->atts['toggle'] && 'false' !== $this->atts['toggle'] ) ? true : false;
		$this->atts['close'] = ( false !== $this->atts['close'] && 'false' !== $this->atts['close'] ) ? true : false;

		$content = $post->post_content;

		$headers = array();
		preg_match_all( '/<([hH][1-6]).*?>(.*?)<\/[hH][1-6].*?>/u', $content, $headers );
		$header_count = count( $headers[0] );
		$counter = 0;
		$counters = array( 0, 0, 0, 0, 0, 0 );
		$current_depth = 0;
		$prev_depth = 0;
		$top_level = intval( $this->atts['toplevel'] );
		if ( $top_level < 1 ) $top_level = 1;
		if ( $top_level > 6 ) $top_level = 6;
		$this->atts['toplevel'] = $top_level;

		// 表示する階層数
		$max_depth = ( ( $this->atts['depth'] == 0 ) ? 6 : intval( $this->atts['depth'] ) );

		$toc_list = '';
		for ( $i = 0; $i < $header_count; $i++ ) {
			$depth = 0;
			switch ( strtolower( $headers[1][$i] ) ) {
				case 'h1': $depth = 1 - $top_level + 1; break;
				case 'h2': $depth = 2 - $top_level + 1; break;
				case 'h3': $depth = 3 - $top_level + 1; break;
				case 'h4': $depth = 4 - $top_level + 1; break;
				case 'h5': $depth = 5 - $top_level + 1; break;
				case 'h6': $depth = 6 - $top_level + 1; break;
			}
			if ( $depth >= 1 && $depth <= $max_depth ) {
				if ( $current_depth == $depth ) {
					$toc_list .= '</li>';
				}
				while ( $current_depth > $depth ) {
					$toc_list .= '</li></ul>';
					$current_depth--;
					$counters[$current_depth] = 0;
				}
				if ( $current_depth != $prev_depth ) {
					$toc_list .= '</li>';
				}
				if ( $current_depth < $depth ) {
					$class = $current_depth == 0 ? ' class="toc-list"' : '';
					$style = $current_depth == 0 && $this->atts['close'] ? ' style="display: none;"' : '';
					$toc_list .= "<ul{$class}{$style}>";
					$current_depth++;
				}
				$counters[$current_depth - 1]++;
				$number = $counters[0];
				for ( $j = 1; $j < $current_depth; $j++ ) {
					$number .= '.' . $counters[$j];
				}
				$counter++;
				$toc_list .= '<li><a href="#toc' . ($i + 1) . '"><span class="contentstable-number">' . $number . '</span> ' . $headers[2][$i] . '</a>';
				$prev_depth = $depth;
			}
		}
		while ( $current_depth >= 1 ) {
			$toc_list .= '</li></ul>';
			$current_depth--;
		}

		$html = '';
		if ( $counter >= $this->atts['showcount'] ) {
			$this->add_script = true;

			$toggle = '';
			if ( $this->atts['toggle'] ) {
				$toggle = ' <span class="toc-toggle">[<a class="internal" href="javascript:void(0);">' . ( $this->atts['close'] ? $this->atts['opentext'] : $this->atts['closetext'] ) . '</a>]</span>';
			}

			$html .= '<div' . ( $this->atts['id'] != '' ? ' id="' . $this->atts['id'] . '"' : '' ) . ' class="' . $this->atts['class'] . '">';
			$html .= '<p class="toc-title">' . $this->atts['title'] . $toggle . '</p>';
			$html .= $toc_list;
			$html .= '</div>' . "\n";
		}

		return $html;
	}

	public function add_script() {
		if ( ! $this->add_script ) {
			return false;
		}

		$var = wp_json_encode( array(
			'open_text' => isset( $this->atts['opentext'] ) ? $this->atts['opentext'] : '開く',
			'close_text' => isset( $this->atts['closetext'] ) ? $this->atts['closetext'] : '閉じる',
			'scroll' => isset( $this->atts['scroll'] ) ? $this->atts['scroll'] : 'smooth',
		) );

		?>
<script type="text/javascript">
var xo_toc = <?php echo $var; ?>;
let xoToc = () => {
  const entryContent = document.getElementById('toc_content');
  if (!entryContent) {
    return false;
  }

  /**
   * スムーズスクロール関数
   */
  let smoothScroll = (target, offset) => {
    const targetRect = target.getBoundingClientRect();
    const targetY = targetRect.top + window.pageYOffset - offset;
    window.scrollTo({left: 0, top: targetY, behavior: xo_toc['scroll']});
  };

  /**
   * アンカータグにイベントを登録
   */
  const wpadminbar = document.getElementById('wpadminbar');
  const smoothOffset = (wpadminbar ? wpadminbar.clientHeight : 0) + 2;
  const links = document.querySelectorAll('.toc-list a[href*="#"]');
  for (let i = 0; i < links.length; i++) {
    links[i].addEventListener('click', function (e) {
      const href = e.currentTarget.getAttribute('href');
      const splitHref = href.split('#');
      const targetID = splitHref[1];
      const target = document.getElementById(targetID);

      if (target) {
        e.preventDefault();
        smoothScroll(target, smoothOffset);
      } else {
        return true;
      }
      return false;
    });
  }

  /**
   * ヘッダータグに ID を付与
   */
  const headers = entryContent.querySelectorAll('h1, h2, h3, h4, h5, h6');
  for (let i = 0; i < headers.length; i++) {
    headers[i].setAttribute('id', 'toc' + (i + 1));
  }

  /**
   * 目次項目の開閉
   */
  const tocs = document.getElementsByClassName('toc');
  for (let i = 0; i < tocs.length; i++) {
    const toggle = tocs[i].getElementsByClassName('toc-toggle')[0].getElementsByTagName('a')[0];
    toggle.addEventListener('click', function (e) {
      const target = e.currentTarget;
      const tocList = tocs[i].getElementsByClassName('toc-list')[0];
      if (tocList.hidden) {
        target.innerText = xo_toc['close_text'];
      } else {
        target.innerText = xo_toc['open_text'];
      }
      tocList.hidden = !tocList.hidden;
    });
  }
};
xoToc();
</script>
		<?php
	}

}

new Toc_Shortcode();
