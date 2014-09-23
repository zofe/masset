masset
============

A simple Javascript and Stylesheet minifier written in pure PHP.  
Available on Composer, Masset is a thin wrapper around PHPmin and CSSmin, it also adds
the ability to concat multiple files into one and cache them.
The cache will be automatically cleaned every time you change
your source files.

### There are alternatives
Before using this package you should consider to use grunt/gulpjs instead, as
they are better solutions.
I've built this package because I have some old project which I nees to speedup to 
improve SEO.


### Installation

Install via composer adding ```"zofe/masset": "dev-master"```

### Laravel 

to-do


### Used standalone

write something like this into your html:

    <link href="/masset.php?files=style,header,footer&type=css" rel="stylesheet" type="text/css" />
    <script src="/masset.php?files=main,carousel,fancybox&type=js" type="text/javascript"></script>
    
    <!-- or  using rewrite rules -->
    
    <link href="/masset/style,header,foote.css" rel="stylesheet" type="text/css" />
    <script src="/masset/main,carousel,fancybox.js" type="text/javascript"></script>
    
then you can place masset in a standalone script:
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

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

If you used the rewrite approach you need something like this in your `.htaccess`:

    RewriteEngine On
    RewriteBase /
    RewriteRule ^masset/(.*).(css|js)$ /masset.php?files=$1&type=$2
    
If you prefer not to use comma as the separator or "masset" as the first segment you 
can easily fit your needs.

### Kudos to 
- Tubal Martin for https://github.com/tubalmartin/YUI-CSS-compressor-PHP-port
- Douglas Crockford for JSMin
and to the lots of people that worked on both projects.
 
