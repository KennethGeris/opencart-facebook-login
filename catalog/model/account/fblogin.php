<?php

class ModelAccountfblogin extends Model {

    public function register($data) {

        $lccode = $this->config->get('config_language_id');
        $customer_group_id = $this->config->get('fblogin_customer_group');
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET email = '" . $this->db->escape($data['email']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', newsletter = '0', customer_group_id = '" . (int) $customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '1', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', date_added = NOW()");
    }

}

?>