<?php

use Carbon\Carbon;

class DashboardController extends BaseController
{

    public function getPatientAppointments()
    {

        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $patient_id = $check['data'][0]['user_id'];

        $query = 'SELECT appointments.*, CONCAT(doctor.first_name," ",doctor.last_name) AS docter_name FROM appointments JOIN users AS doctor on doctor.user_id = appointments.doctor_id where appointments.patient_id = ' . $patient_id;
        $result = $this->select($query);
        if ($result) {
            $data = json_encode([
                'status' => 1,
                'message' => 'List of Patient Appointments',
                'data' => $result
            ]);
        } else {
            $data = json_encode([
                'status' => 1,
                'message' => 'List of Patient Appointments',
                'data' => []
            ]);
        }
        $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function getDoctorAppointments()
    {
        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $doctor_id = $check['data'][0]['user_id'];

        $query = 'SELECT appointments.*, CONCAT(doctor.first_name," ",doctor.last_name) AS docter_name FROM appointments JOIN users AS doctor on doctor.user_id = appointments.doctor_id where appointments.doctor_id = ' . $doctor_id;
        $result = $this->select($query);

        if ($result) {
            $data = json_encode([
                'status' => 1,
                'message' => 'List of Doctor Appointments',
                'data' => $result
            ]);
        } else {
            $data = json_encode([
                'status' => 1,
                'message' => 'List of Doctor Appointments',
                'data' => []
            ]);
        }
        $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function getDoctorAvaialableTime()
    {
        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
        if (!isset($_REQUEST['doctor_id']) || empty($_REQUEST['doctor_id'])) {
            $this->returnGeneralResponse(0, 'Doctor ID is required');
        }

        $date = $_REQUEST['date'];

        $query = "SELECT user_id,user_type_id,user_profile_id FROM users where user_id =" . $_REQUEST['doctor_id'];
        $result = $this->select($query);

        if ($result) {
            $profileID = $result[0]['user_profile_id'];
            $query = "SELECT business_hours,cost FROM user_profile where user_profile_id =" . $profileID;
            $result = $this->select($query);
            if ($result) {
                $week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $businessHours = $result[0]['business_hours'];
                $businessHours = explode(',', $businessHours);
                $month = [];
                $day = isset($date) && $date != '' ? Carbon::createFromFormat('Y-m-d', $date) : Carbon::now();
                $dayInISO = $day->isoWeekday();
                $businessDay = $businessHours[$dayInISO - 1];
                if (!strpos($businessDay, 'Closed')) {
                    $timing = explode('-', $businessDay);
                    $opening = $timing[0];
                    $closing = $timing[1];

                    $slots = $this->getTimeSlot(60, $opening, $closing);

                    $month = [
                        'day' => $day->format('l'),
                        'date' => $day->format('d-m-Y'),
                        'slot_date' => $day->format('Y-m-d'),
                        'slots' => $slots
                    ];
                } else {
                    $month = [
                        'day' => $day->format('l'),
                        'date' => $day->format('d-m-Y'),
                        'slot_date' => $day->format('Y-m-d'),
                        'slots' => ['Closed']
                    ];
                }
                for ($i = 0; $i <= 30; $i++) {

                }
                $data = json_encode([
                    'status' => 1,
                    'message' => 'Appontment Slots',
                    'data' => $month
                ]);
                $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));


            } else {
                $this->returnGeneralResponse(0, 'No Record Found');
            }
        } else {
            $this->returnGeneralResponse(0, 'No Record Found');
        }


    }

