<?php
namespace Sysclass\Services\Utils;

use Phalcon\Mvc\User\Component;

class CurlRequest extends Component {

    protected $responseType = null;
    
    public function __construct() {
        $this->_ch = curl_init();
    }

    public function setInfo($info) {
        curl_setopt_array($this->_ch, $info);

        return $this;
    }

    public function outputJson() {
        $this->responseType = "json";
        return $this;
    }

    public function send() {
        $raw = curl_exec($this->_ch);
        $err = curl_error($this->_ch);
        $info = curl_getinfo($this->_ch);

        curl_close($this->_ch);

        if ($this->responseType == "json") {
            $response = json_decode($raw, true);
        } else {
            $response = $raw;
        }

        return [
            'raw' => $raw,
            'response' => $response,
            'info'  => $info,
            'error' => $err
        ];

    }
}