<?php

class ControllerModulefblogin extends Controller {

    private $error = array();

    public function index($setting) {
        $language_id = $this->config->get('config_language_id');
        $data['fbbutton'] = "image/" . $this->config->get('fblogin_' . $language_id . '_fbutton');
        if (!$this->customer->isLogged()) {
            $data['hideadl'] = 0;
        } else {
            $data['hideadl'] = 1;
        }

        if (!$this->customer->isLogged()) {
            if (!isset($this->fblogin)) {
                if (!class_exists('BaseFacebook')) {
                    require_once(DIR_SYSTEM . 'vendor/facebook-sdk/facebook.php');
                }
                $this->fblogin = new Facebook(array(
                    'appId' => $this->config->get('fblogin_apikey'),
                    'secret' => $this->config->get('fblogin_apisecret'),
                ));
            }
            $data['fblogin_url'] = $this->fblogin->getLoginUrl(
                    array(
                        'scope' => 'public_profile, email',
                        'redirect_uri' => $this->url->link('account/fblogin', '', 'SSL')
                    )
            );
            if ($setting['type'] == 'normal') {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/fblogin.tpl')) {
                    return $this->load->view($this->config->get('config_template') . '/template/module/fblogin.tpl', $data);
                } else {
                    return $this->load->view('default/template/module/fblogin.tpl', $data);
                }
            }
        }
    }

}

?>
