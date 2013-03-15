<?php
elgg_load_js('mediaelement');
elgg_load_css('mediaelement');
$video = $vars['entity'];
$poster_file = elgg_get_site_url().'videolist/download/'.$video->guid.'/poster';
$h264_file = elgg_get_site_url().'videolist/download/'.$video->guid.'/h264';
$webm_file = elgg_get_site_url().'videolist/download/'.$video->guid.'/webm';
$width = $vars['width'];
$height = $vars['height'];
$vendor_dir = elgg_get_site_url().'mod/videolist/vendor/mediaelement';
$no_capabilities = elgg_echo('videolist:no_capabilities');

echo <<< HTML
<video width="$width" height="$height" poster="$poster_file.jpg" controls="controls" preload="none">
  <source type="video/mp4" src="$h264_file.mp4" />
  <source type="video/webm" src="$webm_file.webm" />
  <!-- Fallback flash player for no-HTML5 browsers with JavaScript turned off -->
  <object width="$width" height="$height" type="application/x-shockwave-flash" data="$vendor_dir/flashmediaelement.swf">
		<param name="movie" value="$vendor_dir/flashmediaelement.swf" />
		<param name="flashvars" value="controls=true&amp;file=$h264_file.mp4" />
		<!-- Image fall back for non-HTML5 browser with JavaScript turned off and no Flash player installed -->
		<img src="$poster_file.jpg" width="$width" height="$height" alt="$no_capabilities" title="$no_capabilities" />
	</object>
</video>
<script>
  $('video').mediaelementplayer();
</script>
HTML;
