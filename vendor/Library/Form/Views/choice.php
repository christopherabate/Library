<div>
    <fieldset>
        <legend><?php echo $label ?></legend>

        <?php foreach ($list as $list_value => $list_display): ?>
            <div>
                <label for="<?php echo $name.'_'.$list_value ?>">
                    <input type="radio" <?php echo ($value == $list_value) ? 'checked' : null ?> <?php echo $required ?> id="<?php echo $name.'_'.$list_value ?>" name="<?php echo $form ?>[<?php echo $name ?>]" value="<?php echo $list_value ?>" />
                    <?php echo $list_display ?>
                </label>
            </div>
        <?php endforeach ?>

        <?php echo ($error) ? '<p>'.$error.'</p>' : null ?>
        <?php echo ($help) ? '<p>'.$help.'</p>' : null ?>
    </fieldset>
</div>
