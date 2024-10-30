<?php

$ItsuConst['slug'] = 'itsukaita';
$ItsuConst['youbi_midashi']['ja'] = '曜日';
$ItsuConst['youbi_midashi']['en'] = 'day';
$ItsuConst['pct_midashi']['ja'] = '比率';
$ItsuConst['pct_midashi']['en'] = '%';
$ItsuConst['post_num']['ja'] = '記事数';
$ItsuConst['post_num']['en'] = 'number of posts';
$ItsuConst['graph_midashi']['ja'] = 'グラフ';
$ItsuConst['graph_midashi']['en'] = '';
$ItsuConst['jikoku_midashi']['ja'] = '時刻';
$ItsuConst['jikoku_midashi']['en'] = 'Hour';
$ItsuConst['kyuujitsu_midashi']['ja'] = '平日/休日';
$ItsuConst['kyuujitsu_midashi']['en'] = 'Is Holiday?';
$ItsuConst['kyuujitsu_midashi_heijitsu']['ja'] = '平日';
$ItsuConst['kyuujitsu_midashi_heijitsu']['en'] = 'weekday';
$ItsuConst['kyuujitsu_midashi_kyuujitsu']['ja'] = '休日';
$ItsuConst['kyuujitsu_midashi_kyuujitsu']['en'] = 'holiday';
$ItsuConst['shoyoujikan_midashi']['ja'] = '所要時間';
$ItsuConst['shoyoujikan_midashi']['en'] = 'spent time ';
$ItsuConst['hun']['ja'] = '分';
$ItsuConst['hun']['en'] = 'min';
$ItsuConst['matrix_message']['ja'] = '比率,記事数';
$ItsuConst['matrix_message']['en'] = 'ratio,number of posts';
$ItsuConst['no_posts']['ja'] = '対象期間に記事がありません。';
$ItsuConst['no_posts']['en'] = 'there is no post.';

$Youbi['ja'] = array('日', '月', '火', '水', '木', '金', '土');
$Youbi['en'] = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

class ItsuPosts {

    public $Id;
    public $Written_DateTime_From;
    public $Written_DateTime_To;
    public $Written_DateTime_To_GMT;
    public $Span;   //分単位の所要時間
    public $Span10;   //10分単位の所要時間 0～10分：10
    public $DayOfTheWeek;    //0 (日曜)から 6 (土曜)
    public $IsKyuujitsu;
    public $Jikoku; //int,0～23
    public $StrNum; //文字数
    public $Publish_DateTime;
    public $Publish_DateTime_GMT;
    public $Title;

    public function __construct(
    $Id
    , $Written_DateTime_From
    , $Written_DateTime_To
    , $Written_DateTime_To_GMT
    , $Span
    , $DayOfTheWeek
    , $IsKyuujitsu
    , $Jikoku
    , $StrNum
    , $Publish_DateTime
    , $Publish_DateTime_GMT
    , $Title
    ) {
        $this->Id = $Id;
        $this->Written_DateTime_From = $Written_DateTime_From;
        $this->Written_DateTime_To = $Written_DateTime_To;
        $this->Written_DateTime_To_GMT = $Written_DateTime_To_GMT;
        $this->Span = $Span;
        $Temp = (((int) ($Span / 10.0)) * 10) + 10;
        if ($Temp >= 130) {
            $Temp = 130;
        }
        $this->Span10 = $Temp;

        $this->DayOfTheWeek = $DayOfTheWeek;
        $this->IsKyuujitsu = $IsKyuujitsu;
        $this->Jikoku = $Jikoku;
        $this->StrNum = $StrNum;
        $this->Publish_DateTime = $Publish_DateTime;
        $this->Publish_DateTime_GMT = $Publish_DateTime_GMT;
        $this->Title = $Title;
    }

}

?>