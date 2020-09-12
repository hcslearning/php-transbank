<?php

namespace mailer;

use SendGrid\Mail\Mail;
use config\Config;
use SendGrid;

class Mailer {
    
    public static function mail($destinatario, $subject, $contenidoHtml, $contenidoText) {
        $email = new Mail();
        $email->setFrom( Config::MAIL_FROM );
        $email->addTo( $destinatario );
        $email->setSubject( $subject );
        $email->addContent("text/plain", $contenidoText);
        $email->addContent("text/html", $contenidoHtml);
        
        $sendgrid = new SendGrid( Config::SENDGRID_API_KEY );
        
        try {
            $response = $sendgrid->send( $email );
        } catch (Exception $ex) {
            
        }
        
    }
    
}
