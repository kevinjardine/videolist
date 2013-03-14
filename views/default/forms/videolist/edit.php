<?php
/**
 * Videolist edit form body
 *
 * @package ElggVideolist
 */

elgg_load_js('elgg.videolist');

$variables = elgg_get_config('videolist');

unset($variables['video_url']);

$vars['videolist_variables'] = $variables;

$input_bit = elgg_view('videolist/input_bit',$vars);

if(empty($vars['guid'])){
	// add title and description fields in a hidden section to be revealed later by JS
	// and videotype, thumbnail as hidden fields
?>
<div>
<label><?php echo elgg_echo("videolist:video_url") ?></label><br />
<?php
	echo elgg_view("input/text", array(
		'name' => 'video_url',
		'value' => $vars['video_url'],
	));
?>
</div>
<?php
$allow_transcoding = elgg_get_plugin_setting('transcode','videolist') == 'yes';
if ($allow_transcoding) {
?>
<div>
<label><?php echo elgg_echo("videolist:or") ?></label>
</div>
<div>
<label><?php echo elgg_echo("videolist:video_upload") ?></label><br />
<?php
	echo elgg_view("input/file", array(
		'name' => 'video_file',
	));
?>
</div>
<?php
}
?>
<div id="videolist-metadata" class="hidden">
	<?php echo $input_bit;?>
</div>
<?php
} else {
	echo $input_bit;
	$video = get_entity($vars['guid']);
	if ($video->videotype === 'uploaded') {
  // let the user replace the existing thumbnail
?>
<div>
	<label><?php echo elgg_echo('videolist:replace_thumbnail') ?></label>
	<?php echo elgg_view("input/file", array(
			'name' => 'thumbnail',
		));
	?>
</div>
<?php
  }
}

$cats = elgg_view('categories', $vars);
if (!empty($cats)) {
	echo $cats;
}

echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'video_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if(empty($vars['guid'])){
	echo elgg_view('input/submit', array('id'=>'videolist-continue-button','value' => elgg_echo('videolist:continue')));
	echo elgg_view('input/submit', array('id'=>'videolist-submit-button','value' => elgg_echo('save'),'style'=>'display:none'));
} else {
	echo elgg_view('input/submit', array('id'=>'videolist-submit-button','value' => elgg_echo('save')));
}

echo '</div>';
