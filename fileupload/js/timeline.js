var layer = 0;  //0 means day, 1 means month, 2 means year
var year = "";
var month = "";
var day = "";
var account = "";

$(document).ready(function() {
    var $thumbday = $(".thumbday");
    var $thumbmonth = $(".thumbmonth");
    var $thumbyear = $(".thumbyear");
    var $timeline = $(".thumbyear");
    var $arrowleftforthumbs = $(".arrowleftforthumbs");
    var $arrowrightforthumbs = $(".arrowrightforthumbs");
    var $arrowleft = $(".arrowleft");
    var $arrowright = $(".arrowright");
    var $intro1 = $(".intro1");
    var sizetest = 0;
    var yearmonth = "-";
    var windowheight = $(window).height();
    var thumbheight = $(".thumbday img").height();
    var offset = thumbheight * 1.05;
    var currentdate = new Date();
    month = currentdate.getMonth() + 1;
    year = currentdate.getFullYear();

    //choose today
    $changedate = $("#changedate");
    $changedate.click(function(){
        var day = currentdate.getDate();
        $.ajax({
            type:'POST',
            url:"php/choosedate.php?yearmonthday",
            data:{
                yearmonthday:year + "-" + month + "-" + day
            },
            async:false,
            success:function(data) {
                location.reload();
            }
        });
    });

    

    //make the timeline scrollable
    $thumbday.css("visibility", "visible");         
    $thumbday.smoothDivScroll();
    $thumbmonth.smoothDivScroll();
    $thumbyear.smoothDivScroll();
    setTimeout(function(){
        $thumbmonth.hide();
        $thumbmonth.css("visibility", "visible");
        $thumbyear.hide();
        $thumbyear.css("visibility", "visible");

        //timeline controlled by mouse wheel
        changebywheel();

        //timeline controlled by keyboard
        changebykeyboard();
    },100);
    
    //choose a date 
    $(document).on("click", ".day", function(e) {
        day = $(this).attr("title");
        $.ajax({
            type:'POST',
            url:"php/choosedate.php?yearmonthday",
            data:{
                yearmonthday:year + "-" + month + "-" + day
            },
            async:false,
            success:function(data) {
                location.reload();
            }
        });
    });
      
    //show day when we click month
    $(document).on("click", ".month", function() {
        month = $(this).attr("title");
        RefreshThumbday(year + "-" + month);
    });

    //show month when we click year
    $(document).on("click", ".year", function() {
        year = $(this).attr("title");
        RefreshThumbmonth(year + "-" + month);
    });

    /*//set and reset album cover
    $(document).on("click", ".setcover, .resetcover", function() {
        var filenameurl = escape($(this).attr("id"));
        $.ajax({
            type:'POST',
            url:"php/setcover.php",
            data:{
                filename:filenameurl
            },
            async:false,
            success:function(data){
                location.reload();
            }
        });
    });*/
});

//control timeline by mouse wheel
function changebywheel() {
    $(".timeline").bind("mousewheel", function(event, delta) {
        var $timeline = $(this);
        var $thumbday = $timeline.children(":first");
        var $thumbmonth = $timeline.children(":nth-of-type(2)");
        var $thumbyear = $timeline.children(":nth-of-type(3)");
        event.preventDefault();
        event.stopPropagation();    
        if (delta > 0) {
            if (layer == 0) {
                hideThumbsByWheel(".thumbday");
                $thumbmonth.fadeIn(1e3);
            }
            if (layer == 1) {
                hideThumbsByWheel(".thumbmonth");
                $thumbyear.fadeIn(1e3);
            }
            if (layer < 2) {
                layer++;
            }
        }
    });
}

//control timeline by keyboard
function changebykeyboard(){
    $(document).on("keydown", "body", function(e) {
        if(e.which == 38){
            var $timeline = $(".timeline");
            var $thumbday = $timeline.children(":first");
            var $thumbmonth = $timeline.children(":nth-of-type(2)");
            var $thumbyear = $timeline.children(":nth-of-type(3)");
            if (layer == 0) {
                hideThumbsByWheel(".thumbday");
                $thumbmonth.fadeIn(1e3);
            }
            if (layer == 1) {
                hideThumbsByWheel(".thumbmonth");
                $thumbyear.fadeIn(1e3);
            }
            if (layer < 2) {
                layer++;
            }

        }
    });
}

//refresh the days 
function RefreshThumbday(yearmonth,path) {
    $thumbday = $(".thumbday");
    hideThumbsByWheel(".thumbmonth");
    $thumbday.fadeIn(1e3);
    $thumbday.smoothDivScroll("getAjaxContent", "../php/timeline/thumbday.php?yearmonth=" + yearmonth + "-" + path, "replace");
    layer--;
}

//refresh the months
function RefreshThumbmonth(yearmonth,path) {
    $thumbmonth = $(".thumbmonth");
    hideThumbsByWheel(".thumbyear");
    $thumbmonth.fadeIn(1e3);
    $thumbmonth.smoothDivScroll("getAjaxContent", "../php/timeline/thumbmonth.php?yearmonth=" + yearmonth + "-" + path, "replace");
    layer--;
}

//refresh the months without displaying it
function RefreshThumbmonthHidden(yearmonth,path) {
    $thumbmonth = $(".thumbmonth");
    hideThumbsByWheel(".thumbyear");
    $thumbmonth.smoothDivScroll("getAjaxContent", "../php/timeline/thumbmonth.php?yearmonth=" + yearmonth + "-" + path, "replace");
    layer--;
}

//hide the timeline by mouse wheel
function hideThumbsByWheel(c_remove) {
    $(".timeline").find(c_remove).hide();
}

