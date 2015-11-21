<form id="<?php echo $name ?> "action="<?php echo $action ?>" method="<?php echo $method ?>" enctype="<?php echo $enctype ?>">
    <?php foreach ($fields as $field): ?>
        <?php echo $field ?>
    <?php endforeach ?>

    <?php foreach ($buttons as $button): ?>
        <?php echo $button ?>
    <?php endforeach ?>
</form>
