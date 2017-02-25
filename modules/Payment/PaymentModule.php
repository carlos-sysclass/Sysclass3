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
class PaymentModule extends \SysclassModule /*implements \ISummarizable,  \ILinkable */ /* \IWidgetContainer */
{
    /* ISummarizable */
    public function getSummary() {
        $data = array(1);

        return array(
            'type'  => 'warning',
            'count' => $data[0],
            'text'  => $this->translate->translate('Payments'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath()
            )
        );
    }

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
        //var_dump($paymentItemId);
        
        $paymentItemObject = PaymentItem::findFirstById($paymentItemId);        
        //exit;
        //SE DER ALGUM ERRO ENTRA NA CONDIÇÃO    
        if (!$paymentItemObject) {
            $this->redirect("/module/payment/view", "Error Starting Transaction", "error");  
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
                        $this->redirect("/module/payment/view", $this->translate->translate($result['message']), "warning");  
                    }
                }
            //}
        }
        /*
        
            // EXEMPLO DE RETONO DE MENSAGEM 
            if (!$result) { /// CASO DÊ ERRO
                $this->response->setJsonContent(
                    $this->createAdviseResponse(
                        $this->translate->translate("A problem ocurred when tried to save you data. Please, try again."), 
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

    //função que chama o paypal
    protected function getDatatableSingleItemOptions($item) {

        if(empty($item->payment_date)){

        //var_dump($item);
        //if (!$this->request->hasQuery('block') && $item->pending == 1) {
            return array(
                'aprove' => array(
                    'icon'  => 'fa fa-lock',
                    'link'  => "http://local.sysclass.com/module/payment/initiate/" . $item->id,
                    'class' => 'btn-sm btn-info datatable-actionable tooltips',
                    'attrs' => array(
                        'data-original-title' => 'Make Payment'
                    )
                )
            );
        //}
        return false;
        }
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
        
        // Adapter.php => confirmPayment
        if ($continue) {
            $this->payment->confirmPayment(array(
                'backend'          => $backend,
                'payment_itens_id' => $payment_itens_id,
                'args'             => $this->request->getQuery()
            ));
            $this->redirect("/module/payment/view", "Authorized by User", "sucess");              
            return;             
        } else {
            $this->redirect("/module/payment/view", "Error Authorize the User", "warning");  
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
        $this->redirect("/module/payment/view", $this->translate->translate("Cancelled by User"), "warning");  
        return;             
    }

     /** 
     * [ add a description ]
     *
     * @Get("/confirm/{payment_itens_id}")
     */

    /*confirm/{backend}/{payment_itens_id}")*/
    /*public function doExpressCheckoutPaymentPaymentRequest($payment_itens_id) {    
            
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
    }*/

    /* IWidgetContainer */
    /**
     * [getWidgets description]
     * @param  array  $widgetsIndexes [description]
     * @return [type]                 [description]
     * @implemen
     */
    /*
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('payment.overview', $widgetsIndexes)) {

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
             //
            return array(
             'payment.overview' => array(
                    'id'        => 'payment-panel',
                    'type'      => 'payment',
                    'title'     => 'Payment User',
                    'template'  => $this->template("widgets/overview"),
                    'panel'     => true,
                    'data'      => $data,
                    'box'       => 'blue'
                )
            );
        }
    }
    */
}