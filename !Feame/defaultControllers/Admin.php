<?php

class Admin
{
    public $LoginWildcards = ["?error"];
    public function Login($data)
    {
        if (isset($data['Queries']['error']))
        {
            show($data['Queries']['error']);
        }
        Controller::view("admin/login", $data);
    }

    public $Login_SubmitWildcards = [];
    public function Login_Submit($data = [])
    {

        $config = new Config;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameff = $_POST['username'];
            $passwordff = $_POST['password'];

            session_start();
            import("{$config->DB_PATH}/DB_Admin.php");
            $DB = new DB_Admin;
            $DB->logout();

            if ($DB->login($usernameff, $passwordff)) {
                // Set session variables to mark the user as logged in
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $usernameff;

                Controller::view("admin/dashboard", [$_SESSION]);
            } else {
                redirect("en/admin/?error=Invalid username or password");
            }
        }
    }

    public $Logout_SubmitWildcards = [];
    public function Logout_Submit($data = [])
    {
        $config = new Config;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            import("{$config->DB_PATH}/DB_Admin.php");
            $DB = new DB_Admin;
            $DB->logout();
            
            show("ffff");
            redirect("admin");
        } else {
            redirect("en/admin/?error=Invalid request");
        }
    }
}