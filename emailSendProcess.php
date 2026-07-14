<?php
require "connection.php";
require "SMTP.php";
require "PHPMailer.php";
require "Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = "";
    $status = "error";

    $email = trim($_POST["email"] ?? "");
    $verification_code = trim($_POST["verification_code"] ?? "");

    if (empty($email)) {
        $message = "Please enter your Email Address.";
    } else if (strlen($email) > 100) {
        $message = "Email must have less than 100 characters.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid Email Address.";
    } else {
        $rs = Database::search("SELECT* FROM `users` WHERE `email`='" . $email . "'");
        $n = $rs->num_rows;

        if ($n == 1) {

            Database::iud("UPDATE `users` SET `verification_code`='" . $verification_code . "' WHERE `email`='" . $email . "'");

            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'chamuddikaw@gmail.com';
            $mail->Password = 'ktqg zfyq pjmy mqow';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('chamuddikaw@gmail.com', 'Avika');
            $mail->addReplyTo('chamuddikaw@gmail.com', 'Avika');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Avika Forgot Password Verification Code';
            $bodyContent = ' <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
            <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700);">
                <tr>
                    <td>
                        <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 35px;">
                                                <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;">You have
                                                    requested to reset your password</h1>
                                                <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                                <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                    We cannot simply send you your old password. A unique code will be generated for you to reset your password. To reset your password, enter this code correctly and renew your password through our website.
                                                </p>
                                                <p  style="color:#1e1e2d; font-weight:500; margin:0;font-size:20px; margin-top: 40px;">Your Verification Code:  ' . $verification_code . ' </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; 2026 Avika Hair Care. All rights reserved.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>';
            $mail->Body    = $bodyContent;

            if (!$mail->send()) {
                $message = "Verification Code Sending Failed.";
            } else {
                $status = "success";
                $message = "Email sending Success.";
            }
        } else {
           $message = "Invalid Email Address.";
        }
    }
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
