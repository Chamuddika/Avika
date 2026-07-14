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

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";
    $verification_code = trim($_POST["verification_code"] ?? "");

    if (empty($name)) {
        $message = "Please enter your Name.";
    } else if (strlen($name) > 45) {
        $message = "Your Name must have less than 45 characters.";
    } else if (empty($mobile)) {
        $message = "Please enter your Mobile Number.";
    } else if (!preg_match("/^07[01245678][0-9]{7}$/", $mobile)) {
        $message = "Invalid Mobile Number.";
    } else if (empty($email)) {
        $message = "Please enter your Email Address.";
    } else if (strlen($email) > 100) {
        $message = "Email must have less than 100 characters.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid Email Address.";
    } else if (empty($password)) {
        $message = "Please enter your Password.";
    } else if (strlen($password) < 8 || strlen($password) > 16) {
        $message = "Password length must be between 8 - 16 characters.";
    } else if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$])[A-Za-z\d@#$]{8,16}$/", $password)) {
        $message = "Password must contain letters, numbers and @ # $ symbols.";
    } else if ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {

        $rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "' OR 
    `mobile`='" . $mobile . "'");
        $n = $rs->num_rows;

        if ($n > 0) {
            $message = "User with the same Mobile Number or Email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $d = new DateTime();
            $tz = new DateTimeZone("Asia/Colombo");
            $d->setTimezone($tz);
            $date = $d->format("Y-m-d H:i:s");

            Database::iud("INSERT INTO `users`
            (`email`,`password`,`name`,`mobile`,
            `verification_code`,`created_at`,`updated_at`)
            VALUES
            ('" . $email . "',
            '" . $hashedPassword . "',
            '" . $name . "',
            '" . $mobile . "',
            '" . $verification_code . "',
            '" . $date . "',
            '" . $date . "')");

            //email sending 
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
            $mail->Subject = 'Avika Email Verification';

            $verificationLink = "http://localhost/avika/verify.php?email="
                . urlencode($email) .
                "&code=" . urlencode($verification_code);

            $bodyContent = '
                            <body
                            style="
                                margin: 0;
                                padding: 0;
                                background-color: #f4f4f4;
                                font-family: Arial, sans-serif;
                            "
                            >
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                <td align="center" style="padding: 40px 0">
                                    <table
                                    width="600"
                                    cellpadding="0"
                                    cellspacing="0"
                                    border="0"
                                    style="background: #ffffff; border-radius: 10px; overflow: hidden"
                                    >
                                    <tr>
                                        <td
                                        align="center"
                                        style="
                                            background: #2563eb;
                                            color: white;
                                            padding: 30px;
                                            font-size: 28px;
                                            font-weight: bold;
                                        "
                                        >
                                        Verify Your Email
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="padding: 40px; color: #333333">
                                        <h2 style="margin-top: 0">Welcome {{name}}</h2>

                                        <p style="font-size: 16px; line-height: 1.6">
                                            Thank you for registering with Avika Hair Care. Please verify
                                            your email address by clicking the button below.
                                        </p>

                                        <div style="text-align: center; margin: 35px 0">
                                            <a
                                            href="{{verification_link}}"
                                            style="
                                                background: #2563eb;
                                                color: white;
                                                padding: 15px 35px;
                                                text-decoration: none;
                                                font-size: 18px;
                                                border-radius: 8px;
                                                display: inline-block;
                                                font-weight: bold;
                                            "
                                            >
                                            Verify Email
                                            </a>
                                        </div>

                                        <p style="font-size: 15px; line-height: 1.6">
                                            Or use this verification code:
                                        </p>

                                        <div style="text-align: center; margin: 25px 0">
                                            <span
                                            style="
                                                display: inline-block;
                                                background: #f3f4f6;
                                                padding: 18px 35px;
                                                font-size: 32px;
                                                letter-spacing: 6px;
                                                font-weight: bold;
                                                border-radius: 8px;
                                                color: #2563eb;
                                            "
                                            >
                                            {{verification_code}}
                                            </span>
                                        </div>

                                        <p style="font-size: 15px; line-height: 1.6">
                                            This verification link will expire in 10 minutes.
                                        </p>

                                        <p style="font-size: 15px; line-height: 1.6">
                                            If you did not create this account, you can safely ignore this
                                            email.
                                        </p>

                                        <br />

                                        <p style="font-size: 15px">
                                            Regards,<br />
                                            <strong>Avika Hair Care</strong>
                                        </p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td
                                        align="center"
                                        style="
                                            background: #f9fafb;
                                            padding: 20px;
                                            font-size: 13px;
                                            color: #666;
                                        "
                                        >
                                        &copy; 2026 Avika Hair Care. All rights reserved.
                                        </td>
                                    </tr>
                                    </table>
                                </td>
                                </tr>
                            </table>
                            </body>

            ';

            $emailBody = str_replace(
                ["{{name}}", "{{verification_code}}", "{{verification_link}}"],
                [$name, $verification_code, $verificationLink],
                $bodyContent
            );

            $mail->Body = $emailBody;


            if (!$mail->send()) {
                $message = "Verification Code Sending Failed.";
            } else {

                $status = "success";
                $message = "User registered successfully. Please check your email for the verification code.";
            }
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
