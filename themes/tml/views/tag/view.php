<!DOCTYPE html>
<htm>
<head></head>
</html>
<body style="margin:0;padding:0">
<?php 
echo $code; 
echo ".";
if($tag->analyze){
	// forensiq pixel
	echo '<img src="https://www.fqtag.com/pixel.cgi?org=2upravadave3hajasudr&p='.$imp->placements_id.'&a='.$imp->pubid.'&cmp='.$tag->id.'&fmt=banner&rt=displayImg&pfm=Platform&sl=1&fq=1" width="1" height="1" border="0" />';
}
?>
</body>