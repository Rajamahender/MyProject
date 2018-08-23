<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script>
$(document).ready(function(){
$( "#suggestionbox" ).autocomplete({
source: "adv_get.php"
});
var currentURL=window.location.href.split('?')[0];
  $("#rt").change(function(){
  window.location.href = currentURL+'?rtype='+$(this).val();
})
$('.tb a').click(function(){
if(confirm("Are you sure you want to delete?")){
       return true;
    }
    else{
        return false;
    }})
})
</script>
<form action="" method="post">
<div class="ui-widget">
<label for="suggestionbox">Run Type: </label>
<select id="runtype" name="runtype">
<option value="">Select</option>
<?php
#db connections
$dbusername="db_user";
$dbpassword="sp1r3V";
$susername="db_readuser";
$spassword="Tr33Wat3r";
$database="new_mail";
$masterdb= mysql_connect("db-mail-01.mtroute.net",$dbusername,$dbpassword);
@mysql_select_db($database) or die( "Unable to select update database");
$s = "select distinct runtype from  runtype_advertiser_info";
$runarray = array();
if($r = mysql_query($s)){
    if(mysql_num_rows($r) > 0){
        while($ro = mysql_fetch_array($r)){
        $runarray[] = $ro['runtype'];
        echo "<option value=$ro[runtype]>$ro[runtype]</option>";
        }
    }
}
echo '</select> </div><br/>';
if(isset($_POST['suggestionbox'])){
$adv = explode('|',$_POST['suggestionbox']);
$adv_id=trim($adv[1]);
$listid = trim($_POST['listid']);
$runtype = trim($_POST['runtype']);
if(!empty($adv_id) && !empty($listid) && !empty($runtype)){
$i="insert into runtype_advertiser_info (runtype,advertiser_id,list_key) values('$runtype',$adv_id,'$listid')";
if(mysql_query($i)){
$message="success";
}
}else{$message =  "Please fill three fields";}
}
?>

<div class="ui-widget">
<label for="suggestionbox">Advertiser Name: </label>
<input id="suggestionbox" name="suggestionbox"/><br/><br/>
<label for="suggestionbox">List Id: </label>
<input type="text" name="listid" /><br/><br/>
<input type="submit" name="submit" value="submit">
<?php echo $message;?>
</div>
</form>
<hr>
<div class="ui-widget">

<label for="suggestionbox">Search Run Type: </label>
<select id="rt" name="rt">
<option value=""><?php echo $_GET['rtype']?></option>
<?php
foreach($runarray as $ra){
         echo "<option value=$ra>$ra</option>";
}
echo '</select> </div><br/>';
if(isset($_GET['rtype'])){
$sql = "select a.ID,a.runtype,a.advertiser_id,a.list_key,b.advertiser_name from runtype_advertiser_info a join advertiser_info b on a.advertiser_id = b.advertiser_id where runtype = '$_GET[rtype]' order by id desc ";
if($result = mysql_query($sql)){
    if(mysql_num_rows($result) > 0){
        echo "<table border='1' cellpadding='7' class='tb'>";
            echo "<tr>";
                echo "<th>runtype</th>";
                echo "<th>advertiser_id</th>";
                echo "<th>list_key</th>";
                echo "<th>advertiser_name</th>";
                echo "<th>delete</th>";
            echo "</tr>";
        while($row = mysql_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['runtype'] . "</td>";
                echo "<td>" . $row['advertiser_id'] . "</td>";
                echo "<td>" . $row['list_key'] . "</td>";
                echo "<td>" . $row['advertiser_name'] . "</td>";
                echo "<td><a href='$_SERVER[PHP_SELF]?rtype=$row[runtype]&id=$row[ID]' style='color:red'>X</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        // Close result set
        mysql_free_result($result);
    }
}
}
if(isset($_GET['id'])){
//        echo "<script>var r = confirm("are you sure?"); if(r == true){";echo $y = 'hello';echo "}</script>";
 //       echo $y;
        $d = "delete from runtype_advertiser_info where ID=$_GET[id]";
        $ins = "insert into runtype_advertiser_info_deleterecords(ID,runtype,advertiser_id,list_key,inserted_date) select ID,runtype,advertiser_id,list_key,inserted_date from runtype_advertiser_info where ID=$_GET[id]";
        mysql_query($ins);
        if(mysql_query($d)){
echo "<script>var currentURL=window.location.href.split('?')[0];window.location.href = currentURL+'?rtype=".$_GET['rtype']."';</script>";


}
}
?>
