<p style="text-align: right;">::FULL_NAME::</p>
<?php 
if ( !(get_option('zdae_show_email') === "false")) { ?>
<p style="text-align: right;">::HOME_EMAIL::</p>
<?php } 
if ( !(get_option('zdae_show_phone') === "false")) { ?>
<p style="text-align: right;">::HOME_PHONE::</p>
<?php }
