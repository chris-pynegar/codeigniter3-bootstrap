<div class="page-header">
    <h1><?php echo ! empty($user) ? 'Edit' : 'Create'; ?> User</h1>
</div>
<?php $this->template->component('flashdata'); ?>
<?php echo $form; ?>