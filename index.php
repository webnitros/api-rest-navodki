<?php
include dirname(__FILE__) . '/RestClient.class.php';

$api = new RestClient(array(
    'access_token' => 'c490a15ccdb2fec588e50cc86cea56753baf74a7',
    'client_secret' => '27918041a7dd4a9ce39a60c442400de7da9343e0',
));

$results = $api->get('tenders',
    array(
        'print' => 1,
        'limit' => 100,
        'categories' => 381,382,728
    )
);
if ($results->info->http_code == 200) {

    foreach ($results->response as $result) {

        echo '<pre>';
        print_r($result); die;

    }
} else {
    echo '<pre>';
    print_r($results->response);

    die('Error');
}