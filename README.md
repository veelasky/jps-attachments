# JPS Attachment 

JPS Attachment is a laravel package that enlighten the way of attaching media on any eloquent model.

With JPS Attachment, you can share attachment with anyone, grant access a certain file to a certain owner.

## Installation

## Usage
### Publish vendor configuration
```shell script
php artisan vendor:publish --provider="Jalameta\\Attachments\\AttachmentServiceProvider"
```

### Create attachment
We provide traits `AttachmentCreator` to create an attachment. 

#### From uploaded file

Utilize the `create()` method. First parameter is an UploadedFile instance and the second parameter accept array of attributes.

```php
use Jalameta\Attachments\Concerns\AttachmentCreator;

class YourController {
    use AttachmentCreator;

    public function store()
    {
        $attachment = $this->create(request()->file('upload'), [
            'title' => 'My Amazing Attachment.png',
        ]);
    
        //
    }
}
```

#### From existing file in storage

Utilize the `createFromPath()` method. First parameter accept disk name defined 
in your `config/filesystems.php` (See more at [Laravel Filesystem](https://laravel.com/docs)) and the second parameter accept array of attributes.

```php
use Jalameta\Attachments\Concerns\AttachmentCreator;

class YourController {
    use AttachmentCreator;

    public function store()
    {
        $attachment = $this->createFromPath(request()->file('upload'), [
            'disk' => 'attachment',
            'title' => 'My Amazing Attachment.png',
        ]);
    
        //
    }
}
```

### Attaching owner to your attachment
JPS Attachment provide `AttachableContract` Interface and `Attachable` trait to use 

#### Updating your model
Example
```php
use Jalameta\Attachments\Contracts\AttachableContract;
use Illuminate\Database\Eloquent\Model;

class YourModel extends Model implements AttachableContract 
{
    use Attachable;
}
```

#### Attach created attachment into attachable
```php
$attachment = $this->create(request()->file('upload'), [
    'title' => 'My Amazing Attachment.png',
]);

$model->attachments()->attach($attachment);
``` 

#### Creating attachment response
You may have any struggle to creating response based on saved attachments. 
We provide `AttachmentResponse` that bind into Application Container.

All you need is to resolve our AttachmentResponse instance by
```php
use Jalameta\Attachments\AttachmentResponse;

$response = app(AttachmentResponse::class);
```

Example creating response.

##### Stream
```php
$response->stream($attachment);
```

##### Download
```php
$response->download($attachment);
```

##### Stream or Download

```php
$response->streamOrDownload($attachment);
```
If you are using `streamOrDownload()` method and pass query parameter `stream` with value that equal with `true`, 
it will create stream response instead of download. If the `stream` parameter is not present, the download action will be used.


### Customizing attributes
JPS Attachment is flexible, it can be adjusted as your application needs. There are attributes that can be customized for.

#### Available attributes

| Attribute   	| Required 	| Description                                                                                                                                     	|
|-------------	|----------	|-------------------------------------------------------------------------------------------------------------------------------------------------	|
| title       	| yes      	| File attachment title, it will be used in your download filename.                                                                               	|
| mime        	| yes      	| Attachment file mime.                                                                                                                           	|
| disk        	| no       	| The filesystem disk target to save the attachment. if it's null the default filesystem disk that defined in config/attachment.php will be used. 	|
| path        	| yes      	| After saving, generated filename will be stored in specific path.                                                                               	|
| type        	| yes      	| default value `attachment`.                                                                                                                     	|
| description 	| no       	| Just text description.                                                                                                                          	|
| options     	| no       	| If you need complex logic with unique json value, you can store it here.                                                                        	|


> Milestones 
- [ ] create unit tests
