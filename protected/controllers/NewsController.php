<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class NewsController extends CController
{
    public function filters()
    {
        return array(
            array('application.filters.TimezoneFilter')
        );
    }

    function getContent($url, array $post){
        $ecx = count($post);
        while($ecx--){
            @$post['fields'].=key($post).'='.array_shift($post).'&';
        }
        $post = rtrim($post['fields'], '&');
        try {
            $crl = curl_init($url);
            curl_setopt_array($crl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
                    CURLOPT_FOLLOWLOCATION => 1
                )
            );
            if(!($html = curl_exec($crl))) throw new Exception();
            curl_close($crl);
            return $html;

        } catch(Exception $e){
            return FALSE;
        }
    }



    public function actionNews()
    {
        $this->renderPartial('ajax');
    }

}