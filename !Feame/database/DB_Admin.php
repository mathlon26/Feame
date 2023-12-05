<?php

class DB_Admin
{
    public $logged_in;
    protected $DB;

    public function __construct()
    {
        $this->logged_in = false;
        $config = new Config;
        import("{$config->DB_PATH}/DB.php");
        $this->DB = new DB("dossier", "root", "","localhost");
    }

    public function login($username, $password)
    {
        // Sanitize and validate input
        $username = $this->DB->Util->sanitize($username);
        $password = $this->DB->Util->sanitize($password);

        // Query to check if the user exists
        $sql = "SELECT * FROM admin WHERE username = :username";
        $statement = $this->DB->prepare($sql);
        $this->DB->bind($statement, [':username' => $username]);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Verify the password and set the login status
        if ($user && $password == $user['password']) {
            $this->logged_in = true;
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $_SESSION = [];
    }
}