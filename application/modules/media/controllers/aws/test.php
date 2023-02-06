<?php
$REGION="curl http://169.254.169.254/latest/dynamic/instance-identity/document|grep region|awk -F\" '{print $4}'";

echo $REGION

?>