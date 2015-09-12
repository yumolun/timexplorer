var layer = 0;  //0 means day, 1 means month, 2 means year
var year = "";
var month = "";
var day = "";
var account = "";
var resizeTimer = null;
var newuser = "";



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

    //check if this is a new user
    checkNewUser();

    //make the timeline scrollable
    $thumbday.css("visibility", "visible");         
    $thumbday.smoothDivScroll();
    $thumbmonth.smoothDivScroll();
    $thumbyear.smoothDivScroll();
    
    //drag the modal
    var $modalcontent = $(".modal-content");
    var $modaldialog = $(".modal-dialog");
    $modalcontent.bind("mousedown",function(event){
        var left = $(this).offset().left - 10;
        var top = $(this).offset().top - 30;
        var mouse_x = event.pageX;
        var mouse_y = event.pageY;    

        $(document).bind("mousemove",function(ev){
            $(".settings-right").mousemove(function(event){
                event.stopPropagation()
            }); 
            var x = ev.pageX - mouse_x;
            var y = ev.pageY - mouse_y;
            var now_left = (x + left) + "px";
            var now_top = (y + top) + "px";                    
            
            $modaldialog.css({
                "margin-top":now_top,
                "margin-left":now_left
            });
        });
    });
    $(document).bind("mouseup",function(){
        $(this).unbind("mousemove");
    });  
      

    //put the timeline at the bottom of the screen
    $("#containertimeline").animate({
        top:windowheight - offset - 106 + "px"
    }, 800).queue(function(next) {
        $(".loading").fadeOut(500);
        $thumbmonth.hide();
        $thumbmonth.css("visibility", "visible");
        $thumbyear.hide();
        $thumbyear.css("visibility", "visible");
        //timeline controlled by mouse wheel
        changebywheel();

        //timeline controlled by keyboard
        changebykeyboard();
        next();
    });

    //show settings modal
    $(document).on("click","#settings",function(){
        refreshSettingsContent("account_settings");
        $("#settingsmodal").modal('toggle');     
    });


    //show settings content
    $("#account_settings").add($("#change_password")).add($("#change_theme")).click(function(){
        $this = $(this);
        $this.parent().find(".active").removeClass("active");
        $this.addClass("active");
        refreshSettingsContent($this.attr("id"));
    })

    //show photos when we click the date
    $(document).on("click", ".day", function(e) {
        var pagex = e.pageX;
        day = $(this).attr("title");
        $("#photowall").find("div").each(function() {
            $(this).remove();
        });

        //get the account name and number of photos based on the date clicked
        $.ajax({
            url:"php/photowall/getinfo.php?yearmonthday=" + year + "-" + month + "-" + day,
            type:"get",
            async:false,
            success:function(data) {
                result = eval("(" + data + ")");
                account = result.account;
                num = result.number;
            }
        });
       
        $arrowright.fadeOut(10);
        $arrowleft.fadeOut(10);
        if (num != 0) {
            $(".loading").fadeIn();
            $arrowleftforthumbs.fadeIn(100);
            $arrowrightforthumbs.fadeIn(100);
            photowall(pagex, account, year, month, day, num);
        }       
    });

    //show day when we click month
    $(document).on("click", ".month", function() {
        month = $(this).attr("title");
        RefreshThumbday(year + "-" + month,"root");
    });

    //show month when we click year
    $(document).on("click", ".year", function() {
        year = $(this).attr("title");
        RefreshThumbmonth(year + "-" + month,"root");
    });

   
});

//responsive web
$(document).ready(function() {
    $(window).resize(function(e) {
        if (resizeTimer) {
            clearTimeout(resizeTimer);
        }
        resizeTimer = setTimeout(function() {
            var $containertimeline = $("#containertimeline");
            var windowheight = $(window).height();
            var thumbheight = $("#containertimeline").height();
            var offset = thumbheight * 1.05;
            
            //adjust the introduction position
            if(newuser = "yes"){
                var $intro1 = $(".intro1");
                $intro1.css("top",(windowheight - 380) + "px");
            }

            //adjust the timeline position
            $containertimeline.animate({
                top:windowheight - offset + 5 + "px"
            }, 100);

            if($("#photowall div").length > 0){ 
                var $photowall = $("#photowall");
                var $photowallchildren = $("#photowall").children();    
                if (windowheight < 300) {
                    offset = thumbheight * .5;
                }
                var tw = $(window).width();
                var th = $photowall.get(0).offsetHeight * .9;
                var dwr = $photowall.find("div").width();
                var dhr = $photowall.find("div").height();
                var k = 0;

                //get the number of thumbs displayed according to the window height and width
                if (tw > 750) {
                    if (th > 386) {
                        sizetest = 4;
                    } else {
                        sizetest = 2;
                    }
                } else if (tw > 400) {
                    if (th > 300) {
                        sizetest = 2;
                    } else {
                        sizetest = 1;
                    }
                } else {
                    sizetest = 1;
                }

                //width and height between thumbs
                var a = (tw - COL * dwr) / (COL + 1);
                var b = (th - ROW * dhr) / (ROW + 1);
               

                //adjust the photo positions
                for (var j = 0; j < ROW; j++) {
                    for (var i = 0; i < COL; i++, k++) {
                        $.data($photowallchildren.eq(k).get(0), "timexplorer_left", parseInt(a + i * (dwr + a)));
                        if (th < 385 && tw < 400) {
                            $.data($photowallchildren.eq(k).get(0), "timexplorer_top", th * .5);
                        } else {
                            $.data($photowallchildren.eq(k).get(0), "timexplorer_top", parseInt(45 + b + j * (dhr + b)));
                        }
                        if (!clicked) {
                            $photowallchildren.eq(k).css("left", $.data($photowallchildren.eq(k).get(0), "timexplorer_left") + "px").css("top", $.data($photowallchildren.eq(k).get(0), "timexplorer_top") + "px");
                        } else {
                            var ll = (tw - COL * dw) * .5;
                            var tt = (th - ROW * dh) * .7;
                            $photowallchildren.eq(k).css("left", ll + $.data($photowallchildren.eq(k).get(0), "timexplorer_col") * dw + "px").css("top", tt + $.data($photowallchildren.eq(k).get(0), "timexplorer_row") * dh + "px");
                        }
                    }
                }
                var temp = sizetest;
                var test = 0;

                //recalculate the position of photos and display the new photo wall
                if (sizetest != COL) {
                    if (sizetest == 4) {
                        indexthumbs = Math.floor(currentfirstphotoindex / 12);
                        test = integer.test(currentfirstphotoindex / 12);
                    }
                    if (sizetest == 2) {
                        indexthumbs = Math.floor(currentfirstphotoindex / 4);
                        test = integer.test(currentfirstphotoindex / 4);
                    }
                    if (sizetest == 1) {
                        indexthumbs = Math.floor(currentfirstphotoindex / 1);
                        test = integer.test(currentfirstphotoindex / 1);
                    }
                    if (test && indexthumbs != 0) {
                        indexthumbs--;
                    }
                    $photowallchildren.each(function() {
                        $(this).remove();
                    });
                    setTimeout(function() {
                        photowall(windowheight / 2, account, year, month, day, num);
                    }, 200);
                }
            }
        }, 400);
    });
});

