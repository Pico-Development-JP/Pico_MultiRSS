# pico-multirss

サイトのRSS(RDF site summary)を作成するプラグインです。記事のフォルダを指定して、特定の記事のみのRSSを出力するなど、柔軟な設定が可能です。

## テンプレートに追加する値

なし

## 追加するTwig変数

なし

## コンフィグオプション

```yaml
multirss:
  language: RSSに表示する「RSSの言語」を指定します(指定しなかった場合は表示しません)。
  copyright: RSSに表示する「サイトのコピーライト」を指定します(指定しなかった場合は表示しません)。
  webmaster: RSSに表示する「ウェブマスター情報」を指定します(指定しなかった場合は表示しません)。
  category: RSSに表示する「サイトのカテゴリ」を指定します(指定しなかった場合は表示しません)。
  channel: RSSのチャンネルです。チャンネルの一つの要素が、一つのRSSを示します
    -
      title: チャンネルのタイトルを指定します(必須)。
      url: チャンネルのURLを指定します(必須)。https://onpu-tamago.net/news.rss としたい場合、ここにはnews.rssと指定します。
      directory: RSSとして表示対象となる記事ファイルが格納されているフォルダ名を指定します(必須/文字列またはハッシュリストが指定可能)。
      nosubdir: 記事ファイルを検索する際、サブフォルダを検索に含めるかどうか(省略可)。
      count: RSSに表示される記事の数を指定します(必須)。
      description: チャンネルの説明文を指定します(省略可)。
```

### サイトのフォルダ指定

`channelのdirectory`には、記事の収集対象となるフォルダを指定することができます。

その場合、値に文字列を指定することで、RSS記事タイトルの先頭に、文字を追加することも可能です。

ex).


```yaml
multirss:
  channel:
    -
      /// 中略 ///
      directory:
        /events: 【events】
        /history: 【update】
```

### 全体的な記入例

```yaml
multirss:
  language: ja
  copyright: (C) TEST CORPORATION
  webmaster: Takami Chie
  category: TEST
  channel:
    -
      title: test
      url: rss.rss
      count: 5
      nosubdir: true
      directory: /
      description: test RSS
```
