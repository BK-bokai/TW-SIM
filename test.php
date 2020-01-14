<?php
    function deldir($path)
    {
        //如果是目錄則繼續
        if (is_dir($path)) {
            //掃描一個資料夾內的所有資料夾和檔案並返回陣列
            $p = scandir($path);
            foreach ($p as $val) {
                // 排除目錄中的.和..
                if ($val != "." && $val != "..") {
                    //如果是目錄則遞迴子目錄，繼續操作
                    if (is_dir($path . $val)) {
                        //子目錄中操作刪除資料夾和檔案
                        deldir($path . $val . '/');
                        //目錄清空後刪除空資料夾
                        @rmdir($path . $val . '/');
                    } else {
                        //如果是檔案直接刪除
                        unlink($path . $val);
                    }
                }
            }
        }
    }
    $path= "D:\\bokai\\xampp\\htdocs\\php\\TW_SIM_Evaluate\\public\\MetData\\Evaluate\\2020-01-13-14-05-19_2016-02-01-2016-03-31\\";
    deldir($path);
    @rmdir($path);
    // print_r(scandir($path));
    
?>
