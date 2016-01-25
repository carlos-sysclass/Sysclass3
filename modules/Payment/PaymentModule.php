<?php
namespace Sysclass\Modules\Payment;

use 
    Phalcon\Mvc\User\Component,
    Sysclass\Models\Payments\Payment,
    Sysclass\Models\Payments\PaymentItem,
    Kint,
    Sysclass\Models\Payments\PaymentTransacao;

/**
 * @RoutePrefix("/module/payment")
*/
class PaymentModule extends \SysclassModule implements \ILinkable
{
    /* ILinkable */
    public function getLinks() {
        //if ($this->acl->isUserAllowed(null, $this->module_id, "View")) {

            //$total_itens = User::count("active = 1");

            return array(
                'administration' => array(
                    array(
                        //'count' => 0,
                        'text'  => $this->translate->translate('Payments'),
                        'icon'  => 'fa fa-money',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        //}
    }

   /**
     * [ add a description ]
     *
     * @Get("/initiate/{payment_item_id}")
     */
    public function initiatePaymentRequest($paymentItemId) {

        // PEGAR O USUÁRIO ATUAL
        //$this->user->id
        
        //$paymentItemObject->getPayment()->getUser()->id;
        Kint::dump($paymentItemId);
        $paymentItemObject = PaymentItem::findFirstById($paymentItemId);        
        
        //SE DER ALGUMA ERRO ENTRA NA CONDIÇÃO    
        if (!$paymentItemObject) {
            echo "Não foi encontrado registro";
            exit;
        }

        $data = $paymentItemObject->toArray();
        $data['user'] = $paymentItemObject->getPayment()->getUser()->toArray();        
        
        //ENVIA P/ PAYPAL    
        $result = $this->payment->initiatePayment($data);        
        //$result = $this->payment->sendPayment($data);        
        
        if ($this->request->isAjax()) {
            echo "json";
        } else {
            //header("location:".$result);            
            echo "<meta http-equiv=refresh content='0;URL=$result'>";
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/authorize/{backend}/{payment_itens_id}")
     */
    public function authorizePaymentRequest($backend, $payment_itens_id) {

        $continue = $this->payment->authorizePayment(array(
            'backend' => $backend,
            'payment_itens_id' => $payment_itens_id,
            'args' => $this->request->getQuery()
        ));

        if ($continue) {
            $this->payment->confirmPayment();
        } else {
            //$this->payment->cancel()
        }
    }
    

    /**
     * [ add a description ]
     *
     * @Get("/cancel/{payment_itens_id}")
     */
    public function cancelPaymentRequest($payment_itens_id) {
        $token   = $this->request->getQuery('token');

        $item = PaymentTransacao::findFirst(
            array(
                    'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
                    "bind"       => array(
                        "token"            => $token,
                        "payment_itens_id" => $payment_itens_id
                    )
                )
        );
        $item->status = "cancel";
        $item->save();        
    }

    /**
     * [ add a description ]
     *
     * @Get("/checkout/{token}/{payment_itens_id}")
     */
    public function checkDetailsPayment($token, $payment_itens_id) {   

        //ENVIA P/ PAYPAL    
        //$result = $this->payment->checkDetailsPayment($token);        
        
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

    /**
     * [ add a description ]
     *
     * @Get("/confirm/{token}/{payerID}/{payment_itens_id}")
     */
    public function doExpressCheckoutPaymentPaymentRequest($token, $payerID, $payment_itens_id) {    
            
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