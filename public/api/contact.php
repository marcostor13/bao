<?php
declare(strict_types=1);

// ── Headers ───────────────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$origin  = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = ['https://baorganization.com', 'https://www.baorganization.com'];
if (in_array($origin, $allowed, true) || str_contains($origin, 'localhost')) {
    header("Access-Control-Allow-Origin: $origin");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

function clean(string $v): string {
    return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
}

// ── Site base URL for logo (update after deploy) ──────────────────────────────
$siteUrl = 'https://baorganization.com';

try {
    // ── Fields ────────────────────────────────────────────────────────────────
    $service      = clean($_POST['service']      ?? '');
    $timeline     = clean($_POST['timeline']     ?? '');
    $fullName     = clean($_POST['fullName']      ?? '');
    $email        = trim($_POST['email']          ?? '');
    $phone        = clean($_POST['phone']         ?? '');
    $address      = clean($_POST['address']       ?? '');
    $instagram    = clean($_POST['instagram']     ?? '');
    $spaces       = $_POST['spaces']              ?? [];
    $primaryGoals = clean($_POST['primaryGoals']  ?? '');
    $wellness     = clean($_POST['wellness']      ?? '');
    $theEdit      = clean($_POST['theEdit']       ?? '');

    // ── Validation ────────────────────────────────────────────────────────────
    $errors = [];
    if (empty($service))      $errors[] = 'Service is required.';
    if (empty($timeline))     $errors[] = 'Timeline is required.';
    if (empty($fullName))     $errors[] = 'Full name is required.';
    if (empty($email))        $errors[] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
    if (empty($phone))        $errors[] = 'Phone is required.';
    elseif (!preg_match('/^\+?[\d\s\-(). ]{7,20}$/', $phone)) $errors[] = 'Invalid phone.';
    if (empty($address))      $errors[] = 'Address is required.';
    if (empty($primaryGoals)) $errors[] = 'Primary goals are required.';
    if (empty($spaces) || !is_array($spaces)) $errors[] = 'Select at least one space.';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        exit;
    }

    $emailClean = filter_var($email, FILTER_SANITIZE_EMAIL);
    $spacesStr  = implode(', ', array_map('htmlspecialchars', (array)$spaces));
    $instagramDisplay = $instagram ?: '—';
    $wellnessDisplay  = $wellness  ?: '—';
    $theEditDisplay   = $theEdit   ?: '—';

    // ── File uploads ──────────────────────────────────────────────────────────
    $uploadsDir    = __DIR__ . '/../uploads/';
    $attachedFiles = [];
    $photoCount    = 0;

    if (!empty($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
        $count = count($_FILES['photos']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $tmpPath  = $_FILES['photos']['tmp_name'][$i];
            $origName = basename($_FILES['photos']['name'][$i]);
            $size     = $_FILES['photos']['size'][$i];
            $finfo    = new finfo(FILEINFO_MIME_TYPE);
            $mime     = $finfo->file($tmpPath);
            $allowedMimes = ['image/jpeg','image/png','image/webp','image/gif','image/heic'];
            if (!in_array($mime, $allowedMimes, true) || $size > 10 * 1024 * 1024) continue;
            $safeName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $origName);
            $dest     = $uploadsDir . $safeName;
            if (move_uploaded_file($tmpPath, $dest)) {
                chmod($dest, 0644);
                $attachedFiles[] = ['path' => $dest, 'name' => $origName, 'mime' => $mime];
                $photoCount++;
            }
        }
    }

    $photosDisplay = $photoCount > 0
        ? $photoCount . ' photo' . ($photoCount > 1 ? 's' : '') . ' attached'
        : 'No photos uploaded';

    // ── HTML Email Template ───────────────────────────────────────────────────
    $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Inquiry — BA Organization</title>
</head>
<body style="margin:0;padding:0;background-color:#F8F7F4;font-family:Georgia,'Times New Roman',serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F8F7F4;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="620" cellpadding="0" cellspacing="0" border="0" style="max-width:620px;width:100%;">

          <!-- ── HEADER ── -->
          <tr>
            <td style="background-color:#1D293C;padding:32px 40px;">
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td valign="middle" style="border-right:1px solid rgba(213,173,127,0.35);padding-right:24px;width:100px;">
                    <img src="{$siteUrl}/images/logo.webp" alt="BA Organization" width="80" style="display:block;width:80px;height:auto;" />
                  </td>
                  <td valign="middle" style="padding-left:24px;">
                    <p style="margin:0 0 4px 0;font-family:Georgia,serif;font-size:22px;font-weight:400;color:#ffffff;letter-spacing:0.08em;text-transform:uppercase;">BA Organization</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:13px;color:#D5AD7F;font-style:italic;">New Client Inquiry</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- ── INTRO BAND ── -->
          <tr>
            <td style="background-color:#D5AD7F;padding:14px 40px;">
              <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#1D293C;">New Inquiry Received</p>
            </td>
          </tr>

          <!-- ── BODY ── -->
          <tr>
            <td style="background-color:#ffffff;padding:40px 40px 8px;">

              <!-- Service & Timeline highlight -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:36px;">
                <tr>
                  <td width="50%" style="background-color:#F8F7F4;border:1px solid #e8e4dd;padding:18px 20px;vertical-align:top;">
                    <p style="margin:0 0 4px 0;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#D5AD7F;">Service Interest</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:15px;color:#1D293C;">{$service}</p>
                  </td>
                  <td width="8" style="font-size:0;line-height:0;">&nbsp;</td>
                  <td width="50%" style="background-color:#F8F7F4;border:1px solid #e8e4dd;padding:18px 20px;vertical-align:top;">
                    <p style="margin:0 0 4px 0;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#D5AD7F;">Timeline</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:15px;color:#1D293C;">{$timeline}</p>
                  </td>
                </tr>
              </table>

              <!-- ── 01. CLIENT DETAILS ── -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                <tr>
                  <td colspan="2" style="border-bottom:2px solid #D5AD7F;padding-bottom:8px;margin-bottom:16px;">
                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.25em;text-transform:uppercase;color:#D5AD7F;">01 &nbsp;·&nbsp; Client Details</p>
                  </td>
                </tr>
                <tr><td height="14"></td></tr>
                <tr>
                  <td width="50%" style="padding:8px 16px 8px 0;vertical-align:top;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 3px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Full Name</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;">{$fullName}</p>
                  </td>
                  <td width="50%" style="padding:8px 0 8px 16px;vertical-align:top;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 3px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Email Address</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;"><a href="mailto:{$emailClean}" style="color:#D5AD7F;text-decoration:none;">{$emailClean}</a></p>
                  </td>
                </tr>
                <tr><td height="8" colspan="2"></td></tr>
                <tr>
                  <td width="50%" style="padding:8px 16px 8px 0;vertical-align:top;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 3px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Phone Number</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;"><a href="tel:{$phone}" style="color:#1D293C;text-decoration:none;">{$phone}</a></p>
                  </td>
                  <td width="50%" style="padding:8px 0 8px 16px;vertical-align:top;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 3px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Project Address</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;">{$address}</p>
                  </td>
                </tr>
                <tr><td height="8" colspan="2"></td></tr>
                <tr>
                  <td colspan="2" style="padding:8px 0;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 3px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Instagram Handle</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;">{$instagramDisplay}</p>
                  </td>
                </tr>
              </table>

              <!-- ── 02. PROJECT SCOPE ── -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                <tr>
                  <td style="border-bottom:2px solid #D5AD7F;padding-bottom:8px;">
                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.25em;text-transform:uppercase;color:#D5AD7F;">02 &nbsp;·&nbsp; Project Scope</p>
                  </td>
                </tr>
                <tr><td height="14"></td></tr>
                <tr>
                  <td style="padding:8px 0;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 6px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Spaces to Transform</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;">{$spacesStr}</p>
                  </td>
                </tr>
                <tr><td height="8"></td></tr>
                <tr>
                  <td style="padding:8px 0;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 6px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Primary Goals</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;line-height:1.7;">{$primaryGoals}</p>
                  </td>
                </tr>
              </table>

              <!-- ── 03. LIFESTYLE & WELLNESS ── -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                <tr>
                  <td style="border-bottom:2px solid #D5AD7F;padding-bottom:8px;">
                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.25em;text-transform:uppercase;color:#D5AD7F;">03 &nbsp;·&nbsp; Lifestyle &amp; Wellness</p>
                  </td>
                </tr>
                <tr><td height="14"></td></tr>
                <tr>
                  <td style="padding:8px 0;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 6px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Wellness &amp; Sensitivities</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;line-height:1.7;">{$wellnessDisplay}</p>
                  </td>
                </tr>
                <tr><td height="8"></td></tr>
                <tr>
                  <td style="padding:8px 0;border-bottom:1px solid #f0ede8;">
                    <p style="margin:0 0 6px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">The Edit</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;">{$theEditDisplay}</p>
                  </td>
                </tr>
              </table>

              <!-- ── 04. VISUALS ── -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:40px;">
                <tr>
                  <td style="border-bottom:2px solid #D5AD7F;padding-bottom:8px;">
                    <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.25em;text-transform:uppercase;color:#D5AD7F;">04 &nbsp;·&nbsp; Visuals</p>
                  </td>
                </tr>
                <tr><td height="14"></td></tr>
                <tr>
                  <td style="padding:8px 0;">
                    <p style="margin:0 0 6px 0;font-family:Arial,sans-serif;font-size:10px;color:#9a9a9a;letter-spacing:0.1em;text-transform:uppercase;">Photos</p>
                    <p style="margin:0;font-family:Georgia,serif;font-size:14px;color:#1D293C;">{$photosDisplay}</p>
                  </td>
                </tr>
              </table>

              <!-- CTA Reply Button -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:40px;">
                <tr>
                  <td align="center">
                    <a href="mailto:{$emailClean}?subject=Re: Your BA Organization Inquiry"
                       style="display:inline-block;background-color:#D5AD7F;color:#ffffff;font-family:Arial,sans-serif;font-size:12px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;padding:16px 40px;">
                      Reply to {$fullName}
                    </a>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- ── FOOTER ── -->
          <tr>
            <td style="background-color:#1D293C;padding:24px 40px;">
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td>
                    <p style="margin:0 0 4px 0;font-family:Georgia,serif;font-size:13px;color:rgba(255,255,255,0.7);">BA Organization &nbsp;·&nbsp; Georgia</p>
                    <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;color:rgba(255,255,255,0.4);">This email was sent automatically from the contact form at baorganization.com</p>
                  </td>
                  <td align="right" valign="middle">
                    <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;color:#D5AD7F;">(678) 749-0931</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
HTML;

    // ── Plain text fallback ───────────────────────────────────────────────────
    $textBody = <<<TEXT
BA ORGANIZATION — New Client Inquiry
======================================

SERVICE:  {$service}
TIMELINE: {$timeline}

01. CLIENT DETAILS
------------------
Name:       {$fullName}
Email:      {$emailClean}
Phone:      {$phone}
Address:    {$address}
Instagram:  {$instagramDisplay}

02. PROJECT SCOPE
-----------------
Spaces:        {$spacesStr}
Primary Goals: {$primaryGoals}

03. LIFESTYLE & WELLNESS
------------------------
Sensitivities: {$wellnessDisplay}
The Edit:      {$theEditDisplay}

04. VISUALS
-----------
Photos: {$photosDisplay}

======================================
Sent from baorganization.com
TEXT;

    // ── Build MIME email ──────────────────────────────────────────────────────
    $to         = 'hello@baorganization.com';
    $subject    = "New Inquiry — {$fullName} · {$service}";
    $boundary   = 'BAO_' . md5(uniqid((string)rand(), true));
    $altBound   = 'BAO_ALT_' . md5(uniqid((string)rand(), true));

    $headers    = "From: noreply@baorganization.com\r\n";
    $headers   .= "Reply-To: {$fullName} <{$emailClean}>\r\n";
    $headers   .= "MIME-Version: 1.0\r\n";

    if (empty($attachedFiles)) {
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$altBound}\"\r\n";
        $message  = "--{$altBound}\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n{$textBody}\r\n\r\n";
        $message .= "--{$altBound}\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n{$htmlBody}\r\n\r\n";
        $message .= "--{$altBound}--";
    } else {
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

        $altPart  = "--{$altBound}\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n{$textBody}\r\n\r\n";
        $altPart .= "--{$altBound}\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n{$htmlBody}\r\n\r\n";
        $altPart .= "--{$altBound}--";

        $message  = "--{$boundary}\r\nContent-Type: multipart/alternative; boundary=\"{$altBound}\"\r\n\r\n";
        $message .= $altPart . "\r\n\r\n";

        foreach ($attachedFiles as $file) {
            $data = file_get_contents($file['path']);
            if ($data === false) continue;
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: {$file['mime']}; name=\"{$file['name']}\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=\"{$file['name']}\"\r\n\r\n";
            $message .= chunk_split(base64_encode($data)) . "\r\n";
        }
        $message .= "--{$boundary}--";
    }

    $sent = mail($to, $subject, $message, $headers);

    if ($sent) {
        echo json_encode(['success' => true, 'message' => 'Inquiry sent successfully.']);
    } else {
        error_log('BAO contact: mail() failed for ' . $emailClean);
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Could not send your message. Please email us at hello@baorganization.com or call (678) 749-0931.']);
    }

} catch (Throwable $e) {
    error_log('BAO contact error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please contact us at hello@baorganization.com.']);
}
