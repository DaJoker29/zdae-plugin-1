<div class="wrap">
<h2>Network Replication Settings</h2>
<form method="POST">

    <fieldset>
    <label for="phone_label">Phone Label</label>
    <input type="text" name="phone_label" id="phone_label" value="<?php echo $phone_label; ?>">
    </fieldset>

    <fieldset>
    <label for="email_label">Email Label</label>
    <input type="text" name="email_label" id="email_label" value="<?php echo $email_label; ?>">
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
