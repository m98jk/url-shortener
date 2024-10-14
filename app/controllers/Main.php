<?php
define('PROJECTNAME', '- شهادات التعليم المستمر');

class Main extends Controller
{
    // private $tceData;
    public function __construct()
    {
        // $this->tceData = $this->model('MainDB');
    }
    public function index()
    {
        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //     $long_url = isset($_POST['long_url']) ? trim($_POST['long_url']) : 0;
        //     var_dump($long_url);
        //     if ($long_url) {
        //         echo json_encode("1212");
        //     } else {
        //         echo json_encode("55");
        //     }
        // } else {
        $this->view('main/index', $data = []);
        // }
    }
    public function short()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $originalURL  = isset($_POST['long_url']) ? trim($_POST['long_url']) : 0;
            if ($originalURL) {
                $shortURL = generateRandomString();
                // insert the original and the short url in the db
                echo json_encode("1212");
            } else {
                echo json_encode("55");
            }
        }
    }
    // Function to generate a random string of specified length
    public function generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
