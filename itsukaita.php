<?php
/*
  Plugin Name: itsukaita
  Plugin URI: http://accountingse.net/2013/02/638/
  Description: 記事をいつ書いたかを分析するプラグインです。This is plug-in which analyzes when posts were written.
  Version: 0.1.2
  Author: kazunii_ac
  Author URI: https://twitter.com/kazunii_ac
  License: GPL2
 */

/*  Copyright 2012 kazunii_ac (email : moskov@mcn.ne.jp)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

include_once('itsu_const.php');
include_once('itsu_functions.php');
include_once('itsu_rethtml.php');

// 管理メニューのアクションフック
add_action('admin_menu', 'admin_menu_example');

// アクションフックのコールバッック関数
function admin_menu_example() {
    // 設定メニュー下にサブメニューを追加:
    add_options_page('itsukaita', 'itsukaita', 'manage_options', __FILE__, 'itsukaita');
}

function itsukaita() {
    // 設定変更画面を表示する
    global $ItsuConst;
    //$ItsuPosts = GetItsuPosts($From, $To, 'Ja');
    ?>
    <div class="wrap">
        <h2 id="disp_mytitle"></h2>
        <span id="disp_kaisetsu"></span>
        <form action="<?php echo home_url() ?>/wp-admin/options-general.php?page=itsukaita/itsukaita.php" method="post">
            <div  style="vertical-align: middle;" onsubmit>
                <span>
                    from:
                    <input type="text" name="day_from" id="day_from" value="<?php
    if (isset($_POST['day_from'])) {
        echo $_POST['day_from'];
    } else {
        echo date('Y/m/d', time() - (60 * 60 * 24 * 30));
    }
    ?>" />
                </span>
                <span style="margin-left:10px;">
                    to:
                    <input type="text" name="day_to" id="day_to" value="<?php
                       if (isset($_POST['day_to'])) {
                           echo $_POST['day_to'];
                       } else {
                           echo date('Y/m/d');
                       }
    ?>" />
                </span>
            </div>
            <div  style="vertical-align: middle; margin:10px;">
                <!-- <input type="radio" name="sentaku" value="hiduke" id="dumm1"><label for="dumm1" id="disp_hiduke"></label> -->
                <input type="radio" name="shorishubetsu" value="youbi" id="dumm2"<?php
                       if (($_POST['shorishubetsu'] === 'youbi') || !isset($_POST['shorishubetsu'])) {
                           echo ' checked';
                       }
    ?>><label for="dumm2" id="disp_youbi"></label>
                <input type="radio" name="shorishubetsu" value="jikoku" id="dumm3"<?php
                   if ($_POST['shorishubetsu'] === 'jikoku') {
                       echo ' checked';
                   }
    ?>><label for="dumm3" id="disp_jikoku"></label>
                <input type="radio" name="shorishubetsu" value="kyuujitsu" id="dumm4"<?php
                   if ($_POST['shorishubetsu'] === 'kyuujitsu') {
                       echo ' checked';
                   }
    ?>><label for="dumm4" id="disp_kyuujitsu"></label>
                <input type="radio" name="shorishubetsu" value="youbijikoku" id="dumm5"<?php
                   if ($_POST['shorishubetsu'] === 'youbijikoku') {
                       echo ' checked';
                   }
    ?>><label for="dumm5" id="disp_youbi_jikoku"></label>
                <input type="radio" name="shorishubetsu" value="kyuujitsujikoku" id="dumm6"<?php
                   if ($_POST['shorishubetsu'] === 'kyuujitsujikoku') {
                       echo ' checked';
                   }
    ?>><label for="dumm6" id="disp_kyuujitsu_jikoku"></label>
                <input type="radio" name="shorishubetsu" value="timerequired" id="dumm10"<?php
                   if ($_POST['shorishubetsu'] === 'timerequired') {
                       echo ' checked';
                   }
    ?>>
                <label for="dumm10" id="disp_time_required"></label>
                <input type="radio" name="shorishubetsu" value="rawtable" id="dumm11"<?php
                   if ($_POST['shorishubetsu'] === 'rawtable') {
                       echo ' checked';
                   }
    ?>><label for="dumm11" id="disp_raw_table"></label>
                <input type="radio" name="shorishubetsu" value="raw" id="dumm7"<?php
                   if ($_POST['shorishubetsu'] === 'raw') {
                       echo ' checked';
                   }
    ?>><label for="dumm7" id="disp_raw"></label>
                <!--
                                <br />
                                <input type="radio" name="sentaku" value="youbitimerequired" id="dumm8"><label for="dumm8" id="disp_youbi_syoyoujikan"></label>
                                <input type="radio" name="sentaku" value="jikokutimerequired" id="dumm9"><label for="dumm9" id="disp_jikoku_syoyoujikan"></label>
                -->
            </div>
            <div  style="vertical-align: middle; margin:10px;">
                <input type="radio" name="lang" value="ja" id="disp_lang_ja_input"<?php
                   if ($_POST['lang'] === 'ja') {
                       echo ' checked';
                   }
    ?>><label for="disp_lang_ja_input" id="disp_lang_ja">日本語</label>
                <input type="radio" name="lang" value="en" id="disp_lang_en_input"<?php
                   if (($_POST['lang'] === 'en') || !isset($_POST['lang'])) {
                       echo ' checked';
                   }
    ?>><label for="disp_lang_en_input" id="disp_lang_en">English</label>
                <br />
                <input type="submit" name="submit" id="itsu_submit" class="button-primary" value="表を作成">
            </div>
        </form>
        <div id="displaydiv">
            <?php
            if (isset($_POST['day_from'])) {
                echo itsu_RetHTML();
            }
            ?>
        </div>
        <div class="itsuauthor">
            Author: 
            <a href="https://twitter.com/kazunii_ac" target="_blank">
                <img src="http://api.twitter.com/1/users/profile_image?screen_name=kazunii_ac&size=mini"/>
                @kazunii_ac
            </a>
        </div>

        <?php
        wp_enqueue_script('itsu_jqui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js');
        wp_enqueue_script('itsu_jq', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js');
        wp_enqueue_script('itsu_func', plugins_url() . '/' . $ItsuConst['slug'] . '/itsu_functions.js');
        wp_enqueue_script('itsu_first', plugins_url() . '/' . $ItsuConst['slug'] . '/itsu_first.js');
        wp_enqueue_style('itsu_jquitheme', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css');
        wp_enqueue_style('itsu_css', plugins_url() . '/' . $ItsuConst['slug'] . '/itsu_style.css');
    }
    ?>