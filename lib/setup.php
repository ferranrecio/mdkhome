<?php

function setup() {
    setCfg();
    // Allow CORS from tracker.moodle.org
    header('Access-Control-Allow-Origin: https://tracker.moodle.org');
    ini_set('default_socket_timeout', 1);
}

function setCfg() {
    global $CFG;

    if (!isset($CFG)) {
        $CFG = new stdClass();
    }

    $CFG->dirroot = $CFG->dirroot ?? dirname(__DIR__);
    $CFG->wwwroot = $CFG->wwwroot ?? 'http://localhost/home';
    $CFG->moodleswwwroot = $CFG->moodleswwwroot ?? 'http://localhost/m';
    $CFG->moodlesdir = $CFG->moodlesdir ?? dirname(dirname(__DIR__)) . '/m';
    $CFG->maininstance = $CFG->maininstance ?? $CFG->moodlesdir . '/main';

    $CFG->wwwpix = $CFG->wwwroot . '/pix';
    $CFG->wwwcss = $CFG->wwwroot . '/css';
    $CFG->wwwjs = $CFG->wwwroot . '/js';
    $CFG->helpers = $CFG->wwwroot . '/helpers';
    $CFG->extras = $CFG->extras ?? [];
}