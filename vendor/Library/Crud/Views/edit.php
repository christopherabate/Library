<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, Chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $field => $error): ?>
                <li><a href="#<?php echo $field ?>"><?php echo $error['name'] ?></a> <?php echo $error['message'] ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <?php echo $form ?>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
    (function($){
        $('body').on('submit', 'form', function(e) {
            form = $(this);
            
            $.post(form.attr('action'), form.serialize())
            .done(function(data) {
                window.location.href = <?php echo APP_ROOT ?>+data;
            })
            .fail(function(data) {
                form.replaceWith(data.responseText);
            });
            
            e.preventDefault();
            return false;
        });
    })(jQuery);
    </script>

</body>
</html>
