<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/3/18
 * Time: 3:36 PM
 */

namespace App\Traits;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

trait CephDiskTrait
{
    public function createCephDisk($config)
    {
        $client = new S3Client([
            'base_url' => config('http://10.2.11.146:8080'),
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ],
            'region' => 'RegionOne',
            'version' => 'latest',
        ]);
        $adapter = new AwsS3Adapter($client, $config['bucket']);
        $filesytem = new Filesystem($adapter);

        return $filesytem;
    }
}