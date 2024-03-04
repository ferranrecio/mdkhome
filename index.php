<?php

require_once(__DIR__ . '/lib/setup.php');
require_once(__DIR__ . '/lib/instances.php');
require_once(__DIR__ . '/lib/output.php');

setup();

$template = new Template();

$template->assign('title', 'Moodle Tracker');
$template->assign('instances', get_instances_info());

$template->render('home');
