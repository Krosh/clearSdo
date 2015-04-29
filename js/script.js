function ajaxUpdateAccess(elem)
{
    console.log($(elem.form).serialize()+"&idCourse="+window.currentCourse+"&idMaterial="+window.currentMaterial+"&AccessControlMaterial[idRecord]="+$("#"+elem.form.id+" #GroupSelect_select").val());
     $.ajax({
        type: 'POST',
        url: '/controlMaterial/updateAccessInfo',
        data: $(elem.form).serialize()+"&idCourse="+window.currentCourse+"&idMaterial="+window.currentMaterial+"&AccessControlMaterial[idRecord]="+$("#"+elem.form.id+" #GroupSelect_select").val(),
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
        }
    });
}

function ajaxDeleteAccess(idAccess, idCourse, idMaterial)
{
    $("#accessForm"+idAccess).remove();
    $.ajax({
        type: 'POST',
        url: '/controlMaterial/deleteAccessInfo',
        data: {id: idAccess},
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
//            ajaxGetAccess(idCourse, idMaterial);
        }
    });
}

function ajaxAddAccess(idCourse, idMaterial, typeRelation)
{
    $.ajax({
        type: 'POST',
        url: '/controlMaterial/addAccessInfo',
        data: {idCourse:idCourse, idMaterial: idMaterial, typeRelation: typeRelation},
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            ajaxGetAccess(idCourse, idMaterial);
        }
    });
}


function ajaxGetAccess(idCourse, idMaterial)
{
    window.currentCourse = idCourse;
    window.currentMaterial = idMaterial;
    $.ajax({
        type: 'POST',
        url: '/controlMaterial/getAccessInfo',
        data: {idCourse: idCourse, idMaterial: idMaterial},
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            $("#editCourse-access").html(data);
            $('.dateTimePicker').datetimepicker({ format:'d.m.Y H:i', onChange: function() {ajaxUpdateAccess(this);}});
            $('.combobox').combobox({allowText:false, onSelect:"ajaxUpdateAccess(this);"});
            $('.accessForm').each(function()
            {
                updateAccessDivs(this);
            });
        }
    });
}

function ajaxDeleteAllNonUsedMaterials()
{
    $.ajax({
        type: 'POST',
        url: '/learnMaterial/deleteAllNonUsedMaterials',
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            $.fn.yiiGridView.update("media-grid");
        }
    });
}
function ajaxSendUserFileAnswer(fileInput,idMaterial)
{
    //var form = $(fileInput).parent();
    var form = document.forms.loadFile;
    var formData = new FormData(form);
    formData.append("idMaterial",idMaterial);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/controlMaterial/addUserFileAnswer");
    xhr.onreadystatechange = function() {
        console.log(xhr.responseText);
        if (xhr.readyState == 4) {
            if(xhr.status == 200) {
                window.location = "";
            }
        }
    };
    xhr.send(formData);
}

function startChangeLearnMaterialTitle(obj,id)
{
    $(obj).hide();
    $("#editTitle"+id).show().focus();
}

function ajaxChangeLearnMaterialTitle(obj,id)
{
    $.ajax({
        type: 'POST',
        url: '/learnMaterial/changeTitle',
        data: {idMaterial: id, title: $(obj).val()},
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            $(obj).hide();
            $("#labelTitle"+id).show().html($(obj).val());
        }
    });
}

function ajaxGetTimetable(idGroup)
{
    $.ajax({
        type: 'POST',
        url: '/group/getTimetable',
        data: {idGroup:idGroup},
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            alert('Расписание успешно получено');
        }
    });
}

function checkSubmit(val)
{
    if (val == 5)
    {
        var elem = document.getElementById('answer');
        var order = $("#rightAnswers").sortable("toArray");
        var s = "";
        for (var i = 0; i<order.length; i++)
        {
            s+=order[i]+"~";
        }
        elem.value = s;
    }
}

function checkHasNewPassword()
{
    if ($("#haveNewPassword").prop("checked"))
        $(".divNewPassword").show();
    else
        $(".divNewPassword").hide();

}

function changeWeights(idMaterial)
{
    var summ = 0;
    var count = 0;
    var totalSumm = 1;
    var result = "";
    $("input[data-idMaterial]").each(function()
    {
        summ += parseFloat($(this).val());
        count++;
    });
    if (summ == 0)
    {
        alert("Сумма весов должна быть больше 0!");
        return;
    }
    var i = 0;
    $("input[data-idMaterial]").each(function()
    {
        if (++i == count)
            $(this).val(totalSumm.toFixed(2));
        else
            $(this).val(($(this).val()/summ).toFixed(2));
        totalSumm -=  parseFloat($(this).val());
        result+=$(this).attr("data-idMaterial")+"="+$(this).val()+";";
    });
    $.ajax({
        url: '/controlMaterial/saveWeights',
        data: {idControlMaterial: idMaterial, calcExpression: result},
        type: "POST",
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });

}

