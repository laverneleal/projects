<?php 


function listdirfile_by_date($path){
    $dir = opendir($path);
    $list = array();
    while($file = readdir($dir))
    {
        if($file != '..' && $file != '.')
        {
            $mtime = filemtime($path . $file) . ',' . $file;
            $list[$mtime] = $file;
        }
    }
    closedir($dir);
    krsort($list);

    foreach($list as $key => $value)
    {
        return $list[$key];
    }
    return '';
}

$server = '//hrdapps44/new_Imanager_data/DISTRIBUTION/BUNDLE LIST SAMPLE/';
echo listdirfile_by_date($server);