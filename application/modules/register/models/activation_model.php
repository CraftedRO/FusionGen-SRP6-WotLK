<?php
class Activation_model extends CI_Model
{
    public function add($username, $password, $email, $expansion)
    {
        // Generate unique key
        $key = sha1($username . $email . $password . time());

        $data = [
            'ip'        => $this->input->ip_address(),
            'key'       => $key,
            'email'     => $email,
            'username'  => $username,
            'password'  => $this->encrypt($password, $key),
            'expansion' => $expansion,
            'timestamp' => time()
        ];

        $this->db->insert('pending_accounts', $data);

        return $key;
    }

    public function getAccount($key)
    {
        $query = $this->db->query('SELECT * FROM pending_accounts WHERE `key` = ?', [$key]);

        if($query->num_rows())
        {
            $row = $query->result_array();

            if(isset($row[0]['password']))
                $row[0]['password'] = $this->decrypt($row[0]['password'], $key);

            return $row[0];
        }
        else
        {
            return false;
        }
    }

    public function remove($id, $username, $email)
    {
        $this->db->query('DELETE FROM pending_accounts WHERE id = ? OR username = ? OR email = ?', [$id, $username, $email]);
    }

    /**
     * Basic two-way encryption
     * @param  string $string [description]
     * @param  string $action [description]
     * @param  string $key    [description]
     * @return string $output [description]
     */
    private function _crypt($string, $action, $key)
    {
        // Get keys cache
        $keys = $this->cache->get('register_activation_keys_' . $key);

        // Cache isn't available, generate keys
        if($keys === FALSE)
        {
            $keys = [
                'secret_key' => bin2hex(random_bytes(50)),
                'secret_iv'  => bin2hex(random_bytes(50))
            ];

            // Save the keys for later
            $this->cache->save('register_activation_keys_' . $key, $keys);
        }

        $encrypt_method = 'AES-256-CBC';
        $_key           = hash('sha256', $keys['secret_key']);
        $_iv            = substr(hash('sha256', $keys['secret_iv']), 0, 16);

        // Initialize output
        $output = false;

        switch($action)
        {
            case 'e':
                $output = base64_encode(openssl_encrypt($string, $encrypt_method, $_key, 0, $_iv)); # encrypt string
                break;

            case 'd':
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $_key, 0, $_iv); # decrypt string
                $this->cache->delete('register_activation_keys_' . $key . '.cache');                # delete related cache keys
                break;
        }

        return $output;
    }

    /**
     * Creates a hash of the password we enter
     * @param  string $password [description]
     * @param  string $key      [description]
     * @return string
     */
    private function encrypt($password, $key)
    {
        return $this->_crypt($password, 'e', $key);
    }

    /**
     * Decrypt hashed password we enter
     * @param  string $password [description]
     * @param  string $key      [description]
     * @return string
     */
    private function decrypt($password, $key)
    {
        return $this->_crypt($password, 'd', $key);
    }
}
