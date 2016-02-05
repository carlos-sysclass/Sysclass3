<?php
namespace Sysclass\Modules\Payment;

use 
    Phalcon\Mvc\User\Component,
    Sysclass\Models\Payments\Payment,
    Sysclass\Models\Payments\PaymentItem,
    Sysclass\Services\Payment\Exception as PaymentException,
    Kint,
    Sysclass\Models\Payments\PaymentTransacao;

/**
 * @RoutePrefix("/module/payment")
*/
class PaymentModule extends \SysclassModule implements \ILinkable, \IWidgetContainer
{
    /* ILinkable */
    public function getLinks() {
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
    }

   /**
     * [ add a description ]
     *
     * @Get("/initiate/{payment_item_id}")
     */
    public function initiatePaymentRequest($paymentItemId) {
        //Kint::dump($paymentItemId);
        $paymentItemObject = PaymentItem::findFirstById($paymentItemId);        
        
        //SE DER ALGUMA ERRO ENTRA NA CONDIÇÃO    
        if (!$paymentItemObject) {
            $this->redirect("/module/extrato/view", "Erro mágico", "error");  
            return;             
        }

        $data = $paymentItemObject->toArray();
        $data['user'] = $paymentItemObject->getPayment()->getUser()->toArray();        
        
        //Adapter.php => initiatePayment(array $data) 
        $result = $this->payment->initiatePayment($data);
                
        if ($this->request->isAjax()) {


        } else {
            
            //if ($result['continue']) {
                switch($result['action']) {
                    case "redirect" : {
                        $this->response->redirect($result['redirect']);
                        break;
                    }
                    case "message" :
                    default : {
                        $this->redirect("/module/extrato/view", $this->translate->translate($result['message']), "warning");  
                    }
                }
            //}
        }
        /*
        
            // EXEMPLO DE RETONO DE MENSAGEM 
            if (!$result) { /// CASO DÊ ERRO
                $this->response->setJsonContent(
                    $this->createAdviseResponse(
                        $this->translate->translate("A problem ocurred when tried to save you data. Please try again."), 
                        "warning"
                    )
                );
            } else {            
            // EXEMPLO DE RETONO DE MENSAGEM COM REDIRECIONAMENTO
                $this->response->setJsonContent(
                    $this->createRedirectResponse(
                        $result
                    )
                );
            }
        } else {
            
            if (!$result) { /// CASO DÊ ERRO
                $this->redirect("/module/extrato/view", "Erro mágico", "error");  
            } else {
                $this->response->redirect($result);  
            }
            


            //
            //header("location:".$result);            
            //echo "---".$result;
            //echo "<meta http-equiv=refresh content='0;URL=$result'>";

         
        }
        */
    }

    /**
     * [ add a description ]
     *        
     * @Get("/authorized/paypal/{payment_itens_id}")
     */
    public function authorizePaymentRequest($payment_itens_id) {        
        $token   = $this->request->getQuery('token');   
        $PayerID = $this->request->getQuery('PayerID');   
        
        $continue = $this->payment->authorizePayment(array(
            'backend'          => $backend,
            'payment_itens_id' => $payment_itens_id,
            'args'             => $this->request->getQuery()
        ));
        
        //var_dump($continue);
        // Adapter.php => confirmPayment
        if ($continue) {
            $this->payment->confirmPayment(array(
                'backend'          => $backend,
                'payment_itens_id' => $payment_itens_id,
                'args'             => $this->request->getQuery()
            ));
            $this->redirect("/module/extrato/view", "Authorized by User", "sucess");              
            return;             
        } else {
            $this->redirect("/module/extrato/view", "Error Authorize the User", "warning");  
            return;             
        }        
    }    

    /**
     * [ add a description ]
     *
     * @Get("/cancel/paypal/{payment_itens_id}")
     */
    public function cancelPaymentRequest($payment_itens_id) {
        $token = $this->request->getQuery('token');        
        
        $item  = PaymentTransacao::findFirst(
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
        $this->redirect("/module/extrato/view", $this->translate->translate("Cancelado pelo Usuário"), "warning");  
        return;             
    }

     /** 
     * [ add a description ]
     *
     * @Get("/confirm/{payment_itens_id}")
     */

    /*confirm/{backend}/{payment_itens_id}")*/
    public function doExpressCheckoutPaymentPaymentRequest($payment_itens_id) {    
            
            $token   = $this->request->getQuery('token');   
            $PayerID = $this->request->getQuery('PayerID');   
           
           $continue = $this->payment->confirmPayment(array(
            'backend' => $backend,
            'payment_itens_id' => $payment_itens_id,
            'args' => $this->request->getQuery()
        ));

        $continue = true;
        if ($continue) {
            $this->payment->confirmPayment(array(
                'backend'          => $backend,
                'payment_itens_id' => $payment_itens_id,
                'args'             => $this->request->getQuery()
            ));
            echo "<script>alert('Pagamento Confirmado');</script>";       
            echo "<meta http-equiv=refresh content='0;URL=http://local.sysclass.com/dashboard'>";   
        } else {
            echo "Nao foi possivel confirmar o pagamento";
        }        
    }

    /* IWidgetContainer */
    /**
     * [getWidgets description]
     * @param  array  $widgetsIndexes [description]
     * @return [type]                 [description]
     * @implemen
     */
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('payment.overview', $widgetsIndexes)) {

            //$id = "480";                        
            
            $conditions = "id = ?1";
            $parameters = array(1 => $id);
            $items      = PaymentTransacao::find(
                        array(
                            $conditions,
                            "bind" => $parameters
                             )
                        );
            $data = array();
            //$items->toArray();  
            
            foreach ($items as $linha) {
                echo    $data = $linha->descricao;
            }
                        
            //criar uma array para passar os parametros na outra pagina
             //*/
            return array(
             'payment.overview' => array(
                    'id'        => 'payment-panel',
                    'type'      => 'payment',
                    'title'     => 'Payment Student',
                    'template'  => $this->template("widgets/overview"),
                    'panel'     => true,
                    'data'      => $data,
                    'box'       => 'blue'
                )
            );
        }
    }
}