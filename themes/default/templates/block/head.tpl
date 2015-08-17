<meta charset="utf-8" />
<title>
    {if $T_CONFIGURATION.site_title}{$T_CONFIGURATION.site_title}{else}{$smarty.const._MAGESTERNAME}{/if}
    {if $T_CONFIGURATION.site_subtitle} | {$T_CONFIGURATION.site_subtitle}{/if}
</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<meta name="MobileOptimized" content="320">

<link rel="icon" type="image/ico" href="{Plico_GetResource file='images/favicon.ico'}" />

{foreach item="css" from=$T_STYLESHEETS}
	<link rel="stylesheet" href="{Plico_GetResource file=$css}" />
{/foreach}

<link rel="shortcut icon" href="favicon.ico" />

<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_HTMLorMML-full"></script>

<!-- THE VIDEO JS *MUST* BE ON HEAD TAG!! MAKE A WAY TO INJECT IT HERE -->
<script src="{Plico_GetResource file='plugins/videojs/video.js'}"></script>
<script>
	videojs.options.flash.swf = "{Plico_GetResource file='plugins/videojs/video-js.swf'}";
</script>
<!--
<script src="{Plico_GetResource file='plugins/videojs/youtube.js'}"></script>
-->
<style type="text/css">
/* RESPONSIVE VIDEO */
.videocontent {
    width: 100%;
    margin: 0 auto;
}
.video-js {
    padding-top: 56.25%;
}
.vjs-fullscreen {
    padding-top: 0px;
}
.vjs-poster {
    position: absolute;
    top:0;
}
</style>
