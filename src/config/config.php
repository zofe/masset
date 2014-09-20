<?php

return array(


    /*
    |--------------------------------------------------------------------------
    | default assets path
    |--------------------------------------------------------------------------
    |
    | public path where uncompiled assets are stored (css and js).
    | This is relative to your public path (by default is app/public/assets/cache)
    |
    */

    'css_path' => '/assets/css/',
    'js_path' =>  '/assets/js/',

    
    /*
    |--------------------------------------------------------------------------
    | cache path
    |--------------------------------------------------------------------------
    |
    | public path where store and serve merged and minified assets (css and js).
    | This is relative to your public path (by default is app/public/assets/cache)
    | Note that this directory MUST BE WRITABLE.
    |
    */

    'cache_path' => '/assets/cache/',
    

    /*
    |--------------------------------------------------------------------------
    | base url 
    |--------------------------------------------------------------------------
    |
    | your assets base url, by default, if empty, HTTP_HOST would be used.
    |
    */
    'base_url' => '',
    
    /*
    |--------------------------------------------------------------------------
    | char separator  
    |--------------------------------------------------------------------------
    |
    | Masset can merge files, just use a separator character in your urls
    |
    */
    'separator' => ','
    

);