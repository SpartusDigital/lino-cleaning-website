<?php
/**
 * Lino Professional Cleaning Services — Quote Form Handler
 * Sends lead notifications to linocleanings@gmail.com via Hostinger's mail() function.
 *
 * Deploy note:
 *   - Hostinger sometimes rejects mail() when "From:" is a Gmail/Yahoo address.
 *     So we use a no-reply at the site's own domain as the sender and set
 *     "Reply-To" to the lead's email. Update $from_email below to an address
 *     under your Hostinger domain (e.g. noreply@linocleaningtampa.com).
 */

header('Content-Type: application/json; charset=utf-8');

// -----------------------------------------------------------------------------
// Settings
// -----------------------------------------------------------------------------
$to_email    = 'linocleanings@gmail.com';
$to_name     = 'Lino Professional Cleaning Services';

// IMPORTANT: change this to an email under the hosted domain once configured
$from_email  = 'noreply@linocleaningtampa.com';
$from_name   = 'Lino Website Form';

// Simple rate limit: same IP can submit once every N seconds
$rate_seconds = 30;

// -----------------------------------------------------------------------------
// Helpers
// -----------------------------------------------------------------------------
function respond($ok, $error = null) {
    echo json_encode(['ok' => (bool)$ok, 'error' => $error]);
    exit;
}
function clean($v) {
    if (!is_string($v)) return '';
    return trim(strip_tags($v));
}
function esc($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// -----------------------------------------------------------------------------
// Method check
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, 'Method not allowed.');
}

// -----------------------------------------------------------------------------
// Rate limiting (session-based, falls back silently if sessions unavailable)
// -----------------------------------------------------------------------------
if (function_exists('session_start')) {
    @session_start();
    $now = time();
    if (isset($_SESSION['last_submit']) && ($now - $_SESSION['last_submit']) < $rate_seconds) {
        http_response_code(429);
        respond(false, 'Please wait a moment before submitting again.');
    }
    $_SESSION['last_submit'] = $now;
}

// -----------------------------------------------------------------------------
// Honeypot
// -----------------------------------------------------------------------------
if (!empty($_POST['website'])) {
    // Bot — return a neutral success so it doesn't retry
    respond(true);
}

// -----------------------------------------------------------------------------
// Collect + validate
// -----------------------------------------------------------------------------
$name      = clean($_POST['name']      ?? '');
$phone     = clean($_POST['phone']     ?? '');
$email     = clean($_POST['email']     ?? '');
$address   = clean($_POST['address']   ?? '');
$service   = clean($_POST['service']   ?? '');
$frequency = clean($_POST['frequency'] ?? '');
$date      = clean($_POST['date']      ?? '');
$message   = clean($_POST['message']   ?? '');

if ($name === '')  respond(false, 'Name is required.');
if ($phone === '') respond(false, 'Phone is required.');

// Strip to digits for phone sanity check
$phone_digits = preg_replace('/\D/', '', $phone);
if (strlen($phone_digits) < 7) respond(false, 'Please enter a valid phone number.');

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid email address.');
}

// -----------------------------------------------------------------------------
// Build email
// -----------------------------------------------------------------------------
$subject = 'New Quote Request — ' . $name . ($service ? ' (' . $service . ')' : '');

$html  = '<!DOCTYPE html><html><body style="font-family: Arial, sans-serif; color:#1F2937; max-width:640px; margin:0 auto; padding:20px;">';
$html .= '<h2 style="color:#1E88E5; border-bottom:3px solid #1E88E5; padding-bottom:8px;">New Quote Request</h2>';
$html .= '<p style="color:#4B5563;">A new lead came in from the Lino Professional Cleaning Services website:</p>';
$html .= '<table style="width:100%; border-collapse:collapse; margin-top:12px;">';

