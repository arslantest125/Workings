<?php



class GeneralController extends BaseController
{


    public function loginAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if($requestMethod != 'POST'){
            $this->sendOutput(
                json_encode([
                    'status' => 0,
                    'message' => 'Invalid Request Method',
                    'data' => null
                ]),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }

        if (isset($_POST["email"]) and isset($_POST["password"])) {
            $key = md5("HarshThakkar");
            $email = addslashes(trim($_POST["email"]));
            $password = md5(md5($key . addslashes(trim($_POST["password"])) . $key));
            $deviceToken = $_REQUEST['device_token'] ?? null;
            $query = "SELECT*FROM users,user_types where users.email='$email' and users.password='$password' and users.user_type_id=user_types.user_types_id";
            $result = $this->select($query,[]);
            if(count($result) == 1){
                $token = base64_encode(serialize($result));
                $query1 = "UPDATE users SET device_token = '$deviceToken' WHERE email = '$email'";
                $result1 = $this->update($query1);
                $query = "SELECT*FROM users,user_types where users.email='$email' and users.password='$password' and users.user_type_id=user_types.user_types_id";
                $result = $this->select($query,[]);
                if(count($result) == 1){
                    $data = json_encode([
                        'status' => 1,
                        'message' => 'Successfully Logged In',
                        'access_token' => $token,
                        'data' => $result[0]
                    ]);
                }
            }else{
                $data = json_encode([
                    'status' => 0,
                    'message' => 'Invalid Credentials',
                    'data' => null
                ]);
            }
        }else{
            $data = json_encode([
                'status' => 0,
                'message' => 'Invalid Parameters',
                'data' => null
            ]);
        }

        $this->sendOutput(
            $data,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function registerAction()
    {
        if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["mobile"]) && isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["address"]) && isset($_POST["dob"]) && isset($_POST["gender"])) {
            $key = md5("HarshThakkar");
            $first_name = addslashes(trim($_POST["first_name"]));
            $last_name = addslashes(trim($_POST["last_name"]));
            $email = addslashes(trim($_POST["email"]));
            $mobile = addslashes(trim($_POST["mobile"]));
            $password = addslashes(trim($_POST["password"]));
            $confirm_password = addslashes(trim($_POST["confirm_password"]));
            $address = addslashes(trim($_POST["address"]));
            $dob = addslashes(trim($_POST["dob"]));
            $gender = addslashes(trim($_POST["gender"]));
            $userType = $_REQUEST['user_type'];
            $deviceToken = $_REQUEST['device_token'] ?? null;
            if($userType == 2){

                if(!isset($_REQUEST['title']) || empty($_REQUEST['title'])){
                    $message = 'title is required';
                    return $this->returnGeneralResponse(0,$message);
                }
                if(!isset($_REQUEST['description']) || empty($_REQUEST['description'])){
                    $message = 'description is required';
                    return $this->returnGeneralResponse(0,$message);
                }
                if(!isset($_REQUEST['specialities']) || empty($_REQUEST['specialities'])){
                    $message = 'specialities is required';
                    return $this->returnGeneralResponse(0,$message);
                }
                if(!isset($_REQUEST['cost']) || empty($_REQUEST['cost'])){
                    $message = 'cost is required';
                    return $this->returnGeneralResponse(0,$message);
                }

                $title = $_REQUEST['title'];
                $description = $_REQUEST['description'];
                $specialities = $_REQUEST['specialities'];
                $cost = $_REQUEST['cost'];

            }

            if ($password == $confirm_password) {
                $password = md5(md5($key . addslashes(trim($password)) . $key));
                if($userType == 2){
                    $sql1 = "INSERT INTO user_profile(title, description, specialities, cost, business_hours, feedback_id) VALUES ('$title', '$description', '$specialities', '$cost', '09:00 AM - 6:00 PM, 09:00 AM - 6:00 PM, 09:00 AM - 6:00 PM, 09:00 AM - 6:00 PM, 09:00 AM - 6:00 PM, 09:00 AM - 6:00 PM, Closed,','1')";
                    $result1 = $this->insert($sql1,[]);
                    if ($result1['affected_rows'] > 0){
                        $user_profile_id = $result1['insert_id'];
                        $sql = "INSERT INTO users(user_type_id, user_profile_id ,first_name, last_name, email, password, mobile, address, gender, dob, status, device_token) VALUES (2,'$user_profile_id','$first_name', '$last_name', '$email', '$password', '$mobile', '$address', '$gender', '$dob', '1','$deviceToken')";
                        $result = $this->insert($sql,[]);
                    }

                }else{
                    $sql = "INSERT INTO users(user_type_id, first_name, last_name, email, password, mobile, address, gender, dob, status) VALUES (1,'$first_name', '$last_name', '$email', '$password', '$mobile', '$address', '$gender', '$dob', '1')";
                    $result = $this->insert($sql,[]);
                }

                if($result['affected_rows'] > 0){
                    $query = "SELECT*FROM users,user_types where users.email='$email' and users.password='$password' and users.user_type_id=user_types.user_types_id";
                    $result = $this->select($query,[]);
                    if(count($result) == 1){
                        $token = base64_encode(serialize($result));
                        $data = json_encode([
                            'status' => 1,
                            'message' => 'Successfully Registered',
                            'access_token' => $token,
                            'data' => $result[0]
                        ]);
                    }
                }else{
                    $data = json_encode([
                        'status' => 0,
                        'message' => 'User Already Exists',
                        'data' => null
                    ]);
                }

            } else {
                $data = json_encode([
                    'status' => 0,
                    'message' => 'Both password should be same',
                    'data' => null
                ]);
            }
        }

