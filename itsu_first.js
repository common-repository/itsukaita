(function($){
    $(window).load(function() {
/*
        $('#day_from').attr('value',GetNowDayStr(-30));
        $('#day_to').attr('value',GetNowDayStr(0));
*/        
        $("#day_from").datepicker();
        $("#day_to").datepicker();
        
        if($("input:radio[name='lang']:checked").val()=='ja'){
            FillJapaneseWords();                        
        }else{
            FillEnglishWords();
        }
        
        $('#disp_lang_ja_input').click(function(){
            FillJapaneseWords();            
        });
        $('#disp_lang_ja').click(function(){
            FillJapaneseWords();                        
        });
        $('#disp_lang_en_input').click(function(){
            FillEnglishWords();            
        });
        $('#disp_lang_en').click(function(){
            FillEnglishWords();                        
        });

    });
})(jQuery);
