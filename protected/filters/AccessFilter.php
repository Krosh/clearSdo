<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class AccessFilter extends CFilter {
    public function preFilter($filterChain) {
//        Yii::app()->clientScript->registerCoreScript('jquery');
//        Yii::app()->clientScript->registerCoreScript('jquery.ui');
        PluginController::init();
        $reg = '/[a-zA-Z]*\?/';
        $matches = array();
        preg_match($reg,Yii::app()->request->requestUri."?",$matches);
        $namePage = substr($matches[0],0,strlen($matches[0])-1);
        $rules = array();
        $rules[ROLE_GUEST] = array("","connect","noAccess","checkOnAuthenticate","activation","sendForgotMessage");
        $rules[ROLE_STUDENT] = array_merge($rules[ROLE_GUEST],array("","setNewEmail","getCoursesFiles","ajaxChangeLanguage","ajaxGetCalendarEvents","migration","checkOnAuthenticate","sendForgotMessage","index",'profile',"search","deleteUserFileAnswer","addUserFileAnswer","webinar","userConfig","getMaterial","noAccess","logout","view","startTest","question","endTest","nextQuestion","skipQuestion","endTest","viewTestResults","news","getCourses"));
        $rules[ROLE_TEACHER] = array_merge($rules[ROLE_STUDENT],array("getStudents","ajaxGetStudentsToSlick","changeLink","calendar","ajaxSetEventTime","statistic","getLinks","fullOrderMaterial","addAccessInfo","deleteAccessInfo","updateAccessInfo","getAccessInfo","setAccessInfo", "fullDeleteCourse","learnMaterials","courses","controlMaterials","deleteAllNonUsedMaterials","getUserAnswer","getUserAnswers","getTimetable","changeTitle","changeAccess","plugin","upload","saveWeights","loadStudentsFromExcel","fullDeleteMaterial","mediateka","recalcMarks","journal","edit","addTeacherToCourse","getTeachers","deleteTeacher","addGroupToCourse","getGroups","deleteGroup","addMaterial","getMaterials","addExistMaterial","deleteMaterial","orderMaterial","create","edit","getQuestions","orderQuestions","deleteQuestion","addAnswer","changeAnswer","marks","marksAjax","setMark","getGroupMarks","calcAndGetGroupMarks"));
        $rules[ROLE_ADMIN] = array_merge($rules[ROLE_TEACHER],array("changePassword","changePublicStatus","log","config","admin","update","delete","addToGroup","deleteFromGroup"));
        if (Yii::app()->user->isGuest)
            $curRole = ROLE_GUEST;
        else
            $curRole = Yii::app()->user->getRole();
        if (array_search($namePage,$rules[$curRole]) !== false)
            return true;
        Yii::app()->controller->redirect("/noAccess");
    }
    public function postFilter($filterChain) {}
}