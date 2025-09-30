<?php

/**
 * single test controller
 */

class Single_test extends Controller
{

    public function index($id = '')
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $tests = new Tests_model();
        $row = $tests->first('class_id', $id);

        $crumbs[] = ['Dashboard', '/'];
        $crumbs[] = ['Tests', 'tests'];

        if ($row) {
            // Get the class information
            $classes = new Classes_model(); // Make sure you have this model
            $class = $classes->first('class_id', $row->class_id);
            $row->class = $class ? $class->class : 'Unknown Class';
            $crumbs[] = [$row->class, 'test'];
        }

        $page_tab = isset($_GET['tab']) ? esc($_GET['tab']) : 'view';

        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $results = false;

        $data['row'] = $row;
        $data['crumbs'] = $crumbs;
        $data['page_tab'] = $page_tab;
        $data['results'] = $results;
        $data['errors'] = $errors;
        $data['pager'] = $pager;

        $this->view('single_test', $data);
    }
}
