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
            'RETURNURL'    => 'http://local.sysclass.com/module/payment/authorized/' . $id,
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
        
        $token            = $data['args']['token'];
        $payment_itens_id = $data['payment_itens_id'];
        

        $autorized = $this->backend->authorizePayment($data['args']);

        if ($autorized['continue']) {
            $item = PaymentTransacao::findFirst(
                array(
                        'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                            "bind"             => array(
                            "token"            => $token,
                            "payment_itens_id" => $payment_itens_id
                        )
                    )
            );
            if($item){
                $item->status = "authorized";            
                $item->save();
                return true;
            }
                return false;
        } else {
            //SALVA OS MOTIVOS DE FALHA NA TABELA E RETORNA FALSE PARA PARAR O PROCESSO
            return false;
        }
    }

    public function confirmPayment(array $data) {
        
            $token            = $data['args']['token'];           
            $PayerID          = $data['PayerID'];            
            $payment_itens_id = 3;
            
            $confirmed = $this->backend->confirmPayment($data['args']);

            if ($confirmed['confirmed']) {
                $item = PaymentTransacao::findFirst(
                    array(
                            'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                                "bind"             => array(
                                "token"            => $token,
                                "payment_itens_id" => $payment_itens_id
                            )
                        )
                );
                if($item){
                    $item->status = "Success";            
                    $item->save();
                    return true;
                }else{
                    return false;
                }                
            } return false;
    }    
}