    public function getTimeSlot($interval, $start_time, $end_time)
    {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('h:i A');
        $endTime = $end->format('h:i A');
        $i = 0;
        $time = [];
        while (strtotime($startTime) <= strtotime($endTime)) {
            $start = $startTime;
            $end = date('h:i A', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
            $startTime = date('h:i A', strtotime('+' . $interval . ' minutes', strtotime($startTime)));

            if (strtotime($startTime) <= strtotime($endTime)) {
                $time[$i++] = $start;

            }
        }
        return $time;
    }

    public function bookAppointment()
    {
        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        if ($check['data'][0]['user_type_id'] == 2) {
            $this->returnGeneralResponse(0, 'doctor cannot make an appointment');
        }

        $requestArray = ["doctor_id", "message", "first_name", "last_name", "mobile", "email", "dob", "gender", "appointment_date_time"];

        foreach ($requestArray as $req) {
            if (!isset($_POST[$req]) || $_POST[$req] == '') {
                $this->returnGeneralResponse(0, $req . ' is required');
            }
        }

        $patient_id = $check['data'][0]['user_id'];
        $doctor_id = $_POST["doctor_id"];
        $message = $_POST["message"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $mobile = $_POST["mobile"];
        $email = $_POST["email"];
        $dob = $_POST["dob"];
        $gender = $_POST["gender"];
        $appointment_date_time = $_POST["appointment_date_time"];

        $sql = "INSERT INTO appointments(patient_id, doctor_id, comments, patient_first_name, patient_last_name, patient_email, patient_mobile, patient_dob, patient_gender, appointment_datetime, system_logs_id, appointment_status) 
            VALUES 
        ('$patient_id','$doctor_id','$message','$first_name','$last_name','$email','$mobile','$dob','$gender','$appointment_date_time','1','0')";

        $result = $this->insert($sql, []);
        if ($result['affected_rows'] > 0) {
            $query = "SELECT appointments.*, CONCAT(doctor.first_name,' ',doctor.last_name) AS docter_name FROM appointments,users AS doctor where appointments.appointment_id = " . $result["insert_id"];
            $result = $this->select($query);
            if (count($result)) {
                $data = json_encode([
                    'status' => 1,
                    'message' => 'Successfully Booked',
                    'data' => $result[0]
                ]);
                $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
        }
    }

    public function updateAppointment()
    {
        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $data = json_encode([
            'status' => 0,
            'message' => 'Error nothing ran ',
            'data' => null
        ]);



        if (!isset($_REQUEST['appointment_id']) || empty($_REQUEST['appointment_id'])) {
            $this->returnGeneralResponse(0, 'Appointment ID is required');
        }

        if (!isset($_REQUEST['status'])) {
            $this->returnGeneralResponse(0, 'Status is required');
        }

        $appointment_id = $_REQUEST['appointment_id'];
        $status = $_REQUEST['status'] == 1 ? 1 : 0;

        $query = 'SELECT appointments.* FROM appointments  where appointments.appointment_id = ' . $appointment_id;
        $result = $this->select($query);

        if ($result) {
            if($result[0]['status'] == $status){
                $data = json_encode([
                    'status' => 1,
                    'message' => 'Appointment status updated',
                    'data' => null
                ]);
            }

        }

        $query = "UPDATE appointments SET appointment_status = '$status' WHERE (appointment_id = '$appointment_id')";
        $result = $this->update($query);
        if ($result) {
            $data = json_encode([
                'status' => 1,
                'message' => 'Appointment status updated',
                'data' => null
            ]);



        }

        try {
            $firebaseToken = '';
            $query1 = 'SELECT patient_id FROM appointments where appointment_id = '.$appointment_id;
            $result1 = $this->select($query1);

            if($result1){
                if(count($result1)){
                    $user_id = $result1[0]['patient_id'];
                }
            }

            $query2 = 'SELECT * FROM users where user_id = '.$user_id;
            $result2 = $this->select($query2);

            if($result2){
                if(count($result2)){
                    $firebaseToken = $result2[0]['device_token'];
                }
            }


            $SERVER_API_KEY = "AAAA-y9WUfg:APA91bHk0MoxAa2icRiWyLgdpKb0gKKjPiq29hFiS6by3APaZM1XgweH5itmi8wZhVlL3ph2VFwnHyVQI-OVpY98Z3bNDrnVwYxLfc4kWpDG01Ty4UadOXdThTi3ZEB2qcS7_08uDWo6";

            if($status = $_REQUEST['status'] == 1){
                $message = "Doctor has confirmed appointment";
            }else{
                $message = "Doctor has rejected your appointment";
            }

            $datafireBase = [
                "to" => $firebaseToken,
                "notification" => [
                    "title" => $message,
                    "body" => $message,
                ]
            ];
            $dataString = json_encode($datafireBase);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

        }catch(\Throwable $e){

        }

        $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function getDoctorService()
    {

        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
        $sender_id = $check['data'][0]['user_id'];
        $receiverQuery = 'SELECT user_id FROM users WHERE NOT user_id = ' . $sender_id . ' ORDER BY RAND() LIMIT 1;';

        $receiver = $this->select($receiverQuery);
        $receiver_id = $receiver[0]['user_id'];


        $senderQuery = 'SELECT * FROM chat_inbox WHERE sender_id=' . $sender_id . ' AND receiver_id=' . $receiver_id;
        $senderResult = $this->select($senderQuery);
        $receiverQuery = 'SELECT * FROM chat_inbox WHERE sender_id=' . $receiver_id . ' AND receiver_id=' . $sender_id;
        $receiverResult = $this->select($receiverQuery);

        if ($senderResult && $receiverResult) {
            $data = json_encode([
                'status' => 1,
                'message' => 'Chat already exists',
                'data' => $senderResult
            ]);
        } else {

            $timeStamp = date('Y-m-d H:i:s');
            $senderQuery = "INSERT INTO chat_inbox (sender_id,receiver_id,created_at,updated_at) VALUES ( '$sender_id', '$receiver_id','$timeStamp','$timeStamp')";
            $senderResult = $this->insert($senderQuery);
            $receiverQuery = "INSERT INTO chat_inbox (sender_id,receiver_id,created_at,updated_at) VALUES ( '$receiver_id', '$sender_id','$timeStamp','$timeStamp')";
            $receiverResult = $this->insert($receiverQuery);
            if ($senderResult['affected_rows'] > 0 && $receiverResult['affected_rows'] > 0) {
                $senderQuery = 'SELECT * FROM chat_inbox WHERE sender_id=' . $sender_id . ' AND receiver_id=' . $receiver_id;
                $senderResult = $this->select($senderQuery);
                $data = json_encode([
                    'status' => 1,
                    'message' => 'Chat Initiated',
                    'data' => $senderResult
                ]);
            }

        }
        $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function contactForm()
    {
        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $requestArray = ["name", "email", "phone", "subject", "message"];

        foreach ($requestArray as $req) {
            if (!isset($_POST[$req]) || $_POST[$req] == '') {
                $this->returnGeneralResponse(0, $req . ' is required');
            }
        }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $tel = $_POST['phone'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $senderQuery = "INSERT INTO 1st_levelambulance(name, email, tel, subject, message) VALUES ('$name', '$email', '$tel', '$subject', '$message')";;
        $senderResult = $this->insert($senderQuery);
        if ($senderResult['affected_rows'] > 0) {
            $data = json_encode([
                'status' => 1,
                'message' => 'request sent'
            ]);
        } else {
            $data = json_encode([
                'status' => 0,
                'message' => 'some thing went wrong'
            ]);
        }
        $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function updateProfile()
    {

        $check = $this->getUserLoginInformation();
        if ($check['status'] == 0) {
            $this->sendOutput(json_encode($check), array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

        $requestArray = ["first_name", "last_name", "mobile"];

        foreach ($requestArray as $req) {
            if (!isset($_POST[$req]) || $_POST[$req] == '') {
                $this->returnGeneralResponse(0, $req . ' is required');
            }
        }

        $user_id = $check['data'][0]['user_id'];

        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $mobile = $_POST["mobile"];


        $sql = "UPDATE users SET first_name = '$first_name',last_name = '$last_name', mobile = '$mobile' WHERE (user_id = '$user_id')";


        $senderResult = $this->update($sql);
        if ($senderResult > 0) {
            $data = json_encode([
                'status' => 1,
                'message' => 'Profile updated'
            ]);
        } else {
            $data = json_encode([
                'status' => 0,
                'message' => 'some thing went wrong'
            ]);
        }
        $this->sendOutput($data, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }




}