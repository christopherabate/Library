<div>
    <label for="<?php echo $name ?>"><?php echo $label ?></label>
    <div>
        <input type="range" min="<?php echo $minimum ?>" max="<?php echo $maximum ?>" step="<?php echo $step ?>" <?php echo $required ?> id="<?php echo $name ?>" <?php echo ($error) ? 'placeholder="'.$placeholder.'"' : null ?> name="<?php echo $form ?>[<?php echo $name ?>]" value="<?php echo $value ?>" />

        <?php echo ($error) ? '<p>'.$error.'</p>' : null ?>
        <?php echo ($help) ? '<p>'.$help.'</p>' : null ?>
    </div>
</div>
