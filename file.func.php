<?php
header("Content-type:text/html;charset=utf-8");
function file_get($path){
  	   $handle = opendir($path);
  	   $i = 0;
  	   $dirFile = null;
  	   while ($file = readdir($handle)) {
  	   	  if($file != "." && $file != ".."){
  	   	    $dirFile = $path."/".$file;                              //文件源链接
  	   	  	$message[$i][0] = iconv("gb2312","utf-8",$file);                                 //文件名
  	   	    $message[$i][1] = byteTo(filesize($dirFile));            //文件大小
  	   	    $message[$i][2] = filetype($dirFile);                    //文件类型
  	   	    $message[$i][3] = date("Y-m-d",filectime($dirFile));     //创建时间
            $message[$i]["dir"] = iconv("gb2312","utf-8",$dirFile);
            $mes[] = filetype($dirFile);   
  	   	    $i++;
  	   	  }
  	   }
  	   closedir($handle);
       array_multisort($mes, SORT_ASC, SORT_STRING,$message);
  	   return $message;
  }
/*  $file = file_get("D:\JiShuZ");
  echo $file[0]['dir'];
  var_dump($file);*/


  function byteTo($char){
  	$ch =array("B","KB","MB","GB","TB","PB");
  	$i=0;
  	while(true){
  		if($char>1024){
  			$char = $char/1024;
  		}else{
  			$char = round($char,2).$ch[$i];
  			return $char;
  		}
  		$i++;
  	}
  }
/*  $char = byteTo(20490);
  var_dump($char);*/


    /*复制目录*/
  function copyDir($dirFrom,$dirTo){
  	if(is_file($dirTo)){
  		die("无法建立目录 $dirTo");
  	}

  	if(!file_exists($dirTo)){
  		mkdir($dirTo);
  	}

  	$handle = opendir($dirFrom);
  	readdir($handle);
  	readdir($handle);

  	while (false !== ($file = readdir($handle))) {
  		$fileFrom = $dirFrom.DIRECTORY_SEPARATOR.$file;
  		$fileTo = $dirTo.DIRECTORY_SEPARATOR.$file;
  		if(is_dir($fileFrom)){
  			copyDir($fileFrom,$fileTo);
  		}else{
  			@copy($fileFrom,$fileTo);
  		}
  	}
  }


     /*下载文件*/
    function file_down($path){
      header("content-disposition:attachment;filename=".basename($path));
      header("content-length:".filesize($path));
      readfile($path);
    }



    /*重命名文件*/
    function file_rename($oldname,$newname){
      if(file_check($newname)){
        $path = dirname($oldname);
        if(!file_exists($path."/".$newname)){
           if(rename($oldname, $path."/".$newname)){
             return "<script>alert('重命名成功');window.location.href='index.php'</script>";
           }else{
             return "<script>alert('重命名失败');window.location.href='index.php'</script>";
           }
        }else{
          return "<script>alert('存在同名文件,请重新命名');window.location.href='index.php'</script>";
        }
      }else{
         return "<script>alert('文件名存在非法字符');window.location.href='index.php'</script>";
      }
    }

    function file_check($filename){
      $pattern = "/[\/,*,<>,\?|]/";
      if(preg_match($pattern,$filename)){
        return false;
      }else{
        return true;
      }
    }


        /*删除文件*/
    function file_del($file){
      if(filetype($file) == 'file'){
        unlink($file);
      }else{
        $handle=opendir($file);
        while (($item = readdir($handle)) !== false) {
          if($item !="." && $item !=".."){
            if(is_file($file."/".$item)){
              unlink($file."/".$item);
              echo "正在删除 [".$file."/".$item."] ...<br/>";
            }

            if(is_dir($file."/".$item)){
              $func = __FUNCTION__;
              $func($file."/".$item);
            }
          }
        }/*while*/
        closedir($handle);
        rmdir($file);
      }
     return "<script>alert('文件或文件夹删除成功');window.location.href='index.php'</script>";
    }
  //   /*移动目录*/
  // function move($source,$dest){
  // }


