<?php 
//make sure this file is dropped against the client root directory
move_uploaded_file($_FILES["file"]["tmp_name"], "sites/default/files/civicrm/custom/" . $_FILES["file"]["name"]); 
?> 