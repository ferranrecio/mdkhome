# Custom Moodle tracker localhost homepage

## How to configure

1. Copy the code in you localhost/home directory.
2. Creat a locahost/home.php file with the content:

```php
<?php
require_once 'home/index.php';
```

## How to add extra links

The following example shows how to add a links to the home page.

```php
<?php
global $CFG;
$CFG = new stdClass();

// Extra info.
$CFG->extras = [
  'Links' => [
    ['title' => 'Gmail', 'url' => 'https://mail.google.com/'],
    ['title' => 'Drive', 'url' => 'https://drive.google.com/drive/my-drive'],
  ],
  'Doc' => [
    ['title' => 'Local Devdocs', 'url' => 'http://localhost/devdocs/build/'],
    ['title' => 'MDK', 'url' => 'https://docs.moodle.org/dev/Moodle_Development_kit'],
  ]
];
require_once 'home/index.php';
```

## How to cusmtoize instance icons

By default, the instance icons uses the instance name. However, you can provide
an alternative custom class to override the default icon.

```php
<?php
global $CFG;

class custom {
    public function instance_icon_url(stdClass $entry): string {
        global $CFG;
        return $CFG->wwwpix . '/yourimage.png';
  }
}

$CFG = new stdClass();
$CFG->custom = new custom();

require_once('home/index.php');
```

## Change paths

If you want to change some path, your can override $CFG variables.

By default it works with the default MDK structure and the code in your
localhost/home directory.

```php
<?php
global $CFG;

class custom {
    public function instance_icon_url(stdClass $entry): string {
        global $CFG;
        return $CFG->wwwpix . '/yourimage.png';
  }
}

$CFG = new stdClass();
// The "home" folder.
$CFG->dirroot = '/what/ever/path/you/want';
// The "home" url, for JS and CSS mainly.
$CFG->wwwroot = 'http://localhost/what/ever/path/you/want';
// The "moodles" folder to scan.
$CFG->moodleswwwroot = 'http://localhost/path/to/moodles';
// The "moodles" folder to scan.
$CFG->moodlesdir = '/path/to/moodles';
// The main instance, the code will use it check if other instances are updated.
$CFG->maininstance = 'http://localhost/path/to/moodles/main/instance';

require_once('home/index.php');
```
