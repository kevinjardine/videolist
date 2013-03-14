<?php

/**
 * @param array $hook_params
 * @return array
 */
function videolist_get_regular_platforms(array $hook_params) {
	$platforms = array();
	$path = dirname(dirname(__FILE__)) . '/classes/Videolist/Platform';
	foreach (scandir($path) as $filename) {
		if (preg_match('/^(\\w+)\\.php$/', $filename, $m)) {
			$class = 'Videolist_Platform_' . $m[1];
			$platform = new $class();
			if ($platform instanceof Videolist_PlatformInterface) {
				/* @var Videolist_PlatformInterface $platform */
				$platforms[$platform->getType()][] = $platform;
			}
		}
	}
	$platforms = elgg_trigger_plugin_hook('videolist:prepare', 'platforms', $hook_params, $platforms);
	return $platforms;
}

/**
 * @param array $platforms
 * @param string $url
 * @return array|bool
 */
function videolist_find_matching_platform(array $platforms, $url) {
	foreach ($platforms as $type => $list) {
		/* @var Videolist_PlatformInterface[] $list */
		foreach ($list as $platform) {
			$attributes = $platform->parseUrl($url);
			if ($attributes) {
				$attributes['videotype'] = $type;
				return array($attributes, $platform);
			}
		}
	}
	return false;
}

/**
 * @param string $url
 * @return array [array $attributes, Videolist_PlatformInterface $platform]
 */
function videolist_parse_url($url) {
	$params = array(
		'url' => $url,
	);
	$platforms = videolist_get_regular_platforms($params);
	if ($match = videolist_find_matching_platform($platforms, $url)) {
		return $match;
	}
	/* @var Videolist_PlatformInterface[] $list */
	$platforms = array();
	$list = (require dirname(__FILE__) . '/oembed_list.php');
	$list = elgg_trigger_plugin_hook('videolist:prepare', 'oembed_list', $params, $list);
	foreach ($list as $item) {
		// create only oembed platforms that will match
		if (preg_match($item[2], $url)) {
			$platform = new Videolist_OembedPlatform($item[0], $item[2], new Videolist_OembedService($item[1]));
			$platforms[$platform->getType()][] = $platform;
		}
	}
	if ($match = videolist_find_matching_platform($platforms, $url)) {
		return $match;
	}
	return false;
}

function videolist_get_flash_output_name($video) {
  $file = new ElggFile();
	$file->owner_guid = $video->owner_guid;
	$file->setFilename("videolist/vids/{$video->guid}.flv");
	return $file->getFilenameOnFilestore();
}

function videolist_get_h264_output_name($video) {
  $file = new ElggFile();
  $file->owner_guid = $video->owner_guid;
  $file->setFilename("videolist/vids/{$video->guid}.mp4");
  return $file->getFilenameOnFilestore();
}

function videolist_get_orig_output_name($video) {
  $file = new ElggFile();
  $file->owner_guid = $video->owner_guid;
  $file->setFilename("videolist/vids/{$video->guid}.orig");
  return $file->getFilenameOnFilestore();
}

function videolist_get_thumbnail_output_name($video) {
  $file = new ElggFile();
  $file->owner_guid = $video->owner_guid;
  $file->setFilename("videolist/{$video->guid}.jpg");
  return $file->getFilenameOnFilestore();
}

