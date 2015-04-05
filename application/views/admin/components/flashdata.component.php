<?php if ( ! empty($flash_success)): ?>
    <div class="alert alert-success">
        <?php echo $flash_success; ?>
    </div>
<?php endif; ?>

<?php if ( ! empty($flash_error)): ?>
    <div class="alert alert-danger">
        <?php echo $flash_error; ?>
    </div>
<?php endif; ?>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php echo validation_errors('<li>', '</li>'); ?>
        </ul>
    </div>
<?php endif; ?>