function loadStudentsFromExcel()
{
    var form = document.forms.loadStudentsFromExcelForm;
    var formData = new FormData(form);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/group/loadStudentsFromExcel");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if(xhr.status == 200) {
                $.fn.yiiGridView.update("group-grid");
            }
        }
    };
    xhr.send(formData);
}

function recalcMarks(idControlMaterial,idGroup)
{
    $.ajax({
        url: '/controlMaterial/recalcMarks',
        data: {idGroup: idGroup, idControlMaterial: idControlMaterial},
        type: "POST",
        success: function(data){
            $.ajax({
                url: '/site/journal',
                data: {idGroup: idGroup, idCourse: window.idCourse},
                type: "GET",
                success: function(data){
                    $("#journal_table").html(data);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert(errorThrown);
                    console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function saveMark(idStudent,idControlMaterial,mark)
{
    $.ajax({
        url: '/controlMaterial/setMark',
        data: {idStudent: idStudent, idControlMaterial: idControlMaterial, mark: mark},
        type: "POST",
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').show();
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').text($('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').val());
    $('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').hide();
}

function showMarksOfMaterial(idControlMaterial)
{
    $('div[data-material='+idControlMaterial+']').hide();
    $('input[data-material='+idControlMaterial+']').show();
//    $('input[data-material='+idControlMaterial+']').focus();
}

function showMarkTextbox(idStudent,idControlMaterial)
{
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').hide();
    $('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').show();
    $('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').focus();
    $('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').focusout(function()
    {
        saveMark(idStudent,idControlMaterial,$('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').val());
    });
}

function makeReport_marks()
{
    $.ajax({
        url: '/report/marksAjax',
        data: {group: $("#group").val(), course: $("#course").val()},
        type: "POST",
        success: function(data)
        {
            $("#report").html(data);
            footerUpdate();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function updateAccessDivs(form)
{
    accessVal = $(".accessTypeList",form).val();
    $('.dateAccess',form).hide();
    $('.beforeAccess',form).hide();
    if (accessVal == 3)
    {
        $('.dateAccess',form).show();
    }
    if (accessVal == 4)
    {
        $('.beforeAccess',form).show();
    }
}

function loadCourses(idTerm,newTitle)
{
    // Обновляем окно
    $.ajax({
        url: '/courses/getCourses',
        data: {idTerm: idTerm},
        type: "POST",
        beforeSend: function() {
            $(".fa-loading-icon").fadeIn(100);
        },
        success: function(data)
        {
            $("#ajaxCoursesDiv").fadeOut(300, function() {
                $("#currentTermTitle").html(newTitle);
                $("#ajaxCoursesDiv").html(data);
                $("#ajaxCoursesDiv").fadeIn(300, function() {
                    $(".fa-loading-icon").fadeOut(100);
                    footerUpdate();
                });
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        complete: function() {
            //$("footer").footer();
        }
    });
}

function updateTeachers(idCourse)
{
    $.ajax({
        url: '/courses/getTeachers',
        data: {idCourse: idCourse},
        type: "POST",
        success: function(data)
        {
            $("#editCourse-teachers").html(data);
            footerUpdate();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function deleteTeacher(idCourse,idTeacher)
{
    $.ajax({
        url: '/courses/deleteTeacher',
        data: {idCourse: idCourse, idTeacher: idTeacher},
        type: "POST",
        success: function(data)
        {
            updateTeachers(idCourse);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function updateGroups(idCourse, idTerm)
{
    window.idTerm = idTerm;
    $.ajax({
        url: '/courses/getGroups',
        data: {idCourse: idCourse, idTerm: idTerm},
        type: "POST",
        success: function(data)
        {
            $("#addGroupsSelect").empty().html(data);
            $("#addGroupsSelect").multiSelect('refresh');
            footerUpdate();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function deleteGroup(idGroup,idTerm,idCourse)
{
    $.ajax({
        url: '/courses/deleteGroup',
        data: {idGroup: idGroup, idTerm: window.idTerm, idCourse: idCourse},
        type: "POST",
        success: function(data)
        {
//            updateGroups(idCourse,idTerm);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function addGroup(idGroup,idTerm,idCourse)
{
    $.ajax({
        type: "POST",
        url: "/courses/addGroupToCourse",
        data: {idGroup: idGroup, idTerm: window.idTerm, idCourse: idCourse},
        success: function(data)
        {
//            updateGroups(idCourse,idTerm);
            /* $("#editCourse-groupSelect").hide(); */
//            footerUpdate();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("error"+textStatus+errorThrown);
        }});
}

function addLearnMaterial(idCourse)
{
    var form = document.forms.learnMaterialForm;
    var formData = new FormData(form);
    formData.append("idCourse",idCourse);
    formData.append("linkPath",$("#LinkPath").val());
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/learnMaterial/addMaterial");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if(xhr.status == 200) {
//                $("#loadfile").hide();
                $("#learnMaterialLoader").hide();
                $("#learnMaterialSubmitButton").show();
                updateLearnMaterials(idCourse);
            }
        }
    };
    xhr.send(formData);
}


function deleteLearnMaterial(idCourse,idMaterial)
{
    $.ajax({
        url: '/learnMaterial/deleteMaterial',
        data: {idCourse: idCourse, idMaterial:idMaterial},
        type: "POST",
        success: function(data)
        {
            updateLearnMaterials(window.idCourse);
        },
        error: function(jqXHR, textStatus, errorThrown){
//            alert(errorThrown);
//            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function updateLearnMaterials(idCourse)
{
    // Обновляем окно
    $.ajax({
        url: '/learnMaterial/getMaterials',
        data: {idCourse: idCourse},
        type: "POST",
        success: function(data)
        {
            $("#editCourse-materials").html(data);

            $("#learnMaterialTable tbody").sortable({
                items: 'tr',
                update: function(event, ui ) {
                    var itemId = $(ui.item).prev().attr('id') || 0;
                    $.ajax({
                        url: '/learnMaterial/orderMaterial',
                        data: {idMat: $(ui.item).attr('id'), idParentMat:itemId},
                        type: "POST",
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(errorThrown);
                            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
                        }
                    });
                },
                helper: function(e, tr){
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
            });

            footerUpdate();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function deleteControlMaterial(idCourse,idMaterial)
{
    $.ajax({
        url: '/material/deleteMaterial',
        data: {idCourse: idCourse, idMaterial:idMaterial},
        type: "POST",
        success: function(data)
        {
            updateControlMaterials(idCourse);
        },
        error: function(jqXHR, textStatus, errorThrown){
//            alert(errorThrown);
//            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function updateControlMaterials(idCourse)
{
    // Обновляем окно
    $.ajax({
        url: '/material/getMaterials',
        data: {idCourse: idCourse},
        type: "POST",
        success: function(data)
        {
            $("#editCourse-controlMaterials").html(data);
            footerUpdate();
            $("#controlMaterialTable tbody").sortable({
                items: 'tr',
                update: function(event, ui ) {
                    var itemId = $(ui.item).prev().attr('id') || 0;
                    $.ajax({
                        url: '/material/orderMaterial',
                        data: {idMat: $(ui.item).attr('id'), idParentMat:itemId},
                        type: "POST",
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(errorThrown);
                            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
                        }
                    });
                },
                helper: function(e, tr){
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function updateQuestions(idTest)
{
    // Обновляем окно
    $.ajax({
        url: '/question/getQuestions',
        data: {idControlMaterial: idTest},
        type: "POST",
        success: function(data)
        {
            $("#editTest-questions").html(data);
            footerUpdate();

            $("#questionTable tbody").sortable({
                items: 'tr',
                update: function(event, ui ) {
                    var itemId = $(ui.item).prev().attr('id') || 0;
                    $.ajax({
                        url: '/question/orderQuestions',
                        data: {idMat: $(ui.item).attr('id'), idParentMat:itemId},
                        type: "POST",
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(errorThrown);
                            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
                        }
                    });
                },
                helper: function(e, tr){
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function deleteQuestion(idQuestion,idControlMaterial)
{
    $.ajax({
        url: '/question/deleteQuestion',
        data: {idQuestion: idQuestion, idControlMaterial:idControlMaterial},
        type: "POST",
        success: function(data)
        {
            updateQuestions(idControlMaterial);
        },
        error: function(jqXHR, textStatus, errorThrown){
//            alert(errorThrown);
//            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}


function updateAnswers(idQuestion)
{
    // Обновляем окно
    var questionType = $(".horizontal-buttons-list input[type='radio']:checked").val();
    $.ajax({
        url: '/answer/getMaterials',
        data: {idQuestion: idQuestion, questionType: questionType},
        type: "POST",
        success: function(data)
        {
            $("#question-answers").html(data);
            footerUpdate();

            $("#answerTable tbody").sortable({
                items: 'tr',
                update: function(event, ui ) {
                    var itemId = $(ui.item).prev().attr('id') || 0;
                    $.ajax({
                        url: '/answer/orderMaterial',
                        data: {idMat: $(ui.item).attr('id'), idParentMat:itemId},
                        type: "POST",
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(errorThrown);
                            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
                        }
                    });
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function addAnswer(idQuestion)
{
    var questionType = $(".horizontal-buttons-list input[type='radio']:checked").val();
    if (questionType == 3 ||questionType==4)
    {
        alert("У этого типа вопроса может быть только один вариант ответа!");
        return;
    }
    $.ajax({
        type: "POST",
        url: '/answer/create',
        data: {idQuestion:idQuestion},
        success: function(data){
            updateAnswers(idQuestion);
        },
        error :function()
        {
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function deleteAnswer(idAnswer)
{
    $.ajax({
        url: '/answer/deleteMaterial',
        data: {idAnswer: idAnswer},
        type: "POST",
        success: function(data)
        {
            updateAnswers(window.idQuestion);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function changeAnswer(idAnswer,content,right)
{
    if (right)
        right = 1;
    else
        right = 0;
    $.ajax({
        url: '/answer/changeAnswer',
        data: {idAnswer: idAnswer,content: content, right: right},
        type: "POST",
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function isValidQuestion()
{
//    var questionType = $("#ytQuestion_type").val(); // Вот с этой фигней что-то придумать

    var questionType = $(".horizontal-buttons-list input[type='radio']:checked").val();
    var answerCount = $(".answer").length;
    var rightAnswerCount = $(".answer input:checked").length;
    if (rightAnswerCount == 0)
    {
        alert("Должен быть хотя бы один правильный ответ!");
        return false;
    }
    if (questionType != 2 && questionType != 5 && rightAnswerCount>1)
    {
        alert("Должен быть один правильный ответ!");
        return false;
    }
    if ((questionType == 3||questionType == 4) && answerCount>1)
    {
        alert("Должен быть только один вариант ответа!");
        return false;
    }
    return true;
}

function footerUpdate() {
    var wrapperHeight = $(".wrapper").height() + $("header").height() + 100;
    var windowHeight = $(window).height();

    $("footer").removeClass("fixed");
    if(wrapperHeight <= windowHeight) {
        $("footer").addClass("fixed");
    }
}

$(document).ready(function(){
    footerUpdate();
    $(window).resize(function(){
        footerUpdate();
    });

    // вид инпута файлов
    $("input[type=file]").nicefileinput({
        label: "Обзор"
    });

    // Запуск лоадера, скрываем элемент
    if($(".login").length && !$(".login").hasClass("disable-loader")) {
        $(".login").loader();
    }

    if($(".datePicker").length) {
        $(".datePicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    }
    // Дропдауны
    $(".dropdown").dropdown();

    // Меню
    $(".small-nav").nav();

    // Кастомные чекбоксы
    $("input[type=radio], input[type=checkbox]").picker();

    // Табер
    $(".tabbed").tabber();

    // Лого
    //$(".logo").logo();

    // Ретина
    if($("[data-retina]").length) {
        $("[data-retina]").retinizr();
    }

    // Календарь

    var eventsArray = [
        { date: '2014-09-15', title: 'ЛР 1' },
        { date: '2014-09-11', title: 'ЛР 2' }
    ];
    $("#calendar").clndr({
        template: $('#mini-clndr-template').html(),
        weekOffset: 1,
        daysOfTheWeek: ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'],
        events: eventsArray,
        clickEvents: {
            onMonthChange: function(month) {
                var ruMonth = moment(month).locale("ru").format("MMMM YYYY");
                $(".month").text(ruMonth);
            }
        }
    });

    // Активность в меню
    $(".big-nav .active").removeClass("active");
    $(".big-nav > .link").each(function() {
        var self = $(this),
            url = window.location.href.replace(window.location.protocol+"//"+window.location.hostname, ''),
            href = self.find("a").attr("href");

        if(href == url) {
            self.addClass("active");
        } else if(self.hasClass("more")) {
            self.find(".more-menu-links a").each(function() {
                var self = $(this),
                    href = self.attr("href");

                if(href == url) {
                    self.addClass("active");
                    self.closest(".link").addClass("active");
                }
            });
        }
    });

    // Переход у таблиц по data-href
    if (window.currentTerm !== undefined) {
        loadCourses(window.currentTerm);
    }

    $(document).on("click", '[data-href]', function() {
        window.location = $(this).data('href');
        return false;
    });

    $(document).on("click", "[data-href] a", function(e) {
        e.preventDefault();
        window.location = $(this).attr('href');
        return false;
    });

    $(document).on("click", ".toggler", function(e) {
        e.preventDefault();
        
        var checkbox = $(this).find("input");
        checkbox.prop("checked", !checkbox.prop("checked"));
        if (checkbox.prop("checked"))
            var access = 1;
        else
            var access = 2;

        $.ajax({
            url: '/material/changeAccess',
            data: {access: access, idMaterial:$(this).data("idmaterial")},
            type: "POST",
            success: function(data)
            {
                console.log("Changed access"+data);
            },
            error: function(jqXHR, textStatus, errorThrown){
//            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
            }
        });

        return false;
    });

    if($(".jsRedactor").length) {
        tinymce.init({
            language : "ru",
            selector: ".jsRedactor",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste jbimages"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
            relative_urls: false
        });
        // $(".jsRedactor").redactor();
    }

    if ($('#timerSpan').length) {
        startTimer(document.getElementById("timerSpan"),window.endTime);
    }

    if ($('#news-content').length) {
        $.ajax({
             type: "POST",
             url: "/news/news",
             beforeSend: function() {
                 $("#news-content").hide();
             },
             success: function(data)
             {
                 $("#news-content").html(data);
                 $("#news-content").fadeIn(200);
             },
             error: function()
             {
//                 alert("Ошибка при загрузке новостей");
             }
        });
    }

    if ($("#editCourse-teachers").length)
    {
        updateTeachers(window.idCourse);
        updateGroups(window.idCourse,window.idTerm);
        updateLearnMaterials(window.idCourse);
        updateControlMaterials(window.idCourse);
    }
    if ($("#editTest-questions").length)
    {
        updateQuestions(window.idTest);
        updateAccessDivs();
    }
    if ($("#question-answers").length)
    {
        updateAnswers(window.idQuestion);
    }

    if (window.needModal)
    {
        $("#"+window.nameModal).modal('show');
    }

    if ($("#addGroupsSelect").length) {
        $("#addGroupsSelect").multiSelect({
            selectableHeader: "<input style='margin-bottom:20px;' type='text' class='search-input' autocomplete='off' placeholder='Поиск слушателей'>",
            selectionHeader: "<div style='margin-bottom:31px;'>Слушатели данного курса:</div>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)'

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function(values){
                addGroup(values,idTerm,idCourse);
            },
            afterDeselect: function(values){
                deleteGroup(values,idTerm,idCourse);
            }
        });
    }


    $("#XUploadForm-form").bind("fileuploaddragover",function(e)
    {
        var dropZone = $('#editCourse-materials');
        var timeout = window.dropZoneTimeout;
        if (!timeout) {
            $("#editCourse-materials").uploaderInfo("show");
        } else {
            clearTimeout(timeout);
        }
        var found = false;
        if (e.srcElement)
            var node = e.srcElement;
        else
            var node = e.target;
        do {
            if (node === dropZone[0]) {
                found = true;
                break;
            }
            node = node.parentNode;
        } while (node != null);
        if (found) {
            $("#editCourse-materials").uploaderInfo("setActive");
        } else {
            $("#editCourse-materials").uploaderInfo("unsetActive");
        }
        window.dropZoneTimeout = setTimeout(function () {
            $("#editCourse-materials").uploaderInfo("unsetActive");
            $("#editCourse-materials").uploaderInfo("hide");
            window.dropZoneTimeout = false;
        }, 100);    });

    $("#XUploadForm-form").bind("fileuploadstart",function()
    {
        $('body').showLoader();
    });

    $("#XUploadForm-form").bind("fileuploadadd",function()
    {
        setTimeout(function(){
            $("#XUploadForm-form button[type=submit]").click();
        },100);
    });
    $("#XUploadForm-form").bind("fileuploaddone",function()
    {
        $('body').hideLoader();
        updateLearnMaterials(window.idCourse);
    });
    $("#XUploadForm-form").bind('fileuploadfail',function()
    {
        $('body').hideLoader();
        alert("Произошла ошибка при загрузке файла");
    });

    if ($("#rightAnswers").length)
        $("#rightAnswers").sortable();


    if($('.has-tip').length) {
        $('.has-tip').frosty();
        $('.has-tip.tip-bottom').frosty({
            position: 'bottom'
        });
    }
});