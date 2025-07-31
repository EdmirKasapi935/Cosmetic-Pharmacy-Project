<?php

namespace App\Controllers;

require "vendor/autoload.php";

use App\Controller;
use App\DBAccessor;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Dotenv;

class MailController extends Controller
{
    private $dotenv;

    private $dbAccessor;

    public function __construct()
    {
        $this->dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '\\..\\..\\');
        $this->dotenv->load();

        $this->dbAccessor = new DBAccessor();
    }

    public function recieveSubscription()
    {
        $request = $_POST;

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_ADD'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPKeepAlive = true;
        $mail->Port = $_ENV['SMTP_PORT'];;
        $mail->setFrom($_ENV['SMTP_ADD']);

        $userName = filter_var($request["UserSubName"],FILTER_SANITIZE_SPECIAL_CHARS);
        $userEmail = filter_var($request["UserSubEmail"],FILTER_SANITIZE_SPECIAL_CHARS);
    
         
        if (!$this->validateNotNull(array($userName, $userEmail))) {
        echo "<script> window.location.replace('/')</script>";
        return;
        }
        
        $this -> dbAccessor -> registerSubscriberToDB($userName, $userEmail);

        $template = file_get_contents("./styles/mailTemplates/subscriptionmailtemplate.php");

        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = "Cosmetic Pharmacy Newsletter Subscription";
        $mail->Body = $template;   //file_get_contents("mailtemplate.php");

        if (!$this->validateCaptcha()) {
            header("Location: /?message=Captcha not validated");
            return;
        }
        $mail->send();

        $this -> notifySubscription($userName, $userEmail);

        $mail -> smtpClose();

        echo "<script> window.location.replace('/?message=Subscription Mail sent')</script>";
        
    }

     private function notifySubscription($name, $email)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_ADD'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPKeepAlive = true;
        $mail->Port = $_ENV['SMTP_PORT'];;
        $mail->setFrom($_ENV['SMTP_ADD']);

        $template = file_get_contents("./styles/mailTemplates/subscriptionnotif.php");
        
        //some extra speial characters were added to make the elements more distinguishable and less prone to user mishaps
        $template = str_replace("Username_Here----", $name, $template);
        $template = str_replace("Email_Here--**", $email, $template);

        $mail->addAddress($_ENV['MAIN_ADD']);
        $mail->isHTML(true);
        $mail->Subject = "New User Subscribed";
        $mail->Body = $template;   //file_get_contents("mailtemplate.php");

        $mail->send();
    }


    public function recieveContactForm()
    {
        $request = $_POST;

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_ADD'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPKeepAlive = true;
        $mail->Port = $_ENV['SMTP_PORT'];;
        $mail->setFrom($_ENV['SMTP_ADD']);

        $firstname = filter_var($request["FirstName"],FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_var($request["LastName"],FILTER_SANITIZE_SPECIAL_CHARS);
        $userEmail = filter_var($request["UserEmail"],FILTER_SANITIZE_EMAIL);
        $userMessage = filter_var($request["UserMessage"],FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$this->validateNotNull(array($firstname, $lastname, $userEmail,  $userMessage))) {
        echo "<script> window.location.replace('/contactUs')</script>";
        return;
        }

        $userName = $firstname . " " . $lastname;

        if (!isset($request["UserSubject"]) || (trim($request["UserSubject"]) == "")) {
            $userSubject = "Contact from a user";
        } else {
            $userSubject = filter_var($request["UserSubject"], FILTER_SANITIZE_SPECIAL_CHARS);
        }

        if (!isset($request["UserPhoneNumber"]) || (trim($request["UserPhoneNumber"]) == "") ) {
            $userPhoneNumber = "Not Given";
        } else {
            $userPhoneNumber = filter_var($request["UserPhoneNumber"], FILTER_SANITIZE_NUMBER_INT);
        }

        $template = file_get_contents("./styles/mailTemplates/mailtemplate.php");

        $template = str_replace("Thank you for contacting us!", "Thank you for contacting us, {$firstname}!", $template);

        

        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = "Pharmacy Contact";
        $mail->Body = $template;   //file_get_contents("mailtemplate.php");

        if (!$this->validateCaptcha()) {
            header("Location: /contactUs?message=Captcha not validated");
            return;
        }
        $mail->send();
         
        //the part below sends the email to the man site account

        $this -> notifyContact($userName, $userEmail, $userSubject, $userPhoneNumber, $userMessage);
        $mail -> smtpClose(); 

        //header("Location: /contactUs?message=Contact Form Mail sent");
        echo "<script> window.location.replace('/contactUs?message=Contact Form Mail sent')</script>";
        exit;
    }

    private function notifyContact($name, $email, $subject, $phone, $content)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_ADD'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPKeepAlive = true;
        $mail->Port = $_ENV['SMTP_PORT'];;
        $mail->setFrom($_ENV['SMTP_ADD']);

        $template = file_get_contents("./styles/mailTemplates/contactnotif.php");
        
        //some extra speial characters were added to make the elements more distinguishable and less prone to user mishaps
        $template = str_replace("Username_Here----", $name, $template);
        $template = str_replace("Email_Here--**", $email, $template);
        $template = str_replace("Phone_Here-*-*", $phone, $template);
        $template = str_replace("Message_Here-*-*--", $content, $template);

        $mail->addAddress($_ENV['MAIN_ADD']);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $template;   //file_get_contents("mailtemplate.php");

        $mail->send();

    }

   

    private function validateCaptcha()
    {
        $secretKey = $_ENV['CAPTCHA_SECRET_KEY'];

        // Get response from POST
        $recaptchaResponse = $_POST['g-recaptcha-response'];

        // Verify reCAPTCHA
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        // Make POST request to Google
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ]
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($verifyUrl, false, $context);
        $result = json_decode($response, true);

        if ($result["success"] === true) 
            return true;
        else 
            return false;
    }

     private function validateNotNull($info)
    {
        foreach($info as $key => $val)
        {
           if(trim($val) == "")
           {
              echo "<script> alert('Please fill the fields correctly') </script>";
              return false;
           }
        }
        
        return true;
    
    }

}
