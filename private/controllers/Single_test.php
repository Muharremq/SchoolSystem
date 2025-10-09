<?php

/**
 * single test controller
 */

class Single_test extends Controller
{

    public function index($id = '')
    {
        $errors = array();
        if (!Auth::access('lecturer')) {
            $this->redirect('access_denied');
        }

        $tests = new Tests_model();
        $row = $tests->first('test_id', $id);

        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Tests', 'tests'];

        if ($row) {
            $crumbs[] = [$row->test, ''];
        }

        //disable/enable toggle
        if (isset($_GET['disable'])) {

            if ($row->disabled) {
                $disable = 0; // Enable the test
            } else {
                $disable = 1; // Disable the test
            }
            $query = "update tests set disabled = :disable where id = :id limit 1";
            $tests->query($query, ['id' => $row->id, 'disable' => $disable]);

            // Redirect to refresh the page and show updated status
            $this->redirect('single_test/' . $id);
        }

        $page_tab = 'view';
        $student_scores = false;
        $show_add_menu = false; // New variable to control add menu visibility

        if (isset($_GET['tab']) && $_GET['tab'] == "scores") {
            $page_tab = 'scores';

            $answered_test = new Answered_test();
            $student_scores = $answered_test->query("select * from answered_tests where test_id = :test_id && submitted = 1 && marked = 1 order by score desc", ['test_id' => $id]);
        }


        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $results = false;
        $quest = new Questions_model();

        $questions = $quest->where('test_id', $id);

        // Fix: Ensure $questions is an array before counting
        if (!is_array($questions)) {
            $questions = [];
        }

        $total_questions = count($questions);

        // Auto-expand add menu if no questions exist and we're on the view tab
        if ($total_questions == 0 && $page_tab == 'view') {
            $show_add_menu = true;
        }

        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['questions'] = $questions;
        $data['total_questions'] = $total_questions;
        $data['results'] = $results;
        $data['errors'] = $errors;
        $data['pager'] = $pager;
        $data['student_scores'] = $student_scores;
        $data['show_add_menu'] = $show_add_menu; // Pass to view


        $this->view('single_test', $data);
    }



    public function addquestion($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $tests = new Tests_model();
        $row = $tests->first('test_id', $id);

        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Tests', 'tests'];

        if ($row) {
            // Get the class information
            $classes = new Classes_model(); // Make sure you have this model
            $class = $classes->first('class_id', $row->class_id);
            $row->class = $class ? $class->class : 'Unknown Class';
            $crumbs[] = [$row->class, 'test'];
        }

        $page_tab =  'add-question';

        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $quest = new Questions_model();

        if (count($_POST) > 0) {

            if ($quest->validate($_POST)) {
                //check for files
                if ($myimage = upload_image($_FILES)) {
                    $_POST['image'] = $myimage;
                }

                $_POST['test_id'] = $id;
                $_POST['date'] = date("Y-m-d H:i:s");

                if (isset($_GET['type']) && $_GET['type'] == "multiple") {
                    $_POST['question_type'] = 'multiple';
                    //for multiple choice
                    $num = 0;
                    $arr = [];
                    $letters = ['A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J'];
                    foreach ($_POST as $key => $value) {
                        // code...
                        if (strstr($key, 'choice')) {

                            $arr[$letters[$num]] = $value;
                            $num++;
                        }
                    }

                    $_POST['choices'] = json_encode($arr);
                } else
 				if (isset($_GET['type']) && $_GET['type'] == "objective") {
                    $_POST['question_type'] = 'objective';
                } else {
                    $_POST['question_type'] = 'subjective';
                }



                $quest->insert($_POST);
                $this->redirect('single_test/' . $id);
            } else {
                //errors
                $errors = $quest->errors;
            }
        }

        $results = false;

        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['errors'] = $errors;
        $data['pager'] = $pager;

        $this->view('single_test', $data);
    }




    public function editquestion($id = '', $quest_id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $tests = new Tests_model();
        $row = $tests->first('test_id', $id);

        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Tests', 'tests'];

        if ($row) {
            $crumbs[] = [$row->test, ''];
        }

        $page_tab =  'edit-question';

        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $quest = new Questions_model();
        $question = $quest->first('id', $quest_id);

        if (count($_POST) > 0) {

            if (!$row->editable) {
                $errors[] = "Editing for this test question is disabled";
            }

            if ($quest->validate($_POST) && count($errors) == 0) {

                //check for files
                if ($myimage = upload_image($_FILES)) {
                    $_POST['image'] = $myimage;
                    if (file_exists($question->image)) {
                        unlink($question->image);
                    }
                }

                //check the question type
                $type = '';
                if (isset($_GET['type']) && $_GET['type'] == "multiple") {
                    $_POST['question_type'] = 'multiple';
                    //for multiple choice
                    $num = 0;
                    $arr = [];
                    $letters = ['A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J'];
                    foreach ($_POST as $key => $value) {
                        // code...
                        if (strstr($key, 'choice')) {

                            $arr[$letters[$num]] = $value;
                            $num++;
                        }
                    }

                    $_POST['choices'] = json_encode($arr);
                    $type = '?type=multiple';
                } else
		    	if ($question->question_type == 'objective') {
                    $type = '?type=objective';
                }

                $quest->update($question->id, $_POST);
                $this->redirect('single_test/editquestion/' . $id . '/' . $quest_id . $type);
            } else {
                //errors
                $errors = array_merge($errors, $quest->errors);
            }
        }

        $results = false;

        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['errors'] = $errors;
        $data['pager'] = $pager;
        $data['question'] = $question;

        $this->view('single_test', $data);
    }



    public function deletequestion($id = '', $quest_id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $tests = new Tests_model();
        $row = $tests->first('test_id', $id);

        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Tests', 'tests'];

        if ($row) {
            $crumbs[] = [$row->test, ''];
        }

        $page_tab =  'delete-question';

        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $quest = new Questions_model();
        $question = $quest->first('id', $quest_id);

        if (!$row->editable) {
            $errors[] = "This test question can not be deleted";
        }

        if (count($_POST) > 0 && count($errors) == 0) {

            if (Auth::access('lecturer')) {

                $quest->delete($question->id);
                if (file_exists($question->image)) {
                    unlink($question->image);
                }
                $this->redirect('single_test/' . $id);
            }
        }

        $results = false;

        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['errors'] = $errors;
        $data['pager'] = $pager;
        $data['question'] = $question;

        $this->view('single_test', $data);
    }
}
