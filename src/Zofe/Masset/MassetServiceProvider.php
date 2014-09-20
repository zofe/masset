<?php namespace Zofe\Masset;

use Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MassetServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('zofe/masset', 'masset');
        //include __DIR__ . '/../../routes.php';
        //include __DIR__ . '/../../macro.php';

        AliasLoader::getInstance()->alias('Masset',  'Zofe\Masset\Facades\Masset');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['Masset'] = $this->app->share(function($app)
        {
            return new Masset(
                array(
                    'css_build_path' => Config::get('masset::css_build_path'),
                    'js_build_path'  => Config::get('masset::js_build_path'),
                    'base_url'       => Config::get('masset::base_url'),
                ),
                $app->environment()
            );
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('masset');
    }

}
