<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, Chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
    
    <?php if (!empty($flash)): ?>
        <?php foreach ($flash as $type => $message): ?>
            <div><strong><?php echo $type ?></strong> <?php echo $message ?></div>
        <?php endforeach ?>
    <?php endif ?>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $field => $error): ?>
                <li><a href="#<?php echo $field ?>"><?php echo $error['name'] ?></a> <?php echo $error['message'] ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <?php echo $form ?>

</body>
</html>
