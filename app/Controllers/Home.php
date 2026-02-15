<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function email()
    {
        $email = service('email');

        $email->setFrom('your@example.com', 'Your Name');
        $email->setTo('pevay46601@manupay.com1');
        // $email->setCC('another@another-example.com');
        // $email->setBCC('them@their-example.com');

        $email->setSubject('outro teste');
        $email->setMessage('Testing the email class.');
        $email->setMessage('Q onda é essa?');


        if ($email->send()) {
            echo 'Email enviado com sucesso!';
        } else {
            echo $email->printDebugger();
        }
    }
}