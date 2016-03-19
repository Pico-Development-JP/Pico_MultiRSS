<?php
/**
 * Pico Plugin Pico-MultiRSS
 * RSSを出力する。複数個のRSSを出力することも可能。
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class Pico_MultiRSS extends AbstractPicoPlugin {

  protected $enabled = false;

  private $baseurl;
  
  private $plugin_path;
  
  private $rss;
  
  private $channel;

  /**
   * ファイルパスが指定されたディレクトリ配下のファイルを示しているかどうかを確認する
   * 
   * $path ... ファイルパス
   * $directory ... 確認するディレクトリパス
   * return ... ファイルパスが指定したディレクトリ配下のファイルを示す場合true
   *          チャンネルパラメータnosubdirがtrueの場合、指定ディレクトリのファイルのときのみtrue
   *
   */
  private function check($path, $directory)
  {
    $path = "/" . $path; // 互換性維持のため。
    $nosubdir = FALSE;
    if(isset($this->channel['nosubdir'])) $nosubdir = $this->channel['nosubdir'];
    return substr($path, 0, strlen($directory)) == $directory && 
      (!$nosubdir || strpos(substr($path, strlen($directory)), "/") === FALSE);
  }
  
  public function onConfigLoaded(array &$config)
  {
    $this->rss = array(
      'generator' => 'Pico',
      'channel' => array(
        array(
          'url' => 'rss',
          'directory' => '/',
          'count' => 10,
        )
      ),
    );
    $this->base_url = $config['base_url'];
    $this->plugin_path = dirname(__FILE__);
    if(isset($config['multirss'])){
      $this->rss += $config['multirss'];
      $this->rss['channel'] = $config['multirss']['channel'];
    }
  }

  public function onRequestUrl(&$url)
  {
    $channels = $this->rss["channel"];
    // 該当するチャンネルを探す
    foreach($channels as $channel){
      if($channel['url'] == $url){
        $this->channel = $channel;
        break;
      }
    }
  }

  public function onPagesLoaded(
      array &$pages,
      array &$currentPage = null,
      array &$previousPage = null,
      array &$nextPage = null
  )
  {
    // 該当チャンネルがあれば処理
    if($this->channel){
      $new_pages = array();
      foreach($pages as $page){
        $path = substr($page["url"], strlen($this->base_url));
        if(!empty($page["date"])){
          if(is_array($this->channel['directory'])){
            // 配列の場合、各キーとパスを照合
            foreach($this->channel['directory'] as $key => $value){
              if($this->check($path, $key)){
                $page["title"] = $value . $page["title"];
                array_push($new_pages, $page);
              }
            }
          }else{
            // 文字列の場合、ディレクトリとパスを照合
            $s = $this->channel['directory'];
            if($this->check($path, $s)){
              array_push($new_pages, $page);
            }
          }
        }
      }
      $updated = array();
      foreach($new_pages as $key => $val){
        $updated[$key] = $val["date"];
      }
      array_multisort($updated, SORT_DESC, $new_pages);
      $pages = array_slice($new_pages, 0, $this->channel['count']);
    }
  }

  public function onPageRendering(Twig_Environment &$twig, array &$twigVariables, &$templateName)
  {
    // from https://github.com/gilbitron/Pico-RSS-Plugin/blob/master/pico_rss/pico_rss.php#L34
		if($this->channel){
			header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
			header("Content-Type: application/rss+xml; charset=UTF-8");
			$loader = new Twig_Loader_Filesystem($this->plugin_path);
			$twig_rss = new Twig_Environment($loader, $twigVariables['config']['twig_config']);
			$this->rss['channel'] = $this->channel;
			$twigVariables += $this->rss;
			echo $twig_rss->render('rss.twig', $twigVariables);
			exit;
		}
  }
}

?>
