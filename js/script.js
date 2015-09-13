function fireEvent(element,event){
    console.log("in Fire Event");
    if (document.createEventObject){
        // dispatch for IE
        console.log("in IE FireEvent");
        var evt = document.createEventObject();
        return element.fireEvent('on'+event,evt)
    }
    else{
        // dispatch for firefox + others
        console.log("In HTML5 dispatchEvent");
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent(event, true, true ); // event type,bubbling,cancelable
        return !element.dispatchEvent(evt);
    }
}

function readNotice(idNotice, hidedObject)
{
    console.log(hidedObject);
    $.ajax({
        type: 'GET',
        url: '/message/readNotice',
        data: {idNotice: idNotice},
        error: onError,
        success: function(data)
        {
            hidedObject.slideUp();
        }
    });
}

function closeLearnMaterialDialog()
{
    $("#learnMaterialForm").trigger('reset');
    $("#loadfile").modal('hide');
}

function ajaxChangeLanguage(lang)
{
    $.ajax({
        type: 'GET',
        url: '/site/ajaxChangeLanguage',
        data: {lang: lang},
        error: onError,
        success: function()
        {
            var jObj = $('.goog-te-combo');
            var db = jObj.get(0);
            jObj.val(lang);
            fireEvent(db, 'change');
        }
    });
}

function onAlert(codeMessage)
{
    var messages = {"LOAD_FILE_ERROR":"Ошибка при загрузке файла", "TIMETABLE_GET_SUCCESS":"Расписание получено успешно",
        "SUMM_OF_WEIGHT_MUST_BE_MORE_THAN_ZERO":"Сумма весов должна быть больше 0", "WEIGHT_MUST_BE_MORE_THAN_ZERO":"Вес должен быть больше 0",
        "MUST_BE_LEAST_RIGHT_ANSWER":"Должен быть хотя бы один правильный ответ","MUST_BE_ONLY_ONE_RIGHT_ANSWER":"Должен быть только один правильный ответ","MUST_BE_ONLY_ONE_ANSWER":"Должен быть только один вариант ответа",
        "MUST_BE_ONLY_ONE_ANSWER":"Должен быть только один вариант ответа"};
    alert(messages[codeMessage]);

}

function onError(jqXHR, textStatus, errorThrown)
{
    //  alert(errorThrown);
    console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
}

function startConference(id)
{
    $.ajax({
        type: 'GET',
        url: '/webinar/startConference',
        data: {idMaterial: id},
        error: onError,
        success: function(data)
        {
            window.location = "/webinar/connectToConference?idMaterial="+id;
        }
    });
}

function updateSelectListeners(obj)
{
    $(".ms-selectable .ms-list").find("span").each(
        function()
        {
            text = $(this).html();
            if (text.indexOf($(obj).val())<0)
            {
                $(this).parent().addClass("invisible");
            } else
            {
                $(this).parent().removeClass("invisible");
            }
        }
    );

}

function ajaxUpdateAccess(elem)
{
    $.ajax({
        type: 'POST',
        url: '/controlMaterial/updateAccessInfo',
        data: $(elem.form).serialize()+"&idCourse="+window.currentCourse+"&idMaterial="+window.currentMaterial+"&AccessControlMaterial[idRecord]="+$("#"+elem.form.id+" #GroupSelect_select").val(),
        error: onError,
    });
}

function ajaxDeleteAccess(idAccess, idCourse, idMaterial)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $("#accessForm"+idAccess).remove();
        $.ajax({
            type: 'POST',
            url: '/controlMaterial/deleteAccessInfo',
            data: {id: idAccess},
            error: onError,
        });
    }
}

function ajaxAddAccess(idCourse, idMaterial, typeRelation)
{
    $.ajax({
        type: 'POST',
        url: '/controlMaterial/addAccessInfo',
        data: {idCourse:idCourse, idMaterial: idMaterial, typeRelation: typeRelation},
        error: onError,
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
        error: onError,
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
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            type: 'POST',
            url: '/learnMaterial/deleteAllNonUsedMaterials',
            error: onError,
            success: function(data)
            {
                $.fn.yiiGridView.update("media-grid");
            }
        });
    }
}

