<?php
session_start();

$account = $_SESSION['username'];
/*if(isset($_POST['dateselected'])){
    $dateselected = explode('/',$_POST['dateselected']);
    $currentmonth = $dateselected[1];
    $currentday = $dateselected[2];
    if($currentmonth[0] == 0){
        $currentmonth = $currentmonth[1];
    }
    if($currentday[0] == 0){
        $currentday = $currentday[1];
    }
    $_SESSION['yearclicked'] = $dateselected[0];
    $_SESSION['monthclicked'] = $currentmonth;
    $_SESSION['dayclicked'] = $currentday;
}*/

include "../php/languages/".$_SESSION['language'].".lang.php";
include "../php/timeline/functions.php";

if(isset($_SESSION['yearclicked']) && isset($_SESSION['monthclicked']) && isset($_SESSION['dayclicked'])){

    $currentyear = $_SESSION['yearclicked'];
    $currentmonth = $_SESSION['monthclicked'];
    $currentday = $_SESSION['dayclicked'];
    if(!isset($currentmonth[1])){
        $currentmonthcompleted[0] = "0"; 
        $currentmonthcompleted[1] = $currentmonth[0];     
    }else{
        $currentmonthcompleted = $currentmonth;
    }
    if(!isset($currentday[1])){
        $currentdaycompleted[0] = '0'; 
        $currentdaycompleted[1] = $currentday[0];
    }else{
        $currentdaycompleted = $currentday;
    }
}else{
    $currentyear = date("Y"); 
    $currentmonthcompleted = date("m");
    $currentdaycompleted = date("d");
    if($currentmonthcompleted[0] == 0){
        $currentmonth = $currentmonthcompleted[1];
    }else{
        $currentmonth = $currentmonthcompleted;
    }
    if($currentdaycompleted[0] == 0){
        $currentday = $currentdaycompleted[1];
    }else{
        $currentday = $currentdaycompleted;
    }
    $_SESSION['yearclicked'] = $currentyear;
    $_SESSION['monthclicked'] = $currentmonth;
    $_SESSION['dayclicked'] = $currentday;
}

