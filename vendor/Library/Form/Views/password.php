<div>
    <label for="<?php echo $name ?>"><?php echo $label ?></label>
    <div>
        <input type="password" <?php echo $required ?> id="<?php echo $name ?>" name="<?php echo $form ?>[<?php echo $name ?>]" />
        <input type="password" <?php echo $required ?> id="<?php echo $name ?>_confirm" name="<?php echo $form ?>[<?php echo $name ?>_confirm]" />

        <?php echo ($error) ? '<p>'.$error.'</p>' : null ?>
        <?php echo ($help) ? '<p>'.$help.'</p>' : null ?>
    </div>
</div>
