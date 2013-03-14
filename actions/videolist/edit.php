<?php
/**
 * Create or edit a video
 *
 * @package ElggVideolist
 */

$variables = elgg_get_config('videolist');
$input = array();
foreach ($variables as $name => $type) {
    $should_filter_input = ($name !== 'video_url');
	$input[$name] = get_input($name, null, $should_filter_input);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

//error_log(print_r($_FILES,TRUE));

$allow_transcoding = elgg_get_plugin_setting('transcode','videolist') == 'yes';
// TODO - handle an upload error
if ($allow_transcoding && isset($_FILES)) {
  if (isset($_FILES['video_file']) && ($_FILES['video_file']['error'] === UPLOAD_ERR_OK)) {
    $video_file = $_FILES['video_file'];
  } else {
    $video_file = FALSE;
  }

  if (isset($_FILES['thumbnail']) && ($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK)) {
    $thumbnail_file = $_FILES['thumbnail'];
  } else {
    $thumbnail_file = FALSE;
  }
} else {
  $video_file = FALSE;
  $thumbnail_file = FALSE;
}

// Get guids
$video_guid = (int)get_input('video_guid');
$container_guid = (int)get_input('container_guid');

elgg_make_sticky_form('videolist');

elgg_load_library('elgg:videolist');

if (!$video_guid) {
  if (!$video_file) {
  	$input['video_url'] = elgg_trigger_plugin_hook('videolist:preprocess', 'url', $input, $input['video_url']);

  	if (!$input['video_url']) {
  		register_error(elgg_echo('videolist:error:no_url'));
  		forward(REFERER);
  	}

  	$attributesPlatform = videolist_parse_url($input['video_url']);

  	if (!$attributesPlatform) {
  		register_error(elgg_echo('videolist:error:invalid_url'));
  		forward(REFERER);
  	}
  	list ($attributes, $platform) = $attributesPlatform;
  	/* @var Videolist_PlatformInterface $platform */

  	$attributes = array_merge($attributes, $platform->getData($attributes));

  	$input = array_merge($attributes, $input);
  }
} else {
	unset($input['video_url']);
}

if ($video_guid) {
	$video = get_entity($video_guid);
	if (!$video || !$video->canEdit()) {
		register_error(elgg_echo('videolist:error:no_save'));
		forward(REFERER);
	}
	$new_video = false;
} else {
	$video = new ElggObject();
	$video->subtype = 'videolist_item';
	$new_video = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$video->$name = $value;
	}
}

$video->container_guid = $container_guid;

if ($video->save()) {

	elgg_clear_sticky_form('videolist');

	if ($video_file) {
	  videolist_transcode($video,$video_file['tmp_name']);
	} else {
	  // Let's save the thumbnail in the data folder
	  if ($thumbnail_file) {
	    $thumb_url = $thumbnail_file['tmp_name'];
	  } else {
      $thumb_url = $video->thumbnail;
	  }
    if ($thumb_url) {
        $thumbnail = file_get_contents($thumb_url);
        if ($thumbnail) {
            $prefix = "videolist/" . $video->guid;
            $filehandler = new ElggFile();
            $filehandler->owner_guid = $video->owner_guid;
            $filehandler->setFilename($prefix . ".jpg");
            $filehandler->open("write");
            $filehandler->write($thumbnail);
            $filehandler->close();
        }
    }
	}

	system_message(elgg_echo('videolist:saved'));

	if ($new_video) {
		add_to_river('river/object/videolist_item/create', 'create', elgg_get_logged_in_user_guid(), $video->guid);
	}

	forward($video->getURL());
} else {
	register_error(elgg_echo('videolist:error:no_save'));
	forward(REFERER);
}
