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

    <table>
        <tr>
            <?php foreach (current($index) as $column => $attribute): ?>
                <th><?php echo (isset($attribute['display'])) ? $attribute['display'] : $column ?></th>
            <?php endforeach ?>
        </tr>
        <?php foreach ($index as $model): ?>
            <tr>
                <?php foreach ($model as $attribute): ?>
                    <td><?php echo $attribute['value'] ?></td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>

</body>
</html>
