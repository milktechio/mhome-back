<?php

//local controller routes;
$path = __DIR__;
$files = collect(scandir($path));
foreach ($files as $file) {
    if (str_contains($file, '.php') && $file != 'index.php') {
        include __DIR__.'/'.$file;
    }
}

//modules routes
$path = base_path().'/Modules/';

if (file_exists($path)) {
    $folders = collect(scandir($path));
    foreach ($folders as $folder) {
        if (! str_contains($folder, '.')) {
            $file = base_path().'/Modules/'.$folder.'/routes/api.php';
            if (file_exists($file)) {
                include $file;
            }
        }
    }
}
