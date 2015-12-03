<?php

class ControllerModulefblogin extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('sale/customer');
        $this->load->language('module/fblogin');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title2'] = $this->language->get('heading_title2');
        $data['title'] = $this->language->get('heading_title');


        $this->document->setTitle($this->language->get('title'));
        $this->load->model('setting/setting');
        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('fblogin', $this->request->post['modulearray']);
            } else {
                $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post['modulearray']);
            }
            $abc = $this->request->post;

            unset($abc['modulearray']);

            $this->model_setting_setting->editSetting('fblogin', $abc);


            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();
        $data['languages'] = $languages;
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');


        $data['tab_fields'] = $this->language->get('tab_fields');

        $data['column_name'] = $this->language->get('column_name');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_type'] = $this->language->get('entry_type');

        $data['tab_popup'] = $this->language->get('tab_popup');
        $data['tab_modulesetting'] = $this->language->get('tab_modulesetting');


        $data['error_name'] = $this->language->get('error_name');
        $data['error_permission'] = $this->language->get('error_permission');


        $data['entry_apikey'] = $this->language->get('entry_apikey');
        $data['entry_apisecret'] = $this->language->get('entry_apisecret');
        $data['entry_pwdsecret'] = $this->language->get('entry_pwdsecret');
        $data['entry_pwdsecret_desc'] = $this->language->get('entry_pwdsecret_desc');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_fbutton'] = $this->language->get('entry_fbutton');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');


        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/fblogin', 'token=' . $this->session->data['token'], 'SSL')
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/fblogin', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
            );
        }
        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('module/fblogin', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/fblogin', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
        }



        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
        }



        $this->load->model('sale/customer_group');
        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        if (isset($this->request->post['fblogin_customer_group'])) {
            $data['fblogin_customer_group'] = $this->request->post['fblogin_customer_group'];
        } else {
            if ($this->config->get('fblogin_customer_group')) {
                $data['fblogin_customer_group'] = $this->config->get('fblogin_customer_group');
            } else {
                $data['fblogin_customer_group'] = '1';
            }
        }

        if (isset($this->request->post['fblogin_pwdsecret'])) {
            $data['fblogin_pwdsecret'] = $this->request->post['fblogin_pwdsecret'];
        } elseif ($this->config->get('fblogin_pwdsecret')) {
            $data['fblogin_pwdsecret'] = $this->config->get('fblogin_pwdsecret');
        } else
            $data['fblogin_pwdsecret'] = 'a1a2';

        foreach ($languages as $language) {
            $this->load->model('tool/image');
            $imagepath = '';
            $data['placeholder'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

            if (isset($this->request->post['fblogin_' . $language['language_id'] . '_fbutton'])) {
                $data['fblogin'][$language['language_id']]['fbutton'] = $this->request->post['fblogin_' . $language['language_id'] . '_fbutton'];
            } else {
                if ($this->config->get('fblogin_' . $language['language_id'] . '_fbutton')) {
                    $imagepath = $this->config->get('fblogin_' . $language['language_id'] . '_fbutton');
                    $data['fblogin'][$language['language_id']]['fbutton'] = $this->config->get('fblogin_' . $language['language_id'] . '_fbutton');
                }
            }
            if (!empty($imagepath) && file_exists(DIR_IMAGE . $imagepath))
                $data['thumb5'][$language['language_id']] = $this->model_tool_image->resize($imagepath, 100, 100);
            else
                $data['thumb5'][$language['language_id']] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }


        if (isset($this->request->post['fblogin_apikey'])) {
            $data['fblogin_apikey'] = $this->request->post['fblogin_apikey'];
        } elseif ($this->config->get('fblogin_apikey')) {
            $data['fblogin_apikey'] = $this->config->get('fblogin_apikey');
        } else
            $data['fblogin_apikey'] = '';

        if (isset($this->request->post['fblogin_apisecret'])) {
            $data['fblogin_apisecret'] = $this->request->post['fblogin_apisecret'];
        } elseif ($this->config->get('fblogin_apisecret')) {
            $data['fblogin_apisecret'] = $this->config->get('fblogin_apisecret');
        } else
            $data['fblogin_apisecret'] = '';

        if (isset($this->request->post['name'])) {
            $data['modulearray']['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['modulearray']['name'] = $module_info['name'];
        } else {
            $data['modulearray']['name'] = '';
        }
        if (isset($this->request->post['type'])) {
            $data['modulearray']['type'] = $this->request->post['type'];
        } elseif (!empty($module_info)) {
            $data['modulearray']['type'] = $module_info['type'];
        } else {
            $data['modulearray']['type'] = '';
        }
        if (isset($this->request->post['status'])) {
            $data['modulearray']['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['modulearray']['status'] = $module_info['status'];
        } else {
            $data['modulearray']['status'] = '';
        }

        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/fblogin.tpl', $data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/fblogin')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if ((utf8_strlen($this->request->post['modulearray']['name']) < 1) || (utf8_strlen($this->request->post['modulearray']['name']) > 50)) {
            $this->error['name'] = $this->language->get('error_name');
        }
        return !$this->error;
    }

}
