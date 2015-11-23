<div>
    <label for="<?php echo $name ?>"><?php echo $label ?></label>
    <div>
        <input type="email" <?php echo $required ?> id="<?php echo $name ?>" <?php echo ($placeholder) ? 'placeholder="'.$placeholder.'"' : null; ?> name="<?php echo $form ?>[<?php echo $name ?>]" value="<?php echo $value ?>" />

        <?php echo ($error) ? '<p>'.$error.'</p>' : null ?>
        <?php echo ($help) ? '<p>'.$help.'</p>' : null ?>
    </div>
</div>
