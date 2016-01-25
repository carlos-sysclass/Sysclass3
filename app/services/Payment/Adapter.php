<?php
namespace Sysclass\Services\Payment;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Payment\Interfaces\IPayment,
    Sysclass\Services\Payment\Exception as PaymentException,
    Sysclass\Models\Payments\PaymentTransacao,
    Kint;

class Adapter extends Component implements IPayment
{
    /*
    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
    */
    protected $backend_class = null;
    protected $backend = null;

    public function initialize() {
        $backend_class = $this->environment->payment->backend;

        $this->setBackend($backend_class);

        $this->backend->initialize(true);
    }

    public function setBackend($class) {
        if (class_exists($class)) {
            $this->backend = new $class();
        } else {
            throw new StorageException("NO_BACKEND_DISPONIBLE", StorageException::NO_BACKEND_DISPONIBLE);
        }
        return true;
    }

    /* PROXY/ADAPTER PATTERN */
    public function initiatePayment(array $data) {

        //Vai usar o Sandbox, ou produção?
        $sandbox = true;
        
        //Baseado no ambiente, sandbox ou produção, definimos as credenciais
        //e URLs da API.
        if ($sandbox) {
            //credenciais da API para o Sandbox
            $user = 'conta-business_api1.test.com';
            $pswd = '1365001380';
            $signature = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx';
          
            //URL da PayPal para redirecionamento, não deve ser modificada
            $paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            //credenciais da API para produção
            $user      = 'usuario';
            $pswd      = 'senha';
            $signature = 'assinatura';
          
            //URL da PayPal para redirecionamento, não deve ser modificada
            $paypalURL = 'https://www.paypal.com/cgi-bin/webscr';
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
            
            //'RETURNURL'    => 'http://local.sysclass.com/module/payment/paypal/authorize/' . $id,
            //'CANCELURL'    => 'http://local.sysclass.com/module/payment/paypal/cancel/' . $id,
            'RETURNURL'    => 'http://local.sysclass.com/module/payment/authorize/' . $id,
            'CANCELURL'    => 'http://local.sysclass.com/module/payment/cancel/' . $id,
            'BUTTONSOURCE' => 'BR_EC_EMPRESA'
        );  

        //Envia a requisição e obtém a resposta da PayPal
        $responseNvp = $this->backend->initiatePayment($requestNvp, $sandbox);

        //GRAVA RETORNO DA TANSACAO EM TABELA PRÒPRIA DA TRANSAÇÃO => sysclass_demo.mod_payment_transacao
        $x = new PaymentTransacao();        

        //Se a operação tiver sido bem sucedida, redirecionamos o cliente para o ambiente de pagamento.
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {
            $query = array(
                'cmd'    => '_express-checkout',
                'token'  => $responseNvp['TOKEN']
            );    
            
            $x->descricao        = json_encode($responseNvp);
            $x->token            = $responseNvp['TOKEN'];
            $x->payment_itens_id = $id;    
            $x->status           = 'Initiate';
            $x->save();            
            return $redirectURL = sprintf('%s?%s', $paypalURL, http_build_query($query));  
        } else {
            //printf("Erro[%d]: %s\n", $responseNvp['L_ERRORCODE0'], $responseNvp['L_LONGMESSAGE0']);
            //status == 'failed'
            
            echo "<pre>";
            Kint::dump($responseNvp);

            $x->descricao = json_encode($responseNvp);
            $x->status       = 'Duplicate';
            $x->payment_itens_id = $id;
            $x->save();
            exit;
        }
    }

    public function authorizePayment(array $data) {
        // RETORNA UM VETOR COM O SEGUINTE FORMATO
        /*
        [   'token' => '<token>',
            'email' => '<email>',
            'continue' => '<continue>',
            'reason' => '<err_message>'
        ] */ 

        $autorized = $this->backend->authorizePayment($data['args']);

        if ($autorized['continue']) {
            //  SALVA OS DADOS NA TABELA DE TRANSACAO E RETORNA TRUE PARA CONTINUAR

            Kint::dump($data);

            $item = PaymentTransacao::findFirst(
                array(
                        'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                            "bind"             => array(
                            "token"            => $token,
                            "payment_itens_id" => $payment_itens_id
                        )
                    )
            );
            $item->status = "authorized";
            $item->save();
            return true;
        } else {
            //SALVA OS MOTIVOS DE FALHA NA TABELA E RETORNA FALSE PARA PARAR O PROCESSO

            return false;
        }
    }

    public function checkDetailsPayment($token, $payment_itens_id) {

       // echo "=>>  ".$autorized = $this->backend->checkDetailsPayment($token);


        $item = PaymentTransacao::findFirst(
        array(
                    'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                        "bind"             => array(
                        "token"            => $token,
                        "payment_itens_id" => $payment_itens_id
                    )
                )
        );
        $item->status = "checked";
        $item->save();
        echo "checked";
    }    

    public function confirmPayment($token, $payerID, $payment_itens_id) {
            $item = PaymentTransacao::findFirst(
                array(
                        'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                            "bind"             => array(
                            "token"            => $token,
                            "payment_itens_id" => $payment_itens_id
                        )
                    )
            );
            $item->status = "Success";
            $item->save();
            echo "Success";
    }    
}