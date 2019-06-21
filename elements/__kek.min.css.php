<?php
$css = gzencode(get_global_val("Kek.min.css"));
header("Cache-Control: max-age=300");
header("Content-Encoding: gzip");
header("Content-Length: ".strlen($css));
header("Content-Type: text/css");
if(!empty($css)){echo $css;}
exit;