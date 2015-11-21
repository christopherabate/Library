<div>
    <label for="<?php echo $name ?>"><?php echo $label ?></label>
    <div>
        <textarea <?php echo $required ?> id="<?php echo $name ?>" <?php echo ($placeholder) ? 'placeholder="'.$placeholder.'"' : null ?> name="<?php echo $form ?>[<?php echo $name ?>]" rows="10"><?php echo $value ?></textarea>

        <?php echo ($error) ? '<p>'.$error.'</p>' : null ?>
        <?php echo ($help) ? '<p>'.$help.'</p>' : null ?>
    </div>
</div>
