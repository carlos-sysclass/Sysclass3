<?php
namespace Sysclass\Services\Payment\Backend;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Payment\Interfaces\IPayment,    
    Sysclass\Models\Payments\Payment,
    Sysclass\Models\Payments\PaymentItem,
    Kint;

class Paypal extends Component implements IPayment {

    public function initialize() {
        
    }
    
    
    function sendPayment(array $data, $sandbox = false)
    {
        //Endpoint da API
        $apiEndpoint  = 'https://api-3t.' . ($sandbox? 'sandbox.': null);
        $apiEndpoint .= 'paypal.com/nvp';
      
        //Executando a operação
        $curl = curl_init();
      
        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
      
        $response = urldecode(curl_exec($curl));
      
        curl_close($curl);
      
        //Tratando a resposta
        $responseNvp = array();

        // GRAVAR RETORNO DO PAYPAL EM TABELA PRÒPRIA DO PAYPAL
        
        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $responseNvp[$name] = $matches['value'][$offset];
            }
        }
      
        //Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
        //gravamos um log para depuração.
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
            for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
                $message = sprintf("PayPal NVP %s[%d]: %s\n",
                                   $responseNvp['L_SEVERITYCODE' . $i],
                                   $responseNvp['L_ERRORCODE' . $i],
                                   $responseNvp['L_LONGMESSAGE' . $i]);
      
                error_log($message);
            }
        }
      
        return $responseNvp;
    }
   
}