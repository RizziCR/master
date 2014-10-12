<?php

    if (session_id() == "") {
        session_start();
    }
    if (!isset($_SESSION["user"]) || strlen($_SESSION["user"])==0) {
        $template->set('notLoggedIn', true);
    }

    $template->set('forumAddress', $forumAddress);
    $template->set('wikiAddress', $wikiAddress);
    $template->set('etsAddress', $etsAddress);
    $template->set('imgAddress', $imgAddress);
    $template->set('cssAddress', $cssAddress);
    $template->set('blogAddress', $blogAddress);
    $template->set('youtubeAddress', $youtubeAddress);

    $metas = sql_query('SELECT * FROM html_meta WHERE page="'.$_SERVER['PHP_SELF'].'"');
    $meta = sql_fetch_assoc($metas);
    if($meta['title'])        $template->set('pageTitle', $meta['title']);
    if($meta['description'])  $template->set('descriptionContent', $meta['description']);
    if($meta['keywords'])     $template->set('keywordsContent', $meta['keywords']);

    // set third party banner option
#    list($mt, $dummy) = explode(' ',microtime());
#    mt_srand($mt*1000000);
#    if(mt_rand(0,10) <= 7) {                        // etwas mehr als 2/3 Google-Werbung
        $template->set('enable_google', 1);
/*
    }
    else {
        $get_ads_banner = sql_query("SELECT * FROM alliance_ads WHERE credit > 0 AND approved = 1 AND text = 'thirdParty'");
        $num = sql_num_rows($get_ads_banner);
        if($num > 0) {
            $i = rand(1, $num);
            while($i--) {
                $ads_banner = sql_fetch_assoc($get_ads_banner);
            }
            sql_query("UPDATE alliance_ads SET credit = credit - 1 WHERE id=".$ads_banner['id']);
            $template->set('enable_thirdParty', 1);
            $template->set('ads_banner', $ads_banner);
        }
        else
            $template->set('disable_ads', 1);
    }
*/

?>
