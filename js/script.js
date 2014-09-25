function loadCourses(idTerm,newTitle)
{
    // Обновляем окно
    $.ajax({
        url: '/courses/getCourses',
        data: {idTerm: idTerm},
        type: "POST",
        success: function(data)
        {
            $("#currentTermTitle").html(newTitle);
            $("#ajaxCoursesDiv").html(data);
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
    $("footer").footer();
    
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
    if (window.currentTerm !== undefined)
    {
        loadCourses(window.currentTerm);
    }
    $('[data-href]').click(function(){
        window.location = $(this).data('href');
        return false;
    });

    if ($('#timerSpan').length)
    {
        startTimer(document.getElementById("timerSpan"),window.endTime);
    }

});