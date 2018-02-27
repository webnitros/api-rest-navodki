<?php
/**
 * Created by Andrey Stepanenko.
 * User: Nitros
 * Date: 27.02.2018
 * Time: 17:16
 */
include dirname(__FILE__) . '/RestClient.php';


$api = new RestClientNavodki(array(
    'base_url' => 'http://dev.navodki.ru/api'
));


$results = $api->get("tenders", ['limit' => 100,'categories' => 381,382,728,]);
if ($results->info->http_code == 200) {
    //$results = $results->decode_response();
    foreach ($results->response as $result) {

        echo '<pre>';
        print_r($result); die;
    }
} else {
    echo '<pre>';
    print_r('Error'); die;
}