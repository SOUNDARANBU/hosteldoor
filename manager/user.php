<?php

namespace manager;

class user
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $username;
    public $dob;
    public $mobile;
    public $last_login;
    public $active;
    public $deleted;

    public function __construct()
    {
        if (isset($_SESSION['USER'])) {
            $user = $_SESSION['USER'];
            $this->id = $user->id;
            $this->firstname = $user->firstname;
            $this->lastname = $user->lastname;
            $this->email = $user->email;
            $this->username = $user->email;
            $this->dob = $user->dob;
            $this->mobile = $user->mobile;
            $this->last_login = $user->last_login;
            $this->active = $user->active;
            $this->deleted = $user->deleted;
        }
    }

    /**
     * Get user by id
     * @param string|int $user_id
     * @return bool|object
     */
    public static function get_user_by_id($user_id)
    {
        global $DB;
        $user = $DB->get_record('user', ['id' => $user_id]);
        return $user;
    }

    /**
     * Get user by username
     * @param string $user_name
     * @return bool|object
     */
    public static function get_user_by_username($user_name)
    {
        global $DB;
        $user = $DB->get_record('user', ['username' => $user_name]);
        return $user;
    }

    /**
     * Get user by mobile number
     * @param $user_mobile_number
     * @return bool|object
     */
    public static function get_user_by_mobile_number($user_mobile_number)
    {
        global $DB;
        $user = $DB->get_record('user', ['mobile' => $user_mobile_number]);
        return $user;
    }

    /**
     * Get user by email
     * @param $user_email
     * @return bool|object
     */
    public static function get_user_by_email($user_email)
    {
        global $DB;
        $user = $DB->get_record('user', ['email' => $user_email]);
        return $user;
    }

    public function get_profilepic()
    {

    }

    public function is_remembered()
    {

    }

    public function get_role()
    {

    }

    public function get_capabilities()
    {

    }

    public static function check_username_taken($username)
    {
        global $DB;
        $user = $DB->get_record('hdr_user', ['username' => $username]);
        return $user ? true : false;
    }

    public static function check_email_taken($email)
    {
        global $DB;
        $user = $DB->get_record('hdr_user', ['email' => $email]);
        return $user ? true : false;
    }

    public static function validate_existing_user($user)
    {
        global $DB;
        $result = new \stdClass();
        $result->status = SUCCESS;
        //check mandatory fields. Common for both create and update
        if (empty($user->usename) && empty($user->email)) {
            $result->message = "Username or email is not set";
            $result->status = FAIL;
        }

//        //check passwords set
//        if ($result->status == SUCCESS && empty($user->password) && empty($user->confirm_password)) {
//            $result->message = "Password is not set";
//            $result->status = FAIL;
//        }

        //check if the email is valid
        if ($result->status == SUCCESS && !util::is_valid_email($user->email)) {
            $result->message = "Email is not valid";
            $result->status = FAIL;
        }

        //check if the password matches
        if ($result->status == SUCCESS && ($user->password != $user->confirm_password)) {
            $result->message = "Password mismatch";
            $result->status = FAIL;
        }
        return $result;
    }

    public static function validate_new_user($user)
    {
        global $DB;
        $result = new \stdClass();
        $result->status = SUCCESS;
        //check mandatory fields. Common for both create and update
        if (empty($user->usename) && empty($user->email)) {
            $result->message = "Username or email is not set";
            $result->status = FAIL;
        }

        //check passwords set
        if ($result->status == SUCCESS && empty($user->password) && empty($user->confirm_password)) {
            $result->message = "Password is not set";
            $result->status = FAIL;
        }

        //check if the username already exists
        if ($result->status == SUCCESS && self::check_username_taken($user->username)) {
            $result->message = "Username already exists";
            $result->status = FAIL;
        }
        //check if the email already exists
        if ($result->status == SUCCESS && self::check_email_taken($user->email)) {
            $result->message = "Email already exists";
            $result->status = FAIL;
        }

        //check if the email is valid
        if ($result->status == SUCCESS && !util::is_valid_email($user->email)) {
            $result->message = "Email is not valid";
            $result->status = FAIL;
        }

        //check if the password matches
        if ($result->status == SUCCESS && ($user->password != $user->confirm_password)) {
            $result->message = "Password mismatch";
            $result->status = FAIL;
        }
        return $result;
    }

    public static function process_signup()
    {
        global $DB, $C;
        $status = new \stdClass();
        $status->message = "";
        $status->status = SUCCESS;
        $status->code = 0;

        $user = new \stdClass();
        $user->username = page::optional_param('username');
        $user->firstname = page::optional_param('firstname');
        $user->lastname = page::optional_param('lastname');
        $user->email = page::optional_param('email');
        $user->mobile = page::optional_param('mobile');
        $user->password = page::optional_param('password');
        $user->confirm_password = page::optional_param('confirm-password');

        $validation = self::validate_new_user($user);

        if ($validation->status == SUCCESS) {
            $user->password = util::create_password_hash($user->password);

            //assign default data
            $user->firstname = isset($user_data->firstname) ? $user->firstname : $user->username;
            $user->timecreated = time();
            $user->timemodified = $user->timecreated;
            $user->active = 1;
            $user->deleted = 0;
            unset($user->confirm_password);
            $user->id = self::create_user($user);
            if ($user->id > 0) {
                $status->message = "Signed Up Successfully. Enter you credentials to login.";
                if (self::send_signup_email($user)) {
                    $status->message = "Please check your email to verify your identity.";
                }
                $status->status = SUCCESS;
            } else {
                $status->message = "User Sign Up failed";
            }
        } else {
            $status = $validation;
        }
        return $status;
    }

    public static function create_user($user)
    {
        global $DB;
        $userid = $DB->insert_record('hdr_user', $user);
        if ($userid > 0) {
            return $userid;
        } else {
            return false;
        }
    }

    public static function update_user($user_data)
    {
        global $DB;
        //get user record
        $user = $DB->get_record('user', ['id' => $user_data->id]);
        $result = new \stdClass();
        $result->status = SUCCESS;
        if ($user) {
            $validation = self::validate_existing_user($user_data);
            if ($validation->status == SUCCESS) {
                //create user object
                $user->username = isset($user_data->username) ? $user_data->username : $user->username;
                $user->firstname = isset($user_data->firstname) ? $user_data->firstname : $user->firstname;
                $user->lastname = isset($user_data->lastname) ? $user_data->lastname : $user->lastname;
                $user->email = isset($user_data->email) ? $user_data->email : $user->email;
                $user->mobile = isset($user_data->mobile) ? $user_data->mobile : $user->mobile;
                $user->timemodified = time();
                $user->active = isset($user_data->active) ? $user_data->active : $user->active;
                $user->deleted = isset($user_data->deleted) ? $user_data->deleted : $user->deleted;
                $user->password = !empty($user_data->password) ? password_hash($user_data->password, PASSWORD_DEFAULT) : $user->password;

                $status = $DB->update_record('hdr_user', $user);
                $result->status = $status ? SUCCESS : FAIL;
            } else {
                $result = $validation;
            }
        }
        return $result;
    }

    public static function process_signin()
    {
        global $DB;
        $status = new \stdClass();
        $status->message = "";
        $status->status = true;
        $break = false;
        //check if all the fields all entered
        if (!isset($_POST['username']) && !isset($_POST['password'])) {
            $status->message = "Please enter all the fields";
            $status->status = false;
            $break = true;
        }
        if (!$break) {
            $user = $DB->get_record('hdr_user', ['username' => $_POST['username']]);
            //check if the user is found in db
            if ($user) {
                //check if password matches
                if (password_verify($_POST['password'], $user->password)) {
                    $_SESSION['USER'] = $user;
                } else {
                    $status->message = "Please enter correct password";
                    $status->status = false;
                }
            } else {
                $status->message = "Please enter valid username";
                $status->status = false;
            }
        }
        return $status;
    }

    public function process_signout()
    {
        if (isset($_SESSION['USER'])) {
            $_SESSION['USER'] = null;
        }
        return true;
    }

    public function is_signedin()
    {
        if (isset($_SESSION['USER'])) {
            return true;
        } else {
            return false;
        }
    }

    public function require_signin()
    {
        global $USER, $PAGE, $C;
        if (isset($USER->id) && $USER->id > 0) {
            return true;
        } else {
            $PAGE->redirect($C->wwwroot . '/account/signin.php');
        }
    }

    public function send_signup_email($user)
    {
        global $C;
        if ($C->send_signup_email) {
            $link = $this->create_signup_link($user->id);
            email::send_email((object)[
                'fromemail' => $C->site_email,
                'fromusername' => $C->site_username,
                'toemail' => $user->email,
                'tousername' => $user->username,
                'subject' => 'Confirm User Registration',
                'body' => "Please click the below link to verify the email. <br> $link <br><br> $C->site_username"
            ]);
        }
    }

    public function create_signup_link($userid)
    {
        global $C;
        $token = base64_encode(time() + '_' + $userid);
        return "$C->wwwroot/app/user/verify.php?token=$token";
    }

    public function create_password_reset_link()
    {

    }

    public function send_password_reset_email()
    {

    }

    public static function get_users($onlyactive = false)
    {
        global $DB;
        $params = array();
        if ($onlyactive) {
            $params['active'] = 1;
        }

        $users = $DB->get_records('user', $params);
        return $users;
    }


    public static function get_deleted_users()
    {
        global $DB;
        $deleted_users = $DB->get_records('user', ['deleted' => 1]);
        return $deleted_users;
    }
}