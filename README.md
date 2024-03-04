# Custom Moodle tracker localhost homepage

This is my locahost custom homepage. It shows the Moodle instances I have in my localhost,
all of them created using Moodle Development Kit in the localhost/m folder. It also shows
the Moodle tracker issue if the instance is pointing at MDL-XXXXX git branch.

It is not suposed to be a super well built project, but a simple way to show the instances
and get the issue information form the Moodle tracker. However, many people ask me to share it
and I think it is a good idea.

I will keep it updated I add feature to i when needed.

## Requirements

Optional:

- php-redis -> it uses redis cache to store the instances data.

## Where to install

The code is intended to be installed in the localhost/home directory.
You can clone the project by running this commands on your localhost:

```bash
git clone https://github.com/ferranrecio/mdkhome.git home
```

If you want to use a different directory, you can change the $CFG->dirroot
and $CFG->wwwroot variables. See the "Change paths" section for more details.

## How to configure

Once you have the code in your localhost/home directory, you can configure
it by creating a locahost/index.php file (or locahost/home.php if you don't
want to override the default localhost index) with the content:

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
$CFG = new stdClass();

class custom {
    public function instance_icon_url(stdClass $entry): string {
        global $CFG;
        return $CFG->wwwpix . '/yourimage.png';
  }
}

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
$CFG = new stdClass();

class custom {
    public function instance_icon_url(stdClass $entry): string {
        global $CFG;
        return $CFG->wwwpix . '/yourimage.png';
  }
}

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

## Configure Redis

The page uses redis to store the instances data. By default it will use a localhost
redis instance using the default port (6379).. However, if you use a different
setup, you can override the $CFG->redis variable in the home.php file.

```php
<?php
global $CFG;
$CFG = new stdClass();

$CFG->redisip = '127.0.0.1'; // Set an alternative IP.
$CFG->redisport = 6379; // Set an alternative port.
$CFG->rediscachename = 'locahostinstances'; // Change the entry name if you want.

require_once('home/index.php');
```

## Check other $CFG variables

All the $CFG variables are defined in the lib/setup.php file. You can check them
in the setCfg method.

## What to do if you have ideas to improve it

This is not a project meant to be improved and generate community contributions. It is just
my locahost homepage. However, if you have ideas to improve it, you can create a pull request.
I will review them from time to time.

However, if you really want to customize it, you can fork the project and create your own
version.
