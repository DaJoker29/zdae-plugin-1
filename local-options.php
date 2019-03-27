<div class="wrap">
<h2>Site Replication Settings</h2>
<?php if ( $phone === "true" ) { ?>
    <h3>The phone number is currently visible.</h3>
<?php } else { ?>
    <h3>The phone number is currently hidden.</h3>
<?php } ?>
<?php if ( $email === "true" ) { ?>
    <h3>The email address is currently visible.</h3>
<?php } else { ?>
    <h3>The email address is currently hidden.</h3>
<?php } ?>
<form method="POST">
    <fieldset>
    <label for="phone">Display phone?</label>
    <input type="radio" name="phone" value="true" <?php if ($phone === "true") echo "checked='checked'"; ?>>Yes</input>
    <input type="radio" name="phone" value="false" <?php if ($phone === "false") echo "checked='checked'"; ?>>No</input>
    </fieldset>

    <fieldset>
    <label for="email_value">Display email?</label>
    <input type="radio" name="email" value="true" <?php if ($email === "true") echo "checked='checked'"; ?>>Yes</input>
    <input type="radio" name="email" value="false" <?php if ($email === "false") echo "checked='checked'"; ?>>No</input>
    </fieldset>

    <br>

    <input type="submit" value="Save" class="button button-primary button-large">
</form>
<br>
<form method="POST">
<input type="hidden" name="reset" value="true">
<input type="submit" value="Reset" class="button button-secondary button-large">
</form>
</div>
