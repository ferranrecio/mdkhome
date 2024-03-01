<?php

function get_version_from_file($versionpath) {
    $versionfile = file_get_contents($versionpath);
    $matches = [];
    preg_match('/\$version[ ]+=[ ]+([0-9\.]+)/', $versionfile, $matches);
    return $matches[1] ?? '¿?';
}

function clean_mdl_name($entry) {
    // if we hace more than 2 - delete them
    $parts = explode('-', $entry, 3);
    if (count($parts) > 2)
        $entry = $parts[0] . '-' . $parts[1];
    // grant we get a clean MDL-XXXX ID
    $MDL = str_replace('MDL-', 'MDL', $entry);
    return str_replace('MDL', 'MDL-', $MDL);
}

function is_an_mdl($entry) {
    //return  strpos($entry, 'MDL') === 0;
    return preg_match('/MDL\-\d+/', $entry);
}

function instance_icon_url($entry) {
    global $CFG;

    if (isset($CFG->custom) && method_exists($CFG->custom, 'instance_icon_url')) {
        return $CFG->custom->instance_icon_url($entry);
    }

    $base = $CFG->wwwpix . '/';
    if (file_exists('./img/' . $entry->name . '.png')) {
        return $base . $entry->name . '.png';
    }
    if ($entry->name == 'integration') {
        return $base . 'int_main.png';
    }
    if (in_array($entry->name, ['master', 'main']) || strpos($entry->name, 'stable_') === 0) {
        return $base . 'star.png';
    }
    $parts = explode('-', $entry->name);
    if (count($parts) < 2) {
        return $base . 'moodle.png';
    }
    if (!is_numeric($parts[1])) {
        return $base . 'labs.png';
    }
    return $base . 'moodle.png';
}

function scan_instances() {
    global $CFG;

    $baseversion = get_version_from_file($CFG->maininstance . "/version.php");

    $instances = [];
    if ($handle = opendir('./m')) {
        while (false !== ($entry = readdir($handle))) {
            $info = entry_info($entry, $baseversion);
            // Add entry.
            if ($info) {
                $instances[$entry] = $info;
            }
        }
        closedir($handle);
    }
    ksort($instances);
    return $instances;
}

function entry_info($entry, $baseversion) {
    if ($entry == "." || $entry == ".." || $entry == "mdk") {
        return null;
    }

    $info = (object) [
        'title' => "¿$entry?",
        'name' => $entry,
        'mdl' => '',
    ];

    if (is_an_mdl($entry)) {
        $info->mdl = clean_mdl_name($info->name);
    }

    // Load git information.
    $gitpath = './m/' . $entry . '/.git/HEAD';
    if (file_exists($gitpath)) {
        $stringfromfile = file($gitpath, FILE_USE_INCLUDE_PATH);
        $stringsfromfile = explode('/', $stringfromfile[0]);
        $branch = end($stringsfromfile);
        $info->title = $branch;
        $info->branch = $branch;
        if (empty($info->mdl) && is_an_mdl($branch)) {
            $info->mdl = clean_mdl_name($branch);
        }
    }

    // Moodle version.
    $versionpath = "./m/{$entry}/version.php";
    if (file_exists($versionpath)) {
        $info->version = get_version_from_file($versionpath);
        $info->rebase = ($info->version < $baseversion);
    } else {
        $info->version = '¿?';
        $info->rebase = false;
    }

    // Check for untracked file in git
    $output = [];
    // $command = "cd ./m/{$entry} ; git diff-index --name-only HEAD";
    $command = "cd ./m/{$entry} ; git status --porcelain";
    exec($command, $output);
    // $output = trim($output);
    if (!empty($output)) {
        $info->dirty = $output;
    }

    return $info;
}

function get_instances_info() {
    global $_GET;

    $refresh = $_GET['refresh'] ?? false;

    // Check local cache for instances.
    if (!class_exists('Redis')) {
        return scan_instances();
    }

    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    try {
        $json = $redis->get('locahostinstances');
        $instances = json_decode($json);
    } catch (\Throwable $th) {
        $instances = [];
    }

    if (empty($instances) || $refresh) {
        // Scan local instances (cached for 6 hours).
        $instances = scan_instances();
        $redis->setex('locahostinstances', 22000, json_encode($instances));
    }

    return $instances;
}
