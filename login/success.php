<?php if ($success) : ?>
    <div class="success">
        <?php foreach ($success as $success) : ?>
            <p> <?php echo $success ?></p>
        <?php endforeach  ?>
    </div>
<?php endif ?>