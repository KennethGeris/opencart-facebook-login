<?php

class ControllerAccountfblogin extends Controller {

    private $error = array();

    public function index() {

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        if (!isset($this->fblogin)) {
            require_once(DIR_SYSTEM . 'vendor/facebook-sdk/facebook.php');

            $this->fblogin = new Facebook(array(
                'appId' => $this->config->get('fblogin_apikey'),
                'secret' => $this->config->get('fblogin_apisecret'),
            ));
        }

        $_SERVER_CLEANED = $_SERVER;
        $_SERVER = $this->clean_decode($_SERVER);

        $fbuser = $this->fblogin->getUser();
        $fbuser_profile = null;
        if ($fbuser) {
            try {
                $fbuser_profile = $this->fblogin->api("/$fbuser");
            } catch (FacebookApiException $e) {
                error_log($e);
                $fbuser = null;
            }
        }

        $_SERVER = $_SERVER_CLEANED;

        if (!isset($fbuser_profile['verified'])) {
            $fbuser_profile['verified'] = 1;
        }
        if ($fbuser_profile['id'] && $fbuser_profile['email'] && $fbuser_profile['verified']) {
            $this->load->model('account/customer');

            $email = $fbuser_profile['email'];
            $password = $this->get_password($fbuser_profile['id']);

            if ($this->customer->login($email, $password)) {
                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }

            $email_query = $this->db->query("SELECT `email` FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "'");

            if ($email_query->num_rows) {
                $this->model_account_customer->editPassword($email, $password);
                if ($this->customer->login($email, $password)) {
                    $this->response->redirect($this->url->link('account/account', '', 'SSL'));
                }
            } else {
                $this->request->post['email'] = $email;

                $add_data = array();
                $add_data['email'] = $fbuser_profile['email'];
                $add_data['password'] = $password;
                $add_data['firstname'] = isset($fbuser_profile['first_name']) ? $fbuser_profile['first_name'] : '';
                $add_data['lastname'] = isset($fbuser_profile['last_name']) ? $fbuser_profile['last_name'] : '';

                $this->load->model('account/fblogin');

                $this->model_account_fblogin->register($add_data, 1);

                if ($this->customer->login($email, $password)) {
                    unset($this->session->data['guest']);
                    $this->response->redirect($this->url->link('account/success', '', 'SSL'));
                }
            }
        }
        $this->response->redirect($this->url->link('account/account', '', 'SSL'));
    }

    private function get_password($str) {
        $password = $this->config->get('fblogin_pwdsecret') ? $this->config->get('fblogin_pwdsecret') : 'fb';
        $password.=substr($this->config->get('fblogin_apisecret'), 0, 3) . substr($str, 0, 3) . substr($this->config->get('fblogin_apisecret'), -3) . substr($str, -3);
        return strtolower($password);
    }

    private function clean_decode($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean_decode($key)] = $this->clean_decode($value);
            }
        } else {
            $data = htmlspecialchars_decode($data, ENT_COMPAT);
        }
        return $data;
    }

}

?>