<?php
  // *** local values ******************************************************************************
  // e-mail configuration not included; see class.phpmailer.php, class.smtp.php, functions.php
  $etsName      = "test.escape-to-space.de:8080";
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        // SSL connection
      $etsAddress   = "https://$etsName";
    }
    else
    {
      $etsAddress   = "http://$etsName";
    }
  $imgAddress   = $etsAddress . "/pics";
  $cssAddress   = $etsAddress . "/css/new.css";
  $oldcssAddress = $etsAddress . "/css/main.css";
  $supportEmail = "support@escape-to-space.de";
  $supportTextEmail = "support (at) escape-to-space.de";
  $debugEmail    = "a@a.com";
  $securityEmail = "a@a.com";
  $multiEmail    = "multi@escape-to-space.de";
  $etsEmail      = "support@escape-to-space.de";
  $noreplyEmail  = "noreply@ets-game.com";
  $ownerEmail    = "thomas@weichert-web.de";
  $stewardEmail  = "a@a.com";
  $ownerName     = "Thomas Weichert";
  $stewardName   = "Betreibername";
  $forumAddress = "http://forum.escape-to-space.de/index.php";
  $wikiAddress  = "http://wiki.ets-game.com";
  $shopAddress  = "http://";
  $chatAddress  = "http://web853.webbox443.server-home.org/chat";
  $blogAddress  = "http://www.ets-blog.de.vu";
  $youtubeAddress  = "http://www.youtube.com/user/EscapeToSpaceGame";
  $goodbye      = "Beste Gr&uuml;sse";
  $liable       = "Die ETS-Verwaltung";

  define('SESSION_TIMEOUT',3600); //1 stunde
  define('SESSION_TIMEOUT_WARNING',60*50); // 50minuten
  define('CAPTCHA_TIMEOUT',90); //1.5min

  // date and time output in german
  setlocale(LC_TIME, 'de_DE', 'de_DE.iso885915');  // Linux
  // setlocale(LC_TIME, 'German_Germany');  // Windows

  // start of donation finance and view factor for alliance banner
  $donationStart = "2014-01-03 20:00:00";
  $viewFactor    = 15000;

  define('PHPTAL_TEMPLATE_REPOSITORY', '/var/www/ETS10/templates');
  // add ets-path to your include_path in your php.ini, e.g. ":var/www/ETS"

?>
