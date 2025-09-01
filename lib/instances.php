<?php

/**
 * Retrieves the version number from a file.
 *
 * @param string $versionpath The path to the version file.
 * @return string The version number.
 */
function get_version_from_file(string $versionpath): string {
    $versionfile = file_get_contents($versionpath);
    $matches = [];
    preg_match('/\$version[ ]+=[ ]+([0-9\.]+)/', $versionfile, $matches);
    return $matches[1] ?? '¿?';
}

/**
 * Cleans the MDL name by removing extra dashes and adding 'MDL-' prefix.
 *
 * @param string $entry The MDL name to clean.
 * @return string The cleaned MDL name.
 */
function clean_mdl_name(string $entry): string {
    $parts = explode('-', $entry, 3);
    if (count($parts) > 2)
        $entry = $parts[0] . '-' . $parts[1];
    $MDL = str_replace('MDL-', 'MDL', $entry);
    return str_replace('MDL', 'MDL-', $MDL);
}

/**
 * Checks if a given entry is an MDL.
 *
 * @param string $entry The entry to check.
 * @return bool True if the entry is an MDL, false otherwise.
 */
function is_an_mdl(string $entry): bool {
    return preg_match('/MDL\-\d+/', $entry);
}

/**
 * Retrieves the icon URL for an instance.
 *
 * @param stdClass $entry The instance entry.
 * @return string The icon URL.
 */
function instance_icon_url($entry): string {
    global $CFG;

    if (isset($CFG->custom) && method_exists($CFG->custom, 'instance_icon_url')) {
        return $CFG->custom->instance_icon_url($entry);
    }

    $base = $CFG->wwwpix . '/';
    if (file_exists($CFG->dirroot . '/pix/' . $entry->name . '.png')) {
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

/**
 * Scans the instances and returns an array of instance information.
 *
 * @return array An array of instance information.
 */
function scan_instances(): array {
    global $CFG;

    $baseversion = get_version_from_file($CFG->maininstance . "/public/version.php");

    $instances = [];
    if ($handle = opendir('./m')) {
        while (false !== ($entry = readdir($handle))) {
            $info = entry_info($entry, $baseversion);
            if ($info) {
                $instances[$entry] = $info;
            }
        }
        closedir($handle);
    }
    ksort($instances);
    return $instances;
}

function instance_type($entry) {
    if (strpos($entry, 'int') === 0) {
        return 'int';
    }
    if (strpos($entry, 'master') === 0 || strpos($entry, 'main') === 0) {
        return 'stable';
    }
    if (strpos($entry, 'stable_') === 0) {
        return 'stable';
    }
    if (strpos($entry, 'MDL') === 0) {
        return 'MDL';
    }
    return 'other';
}

/**
 * Retrieves information about an entry.
 *
 * @param string $entry The entry to get information for.
 * @param string $baseversion The base version number.
 * @return stdClass|null The entry information object, or null if the entry is invalid.
 */
function entry_info(string $entry, string $baseversion): ?stdClass {
    global $CFG;

    if ($entry == "." || $entry == ".." || $entry == "mdk") {
        return null;
    }

    $info = (object) [
        'title' => "¿$entry?",
        'name' => $entry,
        'mdl' => '',
        'type' => instance_type($entry),
        'idelink' => 'vscode://file' . realpath($CFG->moodlesdir . '/' . $entry),
    ];

    if (is_an_mdl($entry)) {
        $info->mdl = clean_mdl_name($info->name);
    }

    $gitpath = $CFG->moodlesdir . '/' . $entry . '/.git/HEAD';
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

    $versionpaths = [
        "{$CFG->moodlesdir}/{$entry}/version.php",
        "{$CFG->moodlesdir}/{$entry}/public/version.php",
    ];
    $info->version = '¿?';
    $info->rebase = false;
    foreach ($versionpaths as $versionpath) {
        if (file_exists($versionpath)) {
            $info->version = get_version_from_file($versionpath);
            $info->rebase = ($info->version < $baseversion);
        }
    }

    $output = [];
    $command = "cd {$CFG->moodlesdir}/{$entry} ; git status --porcelain";
    exec($command, $output);
    if (!empty($output)) {
        $info->dirty = $output;
    }

    return $info;
}

/**
 * Retrieves information about instances.
 *
 * @return stdClass|array The instances information.
 */
function get_instances_info() {
    global $_GET, $CFG;

    $refresh = $_GET['refresh'] ?? false;

    if (!class_exists('Redis')) {
        return scan_instances();
    }

    $redis = new Redis();
    $redis->connect($CFG->redisip, $CFG->redisport);
    try {
        $json = $redis->get($CFG->rediscachename);
        $instances = json_decode($json);
    } catch (\Throwable $th) {
        $instances = [];
    }

    if (empty($instances) || $refresh) {
        $instances = scan_instances();
        $redis->setex($CFG->rediscachename, $CFG->rediscachetime, json_encode($instances));
    }

    return $instances;
}
