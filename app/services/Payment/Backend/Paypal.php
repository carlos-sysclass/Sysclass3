<?php
namespace Sysclass\Services\Payment\Backend;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Payment\Interfaces\IPayment,    
    Sysclass\Models\Payments\Payment,
    Sysclass\Models\Payments\PaymentItem,
    Sysclass\Models\Payments\Paypal\TransactionLog as PaypalTransactionLog,   
    Sysclass\Models\Payments\PaymentTransacao, 
    Kint;

class Paypal extends Component implements IPayment {

    protected $debug;

    public function initialize($debug = false) {
        $this->debug = $debug;
    }
    
    function initiatePayment(array $data)
    {
        //Endpoint da API
        $apiEndpoint  = 'https://api-3t.' . ($this->debug ? 'sandbox.': "");
        $apiEndpoint .= 'paypal.com/nvp';
      
        //Kint::dump($data);
        //Executando a operação
        $curl = curl_init();
      
        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_VERBOSE, true);
      

      //Vai usar o Sandbox, ou produção?
        $sandbox = true;
        
        //Baseado no ambiente, sandbox ou produção, definimos as credenciais
        //e URLs da API.
        if ($sandbox) {
            $user = $this->environment->paypal->user;
            $pswd = $this->environment->paypal->pass;
            $signature = $this->environment->paypal->signature;          
            //URL da PayPal para redirecionamento, não deve ser modificada
            $paypalURL = $this->environment->paypal->paypalURL;
        } else {
            //credenciais da API para produção
            $user      = 'usuario';
            $pswd      = 'senha';
            $signature = 'assinatura';
          
            //URL da PayPal para redirecionamento, não deve ser modificada
            $paypalURL = $this->environment->paypal->paypalURL;
        }
          
        //Campos da requisição da operação SetExpressCheckout, como ilustrado acima.

        $id         = $data['id'];
        $valor      = floatval($data['valor']);
        $valor      = round($valor,2);

        $requestNvp = array(
            'USER'      => $user,
            'PWD'       => $pswd,
            'SIGNATURE' => $signature,          
            'VERSION'   => '108.0',
            'METHOD'    => 'SetExpressCheckout',          
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
            'PAYMENTREQUEST_0_AMT'           => $valor,
            'PAYMENTREQUEST_0_CURRENCYCODE'  => 'BRL',
            'PAYMENTREQUEST_0_ITEMAMT'       => $valor,
            'PAYMENTREQUEST_0_INVNUM'        => $id,
          
            //'L_PAYMENTREQUEST_0_NAME0' => 'Item A',
            'L_PAYMENTREQUEST_0_DESC0' => 'Produto A – 110V',
            'L_PAYMENTREQUEST_0_AMT0'  => $valor,
            'L_PAYMENTREQUEST_0_QTY0'  => '1',    
                                                                               
            'RETURNURL'    => 'http://local.sysclass.com/module/payment/authorized/paypal/' . $id,
            'CANCELURL'    => 'http://local.sysclass.com/module/payment/cancel/paypal/' . $id,
            'BUTTONSOURCE' => 'BR_EC_EMPRESA'
        );  

        $response = urldecode(curl_exec($curl));
      
        curl_close($curl);
      
        //Tratando a resposta
        $responseNvp = array();

        //GRAVAR RETORNO DO PAYPAL EM TABELA PRÒPRIA DO PAYPAL
        
        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $responseNvp[$name] = $matches['value'][$offset];
            }
        }

        // TODO: Gravar aqui os dados de requisição e retorno
        /*$item = PaymentTransacao::findFirst(
            array(
                    'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                        "bind"             => array(
                        "token"            => $token,
                        "payment_itens_id" => $payment_itens_id
                    )
                )
        );
        $item->status = "authorized";
        $item->save();*/

        /* 
        REQUISIÇÂO = $data
        RETORNO = $responseNvp
        */

        /*$paypalTransation = new PaypalTransaction();
        $paypalTransation->token = 
        $paypalTransation->timestamp = 
        $paypalTransation->request = json_encode($data);
        $paypalTransation->response = json_encode($responseNvp);
        $paypalTransation->save();
*/
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

    public function authorizePayment(array $data) {
        
        $result = array(
            'token' => $data['token']
        );
        //$details = $this->checkDetailsPayment($result);

        //INSERIR ESTES DADOS NA TABELA ESPECIFICA DO PAYPAL, (SE HOUVER)
        /*$paypalTransation = new PaypalTransactionLog();
        $paypalTransation->token = 
        $paypalTransation->timestamp = 
        $paypalTransation->request = json_encode($data);
        $paypalTransation->response = json_encode($responseNvp);
        $paypalTransation->save();
        */
        $result['email'] = $details['EMAIL'];
        //TODO Check todos os status de retorno do paypal e retornar true or false

        $result['continue'] = true;

        $result['failreason'] = $details['CHECKOUTSTATUS'];

        //RETORNAR DADOS NA ESTRUTURA ESPERADA PELO ADAPTER
        return $result;
    }

    public function confirmPayment(array $data){

            $token   = $this->request->getQuery('token');   
            $PayerID = $this->request->getQuery('PayerID');  
            //$payment_itens_id = $this->request->getQuery('payment_itens_id');  
            $payment_itens_id = 3;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
            

            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                'USER'      => $user,
                'PWD'       => $pswd,
                'SIGNATURE' => $signature,                          
                'METHOD'    => 'DoExpressCheckoutPayment',
                'VERSION'   => '108',
                'LOCALECODE'=> 'pt_BR',              
                'TOKEN'     => $token,
                'PayerID'   => $PayerID,              
                'PROFILESTARTDATE' => '2016-01-22T12:00:00Z',
                'DESC'             => 'Exemplo',
                'BILLINGPERIOD'    => 'Month',
                'BILLINGFREQUENCY' => '1',
                'AMT'              => 100,
                'CURRENCYCODE'     => 'BRL',
                'COUNTRYCODE'      => 'BR',
                'NOTIFYURL'        => 'http://PayPalPartner.com.br/notifyme',

                'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
                'PAYMENTREQUEST_0_AMT'           => '200.00',
                'PAYMENTREQUEST_0_CURRENCYCODE'  => 'BRL',
                'PAYMENTREQUEST_0_ITEMAMT'       => '200.00',
                'PAYMENTREQUEST_0_INVNUM'        => $payment_itens_id, 

                'PAYMENTREQUEST_0_SHIPTONAME'    =>'José Silva',
                'PAYMENTREQUEST_0_SHIPTOSTREET'  =>'Rua Main, 150',
                'PAYMENTREQUEST_0_SHIPTOSTREET2' =>'Centro',
                'PAYMENTREQUEST_0_SHIPTOCITY'    =>'Rio De Janeiro',
                'PAYMENTREQUEST_0_SHIPTOSTATE'   =>'RJ',
                'PAYMENTREQUEST_0_SHIPTOZIP'     =>'22021-001',
                'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' =>'BR',

                'MAXFAILEDPAYMENTS'=> 3
            )));
              
            $response = curl_exec($curl);
              
            curl_close($curl);
              
            $nvp = array();
              
            if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
                foreach ($matches['name'] as $offset => $name) {
                    $nvp[$name] = urldecode($matches['value'][$offset]);
                }
            }          
            
            return $nvp;
    }         
}