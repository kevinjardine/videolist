<?php
/**
 * Elgg videolist english language pack.
 *
 * @package ElggVideolist
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'videolist' => "Videos",
	'videolist:owner' => "%s's videos",
	'videolist:friends' => "Friends' videos",
	'videolist:all' => "All site videos",
	'videolist:add' => "Add video",

	'videolist:group' => "Group videos",
	'groups:enablevideolist' => 'Enable group videos',

	'videolist:edit' => "Edit this video",
	'videolist:delete' => "Delete this video",

	'videolist:new' => "A new video",
	'videolist:notification' =>
'%s added a new video:

%s
%s

View and comment on the new video:
%s
',
	'videolist:delete:confirm' => 'Are you sure you want to delete this video?',
	'item:object:videolist_item' => 'Video',
	'videolist:nogroup' => 'This group does not have any video yet',
	'videolist:more' => 'More videos',
	'videolist:none' => 'No videos posted yet.',

	/**
	* River
	**/

	'river:create:object:videolist_item' => '%s created the video %s',
	'river:update:object:videolist_item' => '%s updated the video %s',
	'river:comment:object:videolist_item' => '%s commented on the video titled %s',

	/**
	 * Form fields
	 */

	'videolist:title' => 'Title',
	'videolist:description' => 'Description',
	'videolist:video_url' => 'Enter video URL',
	'videolist:access_id' => 'Who can see you posted this video?',
	'videolist:tags' => 'Add tags',

	/**
	 * Status and error messages
	 */
	'videolist:error:no_save' => 'There was an error in saving the video, please try after sometime',
	'videolist:error:no_url' => 'You must provide a video URL.',
	'videolist:saved' => 'Your video has been saved successfully!',
	'videolist:deleted' => 'Your video was removed successfully!',
	'videolist:deletefailed' => 'Unfortunately, this video could not be removed now. Please try again later',


	/**
	 * Widget
	 **/

	'videolist:num_videos' => 'Number of videos to display',
	'videolist:widget:description' => 'Your personal video playlist.',
	'videolist:continue' => "Continue",

	/*
	 * Transcoding
	 */

  'videolist:or' => '-- OR --',
  'videolist:video_upload' => 'Upload video file',
  'videolist:downloadfailed' => 'Cannot download video',
  'videolist:invalidtype' => 'Video has invalid type',
  'videolist:processing' => 'Your video is currently being processed. Please reload this page in a minute or two.',
  'videolist:transcode_failed' => 'Your video could not be processed. Please check the file you uploaded and try again or contact the site administrator.',
  'videolist:replace_thumbnail' => "Replace thumbnail (should be jpg)",
  'videolist:no_capabilities' => 'No video playback capabilities',

	/*
	 * Settings
	 */

'videolist:settings:transcode:title' => 'Support transcoding',
'videolist:settings:transcode:description' => 'Support uploading and transcoding videos using ffmpeg.',
'videolist:settings:ffmpeg_location:title' => 'Location for ffmpeg',
'videolist:settings:ffmpeg_location:description' => 'If you are supporting transcoding, this is the location of the ffmpeg binary on your file system.',
'videolist:settings:wget_location:title' => 'Location for wget',
'videolist:settings:wget_location:description' => 'If you are supporting transcoding, this is the location of the wget binary on your file system.',
'videolist:settings:thumbnail_command:title' => 'Thumbnail command',
'videolist:settings:thumbnail_command:description' => 'If you are supporting transcoding, this is the command string to allow ffmpeg to create video thumbnails.',
'videolist:settings:poster_command:title' => 'Poster command',
'videolist:settings:poster_command:description' => 'If you are supporting transcoding, this is the command string to allow ffmpeg to create video posters (full size images used to represent a video before it plays).',
'videolist:settings:webm_command:title' => 'WebM command',
'videolist:settings:webm_command:description' => 'If you are supporting transcoding, this is the command string to allow ffmpeg to create WebM (webm) videos.',
'videolist:settings:h264_command:title' => 'H.264 command',
'videolist:settings:h264_command:description' => 'If you are supporting transcoding, this is the command string to allow ffmpeg to create H.264 (mp4) videos.',

);

add_translation("en", $english);
