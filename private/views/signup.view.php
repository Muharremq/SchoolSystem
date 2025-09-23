<?php $this->view('includes/header'); ?>


<div class="container-fluid">
    <form method="post">
        <div class="p-4 mx-auto shadow rounded" style="margin-top:50px; width: 100%; max-width: 310px;">
            <h2 class="text-center">My School</h2>
            <img src="<?= ROOT ?>/assets/logo.png" class="d-block mx-auto rounded-circle" style="width:100px">
            <h1 class="text-center">Add User</h1>


            <?php if (count($errors) > 0): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Errors</strong>
                    <?php foreach ($errors as $error): ?>
                        <br> <?= $error ?>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <input class="my-2 form-control" value="<?= get_var('firstname') ?>" type="firstname" name="firstname" placeholder="First Name">
            <input class="my-2 form-control" value="<?= get_var('lastname') ?>" type="lastname" name="lastname" placeholder="Last Name">
            <input class="my-2 form-control" value="<?= get_var('email') ?>" type="email" name="email" placeholder="Email">



            <select class="my-2 form-control" name="gender">
                <option <?= get_select('gender', '') ?> value="">Select a Gender</option>
                <option <?= get_select('gender', 'male') ?> value="male">Male</option>
                <option <?= get_select('gender', 'female') ?> value="female">Female</option>
            </select>

            <?php if ($mode == 'students'): ?>

                <input type="hidden" name="rank" value="student">

            <?php else: ?>

                <select class="my-2 form-control" name="rank">
                    <option <?= get_select('rank', '') ?> value="">Select a Rank</option>
                    <option <?= get_select('rank', 'student') ?> value="student">Student</option>
                    <option <?= get_select('rank', 'reception') ?> value="reception">Reception</option>
                    <option <?= get_select('rank', 'lecturer') ?> value="lecturer">Lecturer</option>
                    <option <?= get_select('rank', 'admin') ?> value="admin">Admin</option>

                    <?php if (Auth::getRank() == 'super_admin'): ?>

                        <option <?= get_select('rank', 'super_admin') ?> value="super_admin">Super Admin</option>

                    <?php endif; ?>

                </select>

            <?php endif; ?>

            <input class="my-2 form-control" value="<?= get_var('password') ?>" type="text" name="password" placeholder="Password">
            <input class="my-2 form-control" value="<?= get_var('password2') ?>" type="text" name="password2" placeholder="Retype Password">
            <button class="btn btn-primary float-end">Add User</button>

            <?php if ($mode == 'students'): ?>

                <a href="<?= ROOT ?>/students">
                    <button type="button" class="btn btn-danger text-white">Cancel</button>
                </a>

            <?php else: ?>

                <a href="<?= ROOT ?>/users">
                    <button type="button" class="btn btn-danger text-white">Cancel</button>
                </a>

            <?php endif; ?>

        </div>
    </form>
</div>

<?php $this->view('includes/footer'); ?>