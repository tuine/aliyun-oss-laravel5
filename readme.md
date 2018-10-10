# laravel-aliyun-oss
Aliyun oss filesystem storage adapter for laravel 5. 

## Adaptation By
- [jacobcyl/Aliyun-oss-storage](https://github.com/jacobcyl/Aliyun-oss-storage) 

## Require
- Laravel 5+
- cURL extension

##Installation
Edit composer.json, just add

    "tuine/laravel-aliyun-oss": "^1.0"

to your composer.json. Then run `composer install`.  
Or you can simply run below command to install:

    "composer require tuine/laravel-aliyun-oss:^1.0"
    
Then in your `config/app.php` add this line to providers array:
```php
Tuine\AliOSS\AliOssServiceProvider::class,
```
## Configuration
Add the following in app/filesystems.php:
```php
'disks'=>[
    ...
    'alioss' => [
            'driver'        => 'oss',
            'access_id'     => '<Aliyun OSS AccessKeyId>',
            'access_key'    => '<Aliyun OSS AccessKeySecret>',
            'bucket'        => '<OSS bucket name>',
            'endpoint'      => '<the endpoint of OSS>',
            //'endpoint_internal' => '<internal endpoint [OSS内网节点] 如：oss-cn-shenzhen-internal.aliyuncs.com>',
            'cdnDomain'     => '<CDN domain, cdn域名>', // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
            'isCName'       => <true|false>, // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名生成文件url， false: 则使用外部节点生成url
            'debug'         => <true|false>,
    ],
    ...
]
```
Ok, well! You are finish to configure. Just feel free to use Aliyun OSS like Storage!

## Usage
See [Larave doc for Storage](https://laravel.com/docs/5.2/filesystem#custom-filesystems)
Or you can learn here:

> First you must use Storage facade

```php
use Illuminate\Support\Facades\Storage;
```    
> Then You can use all APIs of laravel Storage

```php
Storage::disk('oss'); // if default filesystems driver is oss, you can skip this step

//fetch all files of specified bucket(see upond configuration)
Storage::files($directory);
Storage::allFiles($directory);

Storage::put('path/to/file/file.jpg', $contents); //first parameter is the target file path, second paramter is file content
Storage::putFile('path/to/file/file.jpg', 'local/path/to/local_file.jpg'); // upload file from local path

Storage::get('path/to/file/file.jpg'); // get the file object by path
Storage::exists('path/to/file/file.jpg'); // determine if a given file exists on the storage(OSS)
Storage::size('path/to/file/file.jpg'); // get the file size (Byte)
Storage::lastModified('path/to/file/file.jpg'); // get date of last modification

Storage::directories($directory); // Get all of the directories within a given directory
Storage::allDirectories($directory); // Get all (recursive) of the directories within a given directory

Storage::copy('old/file1.jpg', 'new/file1.jpg');
Storage::move('old/file1.jpg', 'new/file1.jpg');
Storage::rename('path/to/file1.jpg', 'path/to/file2.jpg');

Storage::prepend('file.log', 'Prepended Text'); // Prepend to a file.
Storage::append('file.log', 'Appended Text'); // Append to a file.

Storage::delete('file.jpg');
Storage::delete(['file1.jpg', 'file2.jpg']);

Storage::makeDirectory($directory); // Create a directory.
Storage::deleteDirectory($directory); // Recursively delete a directory.It will delete all files within a given directory, SO Use with caution please.

// upgrade logs
// new plugin for v2.0 version
Storage::putRemoteFile('target/path/to/file/jacob.jpg', 'http://example.com/jacob.jpg'); //upload remote file to storage by remote url
// new function for v2.0.1 version
Storage::url('path/to/img.jpg') // get the file url
```

## Documentation
More development detail see [Aliyun OSS DOC](https://help.aliyun.com/document_detail/32099.html?spm=a2c4g.11186623.6.767.34676ef9YWuxdO)
## License
Source code is release under MIT license. Read LICENSE file for more information.
