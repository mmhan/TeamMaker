<?php
$name = $member['Member']['name'];
$email = $member['Member']['email'];
$projName = $project['Project']['name'];
$projDesc = $project['Project']['description']; 
$deadline = date("d-m-Y H:i:s", strtotime($project['Project']['collection_end']))
?>
Hi <?php echo $name ?>,

We have recently added you to a TeamMaker project called "<?php echo $projName ?>". Please see below for details. 

Details : <?php echo $projDesc ?> 

You may log in to the system at <?php echo Router::url("/users/login", true); ?>.

Please take note that you are required to fill in your data for the project before <?php echo $deadline ?>.

----
This is a system generated email. Do not reply. 