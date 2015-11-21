<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, Chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

    <table>
        <tr>
            <?php foreach ($model as $column => $attribute): ?>
                <th><?php echo (isset($attribute['display'])) ? $attribute['display'] : $column ?></th>
            <?php endforeach ?>
        </tr>
        <tr>
            <?php foreach ($model as $attribute): ?>
                <td><?php echo $attribute['value'] ?></td>
            <?php endforeach ?>
        </tr>
    </table>

</body>
</html>
