<?php
# The purpose of this template is to force the download of
# a files by setting an arbitrary MIME type.
header('Content-type: application/octet-stream');
header('Content-description: File Transfer');
header("Content-disposition: attachement; filename=$fname");
header("Content-length: $fsize");
readfile($path);
die();
