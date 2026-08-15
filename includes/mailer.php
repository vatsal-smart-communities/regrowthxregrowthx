<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

define('DEV_MODE', false);

function sendOtpEmail($email, $otpCode) {
    if (DEV_MODE) {
        return true;
    }
    
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'SMTP_HOST';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS; // Use App Password if Google blocks this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_USER, 'RegrowthX Support');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your RegrowthX Login Code';
        $mail->Body    = "
        <html>
        <head>
          <title>Your RegrowthX Login Code</title>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center;'>
          <h2>RegrowthX</h2>
          <p>Your login code is:</p>
          <h1 style='color: #059669; letter-spacing: 5px;'>$otpCode</h1>
          <p>This code will expire in 10 minutes.</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Your RegrowthX login code is: $otpCode. This code will expire in 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Return the exact PHPMailer error for debugging
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

function sendResetEmail($email, $resetCode) {
    if (DEV_MODE) {
        return true;
    }
    
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'SMTP_HOST';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS; // Use App Password if Google blocks this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_USER, 'RegrowthX Support');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your RegrowthX Password';
        $mail->Body    = "
        <html>
        <head>
          <title>Reset Your RegrowthX Password</title>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center;'>
          <h2>RegrowthX</h2>
          <p>You requested a password reset. Your reset code is:</p>
          <h1 style='color: #059669; letter-spacing: 5px;'>$resetCode</h1>
          <p>This code will expire in 15 minutes. If you did not request this, please ignore this email.</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Your RegrowthX password reset code is: $resetCode. This code will expire in 15 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * Send Welcome Email on Signup
 */
function sendWelcomeEmail($email, $name) {
    if (DEV_MODE) return true;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'SMTP_HOST';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'RegrowthX Support');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to RegrowthX!';
        $mail->Body    = "
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset='utf-8'>
          <title>Welcome to RegrowthX</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px;'>
          <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'>
            <div style='background-color: #0d1e12; padding: 28px; text-align: center;'>
              <h1 style='color: #ffffff; margin: 0; font-size: 26px; font-weight: bold;'>Regrowth<span style='color: #10b981;'>X</span></h1>
              <p style='color: #a7f3d0; margin: 5px 0 0 0; font-size: 14px;'>Advanced Hair Care & Growth Solutions</p>
            </div>
            <div style='padding: 32px; color: #374151; line-height: 1.6;'>
              <h2 style='color: #111827; margin-top: 0;'>Welcome aboard, " . htmlspecialchars($name) . "! 👋</h2>
              <p>Thank you for joining <strong>RegrowthX</strong>. We are thrilled to partner with you on your hair regrowth journey!</p>
              <p>Your account has been created successfully. You can now browse our Extra Strength 5% Minoxidil solutions, save your preferences, and track your orders in real-time.</p>
              
              <div style='text-align: center; margin: 30px 0;'>
                <a href='http://localhost/regrowthx/products.php' style='background-color: #059669; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 50px; font-weight: bold; display: inline-block; box-shadow: 0 4px 12px rgba(5,150,105,0.3);'>Explore Products</a>
              </div>
              
              <p style='font-size: 13px; color: #6b7280; border-t: 1px solid #f3f4f6; pt: 16px; margin-top: 24px;'>
                If you have any questions, our support team is available at <a href='mailto:mvatsal1103@gmail.com' style='color: #059669;'>mvatsal1103@gmail.com</a> or call us at <strong>+1 (718) 438-7400</strong> (Mon-Sat, 9AM - 6PM EST).
              </p>
            </div>
            <div style='background-color: #f3f4f6; padding: 16px; text-align: center; font-size: 12px; color: #9ca3af;'>
              © 2026 RegrowthX USA Labs. All rights reserved.
            </div>
          </div>
        </body>
        </html>
        ";
        $mail->AltBody = "Welcome to RegrowthX, {$name}! Your account is active. Explore our products at http://localhost/regrowthx/products.php";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * Send Order Confirmation Email
 */
function sendOrderConfirmationEmail($email, $name, $orderNumber, $totalAmount, $items = []) {
    if (DEV_MODE) return true;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'SMTP_HOST';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'RegrowthX Orders');
        $mail->addAddress($email, $name);

        $itemsHtml = '';
        foreach ($items as $item) {
            $unitPriceFormatted = '$' . number_format($item['price_inr'], 2);
            $totalPriceFormatted = '$' . number_format($item['price_inr'] * $item['quantity'], 2);
            $itemsHtml .= "
            <tr>
              <td style='padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111827;'>
                <strong>" . htmlspecialchars($item['title']) . "</strong><br>
                <span style='font-size: 12px; color: #6b7280;'>" . htmlspecialchars($item['variant_name']) . "</span>
              </td>
              <td style='padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; text-align: center;'>{$item['quantity']}</td>
              <td style='padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111827; font-weight: bold; text-align: right;'>{$totalPriceFormatted}</td>
            </tr>
            ";
        }

        $formattedTotal = '$' . number_format($totalAmount, 2);

        $mail->isHTML(true);
        $mail->Subject = "Order Confirmed! #{$orderNumber} - RegrowthX";
        $mail->Body    = "
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset='utf-8'>
          <title>Order Confirmation #{$orderNumber}</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px;'>
          <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'>
            <div style='background-color: #0d1e12; padding: 28px; text-align: center;'>
              <h1 style='color: #ffffff; margin: 0; font-size: 26px; font-weight: bold;'>Regrowth<span style='color: #10b981;'>X</span></h1>
              <p style='color: #a7f3d0; margin: 5px 0 0 0; font-size: 14px;'>Order Confirmation</p>
            </div>
            <div style='padding: 32px; color: #374151; line-height: 1.6;'>
              <h2 style='color: #111827; margin-top: 0;'>Thank you for your order, " . htmlspecialchars($name) . "! 🎉</h2>
              <p>We have received your order <strong>#{$orderNumber}</strong> and are preparing it for shipment.</p>
              
              <div style='background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin: 20px 0;'>
                <p style='margin: 0; font-size: 13px; color: #166534;'><strong>Order Status:</strong> Processing</p>
                <p style='margin: 4px 0 0 0; font-size: 13px; color: #166534;'><strong>Estimated Delivery:</strong> 3 - 5 Business Days (Free US Shipping)</p>
              </div>

              <h3 style='color: #111827; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px; margin-top: 24px;'>Order Summary</h3>
              <table style='width: 100%; border-collapse: collapse;'>
                <thead>
                  <tr style='border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 12px; color: #6b7280; text-transform: uppercase;'>
                    <th style='padding-bottom: 8px;'>Item</th>
                    <th style='padding-bottom: 8px; text-align: center;'>Qty</th>
                    <th style='padding-bottom: 8px; text-align: right;'>Price</th>
                  </tr>
                </thead>
                <tbody>
                  {$itemsHtml}
                </tbody>
              </table>

              <div style='border-top: 2px solid #e5e7eb; margin-top: 16px; padding-top: 16px;'>
                <div style='display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; color: #111827;'>
                  <span>Total Amount Paid:</span>
                  <span style='color: #059669;'>{$formattedTotal}</span>
                </div>
              </div>

              <div style='text-align: center; margin: 30px 0;'>
                <a href='http://localhost/regrowthx/orders.php' style='background-color: #059669; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 50px; font-weight: bold; display: inline-block;'>View Order Status</a>
              </div>
              
              <p style='font-size: 13px; color: #6b7280; border-t: 1px solid #f3f4f6; pt: 16px; margin-top: 24px;'>
                Need help with your order? Contact us at <a href='mailto:mvatsal1103@gmail.com' style='color: #059669;'>mvatsal1103@gmail.com</a> or call +1 (718) 438-7400.
              </p>
            </div>
            <div style='background-color: #f3f4f6; padding: 16px; text-align: center; font-size: 12px; color: #9ca3af;'>
              © 2026 RegrowthX USA Labs. All rights reserved.
            </div>
          </div>
        </body>
        </html>
        ";
        $mail->AltBody = "Thank you for your order #{$orderNumber}, {$name}! Total Amount: {$formattedTotal}. View order details at http://localhost/regrowthx/orders.php";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * Send Order Status Update Email (When Admin changes status)
 */
function sendOrderStatusUpdateEmail($email, $name, $orderNumber, $newStatus) {
    if (DEV_MODE) return true;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'SMTP_HOST';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'RegrowthX Orders');
        $mail->addAddress($email, $name);

        $statusBadgeColor = '#059669';
        if ($newStatus === 'shipped') $statusBadgeColor = '#7c3aed';
        if ($newStatus === 'delivered') $statusBadgeColor = '#059669';
        if ($newStatus === 'cancelled') $statusBadgeColor = '#dc2626';

        $statusDisplay = strtoupper(htmlspecialchars($newStatus));

        $mail->isHTML(true);
        $mail->Subject = "Order Status Update: #{$orderNumber} is now {$statusDisplay}";
        $mail->Body    = "
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset='utf-8'>
          <title>Order Status Update #{$orderNumber}</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px;'>
          <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'>
            <div style='background-color: #0d1e12; padding: 28px; text-align: center;'>
              <h1 style='color: #ffffff; margin: 0; font-size: 26px; font-weight: bold;'>Regrowth<span style='color: #10b981;'>X</span></h1>
              <p style='color: #a7f3d0; margin: 5px 0 0 0; font-size: 14px;'>Status Update Notification</p>
            </div>
            <div style='padding: 32px; color: #374151; line-height: 1.6;'>
              <h2 style='color: #111827; margin-top: 0;'>Hello " . htmlspecialchars($name) . ",</h2>
              <p>The status of your order <strong>#{$orderNumber}</strong> has been updated.</p>
              
              <div style='background-color: #f9fafb; border: 1px dashed #d1d5db; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0;'>
                <p style='margin: 0; font-size: 13px; color: #6b7280; text-transform: uppercase; font-weight: bold;'>New Order Status</p>
                <span style='display: inline-block; margin-top: 8px; background-color: {$statusBadgeColor}; color: #ffffff; padding: 8px 20px; border-radius: 50px; font-size: 16px; font-weight: bold;'>{$statusDisplay}</span>
              </div>

              <p>You can log into your RegrowthX account at any time to review your order details and track delivery progress.</p>

              <div style='text-align: center; margin: 30px 0;'>
                <a href='http://localhost/regrowthx/orders.php' style='background-color: #059669; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 50px; font-weight: bold; display: inline-block;'>Check Order Details</a>
              </div>
              
              <p style='font-size: 13px; color: #6b7280; border-t: 1px solid #f3f4f6; pt: 16px; margin-top: 24px;'>
                If you have questions regarding this update, please contact our support team at <a href='mailto:mvatsal1103@gmail.com' style='color: #059669;'>mvatsal1103@gmail.com</a>.
              </p>
            </div>
            <div style='background-color: #f3f4f6; padding: 16px; text-align: center; font-size: 12px; color: #9ca3af;'>
              © 2026 RegrowthX USA Labs. All rights reserved.
            </div>
          </div>
        </body>
        </html>
        ";
        $mail->AltBody = "Hello {$name}, your order #{$orderNumber} status is now {$statusDisplay}. View details at http://localhost/regrowthx/orders.php";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * Send Contact Us Email to Admin
 */
function sendContactEmail($name, $email, $message) {
    if (DEV_MODE) return true;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'SMTP_HOST';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'RegrowthX Website');
        $mail->addAddress(SMTP_USER, 'RegrowthX Admin'); // Send TO admin
        $mail->addReplyTo($email, $name); // Allow replying directly to the customer

        $mail->isHTML(true);
        $mail->Subject = "New Contact Message from " . htmlspecialchars($name);
        $mail->Body    = "
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset='utf-8'>
          <title>New Contact Message</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px;'>
          <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb;'>
            <div style='background-color: #0d1e12; padding: 20px; text-align: center;'>
              <h2 style='color: #ffffff; margin: 0;'>New Contact Inquiry</h2>
            </div>
            <div style='padding: 30px; color: #374151; line-height: 1.6;'>
              <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
              <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
              <hr style='border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;'>
              <p><strong>Message:</strong></p>
              <p style='background: #f3f4f6; padding: 15px; border-radius: 8px; white-space: pre-wrap;'>" . htmlspecialchars($message) . "</p>
            </div>
          </div>
        </body>
        </html>
        ";
        $mail->AltBody = "New message from {$name} ({$email}):\n\n{$message}";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