function ajaxDeleteUserFileAnswer(idMaterial)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            type: 'POST',
            url: '/controlMaterial/deleteUserFileAnswer',
            data: {idMaterial: idMaterial},
            error: onError,
            success: function(data)
            {
                window.location = "";
            }
        });
    }
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
                if (xhr.responseText == "success")
                {
                    window.location = "";
                } else
                {
                    onAlert("LOAD_FILE_ERROR");
                }
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
        error: onError,
        success: function(data)
        {
            $(obj).hide();
            $("#labelTitle"+id).show().html($(obj).val());
        }
    });
}

function startChangeLearnMaterialLink(obj,id)
{
    $(obj).hide();
    $("#editLink"+id).show().focus();
}

function ajaxChangeLearnMaterialLink(obj,id)
{
    $.ajax({
        type: 'POST',
        url: '/learnMaterial/changeLink',
        data: {idMaterial: id, link: $(obj).val()},
        error: onError,
        success: function(data)
        {
            $(obj).hide();
            $("#labelLink"+id).show().html($(obj).val());
        }
    });
}


function ajaxGetTimetable(idGroup)
{
    $.ajax({
        type: 'POST',
        url: '/group/getTimetable',
        data: {idGroup:idGroup},
        error: onError,
        success: function(data)
        {
            onAlert("TIMETABLE_GET_SUCCESS");
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
    return true;
}

function checkHasNewPassword()
{
    if ($("#haveNewPassword").prop("checked"))
        $(".divNewPassword").show();
    else
        $(".divNewPassword").hide();

}

function changeWeights(idMaterial,idCourse)
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
        onAlert("SUMM_OF_WEIGHT_MUST_BE_MORE_THAN_ZERO");
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
        success: function(data)
        {
            window.location =  "/editCourse?idCourse="+idCourse;
        },
        error: onError,
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
                    if($('.has-tip').length) {
                        $('.has-tip').frosty();
                        $('.has-tip.tip-bottom').frosty({
                            position: 'bottom'
                        });
                    }
                },
                error: onError,
            });
        },
        error: onError,
    });
}

function saveMark(idStudent,idControlMaterial,mark)
{
    $.ajax({
        url: '/controlMaterial/setMark',
        data: {idStudent: idStudent, idControlMaterial: idControlMaterial, mark: mark},
        type: "POST",
        error: onError,
    });

    var mark = $('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').val();
    if(parseInt(mark) < 25) {
        mark = '<span class="mark-bad">'+mark+'</span>';
    } else {
        mark = '<span class="mark-good">'+mark+'</span>';
    }
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').show();
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').parent().find("a").show();
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').html(mark);
    $('input[data-student='+idStudent+'][data-material='+idControlMaterial+']').hide();
}

function showMarksOfMaterial(idControlMaterial)
{
    $('div[data-material='+idControlMaterial+']').hide();
    $('div[data-material='+idControlMaterial+']').parent().find("a").hide();
    $('input[data-material='+idControlMaterial+']').show();
    $('input[data-material='+idControlMaterial+']').first().focus();
}

function showMarkTextbox(idStudent,idControlMaterial)
{
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').hide();
    $('div[data-student='+idStudent+'][data-material='+idControlMaterial+']').parent().find("a").hide();
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
        error: onError,
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
        error: onError,
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
        error: onError,
    });
}

function deleteTeacher(idCourse,idTeacher)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            url: '/courses/deleteTeacher',
            data: {idCourse: idCourse, idTeacher: idTeacher},
            type: "POST",
            success: function(data)
            {
                updateTeachers(idCourse);
            },
            error: onError,
        });
    }
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
        error: onError,
    });
}

function deleteGroup(idGroup,idTerm,idCourse)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            url: '/courses/deleteGroup',
            data: {idGroup: idGroup, idTerm: window.idTerm, idCourse: idCourse},
            type: "POST",
            success: function(data)
            {
                //            updateGroups(idCourse,idTerm);
            },
            error: onError,
        });
    }
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
        error: onError
    });
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
            console.log(xhr.responseText);
            if(xhr.status == 200) {
//                $("#loadfile").hide();
                $("#learnMaterialLoader").hide();
                $("#learnMaterialSubmitButton").show();
                if (xhr.responseText == "success")
                {
                    updateLearnMaterials(idCourse);
                    closeLearnMaterialDialog();
                } else
                {
                    onAlert("LOAD_FILE_ERROR");
                }
            }
        }
    };
    xhr.send(formData);
}


