<br />
<b>Fatal error</b>:  Uncaught TypeError: mb_detect_encoding() expects parameter 1 to be string, boolean given in C:\Bokai\xampp\phpMyAdmin\libraries\classes\Util.php:1620
Stack trace:
#0 C:\Bokai\xampp\phpMyAdmin\libraries\classes\Util.php(1620): mb_detect_encoding(false, 'UTF-8', true)
#1 C:\Bokai\xampp\phpMyAdmin\libraries\classes\Plugins\Export\ExportSql.php(725): PhpMyAdmin\Util::localisedDate()
#2 C:\Bokai\xampp\phpMyAdmin\export.php(444): PhpMyAdmin\Plugins\Export\ExportSql-&gt;exportHeader()
#3 {main}
  thrown in <b>C:\Bokai\xampp\phpMyAdmin\libraries\classes\Util.php</b> on line <b>1620</b><br />
