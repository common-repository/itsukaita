<?php

function GetItsuPosts($From, $To, $Lang) {
/*
    $path = '../../../';    //WordPressのルートパス
    include_once($path . 'wp-config.php');
    include_once($path . 'wp-load.php');
*/
    global $wpdb;
    $AllPublishPosts = $wpdb->get_results(
            "SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' order by id asc"
            , OBJECT
    );

    $Posts;
    $Holidays;
    $SimpleHolidaysStr = array();
    if ($Lang === 'ja') {
        $Holidays = GetHolidaysJa(date('Y-m-d', time() - (60 * 60 * 24 * 365 * 2)),date('Y-m-d', time()));
        foreach ($Holidays as $SingleHoliday) {
            $SimpleHolidaysStr[] = $SingleHoliday['DayStr'];
        }
    }

    foreach ($AllPublishPosts as $SinglePost) {
//日付系を取得
        $SQL = GetDateTimes($SinglePost->ID);
        $Obj_DateTime = $wpdb->get_row($SQL, OBJECT);
        $ModFromSec = strtotime($Obj_DateTime->min_post_modified);
        $ModToSec = strtotime($Obj_DateTime->max_post_modified);
        $ModTo = new DateTime($Obj_DateTime->max_post_modified);

        $IsHoliday = (
                (
                in_array(date('Y-m-d', $ModToSec), $SimpleHolidaysStr)
                or date('w', $ModToSec) === '0'
                or date('w', $ModToSec) === '6'
                ) ? 1 : 0);

        if (
                strtotime($From) < $ModToSec
                and $ModToSec < strtotime($To)
        ) {
            $Posts[] = new ItsuPosts(
                            $SinglePost->ID
                            , $Obj_DateTime->min_post_modified
                            , $Obj_DateTime->max_post_modified
                            , $Obj_DateTime->max_post_modified_gmt
                            , (($ModToSec - $ModFromSec) / 60)
                            , (int) date('w', $ModToSec)
                            , $IsHoliday
                            , (int) ($ModTo->format('H'))
                            , mb_strlen(strip_tags($SinglePost->post_content))
                            , $Obj_DateTime->max_post_date
                            , $Obj_DateTime->max_post_date_gmt
                            , $SinglePost->post_title
            );
        }
    }
    return($Posts);
}

//////////
function GetDateTimes($ID) {
    global $wpdb;
    $SQL = <<<EOT
    select
        min(post_modified) as min_post_modified
        ,max(post_modified) as max_post_modified
        ,max(post_modified_gmt) as max_post_modified_gmt
        ,max(post_date) as max_post_date
        ,max(post_date_gmt) as max_post_date_gmt
    from
        {$wpdb->posts}
EOT;
    $SQL .= GetWhereOfPostID($ID);
    return($SQL);
}

function GetHolidaysJa($FromStr,$ToStr) {
    $holidays_url = sprintf(
            'http://www.google.com/calendar/feeds/%s/public/full-noattendees?start-min=%s&start-max=%s&max-results=%d&alt=json', 'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com', // 'japanese@holiday.calendar.google.com' ,
            $FromStr, // 取得開始日
            $ToStr, // 取得終了日
            50            // 最大取得数
    );
    $holidays;
    if ($results = file_get_contents($holidays_url)) {
        $results = json_decode($results, true);
        $holidays = array();
        foreach ($results['feed']['entry'] as $val) {
            $date = $val['gd$when'][0]['startTime'];
            $S = explode('/', $val['title']['$t']);
            $title = trim($S[0]);
            $holidays[] = array('DayStr' => $date, 'DateTime' => new DateTime($date), 'Name' => $title);
        }
    }
    return($holidays);
}

function hm($S) {
    return(
            mysql_real_escape_string(
                    htmlspecialchars($S)
            )
            );
}

function GetWhereOfPostID($ID) {
    $PostNameSearchStr = $ID . '-revision';
    $Where = <<<EOT
    where
        id = {$ID}
        or post_parent = {$ID}
EOT;
    return($Where);
}
///////////

?>