var ROW = 0;    //number of rows of photo wall

var COL = 0;    //number of columns of photo wall

var W = 0;

var H = 0;

var BW = 0;

var BH = 0;

var dw = 0;

var dh = 0;

var clicked = false;    //false means show thumbs, true means show photo

var indexthumbs = 0; //the page index of photos

var currentfirstphotoindex = 0; //the fist photo index in one page

var integer = /^[0-9]*[1-9][0-9]*$/;

function photowall(pagex, account, year, month, day, num) {
    var loaded = 0;
    var photolisturl = new Array();
    photolisturl = [];
    var i = 1;

    //get the photo list
    $.ajax({
            url:"php/photowall/getphotolist.php?yearmonthday=" + year + "-" + month + "-" + day,
            type:"get",
            async:false,
            success:function(data) {
                result = eval("(" + data + ")");
                for(var cpt = 0; cpt < result.length; cpt++){
                    photolisturl[cpt] = escape(result[cpt].file);
                }
            }
    });

    //load and show the first page of thumbs
    for (i = 1; i <= num; i++) {
        var oImg = new Image();
        oImg.onload = function() {
            if (++loaded == num) {
                generatephotowall(pagex, account, year, month, day, num, photolisturl);
            }
        };
        oImg.src = "accounts/" + account + "/" + year + "/" + month + "/" + day + "/thumbs/" + photolisturl[i - 1];
    }
}

