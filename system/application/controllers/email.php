<?php

require_once('EasyDeposit.php');

class Email extends EasyDeposit
{

    function Email()
    {
        // Initalise the parent
        parent::EasyDeposit();
    }

	function index()
    {
        // Compile the email
        $message = '';
        foreach ($this->easydeposit_steps as $stepname)
        {
            if ($stepname == 'email')
            {
                break;
            }
            include_once(APPPATH . 'controllers/' . $stepname . '.php');
            $stepclass = ucfirst($stepname);
            $message = call_user_func(array($stepclass, '_email'), $message);
        }
        $message .= $this->config->item('easydeposit_email_end');

        // Send the email
        $to = $_SESSION['user-email'];
        $from = $this->config->item('easydeposit_email_from');
        $fromname = $this->config->item('easydeposit_email_fromname');
        $cc = $this->config->item('easydeposit_email_cc');
        $subject = $this->config->item('easydeposit_email_subject');

        $headers = 'From: ' . $fromname . ' <' . $from . ">\r\n";

        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/plain; charset=utf-8\r\n";
        $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";

        if (!empty($cc))
        {
            $headers .= 'Cc: ' . $cc . "\r\n";
        }

        mail($to, $subject, $message, $headers);

        // Now go to the next step
        $this->_gotonextstep();
    }

}

?>