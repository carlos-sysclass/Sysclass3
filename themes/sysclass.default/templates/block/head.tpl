<meta charset="utf-8" />
<title>{if $T_CONFIGURATION.site_name}{$T_CONFIGURATION.site_name}{else}{$smarty.const._MAGESTERNAME}{/if} | {if $T_CONFIGURATION.site_motto}{$T_CONFIGURATION.site_motto}{else}{$smarty.const._THENEWFORMOFADDITIVELEARNING}{/if}</title>
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
