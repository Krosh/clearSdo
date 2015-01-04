<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 21.12.14
 * Time: 2:02
 * To change this template use File | Settings | File Templates.
 */

class AnalyzeUserTestPlugin
{
    public $name = "См-ть прохождения";
    public $fullName = "Анализ самостоятельности прохождения тестов";
    public $id = 0;

    public function getUrl($params = array())
    {
        $s = "/site/plugin";
        $paramText = "?id=".$this->id;
        foreach ($params as $key => $value)
        {
            $paramText.="&".$key."=".$value;
        }
        return $s.$paramText;
    }

    public function renderAjax($params,$controller)
    {
        $results = array();
        $userMaterials = UserControlMaterial::model()->findAll("idControlMaterial = :idMat", array(":idMat" => $params["idMaterial"]));
        foreach ($userMaterials as $userMaterial)
        {
            $answers = UserAnswer::model()->findAll("idUserControlMaterial = :idMat", array(":idMat" => $userMaterial->id));
            foreach ($answers as $answer)
            {
                echo $answer->idQuestion;
                $question = Question::model()->findByPk($answer->idQuestion);
                $mark = $question->getMark($answer);
                if (!isset($results[$userMaterial->idUser]))
                    $results[$userMaterial->idUser] = array(0,0,0,0);
                if ($mark>=75)
                    $results[$userMaterial->idUser][3]++;
                elseif ($mark >= 50)
                    $results[$userMaterial->idUser][2]++;
                elseif ($mark >= 25)
                    $results[$userMaterial->idUser][1]++;
                else
                    $results[$userMaterial->idUser][0]++;
            }
        }
        foreach ($results as $key => $value)
        {
            if ($value[0]+$value[1]+$value[2]+$value[3] == 0)
            {
                $val = 100;
                $valText = "Мало данных для анализа";
                continue;
            } else
            {
                $xml =
                    '<?xml version="1.0" encoding="utf-8"?>
                     <ba_load_docs>
                       <docum>
                         <docum_shablon id_shablon="4"> </docum_shablon>
                         <docum_firma id_firma="2"> </docum_firma>
                         <docum_period id_periods="2"></docum_period>
                       </docum>
                       <pokazateli count="4">
                         <pokazatel id="8">
                           <value_pokazateli>'.$value[0].'</value_pokazateli>
                    </pokazatel>
                    <pokazatel id="9">
                      <value_pokazateli>'.$value[1].'</value_pokazateli>
                    </pokazatel>
                    <pokazatel id="10">
                      <value_pokazateli>'.$value[2].'</value_pokazateli>
                    </pokazatel>
                    <pokazatel id="11">
                      <value_pokazateli>'.$value[3].'</value_pokazateli>
                    </pokazatel>
                  </pokazateli>
                </ba_load_docs>
                ';

                $url = "http://ba40.ru/ba_xml.asmx?WSDL";
                $secretKey = "ba40_masterkey_04";
                $idBase = "_p11_13";

                $client = new SoapClient($url);
                $result = $client->GetDataTable(array("tableName" => "periods","secretKey" => $secretKey,"numConnectFile" => $idBase));
//        echo "[".$result->GetDataTableResult."]";
                $result = $client->SetDocums(array("xmlDocumsStru" => $xml,"secretKey" => $secretKey,"numConnectFile" => $idBase));
//        echo "[".$result->SetDocumsResult."]";
                $result = $client->RaschetMetodika(array("idMetodika" => 4,"idFirma" => 2, "idPeriod" => 2, "secretKey" => $secretKey,"numConnectFile" => $idBase));
//        echo "[".$result->RaschetMetodikaResult."]";
                $p = xml_parser_create();
                xml_parse_into_struct($p, $result->RaschetMetodikaResult, $vals, $index);
                xml_parser_free($p);
                $val = max($vals[1]["attributes"]["VALUE"]*100,0);
                if ($val > 10)
                {
                    $color = "red";
                    $valText = "Очень подозрительное прохождение";
                } elseif ($val>0)
                {
                    $color = "yellow";
                    $valText = "Подозрительное прохождение";
                }
                else
                {
                    $color = false;
                    $valText = "Ничего подозрительного";
                }
                $val = 100;
            }
            ?>
            <div class="progress-bar">
                <div class="progress-bar-title"><?php echo User::model()->findByPk($key)->fio." - ".$valText;?></div>

                <div class="progress-out">
                    <div class="progress-in" style="width: <?php echo $val; ?>%; <?php if ($color) echo "background-color:".$color.";"?>"></div>
                </div>
            </div>
        <?php
        }

    }

    public function render($params,$controller)
    {
        if ($params["action"] == "ajaxUpdate")
        {
            $this->renderAjax($params,$controller);
            return;
        }
        $mas = array();
        $models = ControlMaterial::model()->findAll("idAutor = ".Yii::app()->user->getId());
        foreach ($models as $item)
        {
            $mas[$item->id] = $item->id." ".$item->title;
        }
        $fakeModel = new ControlMaterial();
        $fakeModel->title = "";
        echo "Выберите тест для анализа:";
        $controller->widget('ext.combobox.EJuiComboBox', array(
            'model' => $fakeModel,
            'attribute' => 'title',
            'data' => $mas,
            'options' => array(
                'onSelect' => '
                     $.ajax({
                        type: "POST",
                        url: "'.$this->getUrl(array("action" => "ajaxUpdate")).'",
                        data: {idMaterial: item.value.split(" ")[0]},
                        success: function(data)
                        {
                            $("#analyzeResult").html(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert("error"+textStatus+errorThrown);
                        }});',
                'allowText' => false,
            ),
            'htmlOptions' => array('size' => 30),
        ));
        ?>
        <br>
        <div id = "analyzeResult">
        </div>
    <?php
    }
}