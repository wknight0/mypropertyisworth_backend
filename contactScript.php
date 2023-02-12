<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 
'/WITHHELD/Exception.php';
require 
'/WITHHELD/PHPMailer.php';
require '/WITHHELD/SMTP.php';

$mail = new PHPMailer(true);
$mail2 = new PHPMailer(true);
$r_servername = 'WITHHELD';
$r_username = 'WITHHELD';
$r_password = 'WITHHELD';
$r_databasename = 'WITHHELD';

$conn = new mysqli($r_servername, $r_username, $r_password, $r_databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = 'SELECT * FROM `WITHHELD` ORDER BY uniq_contact_id DESC LIMIT 1;';

$result = $conn->query($query);

if ($result->num_rows == 1)
{
    while($row = $result->fetch_assoc())
    {
        try {
            $variables = array();
            $variables['uniq_contact_id'] = $row['uniq_contact_id'];
            $variables['c_name'] = $row['c_name'];
            $variables['c_email'] = $row['c_email'];
            $variables['c_subject'] = $row['c_subject'];
            $variables['c_message'] = $row['c_message'];

            if (filesize('/WITHHELD.csv') == 0)
            {
                $file_var = array();
                $file_var['CONTACT_ID'] = 'CONTACT_ID';
                $file_var['NAME'] = 'NAME';
                $file_var['EMAIL'] = 'EMAIL';
                $file_var['SUBJECT'] = 'SUBJECT';
                $file_var['MESSAGE'] = 'MESSAGE';

                $fp = fopen('/WITHHELD.csv', 'w');
                fputcsv($fp, $file_var);
                fclose($fp);
            }

            $f = fopen('/WITHHELD.csv', 'a');
            fputcsv($f, $variables);
            fclose($f);

            $csv_file = '/WITHHELD.csv';

            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'WITHHELD';
            $mail->SMTPAuth = true;
            $mail->Username = 'WITHHELD'; 
            $mail->Password = 'WITHHELD';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
           
            $mail->setFrom($row['c_email'], $row['c_name']);
            $mail->addAddress('support@mypropertyisworth.nz', 'mypropertyisworth@gmail.com');
            $mail->addCC('mypropertyisworth@gmail.com');
            $mail->AddAttachment($csv_file);
           
            $mail->isHTML(true);
            $mail->Subject = 'Contact ID: ' . $row['uniq_contact_id']. ' - ' . $row['c_subject'];
            
            $template = file_get_contents('/WITHHELD/contact_template.html');
            
            foreach($variables as $key=>$value)
            {
                $template = str_replace('{{ '.$key.' }}', $value, $template);
            }
            
            $mail->Body = $template;
            $mail->AltBody = 'ID: ' . $row['uniq_contact_id']. '     Name:' . $row['c_name']. '     Email: ' . $row['c_email']. '     Subject: ' . $row['c_subject']. '     Body: ' . $row['c_message'];
           
           $mail->send();
            echo 'Message has been sent';
           
           } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: 
           {$mail->ErrorInfo}";
           }


           try {
            $mail2->SMTPDebug = 2;
            $mail2->isSMTP();
            $mail2->Host = 'WITHHELD';
            $mail2->SMTPAuth = true;
            $mail2->Username = 'WITHHELD'; 
            $mail2->Password = 'WITHHELD';
            $mail2->SMTPSecure = 'tls';
            $mail2->Port = 587;
           
            $mail2->setFrom('support@mypropertyisworth.nz', 'MyPropertyIsWorth');
            $mail2->addAddress($row['c_email']);
           
            $mail2->isHTML(true);
            $mail2->Subject = 'Contact Submission';
            
            $variables2 = array();
            $variables2['name'] = $row['c_name'];
            
            $template2 = file_get_contents('/WITHHELD/confirmation.html');
            
            foreach($variables2 as $key=>$value)
            {
                $template2 = str_replace('{{ '.$key.' }}', $value, $template2);
            }
            
            $mail2->Body = $template2;
            $mail2->AltBody = "Thank you for taking the time to fill out our form. We have received your submission and will get back to you as soon as possible. If you have any further enquiries please don't hesitate to reply to this email or contact us using one of our social media links below.";

           $mail2->send();
            echo 'Message has been sent';
           
           } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: 
           {$mail2->ErrorInfo}";
           }
    }
}

$conn->close();

?>