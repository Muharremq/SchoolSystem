<center>
    <h5>Test Questions</h5>
</center>


<a href="<?= ROOT ?>/single_class/testadd/<?= $row->class_id ?>?tab=test-add">
    <button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button>
</a>
<a href="">
    <button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button>
</a>
<a href="<?= ROOT ?>/single_class/testadd/<?= $row->class_id ?>?tab=test-add">
    <button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button>
</a>
<nav class="navbar">
    <div class="btn-group">
        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Add
        </button>
        <ul class="dropdown-menu">

            <li>
                <a class="dropdown-item" href="<?= ROOT ?>/single_class/testadd/<?= $row->class_id ?>?tab=test-add">
                    Add Multiple Choice Questions
                </a>
            </li>


            <li>
                <a class="dropdown-item" href="<?= ROOT ?>/single_class/testadd/<?= $row->class_id ?>?tab=test-add">
                    Add Objective Questions
                </a>
            </li>


            <li>
                <hr class="dropdown-divider">
            </li>


            <li>
                <a class="dropdown-item" href="<?= ROOT ?>/single_class/testadd/<?= $row->class_id ?>?tab=test-add">
                    Add Subjective Questions
                </a>
            </li>
        </ul>
    </div>
</nav>