function deleteLearnMaterial(idCourse,idMaterial,idDiv)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            url: '/learnMaterial/deleteMaterial',
            data: {idCourse: idCourse, idMaterial:idMaterial},
            type: "POST",
            success: function(data)
            {
                $("#learnMaterialTable #"+idDiv).remove();
                //updateLearnMaterials(window.idCourse);
            },
            error: onError
        });
    }
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
            if($('.has-tip').length) {
                $('.has-tip').frosty();
                $('.has-tip.tip-bottom').frosty({
                    position: 'bottom'
                });
            }
            window.isDragged = false;
            selector  = "#learnMaterialTable tbody ";

            $("#learnMaterialTable tbody").sortable({
                items: 'tr',
                start: function (event, ui) {
                    console.log("start");
                    if ($(ui.item).hasClass("titleRow") && !window.isDragged)
                    {
                        idGroup1 = $(ui.item).attr('id');
                        $(selector+"tr").hide();
                        $(selector+".titleRow").show();
                        $(ui.item).hide();
                    }
                    window.isDragged = true;
                },
                stop: function(event, ui) {
                    console.log("stop");
                    window.isDragged = false;
                    $(selector+"tr").show();
                },
                update: function(event, ui ) {
                    if ($(ui.item).hasClass("titleRow"))
                    {
                        console.log("update");
                        idGroup1 = $(ui.item).attr('id');
                        idGroup2 = $(ui.item).prev().attr("data-idHeader") || 0;
                        if (idGroup2 == 0)
                            idGroup2 = $(ui.item).prev().attr('id');
                        console.log(idGroup2);
                        if (idGroup2 != idGroup1)
                            $(selector+"#"+idGroup2).insertBefore($(selector+"#"+idGroup1));
                        $(selector+"[data-idHeader="+idGroup1+"]").insertAfter($(selector+"#"+idGroup1));
                        $(selector+"[data-idHeader="+idGroup2+"]").insertAfter($(selector+"#"+idGroup2));
                        s = "";
                        $(selector+" tr").each(function()
                        {
                            s += $(this).attr('id')+",";
                        });
                        $.ajax({
                            url: '/learnMaterial/fullOrderMaterial',
                            data: {newOrder: s},
                            type: "POST",
                            error: onError,
                        });
                        return;
                    }
                    var itemId = $(ui.item).prev().attr('id') || 0;
                    idGroup2 = $(ui.item).prev().attr("data-idHeader") || 0;
                    if (idGroup2 == 0)
                        idGroup2 = $(ui.item).prev().attr('id');
                    $(ui.item).attr('data-idHeader',idGroup2);
                    $.ajax({
                        url: '/learnMaterial/orderMaterial',
                        data: {idMat: $(ui.item).attr('id'), idParentMat:itemId},
                        type: "POST",
                        error: onError,
                    });
                },
                helper: function(e, tr){
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                }
            });
            $('.changeOnEnter').keyup(function(e)
            {
                if (e.keyCode == 13)
                    $(this).change();
            });


            footerUpdate();
        },
        error: onError,
    });
}

function deleteControlMaterial(idCourse,idMaterial,idDiv)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            url: '/material/deleteMaterial',
            data: {idCourse: idCourse, idMaterial:idMaterial},
            type: "POST",
            success: function(data)
            {
                $("#controlMaterialTable #"+idDiv).remove();
            },
            error: onError,
        });
    }
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
                        error: onError,
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
        error: onError,
    });
}

function changeDiv(title,n)
{
    $("#modalTitle").html(" "+title);
    $("#LearnMaterial_category").val(n);
    if (n == 2)
    {
        $('.fileDiv').hide();
        $('.dateDiv').hide();
        $('.linkDiv').show();
    }
    if (n ==1 || n == 3)
    {
        $('.fileDiv').show();
        $('.dateDiv').hide();
        $('.linkDiv').hide();
    }
    if (n == 4)
    {
        $('.dateDiv').hide();
        $('.fileDiv').hide();
        $('.linkDiv').hide();
    }
    if (n == 7)
    {
        $('.dateDiv').show();
        $('.fileDiv').hide();
        $('.linkDiv').hide();
    }
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
                        error: onError,
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
        error: onError,
    });
}

function deleteQuestion(idQuestion,idControlMaterial)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            url: '/question/deleteQuestion',
            data: {idQuestion: idQuestion, idControlMaterial:idControlMaterial},
            type: "POST",
            success: function(data)
            {
                updateQuestions(idControlMaterial);
            },
            error: onError,
        });
    }
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
                        error: onError,
                    });
                }
            });
        },
        error: onError,
    });
}