function videolist_transcode($video, $input_file) {
  if (elgg_instanceof($video,'object','videolist_item') && file_exists($input_file)) {
    $video->videotype = 'transcoding';

    // generate the Linux shell script or Windows batch file
    // to handle the transcoding in the background
    $tfn = tempnam(sys_get_temp_dir(),'vid');
    $ffmpeg_location = elgg_get_plugin_setting('ffmpeg_location','videolist');
    $wget_location = elgg_get_plugin_setting('wget_location','videolist');
    if ($ffmpeg_location) {
      $flash_output = videolist_get_flash_output_name($video);
      // need to make sure that the appropriate videolist/vids directory already exists
      $path_parts = pathinfo($flash_output);
      $dir = $path_parts['dirname'];
      if (!file_exists($dir)) {
        mkdir($dir,0700,TRUE);
      }

      // copy the temporary file to the user's directory to make sure that it doesn't disappear
      // currently it is left there, but could be deleted in the transcode_complete step
      $new_location = videolist_get_orig_output_name($video);
      copy($input_file,$new_location);

      // generate the script commands
      $input_file = $new_location;
      $thumbnail_bit = elgg_get_plugin_setting('thumbnail_command','videolist');
      $thumbnail_bit = str_replace("[inputFile]",$input_file,$thumbnail_bit);
      $thumbnail_bit = str_replace("[outputFile]",videolist_get_thumbnail_output_name($video),$thumbnail_bit);
      $thumbnail_command = $ffmpeg_location.' '.$thumbnail_bit;

      $flash_bit = elgg_get_plugin_setting('flash_command','videolist');
      $flash_bit = str_replace("[inputFile]",$input_file,$flash_bit);
      $flash_bit = str_replace("[outputFile]",$flash_output,$flash_bit);
      $flash_command = $ffmpeg_location.' '.$flash_bit;

      $h264_bit = elgg_get_plugin_setting('h264_command','videolist');
      $h264_bit = str_replace("[inputFile]",$input_file,$h264_bit);
      $h264_bit = str_replace("[outputFile]",videolist_get_h264_output_name($video),$h264_bit);
      $h264_command = $ffmpeg_location.' '.$h264_bit;

      $update_command = $wget_location.' --spider '.elgg_get_site_url().'videolist/transcode_complete/'.$video->guid;
      $cmds = array($flash_command,$h264_command,$thumbnail_command,$update_command);

      if (substr(php_uname(), 0, 7) == "Windows") {
        $path_parts = pathinfo($tfn);
        $ntfn = $path_parts['dirname'].'/videolist_'.($video->guid).'_'.$path_parts['filename'].".bat";
        rename($tfn,$ntfn);
        // keep track of the location of the script file so we can delete it later
        $video->transcode_script_file = $ntfn;

        $script = implode("\r\n",$cmds)."\r\n";
        $f = fopen($ntfn,"wb");
        fwrite($f,$script);
        fclose($f);

        //error_log("videolist: created script $ntfn");
        pclose(popen("start /B ". $ntfn, "r"));
      }
      else {
        // assume that this is a Linux-like system
        $path_parts = pathinfo($tfn);
        $ntfn = $path_parts['dirname'].'/videolist_'.($video->guid).'_'.$path_parts['filename'].".sh";
        rename($tfn,$ntfn);
        // keep track of the location of the script file so we can delete it later
        $video->transcode_script_file = $ntfn;

        $script = implode("\n",$cmds)."\n";
        $f = fopen($ntfn,"wb");
        fwrite($f,$script);
        fclose($f);
        //error_log("videolist: created script $ntfn");

        // is it safe to assume that /bin/sh will work?
        exec("/bin/sh $ntfn > /dev/null 2>&1 &");
      }
    }
  }
}

function videolist_transcode_complete($guid) {
  $video = get_entity($guid);
  if (elgg_instanceof($video,'object','videolist_item')) {
    // sanity checks
    $thumbnail_file = videolist_get_thumbnail_output_name($video);
    $flash_file = videolist_get_flash_output_name($video);
    $h264_file = videolist_get_h264_output_name($video);
    if (file_exists($thumbnail_file) && file_exists($flash_file) && file_exists($h264_file)) {
      if (filesize($thumbnail_file) && filesize($flash_file) && filesize($h264_file)) {
        $video->videotype = "uploaded";
        $video->thumbnail = 1;
        unlink($video->transcode_script_file);
      } else {
        $video->videotype = "transcode_failed";
      }
    } else {
      $video->videotype = "transcode_failed";
    }
  }
}
