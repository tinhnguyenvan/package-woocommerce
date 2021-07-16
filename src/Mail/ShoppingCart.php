<?php

namespace TinhPHP\Woocommerce\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShoppingCart extends Mailable
{
    use Queueable;
    use SerializesModels;

    public const ACTION_SEND_MAIL_CUSTOMER = 'send_mail_customer';
    public const ACTION_RESEND_MAIL_ORDER = 'resend_mail_order';

    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function build()
    {
        $action = $this->data['action'] ?? '';
        $params = $this->data;
        switch ($action) {
            case self::ACTION_SEND_MAIL_CUSTOMER:
                $this->sendMailCustomer($params);
                break;
            case self::ACTION_RESEND_MAIL_ORDER:
                $this->resendMailOrder($params);
                break;
        }
    }

    private function sendMailCustomer($params): ShoppingCart
    {
        return $this->to($this->data['email'])
            ->cc($this->data['email_cc'])
            ->subject(trans('lang_woocommerce::sale_order.subject.send_mail_customer'))
            ->view('view_woocommerce::web.email.sale_order.send_mail_customer', $params);
    }

    private function resendMailOrder($params): ShoppingCart
    {
        return $this->to($this->data['email'])
            ->cc($this->data['email_cc'])
            ->subject(trans('lang_woocommerce::sale_order.subject.resend_mail_order'))
            ->view('view_woocommerce::web.email.sale_order.resend_mail_order', $params);
    }
}
