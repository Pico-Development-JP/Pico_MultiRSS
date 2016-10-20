# pico-multirss
サイトのRSS(RDF site summary)を作成するプラグインです。記事のフォルダを指定して、特定の記事のみのRSSを出力するなど、柔軟な設定が可能です。

## テンプレートに追加する値
なし
 
##  追加するTwig変数
なし

##  コンフィグオプション
 * $config['multirss']['language']: RSSに表示する「RSSの言語」を指定します。指定しなかった場合は表示されません。
 * $config['mailform']['copyright']：RSSに表示する「サイトのコピーライト」を指定します。指定しなかった場合は表示されません。
 * $config['mailform']['webmaster']：RSSに表示する「ウェブマスター情報」を指定します。指定しなかった場合は表示されません。
 * $config['mailform']['category']：RSSに表示する「サイトのカテゴリ」を指定します。指定しなかった場合は表示されません。
 * $config['mailform']['channel']：RSSのチャンネルです。チャンネルの一つの要素が、一つのRSSを示します(配列)
  * $config['mailform']['channel']['title']：チャンネルのタイトルを指定します。
  * $config['mailform']['channel']['url']：チャンネルのURLを指定します。サイトアドレスが「 http://onpu-tamago.net/ 」、urlの値が「news.rss」の場合、実際のURLは「 http://onpu-tamago.net/news.rss 」になります。
  * $config['mailform']['channel']['directory']：RSSとして表示対象となる記事ファイルが格納されているフォルダ名を指定します(フォルダ名文字列)
  * $config['mailform']['channel']['directory']：RSSとして表示対象となる記事ファイルが格納されているフォルダ名を指定します(後述)
  * $config['mailform']['channel']['nosubdir']：記事ファイルを検索する際、サブフォルダを検索に含めるかどうかを指定します。省略時は含めません。
  * $config['mailform']['channel']['count']：RSSに表示される記事の数を指定します。
  * $config['mailform']['channel']['description']：チャンネルのタイトルを指定します。

### サイトのフォルダ指定

`$config['mailform']['channel']['directory']`には、記事の収集対象となるフォルダを指定することができます。

この要素には、フォルダを示す文字列の他、配列で複数のフォルダを指定することができます。その場合、値に文字列を指定することで、RSS記事タイトルの先頭に、文字を追加することができます。

ex).

```php
$config['mailform']['channel']['directory'] = array(
    '/events' => '【events】', 
    '/history' => '【update】');
```