function generatephotowall(pagex, account, year, month, day, num, photolisturl) {
    var $loading = $(".loading");
    var $photowall = $("#photowall");
    var currentphotoindex = -1;     //current photo displayed
    var $arrowleftforthumbs = $(".arrowleftforthumbs");
    var $arrowrightforthumbs = $(".arrowrightforthumbs");
    var $arrowleft = $(".arrowleft");
    var $arrowright = $(".arrowright");
    var currentnum = 0;
    var tw = $(window).width();
    var th = $photowall.get(0).offsetHeight * .9;
    $arrowleft.hide();
    $arrowright.hide();
    $arrowleftforthumbs.show();
    $arrowrightforthumbs.show();
    dw = 180;
    dh = 120;

    //define the number of thumbs displayed according to the width and heigt of window
    if (tw > 750) {
        if (th > 386) {
            COL = 4;
            ROW = 3;
        } else {
            ROW = 2;
            COL = 2;
        }
    } else if (tw > 400) {
        if (th > 300) {
            ROW = 2;
            COL = 2;
        } else {
            ROW = 1;
            COL = 1;
        }
    } else {
        ROW = 1;
        COL = 1;
    }
    if (th < 335) {
        ROW = 1;
        COL = 1;
    }

    var lastindexthumbs = Math.floor(num / (ROW * COL));
    var rest = num - lastindexthumbs * ROW * COL;

    //width and height between thumbs
    var a = (tw - COL * dw) / (COL + 1);
    var b = (th - ROW * dh) / (ROW + 1);
    var k = 1;
    clicked = false;

    //create the first page of thumbs
    for (var j = 0; j < ROW; j++) {
        for (var i = 0; i < COL; i++, k++) {
            var oDiv = document.createElement("div");
            var $oDiv = $(oDiv);
            var indexphoto = k + indexthumbs * ROW * COL;
            if (indexphoto > ROW * COL) {
                indexphoto = indexphoto - ROW * COL * indexthumbs;
            }
            $.data(oDiv, "index", indexphoto);
            $.data(oDiv, "timexplorer_left", parseInt(a + i * (dw + a)));
            if (th < 385 && tw < 400) {
                $.data(oDiv, "timexplorer_top", th * .5);
            } else {
                $.data(oDiv, "timexplorer_top", parseInt(45 + b + j * (dh + b)));
            }
            $.data(oDiv, "timexplorer_bg", "rgba(0,0,0,0.3) url(accounts/" + account + "/" + year + "/" + month + "/" + day + "/thumbs/" + photolisturl[k + indexthumbs * ROW * COL - 1] + ") center no-repeat");
            $.data(oDiv, "timexplorer_row", j);
            $.data(oDiv, "timexplorer_col", i);
            $oDiv.css("left", pagex - 100 + "px").css("top", "80%")
                 .css("width", dw + "px")
                 .css("height", dh + "px")
                 .html("<span></span>");
            if(indexthumbs == lastindexthumbs){
                if(k <= (num - indexthumbs * ROW * COL)){
                    $oDiv.css("background", $.data(oDiv, "timexplorer_bg"));
                }else{
                    $oDiv.css("background", "none").attr("class","active");
                }
            }else{
                $oDiv.css("background", $.data(oDiv, "timexplorer_bg"));
            }     
            $photowall.append($oDiv);
        }
    }

    var $aDiv = $photowall.find("div");
    var ready = false;
    setTimeout(function() {
        var ii = $aDiv.length - 1;

        //hide the empty thumbs
        if(indexthumbs == lastindexthumbs){
            $("#photowall div:gt("+ (rest - 1) +")").hide();
        }else{
            $aDiv.show();
        }
        //display the first page of thumbs
        var timer = setInterval(function() {
            var $childDiv = $aDiv.eq(ii);
            $childDiv.css("left", $.data($childDiv.get(0), "timexplorer_left") + "px")
                     .css("top", $.data($childDiv.get(0), "timexplorer_top") + "px");
            $childDiv.bind("click", function() {
                var idx = $.data($(this).get(0), "index");
                if (!ready) return;
                if (clicked) {
                    $arrowleft.hide();
                    $arrowright.hide();
                    $arrowleftforthumbs.show();
                    $arrowrightforthumbs.show();
                    if(indexthumbs == lastindexthumbs){
                        $("#photowall div:gt("+ (rest - 1) +")").hide();
                    }else{
                        $aDiv.show();
                    }
                    (function() {
                        for (i = 0; i < COL * ROW; i++) {
                            var $childDiv1 = $aDiv.eq(i);
                            $childDiv1.css("left", $.data($childDiv1.get(0), "timexplorer_left") + "px")
                                      .css("top", $.data($childDiv1.get(0), "timexplorer_top") + "px");
                            if(i < (num - indexthumbs * ROW * COL)){
                                $childDiv1.css("background", "rgba(0,0,0,0.3) url(accounts/" + account + "/" + year + "/" + month + "/" + day + "/thumbs/" + photolisturl[i + indexthumbs * ROW * COL] + ") center no-repeat").attr("class", "");
                            }
                        }
                        $photowall.find("span").css("opacity", "0");
                    })();
                } else {
                    //display the photo by clicking the thumbs
                    (function() {
                        $arrowleft.show();
                        $arrowright.show();
                        $arrowleftforthumbs.hide();
                        $arrowrightforthumbs.hide();
                        $aDiv.show();
                        
                        currentphotoindex = idx + indexthumbs * ROW * COL - 1;
                        $loading.fadeIn();
                        var oImg = new Image();
                        oImg.onload = function() {
                            $loading.fadeOut(500);
                            for (i = 0; i < $aDiv.length; i++) {
                                var $childDiv2 = $aDiv.eq(i);
                                var $oSpan = $childDiv2.find("span");
                                var ratio = 0;
                                dw = this.width / COL;
                                dh = this.height / ROW;
                                if(dw > 180){
                                    ratio = dw / 180;
                                    dw = 180;
                                    dh = dh / ratio;
                                }
                                if(dh > 120){
                                    ratio = dh / 120;
                                    dh = 120;
                                    dw = dw / ratio;
                                }
                                var ll = ($photowall.get(0).offsetWidth - COL * dw) * .5;
                                var tt = ($photowall.get(0).offsetHeight - ROW * dh) * .7;
                                $oSpan.css("background", "url(accounts/" + account + "/" + year + "/" + month + "/" + day + "/sample/" + photolisturl[idx + indexthumbs * ROW * COL - 1] + ") " + -$.data($childDiv2.get(0), "timexplorer_col") * dw + "px " + -$.data($childDiv2.get(0), "timexplorer_row") * dh + "px no-repeat" ).css("background-size", COL * dw + "px " + ROW * dh + "px ").css("opacity", "1");
                                $childDiv2.css("left", ll + $.data($childDiv2.get(0), "timexplorer_col") * dw + "px").css("top", tt + $.data($childDiv2.get(0), "timexplorer_row") * dh + "px").addClass("active");
                            }
                            setTimeout(function(){
                                $aDiv.css("background","none");
                            },400);   
                        };
                        oImg.src = "accounts/" + account + "/" + year + "/" + month + "/" + day + "/sample/" + photolisturl[idx + indexthumbs * ROW * COL - 1];
                        $(oImg).error(function() {
                            $loading.fadeOut(500);
                            alert("Photo not found");
                            $arrowleft.hide();
                            $arrowright.hide();
                            $arrowleftforthumbs.show();
                            $arrowrightforthumbs.show();
                            clicked = !clicked;
                        });
                    })();
                }
                clicked = !clicked;
            });
            ii--;
            if (ii == -1) {
                clearInterval(timer);
                ready = true;
                $loading.fadeOut(500);
            }
        }, 20);
    }, 0);

    //change photo by clicking the arrows
    $arrowright.add($arrowleft).unbind().bind("click", function() {
        var $aDiv = $photowall.find("div");
        var tmp = currentphotoindex / (COL * ROW);
        $loading.fadeIn();
        if ($(this).attr("class") == "arrowleft") {
            if (integer.test(tmp)) {
                indexthumbs--;
            }
            currentphotoindex--;
            //go back to the last photo
            if (currentphotoindex == -1) {      //num is the total number of photos in the repertory
                currentphotoindex = num - 1;        
                
                //recalculate the page index
                if (integer.test(num / (ROW * COL))) {      
                    indexthumbs = lastindexthumbs - 1;    
                } else {
                    indexthumbs = lastindexthumbs;
                }
            }
        } else {
            tmp = (currentphotoindex + 1) / (COL * ROW);

            //recalculate the page index
            if (integer.test(tmp)) {
                indexthumbs++;
            }
            currentphotoindex++;
            //go back to the first photo
            if (currentphotoindex == num) {
                currentphotoindex = 0;
                indexthumbs = 0;
            }
        }
        currentfirstphotoindex = indexthumbs * COL * ROW + 1;   //used for resize of window
        var oImg = new Image();
        oImg.onload = function() {
            var arr = [];
            var ratio = 0;
            dw = this.width / COL;
            dh = this.height / ROW;
            if(dw > 180){
                ratio = dw / 180;
                dw = 180;
                dh = dh / ratio;
            }
            if(dh > 120){
                ratio = dh / 120;
                dh = 120;
                dw = dw / ratio;
            }
            var ll = ($photowall.get(0).offsetWidth - COL * dw) * .5;
            var tt = ($photowall.get(0).offsetHeight - ROW * dh) * .7;
            for (i = 0; i < $aDiv.length; i++) {
                arr[i] = i;
            }

            //sort the index of thumbs randomly for the mosaic effect
            arr.sort(function() {
                return Math.random() - .5;
            });
            $loading.fadeOut(50);
            var timer = setInterval(function() {
                var item = arr.pop();
                var $childDiv3 = $aDiv.eq(item);
                $childDiv3.find("span").css("background", "url(accounts/" + account + "/" + year + "/" + month + "/" + day + "/sample/" + photolisturl[currentphotoindex] + ")" + -$.data($childDiv3.get(0), "timexplorer_col") * dw + "px " + -$.data($childDiv3.get(0), "timexplorer_row") * dh + "px no-repeat").css("background-size", COL * dw + "px " + ROW * dh + "px ");
                $childDiv3.css("left", ll + $.data($childDiv3.get(0), "timexplorer_col") * dw + "px").css("top", tt + $.data($childDiv3.get(0), "timexplorer_row") * dh + "px");

                if (!arr.length) clearInterval(timer);
            }, 20);
        };
        oImg.src = "accounts/" + account + "/" + year + "/" + month + "/" + day + "/sample/" + photolisturl[currentphotoindex];
    });
    
    //change the thumbs by clicking the arrows
    $arrowrightforthumbs.unbind().bind("click", function() {
        var $aDiv = $photowall.find("div");
        var tmp;
        $loading.fadeIn();
        indexthumbs++;

        if (integer.test(num / (ROW * COL))) {
            tmp = lastindexthumbs - 1;
        } else {
            tmp = lastindexthumbs;
        }

        //go back to the first page
        if (indexthumbs > tmp) {
            indexthumbs = 0;
        }
        if(indexthumbs == lastindexthumbs){
            $("#photowall div:gt("+ (rest - 1) +")").hide();
        }else{
            $aDiv.show();
        }
        currentfirstphotoindex = indexthumbs * COL * ROW + 1;
        $loading.fadeOut(50);
        for (var cptthumbs = 1; cptthumbs <= ROW * COL; cptthumbs++) {
            if(cptthumbs <= (num - indexthumbs * ROW * COL)){
                $aDiv.eq(cptthumbs - 1).css("background", "rgba(0,0,0,0.3) url(accounts/" + account + "/" + year + "/" + month + "/" + day + "/thumbs/" + photolisturl[cptthumbs + indexthumbs * COL * ROW - 1] + ") center no-repeat").attr("class","");
            }else{
                $aDiv.eq(cptthumbs - 1).css("background", "none").attr("class","active");
            }
        }
    });

    $arrowleftforthumbs.unbind().bind("click", function() {
        var $aDiv = $photowall.find("div");
        $loading.fadeIn();
        indexthumbs--;

        //go back to the last page
        if (indexthumbs < 0) {
            if (integer.test(num / (ROW * COL))) {
                indexthumbs = lastindexthumbs - 1;
            } else {
                indexthumbs = lastindexthumbs;
            }
        }
        if(indexthumbs == lastindexthumbs){
            $("#photowall div:gt("+ (rest - 1) +")").hide();
        }else{
            $aDiv.show();
        }
        currentfirstphotoindex = indexthumbs * COL * ROW + 1;
        for (var cptthumbs = 1; cptthumbs <= ROW * COL; cptthumbs++) {
            $loading.fadeOut(50);
            if(cptthumbs <= (num - indexthumbs * ROW * COL)){
                $aDiv.eq(cptthumbs - 1).css("background", "rgba(0,0,0,0.3) url(accounts/" + account + "/" + year + "/" + month + "/" + day + "/thumbs/" + photolisturl[cptthumbs + indexthumbs * COL * ROW - 1] + ") center no-repeat").attr("class","");
            }else{
                $aDiv.eq(cptthumbs - 1).css("background", "none").attr("class","active");
            }
        }
    });
}
