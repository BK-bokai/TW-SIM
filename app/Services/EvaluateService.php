<?php

namespace App\Services;

// use App\Repositories\MemberRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Jobs\Met_Evaluate;

class EvaluateService
{
    public function get_img($Path, $area){
        $Path = explode("\\", $Path);
        $Path = join("\\", array_slice($Path, 1, 4)) . '\\imgs';

        $T2img = $this->get_img_area("$Path\\T2",$area); //得到T2資料夾
        $WSimg = $this->get_img_area("$Path\\WS",$area); //得到WS資料夾
        $WDimg = $this->get_img_area("$Path\\WD",$area); //得到WD資料夾
        return ['T2img'=>$T2img, 'WSimg'=>$WSimg, 'WDimg'=>$WDimg];
    }

    public function get_img_area($Path, $area){
        foreach (scandir($Path) as $imgs) {
            // 掃描圖片資料夾內是否有符合我要的區域
            if (strpos($imgs, $area)) {  
                $img = $Path . '\\' . $imgs;
            }
        }
        //回傳圖片
        return $img;
    }

    function zipfile($zipFileName, $Path)
    {
        $zip_file = $zipFileName;
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $predir = Null;
        $this->addFileToZip($Path, $zip, $predir);
        $zip->close();
        return $zip_file;
    }

    function addFileToZip($Path, $zip, $predir)
    {
        $datas = scandir($Path);
        foreach ($datas as $data) {
            if ($data != "." && $data != "..") {
                if (!is_dir($Path . $data)) {
                    if (is_null($predir)) {
                        $download = $Path . $data;
                        $zip->addFile($download, $data);
                    } else {
                        $download = $Path . $data;
                        $zip->addFile($download, $predir . '\\' . $data);
                    }
                } else {
                    $this->addFileToZip($Path . $data . '\\', $zip, $data);
                }
            }
        }
    }

    public function Met_Evaluate($now,$start,$end,$rootdir)
    {
        dispatch(new Met_Evaluate($now,$start,$end,$rootdir));
    }


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
                        $this->deldir($path . $val . '/');
                        //目錄清空後刪除空資料夾
                        @rmdir($path . $val . '/');
                    } else {
                        //如果是檔案直接刪除
                        unlink($path . $val);
                        @rmdir($path);
                    }
                }
            }
        }
    }
}
