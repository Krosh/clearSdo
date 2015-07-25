<?php

class ReportController extends CController
{
    public $layout='//layouts/full';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.AccessFilter'),
        );
	}

    public function actionMarks()
    {
        $this->render("marks");
    }

    public function actionMarksAjax()
    {
        $this->renderPartial("marksAjax", array('groupName' => $_POST['group'], 'courseName' => $_POST['course']));
    }

}
