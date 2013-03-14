<?php
elgg_load_js('mediaelement');
elgg_load_css('mediaelement');
$video = $vars['entity'];
$flash_file = elgg_get_site_url().'videolist/download/'.$video->guid.'/flash';
$h264_file = elgg_get_site_url().'videolist/download/'.$video->guid.'/h264';
$width = $vars['width'];
$height = $vars['height'];
$vendor_dir = elgg_get_site_url().'mod/videolist/vendor/mediaelement';

echo <<< HTML
<video width="$width" height="$height" controls="controls" preload="none">
  <source type="video/mp4" src="$h264_file.mp4" />
  <!-- Fallback flash player for no-HTML5 browsers with JavaScript turned off -->
  <object width="$width" height="$height" type="application/x-shockwave-flash" data="$vendor_dir/flashmediaelement.swf">
		<param name="movie" value="$vendor_dir/flashmediaelement.swf" />
		<param name="flashvars" value="controls=true&amp;file=$h264_file.mp4" />
	</object>
</video>
<script>
  $('video').mediaelementplayer();
</script>
HTML;
