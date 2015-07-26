<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
/** @var Course $model */
?>

<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
<link rel="stylesheet" type="text/css" href="/css/calendar/fullcalendar.css" />
<link rel="stylesheet" type="text/css" href="/css/calendar/fullcalendar.print.css" media="print"/>



<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading col-group">
                        <div class="col-6">
                            <div class="page-title">Календарь курса <?php echo $model->title; ?></div>
                        </div>
                    </div>

                    <div id = "calendar">

                    </div>

                </div>

            </div>
            <div class="col-3">
                <div class="sidebar">

                    <div class="sidebar-item">
                        <div class="sidebar-title">
                            Контрольные точки
                        </div>
                        <div class="sidebar-content" id = "eventContainer">
                            <?php foreach ($withoutDateMaterials as $item):?>
                                <div class='fc-event' id = "material<?php echo $item->id; ?>" data-idCourseControlMaterial = "<?php echo $item->id; ?>"><?php echo $item->controlMaterial->title; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php $this->renderPartial("//news/block") ?>
                    <?php $this->renderPartial("//news/timetable") ?>




                </div>
            </div>


            <script>

                $(document).ready(function() {


                    /* initialize the external events
                     -----------------------------------------------------------------*/

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

                    $( "#eventContainer" ).droppable({
                        drop: function( event, ui ) {
                            console.log("dropped!");
                        }
                    });

                    /* initialize the calendar
                     -----------------------------------------------------------------*/

                    $('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        editable: true,
                        eventDurationEditable: false,
                        events: {
                            url: '<?php echo $this->createUrl("/courses/ajaxGetCalendarEvents", array("id" => $model->id)); ?>',
                            type: 'GET',
                            data: {
                                id: <?php echo $model->id; ?>,
                            },
                        },
                        dragRevertDuration: 0,
                        droppable: true, // this allows things to be dropped onto the calendar
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


                });
            </script>