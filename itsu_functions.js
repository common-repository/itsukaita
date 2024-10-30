function GetNowDayStr(days){
    //days日前の日付文字列を返す。
    //daysがゼロなら本日日付。
    var nowdaytime = new Date();
    var milisec = nowdaytime.getTime();
    milisec = ((60 * 60 * 24 * 1000) * days) + (nowdaytime.getTime());
    var daytime = new Date();
    daytime.setTime(milisec);
    var S =
    daytime.getFullYear() + '/' + int2keta(daytime.getMonth() + 1) + '/' + int2keta(daytime.getDate())
    return(S);
}
function int2keta(i){
    var S = '';
    if(String(i).length==1){
        S = '0' + String(i);
    }else{
        S = String(i);
    }
    return(S);
}
function FillEnglishWords(){
    (function($){
        $('#disp_mytitle').text('itsukaita')
        $('#disp_kaisetsu').text('This is plug-in which analyzes when posts were written.')
        $('#disp_youbi').text('by day of week')
        $('#disp_jikoku').text('by hour')
        $('#disp_kyuujitsu').text('by holiday or not')
        $('#disp_youbi_jikoku').text('by day of week and hour')
        $('#disp_kyuujitsu_jikoku').text('by holiday or not and hour')
//        $('#disp_youbi_syoyoujikan').text('by day of week and time required')
//        $('#disp_jikoku_syoyoujikan').text('by hour and time required')
        $('#disp_time_required').text('by spent time')
        $('#disp_raw_table').text('raw data(table)')
        $('#disp_raw').text('raw data(csv)')
        $('#itsu_submit').attr('value','display table!')
    })(jQuery);
}
function FillJapaneseWords(){
    (function($){
        $('#disp_mytitle').text('itsukaita(いつ書いた?)')
        $('#disp_kaisetsu').text('記事をいつ書いたかを分析するプラグインです。')
        $('#disp_youbi').text('曜日毎')
        $('#disp_jikoku').text('時刻毎')
        $('#disp_kyuujitsu').text('平日休日毎')
        $('#disp_youbi_jikoku').text('曜日・時刻毎')
        $('#disp_kyuujitsu_jikoku').text('平日休日・時刻毎')
//        $('#disp_youbi_syoyoujikan').text('曜日・記事作成にかけた時間毎')
//        $('#disp_jikoku_syoyoujikan').text('時刻・記事作成にかけた時間毎')
        $('#disp_time_required').text('記事作成にかけた時間')
        $('#disp_raw_table').text('生データ（table）')
        $('#disp_raw').text('生データ（csv）')
        $('#itsu_submit').attr('value','表示！')
    })(jQuery);
}