//control timeline by mouse wheel
function changebywheel() {
    $(".timeline").bind("mousewheel", function(event, delta) {
        var $timeline = $(this);
        var $thumbday = $timeline.children(":first");
        var $thumbmonth = $timeline.children(":nth-of-type(2)");
        var $thumbyear = $timeline.children(":nth-of-type(3)");
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
    $thumbday.smoothDivScroll("getAjaxContent", "php/timeline/thumbday.php?yearmonth=" + yearmonth + "-" + path, "replace");
    layer--;
}

//refresh the months
function RefreshThumbmonth(yearmonth,path) {
    $thumbmonth = $(".thumbmonth");
    hideThumbsByWheel(".thumbyear");
    $thumbmonth.fadeIn(1e3);
    $thumbmonth.smoothDivScroll("getAjaxContent", "php/timeline/thumbmonth.php?yearmonth=" + yearmonth + "-" + path, "replace");
    layer--;
}

//hide the timeline by mouse wheel
function hideThumbsByWheel(c_remove) {
    $(".timeline").find(c_remove).hide();
}

//check if this is a new user
function checkNewUser(){
    $introduction1 = $("#introduction1");
    $intro1 = $(".intro1");
    $.ajax({
        url:"php/database/requests.php?newuser=?",
        type:"get",
        async:false,
        success:function(data) {
            newuser = data;
        }
    });
    if(newuser == "yes"){
        $intro1.css("top",($(window).height() - 380) + "px");
        $introduction1.modal("show"); 
    }
    $introduction1.on('hidden.bs.modal', function (){
        $.ajax({
            url: "php/database/requests.php?statususer=old",
            async:false,
            success:function(data){
            }
        })
    });
}

//Settings content
function refreshSettingsContent(tab){
    var result = "";   
    var $loading = $(".loading");
    $loading.fadeIn(100);
    $.ajax({
        url: "php/settings/" + tab + ".php", 
        async:false,
        success:
            function(data){
            $(".settings-right").html(data); 
            $loading.fadeOut(100);
        }
    });
    if(tab == "account_settings"){
        $(".form-horizontal").unbind();
    }
    if(tab == "change_password"){
        $(".form-horizontal").submit(function(event){
            var currentpassword = $("#inputCurrentPassword").val();
            var newpassword = $("#inputNewPassword").val();
            var newpasswordagain = $("#inputNewPasswordAgain").val();
            var currentpasswordencrypted = $.md5(currentpassword);
            var newpasswordencrypted = $.md5(newpassword);
            if(newpassword != newpasswordagain){
                $("#hintpasswordagain").fadeIn(200);
            }else if(newpassword == currentpassword){
                $("#hintnewpassword").fadeIn(200);
            }else{
                $.ajax({
                    type: 'POST',
                    url: "php/database/requests.php?noob", 
                    data: {
                        oldpw:currentpasswordencrypted,
                        newpw:newpasswordencrypted
                    },
                    async:false,
                    success:function(data){
                        result = data;
                        if(result == "success"){
                            $("#change-password-success").fadeIn(200);
                            setTimeout(function(){
                                location.reload();
                            },500);
                        }else if(result == "update error"){
                            $("#change-password-failed").fadeIn(200);
                        }else if(result == "incorrect password"){
                            $("#hintpassword").fadeIn(200);
                        }
                    }
                });
            }
            event.preventDefault();
        });
        $(document).on("focus","#inputNewPasswordAgain, #inputNewPassword, #inputCurrentPassword",function(){
            $("#hintpasswordagain").fadeOut(200);
            $("#hintpassword").fadeOut(200);
            $("#hintnewpassword").fadeOut(200);
            $("#change-password-failed").fadeOut(200);
        });
    }
}