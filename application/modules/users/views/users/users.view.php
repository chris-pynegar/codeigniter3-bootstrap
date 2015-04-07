<div class="page-header">
    <h1>Users</h1>
</div>
<?php $this->template->component('flashdata'); ?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a href="<?php echo admin_url('users/create'); ?>"><i class="fa fa-plus"> </i><span>Add User</span></a></li>
        </ul>
        <form class="navbar-form navbar-right">
            <div class="input-group">
                <input type="text" name="search" placeholder="Search Users" class="form-control"/>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
    </div>
</nav>
<?php if ( ! empty($users)): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user->id; ?></td>
                    <td><?php echo $user->username; ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Actions <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo admin_url('users/edit/'.$user->id); ?>">Edit</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo admin_url('users/delete/'.$user->id); ?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php $this->template->component('pagination', array('pagination_url' => 'admin/users')); ?>
<?php else: ?>
<p class="text-center">No users found.</p>
<?php endif; ?>
