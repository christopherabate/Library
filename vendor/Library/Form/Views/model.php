<div>
    <label for="<?php echo $name ?>"><?php echo $label ?></label>
    <div>
        <select id="<?php echo $name ?>" name="<?php echo $form ?>[<?php echo $name ?>]" >

            <?php echo (!$required) ? '<option value="">&hellip;</option>' : null ?>

            <?php foreach ($models as $model): ?>
                <option <?php echo ($value == $model[$unique_column]['value']) ? 'selected' : null ?> value="<?php echo $model[$unique_column]['value'] ?>"><?php echo $model[$value_column]['value'] ?></option>
            <?php endforeach ?>

        </select>

        <?php echo ($error) ? '<p>'.$error.'</p>' : null ?>
        <?php echo ($help) ? '<p>'.$help.'</p>' : null ?>
    </div>
</div>
