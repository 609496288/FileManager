<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>File Manager</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/index.css">
</head>
<body>
<?php 
  error_reporting(0);
  include("file.func.php");
  $home = $_SERVER['DOCUMENT_ROOT'];             //修改根目录
  $path = $_GET['path']?iconv("utf-8","gb2312",$_GET['path']):$home;
  $ext = strtolower(end(explode(".", $path)));
  $imageExt = array("gif","jpg","jpeg","png","bmp");
  $View = array("txt","html","htm","php","js","md","json","css","xml","jsp");

  if(filetype($path) == "dir"){
    $file = file_get($path);
  }elseif(filetype($path) == "file"){
    if($_GET['act'] == 'view'){
      if(in_array($ext, $imageExt)){
        $content = 'img';
      }elseif(in_array($ext, $View)){
        $content = file_get_contents($path)?file_get_contents($path):"文件内容为空！！";
      }else{
        echo "<script>alert('无法查看此类型文件');window.history.go(-1)</script>";
      }
    }else{
      file_down($path);
    }
  }

  if($_GET['act'] == 'rename'){
     $content = 'rename';
     $oldname = $_GET['oldname'];
  }elseif($_POST['newname'] && $_POST['oldname']){
     echo file_rename($_POST['oldname'],$_POST['newname']);
  }elseif($_GET['act'] == 'del'){
    $file = $_GET['file'];
    echo file_del($file);
  }

  $p = substr($path, strlen($home));
  $nav = explode('/', $p);
?>
<div class="modal fade" id="view">
   <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
             </button>
             <h4 class="modal-title">Content...</h4>
          </div>
          <div class="modal-body">
             <?php 
                if($content == 'img'){
                  echo "<img src='".$path."'/>pc环境看不了图片 请上传到服务器测试";  //pc环境看不了图片 请上传到服务器测试
                }elseif($content == 'rename'){
                  ?>
                <center>
                  <form action="index.php" method="post">
                      请填写新的文件名:<input type="text" name="newname" placeholder="重命名" style="border:1px solid gray;" />
                      <input type="hidden" name="oldname" value="<?php echo $oldname ?>"/>
                      <input type="submit" value="重命名" />
                  </form>
                </center>
             <?php
                }else{
                  echo "<pre style='overflow: auto;width:100%;height:400px;'>";
                   highlight_string($content);
                  echo "</pre>" ;
                }
                ?>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="history.go(-1)">返 回</button>
          </div>
      </div>
   </div>
</div>
    <div class="container">
        <div class="header">
           <div class="logo">
               <img class="img-circle" src="image/1.jpg" width="120px" height="80px" />
               <a href="http://jishuz.cn/about" target="_blank" style="float: right">About me</a>
           </div>
        </div>
        <div class="FileManager">
           <div class="menu">
              <button class="btn-primary">
                 上传文件
              </button>
           </div>
           <div class="menu-nav">
             <ol class="breadcrumb">
              <?php
                    for($i=0;$i<sizeof($nav);$i++){
                        $van[$i] = $nav[$i];
                        $url = $home.implode('/', $van);
                     if($nav[$i] == ''){
                       echo "<li><a href='index.php'>Home</a></li>";
                     }else{
                       echo "<li class='active'><a href='index.php?path=".$url."'>".$nav[$i]."</a></li>";
                     }
                  }
              ?>
              <span class="input-group">
                 <input type="text" name="" id="search"><span class="glyphicon glyphicon-search" id="search-sub"></span>
              </span>
             </ol>
           </div>
           <div class="file-list">
              <table width="100%" class="table table-striped">
              <?php   
                     for($i=0;$i<sizeof($file);$i++){
                      if($file[$i][2] == "file"){
                          $file[$i][2] = $file[$i][1];
                      }
                            echo "<tr>";
                            echo "<td width='5%'><input type='checkbox' class='chk'></td>";
                            echo "<td width='60%'><a href='index.php?path=".$file[$i]['dir']."'  style='color:black'>".$file[$i][0]."</a></td>";
                            echo "<td width='10%'>".$file[$i][2]."</td>";
                            echo "<td width='13%'>".$file[$i][3]."</td>";
                            echo "<td width='12%'>";
                            echo "<a href='index.php?path=".$file[$i]['dir']."&act=view' title='查看'><i class='glyphicon glyphicon-eye-open'></i></a>&nbsp;&nbsp;&nbsp;";
                            echo "<a href='index.php?act=rename&oldname=".$file[$i]['dir']."' title='重命名'><i class='glyphicon glyphicon-cog'></i></a>&nbsp;&nbsp;&nbsp;";
                            echo "<a href='javascript:del(\"".$file[$i]['dir']."\")' title='删除'><i class='glyphicon glyphicon-remove'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                      }
              ?>
              </table>
           </div>
           <div class="menu-select">
                <button type="button" class="btn-md btn btn-info" style="margin-left: 15px;" onClick="checkb()">选择所有</button>
                <button class="btn btn-default" style="margin-left: 15px;">Copy to</button>
                <button class="btn btn-default">Move to</button>
                <button class="btn btn-default">ZIP</button>
           </div>
        </div>
    </div>
    <div class="footer container">
        <div class="f">© 2016 Silver'FileManager- 湘ICP备15013373号 |  Theme By <a href="http://jishuz.cn">Silver</a>
        </div>
    </div>
	  <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type='text/javascript'>
    $(document).ready(function () {
      <?php 
      if($content){
        echo "$('#view').modal(true);";//加载模态框
      }
      ?>
    });

    function del(file){
      if(window.confirm("你确定要删除此文件或文件夹吗？")){
          window.location.href="index.php?act=del&file="+file;
      }
    }

    function checkb(){
      var chk = document.getElementsByClassName("chk");
      for(var i=0;i<chk.length;i++){
        chk[i].checked = true;
      }
    }
   </script>
</body>
</html>