if (isset($_GET['url']) && $_GET['url']=="signout")
{
    session_destroy();
    echo"<script type='text/javascript'>location='../';</script>";
}
if(isset($_SESSION['username'])){
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title>Timexplorer - <?php echo $title;?></title>
<meta name="description" content="File Upload widget with multiple file selection, drag&amp;drop support, progress bars, validation and preview images, audio and video for jQuery. Supports cross-domain, chunked and resumable file uploads and client-side image resizing. Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- jQuery UI styles -->
<link rel="stylesheet" href="../css/jquery-ui-1.10.3.custom.min.css">
<!-- Bootstrap styles -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="css/style.css">
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
<link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-fixed-top .navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../home.php">Timexplorer</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">  
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../home.php"><?php echo $goback;?></a></li>
                <li><a href="index.php?url=signout"><?php echo $signout;?></a></li>
            </ul>
        </div>
    </div>
</div>


<div id="containertimeline">
    <div class="timeline">
        <div class="thumbday">
            <?php
                $i = 0;
                $number = 0;
                $arr = array();
                $covers = array();
                if(!is_dir("../accounts/".$account."/".$currentyear."/".$currentmonth)){
                    mkdir("../accounts/".$account."/".$currentyear."/".$currentmonth);
                }
                $day = opendir("../accounts/".$account."/".$currentyear."/".$currentmonth);
                while (($file = readdir($day)) !== false)
                {
                    if($file != "." && $file !=".."){
                        if(is_dir("../accounts/".$account."/".$currentyear."/".$currentmonth."/".$file."/thumbs/")){
                            $number = count(scandir("../accounts/".$account."/".$currentyear."/".$currentmonth."/".$file."/thumbs/")) - 2;
                        }
                        if($number == 0){
                            deldir("../accounts/".$account."/".$currentyear."/".$currentmonth."/".$file);
                        }else{
                            $arr[$i] = $file;
                            $covers[$file] = getAlbumCover($account,$currentyear,$currentmonth,$file,"../"); 
                            $i++;
                        }
                    }
                }
                sort($arr);
                for($i = 0; $i < sizeof($arr); $i++){
                    if($arr[$i] != "." && $arr[$i] != ".."){
                        $background = "../accounts/".$account."/".$currentyear."/".$currentmonth."/".$arr[$i]."/thumbs/".$covers[$arr[$i]];
                        echo "<div class=\"day\" title=\"".$arr[$i]."\"><div class=\"dayposition\"><div class='calendartitle'>".$months[$currentmonth - 1]."</div>". $arr[$i] ."</div></div>";
                    }
                }
                if($i == 0){
                    $random = rand(1,5);
                    $background = "images/thumbs/".$random.".jpg";
                    echo "<div class=\"day\" title=".$currentday."><div class=\"dayposition\"><div class='calendartitle'>".$months[$currentmonth - 1]."</div>$empty</div></div>";
                }
                closedir($day);
            ?>      
        </div>
        <div class="thumbmonth">
            <?php
                $i = 0;
                $month = opendir("../accounts/".$account."/".$currentyear);
                while (($file = readdir($month)) !== false)
                {
                    
                    $arr1[$i] = $file;
                    $i++;
                }
                sort($arr1);
                for($i = 0; $i < sizeof($arr1); $i++){
                    if($arr1[$i] != "." && $arr1[$i] != ".."){
                        echo "<div class=\"month\" title=\"".$arr1[$i]."\"><div class=\"monthposition\"><div class='calendartitle'>".$currentyear."</div>".$months[$arr1[$i] - 1]."</div></div>";
                    }
                }
                closedir($month);
            ?>              
        </div>     
        <div class="thumbyear">
            <?php
            $year = opendir("../accounts/".$account);
            while (($file = readdir($year)) !== false)
            {
                $random = rand(1,5);
                if($file != "." && $file != ".."){
                    echo "<div class=\"year\" title=\"".$file."\"><div class=\"yearposition\"><div class='calendartitle'>Timexplorer</div>".$file ."</div></div>";
                }
            }
            closedir($year);
            ?>              
        </div>
    </div>
</div>

<div style="top:100px;position:relative;" class="container">
    <h1><?php echo $title;?></h1>    

    <div style="top:50px;" class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $notes;?></h3>
        </div>
        <div class="panel-body">
            <ul>
                <li style="margin-bottom: 5px;"><?php echo $firstnote;?></li>
                <li style="margin-bottom: 5px;"><?php echo $secondnote;?></li>
                <li><?php echo $thirdnote;?></li>
                <?php 
                    if($_SESSION['language'] == "fr-FR"){
                        echo $fourthnote.$currentdaycompleted[0].$currentdaycompleted[1]."/".$currentmonthcompleted[0].$currentmonthcompleted[1]."/".$currentyear."</strong>.<button id=\"changedate\" class=\"btn btn-info\">".$today."</li>";
                    }else if($_SESSION['language'] == "zh-CN"){
                        echo $fourthnote.$currentyear."/".$currentmonthcompleted[0].$currentmonthcompleted[1]."/".$currentdaycompleted[0].$currentdaycompleted[1]."</strong>的照片。<button id=\"changedate\" class=\"btn btn-info\">".$today."</li></li>";
                    }else{
                        echo $fourthnote.$currentyear."/".$currentmonthcompleted[0].$currentmonthcompleted[1]."/".$currentdaycompleted[0].$currentdaycompleted[1]."</strong>.<button id=\"changedate\" class=\"btn btn-info\">".$today."</li></li>";
                    }
                ?>  
                <form method="POST" name="choose" action="">
                     <div style="visibility:hidden;position:absolute;left:40%;top:40%"><input type="text" name="dateselected" id="datepicker" onchange="choose.submit();"/></div>
                </form>
     
            </ul>
        </div>
    </div>
    <br>
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span><?php echo $addfiles;?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span><?php echo $startupload;?></span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span><?php echo $cancelupload;?></span>
                </button>
                <button id="del" type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span><?php echo $delete;?></span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The loading indicator is shown during file processing -->
                <span class="fileupload-loading"></span>
            </div>
            <!-- The global progress information -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
    <br>
    
</div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>

        </td>
        <td>
            <p class="name">{%=file.name%} </p>
            {% if (file.error) { %}
                <div><span class="label label-danger"><?php echo $error;?></span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <p class="size">{%=o.formatFileSize(file.size)%}</p>
            {% if (!o.files.error) { %}
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            {% } %}
        </td>
        <td>
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span><?php echo $startupload;?></span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button id="del" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span><?php echo $cancelupload;?></span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                    {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
                <!--<div title="<?php echo $setalbumcover;?>" class="setcover btn btn-primary glyphicon glyphicon-refresh" id="{%=file.name%}"></div>-->
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger"><?php echo $error;?></span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span><?php echo $delete;?></span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span><?php echo $cancelupload;?></span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="js/timeline.js"></script>

<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script type="text/javascript" src="../js/jquery.smoothdivscroll-1.3-min.js"></script>
<script type="text/javascript" src="../js/jquery.mousewheel.min.js"></script>
</body> 
</html>

<?php
}else{
    echo"<script type='text/javascript'>location='../';</script>";  
}

?>