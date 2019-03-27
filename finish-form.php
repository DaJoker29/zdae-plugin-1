<?php

function siteExists($alias) {
    $args = array(
        'path' => '/'.$alias.'/',
        'count' => true
    );
    $result = get_sites($args);
    if ( $result > 0 ) {
        return true;
    } else {
        return false;
    }
}
if ( isset($alias) && !siteExists($alias) ) {
?>
<div>
    <p>Your current alias is <strong><?php echo $alias; ?></strong></p>
</div>
<form action="<?php echo admin_url( 'admin-post.php'); ?>" method="post">

<div>
    <label for="phone"><?php echo get_site_option('zdae_network_phone_label', 'Display your phone number?'); ?></label>
    <input type="radio" name="phone" value="true" checked="checked">Yes</input>
    <input type="radio" name="phone" value="false">No</input>
</div>

<div>
    <label for="email">Display email?</label>
    <input type="radio" name="email" value="true" checked="checked">Yes</input>
    <input type="radio" name="email" value="false">No</input>
</div>

<br>

<div>
    <input type="hidden" name="action" value="zdae_replicator_action" />
    <input type="hidden" name="alias" value="<?php echo $alias; ?>" />
    <input type="submit" value="Create Website" />
</div>
</form>
<?php } elseif ( isset($alias) ) { 
    $url = get_site_url(get_current_blog_id(), $alias);
?>
<div>
    <p>The site already exists.</p>
    <p><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
</div>
<?php } else { ?>
<div>
    <p>No user alias provided.</p>
</div>
<?php }
