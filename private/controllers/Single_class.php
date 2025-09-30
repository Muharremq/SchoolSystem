<?php

/**
 * single class controller
 */

class Single_class extends Controller
{

    public function index($id = '')
    {
        //   code....

        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }


        $classes = new Classes_model();

        $row = $classes->first('class_id', $id);


        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Classes', 'single_class'];

        if ($row) {
            $crumbs[] = [$row->class, 'class'];
        }

        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $page_tab = isset($_GET['tab']) ? esc($_GET['tab']) : 'lecturers';

        $lect = new Lecturers_model();


        $results = false;


        if ($page_tab == 'lecturers') {

            //display lecturer
            $query = "select * from class_lecturers where class_id = :class_id && disabled = 0 order by id desc limit $limit offset $offset";
            $lecturers = $lect->query($query, ['class_id' => $id]);

            $data['lecturers'] = $lecturers;
        } else 
            if ($page_tab == 'students') {
            //display student
            $query = "select * from class_students where class_id = :class_id && disabled = 0 order by id desc limit $limit offset $offset";
            $students = $lect->query($query, ['class_id' => $id]);

            $data['students'] = $students;
        } else 
            if ($page_tab == 'tests') {
            //display tests
            $query = "select * from tests where class_id = :class_id order by id desc limit $limit offset $offset";
            $tests = $lect->query($query, ['class_id' => $id]);

            $data['tests'] = $tests;
        }



        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;
        $data['pager'] = $pager;


