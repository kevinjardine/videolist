<?php
/**
 * Videolist plugin settings
 */

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:transcode:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:transcode:description').'</p>';
echo elgg_view('input/radio', array(
	'name' => 'params[transcode]',
	'options' => array(
		elgg_echo('option:no') => 'no',
		elgg_echo('option:yes') => 'yes'
	),
	'value' => $vars['entity']->transcode?$vars['entity']->transcode:'no',
));
echo '</div>';

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:ffmpeg_location:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:ffmpeg_location:description').'</p>';
echo elgg_view('input/text', array(
	'name' => 'params[ffmpeg_location]',
	'value' => $vars['entity']->ffmpeg_location,
));
echo '</div>';

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:wget_location:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:wget_location:description').'</p>';
echo elgg_view('input/text', array(
    'name' => 'params[wget_location]',
    'value' => $vars['entity']->wget_location,
));
echo '</div>';

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:thumbnail_command:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:thumbnail_command:description').'</p>';
echo elgg_view('input/text', array(
    'name' => 'params[thumbnail_command]',
    'value' => $vars['entity']->thumbnail_command,
));
echo '</div>';

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:poster_command:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:poster_command:description').'</p>';
echo elgg_view('input/text', array(
    'name' => 'params[poster_command]',
    'value' => $vars['entity']->poster_command,
));
echo '</div>';

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:webm_command:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:webm_command:description').'</p>';
echo elgg_view('input/text', array(
    'name' => 'params[webm_command]',
    'value' => $vars['entity']->webm_command,
));
echo '</div>';

echo '<div>';
echo '<h3>'.elgg_echo('videolist:settings:h264_command:title').'</h3>';
echo '<p>'.elgg_echo('videolist:settings:h264_command:description').'</p>';
echo elgg_view('input/text', array(
    'name' => 'params[h264_command]',
    'value' => $vars['entity']->h264_command,
));
echo '</div>';
