<?php

namespace Tuine\AliOSS;

use Tuine\AliOSS\Plugins\PutFile;
use Tuine\AliOSS\Plugins\PutRemoteFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class AliOssServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //发布配置文件
        /*
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('alioss.php'),
            ], 'config');
        }
        */

        Storage::extend('alioss', function ($app, $config) {
            $accessId  = $config['access_id'];
            $accessKey = $config['access_key'];

            $cdnDomain = empty($config['cdnDomain']) ? '' : $config['cdnDomain'];
            $bucket    = $config['bucket'];
            $isCname   = empty($config['isCName']) ? false : $config['isCName'];
            $debug     = empty($config['debug']) ? false : $config['debug'];
            $prefix    = empty($config['root']) ? null : $config['root'];
            $timeout   = empty($config['timeout']) ? 600 : $config['timeout'];

            $endPoint   = $config['endpoint']; // 默认作为外部节点
            $epInternal = $isCname ? $cdnDomain : (empty($config['endpoint_internal']) ? $endPoint : $config['endpoint_internal']); // 内部节点

            if ($debug) {
                Log::debug('OSS config:', $config);
            }

            $client    = new OssClient($accessId, $accessKey, $epInternal, $isCname);
            $bucketAcl = $client->getBucketAcl($bucket);
            $adapter   = new AliOssAdapter($client, $bucket, $endPoint, $isCname, $debug, $cdnDomain, $prefix, [],
                $bucketAcl, $timeout);

            //Log::debug($client);
            $filesystem = new Filesystem($adapter);

            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());
            //$filesystem->addPlugin(new CallBack());
            return $filesystem;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

}
