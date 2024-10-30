<?php

function itsu_RetHTML() {
    include_once('itsu_const.php');
    include_once('itsu_functions.php');
    global $ItsuConst;

    $From = date('Y-m-d', strtotime(hm($_POST['day_from'])));
    $To = date('Y-m-d', strtotime(hm($_POST['day_to'])));
    $Lang = hm($_POST['lang']);

    $ItsuPosts = GetItsuPosts($From, $To, $Lang);

    if (count($ItsuPosts) === 0) {
        return($ItsuConst['no_posts'][$Lang]);
    } else {
        if ($_POST['shorishubetsu'] === 'youbi') {
            return(YoubiHtml($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'jikoku') {
            return( JikokuHtml($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'kyuujitsu') {
            return( KyuujitsuHtml($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'youbijikoku') {
            return( YoubiJikokuHtml($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'kyuujitsujikoku') {
            return( KyuujitsuJikokuHtml($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'timerequired') {
            return( TimeRequiredHtml($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'rawtable') {
            return( RawTable($ItsuPosts, $Lang));
        } elseif ($_POST['shorishubetsu'] === 'raw') {
            return( RawTsv($ItsuPosts, $Lang));
        } else {
            //DoNothing
        }
    }
}

function RawTable($ItsuPosts, $Lang) {
    $HTML = '';
    if ($Lang === 'en') {
        $HTML .= '<table border="1">';
        $HTML .= '<tr>';
        $HTML .= '<th>PostId</th>';
        $HTML .= "<th>PostTitle</th>";
        $HTML .= "<th>Written DateTime From</th>";
        $HTML .= "<th>Written DateTime To</th>";
        $HTML .= "<th>Written DateTime To GMT</th>";
        $HTML .= "<th>Min</th>";   //分単位の所要時間
        $HTML .= "<th>Min10</th>";   //10分単位の所要時間 0～10分：10
        $HTML .= "<th>Day Of The Week<br />(0:sun 1:mon ... 6:sat)</th>";    //0 (日曜)から 6 (土曜)
        $HTML .= "<th>Is Holiday?<br />(Holiday:1)</th>";
        $HTML .= "<th>Hour</th>"; //int,0～23
        $HTML .= "<th>Number Of Strings<br />(after remove tags)</th>"; //文字数
        $HTML .= "<th>Publish DateTime</th>";
        $HTML .= "<th>Publish DateTime GMT</th>";
        $HTML .= "</tr>";
    } else {
        $HTML .= '<table border="1">';
        $HTML .= '<tr>';
        $HTML .= '<th>記事ID</th>';
        $HTML .= '<th>記事のタイトル</th>';
        $HTML .= '<th>いつから書いたか</th>';
        $HTML .= '<th>いつまで書いたか</th>';
        $HTML .= '<th>いつまで書いたか(GMT)</th>';
        $HTML .= '<th>何分間書いたか</th>';   //分単位の所要時間
        $HTML .= '<th>何分間書いたか<br />(10分単位,130分以上は一律130)</th>';   //10分単位の所要時間 0～10分：10
        $HTML .= '<th>曜日<br />(0:日 1:月 ... 6:土)</th>';    //0 (日曜)から 6 (土曜)
        $HTML .= '<th>休日か否か<br />(平日:0 休日:1)<br />（日本の祝日を考慮しています）</th>';
        $HTML .= '<th>時刻(0～23)</th>'; //int,0～23
        $HTML .= '<th>記事の文字数<br />（タグ除去後）</th>'; //文字数
        $HTML .= '<th>公開した時刻</th>';
        $HTML .= '<th>公開した時刻（GMT）</th>';
        $HTML .= '</tr>';
    }
    foreach ($ItsuPosts as $SinglePost) {
        $HTML .= '<tr>';
        $HTML .= '<td>' . $SinglePost->Id . '</td>';
        $HTML .= '<td style="text-align:left;">' . ((string) $SinglePost->Title) . '</td>';
        $HTML .= '<td>' . ((string) $SinglePost->Written_DateTime_From) . '</td>';
        $HTML .= '<td>' . ((string) $SinglePost->Written_DateTime_To) . '</td>';
        $HTML .= '<td>' . ((string) $SinglePost->Written_DateTime_To_GMT) . '</td>';
        $HTML .= '<td>' . ((int) $SinglePost->Span) . '</td>';   //分単位の所要時間
        $HTML .= '<td>' . ((string) $SinglePost->Span10) . '</td>';   //10分単位の所要時間 0～10分：10
        $HTML .= '<td>' . ((string) $SinglePost->DayOfTheWeek) . '</td>';    //0 (日曜)から 6 (土曜)
        $HTML .= '<td>' . ((string) $SinglePost->IsKyuujitsu) . '</td>';
        $HTML .= '<td>' . ((string) $SinglePost->Jikoku) . '</td>'; //int,0～23
        $HTML .= '<td>' . ((string) $SinglePost->StrNum) . '</td>'; //文字数
        $HTML .= '<td>' . ((string) $SinglePost->Publish_DateTime) . '</td>';
        $HTML .= '<td>' . ((string) $SinglePost->Publish_DateTime_GMT) . '</td>';
        $HTML .= '</tr>';
    }
    $HTML .= '</table>';
    return($HTML);
}

function RawTsv($ItsuPosts, $Lang) {
    $Tsv = '';
    if ($Lang === 'en') {
        $Tsv .= '<p>Please copy & paste!</p>';
        $Tsv .= 'PostId';
        $Tsv .= "," . '"PostTitle"';
        $Tsv .= "," . 'Written_DateTime_From';
        $Tsv .= "," . 'Written_DateTime_To';
        $Tsv .= "," . 'Written_DateTime_To_GMT';
        $Tsv .= "," . 'Span';   //分単位の所要時間
        $Tsv .= "," . 'Span10';   //10分単位の所要時間 0～10分：10
        $Tsv .= "," . 'DayOfTheWeek';    //0 (日曜)から 6 (土曜)
        $Tsv .= "," . 'IsHoliday?';
        $Tsv .= "," . 'Hour'; //int,0～23
        $Tsv .= "," . 'Number_Of_Strings'; //文字数
        $Tsv .= "," . 'Publish_DateTime';
        $Tsv .= "," . 'Publish_DateTime_GMT';
        $Tsv .= "<br />";
    } else {
        $Tsv .= '<p>カンマ区切りのCSVデータです。Excelなどにコピペして下さい！</p>';
        $Tsv .= '記事ID';
        $Tsv .= "," . '"記事のタイトル"';
        $Tsv .= "," . 'いつから書いたか';
        $Tsv .= "," . 'いつまで書いたか';
        $Tsv .= "," . 'いつまで書いたか（グリニッジ標準時）';
        $Tsv .= "," . '何分間書いたか';   //分単位の所要時間
        $Tsv .= "," . '何分間書いたか(10分単位・130分以上は一律130)';   //10分単位の所要時間 0～10分：10
        $Tsv .= "," . '曜日(0:日曜 1:月曜 ・・・ 6:土曜)';    //0 (日曜)から 6 (土曜)
        $Tsv .= "," . '休日か否か（日本の祝日を考慮しています）';
        $Tsv .= "," . '時刻(0～23)'; //int,0～23
        $Tsv .= "," . '記事の文字数（タグ除去後）'; //文字数
        $Tsv .= "," . '公開した時刻';
        $Tsv .= "," . '公開した時刻（グリニッジ標準時）';
        $Tsv .= "<br />";
    }
    foreach ($ItsuPosts as $SinglePost) {
        $Tsv .= $SinglePost->Id;
        $Tsv .= ',"' . ((string) $SinglePost->Title) . '"';
        $Tsv .= "," . ((string) $SinglePost->Written_DateTime_From);
        $Tsv .= "," . ((string) $SinglePost->Written_DateTime_To);
        $Tsv .= "," . ((string) $SinglePost->Written_DateTime_To_GMT);
        $Tsv .= "," . ((string) $SinglePost->Span);   //分単位の所要時間
        $Tsv .= "," . ((string) $SinglePost->Span10);   //10分単位の所要時間 0～10分：10
        $Tsv .= "," . ((string) $SinglePost->DayOfTheWeek);    //0 (日曜)から 6 (土曜)
        $Tsv .= "," . ((string) $SinglePost->IsKyuujitsu);
        $Tsv .= "," . ((string) $SinglePost->Jikoku); //int,0～23
        $Tsv .= "," . ((string) $SinglePost->StrNum); //文字数
        $Tsv .= "," . ((string) $SinglePost->Publish_DateTime);
        $Tsv .= "," . ((string) $SinglePost->Publish_DateTime_GMT);
        $Tsv .= "<br />";
    }
    return($Tsv);
}

function TimeRequiredHtml($ItsuPosts, $Lang) {
    global $Youbi;
    global $ItsuConst;
    $AllPostNum = count($ItsuPosts);
    $PostNum = array();
    $PostPctStr = array();
    $BarPct = array();
    for ($i = 10; $i <= 130; $i+=10) {
        $PostNum[$i] = 0;
    }   //$PostNum['所要時間']['曜日']

    foreach ($ItsuPosts as $SinglePost) {
        $PostNum[$SinglePost->Span10]++;
    }
    $MaxPostNum = max($PostNum);
    foreach ($PostNum as $JikokuRowNum => $ThisPostNum) {
        $PostPctStr[$JikokuRowNum] = number_format(($ThisPostNum / $AllPostNum) * 100, 2, '.', ',') . '%';
        $BarPct[$JikokuRowNum] = ($ThisPostNum / $MaxPostNum) * 100;
    }

    $HTML = <<<EOT
<table border="1">
    <tr>
        <td class="youbitd">
            {$ItsuConst['shoyoujikan_midashi'][$Lang]}
        </td>
        <td class="youbitd">
            {$ItsuConst['post_num'][$Lang]}
        </td>
EOT;
    for ($Row = 10; $Row <= 130; $Row+=10) {
        $HTML .= '<tr>';
        $HTML .= '<td class="youbitd">';
        if ($Row < 130) {
            $HTML .= ' < ' . $Row . $ItsuConst['hun'][$Lang];  //時刻
        } else {
            $HTML .= ' > ' . $Row . $ItsuConst['hun'][$Lang];  //時刻
        }
        $HTML .= '</td>';

        $DivBGColor = ColorScaleBCGYR($BarPct[$Row] / 100);

        $HTML .= <<<EOT
<td>
    <div class="graph">
        <span class="bar" style="width: {$BarPct[$Row]}%;background: {$DivBGColor} !important;">{$PostPctStr[$Row]},{$PostNum[$Row]}</span>
    </div>
</td>
EOT;
        $HTML .= '</tr>';
    }

    $HTML .= '</table>';
    return($HTML);
}

function KyuujitsuJikokuHtml($ItsuPosts, $Lang) {
    global $Youbi;
    global $ItsuConst;
    $AllPostNum = count($ItsuPosts);
    $TempArr = array(0, 0);
    $PostNum = array();
    $PostPctStr = array();
    $BarPct = array();
    for ($i = 0; $i <= 23; $i++) {
        $PostNum[] = $TempArr;
    }   //$PostNum['時刻']['休日/平日']

    foreach ($ItsuPosts as $SinglePost) {
        $PostNum[$SinglePost->Jikoku][$SinglePost->IsKyuujitsu]++;
    }
    $TempMaxPostNum = array();
    foreach ($PostNum as $JikokuRowNum => $Weeks) {
        $TempMaxPostNum[] = max($Weeks);
    }
    $MaxPostNum = max($TempMaxPostNum);
    foreach ($PostNum as $JikokuRowNum => $Weeks) {
        foreach ($Weeks as $YoubiNum => $ThisPostNum) {
            $PostPctStr[$JikokuRowNum][$YoubiNum] = number_format(($ThisPostNum / $AllPostNum) * 100, 2, '.', ',') . '%';
            $BarPct[$JikokuRowNum][$YoubiNum] = ($ThisPostNum / $MaxPostNum) * 100;
        }
    }

    $HTML = <<<EOT
{$ItsuConst['matrix_message'][$Lang]}
<table border="1">
    <tr>
        <td class="youbitd">
            {$ItsuConst['jikoku_midashi'][$Lang]}
        </td>
EOT;
    for ($Col = 0; $Col <= 1; $Col++) {
        $HTML .= '<td class="youbitd">';
        $HTML .= ($Col === 0) ? $ItsuConst['kyuujitsu_midashi_heijitsu'][$Lang] : $ItsuConst['kyuujitsu_midashi_kyuujitsu'][$Lang];
        $HTML .= '</td>';
    }

    for ($Row = 0; $Row <= 23; $Row++) {
        $HTML .= '<tr>';
        $HTML .= '<td class="youbitd">';
        $HTML .= $Row;  //時刻
        $HTML .= '</td>';
        for ($Col = 0; $Col <= 1; $Col++) {
            $DivBGColor = ColorScaleBCGYR($BarPct[$Row][$Col] / 100);
            $HTML .= <<<EOT
<td>
    <div class="graph">
        <span class="bar" style="width: {$BarPct[$Row][$Col]}%;background: {$DivBGColor} !important;">{$PostPctStr[$Row][$Col]},{$PostNum[$Row][$Col]}</span>
    </div>
</td>
EOT;
        }
        $HTML .= '</tr>';
    }

    $HTML .= '</table>';
    return($HTML);
}

function YoubiJikokuHtml($ItsuPosts, $Lang) {
    global $Youbi;
    global $ItsuConst;
    $AllPostNum = count($ItsuPosts);
    $TempArr = array(0, 0, 0, 0, 0, 0, 0);
    $PostNum = array();
    $PostPctStr = array();
    $BarPct = array();
    for ($i = 0; $i <= 23; $i++) {
        $PostNum[] = $TempArr;
    }   //$PostNum['時刻']['曜日']

    foreach ($ItsuPosts as $SinglePost) {
        $PostNum[$SinglePost->Jikoku][$SinglePost->DayOfTheWeek]++;
    }
    $TempMaxPostNum = array();
    foreach ($PostNum as $JikokuRowNum => $Weeks) {
        $TempMaxPostNum[] = max($Weeks);
    }
    $MaxPostNum = max($TempMaxPostNum);
    foreach ($PostNum as $JikokuRowNum => $Weeks) {
        foreach ($Weeks as $YoubiNum => $ThisPostNum) {
            $PostPctStr[$JikokuRowNum][$YoubiNum] = number_format(($ThisPostNum / $AllPostNum) * 100, 2, '.', ',') . '%';
            $BarPct[$JikokuRowNum][$YoubiNum] = ($ThisPostNum / $MaxPostNum) * 100;
        }
    }

    $HTML = <<<EOT
{$ItsuConst['matrix_message'][$Lang]}
<table border="1">
    <tr>
        <td class="youbitd">
            {$ItsuConst['jikoku_midashi'][$Lang]}
        </td>
EOT;
    for ($Col = 0; $Col <= 6; $Col++) {
        $HTML .= '<td class="youbitd">';
        $HTML .= $Youbi[$Lang][$Col];
        $HTML .= '</td>';
    }

    for ($Row = 0; $Row <= 23; $Row++) {
        $HTML .= '<tr>';
        $HTML .= '<td class="youbitd">';
        $HTML .= $Row;  //時刻
        $HTML .= '</td>';
        for ($Col = 0; $Col <= 6; $Col++) {
            $DivBGColor = ColorScaleBCGYR($BarPct[$Row][$Col] / 100);
            $HTML .= <<<EOT
<td>
    <div class="graph">
        <span class="bar" style="width: {$BarPct[$Row][$Col]}%;background: {$DivBGColor} !important;">{$PostPctStr[$Row][$Col]},{$PostNum[$Row][$Col]}</span>
    </div>
</td>
EOT;
        }
        $HTML .= '</tr>';
    }

    $HTML .= '</table>';
    return($HTML);
}

function KyuujitsuHtml($ItsuPosts, $Lang) {
    global $ItsuConst;
    $AllPostNum = count($ItsuPosts);
    $PostNumByHeiKyu = array(0, 0);

    foreach ($ItsuPosts as $SinglePost) {
        $PostNumByHeiKyu[$SinglePost->IsKyuujitsu]++;
    }
    foreach ($PostNumByHeiKyu as $key => $val) {
        $PostPctStrByHeiKyu[$key] = number_format(($val / $AllPostNum) * 100, 2, '.', ',') . '%';
    }
    foreach ($PostNumByHeiKyu as $key => $val) {
        $BarPct[$key] = $val / max($PostNumByHeiKyu) * 100;
    }

    $DivBGColor[0] = ColorScaleBCGYR($BarPct[0] / 100);
    $DivBGColor[1] = ColorScaleBCGYR($BarPct[1] / 100);
    $HTML = <<<EOT
   <table border="1">
       <tr>
            <th>
                {$ItsuConst['kyuujitsu_midashi'][$Lang]}
            </th>
            <th>
                {$ItsuConst['post_num'][$Lang]}
            </th>
            <th>
                {$ItsuConst['pct_midashi'][$Lang]}
            </th>
       </tr>
       <tr>
            <td class="youbitd">
                {$ItsuConst['kyuujitsu_midashi_heijitsu'][$Lang]}
            </td>
            <td>
                {$PostNumByHeiKyu[0]}
            </td>
            <td>
                <div class="graph">
                    <span class="bar" style="width: {$BarPct[0]}%;background: {$DivBGColor[0]} !important;">{$PostPctStrByHeiKyu[0]}</span>
                </div>
            </td>
       </tr>
       <tr>
            <td class="youbitd">
                {$ItsuConst['kyuujitsu_midashi_kyuujitsu'][$Lang]}
            </td>
            <td>
                {$PostNumByHeiKyu[1]}
            </td>
            <td>
                <div class="graph">
                    <span class="bar" style="width: {$BarPct[1]}%;background: {$DivBGColor[1]} !important;">{$PostPctStrByHeiKyu[1]}</span>
                </div>
            </td>
       </tr>
    </table>
EOT;

    return($HTML);
}

function JikokuHtml($ItsuPosts, $Lang) {
    global $ItsuConst;
    $AllPostNum = count($ItsuPosts);
    $PostNumByHour = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    foreach ($ItsuPosts as $SinglePost) {
        $PostNumByHour[$SinglePost->Jikoku]++;
    }
    foreach ($PostNumByHour as $key => $val) {
        $PostPctStrByHour[$key] = number_format(($val / $AllPostNum) * 100, 2, '.', ',') . '%';
    }
    foreach ($PostNumByHour as $key => $val) {
        $BarPct[$key] = $val / max($PostNumByHour) * 100;
    }

    $HTML = <<<EOT
   <table border="1">
       <tr>
            <th>
                {$ItsuConst['jikoku_midashi'][$Lang]}
            </th>
            <th>
                {$ItsuConst['post_num'][$Lang]}
            </th>
            <th>
                {$ItsuConst['pct_midashi'][$Lang]}
            </th>
       </tr>
EOT;
    for ($i = 0; $i <= 23; $i++) {
        $DivBGColor = ColorScaleBCGYR($BarPct[$i] / 100);
        $HTML .= <<<EOT
       <tr>
            <td class="youbitd">
                {$i}
            </td>
            <td>
                {$PostNumByHour[$i]}
            </td>
            <td>
                <div class="graph">
                    <span class="bar" style="width: {$BarPct[$i]}%;background: {$DivBGColor} !important;">{$PostPctStrByHour[$i]}</span>
                </div>
            </td>
       </tr>
EOT;
    }
    $HTML .= '</table>';
    return($HTML);
}

function YoubiHtml($ItsuPosts, $Lang) {
    global $Youbi;
    global $ItsuConst;
    $PostNumByYoubi = array(0, 0, 0, 0, 0, 0, 0);
    $AllPostNum = count($ItsuPosts);
    foreach ($ItsuPosts as $SinglePost) {
        $PostNumByYoubi[$SinglePost->DayOfTheWeek]++;
    }
    $PostPctByYoubi = array(
        number_format(($PostNumByYoubi[0] / $AllPostNum) * 100, 2, '.', ',') . '%'
        , number_format(($PostNumByYoubi[1] / $AllPostNum) * 100, 2, '.', ',') . '%'
        , number_format(($PostNumByYoubi[2] / $AllPostNum) * 100, 2, '.', ',') . '%'
        , number_format(($PostNumByYoubi[3] / $AllPostNum) * 100, 2, '.', ',') . '%'
        , number_format(($PostNumByYoubi[4] / $AllPostNum) * 100, 2, '.', ',') . '%'
        , number_format(($PostNumByYoubi[5] / $AllPostNum) * 100, 2, '.', ',') . '%'
        , number_format(($PostNumByYoubi[6] / $AllPostNum) * 100, 2, '.', ',') . '%'
    );
    $BarPct = array(
        $PostNumByYoubi[0] / max($PostNumByYoubi) * 100
        , $PostNumByYoubi[1] / max($PostNumByYoubi) * 100
        , $PostNumByYoubi[2] / max($PostNumByYoubi) * 100
        , $PostNumByYoubi[3] / max($PostNumByYoubi) * 100
        , $PostNumByYoubi[4] / max($PostNumByYoubi) * 100
        , $PostNumByYoubi[5] / max($PostNumByYoubi) * 100
        , $PostNumByYoubi[6] / max($PostNumByYoubi) * 100
    );

    $HTML = <<<EOT
   <table border="1">
       <tr>
            <th>
                {$ItsuConst['youbi_midashi'][$Lang]}
            </th>
            <th>
                {$ItsuConst['post_num'][$Lang]}
            </th>
            <th>
                {$ItsuConst['pct_midashi'][$Lang]}
            </th>
       </tr>
EOT;
    for ($i = 0; $i <= 6; $i++) {
        $DivBGColor = ColorScaleBCGYR($BarPct[$i] / 100);
        $HTML .= <<<EOT
          <tr>
            <td class="youbitd">
                {$Youbi[$Lang][$i]}
            </td>
            <td>
                {$PostNumByYoubi[$i]}
            </td>
            <td>
                <div class="graph">
                    <span class="bar" style="width: {$BarPct[$i]}%;background: {$DivBGColor} !important;">{$PostPctByYoubi[$i]}</span>
                </div>
            </td>
       </tr>
EOT;
    }
    $HTML .= '</table>';
    return($HTML);
}

function ColorScaleBCGYR($in_value) {
    // 0.0～1.0 の範囲の値をサーモグラフィみたいな色にする
    // 0.0                    1.0
    // 青    水    緑    黄    赤
    // 最小値以下 = 青
    // 最大値以上 = 赤
    $a = 255;    // alpha値
    $r;
    $g;
    $b;    // RGB値
    $value = $in_value;
    $tmp_val = cos(4 * M_PI * $value);
    $col_val = (int) ( ((0 - $tmp_val) / 2 + 0.5 ) * 255 );
    if ($value >= ( 4.0 / 4.0 )) {
        $r = 255;
        $g = 0;
        $b = 0;
    }   // 赤
    else if ($value >= ( 3.0 / 4.0 )) {
        $r = 255;
        $g = $col_val;
        $b = 0;
    }   // 黄～赤
    else if ($value >= ( 2.0 / 4.0 )) {
        $r = $col_val;
        $g = 255;
        $b = 0;
    }   // 緑～黄
    else if ($value >= ( 1.0 / 4.0 )) {
        $r = 0;
        $g = 255;
        $b = $col_val;
    }   // 水～緑
    else if ($value >= ( 0.0 / 4.0 )) {
        $r = 0;
        $g = $col_val;
        $b = 255;
    }   // 青～水
    else {
        $r = 0;
        $g = 0;
        $b = 255;
    }   // 青
    $ColorStr = '#';
    $ColorStr .= Ret2KetaStr(dechex($r));
    $ColorStr .= Ret2KetaStr(dechex($g));
    $ColorStr .= Ret2KetaStr(dechex($b));
    return $ColorStr;
}

function Ret2KetaStr($S) {
    if (strlen($S) == 1) {
        return('0' . $S);
    } else {
        return($S);
    }
}

?>