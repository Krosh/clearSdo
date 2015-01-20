<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 21.12.14
 * Time: 2:02
 * To change this template use File | Settings | File Templates.
 */

class AnalyzeTestPlugin
{
    public $name = "Сб-ть тестов";
    public $fullName = "Анализ сбалансированности тестов";
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

    public function render($params,$controller)
    {
        $tests = ControlMaterial::model()->findAll("idAutor = :idAutor AND (is_point IS NULL OR is_point = 0)", array(":idAutor" => Yii::app()->user->getId()));
        foreach ($tests as $controlMaterial)
        {
            $valBad = 0;
            $valPoor = 0;
            $valGood = 0;
            $valSuper = 0;
            $valTotal = 0;
            $criteria = new CDbCriteria();
            $criteria->compare("idControlMaterial", $controlMaterial->id);
            $criteria->addCondition("dateEnd IS NOT NULL");
            $tries = UserControlMaterial::model()->findAll($criteria);
            foreach ($tries as $try)
            {
                $valTotal++;
                if ($try->mark >= 75)
                    $valSuper++;
                elseif ($try->mark >= 50)
                    $valGood++;
                elseif ($try->mark >= 25)
                    $valPoor++;
                else
                    $valBad++;
            }
            if ($valTotal == 0)
            {
                $val = 100;
                $error = true;
                $valText = "Мало данных для анализа";
                continue;
            } else
            {
                $error = false;
                $valSuper /= $valTotal/100;
                $valGood /= $valTotal/100;
                $valPoor /= $valTotal/100;
                $valBad /= $valTotal/100;
                $xml =
                    '<?xml version="1.0" encoding="utf-8"?>
                     <ba_load_docs>
                       <docum>
                         <docum_shablon id_shablon="3"> </docum_shablon>
                         <docum_firma id_firma="2"> </docum_firma>
                         <docum_period id_periods="2"></docum_period>
                       </docum>
                       <pokazateli count="4">
                         <pokazatel id="4">
                           <value_pokazateli>'.$valBad.'</value_pokazateli>
                    </pokazatel>
                    <pokazatel id="5">
                      <value_pokazateli>'.$valPoor.'</value_pokazateli>
                    </pokazatel>
                    <pokazatel id="6">
                      <value_pokazateli>'.$valGood.'</value_pokazateli>
                    </pokazatel>
                    <pokazatel id="7">
                      <value_pokazateli>'.$valSuper.'</value_pokazateli>
                    </pokazatel>
                  </pokazateli>
                </ba_load_docs>
                ';

                $url = "http://ba40.ru/ba_xml.asmx?WSDL";
                $secretKey = "ba40_masterkey_04";
                $idBase = "_p11_13";

                $client = new SoapClient($url);
          //      $result = $client->GetDataTable(array("tableName" => "periods","secretKey" => $secretKey,"numConnectFile" => $idBase));
//        echo "[".$result->GetDataTableResult."]";
                $result = $client->SetDocums(array("xmlDocumsStru" => $xml,"secretKey" => $secretKey,"numConnectFile" => $idBase));
//        echo "[".$result->SetDocumsResult."]";
                $result = $client->RaschetMetodika(array("idMetodika" => 3,"idFirma" => 2, "idPeriod" => 2, "secretKey" => $secretKey,"numConnectFile" => $idBase));
//        echo "[".$result->RaschetMetodikaResult."]";
                $p = xml_parser_create();
                xml_parse_into_struct($p, $result->RaschetMetodikaResult, $vals, $index);
                xml_parser_free($p);

                $val = max($vals[1]["attributes"]["VALUE"]*100,0);
                if ($val>=66)
                    $valText = "Хорошо сбалансирован";
                elseif ($val>=33)
                    $valText = "Средне сбалансирован";
                else
                    $valText = "Плохо сбалансирован";
            }
            ?>
            <div class="progress-bar">
                <div class="progress-bar-title"><?php echo $controlMaterial->title." - ".$valText;?></div>

                <div class="progress-out">
                    <div class="progress-in" style="width: <?php echo $val; ?>%; <?php if ($error) echo "background-color:red;"?>"></div>
                </div>
            </div>
        <?php
        }
    }
}