function addAnswer(idQuestion)
{
    var questionType = $(".horizontal-buttons-list input[type='radio']:checked").val();
    if (questionType == 3 ||questionType==4)
    {
        onAlert("MUST_BE_ONLY_ONE_ANSWER");
        return;
    }
    $.ajax({
        type: "POST",
        url: '/answer/create',
        data: {idQuestion:idQuestion},
        success: function(data){
            updateAnswers(idQuestion);
        },
        error: onError,
    });
}

function deleteAnswer(idAnswer)
{
    if (confirm("Вы действительно хотите удалить?"))
    {
        $.ajax({
            url: '/answer/deleteMaterial',
            data: {idAnswer: idAnswer},
            type: "POST",
            success: function(data)
            {
                updateAnswers(window.idQuestion);
            },
            error: onError,
        });
    }
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
        error: onError,
    });
}

function isValidQuestion()
{
//    var questionType = $("#ytQuestion_type").val(); // Вот с этой фигней что-то придумать

    if ($("#Question_weight").val()<=0)
    {
        onAlert("WEIGHT_MUST_BE_MORE_THAN_ZERO");
        return false;
    }
    var questionType = $(".horizontal-buttons-list input[type='radio']:checked").val();
    var answerCount = $(".answer").length;
    var rightAnswerCount = $(".answer input:checked").length;
    if (rightAnswerCount == 0)
    {
        onAlert("MUST_BE_LEAST_RIGHT_ANSWER");
        return false;
    }
    if (questionType != 2 && questionType != 5 && rightAnswerCount>1)
    {
        onAlert("MUST_BE_ONLY_ONE_RIGHT_ANSWER");
        return false;
    }
    if ((questionType == 3||questionType == 4) && answerCount>1)
    {
        onAlert("MUST_BE_ONLY_ONE_ANSWER");
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
    moment.locale("ru");

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
    if ($('.dtPicker').length)
        $('.dtPicker').datetimepicker({ format:'d.m.Y H:i'});

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
                "searchreplace visualblocks code fullscreen sh4tinymce wordcount",
                "insertdatetime media table contextmenu paste jbimages media insert_control_material"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages media insert_control_material  sh4tinymce",
            relative_urls: false,
            init_instance_callback: "footerUpdate"
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
            selectableHeader: "<input style='margin-bottom:20px;' type='text' class='search-input' autocomplete='off' placeholder='Поиск слушателей' onchange='updateSelectListeners(this)'>",
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
    $("#XUploadForm-form").bind('fileuploadfail',function(e,data,x)
    {
        $('body').hideLoader();
        onAlert("LOAD_FILE_ERROR");
    });

    if ($("#rightAnswers").length)
        $("#rightAnswers").sortable();


    if($('.has-tip').length) {
        $('.has-tip').frosty();
        $('.has-tip.tip-bottom').frosty({
            position: 'bottom'
        });
    }

    $("#loginForm").submit(function(e) {
        e.preventDefault();

        var self = this;

        $.ajax({
            type: 'POST',
            url: '/user/checkOnAuthenticate',
            data: $("#loginForm").serialize(),
            error: onError,
            success: function(data)
            {
                if (data == "1") {
                    $("#wrongPassword").fadeOut(100);
                    self.submit();
                } else {
                    $("#loginForm").addClass("shakeIt");
                    if(!$("#wrongPassword").length) {
                        $("#loginForm button[type='submit']").after('<div id="wrongPassword" class="center red" style="display:none">Неправильный логин или пароль</div>');
                        $("#wrongPassword").fadeIn(100);
                    }
                    setTimeout(function() {
                        $("#loginForm").removeClass("shakeIt");
                    }, 1000);
                }
            }
        });
    });

    $("#messageTextArea").on('keydown', function(e){
        if (e.which === 13 && e.ctrlKey){
            $(this).parent().submit();
        }
    });

    // сложность пароля
    if($(".js-strength").length) {
        $(".js-strength").strength();
    }

    $(".fa.fa-remove").click(function() {
        // if(confirm("Действительно удалить?")) {
        //     return true;
        // } else {
        //     return false;
        // }
    });


    $('.fc-event').each(function() {
        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            idCourseControlMaterial: $(this).attr("data-idCourseControlMaterial"),
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });

    /* initialize the calendar
     -----------------------------------------------------------------*/
    if ($('#calendar').length)
    {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            lang: 'ru',
            height: 350,
            editable: true,
            eventDurationEditable: false,
            events: {
                url: '/courses/ajaxGetCalendarEvents',
                type: 'GET',
                data: {
                    id: window.idCalendarCourse == undefined ? 0 : window.idCalendarCourse,
                },
            },
            dragRevertDuration: 0,
            droppable: true, // this allows things to be dropped onto the calendar
            loading: function() {
                footerUpdate()
            },
            drop: function() {
                $(this).remove();
            },
            eventReceive: function(event)
            {
                $.ajax({
                    type: 'POST',
                    url: '/courses/ajaxSetEventTime',
                    data: {idCourseControlMaterial: event.idCourseControlMaterial, date: event._start.format()},
                    error: onError,
                });
            },
            eventRender: function(event, element) {
                if (event.description != undefined)
                {
                    element.attr("title", event.description);
                    element.frosty();
                }
            },
            eventDragStop: function( event, jsEvent, ui, view ) {
                // Проверить, не сняли ли с календаря объект
                var target=$(document.elementFromPoint(jsEvent.pageX, jsEvent.pageY));
                var flag = true;
                var i = 0;
                while (target[0].parentNode != null && i < 20)
                {
                    if (target.attr('id') == "calendar")
                    {
                        flag = false;
                        break;
                    }
                    target = $(target[0].parentNode);
                    i++;
                }
                if (flag)
                {
                    // Выкинули
                    var text = "<div class='fc-event' id = 'mat"+event.idCourseControlMaterial+"' data-idCourseControlMaterial = '"+event.idCourseControlMaterial+"'>"+event.title+"</div>";
                    $("#eventContainer").append(text);
                    var newId = "#mat"+event.idCourseControlMaterial;
                    $(newId).data('event', {
                        idCourseControlMaterial: $(newId).attr("data-idCourseControlMaterial"),
                        title: $.trim($(newId).text()), // use the element's text as the event title
                        stick: true // maintain when user navigates (see docs on the renderEvent method)
                    });

                    // make the event draggable using jQuery UI
                    $(newId).draggable({
                        zIndex: 999,
                        revert: true,      // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                    // Меняем в базе
                    $.ajax({
                        type: 'POST',
                        url: '/courses/ajaxSetEventTime',
                        data: {idCourseControlMaterial: event.idCourseControlMaterial, date: ""},
                        error: onError,
                    });
                    $('#calendar').fullCalendar('removeEvents',event._id);

                }

            },
            eventDrop: function(event, delta, revertFunc, jsEvent) {
                // Передвинули на календаре
                $.ajax({
                    type: 'POST',
                    url: '/courses/ajaxSetEventTime',
                    data: {idCourseControlMaterial: event.idCourseControlMaterial, date: event._start.format()},
                    error: onError,
                });
            },
        });
    }

    $("#user-form input").change(function(e)
    {
        $(".divOldPassword").show();
    });

    if($(".are-you-sure")) {
        $(".are-you-sure").areYouSure();
    }

    // Восстановление пароля
    $("[data-open-forgot-form]").click(function() {
        $(".login-tab").addClass("zoomOut");
        setTimeout(function() {
            $(".login-tab").hide().removeClass("zoomOut");
            $(".forgot-tab").show().addClass("zoomIn");
        }, 300);

        return false;
    });

    $("[data-open-login-form]").click(function() {
        $(".forgot-tab").addClass("zoomOut");
        setTimeout(function() {
            $(".forgot-tab").hide().removeClass("zoomOut");
            $(".login-tab").show().addClass("zoomIn");
        }, 300);

        return false;
    });

    $("#forgotForm").submit(function(e) {
        e.preventDefault();

        var self = this;

        $.ajax({
            type: 'POST',
            url: '/user/sendForgotMessage',
            data: $("#forgotForm").serialize(),
            error: onError,
            success: function(data)
            {
                console.log(data);

                if (data == "1") {
                    $("#wrongEmail").fadeOut(100);
                    alert("На вашу почту придет сообщение с инструкциями. Проверьте почту.");
                } else {
                    $("#forgotForm").addClass("shakeIt");
                    if(!$("#wrongEmail").length) {
                        $("#forgotForm button[type='submit']").after('<div id="wrongEmail" class="center red" style="display:none">Пользователя с таким E-Mail не существует.</div>');
                        $("#wrongEmail").fadeIn(100);
                    }
                    setTimeout(function() {
                        $("#forgotForm").removeClass("shakeIt");
                    }, 1000);
                }
            }
        });
    });

    /**
     * Загрузка факультетов
     */
    function loadFaculties(faculty, floader, group, gloader, group_id) {
        var fUrl = "https://altstu-schedule.herokuapp.com/api/faculties/",
            gUrl = "https://altstu-schedule.herokuapp.com/api/groupsByFaculty/";

        $.getJSON(fUrl, function(data) {
            $.each(data, function() {
                faculty.append('<option value="'+this.id+'">'+this.name+'</option>');
            });
            floader.fadeOut(300);
            if (typeof(window.faculty) != 'undefined')
            {
                faculty.val(window.faculty).change();
            }
        });


        faculty.change(function() {
            var v = $(this).val();

            gloader.fadeIn(300);
            group.html("");

            $.getJSON(gUrl + v, function(data) {
                $.each(data, function() {
                    group.append('<option value="'+this.id+'">'+this.name+'</option>');
                });
                if (typeof(window.id_altstu) != 'undefined')
                {
                    group.val(window.id_altstu);
                }
            });

            gloader.fadeOut(300);
            group.prop("disabled", false);
        });

        group.change(function() {
            var v = $(this).val();
            group_id.val(v);
        });
    }

    if($("#Group_faculty").length) {
        loadFaculties($("#Group_faculty"), $(".faculty-loader"), $("#Group_id_altstu"), $(".group-loader"), $("#Group_id_altstu"));
    }

    /**
     * Загрузка расписания
     */
    String.prototype.capitalizeFirstLetter = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    if($("#schedule-groups").length) {
        var groups = JSON.parse($("#schedule-groups").val())
        scheduleContent = $("#schedule-content"),
            url = "https://altstu-schedule.herokuapp.com/api/scheduleByGroup/",
            sloader = $(".schedule-loader");

        var week1spt = moment("09-01-" + new Date().getFullYear(), "MM-DD-YYYY").week(); // сравниваем если четность недели 1 сентября == четности текущией недели => 1 неделя

        $.each(groups, function() {
            var group = this;

            $.getJSON(url + group, function(data) {
                data = data[0];
                var thisWeekSchedule = week1spt&1 == moment().week() ? data["week1"] : data["week2"],
                    todayName = moment().format("dddd").capitalizeFirstLetter(),
                    todaySchedule = thisWeekSchedule[todayName];


                if(todaySchedule) {
                    $.each(todaySchedule, function() {
                        scheduleContent.append('<div class="sidebar-small-item"><span>'+this.name+' '+this.type+' '+this.room+'</span><div class="description">'+this.timeFrom+' — '+this.timeTo+'</div></div>')
                    });
                } else {
                    scheduleContent.html('<span>ЗАНЯТИЙ НЕТ</span>');
                }

                sloader.fadeOut(300);
            });
        });
    }

    if ($(".selectUser").length)
    {
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: '/message/ajaxGetUsers',
            height: 40,
            success: function(data)
            {
                $('.selectUser').each(function()
                {
                    $(this).append('<div class = "selectUserBox"></div>');
                    var selectUser = $(this).children(".selectUserBox");
                    selectUser.ddslick({
                        data:data,
                        width:274,
                        imagePosition:"left",
                        needTextBox: true,
                        placeholderTextBox: 'Выберите собеседника...',
                        onSelected: function(selectedData){
                            $($(selectedData.selectedItem).closest('.selectUser')).children("input").val(selectedData.selectedData.value).change();
                        }
                    });
                });
            }
        });
    }
    if ($(".selectTeacher").length)
    {
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: '/message/ajaxGetTeachers',
            height: 40,
            success: function(data)
            {
                $('.selectTeacher').each(function()
                {
                    $(this).append('<div class = "selectUserBox"></div>');
                    var selectUser = $(this).children(".selectUserBox");
                    selectUser.ddslick({
                        data:data,
                        width:'100%',
                        imagePosition:"left",
                        needTextBox: true,
                        placeholderTextBox: 'Выберите собеседника...',
                        onSelected: function(selectedData){
                            $($(selectedData.selectedItem).closest('.selectTeacher')).children("input").val(selectedData.selectedData.value).change();
                        }
                    });
                });
            }
        });
    }
});