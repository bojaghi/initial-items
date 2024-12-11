# Initial items

Seed posts and terms helper 

## How to use

See example below:

```php
<?php

use Bojaghi\SeedObjects\SeedsObjects;

$seedObjects = new SeedsObjects(
    [
        'isPlugin'             => true,  // 'true' for plugins, 'false' for themes.
        'removeOnDeactivation' => false, // 'true' if you want to delete all seed objects after the deactivation.
        'mainFile'             => '/path/to/plugin/main_file' // Required if isPlugin=true.
        'comments'             => '',    // Path or configuration array for comments/posts/terms/users.
        'posts'                => '',
        'terms'                => '',
        'users'                => '',
    ],
);
```

Each configuration array is a series of arguments, which should be adapted into wp_insert_*() function call.
For example, 'posts' configuration file:

```php
<?php
/** 
 * /path/to/posts/configuration/post-seeds.php 
 */
 
// Prevent direct access.
if (!defined('ABSPATH')) {
    exist;
}

return array(
    // #1
    array(
        'post_title' => 'Foo #1',
        'post_type'  => 'post',
        /* ... */
    ),
    // #2
    array(/* .. */),
     // #3
    array(/* .. */),
     // #4
    array(/* .. */),
);
```