$rows = [
    'Name'            => $name,
    'Phone'           => $phone,
    'Email'           => $email,
    'Home Address'    => $address,
    'Service Type'    => $service,
    'Frequency'       => $frequency,
    'Preferred Date'  => $date,
    'Message'         => $message,
];
foreach ($rows as $label => $val) {
    if ($val === '') continue;
    $html .= '<tr>';
    $html .= '<td style="padding:8px 12px; background:#F7FAFC; border:1px solid #E5E7EB; font-weight:600; width:35%;">' . esc($label) . '</td>';
    $html .= '<td style="padding:8px 12px; border:1px solid #E5E7EB;">' . nl2br(esc($val)) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

$html .= '<p style="margin-top:20px; color:#4B5563; font-size:14px;">';
$html .= 'Quick reply: ';
$html .= '<a href="sms:+' . esc($phone_digits) . '">Text this lead</a>';
if ($email !== '') {
    $html .= ' · <a href="mailto:' . esc($email) . '">Email this lead</a>';
}
$html .= '</p>';

$html .= '<p style="margin-top:24px; color:#94A3B8; font-size:12px;">Received: ' . esc(date('Y-m-d H:i:s')) . ' · IP: ' . esc($_SERVER['REMOTE_ADDR'] ?? 'unknown') . '</p>';
$html .= '</body></html>';

$plain  = "New Quote Request\n\n";
foreach ($rows as $label => $val) {
    if ($val === '') continue;
    $plain .= $label . ': ' . $val . "\n";
}
$plain .= "\nReceived: " . date('Y-m-d H:i:s');

// Headers
$boundary = md5(uniqid(mt_rand(), true));

$headers  = 'From: "' . $from_name . '" <' . $from_email . '>' . "\r\n";
if ($email !== '') {
    $headers .= 'Reply-To: "' . str_replace('"', '', $name) . '" <' . $email . '>' . "\r\n";
} else {
    $headers .= 'Reply-To: ' . $from_email . "\r\n";
}
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '"' . "\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

$body  = "--" . $boundary . "\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $plain . "\r\n\r\n";
$body .= "--" . $boundary . "\r\n";
$body .= "Content-Type: text/html; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $html . "\r\n\r\n";
$body .= "--" . $boundary . "--";

// -----------------------------------------------------------------------------
// Send
// -----------------------------------------------------------------------------
$sent = @mail($to_email, $subject, $body, $headers, '-f' . $from_email);

if (!$sent) {
    error_log('Lino form: mail() returned false for ' . $name . ' (' . $phone . ')');
    respond(false, 'We could not send your request. Please text us at (656) 224-0404.');
}

// -----------------------------------------------------------------------------
// Optional auto-reply to the lead
// -----------------------------------------------------------------------------
if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $reply_subject = 'We got your request — Lino Professional Cleaning Services';

    $reply_html  = '<!DOCTYPE html><html><body style="font-family: Arial, sans-serif; color:#1F2937; max-width:640px; margin:0 auto; padding:20px; line-height:1.6;">';
    $reply_html .= '<h2 style="color:#1E88E5;">Thanks, ' . esc(explode(' ', $name)[0]) . '!</h2>';
    $reply_html .= '<p>We received your quote request and will text or email you back — usually within the hour during business hours (Mon–Sat, 8am–6pm).</p>';
    $reply_html .= '<p>Need a faster reply? Text us anytime at <a href="sms:+16562240404">(656) 224-0404</a>.</p>';
    $reply_html .= '<p style="margin-top:24px;">— Lino Professional Cleaning Services<br>Tampa, FL</p>';
    $reply_html .= '<hr style="margin:24px 0; border:none; border-top:1px solid #E5E7EB;">';
    $reply_html .= '<p style="font-size:12px; color:#94A3B8;">This is an automated confirmation. Please reply to this email if you have any questions.</p>';
    $reply_html .= '</body></html>';

    $reply_headers  = 'From: "' . $to_name . '" <' . $from_email . '>' . "\r\n";
    $reply_headers .= 'Reply-To: ' . $to_email . "\r\n";
    $reply_headers .= 'MIME-Version: 1.0' . "\r\n";
    $reply_headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

    @mail($email, $reply_subject, $reply_html, $reply_headers, '-f' . $from_email);
}

respond(true);
