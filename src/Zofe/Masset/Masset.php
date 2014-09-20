<?php namespace Zofe\Masset;

use Zofe\Masset\Compressors\CSSMin;
use Zofe\Masset\Compressors\JSMin;

class Masset
{

    /**
     * @var string
     */
    protected $css_path;

    /**
     * @var string
     */
    protected $js_path;

    /**
     * @var string
     */
    protected $cache_path;

    /**
     * @var string
     */
    protected $base_url;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var type
     */
    protected $type = 'js';

    /**
     * @var int
     */
    protected $recent = 0;

    /**
     * @var string
     */
    protected $contents;

    /**
     * @var string
     */
    protected $minified;

    /**
     * @param array  $config
     * @param string $environment
     */
    public function __construct(array $config)
    {
        $this->checkConfiguration($config);
    }

    /**
     * add a file/s
     * @param $file
     * @return array
     */
    public function add($files, $type = 'js')
    {
        $this->files = array_merge($this->files, (array) $files);
        $this->type = ($type=='js') ? 'js' : 'css';
        $this->ext = ".".$this->type;
    }

    /**
     * compress assets and generate cache file
     */
    public function build()
    {
        $this->checkFiles();
        $this->cached = $this->fileHash();
        if(!$this->cacheIsOld($this->cached, $this->recent)) return;

        $this->contents = $this->loadFiles();
        
        if ($this->type == 'css') {

            $compressor = new CSSMin();
            $compressor->set_memory_limit('128M');
            $compressor->set_max_execution_time(60);
            $this->minified = $compressor->run($this->contents);
            file_put_contents($this->cached, $this->minified);
        }

        if ($this->type == 'js') {

            $this->minified  = JSMin::minify($this->contents);
            file_put_contents($this->cached, $this->minified);
        }
    }

    public function render()
    {
        static::compressOutput();
        static::sendHeaders($this->type, $this->recent);
        if ($this->minified) {
            echo $this->minified;
        } elseif (file_exists($this->cached)) {
            readfile($this->cached);
        }
        exit;
    }

    /**
     * build and send headers using given
     *
     * @param $type (css or js)
     * @param $lastmod  last modified file in pool, as timestamp
     * @param int $expires browser cache, in seconds
     */
    protected static function sendHeaders($type, $lastmod, $expires=604800)
    {
        $content_type = ($type=='css') ? 'content-type:text/css' : "content-type: text/javascript; charset: UTF-8";
        header($content_type);
        header("Cache-Control: max-age=" . $expires);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastmod) . " GMT");
        header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $expires) . " GMT");
    }

    /**
     * init output compression if available
     */
    protected static function compressOutput()
    {
        if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
            ini_set("zlib.output_compression", 1);
        }
    }

    /**
     * cleanup files array removing invalid files, fixing paths/extensions, checking last modified one
     *
     */
    protected function checkFiles()
    {
        $path = ($this->type=='css') ? $this->css_path : $this->js_path;
        $ext = ".".$this->type;
        $files = $this->files;
        $recent = 0;

        array_walk($files, function (&$value, $index) use ($path, $ext, &$files, &$recent) {
            $value = str_replace($ext, '', $value);
            $value = $path . $value . $ext;
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $value)) {
                $filetime = filemtime($_SERVER['DOCUMENT_ROOT'] . $value);
                $recent = ($filetime > $recent) ? $filetime : $recent;
            } else {
                unset($files[$index]);
            }
        });
        $this->recent = $recent;
        $this->files = $files;
    }

    /**
     * return a the cache file path
     * @return string
     */
    protected function fileHash()
    {
        return $_SERVER['DOCUMENT_ROOT'].$this->cache_path.substr(md5(serialize($this->files)), 0, 12).$this->ext;

    }

    /**
     * check if cache file is old or fresh comparing filetime
     *
     * @param $cached
     * @param $recent
     * @return bool
     */
    protected function cacheIsOld($cached, $recent)
    {

        if (file_exists($cached)) {
            if (filemtime($cached) > $recent) {
                return false;
            }
        }

        return true;
    }

    /**
     * get files content
     *
     * @return string
     */
    protected function loadFiles()
    {
        $contents = "";
        if (count($this->files)) {
            foreach ($this->files as $file) {
                $filepath = $_SERVER['DOCUMENT_ROOT'] . $file;
                if (file_exists($filepath)) {
                    $contents .= file_get_contents($filepath);
                }
            }
        }

        return $contents;
    }

    /**
     * simple check for config parameters
     *
     * @param  array                     $config
     * @throws \InvalidArgumentException
     */
    protected function checkConfiguration(array $config)
    {

        foreach ($config as $key=>$value) {
            if (!in_array($key, array('css_path','js_path','cache_path')))
                throw new \InvalidArgumentException("Missing {$key} configuration");

            if (!is_string($value))
                throw new \InvalidArgumentException("Wrong value for {$key}");

            $this->$key = $value;
        }

    }
}
