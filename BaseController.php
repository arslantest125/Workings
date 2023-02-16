<?php
require_once PROJECT_ROOT_PATH . "./Models/Database.php";
class BaseController extends Database
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     *
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        return $uri;
    }

    /**
     * Get querystring params.
     *
     * @return array
     */
    protected function getQueryStringParams()
    {
        return parse_str($_SERVER['QUERY_STRING'], $query);
    }

    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }


    public function generateUUID($length) {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }

    public function getUserLoginInformation()
    {
        $req = apache_request_headers();
        if(isset($req['Authorization']) && $req['Authorization'] != null){
            $auth = $req['Authorization'];

            $token = str_replace('Bearer ','',$auth);

            try {
                $decode = base64_decode($token);
                $token = unserialize($decode);
                if(is_int($token[0]['user_id'])){
                    $id = $token[0]['user_id'];
                    $query = 'SELECT * FROM users where users.user_id = '.$id;
                    $result = $this->select($query);

                    if($result){
                        if(count($result)){
                            return $data = [
                                'status' => 1,
                                'message' => '',
                                'data' => $result
                            ];
                        }
                    }
                }
            }catch(\Exception $exception){
                return [
                    'status' => 0,
                    'message' => 'Invalid Token! Or Token Not Found',
                    'data' => []
                ];
            }


        }

        return [
            'status' => 0,
            'message' => 'Invalid Token! Or Token Not Found',
            'data' => []
        ];
    }

    public function returnGeneralResponse($status , $message)
    {
        $this->sendOutput(
            json_encode([
                'status' => $status,
                'message' => $message,
            ]),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }


}