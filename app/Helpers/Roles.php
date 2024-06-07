<?php

if (! function_exists('becomeAdmin')) {
    function becomeAdmin($user = [])
    {
        $jwt = \Config::get('jwt') ?? [];
        $jwt['sub'] = $jwt['sub'] ?? $user;
        $jwt['sub']['roles'] = [['name' => 'administracion']];
        \Config::set('jwt', $jwt);
    }
}
