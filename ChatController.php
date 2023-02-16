<?php

use Carbon\Carbon;

class ChatController extends BaseController
{
    public function startChat()
    {
        $check = $this->getUserLoginInformation();
        if($check['status'] == 0){
            $this->sendOutput(json_encode($check),array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $requestArray = ['receiver_id'];
        foreach ($requestArray as $req){
            if(!isset($_POST[$req]) || $_POST[$req] == ''){
                $this->returnGeneralResponse(0,$req.' is required');
            }
        }
        $receiver_id = $_REQUEST['receiver_id'];
        $sender_id = $check['data'][0]['user_id'];

        $senderQuery = 'SELECT * FROM chat_inbox WHERE sender_id='.$sender_id.' AND receiver_id='.$receiver_id;
        $senderResult = $this->select($senderQuery);
        $receiverQuery = 'SELECT * FROM chat_inbox WHERE sender_id='.$receiver_id.' AND receiver_id='.$sender_id;
        $receiverResult = $this->select($receiverQuery);

        if($senderResult && $receiverResult){
            $data = json_encode([
                'status' => 1,
                'message' => 'Chat already exists',
                'data' => $senderResult
            ]);
        }else{

            $timeStamp = date('Y-m-d H:i:s');
            $senderQuery = "INSERT INTO chat_inbox (sender_id,receiver_id,created_at,updated_at) VALUES ( '$sender_id', '$receiver_id','$timeStamp','$timeStamp')";
            $senderResult = $this->insert($senderQuery);
            $receiverQuery = "INSERT INTO chat_inbox (sender_id,receiver_id,created_at,updated_at) VALUES ( '$receiver_id', '$sender_id','$timeStamp','$timeStamp')";
            $receiverResult = $this->insert($receiverQuery);
            if($senderResult['affected_rows'] > 0 && $receiverResult['affected_rows'] > 0){
                $senderQuery = 'SELECT * FROM chat_inbox WHERE sender_id='.$sender_id.' AND receiver_id='.$receiver_id;
                $senderResult = $this->select($senderQuery);
                $data = json_encode([
                    'status' => 1,
                    'message' => 'Chat Initiated',
                    'data' => $senderResult
                ]);
            }

        }
        $this->sendOutput($data,array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function getInbox()
    {
        $check = $this->getUserLoginInformation();
        if($check['status'] == 0){
            $this->sendOutput(json_encode($check),array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }



        $sender_id = $check['data'][0]['user_id'];
        $senderQuery = 'SELECT chat_inbox.receiver_id, chat_inbox.*,concat(reciever.first_name," ",reciever.last_name) as receiver_name FROM chat_inbox join users as reciever on reciever.user_id = chat_inbox.receiver_id WHERE chat_inbox.sender_id='.$sender_id;

        $senderResult = $this->select($senderQuery);

        if($senderResult){
            $data = json_encode([
                'status' => 1,
                'message' => 'Inbox Loaded',
                'data' => $senderResult
            ]);
        }else{
            $data = json_encode([
                'status' => 0,
                'message' => 'Inbox Loaded',
                'data' => [],
            ]);
        }
        $this->sendOutput($data,array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function getMessages()
    {
        $check = $this->getUserLoginInformation();
        if($check['status'] == 0){
            $this->sendOutput(json_encode($check),array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $requestArray = ['receiver_id'];
        foreach ($requestArray as $req){
            if(!isset($_POST[$req]) || $_POST[$req] == ''){
                $this->returnGeneralResponse(0,$req.' is required');
            }
        }
        $receiver_id = $_REQUEST['receiver_id'];
        $sender_id = $check['data'][0]['user_id'];

        $senderQuery = 'SELECT * FROM chat_inbox WHERE sender_id='.$sender_id.' AND receiver_id='.$receiver_id;
        $senderResult = $this->select($senderQuery);

        if($senderResult){
            $id = $senderResult[0]['id'];
            $query = "SELECT * FROM chat_messages where chat_inbox_id = ".$id;
            $result = $this->select($query);

            if($result){
                $data = json_encode([
                    'status' => 1,
                    'message' => 'Messages Loaded',
                    'data' => $result
                ]);
            }else{
                $data = json_encode([
                    'status' => 0,
                    'message' => 'Messages Loaded',
                    'data' => [],
                ]);
            }
        }else{
            $data = json_encode([
                    'status' => 0,
                    'message' => 'No Chat Found',
                ]);
        }
        $this->sendOutput($data,array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function sendMessage()
    {
        $check = $this->getUserLoginInformation();
        if($check['status'] == 0){
            $this->sendOutput(json_encode($check),array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $requestArray = ['receiver_id','message'];
        foreach ($requestArray as $req){
            if(!isset($_POST[$req]) || $_POST[$req] == ''){
                $this->returnGeneralResponse(0,$req.' is required');
            }
        }

        $message = $_REQUEST['message'];
        $receiver_id = $_REQUEST['receiver_id'];
        $sender_id = $check['data'][0]['user_id'];

        $senderQuery = 'SELECT * FROM chat_inbox WHERE sender_id='.$sender_id.' AND receiver_id='.$receiver_id;
        $senderResult = $this->select($senderQuery);
        $receiverQuery = 'SELECT * FROM chat_inbox WHERE sender_id='.$receiver_id.' AND receiver_id='.$sender_id;
        $receiverResult = $this->select($receiverQuery);

        if($senderResult && $receiverResult){
            $timeStamp = date('Y-m-d H:i:s');
            $senderInboxID = $senderResult[0]['id'];
            $senderInboxUpdateQuery = "UPDATE chat_inbox SET message = '$message',read_at = '$timeStamp' WHERE id = '$senderInboxID'";
            $senderInboxUpdateResult = $this->update($senderInboxUpdateQuery);
            $senderMessageQuery = "INSERT INTO chat_messages (chat_inbox_id,message,created_at) VALUES ( '$senderInboxID', '$message','$timeStamp')";
            $senderMessageResult = $this->insert($senderMessageQuery);
            $receiverInboxID = $receiverResult[0]['id'];
            $receiverInboxUpdateQuery = "UPDATE chat_inbox SET message = '$message' WHERE id = '$receiverInboxID'";
            $receiverInboxUpdateResult = $this->update($receiverInboxUpdateQuery);
            $receiverMessageQuery = "INSERT INTO chat_messages (chat_inbox_id,message,message_type,created_at) VALUES ( '$receiverInboxID', '$message',1,'$timeStamp')";
            $receiverMessageResult = $this->insert($receiverMessageQuery);

            if($senderInboxUpdateResult && $senderMessageResult['affected_rows'] && $receiverInboxUpdateResult && $receiverMessageResult['affected_rows']){
                $this->returnGeneralResponse(1,'Message Send Successfully');
            }else{
                $this->returnGeneralResponse(0,'Something went wrong please try again');
            }
        }else{
            $this->returnGeneralResponse(0,'Something went wrong please try again');
        }
    }

}