masset
============

simple javascript and stylesheet minifier in pure php.  
Basically It's wrapper to get via composer both phpmin & cssmin.  
It add the ability to merge and cache files.  
The cache will be cleaned each modify you do in sources.

### there are alternatives
Before use this package you should consider to use grunt / bower
they are better solutions.
I've made this stuff because I've some aged php project where I need only to speedup and increase SEO.


### kudos to 
- Tubal Martin for https://github.com/tubalmartin/YUI-CSS-compressor-PHP-port
- Douglas Crockford for JSMin
and a lot of people that worked on both project.
 

### used standalone

you can use something like this in your html

    <link href="/masset.php?files=style,header,footer&type=css" rel="stylesheet" type="text/css" />
    <script src="/masset.php?files=main,carousel,fancybox&type=js" type="text/javascript"></script>
    
    <!-- or  using rewrite rules -->
    
    <link href="/masset/style,header,foote.css" rel="stylesheet" type="text/css" />
    <script src="/masset/main,carousel,fancybox.js" type="text/javascript"></script>
    
then you can place masset in a standalone script
```php
<?php

$type = $_GET['type'];
$files = explode(",",$_GET['files']);

$config = array(
    'css_path' => '/assets/css/',
    'js_path' =>  '/assets/js/',
    'cache_path' => '/assets/cache/',
)

$masset = new Zofe\Masset\Masset($config);
$masset->add($files, $type);
$masset->build();
$masset->render();


```

if you used rewrite approach you need something like this in your htaccess 

    RewriteEngine On
    RewriteBase /
    RewriteRule ^masset/(.*).(css|js)$ /masset.php?files=$1&type=$2
    
if you don't like commas as separator or "masset" as first segment you can easily change the configuration.