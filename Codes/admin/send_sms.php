<?php
    
    include('../queue_log.php');
    include ('db_connect.php');
    require_once '../vendor/autoload.php';
    use GuzzleHttp\Client;

        $qry = $conn->query("SELECT msg FROM msg_temp");
        if ($qry->num_rows > 0)
        {
            foreach ($qry->fetch_array() as $value)
            {
                $msg = $value;
            }
            
        }
        else
        {
            $msg = "You are next in queue. Kindly proceed back to the showroom to be served.";
        }

    function SendSms($phone_number,$queue_no) 
    {
        $msg = $GLOBALS['msg'];
        $search = array(
            '{{QUEUE_NUMBER}}',
        );
        $replace = array(
            $queue_no,
        );
        $message = str_replace($search, $replace, $msg);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://gaincity.net/api/talariax/sendSMS', [ //https://sms.gaincity.net/api/talariax/sendSMS
            'json' => [
                'MobileNo' => $phone_number,
                'Msg' => $message,
                'TrackId' => 'testpassword',
            ],
            'headers' => ['Content-Type' => 'application/json']
        ]);
        echo AddLog($message);
        echo AddLog($phone_number);
        echo AddLog($response->getBody());
    }

?>


 