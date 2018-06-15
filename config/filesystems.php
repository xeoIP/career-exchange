<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => public_path(),
        ],

        'ftp' => [
            'driver'   => 'ftp',
            'host'     => 'ftp.example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        's3' => [
            'driver'           => 's3',
            'key'              => env('S3_KEY', 'your-key'),
            'secret'           => env('S3_SECRET', 'your-secret'),
            'region'           => env('S3_REGION', 'your-region'),
            'bucket'           => env('S3_BUCKET', 'your-bucket'),
            'distribution_url' => env('S3_DISTRIBUTION_URL', ''),
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'your-username',
            'key'       => 'your-key',
            'container' => 'your-container',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL',
        ],

        'dropbox' => [
            'driver'           => 'dropbox',
            'clientIdentifier' => env('DROPBOX_CLIENT_IDENTIFIER'),
            'accessToken'      => env('DROPBOX_ACCESS_TOKEN'),
            'storageFolder'    => env('DROPBOX_STORAGE_FOLDER', 'Public/your-folder'),
            'userId'           => env('DROPBOX_USER_ID'),
        ],

        // used for Admin/Elfinder
        'uploads' => [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'url'        => env('APP_URL') . '/uploads',
            'visibility' => 'public',
        ],

        // used for Admin/Log
        'storage' => [
            'driver' => 'local',
            'root'   => storage_path(),
        ],

        // used for Admin/Backup
        'backups' => [
            'driver' => 'local',
            'root'   => storage_path('backups'), // that's where your backups are stored by default: storage/backups
        ],

    ],

];