        $this->sendOutput(
            $data,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function searchDoctorsAction()
    {
        $location = $_REQUEST["location"];
        $speciality = $_REQUEST["speciality"];
        $query = "SELECT users.*,user_profile.* FROM users,user_profile where (user_profile.specialities like '%" . strtolower($speciality) . "%') and (users.city like '%" . strtolower($location) . "%') and users.user_type_id=2 and users.status=1 and users.user_profile_id=user_profile.user_profile_id";
        $result = $this->select($query);

        if($result){
            if(count($result)){
                $data = json_encode([
                    'status' => 1,
                    'message' => 'List of Doctors',
                    'data' => $result
                ]);
            }else{
                $data = json_encode([
                    'status' => 0,
                    'message' => 'No record found',
                    'data' => $result
                ]);
            }
        }
        $this->sendOutput($data,array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function getDoctorsAction()
    {
        $query = 'SELECT users.*,user_profile.* FROM users,user_profile where users.user_type_id=2 and users.status=1';
        $result = $this->select($query);

        if($result){
            if(count($result)){
                $data = json_encode([
                    'status' => 1,
                    'message' => 'List of Doctors',
                    'data' => $result
                ]);
            }
        }
        $this->sendOutput($data,array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }

    public function dashboardAction()
    {
        $this->generateUUID(200);
    }

    public function getHospitalsAction()
    {
        $array = [
            [
                'name' => 'Hameed Latif Hospital - Garden Town, Lahore',
                'type' => 'Hospital',
                'address' => '14 New Abu Bakar Block, New Garden Town, Garden Town, Lahore',
                'image_url' => 'https://h7u5d3a4.stackpathcdn.com/assets/hospitals/37/hameed-latif-hospital-27_80X80.jpg',
                'phone' => '(042) 111 000 043'
            ],
            [
                'name' => 'Doctors Hospital - Johar Town Lahore',
                'type' => 'Hospital',
                'address' => '152-G/1, Canal Bank, Johar Town, Lahore',
                'image_url' => 'https://h7u5d3a4.stackpathcdn.com/assets/hospitals/61/doctors-hospital-15_80X80.jpg',
                'phone' => '(042) 35302701'
            ],
            [
                'name' => 'National Hospital DHA Lahore',
                'type' => 'Hospital',
                'address' => '32/3, Block L, DHA, Lahore. Landmarks : Sports Stadium, DHA Defence, Lahore',
                'image_url' => 'https://h7u5d3a4.stackpathcdn.com/assets/images/hospital-default.jpg    ',
                'phone' => '(042) 111 000 043'
            ],
            [
                'name' => 'Horizon Hospital Johar Town Lahore',
                'type' => 'Hospital',
                'address' => 'Block-D-II, Johar Town, Johar Town, Lahore',
                'image_url' => 'https://h7u5d3a4.stackpathcdn.com/assets/hospitals/898/horizon-hospital-48_80X80.jpg',
                'phone' => '(042) 111 000 043'
            ],
            [
                'name' => 'Hameedah Memorial Hospital Valencia Town Lahore',
                'type' => 'Hospital',
                'address' => 'B 1 block D, Valencia Housing Society, Lahore',
                'image_url' => 'https://h7u5d3a4.stackpathcdn.com/assets/hospitals/898/horizon-hospital-48_80X80.jpg',
                'phone' => '(042) 35210699'
            ],
            [
                'name' => 'Chughtai Lab',
                'type' => 'Lab',
                'address' => '10 Jail Road, Adjacent to Ammar Medical Complex, Gulberg, Lahore',
                'image_url' => 'https://static.marham.pk/assets/labs/2/chugtai-medical-25.png',
                'phone' => '(042) 35210699'
            ],
            [
                'name' => 'Citilab and Research Centre',
                'type' => 'Lab',
                'address' => 'Poonch Road Samanabad',
                'image_url' => 'https://static.marham.pk/assets/labs/7/bd25b02dd90cf6a35218cf32ba2e3f21.png',
                'phone' => '(042) 35210699'
            ],
            [
                'name' => 'CLINLAB',
                'type' => 'Lab',
                'address' => 'Shop # 1-A, Chaudhry Market, Chungi Multan, Wahdat Road,',
                'image_url' => 'https://static.marham.pk/assets/labs/16/b4b5ac145d89531429feaccaf4af71b6.png',
                'phone' => '(042) 35210699'
            ],
        ];

        $data = json_encode([
            'status' => 1,
            'message' => 'List of Hospitals/Labs',
            'data' => $array
        ]);


        $this->sendOutput($data,array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
    }


}