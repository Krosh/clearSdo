<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.07.15
 * Time: 14:42
 * To change this template use File | Settings | File Templates.
 */?>


<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">
                            Статистика по: <?php echo $controlMaterial->title; ?></div>
                        <a href = "<?php echo $this->createUrl("/site/editCourse", array("idCourse" => Yii::app()->session['currentCourse'])); ?>"><div>Вернуться к курсу</div></a>
                        <div class="clearfix"></div>
                        <div id = "chartContainer" style="width: 100%; height: 400px">

                        </div>
                        <script>
                            var bad =<?php echo $result['bad']?>;
                            var ok = <?php echo $result['ok']?>;
                            var good =<?php echo $result['good']?>;
                            var excellent = <?php echo $result['excellent']?>;
                            $(function () {
                                $('#chartContainer').highcharts({
                                    chart: {
                                        plotBackgroundColor: null,
                                        plotBorderWidth: null,
                                        plotShadow: false,
                                        type: 'pie'
                                    },
                                    title: {
                                        text: 'Статистика по прохождениям теста'
                                    },
                                    tooltip: {
                                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                    },
                                    plotOptions: {
                                        pie: {
                                            allowPointSelect: true,
                                            cursor: 'pointer',
                                            dataLabels: {
                                                enabled: true,
                                                format: '<b>{point.name}</b>: {point.y}',
                                                style: {
                                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                                }
                                            }
                                        }
                                    },
                                    series: [{
                                        name: "Процент пользователей",
                                        colorByPoint: true,
                                        data: [
                                            {
                                                color: "#e74c3c",
                                                name: "Неудовлетворительно",
                                                y: bad
                                            },
                                            {
                                                color: "#ffc90e",
                                                name: "Удовлетворительно",
                                                y: ok
                                            },
                                            {
                                                color: "#33913a",
                                                name: "Хорошо",
                                                y: good
                                            },
                                            {
                                                color: "#00b16a",
                                                name: "Отлично",
                                                y: excellent
                                            },
                                        ]
                                    }]
                                });
                            });
                        </script>
                    </div>

                </div>
            </div>