<?php
        if ( isset($alias) && !siteExists($alias) ) {
?>
<div>
    <p>Your current alias is <strong><?php echo $alias; ?></strong></p>
</div>
<form action="<?php echo admin_url( 'admin-post.php'); ?>" method="post">

<div>
    <input type="checkbox" name="show_phone" checked />
    <label for="show_phone"><?php echo get_site_option('zdae_network_phone_label', 'Display your phone number?'); ?></label>
</div>
<div>
    <input type="checkbox" name="show_email" checked />
    <label for="show_email"><?php echo get_site_option('zdae_network_email_label', 'Display your email address?'); ?></label>
</div>

<div>
    <input type="hidden" name="action" value="zdae_replicator_action" />
    <input type="hidden" name="alias" value="'.$alias.'" />
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