        $this->view('single_class', $data);
    }







    public function lectureradd($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }


        $classes = new Classes_model();

        $row = $classes->first('class_id', $id);


        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Classes', 'single_class'];

        if ($row) {
            $crumbs[] = [$row->class, 'class'];
        }


        $page_tab = 'lecturer-add';

        $lect = new Lecturers_model();


        $results = false;

        if (count($_POST) > 0) {

            if (isset($_POST['search'])) {


                if (!empty($_POST['name'])) {
                    //find lecturer
                    $user = new User();
                    $name = "%" . trim($_POST['name']) . "%";

                    $query = "SELECT * FROM users WHERE (firstname LIKE :fname OR lastname LIKE :lname) && rank = 'lecturer' LIMIT 10";

                    $results = $user->query($query, ['fname' => $name, 'lname' => $name]);
                } else {
                    $errors[] = " pleas type a name to search for ";
                }
            } else
                if (isset($_POST['selected'])) {

                //add lecturer
                $query = "select id from class_lecturers where user_id = :user_id && class_id = :class_id && disabled = 0 limit 1";


                if (!$lect->query($query, [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ])) {

                    $arr = array();
                    $arr['user_id'] = $_POST['selected'];
                    $arr['class_id'] = $id;
                    $arr['disabled'] = 0;
                    $arr['date'] = date('Y-m-d H:i:s');

                    $lect->insert($arr);

                    $this->redirect('single_class/' . $id . '?tab=lecturers');
                } else {
                    //check if user is active
                    if (isset($check[0]->disabled)) {
                        if ($check[0]->disabled) {
                            $arr = array();
                            $arr['disabled'] = 0;

                            $lect->update($check[0]->id, $arr);

                            $this->redirect('single_class/' . $id . '?tab=lecturers');
                        } else {
                            $errors[] = " that lecturer is already belongs to that class";
                        }
                    } else {
                        $errors[] = " that lecturer is already belongs to that class";
                    }
                }
            }
        }


        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;


        $this->view('single_class', $data);
    }


    public function lecturerremove($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }


        $classes = new Classes_model();

        $row = $classes->first('class_id', $id);


        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Classes', 'single_class'];

        if ($row) {
            $crumbs[] = [$row->class, 'class'];
        }


        $page_tab = 'lecturer-remove';

        $lect = new Lecturers_model();


        $results = false;

        if (count($_POST) > 0) {

            if (isset($_POST['search'])) {


                if (!empty($_POST['name'])) {
                    //find lecturer
                    $user = new User();
                    $name = "%" . trim($_POST['name']) . "%";

                    $query = "SELECT * FROM users WHERE (firstname LIKE :fname OR lastname LIKE :lname) && rank = 'lecturer' LIMIT 10";

                    $results = $user->query($query, ['fname' => $name, 'lname' => $name]);
                } else {
                    $errors[] = " pleas type a name to search for ";
                }
            } else
                if (isset($_POST['selected'])) {

                //add lecturer
                $query = "select id from class_lecturers where user_id = :user_id && class_id = :class_id && disabled = 0 limit 1";

                //removce lecturer

                if ($row = $lect->query($query, [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ])) {

                    $arr = array();
                    $arr['disabled'] = 1;

                    $lect->update($row[0]->id,  $arr);

                    $this->redirect('single_class/' . $id . '?tab=lecturers');
                } else {
                    $errors[] = " that lecturer was not found in that class";
                }
            }
        }


        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;


        $this->view('single_class', $data);
    }








    public function studentadd($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }


        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $classes = new Classes_model();
        $row = $classes->first('class_id', $id);

        $crumbs[] = ['Dashboard', ''];
        $crumbs[] = ['classes', 'classes'];

        if ($row) {
            $crumbs[] = [$row->class, ''];
        }

        $page_tab = 'student-add';
        $stud = new Students_model();

        $results = false;

        if (count($_POST) > 0) {

            if (isset($_POST['search'])) {


                if (!empty($_POST['name'])) {
                    //find student
                    $user = new User();
                    $name = "%" . trim($_POST['name']) . "%";

                    $query = "SELECT * FROM users WHERE (firstname LIKE :fname OR lastname LIKE :lname) && rank = 'student' LIMIT 10";

                    $results = $user->query($query, ['fname' => $name, 'lname' => $name]);
                } else {
                    $errors[] = " pleas type a name to search for ";
                }
            } else
                if (isset($_POST['selected'])) {

                //add student
                $query = "select disabled,id from class_students where user_id = :user_id && class_id = :class_id limit 1";


                if (!$check = $stud->query($query, [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ])) {

                    $arr = array();
                    $arr['user_id'] = $_POST['selected'];
                    $arr['class_id'] = $id;
                    $arr['disabled'] = 0;
                    $arr['date'] = date('Y-m-d H:i:s');

                    $stud->insert($arr);

                    $this->redirect('single_class/' . $id . '?tab=students');
                } else {

                    //check if user is active
                    if (isset($check[0]->disabled)) {
                        if ($check[0]->disabled) {

                            $arr = array();
                            $arr['disabled']     = 0;
                            $stud->update($check[0]->id, $arr);

                            $this->redirect('single_class/' . $id . '?tab=students');
                        } else {

                            $errors[] = "that student already belongs to this class";
                        }
                    } else {
                        $errors[] = "that student already belongs to this class";
                    }
                }
            }
        }


        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;


        $this->view('single_class', $data);
    }


    public function studentremove($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }


        $classes = new Classes_model();

        $row = $classes->first('class_id', $id);


        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Classes', 'single_class'];

        if ($row) {
            $crumbs[] = [$row->class, 'class'];
        }


        $page_tab = 'student-remove';

        $lect = new Students_model();


        $results = false;

        if (count($_POST) > 0) {

            if (isset($_POST['search'])) {


                if (!empty($_POST['name'])) {
                    //find student
                    $user = new User();
                    $name = "%" . trim($_POST['name']) . "%";

                    $query = "SELECT * FROM users WHERE (firstname LIKE :fname OR lastname LIKE :lname) && rank = 'student' LIMIT 10";

                    $results = $user->query($query, ['fname' => $name, 'lname' => $name]);
                } else {
                    $errors[] = " pleas type a name to search for ";
                }
            } else
                if (isset($_POST['selected'])) {

                //add student
                $query = "select id from class_students where user_id = :user_id && class_id = :class_id && disabled = 0 limit 1";

                //removce student

                if ($row = $lect->query($query, [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ])) {

                    $arr = array();
                    $arr['disabled'] = 1;

                    $lect->update($row[0]->id,  $arr);

                    $this->redirect('single_class/' . $id . '?tab=students');
                } else {
                    $errors[] = " that student was not found in that class";
                }
            }
        }


        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;


        $this->view('single_class', $data);
    }



    public function testadd($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }


        $classes = new Classes_model();
        $row = $classes->first('class_id', $id);

        $crumbs[] = ['Dashboard', ''];
        $crumbs[] = ['classes', 'classes'];

        if ($row) {
            $crumbs[] = [$row->class, ''];
        }

        $page_tab = 'test-add';
        $test_class = new Tests_model();

        $results = false;

        if (count($_POST) > 0) {

            if (isset($_POST['test'])) {


                $arr = array();
                $arr['test'] = $_POST['test'];
                $arr['description'] = $_POST['description'];
                $arr['class_id'] = $id;
                $arr['disabled'] = 0;
                $arr['date'] = date('Y-m-d H:i:s');

                $test_class->insert($arr);

                $this->redirect('single_class/' . $id . '?tab=tests');
            }
        }


        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;


        $this->view('single_class', $data);
    }




    public function testedit($id = '', $test_id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $classes = new Classes_model();
        $tests = new Tests_model();

        // Get the class data
        $row = $classes->first('class_id', $id);

        // Get the specific test data using test_id
        $test_row = $tests->first('test_id', $test_id);

        $crumbs[] = ['Dashboard', ''];
        $crumbs[] = ['classes', 'classes'];

        if ($row) {
            $crumbs[] = [$row->class, ''];
        }

        $page_tab = 'test-edit';
        $test_class = new Tests_model();


        $results = false;

        if (count($_POST) > 0) {


            if (isset($_POST['test'])) {
                $arr = array();
                $arr['test']     = $_POST['test'];
                $arr['description']     = $_POST['description'];
                $arr['disabled']     = $_POST['disabled'];

                // Update with WHERE clause for specific test
                $test_class->update($test_row->id, $arr);

                $this->redirect('single_class/' . $id . '?tab=tests');
            }
        }

        $data['row'] = $row;
        $data['test_row'] = $test_row;  // This should be the test data, not class data
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;

        $this->view('single_class', $data);
    }



    public function testdelete($id = '', $test_id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $classes = new Classes_model();
        $tests = new Tests_model();

        // Get the class data
        $row = $classes->first('class_id', $id);

        // Get the specific test data using test_id
        $test_row = $tests->first('test_id', $test_id);

        $crumbs[] = ['Dashboard', ''];
        $crumbs[] = ['classes', 'classes'];

        if ($row) {
            $crumbs[] = [$row->class, ''];
        }

        $page_tab = 'test-delete';
        $test_class = new Tests_model();


        $results = false;

        if (count($_POST) > 0) {


            if (isset($_POST['test'])) {

                // Update with WHERE clause for specific test
                $test_class->delete($test_row->id);

                $this->redirect('single_class/' . $id . '?tab=tests');
            }
        }

        $data['row'] = $row;
        $data['test_row'] = $test_row;  // This should be the test data, not class data
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;

        $this->view('single_class', $data);
    }
}
