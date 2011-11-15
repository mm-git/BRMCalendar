# brmcalendar

## 概要

BRMの予定を時系列にカレンダーに並べて表示するだけのスクリプト。

## 説明

To make a code block, indent four spaces:



* 下記のファイルを同じフォルダに置く:

フォルダ構造:

    brmcalendar/
        index.php        -- メインページ
        index.css        -- スタイルシート
        icon.png         -- ページアイコン (iOS用)
        brmdata.php      -- BRMデータ読み込みクラス
        cache.manifest   -- オフラインアプリ用 manifest
        yyyy.txt         -- 西暦４桁のテキストファイル

* yyyy.txtの中身

    - ;で始まる行はコメント行
    - 　ファイル名は yyyy.txt (UTF-8)
    - 中身はcsv形式
       -  ClubUniqueID,クラブ名称,略称名,距離,mmdd,mmdd,...


## LICENSE

このスクリプトはMITライセンスとします。
LICENSE ファイル参照。


## 連絡先

[email :] mmgithub@gmail.com
