<?php
namespace Sysclass\Services\Payment;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Payment\Interfaces\IPayment,
    Sysclass\Services\Payment\Exception as PaymentException,
    Sysclass\Models\Payments\PaymentTransacao,
    Sysclass\Models\Payments\PaymentItem,
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
        
        $response = $this->backend->initiatePayment($data);

        //GRAVA RETORNO DA TANSACAO EM TABELA PRÒPRIA DA TRANSAÇÃO => sysclass_demo.mod_payment_transacao
        $x = new PaymentTransacao();    

        $x->descricao        = json_encode($response['info']);
        $x->token            = $response['token'];
        $x->payment_itens_id = $data['id'];   
        $x->status           = $response['status'];
        $x->save();            

        return $response;
    }

    public function authorizePayment(array $data) {
        
        $token            = $data['args']['token'];
        $payment_itens_id = $data['payment_itens_id'];
        
        //if ($autorized['continue']) {
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
            } else {
                //SALVA OS MOTIVOS DE FALHA NA TABELA E RETORNA FALSE PARA PARAR O PROCESSO
                return false;
            }            
        //}    
    }

    public function confirmPayment(array $data) {
        
            $token            = $data['args']['token'];           
            $PayerID          = $data['args']['PayerID'];            
            $payment_itens_id = $data['payment_itens_id'];            

            $item = PaymentItem::findFirstById($payment_itens_id);

    /*
              $items = PaymentItem::find(array(
                'conditions' => 'payment_id = ?0'
                'bind' => array(1)
            ));

            foreach($items as $item) {
                $item->valor                
            }

            if (!$item) {

            }
    */
            $data['valor'] = $item->valor;

            //Paypal.php => confirmPayment
            $confirmed = $this->backend->confirmPayment($data); 
            
            if ($confirmed['continue']) {
                $item = PaymentTransacao::findFirst(
                    array(
                            'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                                "bind"             => array(
                                "token"            => $token,
                                "payment_itens_id" => $payment_itens_id,
                            )
                         )
                );                
                if($item){
                    $item->status = "Success";
                    $item->save();
                    //return true;                    
                }else{
                    return false;
                }                

                //INSERE O VALOR  PAGO E A DATA DE PAGAMENTO    
                $item = PaymentItem::findFirst(
                    array(
                            'conditions' => "id = :id:",
                                "bind"        => array(
                                "id"  => $payment_itens_id
                            )
                         )
                );
                if($item){
                    $item->amount_paid  = $data['valor'];
                    $item->payment_date = date("Y-m-d");
                    $item->id_status    = "2";
                    $item->save();
                    return true;                    
                }else{
                    return false;
                }  
            }          
    }    
}