##インストール方法

## インストール後の実施事項

画像のダミーデータは
public/imagesフォルダ内に
sample1.jpg〜sample6.jpgとして
保存しています。

php artisan storage:link で
stprageフォルダ内にリンク後、

storage/app/public/productsフォルダ内に
保存されると表示されます。
(productフォルダがない場合は作成してください)

ショップの画像も表示する場合は、
storage/app/public/shopフォルダを作成し
画像を保存してください。