<?php
namespace Sysclass\Modules\Payment;

use 
    Sysclass\Models\Payments\Payment,
    Sysclass\Models\Payments\PaymentItem,
    Kint;

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
     * @Get("/receive/{payment_item_id}")
     */
    
    public function receivePaymentRequest($payment_item_id) {

        Kint::dump($payment_item_id);
        $paymentObject = PaymentItem::findFirstById($payment_item_id);

        //SE DER ALGUMA ERRO ENTRA NA CONDIÇÃO    
        if (!$paymentObject) {
            echo "nao foi encontrado registro";
            exit;
        }



        Kint::dump($paymentObject->toArray(), $paymentObject->getPayment()->getUser()->toArray());
        exit;
        
        //CHAMA UMA CONFIGURAÇÃO PRE-DEFINIDA // app/config/local.ini
        $this->environment->paypal->user;
        $this->environment->paypal->pass;

        //$user_id = 1; $payment_id = 1;
        $object = new Payment();
        $object->assign();
        $object->payment_id = 3;

        //ENVIA P/ PAYPAL    
        $this->payment->EnviarPagamento(10);

        $resut = Payment::find([
                'conditions' => 'payment_id = :pay_id: AND user_id = :user_id:',
                'bind'       => [
                'pay_id'     => $payment_id,
                'user_id'    => $user_id
            ]
        ]);

        //foreach($resut)
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
