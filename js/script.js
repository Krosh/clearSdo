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

function updateGroups(idCourse)
{
    $.ajax({
        url: '/courses/getGroups',
        data: {idCourse: idCourse},
        type: "POST",
        success: function(data)
        {
            $("#editCourse-groups").html(data);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function deleteGroup(idCourse,idGroup)
{
    $.ajax({
        url: '/courses/deleteGroup',
        data: {idCourse: idCourse, idGroup: idGroup},
        type: "POST",
        success: function(data)
        {
            updateGroups(idCourse);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        }
    });
}

function addGroup(groupTitle,termTitle,currentCourse)
{
    $.ajax({
        type: "POST",
        url: "/courses/addGroupToCourse",
        data: {groupTitle: groupTitle, termTitle : termTitle, idCourse:currentCourse},
        success: function(data)
        {
            updateGroups(currentCourse);
            $("#editCourse-groupSelect").hide();
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
                $("#editCourse-materialAdd").hide();
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



$(document).ready(function(){
    
    // Запуск лоадера, скрываем элемент
    if($(".login").length > 0) {
        $(".login").loader();   
    }
    
    // Дропдауны
    $(".dropdown").dropdown();
    
    // Меню
    $(".small-nav").nav();
    
    // Кастомные чекбоксы
    $("input[type=radio], input[type=checkbox]").picker();
    
    // Табер
    $(".tabbed").tabber();
    
    // Футер
    //$("footer").footer();
    
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
        updateGroups(window.idCourse);
        updateLearnMaterials(window.idCourse);
        updateControlMaterials(window.idCourse);
    }
});