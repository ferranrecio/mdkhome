<?php

class Template {
    private array $data = [];

    public function assign($key, $value) {
        $this->data[$key] = $value;
    }

    public function render($page) {
        global $CFG;
        extract($this->data);
        $template = $this;
        include($CFG->dirroot . '/templates/' . $page . '.php');
    }
}

// Convenience methods for templating.

class out {
    public static function url($path) {
        global $CFG;
        echo $CFG->wwwroot . '/' . $path;
    }

    public function img($path, $alt = '', $attrs = []) {
        global $CFG;
        $attrs['src'] = $CFG->wwwpix . '/' . $path;
        $attrs['alt'] = $alt;
        $attrs = array_map(function($k, $v) {
            return "$k=\"$v\"";
        }, array_keys($attrs), array_values($attrs));
        echo '<img ' . implode(' ', $attrs) . '>';
    }

    public static function bodyData() {
        global $CFG;
        $data = [
            'trackerhelper' => $CFG->helpers . '/tracker.php',
            'wwwpix' => $CFG->wwwpix,
        ];
        $attrs = array_map(function($k, $v) {
            return "data-$k=\"$v\"";
        }, array_keys($data), array_values($data));
        echo implode(' ', $attrs);
    }
}
