<?php

function deldir($dirName){
	if ( $handle = opendir( "$dirName" ) ) {       
	    while ( false !== ( $item = readdir( $handle ) ) ) {   
		    if ( $item != "." && $item != ".." ) {
			    if ( is_dir( "$dirName/$item" ) ) {       
			         rmdir( "$dirName/$item" );
			    }else{
			    	unlink("$dirName/$item");
			    } 
		    }
	    }
	    closedir( $handle );
	}
	rmdir( $dirName );
}

function getAlbumCover($account, $year, $month, $day,$position){
	$userFile = $position."accounts/".$account."/".$year."/".$month."/".$day."/user.json";
	$coverPath = $position."accounts/".$account."/".$year."/".$month."/".$day."/thumbs/";
	if(file_exists($userFile)){
		$handle = fopen($userFile, "r");
		$contents = fread($handle, filesize($userFile));
		fclose($handle);
		$fileContent = json_decode($contents);
		if(file_exists($coverPath.rawurldecode($fileContent->cover))){
			return $fileContent->cover;
		}else{
			//return getCoverFileName($userFile, $coverPath);
			return "";
		}
	}else{
		//return getCoverFileName($userFile, $coverPath);
		return "";
	}
}

function getCoverFileName($userFile, $coverPath){
	$cpt = 0;
	$thumbs = opendir($coverPath);
	while (($file = readdir($thumbs)) !== false && $cpt == 0)
	{
		if($file != "." && $file !=".."){
			$cpt++;
			$fileurl = rawurlencode($file);
			$coverFile = array("cover"=>$fileurl);
			file_put_contents($userFile, json_encode($coverFile)); 
			return $file;
		}
	}
	closedir($thumbs);
}

?>