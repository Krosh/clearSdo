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

function updateAccessDivs()
{
    accessVal = $("#Access_access").val();
    $('.dateAccess').hide();
    $('.beforeAccess').hide();
    if (accessVal == 3)
    {
        $('.dateAccess').show();
    }
    if (accessVal == 4)
    {
        $('.beforeAccess').show();
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
            updateLearnMaterials(idCourse);
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
                }
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
                }
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
                }
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
    $.ajax({
        url: '/answer/getMaterials',
        data: {idQuestion: idQuestion},
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
    if ($("#Question_type").val() == 3 || $("#Question_type").val()==4)
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
    var questionType = $("#Question_type").val();
    var answerCount = $(".answer").length;
    var rightAnswerCount = $(".answer input:checked").length;
    if (rightAnswerCount == 0)
    {
        alert("Должен быть хотя бы один правильный ответ!");
        return false;
    }
    if (questionType != 2 && rightAnswerCount>1)
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

    // Запуск лоадера, скрываем элемент
    if($(".login").length > 0) {
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
                alert("Ошибка при загрузке новостей");
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

    if ($("#files").length)
    {
        fileLoaderWork();
    }

    $("#XUploadForm-form").bind("fileuploadadd",function()
    {
        setTimeout(function(){
            $("#XUploadForm-form button[type=submit]").click();
        },100);
    });
    $("#XUploadForm-form").bind("fileuploaddone",function()
    {
        updateLearnMaterials();
    });



    if($('.has-tip').length) {
        $('.has-tip').frosty();
        $('.has-tip.tip-bottom').frosty({
            position: 'bottom'
        });
    }
});