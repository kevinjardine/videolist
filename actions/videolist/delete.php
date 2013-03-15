<?php
/**
* Elgg videolist item delete
*
* @package ElggVideolist
*/

$guid = (int) get_input('guid');

$videolist_item = get_entity($guid);
if (!$videolist_item->guid) {
	register_error(elgg_echo("videolist:deletefailed"));
	forward('videolist/all');
}

if (!$videolist_item->canEdit()) {
	register_error(elgg_echo("videolist:deletefailed"));
	forward($videolist_item->getURL());
}

$container = $videolist_item->getContainerEntity();
$url = $videolist_item->getURL();
$allow_transcoding = elgg_get_plugin_setting('transcode','videolist') == 'yes';
elgg_load_library('elgg:videolist');
$thumbnail_file = videolist_get_thumbnail_output_name($videolist_item);
if ($allow_transcoding) {
  $poster_file = videolist_get_poster_output_name($videolist_item);
  $webm_file = videolist_get_webm_output_name($videolist_item);
  $h264_file = videolist_get_h264_output_name($videolist_item);
  $orig_file = videolist_get_orig_output_name($videolist_item);
}
if (!$videolist_item->delete()) {
	register_error(elgg_echo("videolist:deletefailed"));
} else {
  // tidy up by deleting the thumbnail and related files
  unlink($thumbnail_file);
  if ($allow_transcoding) {
    unlink($poster_file);
    unlink($webm_file);
    unlink($h264_file);
    unlink($orig_file);
  }
	system_message(elgg_echo("videolist:deleted"));
}

// we can't come back to video url because it's deleted
if($url != $_SERVER['HTTP_REFERER']) {
	forward(REFERER);
}

if (elgg_instanceof($container, 'group')) {
	forward("videolist/group/$container->guid/all");
} else {
	forward("videolist/owner/$container->username");
}
