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
     * @Get("/authorized/{payment_itens_id}")
     */
    public function authorizedPaymentRequest($payment_itens_id) {
        $token   = $this->request->getQuery('token');

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
        echo "authorized";
    }
    
    /**
     * [ add a description ]
     *
     * @Get("/checkout/{token}")
     */
    public function checkoutPaymentRequest($token) {    
        $curl = curl_init();          
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'USER'      => 'conta-business_api1.test.com',
            'PWD'       => '1365001380',
            'SIGNATURE' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx',          
            'METHOD'    => 'GetExpressCheckoutDetails',
            'VERSION'   => '108',          
            'TOKEN'     => $token
        )));
          
        $response = curl_exec($curl);
          
        curl_close($curl);
          
        $nvp = array();
          
        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $nvp[$name] = urldecode($matches['value'][$offset]);
            }
        }         
        echo "<pre>"; print_r($nvp);
    }


    /**
     * [ add a description ]
     *
     * @Get("/createRecurring/{token}/{payerID}")
     */
    public function createRecurringPaymentRequest($token, $payerID) {    
            
            echo "token   => ".$token;
            echo "payerID => ".$payerID;

            $curl = curl_init();
              
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                'USER'      => 'conta-business_api1.test.com',
                'PWD'       => '1365001380',
                'SIGNATURE' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx',                      
                'METHOD'    => 'CreateRecurringPaymentsProfile',
                'VERSION'   => '108',
                'LOCALECODE'=> 'pt_BR',              
                'TOKEN'     => $token,
                'PayerID'   => $payerID,              
                'PROFILESTARTDATE' => '2012-10-08T16:00:00Z',
                'DESC'             => 'Exemplo',
                'BILLINGPERIOD'    => 'Month',
                'BILLINGFREQUENCY' => '1',
                'AMT'              => 100,
                'CURRENCYCODE'     => 'BRL',
                'COUNTRYCODE'      => 'BR',
                'MAXFAILEDPAYMENTS'=> 3
            )));
              
            $response =    curl_exec($curl);
              
            curl_close($curl);
              
            $nvp = array();
              
            if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
                foreach ($matches['name'] as $offset => $name) {
                    $nvp[$name] = urldecode($matches['value'][$offset]);
                }
            }              
            echo "<pre>"; print_r($nvp);
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
        echo "cancel";
    }


    /**
     * [ add a description ]
     *
     * @Get("/initiate/{payment_item_id}")
     */
    public function initiatePaymentRequest($payment_item_id) {

        // PEGAR O USUÁRIO ATUAL
        //$this->user->id

        //$paymentItemObject->getPayment()->getUser()->id

        Kint::dump($payment_item_id);
        $paymentItemObject = PaymentItem::findFirstById($payment_item_id);
        
        //SE DER ALGUMA ERRO ENTRA NA CONDIÇÃO    
        if (!$paymentItemObject) {
            echo "Não foi encontrado registro";
            exit;
        }

        $data = $paymentItemObject->toArray();
        $data['user'] = $paymentItemObject->getPayment()->getUser()->toArray();

        //ENVIA P/ PAYPAL    
        $result = $this->payment->sendPayment($data);        

        if ($this->request->isAjax()) {
            echo "json";
        } else {
            //header("location:".$result);            
            echo "<meta http-equiv=refresh content='0;URL=$result'>";
        }

        exit;
        $resut = Payment::find([
                'conditions' => 'payment_id = :pay_id: AND user_id = :user_id:',
                'bind'       => [
                'pay_id'     => $payment_id,
                'user_id'    => $user_id
            ]
        ]);
    }


    public function beforeModelCreate($evt, $model, $data) {
        if (
            array_key_exists('new-password', $data) &&
            !empty($data['new-password'])
        ) {
            // CHECK PASSWORD
            $di = DI::getDefault();

            // DEFINE AUTHENTICATION BACKEND
            $model->password = $this->authentication->hashPassword($data['new-password'], $model);
        }

        if (is_null($userModel->backend)) {
            $model->backend = strtolower($this->configuration->get("default_auth_backend"));
        }

        return true;
    }

    public function afterModelCreate($evt, $model, $data) {
        if (array_key_exists('usergroups', $data) && is_array($data['usergroups']) ) {
            foreach($data['usergroups'] as $group) {
                $userGroup = new UsersGroups();
                $userGroup->user_id = $model->id;
                $userGroup->group_id = $group['id'];
                $userGroup->save();
            }
        }

        return true;
    }


    public function beforeModelUpdate($evt, $model, $data) {
        if (
            array_key_exists('new-password', $data) &&
            array_key_exists('new-password-confirm', $data) &&
            (!empty($data['new-password']) || !empty($data['new-password-confirm']))
        ) {
            if ($data['new-password'] === $data['new-password-confirm']) {
                // CHECK PASSWORD
                if ($this->acl->isUserAllowed(null, "users", "change-password")) {
                    // DEFINE AUTHENTICATION BACKEND
                    if (
                        array_key_exists('old-password', $data) &&
                        !empty($data['old-password']) &&
                        $this->authentication->checkPassword($data['old-password'], $model)
                    ) {
                        $model->password = $this->authentication->hashPassword($data['new-password'], $model);
                    } else {
                        $message = new Message(
                            "Please provide your current password",
                            "password",
                            "warning"
                        );
                        $model->appendMessage($message);

                        return false;
                    }
                }
            } else {
                $message = new Message(
                    "Password confimation does not match",
                    "password",
                    "warning"
                );
                $model->appendMessage($message);

                return false;
            }
        } else {
            // NO PASSWD CHANGE, JUST LET HIM GO.. (BECAUSE ITS UPDATING SOME ANOTHER INFO)
        }

        if (array_key_exists('avatar', $data) && is_array($data['avatar']) ) {
            $userAvatarModel = new \Sysclass\Models\Users\UserAvatar();
            $userAvatarModel->assign($data['avatar']);
            $model->avatar = $userAvatarModel;
        }

        return true;
    }

    public function afterModelUpdate($evt, $model, $data) {
        if (array_key_exists('usergroups', $data) && is_array($data['usergroups']) ) {
            UsersGroups::find("user_id = {$userModel->id}")->delete();
            
            foreach($data['usergroups'] as $group) {
                $userGroup = new UsersGroups();
                $userGroup->user_id = $userModel->id;
                $userGroup->group_id = $group['id'];
                $userGroup->save();
            }
        }
    }

    protected function getDatatableItemOptions() {
        if ($this->request->hasQuery('block')) {
            return array(
                /*
                'check'  => array(
                    'icon'  => 'icon-check',
                    'link'  => $baseLink . 'block/%id$s',
                    'class' => 'btn-sm btn-danger'
                )
                */
                'check'  => array(
                    //'icon'        => 'icon-check',
                    //'link'        => $baseLink . "block/" . $item['id'],
                    //'text'            => $this->translate->translate('Disabled'),
                    //'class'       => 'btn-sm btn-danger',
                    'type'          => 'switch',
                    //'state'           => 'disabled',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-on-text' => $this->translate->translate('YES'),
                        'data-off-color' =>"danger",
                        'data-off-text' => $this->translate->translate('NO')
                    )
                )
            );
        } else {
            return parent::getDatatableItemOptions();
        }
    }

    protected function getDatatableSingleItemOptions($item) {
        if (!$this->request->hasQuery('block') && $item->pending == 1) {
            return array(
                'aprove' => array(
                    'icon'  => 'fa fa-lock',
                    //'link'  => $baseLink . "block/" . $item['id'],
                    'class' => 'btn-sm btn-info datatable-actionable tooltips',
                    'attrs' => array(
                        'data-datatable-action' => "aprove",
                        'data-original-title' => 'Aprove User'
                    )
                )
            );
        }
        return false;
    }

    protected function isUserAllowed($action, $args) {
        $allowed = parent::isUserAllowed($action);
        if (!$allowed) {
            switch($action) {
                case "edit" : {
                    // ALLOW IF THE USER IS UPDATING HIMSELF
                    return $this->_args['id'] == $this->getCurrentUser(true)->id;
                }
            }
        }
        return $allowed;
    }
}
