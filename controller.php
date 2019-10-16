<?php

    $dbhost = 'localhost';
    $dbuser = 'MahdiRefaideen0819';
    $dbpass = '3c2#mahdirefaideen412';

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass) ;

    if (!$conn){
        die('Could not connect: ' . mysqli_error());
    }
    mysqli_select_db( $conn,"MahdiRefaideen0819_db");

    $rssfeedurl = "http://feeds.arstechnica.com/arstechnica/cars";
    $xml = simplexml_load_file($rssfeedurl);

    $title ='';
    $link ='';
    $post ='';

    for($i = 0; $i < 3; $i++){
        $title = $xml->channel->item[$i]->title;
        $link = $xml->channel->item[$i]->link;
        $post .= $i+1 .') '.$title.' : '.$link;
    }
    var_dump(' *** Post: '.$post.' *** ');

    global $body;
    $body = 'CarsTechnica: Recent Top 3 Stories'.$post;
    $result = json_encode($body);

    $query = mysqli_query($conn,"SELECT * FROM user");
    while($re=mysqli_fetch_array($query)){

        $sessionId = $re['sessionId'];
        var_dump(' *** sId: '.$sessionId.' *** ');
        $userId = $re['userId'];
        var_dump(' *** uId: '.$userId.' *** ');

        $json ='{
        "activeStatus": 4,
        "outputObject": {
            "result": ""
        },
        "settingsObject": {},
        "sessionId": "",
        "userId": ""
        }';

        $object =  json_decode($json);

        $object->outputObject->result = $body;
        $object->sessionId = $sessionId;
        $object->userId = $userId;

        var_dump(' *** Json: ');
        var_dump($object);

        $json = json_encode($object);


        /*------------  IdeaBiz part  is below. This is to invoke the Do Box when a certain condition is met------------------*/
        //Reading the stored file to get the existing refresh token. 
        //$myfile = fopen("token.txt", "r+") or die("Unable to open file!");
        //$refresh_token = fread($myfile, filesize("token.txt"));
        //fclose($myfile);
        
        //Getting the new access token  
        $ch2 = curl_init("https://ideabiz.lk/apicall/token?grant_type=password&username=mahdi&password=Admin@22451&scope=PRODUCTION");
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic dFh2NWZWS1o1Y3lLMEFaSmVqQjNzM2hQWVBZYTpwUm1zQjNLbUFub2NTZW4zTXBuWGNnQ2o1VkFh'));
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
        $res2 = curl_exec($ch2);
        curl_close($ch2);
        $acces_token_data = json_decode($res2);
        var_dump('###');
        var_dump($acces_token_data);
        $refresh_token = $acces_token_data->refresh_token;
        $access_token  = $acces_token_data->access_token;
        $myfile = fopen("token.txt", "w") or die("Unable to open file!");
        fwrite($myfile,'rt: '.$refresh_token.', at: '.$access_token);
        fclose($myfile);
    
        // This is to be if a user is invoking the service when a trigger is received
        $ch = curl_init("https://ideabiz.lk/apicall/isayyoudo/1.0/invokeUserCondition");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: text/plain',
                'Authorization: Bearer '.$access_token
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
            $res = curl_exec($ch);
            curl_close($ch);
        }
?>