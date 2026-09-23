<?php
require_once('../lib/setup.php');

setCfg();

ini_set('default_socket_timeout', 1);

function get_jira_info($entry) {
  global $CFG;
  $info = new stdClass();
  $info->title = '¡' . $entry . '!';
  // if we hace more than 2 - delete them
  $parts = explode('-', $entry, 3);
  if (count($parts) > 2)
    $entry = $parts[0] . '-' . $parts[1];
  // grant we get a clean MDL-XXXX ID
  $MDL = str_replace('MDL-', 'MDL', $entry);
  $MDL = str_replace('MDL', 'MDL-', $MDL);
  // Generater stub info just in case
  $info->url = 'https://tracker.moodle.org/browse/' . $entry;
  $info->icon = $CFG->wwwpix . '/help.gif';
  // get info
  $url = "https://tracker.moodle.org/rest/api/latest/issue/$MDL?fields=summary,status";
  $response = file_get_contents($url);
  if (empty($response)) {
    return $info;
  }
  // mount info
  $data = json_decode($response);
  $info->title = $data->fields->summary ?? '¿' . $entry . '?';
  $info->url = "https://tracker.moodle.org/browse/$MDL";
  $info->status = $data->fields->status->name ?? '¿?';
  $info->icon = $data->fields->status->iconUrl ?? 'sfd';
  return $info;
}

if (!isset($_GET['mdl'])) {
  die();
}

$info = get_jira_info($_GET['mdl']);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($info);
