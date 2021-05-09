<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 4.7.7
*/error_reporting(6135);$Xc=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Xc||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Ii=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Ii)$$X=$Ii;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection(){global$g;return$g;}function
adminer(){global$b;return$b;}function
version(){global$ia;return$ia;}function
idf_unescape($u){$pe=substr($u,-1);return
str_replace($pe.$pe,$pe,substr($u,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes($sg,$Xc=false){if(get_magic_quotes_gpc()){while(list($y,$X)=each($sg)){foreach($X
as$fe=>$W){unset($sg[$y][$fe]);if(is_array($W)){$sg[$y][stripslashes($fe)]=$W;$sg[]=&$sg[$y][stripslashes($fe)];}else$sg[$y][stripslashes($fe)]=($Xc?$W:stripslashes($W));}}}}function
bracket_escape($u,$Oa=false){static$ui=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($u,($Oa?array_flip($ui):$ui));}function
min_version($aj,$De="",$h=null){global$g;if(!$h)$h=$g;$nh=$h->server_info;if($De&&preg_match('~([\d.]+)-MariaDB~',$nh,$A)){$nh=$A[1];$aj=$De;}return(version_compare($nh,$aj)>=0);}function
charset($g){return(min_version("5.5.3",0,$g)?"utf8mb4":"utf8");}function
script($yh,$ti="\n"){return"<script".nonce().">$yh</script>$ti";}function
script_src($Ni){return"<script src='".h($Ni)."'".nonce()."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace("\0","&#0;",htmlspecialchars($P,ENT_QUOTES,'utf-8'));}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($B,$Y,$fb,$me="",$uf="",$kb="",$ne=""){$H="<input type='checkbox' name='$B' value='".h($Y)."'".($fb?" checked":"").($ne?" aria-labelledby='$ne'":"").">".($uf?script("qsl('input').onclick = function () { $uf };",""):"");return($me!=""||$kb?"<label".($kb?" class='$kb'":"").">$H".h($me)."</label>":$H);}function
optionlist($_f,$hh=null,$Si=false){$H="";foreach($_f
as$fe=>$W){$Af=array($fe=>$W);if(is_array($W)){$H.='<optgroup label="'.h($fe).'">';$Af=$W;}foreach($Af
as$y=>$X)$H.='<option'.($Si||is_string($y)?' value="'.h($y).'"':'').(($Si||is_string($y)?(string)$y:$X)===$hh?' selected':'').'>'.h($X);if(is_array($W))$H.='</optgroup>';}return$H;}function
html_select($B,$_f,$Y="",$tf=true,$ne=""){if($tf)return"<select name='".h($B)."'".($ne?" aria-labelledby='$ne'":"").">".optionlist($_f,$Y)."</select>".(is_string($tf)?script("qsl('select').onchange = function () { $tf };",""):"");$H="";foreach($_f
as$y=>$X)$H.="<label><input type='radio' name='".h($B)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";return$H;}function
select_input($Ja,$_f,$Y="",$tf="",$eg=""){$Yh=($_f?"select":"input");return"<$Yh$Ja".($_f?"><option value=''>$eg".optionlist($_f,$Y,true)."</select>":" size='10' value='".h($Y)."' placeholder='$eg'>").($tf?script("qsl('$Yh').onchange = $tf;",""):"");}function
confirm($Ne="",$ih="qsl('input')"){return
script("$ih.onclick = function () { return confirm('".($Ne?js_escape($Ne):'Are you sure?')."'); };","");}function
print_fieldset($t,$ue,$dj=false){echo"<fieldset><legend>","<a href='#fieldset-$t'>$ue</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$t');",""),"</legend>","<div id='fieldset-$t'".($dj?"":" class='hidden'").">\n";}function
bold($Wa,$kb=""){return($Wa?" class='active $kb'":($kb?" class='$kb'":""));}function
odd($H=' class="odd"'){static$s=0;if(!$H)$s=-1;return($s++%2?$H:'');}function
js_escape($P){return
addcslashes($P,"\r\n'\\/");}function
json_row($y,$X=null){static$Yc=true;if($Yc)echo"{";if($y!=""){echo($Yc?"":",")."\n\t\"".addcslashes($y,"\r\n\t\"\\/").'": '.($X!==null?'"'.addcslashes($X,"\r\n\"\\/").'"':'null');$Yc=false;}else{echo"\n}\n";$Yc=true;}}function
ini_bool($Sd){$X=ini_get($Sd);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
sid(){static$H;if($H===null)$H=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$H;}function
set_password($Zi,$M,$V,$E){$_SESSION["pwds"][$Zi][$M][$V]=($_COOKIE["adminer_key"]&&is_string($E)?array(encrypt_string($E,$_COOKIE["adminer_key"])):$E);}function
get_password(){$H=get_session("pwds");if(is_array($H))$H=($_COOKIE["adminer_key"]?decrypt_string($H[0],$_COOKIE["adminer_key"]):false);return$H;}function
q($P){global$g;return$g->quote($P);}function
get_vals($F,$e=0){global$g;$H=array();$G=$g->query($F);if(is_object($G)){while($I=$G->fetch_row())$H[]=$I[$e];}return$H;}function
get_key_vals($F,$h=null,$qh=true){global$g;if(!is_object($h))$h=$g;$H=array();$G=$h->query($F);if(is_object($G)){while($I=$G->fetch_row()){if($qh)$H[$I[0]]=$I[1];else$H[]=$I[0];}}return$H;}function
get_rows($F,$h=null,$n="<p class='error'>"){global$g;$yb=(is_object($h)?$h:$g);$H=array();$G=$yb->query($F);if(is_object($G)){while($I=$G->fetch_assoc())$H[]=$I;}elseif(!$G&&!is_object($h)&&$n&&defined("PAGE_HEADER"))echo$n.error()."\n";return$H;}function
unique_array($I,$w){foreach($w
as$v){if(preg_match("~PRIMARY|UNIQUE~",$v["type"])){$H=array();foreach($v["columns"]as$y){if(!isset($I[$y]))continue
2;$H[$y]=$I[$y];}return$H;}}}function
escape_key($y){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$y,$A))return$A[1].idf_escape(idf_unescape($A[2])).$A[3];return
idf_escape($y);}function
where($Z,$p=array()){global$g,$x;$H=array();foreach((array)$Z["where"]as$y=>$X){$y=bracket_escape($y,1);$e=escape_key($y);$H[]=$e.($x=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):($x=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($p[$y],q($X))));if($x=="sql"&&preg_match('~char|text~',$p[$y]["type"])&&preg_match("~[^ -@]~",$X))$H[]="$e = ".q($X)." COLLATE ".charset($g)."_bin";}foreach((array)$Z["null"]as$y)$H[]=escape_key($y)." IS NULL";return
implode(" AND ",$H);}function
where_check($X,$p=array()){parse_str($X,$db);remove_slashes(array(&$db));return
where($db,$p);}function
where_link($s,$e,$Y,$wf="="){return"&where%5B$s%5D%5Bcol%5D=".urlencode($e)."&where%5B$s%5D%5Bop%5D=".urlencode(($Y!==null?$wf:"IS NULL"))."&where%5B$s%5D%5Bval%5D=".urlencode($Y);}function
convert_fields($f,$p,$K=array()){$H="";foreach($f
as$y=>$X){if($K&&!in_array(idf_escape($y),$K))continue;$Ga=convert_field($p[$y]);if($Ga)$H.=", $Ga AS ".idf_escape($y);}return$H;}function
cookie($B,$Y,$xe=2592000){global$ba;return
header("Set-Cookie: $B=".urlencode($Y).($xe?"; expires=".gmdate("D, d M Y H:i:s",time()+$xe)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).($ba?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session($dd=false){$Ri=ini_bool("session.use_cookies");if(!$Ri||$dd){session_write_close();if($Ri&&@ini_set("session.use_cookies",false)===false)session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($Zi,$M,$V,$l=null){global$gc;preg_match('~([^?]*)\??(.*)~',remove_from_uri(implode("|",array_keys($gc))."|username|".($l!==null?"db|":"").session_name()),$A);return"$A[1]?".(sid()?SID."&":"").($Zi!="server"||$M!=""?urlencode($Zi)."=".urlencode($M)."&":"")."username=".urlencode($V).($l!=""?"&db=".urlencode($l):"").($A[2]?"&$A[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($ze,$Ne=null){if($Ne!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($ze!==null?$ze:$_SERVER["REQUEST_URI"]))][]=$Ne;}if($ze!==null){if($ze=="")$ze=".";header("Location: $ze");exit;}}function
query_redirect($F,$ze,$Ne,$Dg=true,$Ec=true,$Pc=false,$gi=""){global$g,$n,$b;if($Ec){$Fh=microtime(true);$Pc=!$g->query($F);$gi=format_time($Fh);}$Ah="";if($F)$Ah=$b->messageQuery($F,$gi,$Pc);if($Pc){$n=error().$Ah.script("messagesPrint();");return
false;}if($Dg)redirect($ze,$Ne.$Ah);return
true;}function
queries($F){global$g;static$xg=array();static$Fh;if(!$Fh)$Fh=microtime(true);if($F===null)return
array(implode("\n",$xg),format_time($Fh));$xg[]=(preg_match('~;$~',$F)?"DELIMITER ;;\n$F;\nDELIMITER ":$F).";";return$g->query($F);}function
apply_queries($F,$S,$Ac='table'){foreach($S
as$Q){if(!queries("$F ".$Ac($Q)))return
false;}return
true;}function
queries_redirect($ze,$Ne,$Dg){list($xg,$gi)=queries(null);return
query_redirect($xg,$ze,$Ne,$Dg,false,!$Dg,$gi);}function
format_time($Fh){return
sprintf('%.3f s',max(0,microtime(true)-$Fh));}function
relative_uri(){return
preg_replace('~^[^?]*/([^?]*)~','\1',$_SERVER["REQUEST_URI"]);}function
remove_from_uri($Pf=""){return
substr(preg_replace("~(?<=[?&])($Pf".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
pagination($D,$Lb){return" ".($D==$Lb?$D+1:'<a href="'.h(remove_from_uri("page").($D?"&page=$D".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($D+1)."</a>");}function
get_file($y,$Tb=false){$Vc=$_FILES[$y];if(!$Vc)return
null;foreach($Vc
as$y=>$X)$Vc[$y]=(array)$X;$H='';foreach($Vc["error"]as$y=>$n){if($n)return$n;$B=$Vc["name"][$y];$oi=$Vc["tmp_name"][$y];$Ab=file_get_contents($Tb&&preg_match('~\.gz$~',$B)?"compress.zlib://$oi":$oi);if($Tb){$Fh=substr($Ab,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Fh,$Jg))$Ab=iconv("utf-16","utf-8",$Ab);elseif($Fh=="\xEF\xBB\xBF")$Ab=substr($Ab,3);$H.=$Ab."\n\n";}else$H.=$Ab;}return$H;}function
upload_error($n){$Ke=($n==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($n?'Unable to upload a file.'.($Ke?" ".sprintf('Maximum allowed file size is %sB.',$Ke):""):'File does not exist.');}function
repeat_pattern($cg,$ve){return
str_repeat("$cg{0,65535}",$ve/65535)."$cg{0,".($ve%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
shorten_utf8($P,$ve=80,$Mh=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$ve).")($)?)u",$P,$A))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$ve).")($)?)",$P,$A);return
h($A[1]).$Mh.(isset($A[2])?"":"<i>‚Ä¶</i>");}function
format_number($X){return
strtr(number_format($X,0,".",','),preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($sg,$Hd=array()){$H=false;while(list($y,$X)=each($sg)){if(!in_array($y,$Hd)){if(is_array($X)){foreach($X
as$fe=>$W)$sg[$y."[$fe]"]=$W;}else{$H=true;echo'<input type="hidden" name="'.h($y).'" value="'.h($X).'">';}}}return$H;}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($Q,$Qc=false){$H=table_status($Q,$Qc);return($H?$H:array("Name"=>$Q));}function
column_foreign_keys($Q){global$b;$H=array();foreach($b->foreignKeys($Q)as$q){foreach($q["source"]as$X)$H[$X][]=$q;}return$H;}function
enum_input($T,$Ja,$o,$Y,$vc=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$o["length"],$Fe);$H=($vc!==null?"<label><input type='$T'$Ja value='$vc'".((is_array($Y)?in_array($vc,$Y):$Y===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Fe[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$fb=(is_int($Y)?$Y==$s+1:(is_array($Y)?in_array($s+1,$Y):$Y===$X));$H.=" <label><input type='$T'$Ja value='".($s+1)."'".($fb?' checked':'').'>'.h($b->editVal($X,$o)).'</label>';}return$H;}function
input($o,$Y,$r){global$U,$b,$x;$B=h(bracket_escape($o["field"]));echo"<td class='function'>";if(is_array($Y)&&!$r){$Ea=array($Y);if(version_compare(PHP_VERSION,5.4)>=0)$Ea[]=JSON_PRETTY_PRINT;$Y=call_user_func_array('json_encode',$Ea);$r="json";}$Ng=($x=="mssql"&&$o["auto_increment"]);if($Ng&&!$_POST["save"])$r=null;$md=(isset($_GET["select"])||$Ng?array("orig"=>'original'):array())+$b->editFunctions($o);$Ja=" name='fields[$B]'";if($o["type"]=="enum")echo
h($md[""])."<td>".$b->editInput($_GET["edit"],$o,$Ja,$Y);else{$wd=(in_array($r,$md)||isset($md[$r]));echo(count($md)>1?"<select name='function[$B]'>".optionlist($md,$r===null||$wd?$r:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($md))).'<td>';$Ud=$b->editInput($_GET["edit"],$o,$Ja,$Y);if($Ud!="")echo$Ud;elseif(preg_match('~bool~',$o["type"]))echo"<input type='hidden'$Ja value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked='checked'":"")."$Ja value='1'>";elseif($o["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$o["length"],$Fe);foreach($Fe[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$fb=(is_int($Y)?($Y>>$s)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$B][$s]' value='".(1<<$s)."'".($fb?' checked':'').">".h($b->editVal($X,$o)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$o["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$B'>";elseif(($ei=preg_match('~text|lob|memo~i',$o["type"]))||preg_match("~\n~",$Y)){if($ei&&$x!="sqlite")$Ja.=" cols='50' rows='12'";else{$J=min(12,substr_count($Y,"\n")+1);$Ja.=" cols='30' rows='$J'".($J==1?" style='height: 1.2em;'":"");}echo"<textarea$Ja>".h($Y).'</textarea>';}elseif($r=="json"||preg_match('~^jsonb?$~',$o["type"]))echo"<textarea$Ja cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';else{$Me=(!preg_match('~int~',$o["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$o["length"],$A)?((preg_match("~binary~",$o["type"])?2:1)*$A[1]+($A[3]?1:0)+($A[2]&&!$o["unsigned"]?1:0)):($U[$o["type"]]?$U[$o["type"]]+($o["unsigned"]?0:1):0));if($x=='sql'&&min_version(5.6)&&preg_match('~time~',$o["type"]))$Me+=7;echo"<input".((!$wd||$r==="")&&preg_match('~(?<!o)int(?!er)~',$o["type"])&&!preg_match('~\[\]~',$o["full_type"])?" type='number'":"")." value='".h($Y)."'".($Me?" data-maxlength='$Me'":"").(preg_match('~char|binary~',$o["type"])&&$Me>20?" size='40'":"")."$Ja>";}echo$b->editHint($_GET["edit"],$o,$Y);$Yc=0;foreach($md
as$y=>$X){if($y===""||!$X)break;$Yc++;}if($Yc)echo
script("mixin(qsl('td'), {onchange: partial(skipOriginal, $Yc), oninput: function () { this.onchange(); }});");}}function
process_input($o){global$b,$m;$u=bracket_escape($o["field"]);$r=$_POST["function"][$u];$Y=$_POST["fields"][$u];if($o["type"]=="enum"){if($Y==-1)return
false;if($Y=="")return"NULL";return+$Y;}if($o["auto_increment"]&&$Y=="")return
null;if($r=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?idf_escape($o["field"]):false);if($r=="NULL")return"NULL";if($o["type"]=="set")return
array_sum((array)$Y);if($r=="json"){$r="";$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}if(preg_match('~blob|bytea|raw|file~',$o["type"])&&ini_bool("file_uploads")){$Vc=get_file("fields-$u");if(!is_string($Vc))return
false;return$m->quoteBinary($Vc);}return$b->processInput($o,$Y,$r);}function
fields_from_edit(){global$m;$H=array();foreach((array)$_POST["field_keys"]as$y=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$y];$_POST["fields"][$X]=$_POST["field_vals"][$y];}}foreach((array)$_POST["fields"]as$y=>$X){$B=bracket_escape($y,1);$H[$B]=array("field"=>$B,"privileges"=>array("insert"=>1,"update"=>1),"null"=>1,"auto_increment"=>($y==$m->primary),);}return$H;}function
search_tables(){global$b,$g;$_GET["where"][0]["val"]=$_POST["query"];$kh="<ul>\n";foreach(table_status('',true)as$Q=>$R){$B=$b->tableName($R);if(isset($R["Engine"])&&$B!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$G=$g->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($Q),array())),1));if(!$G||$G->fetch_row()){$og="<a href='".h(ME."select=".urlencode($Q)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$B</a>";echo"$kh<li>".($G?$og:"<p class='error'>$og: ".error())."\n";$kh="";}}}echo($kh?"<p class='message'>".'No tables.':"</ul>")."\n";}function
dump_headers($Ed,$We=false){global$b;$H=$b->dumpHeaders($Ed,$We);$Mf=$_POST["output"];if($Mf!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($Ed).".$H".($Mf!="file"&&!preg_match('~[^0-9a-z]~',$Mf)?".$Mf":""));session_write_close();ob_flush();flush();return$H;}function
dump_csv($I){foreach($I
as$y=>$X){if(preg_match("~[\"\n,;\t]~",$X)||$X==="")$I[$y]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$I)."\r\n";}function
apply_sql_function($r,$e){return($r?($r=="unixepoch"?"DATETIME($e, '$r')":($r=="count distinct"?"COUNT(DISTINCT ":strtoupper("$r("))."$e)"):$e);}function
get_temp_dir(){$H=ini_get("upload_tmp_dir");if(!$H){if(function_exists('sys_get_temp_dir'))$H=sys_get_temp_dir();else{$Wc=@tempnam("","");if(!$Wc)return
false;$H=dirname($Wc);unlink($Wc);}}return$H;}function
file_open_lock($Wc){$kd=@fopen($Wc,"r+");if(!$kd){$kd=@fopen($Wc,"w");if(!$kd)return;chmod($Wc,0660);}flock($kd,LOCK_EX);return$kd;}function
file_write_unlock($kd,$Nb){rewind($kd);fwrite($kd,$Nb);ftruncate($kd,strlen($Nb));flock($kd,LOCK_UN);fclose($kd);}function
password_file($i){$Wc=get_temp_dir()."/adminer.key";$H=@file_get_contents($Wc);if($H||!$i)return$H;$kd=@fopen($Wc,"w");if($kd){chmod($Wc,0660);$H=rand_string();fwrite($kd,$H);fclose($kd);}return$H;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($X,$_,$o,$fi){global$b;if(is_array($X)){$H="";foreach($X
as$fe=>$W)$H.="<tr>".($X!=array_values($X)?"<th>".h($fe):"")."<td>".select_value($W,$_,$o,$fi);return"<table cellspacing='0'>$H</table>";}if(!$_)$_=$b->selectLink($X,$o);if($_===null){if(is_mail($X))$_="mailto:$X";if(is_url($X))$_=$X;}$H=$b->editVal($X,$o);if($H!==null){if(!is_utf8($H))$H="\0";elseif($fi!=""&&is_shortable($o))$H=shorten_utf8($H,max(0,+$fi));else$H=h($H);}return$b->selectVal($H,$_,$o,$X);}function
is_mail($sc){$Ha='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$fc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$cg="$Ha+(\\.$Ha+)*@($fc?\\.)+$fc";return
is_string($sc)&&preg_match("(^$cg(,\\s*$cg)*\$)i",$sc);}function
is_url($P){$fc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($fc?\\.)+$fc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P);}function
is_shortable($o){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',$o["type"]);}function
count_rows($Q,$Z,$ae,$pd){global$x;$F=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($ae&&($x=="sql"||count($pd)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$pd).")$F":"SELECT COUNT(*)".($ae?" FROM (SELECT 1$F GROUP BY ".implode(", ",$pd).") x":$F));}function
slow_query($F){global$b,$qi,$m;$l=$b->database();$hi=$b->queryTimeout();$vh=$m->slowQuery($F,$hi);if(!$vh&&support("kill")&&is_object($h=connect())&&($l==""||$h->select_db($l))){$ke=$h->result(connection_id());echo'<script',nonce(),'>
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'kill=',$ke,'&token=',$qi,'\');
}, ',1000*$hi,');
</script>
';}else$h=null;ob_flush();flush();$H=@get_key_vals(($vh?$vh:$F),$h,false);if($h){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$H;}function
get_token(){$_g=rand(1,1e6);return($_g^$_SESSION["token"]).":$_g";}function
verify_token(){list($qi,$_g)=explode(":",$_POST["token"]);return($_g^$_SESSION["token"])==$qi;}function
lzw_decompress($Sa){$cc=256;$Ta=8;$mb=array();$Pg=0;$Qg=0;for($s=0;$s<strlen($Sa);$s++){$Pg=($Pg<<8)+ord($Sa[$s]);$Qg+=8;if($Qg>=$Ta){$Qg-=$Ta;$mb[]=$Pg>>$Qg;$Pg&=(1<<$Qg)-1;$cc++;if($cc>>$Ta)$Ta++;}}$bc=range("\0","\xFF");$H="";foreach($mb
as$s=>$lb){$rc=$bc[$lb];if(!isset($rc))$rc=$oj.$oj[0];$H.=$rc;if($s)$bc[]=$oj.$rc[0];$oj=$rc;}return$H;}function
on_help($sb,$sh=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $sb, $sh) }, onmouseout: helpMouseout});","");}function
edit_form($a,$p,$I,$Li){global$b,$x,$qi,$n;$Rh=$b->tableName(table_status1($a,true));page_header(($Li?'Edit':'Insert'),$n,array("select"=>array($a,$Rh)),$Rh);if($I===false)echo"<p class='error'>".'No rows.'."\n";echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$p)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table cellspacing='0' class='layout'>".script("qsl('table').onkeydown = editingKeydown;");foreach($p
as$B=>$o){echo"<tr><th>".$b->fieldName($o);$Ub=$_GET["set"][bracket_escape($B)];if($Ub===null){$Ub=$o["default"];if($o["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$Ub,$Jg))$Ub=$Jg[1];}$Y=($I!==null?($I[$B]!=""&&$x=="sql"&&preg_match("~enum|set~",$o["type"])?(is_array($I[$B])?array_sum($I[$B]):+$I[$B]):$I[$B]):(!$Li&&$o["auto_increment"]?"":(isset($_GET["select"])?false:$Ub)));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$o);$r=($_POST["save"]?(string)$_POST["function"][$B]:($Li&&preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(preg_match("~time~",$o["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$r="now";}input($o,$Y,$r);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($p){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($Li?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n",($Li?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".'Saving'."‚Ä¶', this); };"):"");}}echo($Li?"<input type='submit' name='delete' value='".'Delete'."'>".confirm()."\n":($_POST||!$p?"":script("focus(qsa('td', qs('#form'))[1].firstChild);")));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$qi,'">
</form>
';}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0Ñ\0\n @\0¥CÑË\"\0`E„Q∏‡ˇá?¿tvM'îJd¡d\\åb0\0ƒ\"ô¿f”à§Ós5õœÁ—AùXPaJì0Ñ•ë8Ñ#RäT©ëz`à#.©«cÌX√˛»Ä?¿-\0°Im?†.´M∂Ä\0»Ø(Ãâ˝¿/(%å\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1ÃáìŸåﬁl7úáB1Ñ4vb0òÕfsëºÍn2BÃ—±Ÿòﬁn:á#(ºb.\rDc)»»a7EÑë§¬l¶√±îËi1Ãésò¥Á-4ôáf”	»Œi7Ü≥π§»t4Ö¶”yËZf4ù∞iñAT´VVêÈf:œ¶,:1¶Q›ºÒb2`«#˛>:7GÔó1—ÿ“s∞ôLóXD*bv<‹å#£e@÷:4Áß!foê∑∆t:<•‹Âíæôo‚‹\ni√≈',Èªa_§:πiÔÖ¥¡Bv¯|N˚4.5NfÅi¢vp–h∏∞l®Í°÷ö‹O¶ÅâÓ= £OFQ–ƒk\$•”iıô¿¬d2T„°p‡ 6Ñã˛á°-ÿZÄéÉ†ﬁ6Ω£Äh:¨aÃ,é£ÎÓ2ç#8–ê±#íò6n‚ÓÜÒJà¢h´tÖå±ä‰4O42ÙΩokﬁæ*r†©Ä@p@Ü!ƒæœ√Ù˛?–6¿âr[çL¡ã:2Bàjß!HbÛ√P‰=!1Vâ\"à≤0Öø\nS∆∆œD7√ÏD⁄õ√C!Ü!õ‡¶G åß »+í=tCÊ©.C§¿:+» =™™∫≤°±Â%™cÌ1MR/îE»í4Ñ©†2∞‰±†„`¬8(·”π[W‰—=âySÅb∞=÷-‹πBS+…Ø»‹˝•¯@pL4Yd„Ñqä¯„¶Í¢6£3ƒ¨Ø∏Ac‹åËŒ®åkÇ[&>ˆï®Z¡pkm]óu-c:ÿ∏àNtÊŒ¥p“ùåä8Ë=ø#ò·[.‹ﬁØç~†çÅmÀyáPP·|I÷õ˘¿ÏQ™9v[ñQïÑ\nñŸrÙ'gá+ê·T—2Ö≠V¡ız‰4ç£8˜è(	æEy*#j¨2]≠ïR“¡ë•)É¿[N≠R\$ä<>:Û≠>\$;ñ>†Ã\rªÑŒHÕ√T»\nw°N Âwÿ£¶Ï<ÔÀGw‡ˆˆπ\\YÛ_†Rt^å>é\r}åŸS\rzÈ4=µ\nLî%J„ã\",Z†8∏ûôêi˜0u©?®˚—Ù°s3#®Ÿâ†:Û¶˚ç„Ωñ»ﬁE]x›“Ås^8é£K^…˜*0—ﬁwﬁ‡»ﬁ~è„ˆ:Ì—iÿ˛èv2wΩˇ±˚^7ê„Ú7£c›—u+U%é{P‹*4ÃºÈLX./!ºâ1C≈ﬂqx!Hπ„Fd˘≠L®§®ƒ†œ`6ÎË5ÆôfÄ∏ƒÜ®=H¯l åV1ìõ\0a2◊;Å‘6Ü‡ˆ˛_Ÿáƒ\0&ÙZ‹S†d)KE'íÄnµê[X©≥\0Z…ä‘F[Pëﬁò@‡ﬂ!âÒY¬,`…\"⁄∑Å¬0Ee9yF>À‘9b∫ñåÊF5:¸àî\0}ƒ¥äá(\$û”áÎÄ37Hˆ£Ë MæA∞≤6Rï˙{Mq›7G†⁄CôCÍm2¢(åCt>[Ï-t¿/&Cõ]ÍetGÙÃ¨4@r>«¬Â<öSqï/Â˙îQÎçhmçö¿–∆Ù„ÙùL¿‹#ËÙKÀ|ÆôÑ6fKP›\r%t‘”V=\"†SH\$ù} ∏Å)w°,W\0F≥™u@ÿb¶9Ç\rr∞2√#¨DåîXÉ≥⁄yOI˘>ªÖnÅÜ«¢%„˘ê'ã›_¡Ät\rœÑzƒ\\1òhlº]Q5Mp6kÜ–ƒqh√\$£H~Õ|“›!*4åÒÚ€`SÎ˝≤S tÌPP\\g±Ë7á\n-ä:Ë¢™p¥ïîàlãBû¶Óî7”®cÉ(wO0\\:ï–wî¡ùp4àìÚ{T⁄˙jO§6H√ä∂r’•êq\n¶…%%∂y']\$ÇîaëZ”.fc’q*-ÍFW∫˙kçÑzÉ∞µjëé∞lg·å:á\$\"ﬁNº\r#…d‚√Ç¬ˇ–sc·¨Ã†ÑÉ\"j™\r¿∂ñ¶à’íºPhã1/ÇúDA)†≤›[¿kn¡p76¡Y¥âR{·M§P˚∞Ú@\n-∏a∑6˛ﬂ[ªzJH,ñdl†B£hêo≥çÏÚ¨+á#Dr^µ^µŸeöºEΩΩñ ƒúaPâÙıJG£z‡ÒtÒ†2«XŸ¢¥¡øV∂◊ﬂ‡ﬁ»≥â—B_%K=E©∏bÂºæﬂ¬ßkU(.!‹Æ8∏ú¸…I.@éKÕxn˛¨¸:√PÛ32´îmÌH		C*Ï:v‚T≈\nRπÉïµã0u¬ÌÉÊÓ“ß]ŒØòäîP/µJQd•{Lñﬁ≥:Y¡è2bºúT Òù 3”4Üó‰cÍ•V=êøÜL4Œ–rƒ!ﬂBY≥6Õ≠MeLä™‹Áúˆ˘i¿o–9< Gî§∆ï–ôMhm^ØU€N¿å∑ÚTr5HiMî/¨nÉÌù≥T†ç[-<__Ó3/Xr(<áØäÜÆ…ÙìÃu“ñGNX20Â\r\$^áç:'9Ë∂OÖÌ;◊kèºÜµf†ñN'a∂î«≠b≈,ÀV§ÙÖ´1µÔHI!%6@˙œ\$“EG⁄ú¨1ù(mU™ÂÖr’ΩÔﬂÂ`°–iN+√úÒ)öú‰0lÿ“f0√Ω[U‚¯V Ë-:I^†ò\$ÿs´b\reáëug…h™~9€ﬂàùbòµÙ¬»f‰+0¨‘ hXr›¨©!\$óe,±w+Ñ˜åÎå3ÜÃ_‚AÖkö˘\nk√rı õcuWdYˇ\\◊={.Ûƒçòê¢gªâp8út\rRZøvçJ:≤>˛£Y|+≈@¿áÉ€Cêt\rÄÅjtÅΩ6≤%¬?‡Ù«éÒí>˘/•Õ«Œ9F`◊ï‰Úv~K§ê·ˆ—R–WãzëÍlm™wL«9Yï*q¨xƒzÒËSeÆ›õ≥Ë˜£~öD‡Õ·ñ˜ùxòæÎ…üi7ï2ƒ¯—O›ªí˚_{Ò˙53‚˙têòõ_üız‘3˘d)ãCØ¬\$?K”™PÅ%œœT&˛ò&\0P◊NAé^≠~¢É†p∆ ˆœúì‘ı\r\$ﬁÔ–÷Ïb*+D6Í∂¶œàﬁÌJ\$(»olﬁÕh&îÏKBS>∏ãˆ;z∂¶x≈oz>Ìú⁄oƒZ\n ã[œvıÇÀ»úµ∞2ıOxŸêV¯0f˚Ä˙Øﬁ2Bl…bk–6ZkµhXcdÍ0*¬KT‚ØH=≠ïœÄëp0älVÈıË‚\rºå•ném¶Ô)(è(Ù:#¶è‚ÚEâ‹:C®C‡⁄‚\r®G\r√©0˜ÖiÊ⁄∞˛:`Z1Q\n:Ä‡\r\0‡Á»q±∞¸:`ø-»M#}1;Ë˛πãqë#|ÒSÄæ¢hlôDƒ\0fiDpÎL†ç``ô∞Á—0yÄﬂ1ÖÄÍ\rÒ=ëMQ\\§≥%oqñ≠\0ÿÒ£1®21¨1∞≠ ø±ß—úbi:ìÌ\r±/—¢õ `)öƒ0˘ë@æ¬õ±√I1´N‡Cÿ‡äµÒO±¢ZÒ„1è±Ôq1 Ú—¸‡,Â\rdIÅ«¶v‰jÌÇ1 t⁄B¯ì∞‚Åí0:Ö0ì1†A2VÑÒ‚0†ÈÒè%≤fi3!&Q∑Rc%“q&w%—Ï\rê‡V»# ¯ôQw`ã% æÑ“m*rÖ“y&iﬂ+r{*≤ª(rg(±#(2≠(Â)R@iõ-†ç àûï1\"\0€≤RèÍˇ.e.rÎƒ,°ry(2™C‡Ë≤bÏ!Bﬁè3%“µ,Rø1≤∆&Ë˛tÄ‰bËa\rLì≥-3·†÷†Û\0ÊÛBpó1Ò94≥O'R∞3*≤≥=\$‡[£^iI;/3i©5“&í}17≤# —π8†ø\"ﬂ7—Â8Ò9*“23ô!Ûè!1\\\0œ8ì≠rk9±;SÖ23∂‡⁄ì*”:q]5S<≥¡#3ç83›#e—=π>~9SËû≥ër’)ÄåT*aü@—ñŸbesŸ‘£:-ÛÄèÈ«*;,†ÿô3!i¥õëL“≤#1 ç+n¿ ´*≤„@≥3i7¥1©û¥_ïFëS;3œF±\rAØÈ3ı>¥x:É \r≥0Œ‘@í-‘/¨”w”€7ÒÑ”SëJ3õ Á.FÈ\$O§Bí±ó%4©+t√'gÛLq\rJtáJÙÀM2\rÙÕ7Ò∆T@ì£æ)‚ì£dç…2ÄP>Œ∞ÄùFi‡≤¥˛\nr\0û∏bÁk(¥D∂ø„KQÉ§¥„1„\"2tîÙÙ∫PË\r√¿,\$KCtÚ5Ùˆ#Ù˙)¢·P#Pi.ŒU2µCÊ~ﬁ\"‰");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:õågCIº‹\n8ú≈3)∞À7úÖÜ81– x:\nOg#)–Ír7\n\"ÜË¥`¯|2ÃgSiñH)N¶Së‰ß\ráù\"0πƒ@‰)ü`(\$s6O!”ËúV/=ùå' T4Ê=ÑòiSòç6IO†G#“X∑VCç∆s°†Z1.–hp8,≥[¶H‰µ~Czß…Â2πlæc3öÕÈs£ëŸIÜb‚4\nÈF8T‡ÜIò›©U*fzπ‰r0ûE∆Å¿ÿyé∏ÒféY.:ÊÉIå (ÿc∑·Œã!ç_lôÌ^∑^(∂öN{Sñì)rÀq¡YìñlŸ¶3ä3⁄\nò+G•”Íy∫ÌÜÀi∂¬ÓxV3w≥uh„^rÿ¿∫¥a€î˙πçcÿË\rì®Î(.¬à∫ÅCh“<\r)Ë—£°`Ê7£ÌÚ43'm5å£»\nÅP‹:2£Pª™éãq Úˇ≈Cì}ƒ´à˙ ¡Í38ãBÿ0éhRâ»r(ú0•°b\\0åHr44å¡Bç!°p«\$érZZÀ2‹â.…É(\\é5√|\nC(Œ\"èÄPÖ¯.ç–NÃRT Œì¿Ê>ÅHNÖÅ8HP·\\¨7Jp~Ñ‹˚2%°–OC®1„.ÉßC8ŒáH»Ú*àj∞Ö·˜S(π/°Ï¨6KUú á°<2âpOIÑÙ’`ç‘‰‚≥àdOÅH†ﬁ5ç-¸∆4å„pX25-“¢Ú€à∞z7£∏\"(∞P†\\32:]U⁄ËÌ‚ﬂÖ!]∏<∑A€€§í–ﬂi⁄∞ãl\r‘\0v≤Œ#J8´œwmûÌ…§®<ä…†Ê¸%m;p#„`XùDå¯˜iZç¯N0åêï»9¯®Âç†¡Ë`ÖéwJçDøæ2“9tå¢*¯ŒyÏÀNiIh\\9∆’Ë–:ÉÄÊ·xÔ≠µyl*ö»àŒÊY†‹á¯Í8íW≥‚?µéÅﬁõ3Ÿ !\"6Âõn[¨ \r≠*\$∂∆ßænzx∆9\rÏ|*3◊£pﬁÔª∂û:(p\\;‘Àmz¢¸ß9Û–—¬å¸8NÖ¡êj2çΩ´Œ\r…HÓH&å≤(√zÑ¡7i€k£ ãä§Çc§ãeÚû˝ßtúÃÃ2:SHÛ»†√/)ñxﬁ@ÈÂtâri9•ΩıÎú8œ¿ÀÔy“∑Ω∞éVƒ+^W⁄¶≠¨kZÊYól∑ £ÅÅå4÷»∆ã™∂¿¨Ç\\E»{Ó7\0πpÜÄïDÄÑiî-TÊ˛⁄˚0l∞%=¡†–ÀÉ9(Ñ5\n\nÄn,4á\0Ëa}‹É.∞ˆRsÔÇ™\02B\\€b1üS±\0003,‘XPHJspÂdìKÉ CA!∞2*Wü‘Ò⁄2\$‰+¬f^\nÑ1åÅ¥ÚzEÉ Iv§\\‰ú2…†.*A∞ôîE(d±·∞√bÍ¬‹Ñê∆9áÇ‚Ä¡Dhê&≠™?ƒH∞sèQò2íx~n√ÅJãT2˘&„‡eRúΩôG“QéêTwÍ›ëªıPà‚„\\†)6¶Ù‚ú¬Úsh\\3®\0R	¿'\r+*;RH‡.ì!—[Õ'~≠%t< Áp‹K#¬ëÊ!ÒlﬂÃLeå≥úŸ,ƒ¿Æ&·\$	¡Ω`îñCXöâ”Ü0÷≠Âº˚≥ƒ:MÈh	Á⁄úG‰—!&3†DÅ<!Ëê23Ñ√?h§J©e ⁄h·\r°mïòNi∏£¥éíÜ NÿHl7°ÆvÇÍWIÂ.¥¡-”5÷ßeyè\rEJ\ni*º\$@⁄RU0,\$UøEÜ¶‘‘¬™u)@(tŒSJk·p!Ä~≠Ç‡d`Ã>Øï\n√;#\rp9Üj…π‹]&Nc(rÄàïTQU™ΩS∑⁄\08n`´óyïb§≈ûL‹O5ÇÓ,§Úûë>éÇÜx‚‚±f‰¥í‚ÿê+Åñ\"—IÄ{kM»[\r%∆[	§eÙa‘1! ËˇÌ≥‘Æ©F@´b)Rü£72àÓ0°\nW®ô±L≤‹ú“Ætd’+ÅÌ‹0wgl¯0n@ÚÍ…¢’iÌM´É\nAßM5nÏ\$E≥◊±N€·l©›ü◊Ï%™1 A‹˚∫˙˜›kÒrÓiFB˜œ˘ol,muNx-Õ_†÷§C( ÅêfÈl\r1p[9x(i¥B“ñ≤€zQl¸∫8C‘	¥©XU Tb£›I›`ïp+V\0Óã—;ãCbŒ¿XÒ+œíçsÔ¸]H˜“[·kãx¨G*ÙÜè]∑awn˙!≈6ÇÚ‚€–mSÌæìIﬁÕKÀ~/ù”•7ﬁ˘eeN…Úç™S´/;dÂAÜ>}l~ûœÍ ®%^¥fÁÿ¢p⁄úDEÓ√a∑Çt\nx=√k–éÑ*d∫ÍTó∫¸˚j2ü…júù\në†… ,òe=ëÜM84Ù˚‘aïj@ÓT√sè‘‰nf©›\nÓ6™\rdúº0ﬁÌÙYä'%‘ìÌﬁ~	Å“®Ü<÷ÀñAÓãñHøGÇÅ8ÒøùŒÉ\$z´{∂ª≤u2*Ü‡añ¿>ª(wåK.bPÇ{ÖÉo˝î¬¥´zµ#Î2ˆ8=…8>™§≥A,∞e∞¿Ö+ÏCËßxı*√·“-b=máôü,ãaí√lzkùÅÔ\$Wı,êmèJiÊ ß·˜Å+ãË˝0∞[Øˇ.R sK˘«‰XÁ›ZLÀÁ2ê`Ã(ÔC‡vZ°‹›¿∂Ë\$Å◊π,ÂD?H±÷NxXÙÛ)íÓéM®â\$Û,çÕ*\n—£\$<qˇ≈üh!øπSì‚É¿üxsA!ò:¥K•¡}¡≤ì˘¨£úR˛öA2k∑Xép\n<˜˛¶˝ÎlÏßŸ3Ø¯¶»ïVV¨}£g&Y›ç!Ü+Û;<∏Y«ÛüYE3r≥ŸéÒõCÌo5¶≈˘¢’≥œkk˛Ö¯∞÷€£´œt˜íU¯Ö≠)˚[˝ﬂ¡Ó}Ôÿu¥´lÁ¢:Dü¯+œè _o„‰h140÷· 0¯Øb‰Kò„¨í†ˆ˛ÈªlG™Ñ#™ö©ÍéÜ¶©Ï|UdÊ∂IK´Í¬7‡^Ï‡∏@∫ÆO\0H≈Hiä6\rá€©‹\\cg\0ˆ„Î2éBƒ*e‡ê\nÄö	Özrê!ênWz&ê {Hñ'\$X †w@“8ÎDGr*Îƒ›HÂ'p#éƒÆÄ¶‘\nd¸Ä˜,Ù•ó,¸;g~Ø\0–#ÄÃé≤Eè¬\r÷I`úÓ'É%E“.†]` –õÖÓ%&–Óm∞˝\r‚ﬁ%4SÑv#\n†ûfH\$%Î-¬#≠∆—qB‚ÌÊ†¿¬Q-Ùc2äßÇ&¬¿Ã]‡ô Ëqh\rÒl]‡Æs†–—h‰7±n#±ÇÇ⁄-‡jEØFrÁ§l&d¿ÿŸÂzÏF6∏êà¡\"†ûì|øß¢s@ﬂ±ÆÂz)0rp⁄è\0ÇX\0§ŸË|DL<!∞ÙoÑ*áD∂{.B<E™ãã0nB(Ô é|\r\nÏ^©ç‡ç h≥!Ç÷Ír\$ßí(^™~èËﬁ¬/pèq≤ÃB®≈Oöà˙,\\µ®#RRŒè%Î‰Õd–Hjƒ`¬†ÙÆÃ≠ VÂ bSídßiéEÇ¯Ôoh¥r<i/k\$-ü\$oîº+∆≈ãŒ˙l“ﬁO≥&ev∆íºi“jMPA'u'éŒí( M(h/+´ÚWDæSo∑.n∑.n∏ÏÍ(ú(\"≠¿ßhˆ&pÜ®/À/1DÃäÁjÂ®∏EËﬁ&‚¶Äè,'l\$/.,ƒd®ÖÇWÄbbO3ÛB≥sH†:J`!ì.Ä™Çá¿˚•†è,F¿—7(á»‘ø≥˚1älÂs ÷“éë≤ó≈¢q¢X\r¿öÆÉ~RÈ∞±`Æ“ûÛÆY*‰:R®˘rJ¥∑%Lœ+n∏\"à¯\r¶ŒÕáH!qbæ2‚Li±%”ﬁŒ®Wj#9”‘ObE.I:Ö6¡7\0À6+§%∞.»Öﬁ≥a7E8VSÂ?(DG®”≥BÎ%;Ú¨˘‘/<í¥˙•¿\r Ï¥>˚M¿∞@∂æÄH†Ds–∞Z[tH£Enx(å©R†xÒè˚@Ø˛GkjWî>Ã¬⁄#T/8Æc8ÈQ0ÀË_‘IIGIIí!•äYEdÀE¥^ètdÈth¬`DV!CÊ8é•\r≠¥übì3©!3‚@Ÿ33N}‚ZBÛ3	œ3‰30⁄‹M(Í>Ç }‰\\—tÍÇf†fåÀ‚I\rÆÄÛ337 X‘\"tdŒ,\nbtNO`P‚;≠‹ï“≠¿‘Ø\$\nÇûﬂ‰Z—≠5U5WUµ^ho˝‡ÊtŸPM/5K4Ej≥KQ&53GXìXx)“<5DÖè\r˚VÙ\nﬂr¢5b‹Ä\\J\">ßË1S\r[-¶ Du¿\r“‚ß√)00ÛYı»À¢∑k{\nµƒ#µﬁ\r≥^∑ã|Ëu‹ªUÂ_nÔU4…Uä~Yt”\rIö√@‰è≥ôR Û3:“uePMSË0TµwWØX»ÚÚD®Ú§KOU‹‡ïá;Uı\n†OYçÈYÕQ,M[\0˜_™DöÕ»W†æJ*Ï\rg(]‡®\r\"ZCâ©6uÍè+µYÛàY6√¥ê0™qı(ŸÛ8}êÛ3AX3T†h9j∂j‡fıMtÂPJbqMP5>è»¯∂©Yák%&\\Ç1d¢ÿE4¿ µYnê Ì\$<•U]”â1âmb÷∂ê^“ıö†Í\"NVÈﬂp∂Îpı±eM⁄ﬁ◊WÈ‹¢Ó\\‰)\n À\nf7\n◊2¥ır8ãó=Ek7tVöáµû7P¶∂L…Ìa6ÚÚv@'Ç6i‡Ôj&>±‚;≠„`“ˇa	\0p⁄®(µJ—Î)´\\ø™n˚Úƒ¨m\0º®2ÄÙeqJˆ≠PçÙtåÎ±fj¸¬\"[\0®∑Ü¢X,<\\åÓ∂◊‚˜Ê∑+mdÜÂ~‚‡öÖ—s%o∞¥mn◊),◊ÑÊ‘á≤\r4∂¬8\r±Œ∏◊mEÇH]Ç¶ò¸÷HW≠M0DÔﬂÄóÂ~èÀÅòKòÓE}¯∏¥‡|fÿ^ì‹◊\r>‘-z]2sÇxDòd[sátéS¢∂\0Qf-K`≠¢Çt‡ÿÑwTØ9ÄÊZÄ‡	¯\nB£9 Nbñ„<⁄B˛I5o◊oJÒp¿œJNdÂÀ\rçhﬁç√ê2ê\"‡xÊHC‡›çñ:ç¯˝9Yn16∆Ùzr+z±˘˛\\í˜ïúÙm ﬁ±T ˆÚ†˜@Y2lQ<2O+•%ìÕ.”Éh˘0AﬁÒ∏ä√Zãè2R¶¿1£ä/ØhH\r®XÖ»aNB&ß ƒM@÷[xåá Æ•Íñ‚8&L⁄VÕúv‡±*öj§€öGHÂ»\\ŸÆ	ô≤∂&s€\0Qö†\\\"Ëb†∞	‡ƒ\rBsõ…wùÇ	ùŸ·ûBN`ö7ßCo(Ÿ√‡®\n√®ùì®1ö9Ã*Eò ÒSÖ”Uê0U∫ tö'|îmô∞ﬁ?h[¢\$.#…5	 Â	pÑ‡yB‡@RÙ]£ÖÍ@|Ñß{ô¿ P\0xÙ/¶ w¢%§EsBdøßöCUö~O◊∑‡P‡@X‚]‘Öç®Z3®•1¶•{©eLYâ°å⁄ê¢\\í(*R`†	‡¶\nÖä‡é∫ÃQCF»*éππê‡Èú¨⁄pÜX|`N®Çæ\$Ä[Üâí@ÕU¢‡¶∂‡Z•`Zd\"\\\"ÖÇ¢£)´áIà:ËtöÏoDÊ\0[≤®‡±Ç-©ì†gÌ≥âôÆ*`hu%£,Äî¨„Iµ7ƒ´≤HÛµm§6ﬁ}Æ∫N÷Õ≥\$ªMµUYf&1˘é¿õe]pz•ß⁄I§≈m∂G/£ ∫w ‹!ï\\#5•4I•dπE¬hqÄÂ¶˜—¨kÁx|⁄k•qDöbÖz?ß∫â>˙Éæ:Üì[ËL“∆¨Z∞XöÆ:ûπÑ∑⁄ç«jﬂw5	∂YÅæ0 ©¬ì≠Ø\$\0C¢ÜdSg∏ÎÇ†{ù@î\n`û	¿√¸C ¢∑ªM∫µ‚ª≤# t}xŒNÑ˜∫á{∫€∞)Í˚CÉ FKZﬁjô¬\0PFYîB‰pFkñõ0<⁄> D<JEôög\rı.ì2ñ¸8ÈU@*Œ5fk™ÃJDÏ»…4çïTDU76…/¥ËØ@∑ÇK+Ñ√ˆJÆ∫√¬Ì@”=å‹WIOD≥85MöçN∫\$RÙ\0¯5®\r‡˘_™úÏEúÒœI´œ≥NÁl£“Ây\\Ùëà«qUÄ–Q˚†™\n@í®Ä€∫√pö¨®P€±´7‘ΩN\r˝R{*çqm›\$\0Rî◊‘ìä≈Âq–√à+U@ﬁB§ÁOf*ÜCÀ¨∫MCé‰`_ Ë¸ÚΩÀµNÍÊT‚5Ÿ¶C◊ª© ∏‡\\W√e&_Xå_ÿçhÂó¬∆Bú3¿å€%‹FW£˚Å|ôGﬁõ'≈[Ø≈Ç¿∞Ÿ’V†–#^\rÁ¶GRÄæòÄP±›FgÅ¢˚ÓØ¿Yi ˚•«z\n‚®ﬁ+ﬂ^/ì®ÄÇº•Ω\\ï6Ëﬂbºdmh◊‚@qÌç’Ah÷),J≠◊Wñ«cm˜em]é”èeœkZb0ﬂÂ˛ûÅYÒ]ymäËáfÿeπB;π”ÍO…¿wüapDW˚å…‹”{õ\0ò¿-2/bN¨s÷ΩﬁæRaìœÆh&qt\n\"’iˆRm¸hzœe¯Ü‡‹FS7µ–PPÚ‰ñ§‚‹:Bßà‚’sm∂≠Y d¸ﬁÚ7}3?*Çt˙ÚÈœlT⁄}ò~ÄÑèÄ‰=cû˝¨÷ﬁ«	û⁄3Ö;T≤Lﬁ5*	Ò~#µAïæÉëséx-7˜éf5`ÿ#\"N”b˜ØGòüãı@‹e¸[Ô¯Å§ÃsëòÄ∏-ßòM6ß£qqö hÄe5Ö\0“¢¿±˙*‡b¯IS‹…‹FŒÆ9}˝p”-¯˝`{˝±…ñkPò0T<Ñ©Z9‰0<’ö\r≠Ä;!√àg∫\r\nK‘\nïá\0¡∞*Ω\nb7(¿_∏@,Óe2\r¿]ñKÖ+\0…ˇp C\\—¢,0¨^ÓM–ßö∫©ì@ä;X\rï?\$\rájí+ˆ/¥¨BˆÊP†Ωâ˘®J{\"aÕ6ò‰âúπ|Â£\n\0ª‡\\5ìÅ–	156ˇÜ .›[¬UÿØ\0dË≤8YÁ:!—≤ë=∫¿X.≤uC™äåˆ!S∫∏áoÖp”B›¸€7∏≠≈Ø°Rh≠\\hãE=˙y:< :u≥Û2µ80ìsi¶üTsB€@\$ ÕÈ@«u	»Q∫ê¶.ÙÇT0M\\/ÍÄd+∆É\në°=‘∞då≈ÎA¢∏¢)\r@@¬h3ÄñŸ8.eZa|.‚7ùYk–c¿òÒñ'D#á®YÚ@Xçqñ=M°Ô44öB AM§ØdU\"ãHw4Ó(>Ç¨8®≤√C∏?e_`–≈X:ƒA9√∏ôÅÙp´G–‰áGy6Ω√FìXrâ°l˜1°ΩÿªêB¢√Ö9Rz©ıhBÑ{çûÄô\0ÎÂ^Ç√-‚0©%Dú5F\"\"‡⁄‹ ¬ô˙iƒ`ÀŸnAf® \"tDZ\"_‡V\$ü™!/ÖDÄ·öÜøµã¥àŸ¶°ÃÄF,25…jõTÎ·óy\0ÖNºx\rÁYl¶è#ë∆Eq\nÕ»B2ú\nÏ‡6∑Öƒ4”◊î!/¬\nÛÉâQ∏Ω*Æ;)bR∏Z0\0ƒCDoåÀûé48¿ï¥µá–eë\n„¶S%\\˙PIkêá(0¡åu/ôãG≤∆πäåº\\À}†4FpëûG˚_˜G?)g»otÅ∫[vû÷\0∞∏?b¿;™À`(ï€å‡∂NS)\n„x=Ë–+@Í‹7Éèj˙0èó,1√Özôì≠ç>0àâGc„LÖVXÙÉ±€ %¿Ö¡ÑQ+¯éÈo∆Fı»È‹∂–>Q-„cë⁄«lâ°≥§w‡Ãz5GëÍÇ@(hëc”Hı«r?àöNb˛@…®ˆ«¯∞Ólx3ãU`Ñrw™©‘U√‘Ùtÿ8‘=¿l#Úıèlˇ‰®â8•E\"åÉòôO6\nò¬1e£`\\hKfóV/–∑PaYKÁOÃ˝ Èè‡xë	âOjÑÛèr7•F;¥ÍÅBªëÍ£ÌÃíáº>Ê–¶≤V\rƒñƒ|©'Jµz´ºöî#íPB‰íY5\0NC§^\n~LrRí‘[ÃüR√¨Òg¿eZ\0xõ^ªi<Q„/)”%@ êíôfB≤Hf {%P‡\"\"Ωç¯@™˛ç)ÚíëìDE(iM2ÇSí*ÉyÚS¡\"‚Ò eÃí1å´◊ò\n4` ©>¶èQ*¶‹y∞nîíû•T‰u‘ù‚‰î—~%Å+WÅ≤XKãå£Q°[ îû‡lêPYy#DŸ¨D<´FL˙≥’@¡6']∆ãá˚\rFƒ`±!ï%\nè0êc–Ù¿À©%c8WrpGÉ.TúDoæUL2ÿ*È|\$¨:ÁÅXt5∆XY‚Iàp#Ò ≤^\nÍÑ:Ç#D˙@÷1\r*»K7‡@D\0é∏CíC£xBh…EnKË,1\"ı*y[·#!Û◊ô‚Ÿô© ∞l_¢/ÄˆxÀ\0‡…⁄5–Z«ˇ4\0005J∆h\"2àåá%YÖÅ¶aÆa1S˚Où4à %ni¯öPå‡ﬂ¥qÓ_ Ω6§Üƒ6„Ò\n@PjU˙\0µÉ`r;πHîÄ¥Ç¢ãõ:˜‚∂®4 _w*¯@F@%∏âs[öd◊eÅÙ”bhø\0‚…±P\r†\\i¿Jß99P9Œ^sÅ.ú‚P29©\nNj#,¿Ä⁄5ÅàÌM)ëˇB¶ô≥\ni%~úÉ∏ß:9œŒX\rìe–Ë8≥âÓe”Ω+Ô¿Á9¡µ‚xÅ*úŸÄW2·NêbaÁíS‡Eºï2ÑË\r≥¨≈ÊpÍ	ÓÃ\\(/	Lf‡ ÚYß‰X#8ZJƒÉH Ñ+P‡-I1x…à¢36‡N¢w\r”¡Ä[x3˝>\rTOôb·>s…≤0ÍÖåjAÜ8;ÿ#—òã§≥‡À¬jPdâqRÅJ“\"èá(xáåö°hµ*ƒÛ	T¶ÈaV„ÆY∆å∆Î\$Äò¿Ó7íZ9ƒ∏Ü1ÃöXJ‡ÄÈaÔAOk8fDãC96@·¬ÈMÍ(HßéÕ„–B∫‡ì?ÇiºTAP‹≠ò^0¥P¿µaf/ëœçîP0ÕMH)\"°dU@πr1\\—\rŸoH|†‡«≈…h◊8Ö@à?PíëZ,A>¬Æ ˙E(ê&áøeòôÕû]ÂQ\$∏ÑÇçÑÂ–™Z°}aø§àÃô:Pπw:ëƒ(Ë¢Zòù !8∞¥´Å≠‡n@9Å\$ﬁ£(K\"ê˛îÓñ%≈¶Õò@2âÁí\$P∞û<«∫\0ıóÁÅ¶JtUXP\"-A‘…¶Yk÷2Û—ˆô4œC\n´\0∂Ω†2á˝~ƒs_…˛\0˜N5ºï“úË—/†”ÄI…;õâ¬i∏¶ƒ÷óefkF<«rÑEÏ,Œ6%?®Iój;'S)MÉ¡ßÖ4)ÕNÄ.ú~Ëâ˘ùÈÔ\0Jä”îëı3©„Qzz	î?ıßm1°™∫ëqí	cQHò‹ØyL\"OœÖ0|c\$P \"ïœ≈r0eLåòm#d¬px.uA®^ÈBò76¨¬qn€◊çëB¯nëÊiZvR@Ô)*Ä„åÅq∆íˇ)Ù˝7^öIµ°jI“S5ó3à§çÈ™ÍäÜ8⁄∫ë◊ƒxÅ9	àLqÑ–LƒOA⁄A\0001ë¢™%Ü!1-‚∑WêÅ“éÖ%#!5+≥•Æ°˜!úvue(®Bp∏\nK≈/ÑŸ–„◊∆\\€iœôÊ\0^¿\$Å,†|êZ“Ä(RÜ+ké‡\n++⁄ÿV G§{/TÕ<Ñ÷M¶√™ö√¬¢©Å∞\$‰{–¥ÍÃÄyÏâVt‰ +°S—Z¬Ä§(uÜ x\"HC∑J‰? v8éJ˜P¬ Q\0˘V1¿·#É†'_·\n∫4%Ñ«•\nza_ä≤√PDD{¨Ä+\$Sz ÷Ö? l¨ ç´®2z¥í!=¡OD–Îﬁ[Òb\0ÈK ƒÆÕtjó+™(Ñ“î5Ë.ô‚k£ZÇF÷≠=A∫Æ≠U◊ö£0©C÷ÑÀÍ–«◊~∆v.≠8ä+Rx[¨¬∫Àÿ≤≈¶∑Auﬁ·I8‰¨é3äﬂÆƒ '	iéfˇ‡.Jê àT¢Ôò‹X11§¯Ää&3ëÏ6™óë	Ú–f@|O`bÆùg\0ª>Üœ÷xùkkMD÷QÑ\n¨µëÒhß—¯üa¿y\$t¿»`\"êÃ5Åø≤…56ÄÅÅ| `&è¥¿:T≈Aåí\n≠À•†ñ©pjR˘â“I*ÉÁQ¶®±ï£aNÂÆZÊ_Z†q‚¥©òôõáG9\0åø±ÏèÂ(ƒ∞=J˙Åù dGòÌÌè9r’Í,Qpÿ+kZ°\$û◊I+ëêÑÑ(ô«5ÃÄ{2Ì‹_m«ÀÜ8ä¨eØ¿Ènı¶ÖÆ\\6≈äç∂å\${Xñ÷K\$∑£#kôU⁄Ì+vÊvEØm◊nÿÍòvOË	!Adt£ó_/¥(6ı1⁄ï≠Òm[„å¶º‹Ó\$¯TŒ±h÷d‹ÕX¯¿à÷/7Í†°B¢ ‰ó-\$¿ÆUr…>b*)Ã∂ZﬁXnbƒ\n™ÊêESŒùpoeóì∂ép\\∏¢DÜ Ü®EÕ#¡,â§T~Í.ÖPËÁm)aé∫=√≥RÑÙ∑E∂í<çürı6åàgHE-tªÎ¥∫RÌvZtF+m[∏“Óuœ:‡ì7wí˜]Óﬂ,`›‡-Æw´¬9“—aØÿ„o€À≈[DM∞˝Ö›€ÔoeÒÅrq6≥Hë“‚»ò!*ütehÌ¯Ö^Ë îπçIê…M◊ƒë\"DAÂÿ\$ú\0oHèäÃúçApç˙EŸZL¢äó}\"ˆˆ:Ûè|‡ÂØ6Ëó|=n•™úÎáf∂cŒ–vßJ]ëA5cÖHñ¯8ÛÛû∂-´æù‚ÌâOÀVBV•#–¥ëÚÄ`ù”“\r˝Ç†-º	ÿKBdâGÖ^Ù+˝¿.∑™ElˆÀ\$\$(qÈ0|9(äòhïõ{\n4a7B‹P\0n@-h…oW‡Ä¢º `¡+^jƒ‡dŒÄ9cPÚq1⁄‡H\" ÃÊ\\ –¡í±!µÜ∞\".⁄§øæ‘µ¢E<’/éïáz}éÇ±(∂XD.6?Nxk*,)Àl√Wß9Ü	j\\IÊŒ(J¬¯Ê≠@;‹1Øñ‡¿\nöIxÄ‘√Ø‡h\rI[:˙¨ÀàHÄ5/õvBuüPfu¡6´!4≥xl‚Ã2—€ïº≥^ Ï›g\0§ŸÀòâ_q¯∞~4I—O\"Ì-xúD∫”b\\\"¬-_£r»î§üßG\"¿bïa{Oê™ﬂR⁄v’rÑqKÇ\0\$˙m”b≈–NAt@ù)Uï£∞Æ–èpíjÚ£˝v»º,9ï ÑÍ‘*T~›LõßΩd—ªKÉg¿™P…Lú˝™ºF˚2ﬂ˙ÅP*,uW—˚*Z∂œ˙ÅUpUài\0dì]œˇ\rGw\n@`–Ä∏©kí!Äq÷g‰‚ßEÚ»HE‡£@©¸]y2sˇ«eøÚ%éü√\"î¡√\\ˇOù?¸z+∂Ç‘4¢;uz–Å0d7±˛FÀ‰ –<d…ˆ2çuŒ9í‚ù¬W\$y9˝®\0P‹Äd¿,»-ˆ¿∑[Ê∆’Òh|BQ ß·ê5“ô…Â¯ÿ©ì<óír\0Æt;2Ñà˚ÓÑfî9T™é=@Áès:‰÷…ò˙ÒL·vÀ˜©X@WoN ÖWí˙\$DÑD7¯Ôe€÷Â÷:(Ÿvå∑∞ò/©óä¬r\rAî∆†\n≈z3|πŸò™Üz^ev/€y°ÿ^5áåGµÌ0BäÜÅ∂ˇmû`¿ºvlÅ‡ñnÁnæR>\nYTcƒ‘b¨∑P\\êrPcﬂcx7c•òıDÜ={*èdrôï8Â©Ô©wÎŒÅ‹Ü=R6_õï∆úåNy•æ`&∑·\$äH∞‘GÓkÀ4Y|ª”/ÀŸ≥∆@ÈÂ“§‡sŒ≠˘¬÷¨ÓÁR\"y÷[ÓzGoü%Gg“˝¯é{Äœü∫.ôÔ¿9rôÔ£ôcæ\\UÜâáŒ5‚ÓCÄ»ÈÇ\"ÑÆ)L◊åÀIﬂﬂÜkøÿ\rØ¸iõå(Ìœπ-¥Â˙\\dÑ‹&rˆ|ÂfÊ√Óó–PﬁeMÈüI⁄Äbc0MlÈCæ∞—OZ9Ü&éÙzùó∏ïµºõHKÇXË–éÈ%∑öAauR≈§ÒwÈI=∫KYÉüÚ¥óDe∏¸ÕÄ\rÅﬁà1•Dº\"OmuLèo≈C\\Åm!ÄsÀT\0Ët∫•Ñ|¢uKµù)ôÙ»Ë≤ÖZ2∏XoM|CõÂ©–h/ÑË∏Ù‚ûÅ!áF‘®ê(ëçÌJÒú\0áHÅSz3Ú¥ú›(f¸Jÿ4ﬁ£›8ÑcbŸ\$§Â€©RÉÍ`öö†iÄﬁ∫ä.\0¸‰Å?‡lÇ[6«D®∫H÷Ü√ÚÇR[¿áe<q≥ÆìÖ…;å©ÍïÒ˙ß‘pKtf`/¿ªˇ‘§z\r›´-MiËÕ¢LÄJâÆ,±ÎJC⁄‘‘ ı±f∞é”ß[öØ÷ˆ•⁄≤,-Y⁄á]!y nT≈◊ Bl∑ﬁÑ\$zUcu°É\$¶j>72’,4.Ü‘Êè!£ÌQ∂ÛD+ÏFä‡Û◊üÁ°Õ[\n6¡So8ÎM)ÆLeŸ¥Ø™\r,Ïe=ª\r˘¶Ô «-êhãõ#∫M¥*=OÄ∂’\n∂Ñ#D¡´ÍQÑ+a‰OÇª-Ss1+[@(‰Õ·3|ëÏêrñ®FêÊãÑ=iJπ£⁄2&—sã\rOÌ\$!l–ÆDÏ¿â‰Bt…˛i¿∏Rq;Õâ@áP°∂‰WP>?Ä=r”◊ünCs,îÑ¿;B‡oÍ¸Mƒm¨}≠Êy¡àMî§üˇÀπ-€›>y,gü6†qí„ÑÒÇ\"∏q3|dÓÂ;òÏbÓF7–	Îä´@Èéˆ?é∆v@	¿∏ERUÏê ˚&I\\}-X†∫õßgG4∞]g6óö‘Ç>ËÎ∑\0Õ:∫≥\"jWP‰{±gÇÅ¿O\\3ìÃ›¯\n“\r“ ôà,ﬂDﬂ¢Å9«\0	‡O}jC⁄∑‘LêÁ|	Hºê6øõ˝∞ÌårõTFûˇˆ±ú≠!∑ÄS+ûråÏ‘Ùåò“c3ü¡B@XdT6&˜¡«éG∆gínî8±∆ëèz|)Åá Vä˚^È‹	ù©-\0Ó8Ù∫∂-´8bª7 -Ä/ê@ú÷ê>V¡¨∂+uÓà§\0BΩzl%5◊∂·æäOJÓûÓé!«·÷≤@¯x§h‰7 º!ê1ê8öSRü\0Q*o˜8æn*ë?_Ë◊ÿ\nxŒÏƒT”9®˛ñ°Âú¸nÆ4,7oﬁ^»N]¥d∫q·ñ1#e°(vü¨â≤Ïÿ,Ω∏öms.8˜T≈WgB>`œLÎ@¯ﬁ’\\≠y‰¿n\nNqä¥1ÜE=h4<”æ\$»sAÒ‚áu3 Bå±Ê:ß@·uÜ2öA=≥—\\B-uM——DnWﬂdÒVÖ÷TlrR¿ä≤Î“û‹Ug»\rö§ûùßıâ”{FÎ>A«Cû'ß	’Â2âÄ¥Ñµü•bÄôó°ÑbÕå–dßY/õ|nr\rìõS‰Sk*¯AO¶“R)ü∆;ôs¡‘î\$w\$)EÑÔAiæÈ∞†îQ 1ù›î™Îê∆D3%‚Ô É¶À*2rñ€PLös,é;ﬁug+ùútîh∞bÒç∂LÛ»¯%˝≈rCô|õZÆÁ·ã«Nâ*›–*5;€°˘UØA≤{–Ü§Ù~yÈiKX¢í⁄îDé‰#¢2CJYıíùë≤í÷˘>zS≤CU£ıcß˚ıÍOR‘æ°0ç)ÿ+“˙:-INåáØ£|àeœGâ;€bÿ»\$,p0Ùë_L.≈Ã\$ƒãÚv±—S‹ñF1&U∞À(	àánxtß¢ÊëdÔ@0˘Å® Â±ı‰/wcÒˆ_Rƒ2∑fï—≠eƒ™Ë\0=ı„sÓ°¡bsCO4◊t~ßhú(¢o}OUÚÌÆÇ_hÄ‘Ïpê‘‘—ÚÎxÌß◊\$?!–Bw≥Gƒ9 GàÏÊ∏¶˜√ÌV?{XÓn◊Sù~ó¶_1ÿ˜≈¢qíîU{#x\nN \$Å8ÄE¿îq›~•í7†!ã¿i!Ò•nˆqi\r\$ÑÈk®û£ÙÛ∫Q◊√Ld	“Sœ‹tpA9ˆ·/[˙sﬂ\0úÿ6Vv,ˇÉı‘±•°'›`Í?CÇsähctH\"ÈKæ}n¶ÂÛ•'Æ¸ÎíªÖê^ß3™¢ƒ_M£%’o¯§ÜÈÉÑÁVOÕ‹Ÿøù£´›EÎ\nç£rpTºîLâ|`eÒ—∫ ıöA≤j‰:d|[·€é‚ΩåóêëJÚ˙Ú4îl N±u4]l¥M≥H&µ§\$‰\0YR¿îqzWƒò@‹ˇ±Å¢Ìe3°'t|∑ø.∫“ﬁ`(ÒI<ƒü2§_5ì)%õÑô¢G–√m\0P\nÔmËo@ÑÕ>ÉΩ≥xB\"Ò“Em|Ö˘2ä\$},3LçYXçgo°\$ﬂ∂ <Å”˛õøIE\"`◊˙®4·g©8^£]\nà°ó:¯õqVèT‘£“m∞mÉ˘7&“ìƒ§√m”ˇ&¿®¿Qzõ√—òΩ∑≥≈±ÌHä‘ÎˆyOÁf˝´\rŸ£.¢∏∂áÆ@æJW&ﬂq◊5Å0	‘5¿ÓPÀGãÅ\nΩ≥Ì∏ü∆F≠{\0\r≤mö@†@ ÅP† xë4i4Ü+@\0,Õö\\ñC1”éË\nïLÍ≈”>nÇ\0ˇ‚‚	 #ã«ﬁÈƒ“#@]/4JRú IR≤ÔpËπ<†«ØÚajÑê?)MvÌ†2X|@v\0a∫Á\"≠œÑúùk¯®È-¬yA[|¿7\röí\$Ï¿⁄ÛZ«≠R‡t˘ûí>ú™œ·CErL	ˆ∆r”O™e†R/Å‡¢J∑‰~ì%Xoö4·µdU\"¶Qr∫áIÍ∫QDÂÚÄ§–ËQQM}‡Qø{)ÿ©≠\",fÄ–_(,Ω6‡Q+cØÆàÑ&îSÒë˘›~OÌp·êCÄ∫ØÕ⁄©ƒ˘¥V˛úÒöÕÒ@1Ë[Ÿ<H/ ~‘\0^C†≥T“ıÇq_gP¡úpe˛è@B¡◊—¿È˙«ÎÅ†p»ø∫)Xﬂ„\0ßıﬂîíÒÄ{¸`ä\0vî¸ßüŸ≥Q®ê´“@~†Áø°˙øÌ≈T∆ÅWÚñ˚ˇøŒáÙü€¸Æâõ˙ﬂÏˇO˜>‚8&ÄõﬁˇCL›ë¶ˇ(ÄØÛè(ìˇß«è2˚Ï\r%Ç;‡kÊäê4˚®_OÕæ¯5≥ˆ`@<˝≤º/‹7Ã_	Ä6'AY´ˇ\"∂˝aS∞øz£kpÔõæÆ4ö+h@Zˇ√Ù†8>Æ˝‚ÅoﬂîLˇ˚ø•ò¿jÃs˘¿ˇ\rJäÿm±¿\0L\0cÂ?¬≥¸m™áNÉ(Ø˜†⁄Tp#Ä‡É|†>¿î˛©A[?å[˚≈ø∑HkÔ¸®¬Ñ\n°tøîp:ÜG¨œı>æÄT {*®ÿ-°t¿‘ˇŸP¿˙XÎj•Nç4ä‹¶0\n\$¯ë:H,¶H}∞AæÑ©cË¶*à¸în?„Îè¢\nÉ˛ ;ÈOô\0Z˙∞v©AB£ÈÇá`åo°™8_“R--nôâT#DIs1Œ›\0VêPM\0Vˇr¨áø0\$Biä`ÄTàdìX|e\08\\7),_∫Ç∞Kø3(.c¡ï\\∞dÇÇ2€ŒÁR<Úu®\\Ç£	4–¬êN¿(|gÔù˛Ç|°N&,É≥Òy°‹Õ(¿≤ﬂ8bÔ:PÑóΩ1Y'!àÄƒÑ†\0fx“ÀÎï\0û‰1ÄÜÄ‡H[,Ω>ÇÁ‰È&ÊT∞/a\rLC¡bEöπÑß	7ÁÙ∏÷bËk»ô“|bÌÁÉ0πT\"âí˛.¿‡≈ŸÇ5sêÀDπSgÎï8πRh*ò4¢}àª¶üÄ<-9B\$¨”ﬁd9B\$Âi´HÇ8cj\\`éÜ_ªíìöÊ	…#`Ú¢ÄhÇàáÇHŒ®p†\$Å0á`1ÔõÄW\nòè%NÄZ\\#‡¬úbŸ¶Põå%m7l\"¢ÄdπÙ\"Pºê!ÿ#/≈üÃ§,Õ™ø≠J#0µácÂÑ]¬‡-(ÚêπÜ6†7l~\r\0BÓÑ0¿:CAÈ\\pœëÖ[ÚüŒÂ–(–åÆJGÂ0âB\"8ºPòB*% <#ÉBF72 BÇ§ˆìÈó¬5Bp	t&â6\0b¯àÒû4<\$ÌÄ∂•Kã°V\0G	ÛåmY†");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("v0úÅF£©Ã–==òŒFS	– _6M∆≥òËËr:ôEáCI¥ o:ùCÑîXcÇù\rÊÿÑJ(:=üEÜÅ¶a28°x∏?ƒ'Éi∞SANNë˘xsÖNB·ÃVl0õåÁS	úÀUlÅ(D|“ÑÁ P¶¿>öEÜ„©∂yHch‰¬-3EbìÂ ∏bΩﬂpE¡pˇ9.äèòÃ~\né?Kb±iw|»`«˜d.ºx8EN¶„!îÕ2ôá3©à·\ráç—YéÃËy6GFmYé8o7\n\r≥0§˜\0ÅDbc”!æQ7–®d8ã¡Ï~ë¨N)˘E–≥`ÙNsﬂ`∆S)–OÈó∑Á/∫<Åx∆9éoª‘Âµ¡Ï3n´Æ2ª!rº:;„+¬9àC»®Æâ√\n<Òç`»ÛØbË\\ö?ç`Ü4\r#`»<ØBe„B#§N ‹„\r.D`¨´jÍ4ˇéépÈar∞¯„¢∫˜>Ú8”\$…c†æ1…cú†°c†Í›Í{n7¿√°ÉAN RLi\r1¿æ¯!£(Êj¬¥Æ+¬Í62¿X 8+ ‚‡‰.\rÕŒÙÉŒ!xºÂÉh˘'„‚à6S\0RÔ‘ÙÒO“\nºÖ1(W0Ö„ú«7qúÎ:N√E:68n+é‰’¥5_(Æs†\r„îÍâ/mê6P‘@√EQÅ‡ƒ9\n®V-ã¡Û\"¶.:ÂJçœ8weŒqΩ|ÿá≥X–]µ›Y X¡eÂzW‚¸ é7‚˚Z1çÌhQfŸ„u£j—4Z{p\\AUÀJ<ıÜk·¡@º…ç√‡@Ñ}&ÑÅàL7U∞wuYhê‘2∏»@˚u† P‡7ÀAÜhËÃÚ∞ﬁ3√õÍÁXEÕÖZà]≠l·@Mplv¬)Ê ¡¡HWëë‘y>êYç-¯YüË/´ùõ™¡Ó†hC†[*ã˚F„≠#~Ü!–`Ù\r#0PÔCÀùóf†∑∂°Ó√\\Óõ∂á…Å^√%B<è\\Ωfàﬁ±≈·–›„&/¶OÇL\\jFù®jZ£1´\\:∆¥>ÅNπØXaF√A¿≥≤√ÿÕfÖh{\"s\n◊64á‹¯“Öº?ƒ8‹^pç\"Îù∞Ò»∏\\⁄e(∏PÉNµÏq[g∏¡rˇ&¬}Ph ‡°¿WŸÌ*ﬁÌr_sÀPáh‡º‡–\n€À√omıø•√Íó”#èß°.¡\0@ÈpdW ≤\$“∫∞Q€ΩTl0Ü æ√HdHÎ)öá€èŸ¿)P”‹ÿHêg‡˝U˛Ñè™BËe\rÜt:á’\0)\"≈tÙ,¥úí€«[è(D¯O\nR8!Ü∆¨÷ö‹lA¸VÖ®4†h‡£Sq<û‡@}√Î gK±]Æ‡Ë]‚=90∞Å'ÄÂ‚¯wA<ÇÉ–—a¡~ÄÚWöÊÉD|A¥ÜÜ2”XŸU2‡Èy≈äêä=°p)´\0P	òsÄµnÖ3ÓÅrÑf\0¢FÖ∑∫v“ÃGÆ¡I@È%§îü+¿ˆ_I`∂ÃÙ≈\r.É†N≤∫ÀKIÖ[î ñSJÚ©æaUfõSz˚É´MßÙÑ%¨∑\"Q|9Ä®Bcßa¡q\0©8ü#“<aÑ≥:z1Uf™∑>ÓZπlââπù”¿e5#U@iUG¬Çô©n®%“∞s¶ÑÀ;gxL¥pPö?BÁå Qç\\óbÑˇÈæíQÑ=7Å:∏Ø›°Q∫\r:ÉtÏ•:y(≈ ◊\n€d)π–“\n¡X;†ãÏéÍCaA¨\r·›ÒüP®GH˘!°†¢@»9\n\nAl~H†˙™V\ns™…’´ç∆Ø’bBr£™ˆÑí≠≤ﬂ˚3É\rûPø%¢—Ñ\r}b/âŒë\$ì5ßPÎC‰\"wÃB_Áé…U’gAtÎ§ÙÖÂ§ÖÈ^QƒÂU…ƒ÷jô¡Ì†BvhÏ°Ñ4á)π„+™)<ñj^ê<LÛ‡4U*†ıÅBg†Î–ÊË*nÅ ñË-ˇ‹ı”	9O\$¥âÿ∑zyMô3Ñ\\9‹Ëò.oä∂öÃÎ∏E(iÂ‡ûúƒ”7	tﬂöÈù-&¢\nj!\rÅ¿yúy‡D1g“ˆ]´‹yR‘7\"Êß∑Éà~¿Ì‡‹)TZ0E9MÂYZtXe!›fÜ@Á{»¨yl	8á;ê¶ÉR{ÑÎ8áƒÆ¡eÿ+ULÒ'ÇF≤1˝¯Ê8PE5-	–_!‘7ÖÛ†[2âJÀ¡;áHR≤È«πÄ8pÁó≤›á@ô£0,’ÆpsK0\rø4î¢\$sJæÅ√4…DZ©’I¢ô'\$cLîRÅñMpY&¸ΩèÕiÁz3GÕz“öJ%¡ÃP‹-Ñê[…/xÁ≥Tæ{p∂ßzãC÷vµ•”:ÉV'ù\\ñíKJa®√MÉ&∫∞£”æ\"‡≤eùo^Q+h^‚–iTÅ1™OR‰l´,5[›ò\$π∑)¨ÙjL∆ÅU`£SÀ`Z^|ÄárΩ=–˜nÁôªñòTU	1Hykõ«t+\0v·Dø\r	<ú‡∆ôÏÒjGîû≠t∆*3%kõY‹≤T*›|\"Cä¸lhEß(»\r√8rá◊{‹Ò0Â≤◊˛ŸD‹_åá.6–∏Ë;„¸áÑrBjÉO'€ú••œ>\$§‘`^6ôÃ9ë#∏®ßÊ4X˛•mh8:Í˚cã˛0¯◊;ÿ/‘â∑øπÿ;‰\\'(†ÓÑt˙'+ùôÚ˝ØÃ∑∞^Å]≠±N—vπÁ#«,Îv◊√Oœiùœñ©>∑ﬁ<SÔA\\Ä\\Óµ¸!ÿ3*tl`˜uÅ\0p'Ë7ÖP‡9∑bsú{¿vÆ{∑¸7à\"{€∆rÓa÷(ø^Êº›E˜˙ˇÎπg“‹/°¯ûUƒ9g∂Ó˜/»‘`ƒ\nL\nÅ)¿ÜÇ(A˙a\" ûÁÿ	¡&ÑP¯¬@O\nÂ∏´0Ü(M&©FJ'⁄! Ö0ä<ÔHÎÓ¬Á∆˘•*Ã|Ï∆*ÁOZÌm*n/bÓ/êˆÆê‘àπ.Ï‚©o\0Œ dnŒ)è˘èéiê:RéŒÎP2Ímµ\0/vÏOX˜¯F ≥œàÓåËÆ\"ÒÆÍˆÓ∏˜0ı0ˆÇ¨©Ì0bÀ–gj\$ÒnÈ0}∞	Ó@¯=M∆Ç0nÓPü/pÊotÏÄ˜∞®.ÃÃΩèg\0–)oó\n0»˜â\rF∂ÈÄ†bæi∂√o}\n∞ÃØÖ	NQ∞'xÚFa–JÓŒÙèLıÈ–‡∆\r¿Õ\rÄ÷ˆë0≈Ò'¨…d	oep›∞4D–‹ ê¶q(~¿Ã Í\rÇE∞€pr˘QVFHúl£ÇKj¶ø‰N&≠j!ÕH`Ç_bh\r1é†∫n!Õ…é≠zô∞°•Õ\\´¨\räÌä√`V_k⁄√\"\\◊Ç'Và´\0 æ`AC˙¿±œÖ¶V∆`\r%¢í¬≈Ï¶\rÒ‚ÉÇk@N¿∞¸ÅBÒÌöôØ ∑!»\ní\0Zô6∞\$d†å,%‡%laÌH◊\nã#¢S\$!\$@∂›2±çÑI\$rÄ{!±∞Já2H‡ZM\\…«hb,á'||cj~g–rÖ`ºƒº∫\$∫ƒ¬+ÍA1úEÄ«¿Ÿ < L®—\$‚Y%-FD™ädÄLÁÑ≥†™\n@íbVfËæ;2_(ÎÙLƒ–ø¬≤<%@⁄ú,\"Ídƒ¿NÇerÙ\0ÊÉ`ƒ§ZÄæ4≈'ld9-Ú#`‰Û≈ñÖ‡∂÷„j6Î∆£„v†∂‡N’Õêf†÷@‹Üì&íB\$Â∂(Z&ÑﬂÛ278I ‡ø‡P\rk\\èßó2`∂\rdLb@EˆÉ2`P( B'„Ä∂Ä∫0≤&†Ù{¬êïìß:Æ™dBÂ1Ú^ÿâ*\r\0c<Kê|›5sZæ`∫¿¿O3Í5=@Â5¿C>@¬W*	=\0N<gø6s67Sm7u?	{<&L¬.3~DƒÍ\r≈öØxπÌ),rÓin≈/†ÂO\0o{0kŒ]3>mãî1\0îI@‘9T34+‘ô@eîGFMC…\rE3ÀEtm!€#1¡D @ÇH(ë”n √∆<g,V`R]@˙¬«…3Cr7s~≈GIÛi@\0v¬”5\rVﬂ'¨†§†Œ£P¿‘\r‚\$<b–%(áDdÉãPWƒÓ–ÃbÿfO Êx\0Ë} ‹‚îlb†&âvj4µLSº®÷¥‘∂5&dsF MÛ4Ã”\".HÀM0Û1uL≥\"¬¬/J`Ú{«˛ßÄ x«êYu*\"U.I53Q≠3QÙªJÑîg†í5Ös‡˙é&j—åí’uÇŸ≠–™GQMTmGBÉtl-c˘*±˛\rä´Z7‘ıÛ*hs/RUV∑Ù™BüNÀà∏√Û„Í‘ä‡i®Lk˜.©¥ƒtÏ†Èæ©ÖrYiî’È-SµÉ3Õ\\öTÎOM^≠G>ëZQj‘áô\"§é¨iî÷MsS„S\$Ib	f≤‚—uÊ¶¥ôÂ:ÍSB|i¢†Y¬¶É‡8	v #ÈîD™4`áÜ.ÄÀ^ÛH≈Mâ_’ºäu¿ôU z`ZçJ	eÁ∫›@CeÌÎaâ\"mÛbÑ6‘ØJR¬÷ëTù?‘£XMZ‹Õ–ÜÕÚpË“∂™QvØjˇjV∂{∂º≈Cú\rµ’7âT û™ ˙Ì5{Pˆø]í\r”?Q‡AA¿ËéãíÕ2Òæ†ìV)Ji£‹-N99fñl JmÕÚ;u®@Ç<F˛—†æeÜjÄ“ƒ¶èIâ<+CW@ÅÁ¿øZël—1…<2≈iF˝7`KGò~L&+Nè‡YtWHÈ£ëw	÷ïÉÚlÄ“s'g…„q+LÈzbiz´∆ ≈¢–.–ä«zW≤« ˘zdïW¶€˜π(èy)v›E4,\0‘\"d¢§\$B„{≤é!)1UÜ5bp#≈}m=◊»@àwƒ	P\0‰\rÏ¢∑ëÄ`O|Î∆ˆ	ú…ç¸≈ı˚YÙÊJ’ÇˆE◊ŸOuû_ß\n`F`»}M¬.#1·Ç¨fÏ*¥’°µß  øz‡uc˚Äó≥ xf”8kZRØs2 Ç-ÜíßZ2≠+é ∑Ø(ÂsUıcDÚ—∑ Ïò›X!‡Õu¯&-vP–ÿ±\0'LÔåX ¯L√πåào	›Ù>∏’é”\r@ŸPı\rxF◊¸EÄÃ»≠Ô%¿„ÏÆ¸=5N÷úÉ∏?Ñ7˘NÀ√Ö©wä`ÿhX´98 ÃÅç¯Øq¨£z„œd%6ÃÇtÕ/Öïò‰¨ÎèL˙Õlæ ,‹KaïN~œ¿€Ï˙,ˇ'Ì«ÄM\rf9£wêò!xê˜x[àœëÿGí8;ÑxAò˘-IÃ&5\$ñD\$ˆº≥%Öÿx—¨¡î»¬¥¿¬å]õ§ıá&oâ-3ù9÷L˘Ωzç¸ßy6π;uπzZ Ë—8ˇ_ï…êx\0D?öX7Üô´íy±OY.#3ü8†ô«ÄòeîQ®=ÿÄ*òôGåwm ≥⁄ÑYë˘†¿⁄]YOY®F®ÌöŸ)Ñz#\$eäö)Ü/åz?£z;ôóŸ¨^€˙F“Zg§˘ï†Ã˜•ôßÉö`^⁄e°≠¶∫#ßìÿÒî©é˙?ú∏e£ÄM£⁄3uÃÂÅÉ0π> \"?üˆ@◊óXvï\"Áîåπ¨¶*‘¢\r6v~á√OV~ç&◊®Å^g¸†öƒëŸûá'ŒÄf6:-Z~πöO6;zxÅ≤;&!€+{9M≥Ÿ≥d¨ \r,9÷Ì∞‰∑W¬∆›≠:Í\r˙Ÿú˘„ù@ÁùÇ+¢∑]úÃ-û[gûô€á[s∂[iûŸi»qõõyõÈxÈ+ì|7Õ{7À|w≥}Ñ¢õ£Eñ˚W∞ÄWk∏|JÿÅ∂Ââxmà∏q xwyjüªò#≥òeº¯(≤©â∏çù¿ﬂû√æôÜÚ≥ {Ëﬂ⁄è†yì†ªMª∏¥@´Ê…Çì∞Yù(gÕö-ˇ©∫©‰Ì°ö°ÿJ(•¸Å@ÛÖ;Öy¬#SºáµYÑ»p@œ%Ësû˙oü9;∞ÍøÙı§π+Ø⁄	•;´¡˙àZNŸØ¬∫ßÑö kºVß∑uâ[ÒºxùÖ|qí§ON?Ä…’	Ö`uú°6ç|≠|Xπ§≠óÿ≥|OÏx!Î:è®úœóY]ñ¨πéôcï¨¿\rπhÕ9nŒ¡¨¨ÎçÄœ8'ó˘ÇÍ‡†∆\rS.1ø¢US»∏ÖºXâ…+À…z]…µ §?ú© ¿CÀ\r◊À\\∫≠π¯\$œ`˘Ã)UÃ|À§|—®x'’úÿÃ‰ <‡ÃôeŒ|ÍÕ≥Áó‚íÃÈóLÔœ›MŒyÄ(€ß–lè–∫§O]{—æ◊FDÆ’Ÿ}°yuã—ƒíﬂ,XL\\∆x∆»;U◊…WtÄvüƒ\\OxWJ9»í◊R5∑WiMi[áKàÄf(\0Êædƒö“Ëø©¥\rÏMƒ·»Ÿ7ø;»√∆Û“ÒÁ”6âK ¶I™\rƒ‹√xv\r≤V3’€ﬂ…±.Ã‡R˘¬˛…ç·|ü·æ^2â^0ﬂæ\$†QÕ‰[„øD˜·‹£Â>1'^X~tÅ1\"6Lù˛õ+˛æA‡ûe·ìÊﬁÂIëÁ~üÂ‚≥‚≥@ﬂ’≠ıpM>”m<¥“SK Á-H…¿ºT76ŸSMfg®=ª≈GP ∞õP÷\r∏È>Õˆæ°•2Sb\$ïC[ÿ◊Ô(ƒ)ûﬁ%Q#G`u∞«Gwp\rkﬁKeózhj”ìzi(ÙËrO´Ûƒﬁ”˛ÿT=∑7≥ÚÓ~ˇ4\"efõ~ÌdôÙÌVˇZâö˜Uï-Îb'VµJπZ7€ˆ¬)Të£8.<øRMˇ\$âûÙ€ÿ'ﬂbyÔ\n5¯É›ı_é‡wÒŒ∞ÌUí`eiﬁøJîb©guçSÕÎ?ÕÂ`ˆ·ûÏ+æœÔ MÔgË7`˘ÔÌ\0¢_‘-˚üı_˜ñ?ıF∞\0ìıç∏XÇÂ¥í[≤ØJú8&~D#¡ˆ{PïÿÙ4‹óΩ˘\"õ\0Ã¿Äã˝ßÅ˝@“ìñ•\0F ?*è†^ÒÔçπÂØwÎ–û:Åæu‡œ3xKÕ^Ûwìº®ﬂØây[‘û(ûÊñµ#¶/zr_îg∑Ê?æ\0?Ä1wMR&MøÜ˘?¨StÄT]›¥Gı:I∑‡¢˜à)á©BÔàã vÙßíΩ1Á<Ùt»‚6Ω:èW{¿äÙx:=»ÓëÉåﬁöÛ¯:¬!!\0xõ’ò£˜q&·Ë0}z\"]ƒﬁoïz•ô“j√w◊ﬂ ⁄¡6∏“J¢P€û[\\ }˚™`Sô\0‡§qHMÎ/7BíÄP∞¬ƒ]FT„ï8S5±/I—\rå\n ÅÓOØ0aQ\n†>√2≠jÖ;=⁄¨€dA=≠p£VL)Xı\n¬¶`e\$òT∆¶QJùÕÛÆÊlJÔä‘Ó—yÑIﬁ	‰:É—ƒƒB˘bP¿Ü˚ZÕ∏n´™∞’U;>_—\n	æıÎ–Ã`ñ‘uMÚåÇÇ¬÷çm≥’Û¬Lw˙B\0\\b8¢M‹ê[zëù&©1˝\0Ù	°\ròT÷◊õÅ†Ä+\\ª3¿Plb4-)%Wd#\n»ÂrﬁÂMX\"œ°‰(Ei11(b`@f“¥≠ÉS“ÛàjÂDÜùbf£}ÄrÔæë˝DëR1Öù¥b”òA€ÔIy\"µWv‡¡gC∏IƒJ8z\"P\\i•\\m~ZRπ¢vÓ1ZB5Iä√i@xîÜ∑∞-âuM\njK’U∞h\$oóàJœ§!»L\"#p7\0¥ PÄ\0äD˜\$	†GK4e‘–\$Å\nG‰?˘3£EAJF4‡Ip\0´◊Fé4±≤<f@û %q∏<k„wÄÅ	‡LOp\0âx”«(	ÄG>@°ÿÁ∆∆9\0T¿àòÏGB7†-†Äû¯‚G:<Qô†#√®”«¥˚1œ&tz£·0*J=‡'ãJ>ÿﬂ«8q°ç–•™‡Å	ÄO¿¢XÙF¥‡Qç,Å¿ –\"9ëÆp‰*66A'˝,yÄùIFÄRà≥Tàœ˝\"î˜H¿RÇ!¥j#kyF¿ô‡eë¨z£ÎÈ»G\0ép£âaJ`C˜i˘@úT˜|\nÄIx£K\"≠¥*®çTk\$c≥Ú∆îaAhÄì!†\"˙E\0OêdƒSxÚ\0T	ˆ\0Çû‡!F‹\níUì|ô#S&		IvL\"îìÖ‰\$h–»ﬁEAÔN\$ó%%˘/\nPÜ1öì≤{§Ô) <á†Lç†Â-R1§‚6ë∂í<Å@O*\0J@qπë‘™#…@«µ0\$tÉ|í]„`ª°ƒäA]ËÕÏP·ëÄòC¿p\\p“§\0ô“≈7∞ƒ÷@9©bêmàr∂o€C+Ÿ]•Jr‘f¸∂\rÏ)d§í—ú≠^hﬂI\\Œ. gñ >•Õ◊8åﬁ¿'ñH¿fôrJ“[rÁo„•Ø.πvÑΩÔ#Ñ#yR∑+©yÀ÷^Ú˘õÜF\0·±Åô]!…ï“ﬁî++Ÿ_À,©\0<@ÄM-§2WÚ‚ŸR,cïåúe2ƒ*@\0ÍP Ä¬c∞a0«\\P¡äàOÅ†¯`I_2Qs\$¥w£ø=:Œz\0)Ã`Ãhä¬ñ¡ÉàÁ¢\nJ@@ ´ñ\0ö¯ 6qTØÂá4J%ïN-∫m§ƒÂ„.…ã%*cn‰ÀNÁ6\"\rÕë∏ÚËó˚äf“Aµ¡ÑpıM€ÄI7\0ôM»>lOõ4≈S	7ôcÕÏÄ\"Ïﬂß\0Âì6ÓpsÖñƒ›Ây.¥„	Ú¶ÒRKïPAo1F¬tIƒb*…¡<á©˝@æ7–ÀÇp,Ôù0N≈˜:†®N≤m†,ùxO%Ë!Ç⁄v≥®ò†gz(–M¥Û¿I√‡	‡Å~yÀˆõh\0U:ÈÿOZyA8ù<2ß≤∏ usﬁ~lÚ∆ŒEòOî0±ü0]'Ö>°›…çå:‹Í≈;∞/Ä¬w“Ùù‰Ï'~3GŒñ~”≠ù‰˛ßc.	˛ÑÚvT\0cÿt'”;P≤\$¿\$¯ÄÇ–-Çs≥Úe|∫!ï@d–Obw”Êc¢ı'”@`P\"xÙµË¿0Oô5¥/|„U{:b©R\"˚0Ö—àkò–‚`BDÅ\nkÄPù„c©·4‰^ p6S`è‹\$Îêf;Œ7µ?ls≈¿ﬂÜgD '4Xja	AáÖE%ô	86b°:qr\r±]C8 c¿F\n'—åf_9√%(¶ö*î~ä„iSË€ê…@(85†TîÀ[˛ÜJ⁄ç4ÅIÖl=∞éQ‹\$d¿Æh‰@D	-ÅŸ!¸_]…⁄Hñ∆äîk6:∑⁄Ú\\M-ÃÿÚ£\rëFJ>\n.ëîqêeG˙5QZç¥Üã' …¢ûΩê€Å0üÓÅzPñ‡#≈§¯ˆ÷Èr‡“ÌtΩí“œÀé˛ä<QàèT∏£3èD\\πÑƒ”pOE¶%)77ñWtù[∫Ù@ºõéö\$F)Ω5qG0´-—W¥v¢`Ë∞*)Rr’®=9qE*K\$g	ÇÌA!ÂPjBT:óK˚ßç!◊˜Hì R0?Ñ6§yA)B@:QÑ8B+Jç5U]`Ñ“¨ùÄ:£Â*%Ip9åÃÄˇ`KcQ˙Q.Bî±Ltb™ñyJÒùEÍõTÈ•ı7ïŒˆAm”‰¢ïKu:éSjió 5.q%LiF∫öTr¶¿i©’Kà“®zó55T%UïâU⁄I’Ç¶µ’Y\"\nS’mÜ—ƒx®ΩCh˜NZ∂UZùîƒ( BÍÙ\$YÀV≤„Äu@ËîªíØ¢™|	Ç\$\0ˇ\0†oZw2“Äx2ëù˚k\$¡*I6I“nï†ï°ÉI,Ä∆QU4¸\nÑ¢).¯QêÙ÷aI·]ô¿†ËL‚h\"¯f¢”ä>ò:Z•>L°`nòÿ∂’Ï7îVLZuîÖe®ÎX˙ËÜ∫Bø¨•Bâ∫í°êZ`;Æ¯ïJá]Ú—Äû‰S8º´f \n⁄∂à#\$˘jM(πëﬁ°îÑ¨ùa≠GÌßÃ+A˝!ËxL/\0)	Cˆ\nÒW@È4êÄ∫è·€©ï ä‘RZÉÆ‚†=ò«Ó8ì`≤8~‚Üh¿ÏP Å∞\rñ	∞ûÏD-FyX∞+ f∞QSj+XÛ|ï»9-í¯s¨xêÿ¸ÜÍ+âV…cbpÏøîo6H–q†∞≥™»@.Äòl†8gΩYMü÷WMP¿™U°∑YLﬂ3PaËH2–9©Ñ:∂a≤`¨∆d\0‡&Í≤YÏﬁY0Ÿò°∂Så-óí%;/áT›BS≥P‘%fêÿ⁄˝ï†@ﬂFÌ¨(¥÷ç*—q +[ÉZ:“QY\0ﬁ¥ÎJUY÷ì/˝¶Üpkz»àÚÄ,¥™áÉj⁄ÍÄ•W∞◊¥e©JµFËç˝VBIµ\r£∆pFõNŸÇ÷∂ô*’®Õ3k⁄0ßDÄ{ôÅ‘¯`qôï“≤Bqµe•Dâc⁄⁄‘V√E©Ç¨nÅÒ◊‰FG†Eõ>jÓË–˙Å0g¥a|°ShÏ7u¬›Ñç\$ïÜÏ;aÙó7&°Î∞R[WXÑ ÿ(q÷#ùå¨Pπ∆‰◊ñ›c8!∞H∏‡ÿVXßƒé≠j¯ ZéÙë°•∞Q,DUaQ±X0ë’’®¿›ÀGb¡‹läBät9-oZ¸îçL˜£•¬≠ÂpÀáëx6&ØØMy‘œs“êøñË\"’ÕÄËRÇIWU`c˜∞‡}l<|¬~ƒw\"∑vI%r+ÅãR‡∂\n\\ÿ˘√—][ã—6è&¡∏›»≠√aî”∫Ï≈jπ(⁄ìT—ì¿∑C'äÖ¥ '%de,»\nñFC≈—çe9CπN‰–çÇ-6îUe»µå˝CX∂–V±Éùπ˝‹+‘R+∫ÿîÀï3B‹Å⁄åJ¢Ëôú±ÊT2†]Ï\0PËa«t29œ◊(iã#Äa∆Æ1\"SÖ:ˆ∑†à÷oF)kŸfÙÚƒ–™\0Œ”ø˛’,À’wÍÉJ@Ï÷VÚÑéµÈq.e}KmZ˙€ÔÂπXnZ{G-ª˜’ZQ∫Ø«}ë≈◊∂˚6…∏µƒ_ûÿÅ’â‡\n÷@7ﬂ` ’ÔãòC\0]_ ç© µ˘¨´Ôª}˚G¡WW: fCYk+È⁄b€∂∑¶µ2S,	⁄ãﬁ9ô\0ÔØÅ+˛WƒZ!Øe˛∞2˚Ù‡õóÌ≤k.OcÉ÷(vÃÆ8úDeG`€á¬åˆL±ıì,ÉdÀ\"C »÷B-îƒ∞(˛ÑÑÑp˜Ì”p±=‡Ÿ¸∂!˝kíÿ“ƒºÔ}(˝— Bñkrç_RÓó‹º0å8a%€òL	\0ÈÜ¿Òâb•≤öÒ≈˛@◊\"—œr,µ0T€rV>àÖ⁄»Qü–\"ïrﬁ˜Pâ&3b·P≤Ê-†xÇ“±uW~ç\"ˇ*ËàûåN‚hó%7≤µ˛K°YÄÄ^A˜Æ˙ CÇË˛ªp£·Óà\0..`c≈Ê+œä‚GJ£§∏Hø¿ÆEÇÖ§æl@|I#Ac‚ˇDêÖ|+<[c2‹+*WS<àr‡„g∏€≈}âä>iÅ›ÄÅ!`f8ÒÄ(c¶ÅË…Q˝=fÒ\nÁ2—c£h4ñ+qùèÅ8\na∑R„B‹|∞Rì◊Íø›mµä\\q⁄ıgX¿†ñçœé0‰X‰´`nÓFÄÓÏåO p»ÓHÚCÉîjd°fµﬂEuDVòêbJ…¶øÂ:±ÔÄ\\§!m…±?,TIaòÜÿaT.LÄ]ì,Jèå?ô?œîFMct!aŸßRÍFÑG!πAıìªrrå-péXü∑\rªÚC^¿7Å·&„RÈ\0Œ—f≤*‡A\nı’õH·„§yÓY=«˙ËÖlÄ<áπAƒ_πË	+ëŒtA˙\0Bï<AyÖ(fyã1ŒcßO;pùË≈·¶ù`Áí4–°MÏ‡*úÓfÜÍ 5fvy {?©‡À:y¯—^c‚Õuú'áôÄ8\0±º”±?´ägö”á 8BçŒ&p9÷O\"z«ıûrsñ0∫ÊBë!uÕ3ôf{◊\0£:¡\n@\0‹¿£ÅpêŸ∆6˛v.;‡˙©Ñ b´∆´:J>ÀÇâÈ-√BœhkR`-‹ÒŒawÊxEj©Ö˜¡rû8∏\0\\¡ÔÙÄ\\∏Uhmõ ˝(m’H3Ã¥ÌßSôì¡Êq\0˘üNVh≥Hyç	óª5„MÕée\\gΩ\nÁIP:Sj¶€°Ÿ∂Ë<éØ—xÛ&åL⁄ø;nfÕ∂cÛqõ¶\$f&lÔÕ˛i≥Öú‡Á0%yŒûætÏ/π˜gUÃ≥¨dÔ\0e:√ÃhÔZ	–^É@Á†˝1Äœm#—NèÛw@åﬂOzGŒ\$Ú®¶m6È6}Ÿ““ãöX'•I◊i\\Q∫YùÄ∏4k-.Ë:yz—»›Hø¶]ÊÊxÂGœ÷3¸øM\0Ä£@z7¢Ñ≥6¶-DO34ùﬁã\0Œöƒ˘Œ∞t\"Œ\"vC\"JfœR û‘˙ku3ôMŒÊ~˙§”é5V ‡Ñj/3˙É”@gGõ}DÈæ∫B”Nq¥Ÿ=]\$ÈøIáı”ûî3®x=_jãXŸ®ùfk(C]^jŸM¡ÕF´’’°å‡œ£Cz»“Vú¡=]&û\r¥A<	Êµ¬¿‹„Á6Ÿ‘Æ∂◊¥›`jk7:gÕÓë4’Æ·ÎìYZq÷ftuù|çh»Z““6µ≠i„Ä∞0†?ÈıÈ™≠{-7_:∞◊ﬁêt—ØÌckã`YÕÿ&ì¥ÈùIılP`:ÌÙ j≠{hÏ=–f	‡√[byû¢ Äo–ãB∞RSóÄºB6∞¿^@'Å4Ê¯1U€Dq}Ï√N⁄(XÙ6j}¨c‡{@8„Ú,¿	œPFC‡âB‡\$mvòù®PÊ\"∫€Lˆ’CS≥]õè›‡EŸﬁœlUÜ—fÌwh{oç(ó‰)Ë\0@*a1Gƒ (†ÅD4-cÿÛP8ù£N|RõÜ‚VM∏∞◊n8G`e}Ñ!}•Ä«pªá‹Ú˝@_∏Õ—nCt¬9é—\0]ªu±ÓØsªä›~Ërßª#Cn†p;∑%ã>wu∏çﬁn√w˚§›ûÍ.ù‚‡[«›hT˜{∏›ÂÄº	Á®ÀÅá∑Jç‘∆óiJ 6ÊÄOæ=°Äá˚ÊﬂEî˜Ÿ¥êëIm€Ô⁄V'…ø@‚&Ç{™ëõÚˆØµê;Ìop;^ñÿ6≈∂@2ÁØl˚‘ﬁNÔ∑∫M…ørÄ_‹∞À√ç¥` Ï( yﬂ6Á7ëπ˝ÎÓ«Çìè7/¡pe>|ﬂ‡	¯=Ω]–ocÅ˚ë·&ÂxNmç£âÁÉª¨‡o∑G√N	póÇªòx®ï√Ω›Éy\\3‡è¯á¬Ä'÷I`r‚G˜]ƒæÒ7à\\7⁄49°]≈^pá{<Z·∑∏q4ôuŒ|’€Q€ô‡ıpô˝öi\$∂@oxÒ_<Å¿Ê9pBU\"\0005çó i‰◊Çª∏C˚p¥\nÙi@Ç[„ú∆4ºj–ÅÑ6bÊPÑ\0ü&F2~é¿˘£ºÔU&ö}æΩçø…ò	ôÃDa<ÄÊzx∂k£àã=˘Ò∞r3ÈÀ(l_îÅÖFeFõùû4‰1ìK	\\”éldÓ	‰1ÅH\rΩÄ˘p!Ü%bGÊXfÃ¿'\0»úÿ	'6¿ûps_õ·\$?0\0í~p(ÅH\nÄ1ÖW:9’Õ¢Øò`ãÊ:h«BñËgõBäk©∆pƒ∆ÅÛtºÏàEBI@<Ú%√∏¿˘` ÍäyÅd\\Y@DñP?ä|+!Ñ·W¿¯.:üLeÄv,–>qÛA»Á∫:ûñÓbYÈà@8üd>r/)¬BÁ4¿–Œ(Å∑ä`|È∏:t±!´ã¡®?<Ø@¯´í/•†SíØP\0¬‡>\\Ê‚ |È3Ô:V—uw•ÎÁx∞(Æ≤üú4Ä«ZjD^¥•¶L˝'ºÏƒC[◊'˙∞ßÆÈj¬∫[†E∏Û u„∞{KZ[sÑûÄ6àÇS1ùÃz%1ıcô£B4àB\n3M`0ß;ÁÚÃ¬3–.î&?°Í!YA¿I,)ÂïlÜW['∆ I¬áTjÉÅË>F©º˜Sßá†B–±P·ªca˛«åuÔ¢N›œ¿¯H‘	LSÙçÓ0î’Y`¬∆»\"ilë\rÁB≤Î„/åÙ„¯%PÄœ›NîGÙù0J∆X\n?aÎ!œ3@MÊF&√≥÷˛øê,∞\"ÓÄËlbÙ:KJ\rÔ`k_Íb˜¸A·ŸƒØÃ¸1—I,≈›Ó¸à;B,◊:ÛæÏY%ºJ†éä#vîÄ'Ü{ﬂ—¿„Ñû	wx:\ni∞∂≥í}c¿∞eNÆ—Ô`!wù∆\0ƒBRU#ÿS˝!‡<`ñê&v¨<æ&ÌqO“+Œ£•sfL9èQ“B áÑ…Û‰èb”‡_+Ô´*ÄSu>%0Äéô©Ö8@l±?íL1po.ƒC&ΩÌ…†B¿ qhò¶Û≠í¡ûz\0±`1·_9\"ñÄË!ê\$¯å∂~~-±.º*3r?¯√≤¿dôs\0ÃıÅ»>z\n»\0ä0†1ƒ~ëÙòJ≥˙î|SﬁúÙ†k7gÈ\0å˙K‘†d∂Ÿa…ÓPg∫%„wìDÙÍzm“˚»ı∑)øëÒäújã€◊¬ˇ`kª“ÅQ‡^√Œ1¸å∫+ŒÂú>/wb¸GwOk√ﬁ”_Ÿ'É¨-CJ∏Â7&®¢∫EÒ\0L\r>ô!œqÃÅÓê“7›¡≠ıoäô`9O`à‡Éîˆ+!}˜P~EÂN»cîˆQü)Ï·#˚Ô#ÂÚáÄÏáÃ—¯¿ë°ØËJÒƒz_u{≥€K%ë\0=Û·OéX´ﬂ∂C˘>\n≤ÄÖ|w·?∆FÄ≈ÍÑ’añœ©UêŸÂ÷b	N•YÔ…häΩªÈë/˙˚)ﬁGŒå2¸ô¢K|„±y/ü\0È‰øZî{ÈﬂP˜YG§;ı?Z}T!ﬁ0ü’=mNØ´˙√fÿ\"%4ôaˆ\"!ñﬁüÅ˙∫µ\0ÁıÔ©}ªÓ[ÚÁ‹æ≥ÎbU}ª⁄ïmı÷2±ï†Öˆ/t˛Óë%#è.—ÿñƒˇseÄBˇp&}[Àüé«7„<a˘K˝ÔÒ8Ê˙P\0ôÛ°gºÚ?ö˘,÷\0ﬂﬂàr,†>øå˝W”˛Ô˘/÷˛[ôq˝êk~ÆC”ã4€˚GäØ:ÑÄX˜òG˙r\0…Èü‚Ø˜üL%VFLUcØﬁ‰ë¢˛éHˇybPÇ⁄'#ˇ◊	\0–ø˝œÏπ`9ÿ9ø~ÔÚó_º¨0q‰5K-ŸE0‡bÙœ≠¸ö°éút`lmÍÌÀˇbå‡∆ò; ,=ò†'SÇ.b ÁSÑæ¯CcóÉÍÎ çAR,ÑÉÌ∆Xä@‡'Öú8Z0Ñ&ÏXnc<<»£3\0(¸+*¿3∑ê@&\r∏+–@h, ˆÚ\$Oí∏Ñ\0≈íÉËt+>¨¢ãúb™Ä ∞Ä\r£><]#ı%É;NÏsÛÆ≈éÄ¢ *ªÔc˚0-@Æ™LÏ >ΩYÅp#–-Üf0Ó√ ±a™,>ª‹`è∆≈‡P‡:9ååo∑∞ovπR)e\0⁄¢\\≤∞¡µ\nr{√ÆXô“¯Œ:A*€«.êDı∫7ÅéªºÚ#,˚N∏\réEô‘˜hQK2ª›©•Ωz¿>P@∞∞¶	T<“ =°:Ú¿∞X¡GJ<∞GAfı&◊A^p„`©¿–{˚‘0`º:˚Ä);U !–e\0Ó£ΩœcÜp\rã≥†ãæ:(¯ï@Ö%2	SØ\$Y´›3ÈØhC÷Ïô:Oò#œ¡LÛÔ/ùöÈÇÁ¨k,ÜØKÂoo7•BD0{Éê°jÛ†Ïj&X2⁄´{Ø}ÑRœx§¬v¡‰˜ÿ£¿9AÎ∏∂æ0â;0Åı·ë‡-Ä5Ñà/î<‹Á∞ æN‹8EØëó«	+„–Ö¬Pd°Ç;™√¿*nüº&≤8/jX∞\rêö>	PœêW>K‡ïOí¢Vƒ/î¨U\n<∞•\0Ÿ\nIÅk@ä∫„¶É[‡»œ¶¬≤ú#é?ÄŸ„%ÒÉÇËÀ.\0001\0¯°kË`1T∑ ©ÑæÎÇ…êlºêö¿£Ó≈pÆ¢∞¡§≥¨≥Ö< .£>Ìÿ5é–\0‰ª	O¨>k@Bnæä<\"i%ï>ú∫zƒñÁìÒ·∫«3ŸPÉ!\r¿\"¨„¨\r â>öad‡ˆÛ¢U?⁄«î3P◊¡j3£‰∞ë>;”‰°ø>ût6À2‰[¬ﬁæM\r†>∞∫\0‰ÏPÆÇ∑BË´Oe*RÅn¨ßúy;´ 8\0»À’oÊΩ0˝”¯i¬¯˛3 Ä2@ ˝‡£ÓØ?xÙ[˜Ä€√LˇaéØÅÉw\ns˜àáåA≤øx\r[—a™6¬clc=∂ ºX0ßz/>+ö™â¯W[¥o2¬¯å)eÓ2˛HQPÈDYìzG4#YDÖˆÖ∫p)	∫H˙pêéò&‚4*@Ü/:ò	·âTò	≠ü¶aH5ëÉÎh.ÉA>úÔ`;.ü≠ÓYì¡a	¬Ú˙t/ =3Ö∞BnhD?(\nÄ!ƒB˙sö\0ÿÃD—&DìJèë)\0áj≈QƒyêéhDh(ÙKë/!–>Æh,=€ı±Ü„tJÄ+°Sı±,\"M∏ƒø¥N—1ø[;¯–¢äº+ı±#<ÏåI§ZƒüåPë)ƒ·LJÒDÈÏP1\$ƒÓıºQë>dOëºvÈ#ò/mh8881N:ù¯Z0Zä¡ËT ïBÛC«q3%∞§@°\0ÿÔ\"ÒXD	‡3\0ï!\\Ï8#ÅhºvÏibœÇTÄ!d™óàŒ¸V\\2Û¿SÎ≈≈í\nA+ÕΩpöx»iD(Ï∫(‡<*ˆ⁄+≈’E∑ÃTÆæ†BËS∑C»øT¥ÊŸƒ eÑAÔí\"·|©uºv8ƒT\0002ë@8D^ooÉÇ¯˜ë|îN˘òÙ•ê J8[¨œ3ƒ¬ıÓJçz◊≥WL\0∂\0ûÄ»Ü8◊:y,œ6&@î¿ êE£ Ø›ëh;º!fòº.B˛;:√ Œ[Z3•ô¬´ÇnªÏÎ»ë≠ÈA®í”qP4,ÑÛ∫Xc8^ªƒ`◊ÉÇÙl.Æ¸∫¢S±hﬁî∞ùÇO+™%P#Œ°\n?€‹IBΩ eÀëÅO\\]Œ¬6ˆ#˚¶€ΩÿÅ(!c)†Nı∏∫—?EÿîB##D ÌDdoΩÂPèA™\0Ä:‹n¬∆üÄ`  ⁄ËQÑ≥>!\r6®\0ÄâV%cbÅHF◊)§m&\0B®2IÌ5íŸ#]˙òÿD>¨Ï3<\n:MLê…9CÒè ò0„Î\0êì®(·è©H\n˛Ä¶∫MÄ\"GR\n@Èè¯`[√ÛÄäò\ni*\0ú)à¸ÄÇêÏu©)§´Hp\0ÄNà	¿\"ÄÆN:9q€.\r!çç¥J÷‘{,€'ÊŸÅä4ÖBÜ˙«lq≈®üXc´¬4ﬂãN1…®5´WmÅ«3\nÅ¡FÄÑ`≠'ëà“äx‡É&>z>N¨\$4?Ûõ√Ôè¬(\nÏÄ®>‡	ÎœµP‘!CqÕåºåp≠qGLqqˆG≤yÕH.´^‡û\0z’\$ÄAT9FsÜ–Ö¢D{Ìaß¯cc_ÄG»zÜ)Û≥á ‹}Q∆≈hÛÃHB÷∏ç<Çy!L≠ìÄ€!\\Ç≤àÓ†¯'íH(Ç‰-µ\"Éin]ƒûà≥≠\\®!⁄`MòH,g»éÌª*“KfÎ*\0Ú>¬Ä6∂à‡6»÷2ÛhJÊ7Ÿ{nq¬8‡ﬂÙç…H’#cèH„#ò\rí:∂ñ7 8‡‹ÄZ≤òZrD£˛ﬂ≤`rG\0‰l\nÆIçài\0<±‰„Ù\0LgÖ~ê®√E¨€\$π“Pì\$ä@“P∆ºT03…HGH±l…Q%*\"N?Î%úñ	ÄŒ\nÒCrW…C\$¨ñpÒ%âuR`¿À%≥ÚR\$ñ<ë`÷Ifx™Ø˜\$/\$ÑîÅ•Å\$úöíOÖ(ãèÀ\0ÊÀ\0èRYÇ*Ÿ/	Í\r‹úC9ÄÔ&hh·=I”'\$ñRRI«'\\ïa=E‘ÑùÚu¬∑'ÃôwIÂ'TíÄÄë¸ˇ©æ„K9%òd¢¥∑Ç!¸îÅ¿  ¿“jÖÏ°Ì” &–ÊÑvÃü≤\\=<,úE˘å`€Y¡Ú\\ü≤Ç§*b0>≤rÆ‡,dñpdååÃ0DD Ãñ`‚,T ≠1›% Pëû§/¯\rÚbπ(å£ıJ—ËÕÓT0Ú``∆æﬁËÌÛJît©í© ü((d« ™·h+ <…à+H%iá»Ùã≤ï#¥`≠ ⁄ —'Ù£B>tòØJÄZ\\ë`<JÁ+hR∑ ‘8ÓâÄ‡hR±,J]gÚ®I‰ïË0\n%Jπ*–Y≤Ø£JwDú∞& ñD±Æï…–ú™RßK\"ﬂ1QÚ®À î≤AJKC,‰¥mVíªé≤õ Ÿ-±ÚœKI*±r®É\0«L≥\"∆Kb(¸™çÛJ:qKr∑d˘ ü-)¡ûÀÜ#‘∏≤ﬁ∏[∫Aª@ï.[ñ“® ºﬂ4∫°Ø.ô1ÚÆJΩ.ÃÆ¶u#Jìá¡g\0∆„Úëß£<À&îíK§+Ω	M?Õ/d£ %'/õø2Y»‰>≠\$Õ¨l∫\0Ü©+¯ó¡â}-t∫íÕÖ*ÍâR‰\$ﬂîÚÃKª.¥¡≠ÛJH˚ âá2\rÑøBèÇΩ(PÕ”Ã6\"¸ñnfÜ\0#–á ÆÕ%\$ƒ [Ä\n–noùLJ∞å≈”¬e'<ØÛÖá1KÌ¡yÃY1§«s•0¿&zLf#¸∆≥/%y-≤À£3-Ñ¬íÕKê£L∂ŒÅ…◊0ú≥íÎ∏[,§ÀÃµ,ú±í´Ñß0î±”(ã.D¿°@œ¡2ÔL+.|£í˜§…2Ë(≥L•*¥πS:\0Ÿ3¥ÃÌÛG3lÃ¡aÀêl≥@L≥3z4≠«Ω%ÃíÕL›3ªÖ≥º!0ä33=L˘4|»ó°‡+\"∞ È4¥ÀÂ7À,\$¨SPMë\\±Œ?JäYìÃ°πΩ+(¬a=K®Ï4ú§≥CÃ§<–ÅÖ=\$ç,ª≥UJ]5h≥W†&t÷I%ÄÈ5¨“≥\\M38g¢ÕÅ5HäN?W1Hö±^ Ÿ‘∏ìYÕóÿ†èÕè.ÇN3Mü4√Ö≥`Ñéi/Pâ7÷dM>ödØ/ùLRŒ‹‚=Kë60>ØI\0[ı\0ﬂÕ\r2Ù‘ÚZ@œ1Ñ€2ˇ∞7»9‰FG+‰Ø“ú≈\r)‡hQtL}8\$ BeC#¡ìr*H»€´é-õH˝/ÿÀ“6»ﬂ\$¯RC9¬ÿ®!ÇÄ≈7¸k/PÀ0Xr5É°3DêÑº<T¡‘íqØKÙ©≥nŒHß<µFˇ:1SLŒr¿%(ˇçu)∏Xró1—ÄnJ√IÃ¥S£\$\$È.Œá9‘È≤IŒü“3 ®L√lîìØŒô9‰≈CïN†#‘°Û\$µ/‘Èsù…9´@6 tì≤ÆNÒ9º¥∑N…:πí¬°7Û†”¨Õ:D·”¡M)<#ñ”√M}+Ò2ŒN˛Ò≤õO&Ñ¢JNy*åÚÚŸ∏[;ÒÛŒO\"m⁄ƒÛ≈Mı<c†¬¥Ç∞±8¨K≤,¥”«N£=07s◊JE=T·≥∆O<‘Ù≥£JÈ=Dì”:œC<Ãì‡Àâ=‰ËÛÆKê ªÃ≥»L3¨˜≠èÑLT–Ä3 S,ú.®ˇœq-åÒsÁ7Õ>Ç?Ûº7O;‹†`˘OA9¥ÛÒœª\$ú¸¡O—;Ï˝`9Œn«IÅAåxp‹ˆE=Oπ<¸≤5œŒÑ˝2∏Oç?d¥éÑ¥å`NÚiOˇ>å˛3ΩP	?§Ú‘Oûmú˙SMÙÀ¨∑Ü=π(„d„§A»≠9èìë\0Ì#¸‰≤@É≠9Déç¡…&‹˝ÚäÇ?ú†ì–i9ª\n‡/ÄÒA›ÛÚ»≠A§˝SÀPo?kuN5®~4‹„∆6ÜÜÿ=Úñåì*@(ÆN\0\\€îdGÂ¸p#Ë§>†0¿´\$2ì4z )¿`¬Wò†+\0äë80£Ëè¶ï†§™î‰z\"T–‰0‘:\0ä\ne \$ÄérMî=°r\n≤NâP˜Cmt80˙ #§ÿJ=†&–∆3\0*ÄùB˙6Ä\"ÄàÈË˙Ä#èÃ>ò	†(Q\nåÍ¥8—1C\rt2ÉECà\n`(«x?j8Nπ\0®»[¿§QN>£©‡'\0¨x	cÍ™\n…3è◊Ch¸`&\0≤–¥8—\0¯\n‰µ¶˙O`/ÄÑç¢A`#–ÏêXcË–œD ˇtR\n>ºÅ‘d—BÚD¥L–ƒÃıâ‰–ÕDt4–÷†jîpµGAoQoG8,-s—÷‘K#á);ßE5¥TQ—G–4Ao\0†>tM”D8yRG@'PıC∞	Ù<PıCÂ\"îK\0íêx¸‘~\0™ei9–Ïúv))—µGb6âÄ±H\r48—@ÇMâ:Ä≥FÿtQ“!Hïî{R} ÙURpèÕ‘O\0•IÖt8§ÿ˚Œ«[D4F—Dç# —+DΩ'ÙMè ï¿>RgI’¥äQÔJ®îîU“)Em‡è¸TZ≠Eµ'„Í£iE›¥£“qFzA™∫>˝)TãQ3H≈#TL“qIjNTΩºÖ&C¯“hçX\nTõ—ŸK\0000¥5Äà¢JH—\0ìFE@'—ôFp¥hS5Fù\"Œo—Æêe%aoS E)† ÄìDU†´QóFmŒ—£M¥——≤e(tn“ ìU1‹£~>ç\$Òﬂ«Çí≠(h’«ëG¸y`´\0íÍ†	ÉÌGÑÚ3‘5Sp(˝ıP„GÌ\$îú#§®	©Ü©N®\nÙV\$ˆç]‘úP÷=\"R”®?Lzt∑É1L\$\0‘¯G~Â†,âKN˝=îÎ“GM≈îÖ§NSÄ)—·O]:‘äS}›81‡RGe@CÌ\0´OPSıNÕ1Ù›T!Pï@—›SÄˇ’SâG`\n…:ÄìP∞jî7RÄ @3¸—\në ¸„˜è‚£îD”†Ê˙L»œºé†	ËÎ\0˘Q5Ùµ©CP˙µSMP¥v4Ü∫?h	hÎTáD0˙—÷è‡ı>&“ITxÙOº?ï@U§˜R8@%‘ñåıKâÄßNÂK„ÛRyE≠E#˝˘ @˝√¯‰%L‡´Q´Q®µ£™?N5\0•R\0˙‘ÅTÎFÂ‘îRüSÌ!oTE¬C(œ∂ê»˝ƒµ\0Ñ?3iÓSS@U˜QeMµÉ	Kÿ\n4P’CeSîë\0ùNC´PÇ≠Oı!†\"RTê˚ıÄèS•N’è¡U5OU>UiI’PU#UnKPÙ£UYTË*’Cè´U•/\0+∫∏≈)»⁄:ReA‡\$\0¯é§xÚ«WD∫3√Íè‡`¸⁄¸ÁU5“IHUYîÙ:∞P	ıe\0ñMJiÄÉµ√˝Q¯>ı@´T±C{õ’u—Ï?’^µv\0WRç]U}CˆÍ1-5+U‰?Ì\rıW<∏?5ïJU-SX¸’L‘ﬂ \\t’?“sM’bÑ’ÉV‹ÅtßTå>¬MU+÷	E≈càœ‘9Nm\rR«ÉC˝8éS«Xï'R“ÈXjCI#G|•!QŸGhïtQç∏˝ )<πY–*‘–RmX0¸ÙˆΩM£õıOQﬂY˝h¿´ﬂdu’§’Z(˝Ao#•NlyN¨VÄZ9I’ç∫Mï¶V´ZuO’ÖT’T≈E’á÷∑SÕeµµ÷ \nµXµ™S€QERµ≥‘Ÿ[MF±VÁO=/ı≠è®>ıg’πTÌVçoUèT≥ZíNÄ*T\\*√Ô–◊S-pµS’√V’qÄ“M(œQ=\\ç-UUUV≠Cïƒ◊Zÿ\nuíV\$?M@UŒWJ\r\rU–‘\\Â'U◊W]ÖWî£W8∫N†'#h=oCÛ–˝F(¸È:9’YuïÜ§˜V-U”9ü]“C©:Uø\\ê\nµqWóô‡(TT?5P·™\$ R3’‚∫üC}`>\0ÆE]à#RÍ‡	Éˇ#R•)≤Wñíù:`#ÛGı)4äR¿˝;ı·ViD%8¿)«ì^•QıÈ#îh	¥H¬éX	É˛\$N˝x¥ö#i x˚‘íXRıÄ'‘9`m\\©Ü®\nE¿¶Q±`•bu@◊ÒN•dT◊#YY˝ÑµÆGVç]j5#?L§xt/#¨îÂ#ÈÖΩO≠P’ÎQÊ¢6ï££œ^ÌÜ Äöé¸÷ÿM\\R5t¥”öp‡*ÄÉXàV\"W≈DÄ	oRALm\rdGèN	’÷¿˙6îp\$ùPÂ∫üE5‘˝Ü©Tx\nÄ+ÄãC[®ÙVéå˝ç÷8UïDu}ÿªF\$.™ÀQ-;4»Ä±NX\nè.XÒbÕêï\0Øb•)ñ#≠N˝G4Kÿ–ZSî^◊¥M∂8ÿÛd≠\"CÇ¨>≈’dHe\nˆY8•è—.Í ˙∞à“èF˙DîΩW1cZ6îõQ‚KH¸@*\0ø^∏˙÷\\QﬂFÇ4U3Y|ë=ò”§ÈEõ‘€§¶?-ô47YÉPmôhYw_\röVe◊±Mò±ﬂŸèe(0∂‘F’\r†!“PUIïu—7QÂïCË—é?0ˇµè›gu\rq‡§ßY-QËÛ∞Ë˙=g\0Ö\0M#˜U◊S5ZtÆ÷üae^ï\$>≤ArVØ_\r;tÓè¨í®îHW©ZÌ@H’ÿhzDË⁄\0´S2Jµ HIÂO†'«ÅeÌg…6π[µRî<∏?» /è“KM§ˆñÿ\n>Ω§H·Z!iàˆ§üTX6ñ“◊i∫C !”õgΩ‡ “G }Q6û—4>‰w‡!⁄ôC}ßVB÷>Â™UQ⁄ëj™8cÔUçT‡˚ñ'<Ç>»˝ıÙHC]®Vö—7jj3v•§Â`0√Ë»23ˆ∞–Úx˚@Uók†\nÄ:Si5û’#YÏ-wÓî’‡ÈM?cÈ“MQ≈GQ’—Éb`ïÚ\0é@ıÀ“ß\0M•‡)ZrKX˚÷üŸWl≠≤ˆùèÕlÂ≥TM◊D\r4óQsS•40—sQÃÅımY„hïd∂¬C`{õVÄgE»\nñªXk’Å‡'”Ë,4˙ºπ^Ì¢6∆#<4ÅÈNXnM):π∑OM_6dÄñÊı∏√ı[\"KU≤nû÷?l¥x\0&\0øR56üT~>†ÙÜ’∏?îJnûÄí àœZ/i“6ÙŒ⁄glÕ¶÷U€·F}¥.û£ºçJLˆCTbMé4Õ”cLıTjSDí}JtåÄçZõ™µ«:±L≠Ä¥d:âEzî §™>ç÷V\$2>≠µé¢[„p‚6ˆ‘Ré9uÍW.?ï1Æ£RHuûË€R∏?58‘Æ§ÌD›∆uÉ£Áp˚cÏZ‡?úr◊ª Eaf∞ê}5wY¥ÎÂÇœí“Í≈WÇwT[Sp7'‘_aEk†\"[/i•ø#ˇ\$;mÖfÿ£WO¸Ùî‘FÚ\r%\$Õju-t#<≈!∑\n:´KEA£Ì“—]¿\nUÊQ≠KE¿†#ÄøXÂ®˜5[ >à`/£ÕDµ ÷≠VEp‡)èÂI%œqﬂ‹˚nÌx):§ßle¢¥’[e’\\ïeV[jÖñ£È—7 -+÷ﬂGçWEwtØWkE≈~uÏQ/mı#‘êWó`˝yuì«£D›Aˆ'◊±\r±ï’ôOùD )ZM^Ä≥u-|v8]ãgΩëhˆ◊≈L‡ñW\0¯»˚6ÀXÜë=Y‘dΩQ≠7œìîœ9£ÁÕ≤r <√÷èÍD≥∫B`c†9øí»`èD¨=wx©I%‰,·Ñ¨ÜË≤‡ÍÉj[—öù÷ÌﬂOˇã¥ ``é≈|∏ÚÚ∆ﬁ¯§åòºÌ.Ã	AOä¿ƒ	∑â@Â@ 0h2Ì\\‚–ÄM{e„Ä9^>Ùï‚@7\0ÚÙÀÇWíÄÚ\$,Ì…≈ö°@ÿÄ“‚ïÂ◊w^fmÂâ,\0œyD,◊ù^XÄ.Ø÷Ü©7„∑õ√◊2›≈f;•Ä6´\nî§éÖ^üzC©◊ßmzÖÈnñ^àÙî&LFFÍ,∞ˆ[Ä•e»ıaXy9hÄ!:zÕ9cÚQ9b≈ !Ä¶µGw_W…g•9©è”S+tÆ⁄·p›t…É\nm+ñúﬁŸ_	°™\\ºíùk5£“‹]∆4à_hï9 Ÿ˜NÖêó≈]%|•à7À÷úé];îÔ|ùÒµ†ﬂX˝Õ9’|ÂÒ◊ÃG¢ì®[◊‘\0ë}UÒîÁﬂMCçI:“qO®V‘Éa\0\rÒRÕ6œÄ√\0¯@H¢≈P+rÏS§W„ËÄ¯p7‰I~êp/¯†Hœ^›Í≤¸§¨Eß-%˚•ÃªÕ&.Œƒ+∏J—í;:≥∂´!ì˝–N	∆~ˆ™âÄ/ìWƒ¬!ÑBËL+¬\$Ìqß=¸ø+—`/∆ÑeÑ\\±“œx¿pEëlpS¬JSç›¢Ωˆ6‡á_π(≈Ø©ƒÈb\\O∆ &Ïº\\–59ù\0˚¬Ä9nÒè¯D∏{°\$·∏ãKêëv2	d]ËvÖCÅ’˛≈’?Åtf|W‹:£‘®p&ø‡LnÑŒË≥ûÓ{;àÁ⁄GÅR9¯êT.yπ¸ÔI8Äπ¥\rl∞ ˙	TË†nî3ºˆT.É9¥Ë3õ†öºZËs°Ø—“GÒ˛éà:	0£¶£zË≠›.å]¿Áƒ£Qõ?‡gTª%Òô’xå’å.Ñö‘«n<Ï£-‚8BÀ≥,BÚÏòrgQ˛¢ÌﬂÛÑ…é`⁄·2ÈÑ:ÓµΩ{ÖgÎƒsÑ¯gÛZøïÖ ◊å<Ê◊w{¶òÉbU9à	`5`4Ñ\0BxMpë8qnahÈÜ@ÿºÌÜ-‚(ó>S|0ÆÖæ•Ö3·8h\0—´µC‘zLQû@∂\n?Ü∏`A¿†>2ö¬,˜·òÒNÅ&å´xàl8sah1Ë|òBá…áDçxBﬁ#VóãVñ◊ä`W‚a'@õá¨	X_?\nÏæ  ï_‚Å. ÿPºr2ÆbUar¿I∏~·ÒÖSì‡˙\0◊Ö\"†2Ä÷˛¿>b;ÖvPh{[∞7a`À\0ÍÀ≤jóoå~∑˚˛vÕŸ|fvÜ4[Ω\$∂´{ÛØP\rvÊBKGbpÎ»≈¯ôñOä5›†2\0j˜ŸÑLéÄÓ)«m·»V°ejBB.'R{C§ÔV'`ÿÇ âé%≠«Ä–\$†OÂù\0ò`Çèí´4 ÃNÚ>;4£≥¢/ÃœÄ¥¿*¬¯\\5Ñ≈¡!Ü˚`X*ﬁ%ÓƒNÕ3SıAMÙ˛À∆î,˛1¨≤ÆÌ\\Ø≤caœß ≥˘@ÿ¨ÀÉ∏B/Ñ¨Õ¯0`Ûv2Ô°Ñßå`hD≈JO\$ÁÖ@p!9ò!•\n1¯7pB,>8F4ØÂf†œÄ:ìÒ7¬ÑÓ3õ£3Öø‡∞T8ó=+~ÿn´Œ‚\\ƒe∏<br∑˛†¯Fÿ≤∞ êπC°Nã:cÄ:‘lñ<\rõ„\\3‡>Òòá¿6ÅONnä‰!;·Ò@õtwÎ^FÈÄL‡;Ä◊∫,^aè»\ra\"ﬁ¿⁄Æ'˙:Ñv‡Je4√◊ê;ïÒ_d\r4\rÃ:€¸¿¨Sêòè‡ê2ÅÄ[cÄÑXˇ ¶Plò\$πﬁ£êiìwÂd#éB†öbÅõŒ◊§ıíô`:ÜÄœ~ <\0—2Ÿ∑óëRå¬∆P»\r∏J8D°t@ÏEéË\0\rÕú6ˆÛ‰ﬁ7ïΩ‰òYœ£˙\"Â‰¿ö\r¸É¶¿ö3É°.ò+´z3±;_ üvLè›‰”wJø94¿IêJa,A¶ÒàØ;És?÷N\nRùá!éß›êÜOmÖs»_Ê‡-z€≠wÑÄ€z‹≠7°Õ≈zÓ˜ñMçîàÄoøî•Ê\0¢Éaî≈›π4Â8ËPfÒYÂ?îÚióñeBŒS‡1\0…jDTeKîÆUYSÂ?66R	¶cı6Ry[c˜î∞5Ÿ]BÕî÷R˘_eA)&˘[ÂáïXYRWñ6VYaeUïfYeÂwïéUπbÂwîEÎ∞ Ü;z§^W´9ñ‰◊ß‰›ñıÎ\0<ﬁòËeÍ9SÂŒ§da™	î_-Ó·âL◊8«ÖÕQˆËTH[!<p\0£îPy5à|ó#ÅÍëP≥	◊9v‡ö2¬|«∏ù·faoÜ·,j8◊\$A@kÒÉøéaÀëΩbÛcÒ»f4!4®ë∂cr,;ôëÊëˆb∆=Ä¬;\0∞¯≈∫ÖòÜcd√ÊXæbÏxôaôRx0A„h£+wxN[ò‹Bê∑p⁄ÉøwôT¿8T%ôöMöl2‡áΩ°öêó}°»s.kYÑò0\$/ËfUÄ=˛ÿsÑgK√°àMõ ı?ˇõÁ`4c.‘¯!°&ÄÂàÜg∞˚f‡/˛f1ê=ØõV AE<#Ãπ°f\nª)†äÎõNpÚì„`.\"\"ªAÁú§„ó¸q∏ÅXì†Ÿ¨:a…8ôπfØôVsÛãGôﬁré:ÊVﬁ∆c‘gùVlôùg=ùÅ`„ìWéÀ˝y“gUù¿Àô™·∫ºÓeT=†„Ä·Ä∆x 0‚ Mº@àªö¬%Œ∫bΩú˛wô∆f€ŸO¯Á≠ò‹*0ØÖÆ|t·∞%±ôP»ÕpÊ˙gKû˘¨?pÙ@J¿<BŸü#≠`1ÑÓ9˛2ÁÅg∂!3~ÿ‹ÁÓnl‰≈fäÿVh˘¨é.—Ä‡ÖaC—˘ï?≥ä˚-‡1ú68>A§àa»\ró¶yã0†÷iëJ´}†‡πù©†–z:\r°)ëS˛Ç°@¢Âh@‰ˆÉYπ„¥mCEg°cyœÜçÇ<ı‡Õh@º@´zh<WŸƒ`¬ï®±:zO„Œ÷\rÕÍW´ì∞V08Ÿf7ô(GyêÉ≤`St#ÅÔÑfÜ#É≤ÅúC(9»¬òÿÄd˘ÊÊ8T:Øªå0∫Ë qµ††79∑·£phAg‹6ä.„Ê7Frôb‰ »jöËA5ÓÖÜÉ·°a1˙⁄hïZCh:ñ%πŒgU¢D9÷≈…àÑ◊πœÈ0~vTi;ùVvSöÑwúÿ\rŒÉ?‡«f≤£Öˇ•näœõiYôÏa∫¨3†Œá9’,\nô√rëâ,/,@.:ËY>&ÖöF—)è˙ôç∂}öb£ÄËiO›iùÊö:dËAånòöc=§L9Oíh{¶ê 8hY.íŸ¿ÆæáÆáÖú¸«\r¨ç÷á£¿õäÈ1QØU	îCëhÙÜeˇOâõ∞+2oÃŒÏﬁNãò˜ß¯zpË¢(˛]”hÄÂ¢Z|¨O°c—zD·˛Å;ıT\0j°\0Ö8#ç>Œé¡=bZ8FjÛÏÈ;Ìﬁ∫TÈÖ°wÆÕ)¶˝¯N`ÊÎ®§√ÖB{˚Éz\rÛ°cì”Ë|dTGìiú/˚˙!iÜ 0±º¯'`Z:äCHÔ(8¬èÍ`V•ô⁄„ˆ™\0‹Íß©Ü£WÔﬂ«™ò’zgGæëÖÉΩ≤-[√–	iúÍN\rq∫È´nÑÑìo	∆•fEJ˝°apbπÍ}6£Ö’=o§ñÑ,tËY+ˆÆEC\r÷Px4=ºæôŸ@áâ¶.ÜëF£ç[°zqÁ‹ËX6:FG®†#∞˚\$@&≠ab§˛hE:≤ÉÂ¨‰`∂S≠1ó1g1©˛Ñ2uhYã¨_:Bﬂ°dcÔñ*ˇ≠Ü\0˙∆óFYFú:À£™nÑÿÃ=€®H*ZºMhkê/çÎÉ°ûzŸπÔã¥]ö¡h@ÙÊ©ÿ„1\0ò¯ZK˘û¢ÎŒ∆Ë^+∫,vfÛsÆö>à§íO„|Ë¿ s√\0÷ú5ˆXÈãÓ—ØFÑ˜nøAàr]|œIi4ËÖ˛ ÿ¬C∞ h@ÿπ¥üûñcﬂ•®6smO√ÂâçôõgX¨V2¶6g?~÷√Y’—∞Üs˙cl \\Rä\0å®cúùA+å1∞Ñõ˘ÃÈç\n(—˙√Ã^368cz:=z˜Ç(‰¯ ;Ë£®Òès¸F∂@`;ÏÄ,>yTﬂÔ&ñïdΩL◊üúˇ%“É-ÎCHL8\rá«b˚∞∞£˙Mj]4êYm9¸€¸–Z⁄B¯ÔP}<ü˚‡X≤ØâÃ•·+g≈^ÿMﬁ + B_Fd¨XÑ¯ãlÛw»~Ó\r‚ΩãË\":‘ÍqA1XæÏÊ≤–¯Ø3÷ŒìE·h±4ﬂZZ¬Û∏&†ÖÊÊ1~!NÅf„¥ˆoóàô\nMe‹‡¨ÑÓÎXIŒÑÌG@V*XØÜ;µY5{Và\nËªœTÈz\rF†3}m∂‘p1Ì[Ä>©tËe∂wôüÊÎ@V÷z#Çù2ƒÔ	iÙÙŒ{„9ÉÇpÃùªghëäÊ+[elUâ¶€AﬂŸ∂”ºi1ƒ!åæommµ*K‡áÍ}∂∞!Ì∆≥Ì°Æ›{me∑f`ìómËòC€z=ûnﬁ:}g∞ TõmLu1F‹⁄}=8∏Z·ÌËOû€mFFMf§ÖOOÄÓ·¿ãÉË¯ﬂ/ºÈı∏ﬁìöÂÄ˛Vôoqj≥≤Ën!+ΩêÚµ¸Z®ÀIπ.Ã9!nGπ\\Ñõ3aπ~ÖO+ŒÂ::ÓK@å\n⁄@Éë§Hphë¥\\BƒıdmùfvCËû”P€\" ÊΩ€.nW&ñÍn¢¯HY˛+\r∂ìƒz˜i>Mfq€§Ó≠∫˘›QcÇ[≠H+Ê¿o§—*˙1'§˜#ƒÅEwÄD_XÌÅ)>–s£Ñ-~\rT=Ω£û‡˜à‡- ÌyßmßπÊ{ÑhÛüÃj⁄MË)Ä^ûπÔ¿'@VÂ°+i»ÓŒÚõüÂµÜ…;Fì†D[Œb!ºæè¥B	¶§:MPãÓÛ€≠oCºvAE?ÈC≤IiYÕÑ#˛p∂P\$k‚JﬁqΩ.…07ú˛ˆxàl¶sC|ÔΩæboñ2‰X™>MÙ\rl&ª«:2„~€—cQ≤ÓÚ≤Êo—ﬁd·Ç-˛ËU‹RoÇYönM;ín©#ñﬂ\0ñPæf⁄Po◊ø(C⁄v< ¨¯[Úo€∏îö˚◊f—ø÷¸¡;ﬂ·∫ñı[˙Yü.oÆUpøÆÅpUå¯î.û†©B!'\0ãÚ„<TÒù:1±¿æ†ö„§Ó<ÑõnàÓF≥ÉI¢«î¥ÇV0 «ÅRO8âw¯Œ,aF˙º…•π[¥ŒüÖÒYO˘´âÄ/\0ôŸoxÅ˜«Q?ß∞:ŸãÎ∆Ë`h@:É´øˆ—/MÌmºx:€∞c1§÷‡˚ØÌv≤;ÑÇË^Êÿ∆@Æı@£˙Ω¬«\n{Øº¬Óã‡;Áë¥BºÌ∏8ë∫ gÂùí‰\\*gÂyC)€ÑEù^˝Oƒh	°≥¶AÉu>∆Ë¸@‡DÃÜYÊºÌõ‚`oª<>¿Épâôäƒ∑íq,Y1Q®¡ﬂ∏Üè/qgå\0+\0‚ÊÂáDˇÉÁ?∂˛ Ó©⁄ﬂÓk:˘\$©˚¨Ì◊•6~I•Ö=@éÌ—!æ˘v⁄zOÒÅö≤‚+Õı∆9«i≥ñõºaÔÜÍ˚ÖgÚÙÓøùóπˇ?Åö0Gnòq≤]{“∏,F·√¯O°‚Ñﬁ <_>f+¢è,ÒÃ	ª‘Ò±&ÙúÜÌ¬∑ºyÍ«©O¸:¨U¬ØàL∆\n√√∫I:2≥ø-;_ƒ¢»|%ÈÂ¥ø!Œıfû\$¶àÜXr\"KniÓÒó¿–\$8#õg§t-õÄr@L”ÂúèË@S£<ërN\nêD/rLdQk‡£ìî™ıƒÓeÂ‰„–≠Â¯\n=4)ÉBòîÀ◊öÙÃZ-|Hb°ÅÜëHk *	÷Q!–'ÅÍG ûõYbt!ø (n,ÏP≥Ofq—+XìY±ˇÇÎ\"b F6÷Ãr fÚù\"“‹≥!N°Û^º¶r±B_(Ì\"®K _-<µÚ†*Q˜Ú®Ÿ/,)ÅH\0ùÑâ≤rÁ\"z2(πtŸá.F>Üá#3‚Æÿ¶268shŸ†˛®∆ëI1Sn20∂Á -ç´4í⁄«2Aús(¨4‰ºÀ∂äÅ\0∆›#ÑÂr˛K'ÀÕ∑G'ó7&\n>xﬂ¸‹JÿGO8,ÛÖ0º‚ã˘8î—”\0ÛW9í›Ià?:3n∫\r-w:≥¬Ã≈◊;3»âî!œ;≥‹ÍÉòòZíRMÉ+>÷‹ È0/=RÖ'1œ4’8˚ù—œmˇ%»•}œá9ª;Ç=œnQˆ„=œhhLı∑GœkWŒ\rÙ	%ÿ4“úsÒŒñJÄ3s€4ó@ôUÇ%\$ç‹—N;Ã?4≠ªÛN⁄œ2| ÛZ⁄3ÿh\0œ3ì5Ä^¿xi2d\r|˚M∑ £bh|›#v«` \0îÍêÆ‰‡˚\$\r2h#è˙§?≥àèI\níºç+o-úä?6`·πΩø.\$µö¯KY%ÿ¬ÅJ?¶c∞RèN#K:∞K·EL¡>:¡•@å„jPëÃn_t&slmí'Ê–©…∏”ú≤åΩó„;6€óHU5#ÏQ7U†˝WY‹U bNµñW˚_˚™©;TC¯[›<⁄ñ>≈«ıâW˝CUÅ‘6X#`MI:t˘”µÄˆ	u#`≠fu´\$´t≠ÅˆXÛ`çf<‘;bÂghˆ—’9◊7ÿS58ı¨›#^ñ-ı\0Í¿˙Ó’πR*÷'£®(ııqZÂ££ÍXπQ›FUv‘W GWÌÒ”TÍ«WÙ~⁄≠^ßWˆƒ¡’˝J=_ÿóbm÷›bV\\lÅ∑/⁄M’ˇTmTOXu =_è˝ITvvuãa\rL_’qR/]]m“su=H=u—g o\\U’ÖgM◊	XVU†¿%ıh˝°53Uô\\=°ˆQﬂÿMπváÄ°gÂm‡ıue°ùàŸ˚hˇb›M›GCeO5Æ‘Å÷O5Ö‘YŸi=e’	GùTURvOa∞*›ivWXïJ5<ıØbu†]à◊÷˙µ<ı√Ÿ’\$u3v#◊'eˆu—R5mïävãD5è.véåıW=üU_Â(¥\\Vÿœ_<ı˜SÕn)‹1M%Qh·ZáTÖf5E’'’ÕWΩäv≈Umi’ÇU‘’]aW©UßdRv·Ÿ-YUZuùŸUVùóUiRçVùôı≥”«[£ÌZMUß\\=¬v{€X˝µºwQ˜huHv«◊gq›¥w!⁄oqt¢U{TGq˝{˜#^G_ubQÑÍÂïi9Qb>⁄NUd∫±kÖΩ5hPŸmu[ï\0è¶Í≈_∂È[ıY-èÙ˜rı»’(÷CrMe˝Jı!h?QrX3 xˇ»œ#á˜x÷<€{u5~ÉÌ—-›uéÎYyQ\r-îÓ\0˘u’£uuŸøpU⁄Öï)ñPÂ‹\r<u´Sõ0›…wπﬂ-i›Û‘!Ã÷ä¯B˜·∆d]˘Ë≈á‘∆EÍvlmQ›è6kº“J¥àwÌ¶ƒûÿ√„åED∂UŸRìeçv:XﬂcÿNW}`-®t”H#eÑÅb∫±uÄ„Û	~B7Í ?É	OPúCWêµ◊SEÕïV>∂ì◊U€7ﬂûÁâ‘·mª”Ç¨zˇ=µÉÕÿ1∫ôÉ+†πm√I,>µX7‡‰]†.áΩ*	^Óä„∞NÖ∫.ËŒ/\"Ñèò)–	ÖØÇsûÆ|‡§Á”ü–l¡}„∏éÕÁ!ÛÓÉë5n±pÑj£æhí}ΩËmìE·zH¬aO0d=A|wÎﬂ≥„Î◊öŒÏu≤úüv˘ÿºGÄx#ÆÖbîcSo-â˘tOm`CãÚ^Må≈@Î¥h≠n\$k¥`˛`HD^ùPE‡[‰å]π®rR∏mû=Ç.ÒŸá>AyiÇ \"˙ÄÚ	÷∑o„-,.ú\nq+¿•ÂfXdä´∂„*ﬂΩàKŒÿÉ'‹Í –%aÙˇá˘9p˚Êó¯KLMÑ‡!˛,Ë Àé®åzX#òV·ÜuH%!¿ú63úJæry’ÅÌ˘q_Ëu	˙W˘±á∆|@3b1Â»7|~wÔ±≥˛ÌA7ì“¬õËô	ºô9cS&{„‰“%VxÔkZOâ◊wâUr?ÆÑí™N Œ|ÖC…#≈∞ıÂ’Ø π/˙ô9ÅftéEw∏C¡∫a¶^\0¯O<˛W¶{Y„=ÈüeÎò˝n…ÑÌgyf0h@ÏS›\0:Cê©¥^Ä∏VgpE9:85√3Êﬁß·∫è@ª·éj_™[ﬁ+´Í«©xÉ^ìÍÆÜ~@—áW™∏„„ìúÜ9xóFCòø≠.ê„öÁˆ¸k^Ié˚°pU9¸ÿSüÿ˜Ωóú\$ÛÛ¯\r4¥Ö˘\0ŒËO∞„ëƒ)L[¬p?Ï.PECSÏI1nm{≈?ûPÓWAﬂ≤¡;ÄÒÏD∞;S∫aèKf¯Úõ%è?¥Xıﬁ+è§B>Ω˘9øØŸGjòcûzëAÕé˜:Ía≥n0bJ{o•∑!3¿≠!'íÿK√≈Ì˘‘}„\\ËŒ3W¯Í5Óxœ…¡L;É2Œ∂nóa;≤ÅÌ◊∫X”õ]…o∫úx˚{‰¶5ﬁôjX˜àó∂v”öÈ„qﬁ EE{—Ä4¡æˆƒ{ÌŸÁ	Ã\nˆ >˘ôaÔØ∑æ¸ÏßÔÿL˚‘˚ÂÔˇΩ˚ÏÒ'ΩﬁÈ{Î\nâó>J¯ﬂåå·∏”óÜ˜Yœ\rO ΩëtØˇ˚•-O√¶¸4‘ˇ9F¸;ß¡ª‘¸G¯I™FﬂÏ1¬oˇﬂÛÒO≤æÈa{wó0”ªÔ§∆Ø;ÒîÑël¸oÒ‡J–Tb\rw«2ÆJµ˛=D#Ún¡:…yÒ˚S¯^„,.ø?(»I\$Ø ê∆ØÌ®·3˜√s4M aCR…∆ÕGÃëú˙Iﬂ∞n<˚zy—XNæ?ı‚.√Óê=ó‡Ò¥D«ºç\rõûÿÈ\n’Û®\roı˝\n–üCl%¡ÕYŒ˚•ﬂ∞œ‡G—˛⁄}#ùV–ù%˝(‘ˇ“‡3Ê…çòrû};Ù˚◊øG…Ãnˆ[™{•πñì_<m4[	I•¢¿ºq∞µ?0cV˝nmsÑ≥nMııà\"Nj1ıw?@Ï\$1¶˛>“^¯’˚•ˆ\\Ã{n¬\\ÃûÈ7üÑøŸüic1Ô⁄ˇhooÍ∑?j<Gˆxülœ˘©SËr}Õ√⁄|\"}ï˜/⁄?sÁ¨tI‰ÂÍº&^˝1eÛ”t„Ù,è*'F∏ﬂ=ù/FÅk˛,95rV‚·¯‡¿∫ÏëàÅ€o9Õ¯/F¿ñ_Ü~*^◊„{–I∆ˆØ„_ÉÇ≤åì^nÑ¯˛Nüä~¯·≈AÌ¶ëd©ÂÒ˛U¯w‰qY±ÂÓ¥T∏2¿ÈG‰?á&ñßÊÙ:y˘Ë%üñXÁòJ€C˛d	WËﬂé~˙G!Ü¥J}õó§˙Ï˘ıƒB-”Ô±;Ó˚úh√*ÛºR¥ÏˆE∂†~‚ÊÛ.´~…ÁÊ†SAqDVx¬ÓÕ='Ì…EŸ(^ä˚¢~õ˘¯øõÁÚÈÁÔo7~ÇM[ßÅQ„Ó(≥‹y∏˘nP—>[WX{q‘aœ§∆…˝.&N⁄3]Ò˙HYÔ›˚ÉÎ€[∂¡Ÿ&¸8?—3Ñãõ¶∂ß›Ü⁄ª∂·#å¶ŒBeù6ùÎÖ@ñì[∞§£˚‡–G\rŒ+˝ß}¸ò˜¡ˇœ_›Á7ñ|NÑß´ﬁ4~(z¡~ìªπÔß%õñ?±ﬂ”»[π¯1ûS™]xÿkˆ—KxO^ÈAçÄârZ+∫ˇªΩ*¬WˆØk˛wD(π¯ªR:Ê˝\0ïßÌç˘'§äÛìm!O–\n‰≈uËÇ∆Û.ê[ ÅP∆!π≤}◊œm €Ô1pÒu¸‚,T©ÁL 	¬Ä0}ù‚&PŸ•\nÄ=Dˇ=æÒ–\r¬öA/∑o@‰¸2„t†6‡DK≥∂\0»¬ÉqÜ7Ñl†ºBÍä˙Ã(É;[Òàkr\rë;#ë√‰Él≈î\r≥<}zb+‘–OÒ[ÄWrXÉ`ÅZ ≈£ÜPm'Fn†ºâÓSpﬂ-∞\0005¿`d®ÿ˜PÑ¡⁄«æ∑€;≤Ãn\0Ç5fÔPÑèøEJ‰w˚€ π.?¿;∂ßNÚﬁ•,;∆¶œ-[7∑ﬁe˛⁄i≈‚-ì÷ÓdŸé<[~î6k:&–.7á]Å\0Û©Å˚Îñ˘çè/µ59 Ò¡@eT:ÁÖòØ3≈dês›ù˙5‰èú5f\0–PµˆHBñïÌ∞Ω∫8J‘LS\0vI\0àô«7Dmê∆aû3e◊Ìé?B≥™\$¥.EãÅ–fçèÀ@™n˙ÉâbÚGb¡œq3ü|¸öPaÀà¯œØX7Tg>¬.⁄pÿÔôí5∏´AH≈µíä3S,ò¡@‘#&wµÓ3ÜÙm[œ¿ÚIÌ—•”^ìÃ§J1?©gT·ÅΩ#œS±=_ÑÇ_Å±	´£…Vq/C€æ∑›ÄŒ|ÀÙ·˛êD Ég>‹ÑıÎÈ 6\rä7}qî∆≈§ãJGÔB^ÓÜ\\g¥›ı¸Åú&%≠ÿ[™2Ix√¨™Ò6\03]¡3å{…@RU‡ŸMˆ†v<Â1äøëæsz±uPí5ü™F:“iÓ|¿`≠q”˜ÜV| ª¶\nkê‚}–'|égdÜ!®8¶ <,ÎP7òm¶ª||ªˇ∂IéA”Å]BB œFˆ0Xœ˙≥	äD÷ﬂ`W†µ¡qm¶OLë	Ï∏.Õ(¡pÇº“Å‰∂\"!ãè˝™\0‚ÕAÔ√Ùáâ¡VÄñ7kÉåM∏\$”N0\\’ßÉ\"ãfë·†«ÎÒ†»\0uqûó,å†5∆„A6◊pŒŒ»\nŒêjY≥7[pK∞4;êlú5n©¡@‚\\f˚–l	¶ÇMˆ˘˚P¡Á3ÆóC†Hb–å©∏cEpPâ⁄–4eooe˘{\r-‡ö2.‘÷•ΩåP50u¡≤∞G}ƒ‚\0ÓÀı®<\rˆú!∏ú~ ˝µæÛÒπ\n7FùÆd∂˝‡ìú>∑‘a¢Ÿ%∫c6‘ûßıM¿•|Ú‡dã˚∑ÏO”_®?JÑÊ™C0ƒ>–Å¡&7kM4™`%fÌlŒòB~¢wx—⁄ZGÈPÜ2Ø‡0¸=û*pÜ@àBe»îÿœ|2ƒ\r≥?q∏–8Ì∏Î±ÒÕ–ä(∑yr·ˆ†0‡Ó>ú>¿E?w‹|r]÷%Av‡˝¡≈‰@é+›X¡™Ag‚…€ˇs˚ÆC–˚AXmN“ù˙4\0\r⁄ÕΩ8J›J«∏Dè“öÛ¥:=	ïÛáÎ∆Sô4ØÒF;	¨\\&÷ËÜP!6%\$i‰xi4cΩ0B·;62=⁄€1¬˘ÃàPCÿÂ¬ÉmÀÕìdpc+“5äÂ\$/rCRÜ`£MQ§6(\\ê·2A†¶π\\™ålGÚl¨\0Bq∞§PØr≤˚¯BêµâÍõ—Çπ_6LlÀ!BQéâI¬éG¿Â‹ÿXRbs°]BóHrèû„ò`ŒXã‰\$pÂ±8Ñï	nbR,¬±ÖL†ç\"¬E%\0íaYB¶súÖÕD,ê!∆◊œõpN9RbG∑4∆˛M¨åtÖ∏ú¨jUÙ§¿êßy\0Ï›%\$.òiL!x¬Ï“ì≈(ƒ.ë)6T(íIÖÏa%“K»]mƒt•ÙÖ˙&ÇÛG7«ITMÛB˙\rza¬ÿ])vaà%úÜ≤41T¡jÕπ(!Ö¨ﬁ°®\\Å\\∆W¬‹\\t\$§0≈Ê%·î\0aK\$ËTöF(Y‡C@Ç∫Hœé–H„ÄnDíd√ÜWpò…hZØ'·ZC,/éù°\$˚¶£óJ°FB®u‹¨Q:Œ•¬Aˆâ:-a#îÏ=jb®ßl’Ug;{R∞ÄU∫±EWn‘UaªèV‚ÓïNj¨ßuãG…*®y÷π%›“@≈Ô*Ã‰´’YxÍ±_Û≤ßzÄ]Î)v\"£ÁR’ÂLØVIvÍ=`õæ'™∞U›) S\r~Ròïô\niî≈)5S¶ÂD49~ bî;)3á,¶9M3ØHsJkTú√úá(¢Ü˙óuJâ][\$uf®Ìob£µπ\n.,ÓY‹µ9j1'µå!ˆ1ù\$J∂ëg⁄§’üƒÜU0≠”Zuah£±∑cHù•,√Yt≤ÒKbˆ5óÎ5ñí/dY¨≥AUö“Ö©ã[W>®_Vˇ\ràë*∑ı©j£ß-T±Ö z÷Y dïcÆmá“π±ÿ:πÄ¸À[Ut-{™µ˝l	£i+a)ª.[∫ï_:⁄5û‰hÉÚ≠W¬ß…mª•%JIë¥[T´h>öÆµ∑∞ïô;ÀXÃ∫dÍ¬üSõdâVÊ;\r∆±!NàìK&óAàJu4BÖ¡dgŒ¢.Vp¢·mbãÖ)«V!U\0G‰∏®çì`ã–≠\\ÅÖq‚ü7Qˆb´VL•ﬁ:‰’Ç˙ÉÛ¨Z.≠NÚòƒ*ñ‘èU]Z¥lÊzÎÖŒˆ˘Æ«R D1IüÂ¬£—r:\0<1~;#¿Jb‡¶ Mòy›+ô€î/Å\"œõj<3Ê#ìñÃåÍÒ°Ö:P.}Íe˜ÔÅÚD\"qŸyJ˝Gå˚∑sopåçØ≤˛Xå\r›≥dñﬁ\rxJ%ñÌâœ∆ºO:%yy„≈,áî%{Œ3<ÓX√∏œÃ˜Øz¬EŒz(\0 ÄD_˜Ωü.2+÷gÆb∫c⁄xÏpgﬁ®¡ﬂ|9CPé˚Óò48U	Qß/AqÆ›Qº(4 7e\$Dìâv:åV°b◊˚N4[˘àiv∞¿Í2Ò\rïX1ºòAJ(<PlF–\0æ®Ä\\z›)—ÁöWÄ(¸4Ù»√⁄Ô¢ pïô”ı `µ«\r≥da6îùØ¸O÷ÌmÒa¥}q≈`¬¿6PÉ'h‡Á3ß|öíÓ√fè j»ˇAÊÉzâ¯£+åDåUW¯DÌ˛ﬁ5≈ƒ%#È∞xì3{´∂L\r-Õô]:jd◊P	j¸fΩq:Z˜\"sad“)ÛGÿ3	§ê+ärÑNKÅˆ1Q˛ΩÁÜx=>˚\"§∞-·: FÕıúIŸÉ*Ì@‘ü«yªTÌ\\UË®„äY~¬äâé‰‚öÇ3DÅÂÄ¡ô„®f,s¢8HVØ'…t9v(:ê÷B9Ò\\Zèö°Ö(ë&ÇE8ØÉÕW\$X\0ª\nåû9´WB¿íb¡√66j9– ‚ àÑÉ?,ö¨| ˘aæùg1≤\nPs†\0@Å%#KÑ∏Ä†\r\0≈ß\0Áà¿0‰?¿≈°,‰\0‘êhµ—hÄ\08\0l\0÷-‹Zê±jb‡≈¨\0p\0ﬁ-Ÿf`ql¢‰Ä0\0i-‹\\ps¢ËÄ7ãe\"-ZlbﬂE—,‰\0»Ã]P ¢⁄E∂ãb\0⁄/,Z‡\r¿\0000ã[f-@\r”ØE⁄ãœ/ÑZ8Ωë~\"⁄≈⁄ã≠ˆ.^“ŒQwÄ≈œãÇ\0÷/t_»º¿‚ËEã÷\0Ê0d]µÄb˙≈§ã|\0»ƒ\\ÿºÇ¢ÌE§\0af0tZ¿—nÅJÙ\0l\0Œ0L^ò¥Qj@≈·åJà¥^∏πq#F(å1∫/Ï[µ1ä¢„∆åIÊ.‹^8ªê\0[åqÿÃ[√ël\"Â∆ åÄ\0Ê0,dË∂¿Ä∆\råÅÃÑc¯µ{cE¡\0o‚0¨]∞\0\rc%≈€ãóà8Ωw¢Â∆Zãµ-ƒ\\∫Ò{„≈÷ãG™/\\bpÑÖ@1∆\0a≤1˘ã»œ—s„!≈®å/Ó/Ã]8πë~c\"≈€ã≈˛2ÙcŒëm£\"Ä9åqö/\\^fQ~c∆_ã£Œ-\$iû\"÷\0003åÀ¨§fX∫qx#\09åóZ.¥i∏»å@Fàåâ3tZH… \rcKÄb\0jí/Dj¯…1®‚‚∆Içh¥a»ÒvÄ∆©çOZ4úZÚÃ—Ç#YE®\0iñ.hH“—sX/F<ãœÜ.‰j¯ÀÒ≠bË∆Õ\0mV/d\\ËÿÒãb˜E≥ã£û3T^(›—àcKFRã’˘ÇÙ]X∂qΩ¢¯≈‡çóí6‘]h”Òûc6EƒãÛ66‹hêëü„n\0005çsn/dn∏‘`\r\"—Få≥⁄-D`»’ëã„NÄ2ãYî§bx¿Òî#\\≈ÎãáV3x∑1xÄFxåæ\0 6åb∞qÅ£É«!éû8|^ÇÃ—ubÂ∆‡ç’-Ùrÿ‰qº„:∆Èé%ˆ0åppÒî#Å«ã¢\0∆6‘f’—«¢‚≈¨çd“0ÑqH¥±æ£\$«@ãqÚ-º^B4±¶\"˙\08é1™/lnxœë†‚ÍGç3:0tjh“~@∆ºé•¶3§vH∆Òπb‹G(éeÑê4gÿ∫q¬„2∆1å…-ånXÀÒ∫\"„F<çQû1\\j∏∏1Æ„»E«ã«‰≥4m®’Ò™„[Ùãn¡z7¸yhﬁ1ß#∆ﬁé/Ç3\\x–qÕKGÇåˇ∆6‰oò—1{£∞FJç◊ö6ºlXÈq‚£Ñ∆uç©ﬁ9úr(ø1“„áGc\0≈f:ÑrXΩ†#–≈Ω\0iﬁ<\\}◊ÒÂbÓFΩ\0s÷7‹y2Ã—Ê#uFeçõ\">4iÿ≈ø‚‘∆ÁåÈ\n<{∏„ëç£‚∆âåJ;¨]ÿƒ1≈#Œ∆0èŸJ;4^Ë¬DΩ„Û«Æãü®≥4i®¿(H#⁄∆Eåxñ/§n¯˚1„/«°ãÂj6,lò€1t„/\0005%Ô0Ñ]x¸ë∂£GG5ê!í0§Ä®◊Ò⁄‚Èñråq¢2Ã®ﬁëŒ„NFPèo\"4Ù_ò∑1◊d«%ãe ≤3¨s8Èë¸„ÜG5éì Ê6‘[HÎìcÿHèjYö;Ù[ËæëòbÎ! éyÚ@ƒ\\∏Ωqÿ#WHNèáé;Ãc∆QË„:«-ê%™.úkX∆ë˝£⁄GÕåœÜ1Df®ﬂë∫cWFlê°!Ç0¸Äô≤c E‹ê©é;lò—qê\"ÎF©çﬂ¢7\\\\®˘Ò‚£‘∆Oãq˛.T|\"?ëÒ„ô∆Eê≥f9TyY—©„SG1ê˚¬A\$f9R\n\"ﬁ∆xåπ>BúÖH⁄Òﬂ§\0«å∂:\$eπ1ú£≥F?è=∫3Tu)\nqπbÈ«~èÀŒ<TÅ¯Œ±–câH.ëm~CÙwH ±∏#/»Iç]~3‰^à∫—Ñ#ß∆>ëYÆ4å^∏ŒQjc «Kå1\"“8¨|6—Âc\"«Bëµ\"b4„ËÊ%ú¢‘»G\0e\"í/tã®¥1r£1∆èe!v2Ñy¿±ı‰<«†èçÜ8\\o® —í#t≈—ê\rz@¥}H¬ëËbÔ∆Ëçy Ó1Ã\\®ÎdeGé¡Z3å~Èr)„1»øã€ÜBl~HΩ≤:£dF£ë-Œ?îk8¥qËc(FÕãäKﬁ5|myÒÄc1∆<í*@¥jÿ·Ú1„€≈æåã>I¥ZËÕQj‰ï»2å…\$0§ãhµQà‰VFTå	\$∆Al~ˆq⁄£»±é\$÷>\\pŸ\rqÇ\$/»u%Ô!ÆJq \$†„tE≤ãGN-Tq)Ú\"¢€H åÀ¶=ÏñX…2-£Hí´ö8\\nàµRW\$HåÎ\"¢C\\_π\0ªd\$«fë≥\".DÑu	'Q£zEÌåŸ&0toàÛqj„˙∆øå≥R@dó¯…‰£˘«uç##∂LLk…*qÛ\$*GƒëiŒ@Täiël„ÚE™ëÉŒ5åòær\\dñIñëµ\"/ÃZ…0íj\$T≈˛åz5Ld3í£Î…ío¬.Tqπ!1{£∆ãÂ÷9úZ∏æQ’b”FåwJ94nà“ƒ÷‰{…(ì-é8∑2h§u»Èì;\$Ü-Dk¯Ârs£áHûèô#°ÇÙèY7Ú\"ÿ/Eøí”†	\$j¢^Ú-£]«7é[\"N\$íË¬ëì§W»ëØ÷/]‡\$≤+Ä1Gaê/&IDn¯¬í@\$Â∆!ãÁ\$Œ-åk!ùQ®‚˘ )(N/\$t∏›π‰Î∆OèKzP¥tX‹Ú[\0íGéíw(*K\$vàÀ1Ûc…'ìﬁGÃûIÚxd≠»\nìA“8\\rX∑“a£˜IîiNúI%\$Ω„í∆_ë˜™6§fÁQ˛#ñ»Iî5#éF¥óÿ∫Òœ#≥E‚íï\"Ó3\$¢I‹cáHàã›vR|˘QÄ§cE∏èÒ:RÑe∫±h‰∂EŒèfK`8˛r.#∑E≥èsÆ0LÖò¸Rç‰ÜF©ã∑!\nC\$`»ˆÒ¥\$ÙH?íÀnP‹eô!Òö•@F'îøñ/úá∏∂ƒ÷‰ˇ îØ%¬N,h»ÃrF\$ˆ»˛å«3¥t¯Ê“Ä•≈Êí!1<Ñ…CQœ%…√íπÊJ‰Zÿf.›6≈çÜú∑±Câ• ‘ú.≤[˛ôB“øxÎ‡ÉË\0NRn`ö»˘Y\ní%+N®IMs:√πYdÉef¨B[∂∞›n∆πYäÚm®¡RÆ◊í˚…YØ⁄CÑXåÎ€j≥ÁU+Vk,Ø\0PÎ˝b@e≤π•x¨ÑVæ∫yT§7àuÓ´[JÔï»±\nDØßeRø¨mx&∞l¿\0)å}⁄Jº,\0ÑIÿZ∆µ\$k!µ®ÒYb≤¡ú∞ÄR¬áe/Qæ¿êk∞5.¡eë≠5ï¿®ûWë`™•\0)ÄYv\"V¬\0ï√\ná%óÂñ`YnØ’°aÙ‘x√ÜQ!,ı`\"â	_.üÂÅ©∆ñtm\$ï\"ì≤J´§÷ç¿ßév∆%âM9jÇ∞	Êñßƒ*≥Kp÷îí;\\R º¸3(ßıä^ùØ:}ñ»Ô|>¬µa-'U%w*â#>§@êÃ¨eñJˇù§;Pw/+π·5E\rjn°–√dñÙ¢^[˙ØßcŒ∞•uÀz\\ÿê1mi\"xÇÑpÂ√;£ÃÓàÊàP)‰¯™«#Ñ±ÿí°ÖÀ!A™;®ﬂ	4Ï≥a{`aV{KùU‡ 8„®ü0''oÄ2à®¢ycÃ∏9]KÈ@∫“ó^lBà‚OrÎ‘„,du§æ8§?ıâÄ’%ºgBªàÓÇ∆Yn+„%c¨e\0å∞Ò‡§±Yr@fÏã(]÷º®\nbizÓ÷nÄSS2£¡GdBPjäπ÷@Ä(ó»•¶!‡-Áv≤¥e⁄*c\0Ñ™4JÊÁÇí˘’Ÿ,ìU»	d∫…ej'TàH]‘ä‘G!ú)uã’÷Øüï“Ø˘ZÀB5˚ÃìWéâ0\n±·°‘R´¡ÅWÅÖ\\¶Q jƒ^r %lÃò3,“Yy◊…f3&Ãï‹é’Q:œµ2Ñm…R)îTÄæ(KR¡†0™ î@´ÏY¥¢Y:£Ÿe3\r%¥®∞Tˆ%≠Xî¡πáST‘.J\\Î0ŸhÙƒÖäD!ƒ:óuÊÍ…U\"æ≈Å¡o+7ñ\"ÑµÅìf'∫≠R\0∞ëﬁJùı2Sñ2Ë#nm ª¡IÂäú˝\"X¸≥≤[ê÷Ä—Ï} J®Øcº9p0™¸’Qª(U\0£xDEWÇå.Lı¡=<B‘0+Ω)ZS V;‚\\‚µI{ê5IëAÙ÷√,dW≤uË5Ew\n\$%“ÅÖàΩ2i_\$»Ÿ+ÏÊO,å¨áÌXã¥’ëJg&J°˙Gí∫%\\Jì∑b.ƒ›^LãTÚFlåËñπ]k#f@L∑GÄƒêTºŸó“ÕHœÃ\"ñq1SÃ∞˘âjèV…(ŒôÑÏZVzﬂ≈Ü≥,ùß ËGç.1F˚±gN ;◊1√äV¨¶5EÕÚ5`Ú\0CtË=F\n·πõŒ±ïKá˛ô÷\0≠€ä±%®ÀD]Q\$\r\0á3J\\,Õôö≥<T4*£ô¡.“YK≤D´QÉÈLÔS%,äg‘«Â™ß÷<ÀÎôu0ñÙÕUƒâ÷*x(©ÂN¬íYv!˛•yÕ	w≈4fd™•rGïâM \$‰Íâ^;∫ÈùÓ›Êà)<P„]D“%%”;‘j ÂöI0Êa”u^Jpó[)¶v©3RhR˙Eˆ¿\nÊñL_ö#5|‹æ’m3PÒ*®\\Y51Xíí	i≥Nó»Ò\$\"∞∫a¸≠ıh*KU›ÃÔV8®ÂuÚ±%&ÑrÊØÀö†≤5oå’Ág≥;›rMl[∆®ˆgú≥˘™í∑UÕqôÍπöh|‘eO2∑f MlW2APÑ◊πòíÕ¿Õv~eD¨eÒ3U”´láE62i¸ŒıÏ”UbÃÔò¨´ıUå¨©®Ó¯ê˝™VÍiI!\$i® ≠&Z:Ωñxm!≈Üì.÷OÕfw“Ø!îÃ”k›§ÕÉôç6b\"´IôJ]]:Tôù6“Vr˙π}í‹«´]ôÆ±ëU¢é	ys7f‘M≈ôˇ3àå‹ŒYúÛ:T_MÕw%3∆nœ•\nŒÊz*ôÌ3‚hÉ∑	ª`Uñ≤Lˇöá,•€Ñ–5®ÛvfÉª√õŸ42_Qâºh›«ÕuDß\no£π)§ƒú’´M9ø7fo€º©§r÷›«ŒWB~iT›eyQT‚N\nöd¶prß#õÛMß;íòÖ4Êp™ºÑtÍˇñ(;öõ≥5	|¨‡«Çä≠',AV7‹î‘ÂUAˆ&ÏÕRúPØ\"‰’yá“∑ïâ)†[änÃ’Ò-3VïÀ,?ús6∫pä˘Ü3éfµŒAö€9k|›…ÆSÜf¨*@úï5ﬁgºæ…ø2∑Õ}úåÆ˛U¸›ôë˘ÊHŒFõl%Æp¬´Ie≥beóMŸSO\ré[ºÊi≤3êf…ŒLV·ÆrŸuÆäæ•€NAõ:Ó%rÑ⁄y3Qù_Ã∏õW.—’»^Sl@&Ã¡ù5÷Yl¬Ã1ÂÊŒ}VxÍûg Öß^Sn’ÃÕQ!:5◊ZﬁiZC‘à:øõï3qgÈ%D·ı›™{U°3ítZπ`˚”u%w:…ZQ:QÏœ«W fÓáÌõø9JplÍ)÷3x‘vÃ˛ùK7ûb#´˘Ω´ÁX+Jö(¢¬h¥ÏP*”Åù¥´Œõ˛¢!◊îÏ≈èSLÁh*'ù§®\npB˘ô⁄™ègN ùß8Bu“™È¬éØÁŒåùΩ8niÍàIÕs∏USÕIöá;vv⁄≥UısRï7Nùu◊8©H|ÌÈ≈”∑ßÃéú´8Úq¥’Ÿﬁ+'—ﬂÕ`úx¢9Rà	’Æ∫ÁMaR8˙x‰)ê∏'!œúè;±U¨◊Y÷ìí›sNIùg:’KTÎyØ3ÆgéÕYùÏÎ k‰„…‹≥n'LO(úø3öw4Ò4Óª¶«œú⁄Í˛l¨ÒŒJΩùñ™wùΩ9›\\ÏÁïÛÛhf(¢_~ÏÚ‡}9Nˆ¶’\0ñ¥Âb\"¢YÈ§ÉTh,⁄û§@˙±D°˚Ä\$ÄIû∑;ée¸ËU ùn®≥û∑,πO™∆	XÅˇg¥-¿û…+>ti'GÇÅˆél™%\0≠8‚VBÀU1´yeê\0KT∆4˚¡»mí∫V2)\r]I/\rF˘Ö‘Xà◊¿ﬂ®Òa∑≠Gä¬πÚ*àßªûˇ>ERÏ˜ÓÆ•ûá—Zõ-)I\$ÆπÌÁ:¶aÀ\0æFybaŸg´wß≠(ﬂ_@ßv}ˆiı ≥ÓÄS^À25D‘≥–	»ÙURO±üJHù÷\\ÿisf∆ÀKöN±Äqi˜Sg◊O¬ü\n≤F~|´µœ*@gRÄ_Q<9s‹¨3i+ÿó≤.Cw≤≤Í|Çç¯yÀ6aÏO‹Y9∂å∂…ñ\nÎ‘Ω-([Æ±Ü_à}ÌS˚]c§S=¬§ŒŸ˛ŒÕ‘YŒ‡U->†<˙©µ\n<÷sOÙQ4F¶^}\0007u‰k(/ãü€/5{Lˇ9µ\0ß¨–†&≥ä[<œıüs€\0&ÕË#Ö@hÃÈ™3©V}–ùH¢äÅ*‹w+]'D–&†@ß÷Å])µË;TGe3êç\\ŒÍnÆ—ﬂÀd\$:¶uN4≈yktÍ-dR!7ñÅ≠…e4(P!ïü-˛Å9¿4Á_PMGbèÅƒ±wÖ´ÿ…6OßS¶FÇ‚Ì)ßäyh0+Äû≤ßqT|∑ä+u‘ˇŒ+†èA¨?Úﬁ	ˆTË3.q†è41T¥∏eõÄ\n:P†¯Øñ{TÓ\n≥Îh?´öTÔA˘S£≠*´Â“+Âu•>˙\\ÍæZÈÌ ÓYÏ∑¢wEJÅˆ%∑ísóL±æd™öy¿+\rCËúﬂ°'AÒl,“yÂ3˛Á≤ÀÕó`∫	_*—P˚ ThKDV≤∑ñ~5	‡0¥+·º,ö-?≠]ú∫Ú3Î÷çKÂó`Ø^Ü∏§I42(]™wû.ÊÜrƒ ÀÍ]¨\nY∆®BÜ£≠–	≥Ìñ}–ãR æ…gÿ}:HßJƒWP≤ÍÑ\"ﬁµóÙV\\¨<óó? >ΩÂó·ˇß‹¨›Üø=¶Ö:ü\n0◊Ë\\+ÒSñ¥Êf›Uå≥ÌâU,ÖWC÷àËïOn®ÚŒÖ¢ß.Üe9|R˜I'©[◊/ç∫≤ƒŸ¸2˘õ´Qû”Bn:∆Iı\nˆßgº9∆\r¸,”R6≥˝Á“Q\$X›+∏>êñ©±`\n˘)/_8Qi‘˘µÍó=áÍv?5vù\0 \n®Á…LG•Dmàw\\ÎF÷åá—¢êØ¡dÍüµ}sâ\"ë√Yv§|‚ôJ*¥9h≠°—@XEU—*ﬁ(oQ]\$çBûà,˚È‹ÉïKTúv§AptC…É\n◊C,/ò<°≠⁄ôEWã-VÔP°¢=Wˇ*%KÍó-Q`9	( ˙59”ÄËm)ÀX∏®@Á2¯†˝T@à€\nSñØëbd◊EŒ¥aÄ+ÄDXÓ·|U⁄	ã	í°FÆ 2˙%5\njïm´ÄWŸ+çxÍKåÊVÃ3#Ñ∂CT√ek§ôñ&Œ,£l¨jbd7)”ì\"\n+ÏP¸∫bíËIä@Ë3—ï‹µjU“ÃEsﬁ‘)D¢fÎíÉıäÅ˚ï«PÅZ3AŒå’\nwThó≤™€ò≈4Zè‰™< uﬂ©ﬂdq‚Àäu(˜ûìbKG±‡•È¿n”TÔÆà]z®çf%#ù3IÀfS®Æ&}µ@DÜ@++˘§AÌh™øê\n™ÔÄUóﬁ•|B°;îÖUm—ŸUÖEïN•!Ùx2±1“\0ßGmvH~ı¡HËTÍ)ˆWÆ≥YN˝\"Âk5©—vT#=µ⁄• <\n}ë#R3YÉH≈RÕIÕ≥‹¶;Ã—Rl£1lÈuB%TQJÓô*∫ÍàŸ'∫EÎ0i¨dw,•z Õ•:\$Ü¶;Õ?†¸Ójëø)ßÙ)‘è \$32J}≈&á[≥\$®ıÃÅ§;Dnê˝E◊¥¿+0€aZ{®çËC Ë˚Ä(§Í:ì∏†⁄O@h¯≤D£Ê\0°â`PTouì≥ƒÔFÆ\rQvÇ˚®òoΩ‹°\$SÓˆ+ò“#7¿§IzrÖpk†DWîàFsÕ9ô†QÍ †–∞1Äg¿≈#ï\0\\L‡\$ÿ†3Äg©XéyÙy ú-3hõ¿˛√!ÜnXËÙ]+±ó	…ùÄc\0»\0ºbÿ≈\0\râ¸á-{û\0∫Q(Q‘\$sÄ0Ö∫Èm(∞[RuÚV∆˜“ÿ>∆º+‡J[©6‡ë“‡J\0÷ó˙\\¥∂„,“ÈÇKö3˝.Í]a_\0RÚJ ∆ó`ö^‘∂ClR€IKÓñ˘\n†\$Æn≈è“‰•ÔKjñ©\nÄö¡©~/•™mnò].™`Ùøij“‚¶#Kæòf:`\0ÖÈåÄ6¶7K‚ñ®zcÙ¬\0í“ı¶/KÆñ≠/™dÙƒÈáFE\0aLéò§dZ`ÉJÈÜSëœ ôÖ2ÿÕ4Œ@/∆(åãLÚôı0™`¥ƒ©ÜÄ_éL˛ô]4ZhÙ–©öSD¶MòÖ4:c—ÈãSR•◊MóE4öiÚÄÈûSG¶EMjòÂ4zd‘’©ñSFKL™õ%4™e‘œ%\$”lKM2ñı1»⁄î‘i¶”ç©MVõ≠.∏⁄î÷i¥”ç©Lzõ/à˜Ù€£”Ñ¶—MÊõ,`ä_Ù‡imSä¶gM∆úÄjgëÚÈ«”5¶9.õÖ9j_ÚÈ∫Sê•µ.õ≈9Í_±ÚÈæSà¶ã.ú7⁄rÚ)…”%ß[2ùm8∫uTÊÈôS±ß3M:ù]3∫qîË‰n”±ßKNà1|^“ktœ\"“”HßgKjû-;zcÒiŒ”ößêñù\r<Í_≤-i ”∏•Ò\"÷ûU.π¥ÛiÎR⁄ëkOFûÌ=:\\Ùœ\$Z”©ßMLE≠5˙xÙ¯©¬”ª_\"÷ú=<\0ÒtÈŸSÁ¶9O“û≠1ä~îˆi≤”ÙßπOÍùÌ>Í~qú)ÚF∏®í†=6:~‘ı„J‘ëœP:üÕ=®ÂTˇ)¢∆´ßˇPJ8ı@ÍwÙÙ©˜«*ßÕO 5]>™Åt˜£ïT\nßÂ!\"†ç6Y	)Ä»H®/P™ûÖ3…	ÈÜ/êëP~†‡˘	™”Æ®!\"üçCíÃ‘˝j° ®eNJ°¸àÍàÒ‘*%‘4¶1Q°≈CZáQëjTBçQ.¢\rE)\0004ÀÍ\$Ä2®SM+Â<jÑtøj0‘,¶9QÜ°}F\0\$±s©ûTa®ùKŒ£]Ecj*Ä'KªMæóMGxΩ’R«T1¶#QÍ°•G™ä5™:‘z®Lö°4u6zèï\"j\"TàKuN÷£˝G⁄g\$jFS‹®ÔQ2§•H¯Óµ\"ÍMTÉ©%R§ïHzé’\$™,‘w®Re.\$r™zµ)©€‘¶©-Qˆ†ÕJÑπë ™@‘∞©=R&/ùI ï1Ü*]T≥ã¿7ºòæQ“ÂD&”©qN¶_(¥q≤c[TwåQRÙÂ¥úJö\0n‚˜T≠®˚.¶ò956c‘‹å’Sz•Hò¡ï7™R‘}éSr8•Näö’\"b÷TËß¡Qﬁ5MNäñı#„Á‘Ë©ES¬ß-Hò¡7\"‹T¸©_SÍß}GÿÃï?*y‘©ãáSÚßΩP*ü5#‚ˆ‘‹çœT:ß]P üıC*Ä‘âãT:®-K8∆5C™Ñ’™R¶--M»æïH™à’ ™'TÇ®≠H¯ÀıH™å‘—ã◊Tä®ÌR™£ı,‚È‘‹ãGT⁄©-SJ§ıM*î‘©ãUT⁄©mMH∏ıM™ò’>™gSD≥5M»¬ïR™ú’H™wU\"©ÌK8’’R™†‘⁄å°U*™-U*®‡n¬æTŸIR≠,t¢Z´’ÍY∂IUF´51™¨µW)v’kã_K∆´pJ´5Zj≠≈Ø©Rç4r\n¨^jI”CK∫ÑÇ™}U ì_™∞‘õ™„O¨=N∑R*ØF-™ΩRû¨%Wöã’cÍ¶’\\éaV>´EYjñµd™™‘√´UŒ¨µWXÕ5*»’ãíπUyÇıZä∞1k„ô’®´7Vö¨R\\HÕ5h*÷U¢©œU∆ßM[ä≤±kÍv’∏´3VÚ≠}[(‰5W™z’∏´iB≠O∫Æ1ØÍØT˝´óVÆ;≠[¯ÓµpRÊGu´;T@0>\0ÇÍ/I≥™ˇW`Ì]¶Ù\0™Ó∆8´øPäØ]»Õ1m*Ô’«çyUz®mW°ı|™›ì[´°÷ØÖ]J¨—àÍ¯U±´´ˆØÖZ*§5\\jë÷´ÎZ™Ù`Z¡5~™ÆEÏ¨W˙´4Zö¡5h£Q’^ãcXZÆïS˙Æ1o´V™πU&´çT∫ƒ5}cU^çõXö∞dm*≥±íkUu•´SfG=[πıj‰s’øëœX¶Kc\nÆiR‚HÁ´i#û±uWtªµ™Ω•∫´ªX¬ù’cƒπï´UÜ¨îr⁄¢ıUZã’áÉNE¢¨ëX∫¨Ö4⁄»udÍ∑E‰¨eV^≤ÌK…‡n‚ÚV8ãsX¬•Õf«ı/¬hJ≥-J]”ÇÖô”Œ¡’zOõ±<Ehâ\$Âãì∑°Û\0KúÎ<bwÑÒÖ>∑î¯Nû\")]b£	‚+zÍ.cS.¢iFÁ	„£µQNQê´ÈV*™È€Œ˙ﬁO[X§nxä§P	k≠ßoN¯£}<aOÚßIﬂì¡h∑∫öT;ÚrÒââ§ÉVD6Qﬂ;zä]j◊~'í:Îñ[IvÙÛ7^ ëß÷¡ûjÎ∫w[´˘ÊÓ∫Áú ≈Ü•:u ≈Ds#¶øŒ\\wµ<n|*·âhÎmŒKv;Y“à±⁄3·]å´^#óZ™j•gy≥jƒßY,î%;3æ≥ ⁄˘◊.»W\"ë√\$Ÿ3>g⁄ú∫œ”œ¶™VÅTÛZj•hY›jûkD*!öh&XzÀi™ï•+GVó≠\"•Ê∏Zè:“§ß+áNoG•Zjj•i…] ûkO–_≠÷¨‘êmjI™ï®ßtØñ#Ω[‚j\rnä„Í©◊–nôﬂZ•_,’ÈÜÛgŒƒö©:πº≈9â¡ˇ´[L2ÆW=T‘◊0Æ„f∂\0PÆU6\ns%7isYÊ?£øu·3æíΩnb5°´üªöX|G~lï&◊k§•∑Mß†ÜØ˙∂åœy°Sñ…)Œ]ú‹≠r∑∂Ÿ∏µ∏ÊÏ÷Íõ≈?’}u'n0W-ŒπÆÊb∑¥«™Ïıük?ªvQ˝7Ö‹}p\nÏı¿íÕŸÆZ*ª9) ·5ﬁïZW≠-ZB∏≤å:Ïı„´äWê\0WZfpïGpıÓÕŸÆ:èFp˙§ä‰UŸÎSN/ôœ\\©‹%s9¨S{ß ◊8ÆœZÕas €ìí+¢N^Æì9ôM’{ÖP5”Á ◊QÆ‘ÓJ∫¢´yßı’Ë;èú⁄Óz∏É¬’Y⁄V ƒ3ó:ÔúD≈Iùä√+Áá˝Ø£19M;∫•åíÙ®ìV¥Æö\rQ{Í…’Æï∂≈+£ÉFùCLƒπäN•ñ©‘àù\\˘ﬁ)\$iåé€N'\0¶∞çPä¬öı «]XÃ^ùs1Úfù&ä\"'<O¯ÛöÃ°ÀL\0π\"á@÷î•%‰6˙¬UAı1˝i(zÃË›ÅÄ\r“’Ç‰±»bZ¿î+IQOÔ3Ä∫À\r=*ƒâ†â)Ò®!¡û†–`™ºh∞à,–´mGPCÅÀA†ùŸ≤ÌÉAÑå(Z≈∞%ÉtÏ,h/¡âàiñ»k¨´°XEJ6±ÑIDË»¨\"õ\nÔaU- õ´\nvéyù∞_Äƒ¬¬õ⁄´Øk	aΩB<«V¬É€Dª/PùªÙaÓ¡)9L„∂(ZÇ∞8ÍÅvv√πÿk	ßo–ZXk‰—Âß|¥&∞.¬Êù±CÅπíÿ·∞`Ä1Ä]7&ƒô+ôH§CBcXìB7xXÛ|1ìÄ0¶„aö6ö∞ubpJL«Öñ(∑ö˜mblÅ8I∂*Rˆó@tk0Äó°Ø≈xX€¡”;¡≈ al]4s∞tøÌ≈™0ßcá'¥Êlﬂ`8Må8ë¿√ÄD4w`p?@706gÃà~K±\rÇ€ ìP¥ÖŸbhÄ\"&êØ\nÏqëPD»–ŒÛ\$–(Õ0QP<˜∞‡¿„¨Qç!X¥Öx˙‘5ÄùàR∑`w/2∞2#ä¿∏é `¨ªë1Ü/à‹Å\r°ê÷:¬≤ñ±¢£B7ˆV7ZåõgMY˙H3» ÑŸbŒ	Z¡”Jê≈ˆG‚wŸglÅ^∆-ëR-!Õlì7Ã≤LıÜ∆∞<1 ÌQC/’≤hº‡)œWû6C	˜*dà˛6]VK!mÏÖÿ‹„Ä05G\$ñRòµ4Ø±=Cw&[Êè´YP≤õd…ö≥')VK,®5e»\rﬁ ËÜK+Ô1ÑX)b€e)ƒ‚uF2A#E—&g~ëe°yífp5®lYl≤‘ú5ıÉˆø÷\n¬äŸm}`Ç(¨M ÅPl9YÅˇf¯±˝÷]ÄVl-4é√©¶´¬¡>`¿ï/˚≥fPEôiã\0kôv∆\0ﬂfhS0±&Õ¬¶lÕº¢#fuÂÃ˚5	i%ˇ:FdÄˆ9éôÿÄG<‰	{ˆ}Ï¬s[7\0·¨Œû3Ìft:+.»îñp†>ÿ’±£@!Pas6q,¿≥ó1b«¨≈ã„ZK∞Í±‹-˙ìar`ï?RxX¡Èë°œVÔ˙ò#ƒ§‘z¬êç; ¿DÄïæH≤¡1•í6D`û˛YÍ`˜R≈P÷ã>-∆!\$Ÿ˘≥Ï◊~œÄ–≈‡`>ŸÔ≥ıh‘0Ù1Ü¿¨ñ&\0√hóÎ˚Iñwl˚ZÑ\$ì\\\rç°8∂~,ê\n∫o_·¿B2D¥ñÅÉa1Í≥‡«©è=¢v<œkF¥p`è`îkBF∂6ç ƒ÷≤óh∆…T T÷éÅ	á@?dr—ÂâÄJ¿H@1∞G¥dn¡“wá∆è%‰⁄JGö“0bTf]m(ÿk¥qg\\ÌΩèÛ∏ñ¨Î∞Í†»—à3vk'˝^d¥®AXˇô~«WôVs¬*º ±Êd¥˚M†¿¨ù@?≤ƒ”}ß6\\ñçm9<Œ±iî›ßõà‘¨hΩ^s}Ê-¶[Kús±q„bŒ”-ìˆOORm8\$ﬁywƒÏ##∞å@‚ù∑\0Ù“ÿ§ 5F7ˆ®É†X\n”¿|JÀ/-SôW!f«Ü 0∂,wΩ®D4Ÿ°RU•T¥ûíÓ’ZX«=Ì`âW\$@‚‘•(ãXGßã“äµóa>÷*˚Y∂≤à\n≥¸\nåÏö!´[mjúµä0,mu¨W@ FX˙⁄ŒÚù¸=≠†(¶˝≠bø˝<!\n\"î™83√'¶Ç(Rô›\n>î˘@®W¶r!L£H≈kÃ\ràE\nW∆ﬁ\r¢Ç'FHú\$£ã‰‰¿mÑÅ»=‘€•{LYóÖ&—‹£_\0é∆¸›#¢‰îÄ[Ñ9\0§\"‘“@8ƒiK™πˆ0Ÿlâ—–p\ngÓÇ€'qbFñÿy·´cèl@9€(#JU´›≤É{io≠ë•.{‘Õ≥4ﬁVÕÅäVnF…x—¸zŒ Q‡ﬁû\$kSa~ ®0s@£¿´%Öy@ï¿5HéÜNŒÕ¶¥@Üxí#	‹´ /\\•÷?<h⁄Ç˘ÖºIêTå†:ç3√\n%ó∏");}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo"GIF89a\0\0Å\0001ÓÓÓ\0\0Äôôô\0\0\0!˘\0\0\0,\0\0\0\0\0\0!Ñè©ÀÌMÒÃ*)æo˙Ø) qï°eàµÓ#ƒÚLÀ\0;";break;case"cross.gif":echo"GIF89a\0\0Å\0001ÓÓÓ\0\0Äôôô\0\0\0!˘\0\0\0,\0\0\0\0\0\0#Ñè©ÀÌ#\na÷Fo~y√.Å_waî·1Á±JÓG¬L◊6]\0\0;";break;case"up.gif":echo"GIF89a\0\0Å\0001ÓÓÓ\0\0Äôôô\0\0\0!˘\0\0\0,\0\0\0\0\0\0 Ñè©ÀÌMQN\nÔ}Ùûa8äyöa≈∂Æ\0«Ú\0;";break;case"down.gif":echo"GIF89a\0\0Å\0001ÓÓÓ\0\0Äôôô\0\0\0!˘\0\0\0,\0\0\0\0\0\0 Ñè©ÀÌMÒÃ*)æ[W˛\\¢«L&Ÿú∆∂ï\0«Ú\0;";break;case"arrow.gif":echo"GIF89a\0\n\0Ä\0\0ÄÄÄˇˇˇ!˘\0\0\0,\0\0\0\0\0\n\0\0Çiñ±ãûî™”≤ﬁª\0\0;";break;}}exit;}if($_GET["script"]=="version"){$kd=file_open_lock(get_temp_dir()."/adminer.version");if($kd)file_write_unlock($kd,serialize(array("signature"=>$_POST["signature"],"version"=>$_POST["version"])));exit;}global$b,$g,$m,$gc,$oc,$yc,$n,$md,$sd,$ba,$Td,$x,$ca,$oe,$sf,$dg,$Jh,$xd,$qi,$wi,$U,$Ki,$ia;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";if($_SERVER["HTTP_X_FORWARDED_PREFIX"])$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];$ba=($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_cache_limiter("");session_name("adminer_sid");$Qf=array(0,preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Qf[]=true;call_user_func_array('session_set_cookie_params',$Qf);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Xc);if(get_magic_quotes_runtime())set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",15);function
get_lang(){return'en';}function
lang($vi,$jf=null){if(is_array($vi)){$gg=($jf==1?0:1);$vi=$vi[$gg];}$vi=str_replace("%d","%s",$vi);$jf=format_number($jf);return
sprintf($vi,$jf);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$errno,$error;function
__construct(){global$b;$gg=array_search("SQL",$b->operators);if($gg!==false)unset($b->operators[$gg]);}function
dsn($lc,$V,$E,$_f=array()){try{parent::__construct($lc,$V,$E,$_f);}catch(Exception$Cc){auth_error(h($Cc->getMessage()));}$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=@$this->getAttribute(4);}function
query($F,$Ei=false){$G=parent::query($F);$this->error="";if(!$G){list(,$this->errno,$this->error)=$this->errorInfo();if(!$this->error)$this->error='Unknown error.';return
false;}$this->store_result($G);return$G;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result($G=null){if(!$G){$G=$this->_result;if(!$G)return
false;}if($G->columnCount()){$G->num_rows=$G->rowCount();return$G;}$this->affected_rows=$G->rowCount();return
true;}function
next_result(){if(!$this->_result)return
false;$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($F,$o=0){$G=$this->query($F);if(!$G)return
false;$I=$G->fetch();return$I[$o];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$I=(object)$this->getColumnMeta($this->_offset++);$I->orgtable=$I->table;$I->orgname=$I->name;$I->charsetnr=(in_array("blob",(array)$I->flags)?63:0);return$I;}}}$gc=array();class
Min_SQL{var$_conn;function
__construct($g){$this->_conn=$g;}function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$b,$x;$ae=(count($pd)<count($K));$F=$b->selectQueryBuild($K,$Z,$pd,$Bf,$z,$D);if(!$F)$F="SELECT".limit(($_GET["page"]!="last"&&$z!=""&&$pd&&$ae&&$x=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$K)."\nFROM ".table($Q),($Z?"\nWHERE ".implode(" AND ",$Z):"").($pd&&$ae?"\nGROUP BY ".implode(", ",$pd):"").($Bf?"\nORDER BY ".implode(", ",$Bf):""),($z!=""?+$z:null),($D?$z*$D:0),"\n");$Fh=microtime(true);$H=$this->_conn->query($F);if($og)echo$b->selectQuery($F,$Fh,!$H);return$H;}function
delete($Q,$yg,$z=0){$F="FROM ".table($Q);return
queries("DELETE".($z?limit1($Q,$F,$yg):" $F$yg"));}function
update($Q,$N,$yg,$z=0,$L="\n"){$Xi=array();foreach($N
as$y=>$X)$Xi[]="$y = $X";$F=table($Q)." SET$L".implode(",$L",$Xi);return
queries("UPDATE".($z?limit1($Q,$F,$yg,$L):" $F$yg"));}function
insert($Q,$N){return
queries("INSERT INTO ".table($Q).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES"));}function
insertUpdate($Q,$J,$mg){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($F,$hi){}function
convertSearch($u,$X,$o){return$u;}function
value($X,$o){return(method_exists($this->_conn,'value')?$this->_conn->value($X,$o):(is_resource($X)?stream_get_contents($X):$X));}function
quoteBinary($ah){return
q($ah);}function
warnings(){return'';}function
tableHelp($B){}}$gc["sqlite"]="SQLite 3";$gc["sqlite2"]="SQLite 2";if(isset($_GET["sqlite"])||isset($_GET["sqlite2"])){$jg=array((isset($_GET["sqlite"])?"SQLite3":"SQLite"),"PDO_SQLite");define("DRIVER",(isset($_GET["sqlite"])?"sqlite":"sqlite2"));if(class_exists(isset($_GET["sqlite"])?"SQLite3":"SQLiteDatabase")){if(isset($_GET["sqlite"])){class
Min_SQLite{var$extension="SQLite3",$server_info,$affected_rows,$errno,$error,$_link;function
__construct($Wc){$this->_link=new
SQLite3($Wc);$aj=$this->_link->version();$this->server_info=$aj["versionString"];}function
query($F){$G=@$this->_link->query($F);$this->error="";if(!$G){$this->errno=$this->_link->lastErrorCode();$this->error=$this->_link->lastErrorMsg();return
false;}elseif($G->numColumns())return
new
Min_Result($G);$this->affected_rows=$this->_link->changes();return
true;}function
quote($P){return(is_utf8($P)?"'".$this->_link->escapeString($P)."'":"x'".reset(unpack('H*',$P))."'");}function
store_result(){return$this->_result;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->_result->fetchArray();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;}function
fetch_assoc(){return$this->_result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->_result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$e=$this->_offset++;$T=$this->_result->columnType($e);return(object)array("name"=>$this->_result->columnName($e),"type"=>$T,"charsetnr"=>($T==SQLITE3_BLOB?63:0),);}function
__desctruct(){return$this->_result->finalize();}}}else{class
Min_SQLite{var$extension="SQLite",$server_info,$affected_rows,$error,$_link;function
__construct($Wc){$this->server_info=sqlite_libversion();$this->_link=new
SQLiteDatabase($Wc);}function
query($F,$Ei=false){$Te=($Ei?"unbufferedQuery":"query");$G=@$this->_link->$Te($F,SQLITE_BOTH,$n);$this->error="";if(!$G){$this->error=$n;return
false;}elseif($G===true){$this->affected_rows=$this->changes();return
true;}return
new
Min_Result($G);}function
quote($P){return"'".sqlite_escape_string($P)."'";}function
store_result(){return$this->_result;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->_result->fetch();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;if(method_exists($G,'numRows'))$this->num_rows=$G->numRows();}function
fetch_assoc(){$I=$this->_result->fetch(SQLITE_ASSOC);if(!$I)return
false;$H=array();foreach($I
as$y=>$X)$H[($y[0]=='"'?idf_unescape($y):$y)]=$X;return$H;}function
fetch_row(){return$this->_result->fetch(SQLITE_NUM);}function
fetch_field(){$B=$this->_result->fieldName($this->_offset++);$cg='(\[.*]|"(?:[^"]|"")*"|(.+))';if(preg_match("~^($cg\\.)?$cg\$~",$B,$A)){$Q=($A[3]!=""?$A[3]:idf_unescape($A[2]));$B=($A[5]!=""?$A[5]:idf_unescape($A[4]));}return(object)array("name"=>$B,"orgname"=>$B,"orgtable"=>$Q,);}}}}elseif(extension_loaded("pdo_sqlite")){class
Min_SQLite
extends
Min_PDO{var$extension="PDO_SQLite";function
__construct($Wc){$this->dsn(DRIVER.":$Wc","","");}}}if(class_exists("Min_SQLite")){class
Min_DB
extends
Min_SQLite{function
__construct(){parent::__construct(":memory:");$this->query("PRAGMA foreign_keys = 1");}function
select_db($Wc){if(is_readable($Wc)&&$this->query("ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$Wc)?$Wc:dirname($_SERVER["SCRIPT_FILENAME"])."/$Wc")." AS a")){parent::__construct($Wc);$this->query("PRAGMA foreign_keys = 1");return
true;}return
false;}function
multi_query($F){return$this->_result=$this->query($F);}function
next_result(){return
false;}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$mg){$Xi=array();foreach($J
as$N)$Xi[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($Q)." (".implode(", ",array_keys(reset($J))).") VALUES\n".implode(",\n",$Xi));}function
tableHelp($B){if($B=="sqlite_sequence")return"fileformat2.html#seqtab";if($B=="sqlite_master")return"fileformat2.html#$B";}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;list(,,$E)=$b->credentials();if($E!="")return'Database does not support password.';return
new
Min_DB;}function
get_databases(){return
array();}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){global$g;return(preg_match('~^INTO~',$F)||$g->result("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($F,$Z,1,0,$L):" $F WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$L."LIMIT 1)");}function
db_collation($l,$pb){global$g;return$g->result("PRAGMA encoding");}function
engines(){return
array();}function
logged_user(){return
get_current_user();}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");}function
count_tables($k){return
array();}function
table_status($B=""){global$g;$H=array();foreach(get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$I){$I["Rows"]=$g->result("SELECT COUNT(*) FROM ".idf_escape($I["Name"]));$H[$I["Name"]]=$I;}foreach(get_rows("SELECT * FROM sqlite_sequence",null,"")as$I)$H[$I["name"]]["Auto_increment"]=$I["seq"];return($B!=""?$H[$B]:$H);}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){global$g;return!$g->result("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
fields($Q){global$g;$H=array();$mg="";foreach(get_rows("PRAGMA table_info(".table($Q).")")as$I){$B=$I["name"];$T=strtolower($I["type"]);$Ub=$I["dflt_value"];$H[$B]=array("field"=>$B,"type"=>(preg_match('~int~i',$T)?"integer":(preg_match('~char|clob|text~i',$T)?"text":(preg_match('~blob~i',$T)?"blob":(preg_match('~real|floa|doub~i',$T)?"real":"numeric")))),"full_type"=>$T,"default"=>(preg_match("~'(.*)'~",$Ub,$A)?str_replace("''","'",$A[1]):($Ub=="NULL"?null:$Ub)),"null"=>!$I["notnull"],"privileges"=>array("select"=>1,"insert"=>1,"update"=>1),"primary"=>$I["pk"],);if($I["pk"]){if($mg!="")$H[$mg]["auto_increment"]=false;elseif(preg_match('~^integer$~i',$T))$H[$B]["auto_increment"]=true;$mg=$B;}}$Ah=$g->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));preg_match_all('~(("[^"]*+")+|[a-z0-9_]+)\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',$Ah,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$B=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));if($H[$B])$H[$B]["collation"]=trim($A[3],"'");}return$H;}function
indexes($Q,$h=null){global$g;if(!is_object($h))$h=$g;$H=array();$Ah=$h->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$Ah,$A)){$H[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$A[1],$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$H[""]["columns"][]=idf_unescape($A[2]).$A[4];$H[""]["descs"][]=(preg_match('~DESC~i',$A[5])?'1':null);}}if(!$H){foreach(fields($Q)as$B=>$o){if($o["primary"])$H[""]=array("type"=>"PRIMARY","columns"=>array($B),"lengths"=>array(),"descs"=>array(null));}}$Dh=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q),$h);foreach(get_rows("PRAGMA index_list(".table($Q).")",$h)as$I){$B=$I["name"];$v=array("type"=>($I["unique"]?"UNIQUE":"INDEX"));$v["lengths"]=array();$v["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($B).")",$h)as$Zg){$v["columns"][]=$Zg["name"];$v["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($B).' ON '.idf_escape($Q),'~').' \((.*)\)$~i',$Dh[$B],$Jg)){preg_match_all('/("[^"]*+")+( DESC)?/',$Jg[2],$Fe);foreach($Fe[2]as$y=>$X){if($X)$v["descs"][$y]='1';}}if(!$H[""]||$v["type"]!="UNIQUE"||$v["columns"]!=$H[""]["columns"]||$v["descs"]!=$H[""]["descs"]||!preg_match("~^sqlite_~",$B))$H[$B]=$v;}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("PRAGMA foreign_key_list(".table($Q).")")as$I){$q=&$H[$I["id"]];if(!$q)$q=$I;$q["source"][]=$I["from"];$q["target"][]=$I["to"];}return$H;}function
view($B){global$g;return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',$g->result("SELECT sql FROM sqlite_master WHERE name = ".q($B))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
check_sqlite_name($B){global$g;$Mc="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($Mc)\$~",$B)){$g->error=sprintf('Please use one of the extensions %s.',str_replace("|",", ",$Mc));return
false;}return
true;}function
create_database($l,$d){global$g;if(file_exists($l)){$g->error='File exists.';return
false;}if(!check_sqlite_name($l))return
false;try{$_=new
Min_SQLite($l);}catch(Exception$Cc){$g->error=$Cc->getMessage();return
false;}$_->query('PRAGMA encoding = "UTF-8"');$_->query('CREATE TABLE adminer (i)');$_->query('DROP TABLE adminer');return
true;}function
drop_databases($k){global$g;$g->__construct(":memory:");foreach($k
as$l){if(!@unlink($l)){$g->error='File exists.';return
false;}}return
true;}function
rename_database($B,$d){global$g;if(!check_sqlite_name($B))return
false;$g->__construct(":memory:");$g->error='File exists.';return@rename(DB,$B);}function
auto_increment(){return" PRIMARY KEY".(DRIVER=="sqlite"?" AUTOINCREMENT":"");}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){global$g;$Qi=($Q==""||$ed);foreach($p
as$o){if($o[0]!=""||!$o[1]||$o[2]){$Qi=true;break;}}$c=array();$Kf=array();foreach($p
as$o){if($o[1]){$c[]=($Qi?$o[1]:"ADD ".implode($o[1]));if($o[0]!="")$Kf[$o[0]]=$o[1][0];}}if(!$Qi){foreach($c
as$X){if(!queries("ALTER TABLE ".table($Q)." $X"))return
false;}if($Q!=$B&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)))return
false;}elseif(!recreate_table($Q,$B,$c,$Kf,$ed,$Ma))return
false;if($Ma){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $Ma WHERE name = ".q($B));if(!$g->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($B).", $Ma)");queries("COMMIT");}return
true;}function
recreate_table($Q,$B,$p,$Kf,$ed,$Ma,$w=array()){global$g;if($Q!=""){if(!$p){foreach(fields($Q)as$y=>$o){if($w)$o["auto_increment"]=0;$p[]=process_field($o,$o);$Kf[$y]=idf_escape($y);}}$ng=false;foreach($p
as$o){if($o[6])$ng=true;}$jc=array();foreach($w
as$y=>$X){if($X[2]=="DROP"){$jc[$X[1]]=true;unset($w[$y]);}}foreach(indexes($Q)as$ie=>$v){$f=array();foreach($v["columns"]as$y=>$e){if(!$Kf[$e])continue
2;$f[]=$Kf[$e].($v["descs"][$y]?" DESC":"");}if(!$jc[$ie]){if($v["type"]!="PRIMARY"||!$ng)$w[]=array($v["type"],$ie,$f);}}foreach($w
as$y=>$X){if($X[0]=="PRIMARY"){unset($w[$y]);$ed[]="  PRIMARY KEY (".implode(", ",$X[2]).")";}}foreach(foreign_keys($Q)as$ie=>$q){foreach($q["source"]as$y=>$e){if(!$Kf[$e])continue
2;$q["source"][$y]=idf_unescape($Kf[$e]);}if(!isset($ed[" $ie"]))$ed[]=" ".format_foreign_key($q);}queries("BEGIN");}foreach($p
as$y=>$o)$p[$y]="  ".implode($o);$p=array_merge($p,array_filter($ed));$bi=($Q==$B?"adminer_$B":$B);if(!queries("CREATE TABLE ".table($bi)." (\n".implode(",\n",$p)."\n)"))return
false;if($Q!=""){if($Kf&&!queries("INSERT INTO ".table($bi)." (".implode(", ",$Kf).") SELECT ".implode(", ",array_map('idf_escape',array_keys($Kf)))." FROM ".table($Q)))return
false;$Bi=array();foreach(triggers($Q)as$_i=>$ii){$zi=trigger($_i);$Bi[]="CREATE TRIGGER ".idf_escape($_i)." ".implode(" ",$ii)." ON ".table($B)."\n$zi[Statement]";}$Ma=$Ma?0:$g->result("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));if(!queries("DROP TABLE ".table($Q))||($Q==$B&&!queries("ALTER TABLE ".table($bi)." RENAME TO ".table($B)))||!alter_indexes($B,$w))return
false;if($Ma)queries("UPDATE sqlite_sequence SET seq = $Ma WHERE name = ".q($B));foreach($Bi
as$zi){if(!queries($zi))return
false;}queries("COMMIT");}return
true;}function
index_sql($Q,$T,$B,$f){return"CREATE $T ".($T!="INDEX"?"INDEX ":"").idf_escape($B!=""?$B:uniqid($Q."_"))." ON ".table($Q)." $f";}function
alter_indexes($Q,$c){foreach($c
as$mg){if($mg[0]=="PRIMARY")return
recreate_table($Q,$Q,array(),array(),array(),0,$c);}foreach(array_reverse($c)as$X){if(!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($Q,$X[0],$X[1],"(".implode(", ",$X[2]).")")))return
false;}return
true;}function
truncate_tables($S){return
apply_queries("DELETE FROM",$S);}function
drop_views($cj){return
apply_queries("DROP VIEW",$cj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
move_tables($S,$cj,$Zh){return
false;}function
trigger($B){global$g;if($B=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$u='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$Ai=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$u\\s*(".implode("|",$Ai["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($u))?\\s+ON\\s*$u\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",$g->result("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($B)),$A);$lf=$A[3];return
array("Timing"=>strtoupper($A[1]),"Event"=>strtoupper($A[2]).($lf?" OF":""),"Of"=>($lf[0]=='`'||$lf[0]=='"'?idf_unescape($lf):$lf),"Trigger"=>$B,"Statement"=>$A[4],);}function
triggers($Q){$H=array();$Ai=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q))as$I){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$Ai["Timing"]).')\s*(.*?)\s+ON\b~i',$I["sql"],$A);$H[$I["name"]]=array($A[1],$A[2]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
begin(){return
queries("BEGIN");}function
last_id(){global$g;return$g->result("SELECT LAST_INSERT_ROWID()");}function
explain($g,$F){return$g->query("EXPLAIN QUERY PLAN $F");}function
found_rows($R,$Z){}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($dh){return
true;}function
create_sql($Q,$Ma,$Kh){global$g;$H=$g->result("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));foreach(indexes($Q)as$B=>$v){if($B=='')continue;$H.=";\n\n".index_sql($Q,$v['type'],$B,"(".implode(", ",array_map('idf_escape',$v['columns'])).")");}return$H;}function
truncate_sql($Q){return"DELETE FROM ".table($Q);}function
use_sql($j){}function
trigger_sql($Q){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));}function
show_variables(){global$g;$H=array();foreach(array("auto_vacuum","cache_size","count_changes","default_cache_size","empty_result_callbacks","encoding","foreign_keys","full_column_names","fullfsync","journal_mode","journal_size_limit","legacy_file_format","locking_mode","page_size","max_page_count","read_uncommitted","recursive_triggers","reverse_unordered_selects","secure_delete","short_column_names","synchronous","temp_store","temp_store_directory","schema_version","integrity_check","quick_check")as$y)$H[$y]=$g->result("PRAGMA $y");return$H;}function
show_status(){$H=array();foreach(get_vals("PRAGMA compile_options")as$zf){list($y,$X)=explode("=",$zf,2);$H[$y]=$X;}return$H;}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~',$Rc);}$x="sqlite";$U=array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0);$Jh=array_keys($U);$Ki=array();$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");$md=array("hex","length","lower","round","unixepoch","upper");$sd=array("avg","count","count distinct","group_concat","max","min","sum");$oc=array(array(),array("integer|real|numeric"=>"+/-","text"=>"||",));}$gc["pgsql"]="PostgreSQL";if(isset($_GET["pgsql"])){$jg=array("PgSQL","PDO_PgSQL");define("DRIVER","pgsql");if(extension_loaded("pgsql")){class
Min_DB{var$extension="PgSQL",$_link,$_result,$_string,$_database=true,$server_info,$affected_rows,$error,$timeout;function
_error($zc,$n){if(ini_bool("html_errors"))$n=html_entity_decode(strip_tags($n));$n=preg_replace('~^[^:]*: ~','',$n);$this->error=$n;}function
connect($M,$V,$E){global$b;$l=$b->database();set_error_handler(array($this,'_error'));$this->_string="host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' user='".addcslashes($V,"'\\")."' password='".addcslashes($E,"'\\")."'";$this->_link=@pg_connect("$this->_string dbname='".($l!=""?addcslashes($l,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->_link&&$l!=""){$this->_database=false;$this->_link=@pg_connect("$this->_string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->_link){$aj=pg_version($this->_link);$this->server_info=$aj["server"];pg_set_client_encoding($this->_link,"UTF8");}return(bool)$this->_link;}function
quote($P){return"'".pg_escape_string($this->_link,$P)."'";}function
value($X,$o){return($o["type"]=="bytea"?pg_unescape_bytea($X):$X);}function
quoteBinary($P){return"'".pg_escape_bytea($this->_link,$P)."'";}function
select_db($j){global$b;if($j==$b->database())return$this->_database;$H=@pg_connect("$this->_string dbname='".addcslashes($j,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($H)$this->_link=$H;return$H;}function
close(){$this->_link=@pg_connect("$this->_string dbname='postgres'");}function
query($F,$Ei=false){$G=@pg_query($this->_link,$F);$this->error="";if(!$G){$this->error=pg_last_error($this->_link);$H=false;}elseif(!pg_num_fields($G)){$this->affected_rows=pg_affected_rows($G);$H=true;}else$H=new
Min_Result($G);if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$H;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;return
pg_fetch_result($G->_result,0,$o);}function
warnings(){return
h(pg_last_notice($this->_link));}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;$this->num_rows=pg_num_rows($G);}function
fetch_assoc(){return
pg_fetch_assoc($this->_result);}function
fetch_row(){return
pg_fetch_row($this->_result);}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;if(function_exists('pg_field_table'))$H->orgtable=pg_field_table($this->_result,$e);$H->name=pg_field_name($this->_result,$e);$H->orgname=$H->name;$H->type=pg_field_type($this->_result,$e);$H->charsetnr=($H->type=="bytea"?63:0);return$H;}function
__destruct(){pg_free_result($this->_result);}}}elseif(extension_loaded("pdo_pgsql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_PgSQL",$timeout;function
connect($M,$V,$E){global$b;$l=$b->database();$P="pgsql:host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' options='-c client_encoding=utf8'";$this->dsn("$P dbname='".($l!=""?addcslashes($l,"'\\"):"postgres")."'",$V,$E);return
true;}function
select_db($j){global$b;return($b->database()==$j);}function
quoteBinary($ah){return
q($ah);}function
query($F,$Ei=false){$H=parent::query($F,$Ei);if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$H;}function
warnings(){return'';}function
close(){}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$mg){global$g;foreach($J
as$N){$Li=array();$Z=array();foreach($N
as$y=>$X){$Li[]="$y = $X";if(isset($mg[idf_unescape($y)]))$Z[]="$y = $X";}if(!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ",$Li)." WHERE ".implode(" AND ",$Z))&&$g->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
slowQuery($F,$hi){$this->_conn->query("SET statement_timeout = ".(1000*$hi));$this->_conn->timeout=1000*$hi;return$F;}function
convertSearch($u,$X,$o){return(preg_match('~char|text'.(!preg_match('~LIKE~',$X["op"])?'|date|time(stamp)?|boolean|uuid|'.number_type():'').'~',$o["type"])?$u:"CAST($u AS text)");}function
quoteBinary($ah){return$this->_conn->quoteBinary($ah);}function
warnings(){return$this->_conn->warnings();}function
tableHelp($B){$ye=array("information_schema"=>"infoschema","pg_catalog"=>"catalog",);$_=$ye[$_GET["ns"]];if($_)return"$_-".str_replace("_","-",$B).".html";}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b,$U,$Jh;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2])){if(min_version(9,0,$g)){$g->query("SET application_name = 'Adminer'");if(min_version(9.2,0,$g)){$Jh['Strings'][]="json";$U["json"]=4294967295;if(min_version(9.4,0,$g)){$Jh['Strings'][]="jsonb";$U["jsonb"]=4294967295;}}}return$g;}return$g->error;}function
get_databases(){return
get_vals("SELECT datname FROM pg_database WHERE has_database_privilege(datname, 'CONNECT') ORDER BY datname");}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return(preg_match('~^INTO~',$F)?limit($F,$Z,1,0,$L):" $F".(is_view(table_status1($Q))?$Z:" WHERE ctid = (SELECT ctid FROM ".table($Q).$Z.$L."LIMIT 1)"));}function
db_collation($l,$pb){global$g;return$g->result("SHOW LC_COLLATE");}function
engines(){return
array();}function
logged_user(){global$g;return$g->result("SELECT user");}function
tables_list(){$F="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support('materializedview'))$F.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$F.="
ORDER BY 1";return
get_key_vals($F);}function
count_tables($k){return
array();}function
table_status($B=""){$H=array();foreach(get_rows("SELECT c.relname AS \"Name\", CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\", pg_relation_size(c.oid) AS \"Data_length\", pg_total_relation_size(c.oid) - pg_relation_size(c.oid) AS \"Index_length\", obj_description(c.oid, 'pg_class') AS \"Comment\", ".(min_version(12)?"''":"CASE WHEN c.relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\", c.reltuples as \"Rows\", n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f')
".($B!=""?"AND relname = ".q($B):"ORDER BY relname"))as$I)$H[$I["Name"]]=$I;return($B!=""?$H[$B]:$H);}function
is_view($R){return
in_array($R["Engine"],array("view","materialized view"));}function
fk_support($R){return
true;}function
fields($Q){$H=array();$Ca=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz',);$Fd=min_version(10)?"(a.attidentity = 'd')::int":'0';foreach(get_rows("SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, pg_get_expr(d.adbin, d.adrelid) AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment, $Fd AS identity
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = ".q($Q)."
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$I){preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$I["full_type"],$A);list(,$T,$ve,$I["length"],$wa,$Fa)=$A;$I["length"].=$Fa;$eb=$T.$wa;if(isset($Ca[$eb])){$I["type"]=$Ca[$eb];$I["full_type"]=$I["type"].$ve.$Fa;}else{$I["type"]=$T;$I["full_type"]=$I["type"].$ve.$wa.$Fa;}if($I['identity'])$I['default']='GENERATED BY DEFAULT AS IDENTITY';$I["null"]=!$I["attnotnull"];$I["auto_increment"]=$I['identity']||preg_match('~^nextval\(~i',$I["default"]);$I["privileges"]=array("insert"=>1,"select"=>1,"update"=>1);if(preg_match('~(.+)::[^)]+(.*)~',$I["default"],$A))$I["default"]=($A[1]=="NULL"?null:(($A[1][0]=="'"?idf_unescape($A[1]):$A[1]).$A[2]));$H[$I["field"]]=$I;}return$H;}function
indexes($Q,$h=null){global$g;if(!is_object($h))$h=$g;$H=array();$Sh=$h->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($Q));$f=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Sh AND attnum > 0",$h);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption , (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $Sh AND ci.oid = i.indexrelid",$h)as$I){$Kg=$I["relname"];$H[$Kg]["type"]=($I["indispartial"]?"INDEX":($I["indisprimary"]?"PRIMARY":($I["indisunique"]?"UNIQUE":"INDEX")));$H[$Kg]["columns"]=array();foreach(explode(" ",$I["indkey"])as$Pd)$H[$Kg]["columns"][]=$f[$Pd];$H[$Kg]["descs"]=array();foreach(explode(" ",$I["indoption"])as$Qd)$H[$Kg]["descs"][]=($Qd&1?'1':null);$H[$Kg]["lengths"]=array();}return$H;}function
foreign_keys($Q){global$sf;$H=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = ".q($Q)." AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname")as$I){if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$I['definition'],$A)){$I['source']=array_map('trim',explode(',',$A[1]));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$A[2],$Ee)){$I['ns']=str_replace('""','"',preg_replace('~^"(.+)"$~','\1',$Ee[2]));$I['table']=str_replace('""','"',preg_replace('~^"(.+)"$~','\1',$Ee[4]));}$I['target']=array_map('trim',explode(',',$A[3]));$I['on_delete']=(preg_match("~ON DELETE ($sf)~",$A[4],$Ee)?$Ee[1]:'NO ACTION');$I['on_update']=(preg_match("~ON UPDATE ($sf)~",$A[4],$Ee)?$Ee[1]:'NO ACTION');$H[$I['conname']]=$I;}}return$H;}function
view($B){global$g;return
array("select"=>trim($g->result("SELECT pg_get_viewdef(".$g->result("SELECT oid FROM pg_class WHERE relname = ".q($B)).")")));}function
collations(){return
array();}function
information_schema($l){return($l=="information_schema");}function
error(){global$g;$H=h($g->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$H,$A))$H=$A[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($A[3]).'})(.*)~','\1<b>\2</b>',$A[2]).$A[4];return
nl_br($H);}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).($d?" ENCODING ".idf_escape($d):""));}function
drop_databases($k){global$g;$g->close();return
apply_queries("DROP DATABASE",$k,'idf_escape');}function
rename_database($B,$d){return
queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($B));}function
auto_increment(){return"";}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=array();$xg=array();if($Q!=""&&$Q!=$B)$xg[]="ALTER TABLE ".table($Q)." RENAME TO ".table($B);foreach($p
as$o){$e=idf_escape($o[0]);$X=$o[1];if(!$X)$c[]="DROP $e";else{$Wi=$X[5];unset($X[5]);if(isset($X[6])&&$o[0]=="")$X[1]=($X[1]=="bigint"?" big":" ")."serial";if($o[0]=="")$c[]=($Q!=""?"ADD ":"  ").implode($X);else{if($e!=$X[0])$xg[]="ALTER TABLE ".table($B)." RENAME $e TO $X[0]";$c[]="ALTER $e TYPE$X[1]";if(!$X[6]){$c[]="ALTER $e ".($X[3]?"SET$X[3]":"DROP DEFAULT");$c[]="ALTER $e ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];}}if($o[0]!=""||$Wi!="")$xg[]="COMMENT ON COLUMN ".table($B).".$X[0] IS ".($Wi!=""?substr($Wi,9):"''");}}$c=array_merge($c,$ed);if($Q=="")array_unshift($xg,"CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");elseif($c)array_unshift($xg,"ALTER TABLE ".table($Q)."\n".implode(",\n",$c));if($Q!=""||$ub!="")$xg[]="COMMENT ON TABLE ".table($B)." IS ".q($ub);if($Ma!=""){}foreach($xg
as$F){if(!queries($F))return
false;}return
true;}function
alter_indexes($Q,$c){$i=array();$hc=array();$xg=array();foreach($c
as$X){if($X[0]!="INDEX")$i[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");elseif($X[2]=="DROP")$hc[]=idf_escape($X[1]);else$xg[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$X[2]).")";}if($i)array_unshift($xg,"ALTER TABLE ".table($Q).implode(",",$i));if($hc)array_unshift($xg,"DROP INDEX ".implode(", ",$hc));foreach($xg
as$F){if(!queries($F))return
false;}return
true;}function
truncate_tables($S){return
queries("TRUNCATE ".implode(", ",array_map('table',$S)));return
true;}function
drop_views($cj){return
drop_tables($cj);}function
drop_tables($S){foreach($S
as$Q){$O=table_status($Q);if(!queries("DROP ".strtoupper($O["Engine"])." ".table($Q)))return
false;}return
true;}function
move_tables($S,$cj,$Zh){foreach(array_merge($S,$cj)as$Q){$O=table_status($Q);if(!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($Zh)))return
false;}return
true;}function
trigger($B,$Q=null){if($B=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");if($Q===null)$Q=$_GET['trigger'];$J=get_rows('SELECT t.trigger_name AS "Trigger", t.action_timing AS "Timing", (SELECT STRING_AGG(event_manipulation, \' OR \') FROM information_schema.triggers WHERE event_object_table = t.event_object_table AND trigger_name = t.trigger_name ) AS "Events", t.event_manipulation AS "Event", \'FOR EACH \' || t.action_orientation AS "Type", t.action_statement AS "Statement" FROM information_schema.triggers t WHERE t.event_object_table = '.q($Q).' AND t.trigger_name = '.q($B));return
reset($J);}function
triggers($Q){$H=array();foreach(get_rows("SELECT * FROM information_schema.triggers WHERE event_object_table = ".q($Q))as$I)$H[$I["trigger_name"]]=array($I["action_timing"],$I["event_manipulation"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($B,$T){$J=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($B));$H=$J[0];$H["returns"]=array("type"=>$H["type_udt_name"]);$H["fields"]=get_rows('SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = '.q($B).'
ORDER BY ordinal_position');return$H;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');}function
routine_languages(){return
get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");}function
routine_id($B,$I){$H=array();foreach($I["fields"]as$o)$H[]=$o["type"];return
idf_escape($B)."(".implode(", ",$H).")";}function
last_id(){return
0;}function
explain($g,$F){return$g->query("EXPLAIN $F");}function
found_rows($R,$Z){global$g;if(preg_match("~ rows=([0-9]+)~",$g->result("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$Jg))return$Jg[1];return
false;}function
types(){return
get_vals("SELECT typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){global$g;return$g->result("SELECT current_schema()");}function
set_schema($ch,$h=null){global$g,$U,$Jh;if(!$h)$h=$g;$H=$h->query("SET search_path TO ".idf_escape($ch));foreach(types()as$T){if(!isset($U[$T])){$U[$T]=0;$Jh['User types'][]=$T;}}return$H;}function
create_sql($Q,$Ma,$Kh){global$g;$H='';$Sg=array();$mh=array();$O=table_status($Q);if(is_view($O)){$bj=view($Q);return
rtrim("CREATE VIEW ".idf_escape($Q)." AS $bj[select]",";");}$p=fields($Q);$w=indexes($Q);ksort($w);$bd=foreign_keys($Q);ksort($bd);if(!$O||empty($p))return
false;$H="CREATE TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." (\n    ";foreach($p
as$Tc=>$o){$Tf=idf_escape($o['field']).' '.$o['full_type'].default_value($o).($o['attnotnull']?" NOT NULL":"");$Sg[]=$Tf;if(preg_match('~nextval\(\'([^\']+)\'\)~',$o['default'],$Fe)){$lh=$Fe[1];$_h=reset(get_rows(min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q($lh):"SELECT * FROM $lh"));$mh[]=($Kh=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $lh;\n":"")."CREATE SEQUENCE $lh INCREMENT $_h[increment_by] MINVALUE $_h[min_value] MAXVALUE $_h[max_value] START ".($Ma?$_h['last_value']:1)." CACHE $_h[cache_value];";}}if(!empty($mh))$H=implode("\n\n",$mh)."\n\n$H";foreach($w
as$Kd=>$v){switch($v['type']){case'UNIQUE':$Sg[]="CONSTRAINT ".idf_escape($Kd)." UNIQUE (".implode(', ',array_map('idf_escape',$v['columns'])).")";break;case'PRIMARY':$Sg[]="CONSTRAINT ".idf_escape($Kd)." PRIMARY KEY (".implode(', ',array_map('idf_escape',$v['columns'])).")";break;}}foreach($bd
as$ad=>$Zc)$Sg[]="CONSTRAINT ".idf_escape($ad)." $Zc[definition] ".($Zc['deferrable']?'DEFERRABLE':'NOT DEFERRABLE');$H.=implode(",\n    ",$Sg)."\n) WITH (oids = ".($O['Oid']?'true':'false').");";foreach($w
as$Kd=>$v){if($v['type']=='INDEX'){$f=array();foreach($v['columns']as$y=>$X)$f[]=idf_escape($X).($v['descs'][$y]?" DESC":"");$H.="\n\nCREATE INDEX ".idf_escape($Kd)." ON ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." USING btree (".implode(', ',$f).");";}}if($O['Comment'])$H.="\n\nCOMMENT ON TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." IS ".q($O['Comment']).";";foreach($p
as$Tc=>$o){if($o['comment'])$H.="\n\nCOMMENT ON COLUMN ".idf_escape($O['nspname']).".".idf_escape($O['Name']).".".idf_escape($Tc)." IS ".q($o['comment']).";";}return
rtrim($H,';');}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
trigger_sql($Q){$O=table_status($Q);$H="";foreach(triggers($Q)as$yi=>$xi){$zi=trigger($yi,$O['Name']);$H.="\nCREATE TRIGGER ".idf_escape($zi['Trigger'])." $zi[Timing] $zi[Events] ON ".idf_escape($O["nspname"]).".".idf_escape($O['Name'])." $zi[Type] $zi[Statement];;\n";}return$H;}function
use_sql($j){return"\connect ".idf_escape($j);}function
show_variables(){return
get_key_vals("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
show_status(){}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(database|table|columns|sql|indexes|descidx|comment|view|'.(min_version(9.3)?'materializedview|':'').'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~',$Rc);}function
kill_process($X){return
queries("SELECT pg_terminate_backend(".number($X).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){global$g;return$g->result("SHOW max_connections");}$x="pgsql";$U=array();$Jh=array();foreach(array('Numbers'=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),'Date and time'=>array("date"=>13,"time"=>17,"timestamp"=>20,"timestamptz"=>21,"interval"=>0),'Strings'=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),'Binary'=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),'Network'=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"txid_snapshot"=>0),'Geometry'=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");$md=array("char_length","lower","round","to_hex","to_timestamp","upper");$sd=array("avg","count","count distinct","max","min","sum");$oc=array(array("char"=>"md5","date|time"=>"now",),array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",));}$gc["oracle"]="Oracle (beta)";if(isset($_GET["oracle"])){$jg=array("OCI8","PDO_OCI");define("DRIVER","oracle");if(extension_loaded("oci8")){class
Min_DB{var$extension="oci8",$_link,$_result,$server_info,$affected_rows,$errno,$error;function
_error($zc,$n){if(ini_bool("html_errors"))$n=html_entity_decode(strip_tags($n));$n=preg_replace('~^[^:]*: ~','',$n);$this->error=$n;}function
connect($M,$V,$E){$this->_link=@oci_new_connect($V,$E,$M,"AL32UTF8");if($this->_link){$this->server_info=oci_server_version($this->_link);return
true;}$n=oci_error();$this->error=$n["message"];return
false;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return
true;}function
query($F,$Ei=false){$G=oci_parse($this->_link,$F);$this->error="";if(!$G){$n=oci_error($this->_link);$this->errno=$n["code"];$this->error=$n["message"];return
false;}set_error_handler(array($this,'_error'));$H=@oci_execute($G);restore_error_handler();if($H){if(oci_num_fields($G))return
new
Min_Result($G);$this->affected_rows=oci_num_rows($G);}return$H;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=1){$G=$this->query($F);if(!is_object($G)||!oci_fetch($G->_result))return
false;return
oci_result($G->_result,$o);}}class
Min_Result{var$_result,$_offset=1,$num_rows;function
__construct($G){$this->_result=$G;}function
_convert($I){foreach((array)$I
as$y=>$X){if(is_a($X,'OCI-Lob'))$I[$y]=$X->load();}return$I;}function
fetch_assoc(){return$this->_convert(oci_fetch_assoc($this->_result));}function
fetch_row(){return$this->_convert(oci_fetch_row($this->_result));}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;$H->name=oci_field_name($this->_result,$e);$H->orgname=$H->name;$H->type=oci_field_type($this->_result,$e);$H->charsetnr=(preg_match("~raw|blob|bfile~",$H->type)?63:0);return$H;}function
__destruct(){oci_free_statement($this->_result);}}}elseif(extension_loaded("pdo_oci")){class
Min_DB
extends
Min_PDO{var$extension="PDO_OCI";function
connect($M,$V,$E){$this->dsn("oci:dbname=//$M;charset=AL32UTF8",$V,$E);return
true;}function
select_db($j){return
true;}}}class
Min_Driver
extends
Min_SQL{function
begin(){return
true;}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases(){return
get_vals("SELECT tablespace_name FROM user_tablespaces");}function
limit($F,$Z,$z,$C=0,$L=" "){return($C?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= ".($z+$C).") WHERE rnum > $C":($z!==null?" * FROM (SELECT $F$Z) WHERE rownum <= ".($z+$C):" $F$Z"));}function
limit1($Q,$F,$Z,$L="\n"){return" $F$Z";}function
db_collation($l,$pb){global$g;return$g->result("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
engines(){return
array();}function
logged_user(){global$g;return$g->result("SELECT USER FROM DUAL");}function
tables_list(){return
get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."
UNION SELECT view_name, 'view' FROM user_views
ORDER BY 1");}function
count_tables($k){return
array();}function
table_status($B=""){$H=array();$eh=q($B);foreach(get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = '.q(DB).($B!=""?" AND table_name = $eh":"")."
UNION SELECT view_name, 'view', 0, 0 FROM user_views".($B!=""?" WHERE view_name = $eh":"")."
ORDER BY 1")as$I){if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){return
true;}function
fields($Q){$H=array();foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)." ORDER BY column_id")as$I){$T=$I["DATA_TYPE"];$ve="$I[DATA_PRECISION],$I[DATA_SCALE]";if($ve==",")$ve=$I["DATA_LENGTH"];$H[$I["COLUMN_NAME"]]=array("field"=>$I["COLUMN_NAME"],"full_type"=>$T.($ve?"($ve)":""),"type"=>strtolower($T),"length"=>$ve,"default"=>$I["DATA_DEFAULT"],"null"=>($I["NULLABLE"]=="Y"),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SELECT uic.*, uc.constraint_type
FROM user_ind_columns uic
LEFT JOIN user_constraints uc ON uic.index_name = uc.constraint_name AND uic.table_name = uc.table_name
WHERE uic.table_name = ".q($Q)."
ORDER BY uc.constraint_type, uic.column_position",$h)as$I){$Kd=$I["INDEX_NAME"];$H[$Kd]["type"]=($I["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($I["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));$H[$Kd]["columns"][]=$I["COLUMN_NAME"];$H[$Kd]["lengths"][]=($I["CHAR_LENGTH"]&&$I["CHAR_LENGTH"]!=$I["COLUMN_LENGTH"]?$I["CHAR_LENGTH"]:null);$H[$Kd]["descs"][]=($I["DESCEND"]?'1':null);}return$H;}function
view($B){$J=get_rows('SELECT text "select" FROM user_views WHERE view_name = '.q($B));return
reset($J);}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
explain($g,$F){$g->query("EXPLAIN PLAN FOR $F");return$g->query("SELECT * FROM plan_table");}function
found_rows($R,$Z){}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=$hc=array();foreach($p
as$o){$X=$o[1];if($X&&$o[0]!=""&&idf_escape($o[0])!=$X[0])queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($o[0])." TO $X[0]");if($X)$c[]=($Q!=""?($o[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($Q!=""?")":"");else$hc[]=idf_escape($o[0]);}if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");return(!$c||queries("ALTER TABLE ".table($Q)."\n".implode("\n",$c)))&&(!$hc||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ",$hc).")"))&&($Q==$B||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)));}function
foreign_keys($Q){$H=array();$F="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($Q);foreach(get_rows($F)as$I)$H[$I['NAME']]=array("db"=>$I['DEST_DB'],"table"=>$I['DEST_TABLE'],"source"=>array($I['SRC_COLUMN']),"target"=>array($I['DEST_COLUMN']),"on_delete"=>$I['ON_DELETE'],"on_update"=>null,);return$H;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
apply_queries("DROP VIEW",$cj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
last_id(){return
0;}function
schemas(){return
get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX'))");}function
get_schema(){global$g;return$g->result("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");}function
set_schema($dh,$h=null){global$g;if(!$h)$h=$g;return$h->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($dh));}function
show_variables(){return
get_key_vals('SELECT name, display_value FROM v$parameter');}function
process_list(){return
get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
show_status(){$J=get_rows('SELECT * FROM v$instance');return
reset($J);}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view|view_trigger)$~',$Rc);}$x="oracle";$U=array();$Jh=array();foreach(array('Numbers'=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),'Date and time'=>array("date"=>10,"timestamp"=>29,"interval year"=>12,"interval day"=>28),'Strings'=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),'Binary'=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");$md=array("length","lower","round","upper");$sd=array("avg","count","count distinct","max","min","sum");$oc=array(array("date"=>"current_date","timestamp"=>"current_timestamp",),array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",));}$gc["mssql"]="MS SQL (beta)";if(isset($_GET["mssql"])){$jg=array("SQLSRV","MSSQL","PDO_DBLIB");define("DRIVER","mssql");if(extension_loaded("sqlsrv")){class
Min_DB{var$extension="sqlsrv",$_link,$_result,$server_info,$affected_rows,$errno,$error;function
_get_error(){$this->error="";foreach(sqlsrv_errors()as$n){$this->errno=$n["code"];$this->error.="$n[message]\n";}$this->error=rtrim($this->error);}function
connect($M,$V,$E){global$b;$l=$b->database();$zb=array("UID"=>$V,"PWD"=>$E,"CharacterSet"=>"UTF-8");if($l!="")$zb["Database"]=$l;$this->_link=@sqlsrv_connect(preg_replace('~:~',',',$M),$zb);if($this->_link){$Rd=sqlsrv_server_info($this->_link);$this->server_info=$Rd['SQLServerVersion'];}else$this->_get_error();return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return$this->query("USE ".idf_escape($j));}function
query($F,$Ei=false){$G=sqlsrv_query($this->_link,$F);$this->error="";if(!$G){$this->_get_error();return
false;}return$this->store_result($G);}function
multi_query($F){$this->_result=sqlsrv_query($this->_link,$F);$this->error="";if(!$this->_result){$this->_get_error();return
false;}return
true;}function
store_result($G=null){if(!$G)$G=$this->_result;if(!$G)return
false;if(sqlsrv_field_metadata($G))return
new
Min_Result($G);$this->affected_rows=sqlsrv_rows_affected($G);return
true;}function
next_result(){return$this->_result?sqlsrv_next_result($this->_result):null;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->fetch_row();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$_fields,$num_rows;function
__construct($G){$this->_result=$G;}function
_convert($I){foreach((array)$I
as$y=>$X){if(is_a($X,'DateTime'))$I[$y]=$X->format("Y-m-d H:i:s");}return$I;}function
fetch_assoc(){return$this->_convert(sqlsrv_fetch_array($this->_result,SQLSRV_FETCH_ASSOC));}function
fetch_row(){return$this->_convert(sqlsrv_fetch_array($this->_result,SQLSRV_FETCH_NUMERIC));}function
fetch_field(){if(!$this->_fields)$this->_fields=sqlsrv_field_metadata($this->_result);$o=$this->_fields[$this->_offset++];$H=new
stdClass;$H->name=$o["Name"];$H->orgname=$o["Name"];$H->type=($o["Type"]==1?254:0);return$H;}function
seek($C){for($s=0;$s<$C;$s++)sqlsrv_fetch($this->_result);}function
__destruct(){sqlsrv_free_stmt($this->_result);}}}elseif(extension_loaded("mssql")){class
Min_DB{var$extension="MSSQL",$_link,$_result,$server_info,$affected_rows,$error;function
connect($M,$V,$E){$this->_link=@mssql_connect($M,$V,$E);if($this->_link){$G=$this->query("SELECT SERVERPROPERTY('ProductLevel'), SERVERPROPERTY('Edition')");if($G){$I=$G->fetch_row();$this->server_info=$this->result("sp_server_info 2",2)." [$I[0]] $I[1]";}}else$this->error=mssql_get_last_message();return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return
mssql_select_db($j);}function
query($F,$Ei=false){$G=@mssql_query($F,$this->_link);$this->error="";if(!$G){$this->error=mssql_get_last_message();return
false;}if($G===true){$this->affected_rows=mssql_rows_affected($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
mssql_next_result($this->_result->_result);}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;return
mssql_result($G->_result,0,$o);}}class
Min_Result{var$_result,$_offset=0,$_fields,$num_rows;function
__construct($G){$this->_result=$G;$this->num_rows=mssql_num_rows($G);}function
fetch_assoc(){return
mssql_fetch_assoc($this->_result);}function
fetch_row(){return
mssql_fetch_row($this->_result);}function
num_rows(){return
mssql_num_rows($this->_result);}function
fetch_field(){$H=mssql_fetch_field($this->_result);$H->orgtable=$H->table;$H->orgname=$H->name;return$H;}function
seek($C){mssql_data_seek($this->_result,$C);}function
__destruct(){mssql_free_result($this->_result);}}}elseif(extension_loaded("pdo_dblib")){class
Min_DB
extends
Min_PDO{var$extension="PDO_DBLIB";function
connect($M,$V,$E){$this->dsn("dblib:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E);return
true;}function
select_db($j){return$this->query("USE ".idf_escape($j));}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$mg){foreach($J
as$N){$Li=array();$Z=array();foreach($N
as$y=>$X){$Li[]="$y = $X";if(isset($mg[idf_unescape($y)]))$Z[]="$y = $X";}if(!queries("MERGE ".table($Q)." USING (VALUES(".implode(", ",$N).")) AS source (c".implode(", c",range(1,count($N))).") ON ".implode(" AND ",$Z)." WHEN MATCHED THEN UPDATE SET ".implode(", ",$Li)." WHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).");"))return
false;}return
true;}function
begin(){return
queries("BEGIN TRANSACTION");}}function
idf_escape($u){return"[".str_replace("]","]]",$u)."]";}function
table($u){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($u);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases(){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($F,$Z,$z,$C=0,$L=" "){return($z!==null?" TOP (".($z+$C).")":"")." $F$Z";}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){global$g;return$g->result("SELECT collation_name FROM sys.databases WHERE name = ".q($l));}function
engines(){return
array();}function
logged_user(){global$g;return$g->result("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables($k){global$g;$H=array();foreach($k
as$l){$g->select_db($l);$H[$l]=$g->result("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$H;}function
table_status($B=""){$H=array();foreach(get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment FROM sys.all_objects AS ao WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$I){if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]=="VIEW";}function
fk_support($R){return
true;}function
fields($Q){$wb=get_key_vals("SELECT objname, cast(value as varchar) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");$H=array();foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default]
FROM sys.all_columns c
JOIN sys.all_objects o ON c.object_id = o.object_id
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.parent_column_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.type IN ('S', 'U', 'V') AND o.name = ".q($Q))as$I){$T=$I["type"];$ve=(preg_match("~char|binary~",$T)?$I["max_length"]:($T=="decimal"?"$I[precision],$I[scale]":""));$H[$I["name"]]=array("field"=>$I["name"],"full_type"=>$T.($ve?"($ve)":""),"type"=>$T,"length"=>$ve,"default"=>$I["default"],"null"=>$I["is_nullable"],"auto_increment"=>$I["is_identity"],"collation"=>$I["collation_name"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),"primary"=>$I["is_identity"],"comment"=>$wb[$I["name"]],);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q),$h)as$I){$B=$I["name"];$H[$B]["type"]=($I["is_primary_key"]?"PRIMARY":($I["is_unique"]?"UNIQUE":"INDEX"));$H[$B]["lengths"]=array();$H[$B]["columns"][$I["key_ordinal"]]=$I["column_name"];$H[$B]["descs"][$I["key_ordinal"]]=($I["is_descending_key"]?'1':null);}return$H;}function
view($B){global$g;return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',$g->result("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($B))));}function
collations(){$H=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$d)$H[preg_replace('~_.*~','',$d)][]=$d;return$H;}function
information_schema($l){return
false;}function
error(){global$g;return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',$g->error)));}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).(preg_match('~^[a-z0-9_]+$~i',$d)?" COLLATE $d":""));}function
drop_databases($k){return
queries("DROP DATABASE ".implode(", ",array_map('idf_escape',$k)));}function
rename_database($B,$d){if(preg_match('~^[a-z0-9_]+$~i',$d))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $d");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($B));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=array();$wb=array();foreach($p
as$o){$e=idf_escape($o[0]);$X=$o[1];if(!$X)$c["DROP"][]=" COLUMN $e";else{$X[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$X[1]);$wb[$o[0]]=$X[5];unset($X[5]);if($o[0]=="")$c["ADD"][]="\n  ".implode("",$X).($Q==""?substr($ed[$X[0]],16+strlen($X[0])):"");else{unset($X[6]);if($e!=$X[0])queries("EXEC sp_rename ".q(table($Q).".$e").", ".q(idf_unescape($X[0])).", 'COLUMN'");$c["ALTER COLUMN ".implode("",$X)][]="";}}}if($Q=="")return
queries("CREATE TABLE ".table($B)." (".implode(",",(array)$c["ADD"])."\n)");if($Q!=$B)queries("EXEC sp_rename ".q(table($Q)).", ".q($B));if($ed)$c[""]=$ed;foreach($c
as$y=>$X){if(!queries("ALTER TABLE ".idf_escape($B)." $y".implode(",",$X)))return
false;}foreach($wb
as$y=>$X){$ub=substr($X,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table',  @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));queries("EXEC sp_addextendedproperty @name = N'MS_Description', @value = ".$ub.", @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table',  @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));}return
true;}function
alter_indexes($Q,$c){$v=array();$hc=array();foreach($c
as$X){if($X[2]=="DROP"){if($X[0]=="PRIMARY")$hc[]=idf_escape($X[1]);else$v[]=idf_escape($X[1])." ON ".table($Q);}elseif(!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ",$X[2]).")"))return
false;}return(!$v||queries("DROP INDEX ".implode(", ",$v)))&&(!$hc||queries("ALTER TABLE ".table($Q)." DROP ".implode(", ",$hc)));}function
last_id(){global$g;return$g->result("SELECT SCOPE_IDENTITY()");}function
explain($g,$F){$g->query("SET SHOWPLAN_ALL ON");$H=$g->query($F);$g->query("SET SHOWPLAN_ALL OFF");return$H;}function
found_rows($R,$Z){}function
foreign_keys($Q){$H=array();foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($Q))as$I){$q=&$H[$I["FK_NAME"]];$q["db"]=$I["PKTABLE_QUALIFIER"];$q["table"]=$I["PKTABLE_NAME"];$q["source"][]=$I["FKCOLUMN_NAME"];$q["target"][]=$I["PKCOLUMN_NAME"];}return$H;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
queries("DROP VIEW ".implode(", ",array_map('table',$cj)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('table',$S)));}function
move_tables($S,$cj,$Zh){return
apply_queries("ALTER SCHEMA ".idf_escape($Zh)." TRANSFER",array_merge($S,$cj));}function
trigger($B){if($B=="")return
array();$J=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($B));$H=reset($J);if($H)$H["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$H["text"]);return$H;}function
triggers($Q){$H=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($Q))as$I)$H[$I["name"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){global$g;if($_GET["ns"]!="")return$_GET["ns"];return$g->result("SELECT SCHEMA_NAME()");}function
set_schema($ch){return
true;}function
use_sql($j){return"USE ".idf_escape($j);}function
show_variables(){return
array();}function
show_status(){return
array();}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(comment|columns|database|drop_col|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~',$Rc);}$x="mssql";$U=array();$Jh=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20),'Date and time'=>array("date"=>10,"smalldatetime"=>19,"datetime"=>19,"datetime2"=>19,"time"=>8,"datetimeoffset"=>10),'Strings'=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823),'Binary'=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");$md=array("len","lower","round","upper");$sd=array("avg","count","count distinct","max","min","sum");$oc=array(array("date|time"=>"getdate",),array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",));}$gc['firebird']='Firebird (alpha)';if(isset($_GET["firebird"])){$jg=array("interbase");define("DRIVER","firebird");if(extension_loaded("interbase")){class
Min_DB{var$extension="Firebird",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($M,$V,$E){$this->_link=ibase_connect($M,$V,$E);if($this->_link){$Oi=explode(':',$M);$this->service_link=ibase_service_attach($Oi[0],$V,$E);$this->server_info=ibase_server_info($this->service_link,IBASE_SVC_SERVER_VERSION);}else{$this->errno=ibase_errcode();$this->error=ibase_errmsg();}return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return($j=="domain");}function
query($F,$Ei=false){$G=ibase_query($F,$this->_link);if(!$G){$this->errno=ibase_errcode();$this->error=ibase_errmsg();return
false;}$this->error="";if($G===true){$this->affected_rows=ibase_affected_rows($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;$I=$G->fetch_row();return$I[$o];}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($G){$this->_result=$G;}function
fetch_assoc(){return
ibase_fetch_assoc($this->_result);}function
fetch_row(){return
ibase_fetch_row($this->_result);}function
fetch_field(){$o=ibase_field_info($this->_result,$this->_offset++);return(object)array('name'=>$o['name'],'orgname'=>$o['name'],'type'=>$o['type'],'charsetnr'=>$o['length'],);}function
__destruct(){ibase_free_result($this->_result);}}}class
Min_Driver
extends
Min_SQL{}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases($cd){return
array("domain");}function
limit($F,$Z,$z,$C=0,$L=" "){$H='';$H.=($z!==null?$L."FIRST $z".($C?" SKIP $C":""):"");$H.=" $F$Z";return$H;}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){}function
engines(){return
array();}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
tables_list(){global$g;$F='SELECT RDB$RELATION_NAME FROM rdb$relations WHERE rdb$system_flag = 0';$G=ibase_query($g->_link,$F);$H=array();while($I=ibase_fetch_assoc($G))$H[$I['RDB$RELATION_NAME']]='table';ksort($H);return$H;}function
count_tables($k){return
array();}function
table_status($B="",$Qc=false){global$g;$H=array();$Nb=tables_list();foreach($Nb
as$v=>$X){$v=trim($v);$H[$v]=array('Name'=>$v,'Engine'=>'standard',);if($B==$v)return$H[$v];}return$H;}function
is_view($R){return
false;}function
fk_support($R){return
preg_match('~InnoDB|IBMDB2I~i',$R["Engine"]);}function
fields($Q){global$g;$H=array();$F='SELECT r.RDB$FIELD_NAME AS field_name,
r.RDB$DESCRIPTION AS field_description,
r.RDB$DEFAULT_VALUE AS field_default_value,
r.RDB$NULL_FLAG AS field_not_null_constraint,
f.RDB$FIELD_LENGTH AS field_length,
f.RDB$FIELD_PRECISION AS field_precision,
f.RDB$FIELD_SCALE AS field_scale,
CASE f.RDB$FIELD_TYPE
WHEN 261 THEN \'BLOB\'
WHEN 14 THEN \'CHAR\'
WHEN 40 THEN \'CSTRING\'
WHEN 11 THEN \'D_FLOAT\'
WHEN 27 THEN \'DOUBLE\'
WHEN 10 THEN \'FLOAT\'
WHEN 16 THEN \'INT64\'
WHEN 8 THEN \'INTEGER\'
WHEN 9 THEN \'QUAD\'
WHEN 7 THEN \'SMALLINT\'
WHEN 12 THEN \'DATE\'
WHEN 13 THEN \'TIME\'
WHEN 35 THEN \'TIMESTAMP\'
WHEN 37 THEN \'VARCHAR\'
ELSE \'UNKNOWN\'
END AS field_type,
f.RDB$FIELD_SUB_TYPE AS field_subtype,
coll.RDB$COLLATION_NAME AS field_collation,
cset.RDB$CHARACTER_SET_NAME AS field_charset
FROM RDB$RELATION_FIELDS r
LEFT JOIN RDB$FIELDS f ON r.RDB$FIELD_SOURCE = f.RDB$FIELD_NAME
LEFT JOIN RDB$COLLATIONS coll ON f.RDB$COLLATION_ID = coll.RDB$COLLATION_ID
LEFT JOIN RDB$CHARACTER_SETS cset ON f.RDB$CHARACTER_SET_ID = cset.RDB$CHARACTER_SET_ID
WHERE r.RDB$RELATION_NAME = '.q($Q).'
ORDER BY r.RDB$FIELD_POSITION';$G=ibase_query($g->_link,$F);while($I=ibase_fetch_assoc($G))$H[trim($I['FIELD_NAME'])]=array("field"=>trim($I["FIELD_NAME"]),"full_type"=>trim($I["FIELD_TYPE"]),"type"=>trim($I["FIELD_SUB_TYPE"]),"default"=>trim($I['FIELD_DEFAULT_VALUE']),"null"=>(trim($I["FIELD_NOT_NULL_CONSTRAINT"])=="YES"),"auto_increment"=>'0',"collation"=>trim($I["FIELD_COLLATION"]),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),"comment"=>trim($I["FIELD_DESCRIPTION"]),);return$H;}function
indexes($Q,$h=null){$H=array();return$H;}function
foreign_keys($Q){return
array();}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ch){return
true;}function
support($Rc){return
preg_match("~^(columns|sql|status|table)$~",$Rc);}$x="firebird";$xf=array("=");$md=array();$sd=array();$oc=array();}$gc["simpledb"]="SimpleDB";if(isset($_GET["simpledb"])){$jg=array("SimpleXML + allow_url_fopen");define("DRIVER","simpledb");if(class_exists('SimpleXMLElement')&&ini_bool('allow_url_fopen')){class
Min_DB{var$extension="SimpleXML",$server_info='2009-04-15',$error,$timeout,$next,$affected_rows,$_result;function
select_db($j){return($j=="domain");}function
query($F,$Ei=false){$Qf=array('SelectExpression'=>$F,'ConsistentRead'=>'true');if($this->next)$Qf['NextToken']=$this->next;$G=sdb_request_all('Select','Item',$Qf,$this->timeout);$this->timeout=0;if($G===false)return$G;if(preg_match('~^\s*SELECT\s+COUNT\(~i',$F)){$Nh=0;foreach($G
as$de)$Nh+=$de->Attribute->Value;$G=array((object)array('Attribute'=>array((object)array('Name'=>'Count','Value'=>$Nh,))));}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
quote($P){return"'".str_replace("'","''",$P)."'";}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0;function
__construct($G){foreach($G
as$de){$I=array();if($de->Name!='')$I['itemName()']=(string)$de->Name;foreach($de->Attribute
as$Ia){$B=$this->_processValue($Ia->Name);$Y=$this->_processValue($Ia->Value);if(isset($I[$B])){$I[$B]=(array)$I[$B];$I[$B][]=$Y;}else$I[$B]=$Y;}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=count($this->_rows);}function
_processValue($rc){return(is_object($rc)&&$rc['encoding']=='base64'?base64_decode($rc):(string)$rc);}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$je=array_keys($this->_rows[0]);return(object)array('name'=>$je[$this->_offset++]);}}}class
Min_Driver
extends
Min_SQL{public$mg="itemName()";function
_chunkRequest($Gd,$va,$Qf,$Gc=array()){global$g;foreach(array_chunk($Gd,25)as$ib){$Rf=$Qf;foreach($ib
as$s=>$t){$Rf["Item.$s.ItemName"]=$t;foreach($Gc
as$y=>$X)$Rf["Item.$s.$y"]=$X;}if(!sdb_request($va,$Rf))return
false;}$g->affected_rows=count($Gd);return
true;}function
_extractIds($Q,$yg,$z){$H=array();if(preg_match_all("~itemName\(\) = (('[^']*+')+)~",$yg,$Fe))$H=array_map('idf_unescape',$Fe[1]);else{foreach(sdb_request_all('Select','Item',array('SelectExpression'=>'SELECT itemName() FROM '.table($Q).$yg.($z?" LIMIT 1":"")))as$de)$H[]=$de->Name;}return$H;}function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$g;$g->next=$_GET["next"];$H=parent::select($Q,$K,$Z,$pd,$Bf,$z,$D,$og);$g->next=0;return$H;}function
delete($Q,$yg,$z=0){return$this->_chunkRequest($this->_extractIds($Q,$yg,$z),'BatchDeleteAttributes',array('DomainName'=>$Q));}function
update($Q,$N,$yg,$z=0,$L="\n"){$Xb=array();$Vd=array();$s=0;$Gd=$this->_extractIds($Q,$yg,$z);$t=idf_unescape($N["`itemName()`"]);unset($N["`itemName()`"]);foreach($N
as$y=>$X){$y=idf_unescape($y);if($X=="NULL"||($t!=""&&array($t)!=$Gd))$Xb["Attribute.".count($Xb).".Name"]=$y;if($X!="NULL"){foreach((array)$X
as$fe=>$W){$Vd["Attribute.$s.Name"]=$y;$Vd["Attribute.$s.Value"]=(is_array($X)?$W:idf_unescape($W));if(!$fe)$Vd["Attribute.$s.Replace"]="true";$s++;}}}$Qf=array('DomainName'=>$Q);return(!$Vd||$this->_chunkRequest(($t!=""?array($t):$Gd),'BatchPutAttributes',$Qf,$Vd))&&(!$Xb||$this->_chunkRequest($Gd,'BatchDeleteAttributes',$Qf,$Xb));}function
insert($Q,$N){$Qf=array("DomainName"=>$Q);$s=0;foreach($N
as$B=>$Y){if($Y!="NULL"){$B=idf_unescape($B);if($B=="itemName()")$Qf["ItemName"]=idf_unescape($Y);else{foreach((array)$Y
as$X){$Qf["Attribute.$s.Name"]=$B;$Qf["Attribute.$s.Value"]=(is_array($Y)?$X:idf_unescape($Y));$s++;}}}}return
sdb_request('PutAttributes',$Qf);}function
insertUpdate($Q,$J,$mg){foreach($J
as$N){if(!$this->update($Q,$N,"WHERE `itemName()` = ".q($N["`itemName()`"])))return
false;}return
true;}function
begin(){return
false;}function
commit(){return
false;}function
rollback(){return
false;}function
slowQuery($F,$hi){$this->_conn->timeout=$hi;return$F;}}function
connect(){global$b;list(,,$E)=$b->credentials();if($E!="")return'Database does not support password.';return
new
Min_DB;}function
support($Rc){return
preg_match('~sql~',$Rc);}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
get_databases(){return
array("domain");}function
collations(){return
array();}function
db_collation($l,$pb){}function
tables_list(){global$g;$H=array();foreach(sdb_request_all('ListDomains','DomainName')as$Q)$H[(string)$Q]='table';if($g->error&&defined("PAGE_HEADER"))echo"<p class='error'>".error()."\n";return$H;}function
table_status($B="",$Qc=false){$H=array();foreach(($B!=""?array($B=>true):tables_list())as$Q=>$T){$I=array("Name"=>$Q,"Auto_increment"=>"");if(!$Qc){$Se=sdb_request('DomainMetadata',array('DomainName'=>$Q));if($Se){foreach(array("Rows"=>"ItemCount","Data_length"=>"ItemNamesSizeBytes","Index_length"=>"AttributeValuesSizeBytes","Data_free"=>"AttributeNamesSizeBytes",)as$y=>$X)$I[$y]=(string)$Se->$X;}}if($B!="")return$I;$H[$Q]=$I;}return$H;}function
explain($g,$F){}function
error(){global$g;return
h($g->error);}function
information_schema(){}function
is_view($R){}function
indexes($Q,$h=null){return
array(array("type"=>"PRIMARY","columns"=>array("itemName()")),);}function
fields($Q){return
fields_from_edit();}function
foreign_keys($Q){return
array();}function
table($u){return
idf_escape($u);}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z":"");}function
unconvert_field($o,$H){return$H;}function
fk_support($R){}function
engines(){return
array();}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){return($Q==""&&sdb_request('CreateDomain',array('DomainName'=>$B)));}function
drop_tables($S){foreach($S
as$Q){if(!sdb_request('DeleteDomain',array('DomainName'=>$Q)))return
false;}return
true;}function
count_tables($k){foreach($k
as$l)return
array($l=>count(tables_list()));}function
found_rows($R,$Z){return($Z?null:$R["Rows"]);}function
last_id(){}function
hmac($Ba,$Nb,$y,$Bg=false){$Va=64;if(strlen($y)>$Va)$y=pack("H*",$Ba($y));$y=str_pad($y,$Va,"\0");$ge=$y^str_repeat("\x36",$Va);$he=$y^str_repeat("\x5C",$Va);$H=$Ba($he.pack("H*",$Ba($ge.$Nb)));if($Bg)$H=pack("H*",$H);return$H;}function
sdb_request($va,$Qf=array()){global$b,$g;list($Cd,$Qf['AWSAccessKeyId'],$fh)=$b->credentials();$Qf['Action']=$va;$Qf['Timestamp']=gmdate('Y-m-d\TH:i:s+00:00');$Qf['Version']='2009-04-15';$Qf['SignatureVersion']=2;$Qf['SignatureMethod']='HmacSHA1';ksort($Qf);$F='';foreach($Qf
as$y=>$X)$F.='&'.rawurlencode($y).'='.rawurlencode($X);$F=str_replace('%7E','~',substr($F,1));$F.="&Signature=".urlencode(base64_encode(hmac('sha1',"POST\n".preg_replace('~^https?://~','',$Cd)."\n/\n$F",$fh,true)));@ini_set('track_errors',1);$Vc=@file_get_contents((preg_match('~^https?://~',$Cd)?$Cd:"http://$Cd"),false,stream_context_create(array('http'=>array('method'=>'POST','content'=>$F,'ignore_errors'=>1,))));if(!$Vc){$g->error=$php_errormsg;return
false;}libxml_use_internal_errors(true);$pj=simplexml_load_string($Vc);if(!$pj){$n=libxml_get_last_error();$g->error=$n->message;return
false;}if($pj->Errors){$n=$pj->Errors->Error;$g->error="$n->Message ($n->Code)";return
false;}$g->error='';$Yh=$va."Result";return($pj->$Yh?$pj->$Yh:true);}function
sdb_request_all($va,$Yh,$Qf=array(),$hi=0){$H=array();$Fh=($hi?microtime(true):0);$z=(preg_match('~LIMIT\s+(\d+)\s*$~i',$Qf['SelectExpression'],$A)?$A[1]:0);do{$pj=sdb_request($va,$Qf);if(!$pj)break;foreach($pj->$Yh
as$rc)$H[]=$rc;if($z&&count($H)>=$z){$_GET["next"]=$pj->NextToken;break;}if($hi&&microtime(true)-$Fh>$hi)return
false;$Qf['NextToken']=$pj->NextToken;if($z)$Qf['SelectExpression']=preg_replace('~\d+\s*$~',$z-count($H),$Qf['SelectExpression']);}while($pj->NextToken);return$H;}$x="simpledb";$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","IS NOT NULL");$md=array();$sd=array("count");$oc=array(array("json"));}$gc["mongo"]="MongoDB";if(isset($_GET["mongo"])){$jg=array("mongo","mongodb");define("DRIVER","mongo");if(class_exists('MongoDB')){class
Min_DB{var$extension="Mongo",$server_info=MongoClient::VERSION,$error,$last_id,$_link,$_db;function
connect($Mi,$_f){return@new
MongoClient($Mi,$_f);}function
query($F){return
false;}function
select_db($j){try{$this->_db=$this->_link->selectDB($j);return
true;}catch(Exception$Cc){$this->error=$Cc->getMessage();return
false;}}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0,$_charset=array();function
__construct($G){foreach($G
as$de){$I=array();foreach($de
as$y=>$X){if(is_a($X,'MongoBinData'))$this->_charset[$y]=63;$I[$y]=(is_a($X,'MongoId')?'ObjectId("'.strval($X).'")':(is_a($X,'MongoDate')?gmdate("Y-m-d H:i:s",$X->sec)." GMT":(is_a($X,'MongoBinData')?$X->bin:(is_a($X,'MongoRegex')?strval($X):(is_object($X)?get_class($X):$X)))));}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=count($this->_rows);}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$je=array_keys($this->_rows[0]);$B=$je[$this->_offset++];return(object)array('name'=>$B,'charsetnr'=>$this->_charset[$B],);}}class
Min_Driver
extends
Min_SQL{public$mg="_id";function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){$K=($K==array("*")?array():array_fill_keys($K,true));$xh=array();foreach($Bf
as$X){$X=preg_replace('~ DESC$~','',$X,1,$Fb);$xh[$X]=($Fb?-1:1);}return
new
Min_Result($this->_conn->_db->selectCollection($Q)->find(array(),$K)->sort($xh)->limit($z!=""?+$z:0)->skip($D*$z));}function
insert($Q,$N){try{$H=$this->_conn->_db->selectCollection($Q)->insert($N);$this->_conn->errno=$H['code'];$this->_conn->error=$H['err'];$this->_conn->last_id=$N['_id'];return!$H['err'];}catch(Exception$Cc){$this->_conn->error=$Cc->getMessage();return
false;}}}function
get_databases($cd){global$g;$H=array();$Sb=$g->_link->listDBs();foreach($Sb['databases']as$l)$H[]=$l['name'];return$H;}function
count_tables($k){global$g;$H=array();foreach($k
as$l)$H[$l]=count($g->_link->selectDB($l)->getCollectionNames(true));return$H;}function
tables_list(){global$g;return
array_fill_keys($g->_db->getCollectionNames(true),'table');}function
drop_databases($k){global$g;foreach($k
as$l){$Og=$g->_link->selectDB($l)->drop();if(!$Og['ok'])return
false;}return
true;}function
indexes($Q,$h=null){global$g;$H=array();foreach($g->_db->selectCollection($Q)->getIndexInfo()as$v){$ac=array();foreach($v["key"]as$e=>$T)$ac[]=($T==-1?'1':null);$H[$v["name"]]=array("type"=>($v["name"]=="_id_"?"PRIMARY":($v["unique"]?"UNIQUE":"INDEX")),"columns"=>array_keys($v["key"]),"lengths"=>array(),"descs"=>$ac,);}return$H;}function
fields($Q){return
fields_from_edit();}function
found_rows($R,$Z){global$g;return$g->_db->selectCollection($_GET["select"])->count($Z);}$xf=array("=");}elseif(class_exists('MongoDB\Driver\Manager')){class
Min_DB{var$extension="MongoDB",$server_info=MONGODB_VERSION,$error,$last_id;var$_link;var$_db,$_db_name;function
connect($Mi,$_f){$kb='MongoDB\Driver\Manager';return
new$kb($Mi,$_f);}function
query($F){return
false;}function
select_db($j){$this->_db_name=$j;return
true;}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0,$_charset=array();function
__construct($G){foreach($G
as$de){$I=array();foreach($de
as$y=>$X){if(is_a($X,'MongoDB\BSON\Binary'))$this->_charset[$y]=63;$I[$y]=(is_a($X,'MongoDB\BSON\ObjectID')?'MongoDB\BSON\ObjectID("'.strval($X).'")':(is_a($X,'MongoDB\BSON\UTCDatetime')?$X->toDateTime()->format('Y-m-d H:i:s'):(is_a($X,'MongoDB\BSON\Binary')?$X->bin:(is_a($X,'MongoDB\BSON\Regex')?strval($X):(is_object($X)?json_encode($X,256):$X)))));}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=$G->count;}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$je=array_keys($this->_rows[0]);$B=$je[$this->_offset++];return(object)array('name'=>$B,'charsetnr'=>$this->_charset[$B],);}}class
Min_Driver
extends
Min_SQL{public$mg="_id";function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$g;$K=($K==array("*")?array():array_fill_keys($K,1));if(count($K)&&!isset($K['_id']))$K['_id']=0;$Z=where_to_query($Z);$xh=array();foreach($Bf
as$X){$X=preg_replace('~ DESC$~','',$X,1,$Fb);$xh[$X]=($Fb?-1:1);}if(isset($_GET['limit'])&&is_numeric($_GET['limit'])&&$_GET['limit']>0)$z=$_GET['limit'];$z=min(200,max(1,(int)$z));$uh=$D*$z;$kb='MongoDB\Driver\Query';$F=new$kb($Z,array('projection'=>$K,'limit'=>$z,'skip'=>$uh,'sort'=>$xh));$Rg=$g->_link->executeQuery("$g->_db_name.$Q",$F);return
new
Min_Result($Rg);}function
update($Q,$N,$yg,$z=0,$L="\n"){global$g;$l=$g->_db_name;$Z=sql_query_where_parser($yg);$kb='MongoDB\Driver\BulkWrite';$Za=new$kb(array());if(isset($N['_id']))unset($N['_id']);$Lg=array();foreach($N
as$y=>$Y){if($Y=='NULL'){$Lg[$y]=1;unset($N[$y]);}}$Li=array('$set'=>$N);if(count($Lg))$Li['$unset']=$Lg;$Za->update($Z,$Li,array('upsert'=>false));$Rg=$g->_link->executeBulkWrite("$l.$Q",$Za);$g->affected_rows=$Rg->getModifiedCount();return
true;}function
delete($Q,$yg,$z=0){global$g;$l=$g->_db_name;$Z=sql_query_where_parser($yg);$kb='MongoDB\Driver\BulkWrite';$Za=new$kb(array());$Za->delete($Z,array('limit'=>$z));$Rg=$g->_link->executeBulkWrite("$l.$Q",$Za);$g->affected_rows=$Rg->getDeletedCount();return
true;}function
insert($Q,$N){global$g;$l=$g->_db_name;$kb='MongoDB\Driver\BulkWrite';$Za=new$kb(array());if(isset($N['_id'])&&empty($N['_id']))unset($N['_id']);$Za->insert($N);$Rg=$g->_link->executeBulkWrite("$l.$Q",$Za);$g->affected_rows=$Rg->getInsertedCount();return
true;}}function
get_databases($cd){global$g;$H=array();$kb='MongoDB\Driver\Command';$sb=new$kb(array('listDatabases'=>1));$Rg=$g->_link->executeCommand('admin',$sb);foreach($Rg
as$Sb){foreach($Sb->databases
as$l)$H[]=$l->name;}return$H;}function
count_tables($k){$H=array();return$H;}function
tables_list(){global$g;$kb='MongoDB\Driver\Command';$sb=new$kb(array('listCollections'=>1));$Rg=$g->_link->executeCommand($g->_db_name,$sb);$qb=array();foreach($Rg
as$G)$qb[$G->name]='table';return$qb;}function
drop_databases($k){return
false;}function
indexes($Q,$h=null){global$g;$H=array();$kb='MongoDB\Driver\Command';$sb=new$kb(array('listIndexes'=>$Q));$Rg=$g->_link->executeCommand($g->_db_name,$sb);foreach($Rg
as$v){$ac=array();$f=array();foreach(get_object_vars($v->key)as$e=>$T){$ac[]=($T==-1?'1':null);$f[]=$e;}$H[$v->name]=array("type"=>($v->name=="_id_"?"PRIMARY":(isset($v->unique)?"UNIQUE":"INDEX")),"columns"=>$f,"lengths"=>array(),"descs"=>$ac,);}return$H;}function
fields($Q){$p=fields_from_edit();if(!count($p)){global$m;$G=$m->select($Q,array("*"),null,null,array(),10);while($I=$G->fetch_assoc()){foreach($I
as$y=>$X){$I[$y]=null;$p[$y]=array("field"=>$y,"type"=>"string","null"=>($y!=$m->primary),"auto_increment"=>($y==$m->primary),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,),);}}}return$p;}function
found_rows($R,$Z){global$g;$Z=where_to_query($Z);$kb='MongoDB\Driver\Command';$sb=new$kb(array('count'=>$R['Name'],'query'=>$Z));$Rg=$g->_link->executeCommand($g->_db_name,$sb);$pi=$Rg->toArray();return$pi[0]->n;}function
sql_query_where_parser($yg){$yg=trim(preg_replace('/WHERE[\s]?[(]?\(?/','',$yg));$yg=preg_replace('/\)\)\)$/',')',$yg);$mj=explode(' AND ',$yg);$nj=explode(') OR (',$yg);$Z=array();foreach($mj
as$kj)$Z[]=trim($kj);if(count($nj)==1)$nj=array();elseif(count($nj)>1)$Z=array();return
where_to_query($Z,$nj);}function
where_to_query($ij=array(),$jj=array()){global$b;$Nb=array();foreach(array('and'=>$ij,'or'=>$jj)as$T=>$Z){if(is_array($Z)){foreach($Z
as$Jc){list($nb,$vf,$X)=explode(" ",$Jc,3);if($nb=="_id"){$X=str_replace('MongoDB\BSON\ObjectID("',"",$X);$X=str_replace('")',"",$X);$kb='MongoDB\BSON\ObjectID';$X=new$kb($X);}if(!in_array($vf,$b->operators))continue;if(preg_match('~^\(f\)(.+)~',$vf,$A)){$X=(float)$X;$vf=$A[1];}elseif(preg_match('~^\(date\)(.+)~',$vf,$A)){$Pb=new
DateTime($X);$kb='MongoDB\BSON\UTCDatetime';$X=new$kb($Pb->getTimestamp()*1000);$vf=$A[1];}switch($vf){case'=':$vf='$eq';break;case'!=':$vf='$ne';break;case'>':$vf='$gt';break;case'<':$vf='$lt';break;case'>=':$vf='$gte';break;case'<=':$vf='$lte';break;case'regex':$vf='$regex';break;default:continue
2;}if($T=='and')$Nb['$and'][]=array($nb=>array($vf=>$X));elseif($T=='or')$Nb['$or'][]=array($nb=>array($vf=>$X));}}}return$Nb;}$xf=array("=","!=",">","<",">=","<=","regex","(f)=","(f)!=","(f)>","(f)<","(f)>=","(f)<=","(date)=","(date)!=","(date)>","(date)<","(date)>=","(date)<=",);}function
table($u){return$u;}function
idf_escape($u){return$u;}function
table_status($B="",$Qc=false){$H=array();foreach(tables_list()as$Q=>$T){$H[$Q]=array("Name"=>$Q);if($B==$Q)return$H[$Q];}return$H;}function
create_database($l,$d){return
true;}function
last_id(){global$g;return$g->last_id;}function
error(){global$g;return
h($g->error);}function
collations(){return
array();}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
connect(){global$b;$g=new
Min_DB;list($M,$V,$E)=$b->credentials();$_f=array();if($V.$E!=""){$_f["username"]=$V;$_f["password"]=$E;}$l=$b->database();if($l!="")$_f["db"]=$l;if(($La=getenv("MONGO_AUTH_SOURCE")))$_f["authSource"]=$La;try{$g->_link=$g->connect("mongodb://$M",$_f);if($E!=""){$_f["password"]="";try{$g->connect("mongodb://$M",$_f);return'Database does not support password.';}catch(Exception$Cc){}}return$g;}catch(Exception$Cc){return$Cc->getMessage();}}function
alter_indexes($Q,$c){global$g;foreach($c
as$X){list($T,$B,$N)=$X;if($N=="DROP")$H=$g->_db->command(array("deleteIndexes"=>$Q,"index"=>$B));else{$f=array();foreach($N
as$e){$e=preg_replace('~ DESC$~','',$e,1,$Fb);$f[$e]=($Fb?-1:1);}$H=$g->_db->selectCollection($Q)->ensureIndex($f,array("unique"=>($T=="UNIQUE"),"name"=>$B,));}if($H['errmsg']){$g->error=$H['errmsg'];return
false;}}return
true;}function
support($Rc){return
preg_match("~database|indexes|descidx~",$Rc);}function
db_collation($l,$pb){}function
information_schema(){}function
is_view($R){}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
foreign_keys($Q){return
array();}function
fk_support($R){}function
engines(){return
array();}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){global$g;if($Q==""){$g->_db->createCollection($B);return
true;}}function
drop_tables($S){global$g;foreach($S
as$Q){$Og=$g->_db->selectCollection($Q)->drop();if(!$Og['ok'])return
false;}return
true;}function
truncate_tables($S){global$g;foreach($S
as$Q){$Og=$g->_db->selectCollection($Q)->remove();if(!$Og['ok'])return
false;}return
true;}$x="mongo";$md=array();$sd=array();$oc=array(array("json"));}$gc["elastic"]="Elasticsearch (beta)";if(isset($_GET["elastic"])){$jg=array("json + allow_url_fopen");define("DRIVER","elastic");if(function_exists('json_decode')&&ini_bool('allow_url_fopen')){class
Min_DB{var$extension="JSON",$server_info,$errno,$error,$_url;function
rootQuery($ag,$Ab=array(),$Te='GET'){@ini_set('track_errors',1);$Vc=@file_get_contents("$this->_url/".ltrim($ag,'/'),false,stream_context_create(array('http'=>array('method'=>$Te,'content'=>$Ab===null?$Ab:json_encode($Ab),'header'=>'Content-Type: application/json','ignore_errors'=>1,))));if(!$Vc){$this->error=$php_errormsg;return$Vc;}if(!preg_match('~^HTTP/[0-9.]+ 2~i',$http_response_header[0])){$this->error=$Vc;return
false;}$H=json_decode($Vc,true);if($H===null){$this->errno=json_last_error();if(function_exists('json_last_error_msg'))$this->error=json_last_error_msg();else{$_b=get_defined_constants(true);foreach($_b['json']as$B=>$Y){if($Y==$this->errno&&preg_match('~^JSON_ERROR_~',$B)){$this->error=$B;break;}}}}return$H;}function
query($ag,$Ab=array(),$Te='GET'){return$this->rootQuery(($this->_db!=""?"$this->_db/":"/").ltrim($ag,'/'),$Ab,$Te);}function
connect($M,$V,$E){preg_match('~^(https?://)?(.*)~',$M,$A);$this->_url=($A[1]?$A[1]:"http://")."$V:$E@$A[2]";$H=$this->query('');if($H)$this->server_info=$H['version']['number'];return(bool)$H;}function
select_db($j){$this->_db=$j;return
true;}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows;function
__construct($J){$this->num_rows=count($J);$this->_rows=$J;reset($this->_rows);}function
fetch_assoc(){$H=current($this->_rows);next($this->_rows);return$H;}function
fetch_row(){return
array_values($this->fetch_assoc());}}}class
Min_Driver
extends
Min_SQL{function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$b;$Nb=array();$F="$Q/_search";if($K!=array("*"))$Nb["fields"]=$K;if($Bf){$xh=array();foreach($Bf
as$nb){$nb=preg_replace('~ DESC$~','',$nb,1,$Fb);$xh[]=($Fb?array($nb=>"desc"):$nb);}$Nb["sort"]=$xh;}if($z){$Nb["size"]=+$z;if($D)$Nb["from"]=($D*$z);}foreach($Z
as$X){list($nb,$vf,$X)=explode(" ",$X,3);if($nb=="_id")$Nb["query"]["ids"]["values"][]=$X;elseif($nb.$X!=""){$ci=array("term"=>array(($nb!=""?$nb:"_all")=>$X));if($vf=="=")$Nb["query"]["filtered"]["filter"]["and"][]=$ci;else$Nb["query"]["filtered"]["query"]["bool"]["must"][]=$ci;}}if($Nb["query"]&&!$Nb["query"]["filtered"]["query"]&&!$Nb["query"]["ids"])$Nb["query"]["filtered"]["query"]=array("match_all"=>array());$Fh=microtime(true);$eh=$this->_conn->query($F,$Nb);if($og)echo$b->selectQuery("$F: ".json_encode($Nb),$Fh,!$eh);if(!$eh)return
false;$H=array();foreach($eh['hits']['hits']as$Bd){$I=array();if($K==array("*"))$I["_id"]=$Bd["_id"];$p=$Bd['_source'];if($K!=array("*")){$p=array();foreach($K
as$y)$p[$y]=$Bd['fields'][$y];}foreach($p
as$y=>$X){if($Nb["fields"])$X=$X[0];$I[$y]=(is_array($X)?json_encode($X):$X);}$H[]=$I;}return
new
Min_Result($H);}function
update($T,$Cg,$yg,$z=0,$L="\n"){$Yf=preg_split('~ *= *~',$yg);if(count($Yf)==2){$t=trim($Yf[1]);$F="$T/$t";return$this->_conn->query($F,$Cg,'POST');}return
false;}function
insert($T,$Cg){$t="";$F="$T/$t";$Og=$this->_conn->query($F,$Cg,'POST');$this->_conn->last_id=$Og['_id'];return$Og['created'];}function
delete($T,$yg,$z=0){$Gd=array();if(is_array($_GET["where"])&&$_GET["where"]["_id"])$Gd[]=$_GET["where"]["_id"];if(is_array($_POST['check'])){foreach($_POST['check']as$db){$Yf=preg_split('~ *= *~',$db);if(count($Yf)==2)$Gd[]=trim($Yf[1]);}}$this->_conn->affected_rows=0;foreach($Gd
as$t){$F="{$T}/{$t}";$Og=$this->_conn->query($F,'{}','DELETE');if(is_array($Og)&&$Og['found']==true)$this->_conn->affected_rows++;}return$this->_conn->affected_rows;}}function
connect(){global$b;$g=new
Min_DB;list($M,$V,$E)=$b->credentials();if($E!=""&&$g->connect($M,$V,""))return'Database does not support password.';if($g->connect($M,$V,$E))return$g;return$g->error;}function
support($Rc){return
preg_match("~database|table|columns~",$Rc);}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
get_databases(){global$g;$H=$g->rootQuery('_aliases');if($H){$H=array_keys($H);sort($H,SORT_STRING);}return$H;}function
collations(){return
array();}function
db_collation($l,$pb){}function
engines(){return
array();}function
count_tables($k){global$g;$H=array();$G=$g->query('_stats');if($G&&$G['indices']){$Od=$G['indices'];foreach($Od
as$Nd=>$Gh){$Md=$Gh['total']['indexing'];$H[$Nd]=$Md['index_total'];}}return$H;}function
tables_list(){global$g;$H=$g->query('_mapping');if($H)$H=array_fill_keys(array_keys($H[$g->_db]["mappings"]),'table');return$H;}function
table_status($B="",$Qc=false){global$g;$eh=$g->query("_search",array("size"=>0,"aggregations"=>array("count_by_type"=>array("terms"=>array("field"=>"_type")))),"POST");$H=array();if($eh){$S=$eh["aggregations"]["count_by_type"]["buckets"];foreach($S
as$Q){$H[$Q["key"]]=array("Name"=>$Q["key"],"Engine"=>"table","Rows"=>$Q["doc_count"],);if($B!=""&&$B==$Q["key"])return$H[$B];}}return$H;}function
error(){global$g;return
h($g->error);}function
information_schema(){}function
is_view($R){}function
indexes($Q,$h=null){return
array(array("type"=>"PRIMARY","columns"=>array("_id")),);}function
fields($Q){global$g;$G=$g->query("$Q/_mapping");$H=array();if($G){$Be=$G[$Q]['properties'];if(!$Be)$Be=$G[$g->_db]['mappings'][$Q]['properties'];if($Be){foreach($Be
as$B=>$o){$H[$B]=array("field"=>$B,"full_type"=>$o["type"],"type"=>$o["type"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),);if($o["properties"]){unset($H[$B]["privileges"]["insert"]);unset($H[$B]["privileges"]["update"]);}}}}return$H;}function
foreign_keys($Q){return
array();}function
table($u){return$u;}function
idf_escape($u){return$u;}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
fk_support($R){}function
found_rows($R,$Z){return
null;}function
create_database($l){global$g;return$g->rootQuery(urlencode($l),null,'PUT');}function
drop_databases($k){global$g;return$g->rootQuery(urlencode(implode(',',$k)),array(),'DELETE');}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){global$g;$ug=array();foreach($p
as$Oc){$Tc=trim($Oc[1][0]);$Uc=trim($Oc[1][1]?$Oc[1][1]:"text");$ug[$Tc]=array('type'=>$Uc);}if(!empty($ug))$ug=array('properties'=>$ug);return$g->query("_mapping/{$B}",$ug,'PUT');}function
drop_tables($S){global$g;$H=true;foreach($S
as$Q)$H=$H&&$g->query(urlencode($Q),array(),'DELETE');return$H;}function
last_id(){global$g;return$g->last_id;}$x="elastic";$xf=array("=","query");$md=array();$sd=array();$oc=array(array("json"));$U=array();$Jh=array();foreach(array('Numbers'=>array("long"=>3,"integer"=>5,"short"=>8,"byte"=>10,"double"=>20,"float"=>66,"half_float"=>12,"scaled_float"=>21),'Date and time'=>array("date"=>10),'Strings'=>array("string"=>65535,"text"=>65535),'Binary'=>array("binary"=>255),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}}$gc["clickhouse"]="ClickHouse (alpha)";if(isset($_GET["clickhouse"])){define("DRIVER","clickhouse");class
Min_DB{var$extension="JSON",$server_info,$errno,$_result,$error,$_url;var$_db='default';function
rootQuery($l,$F){@ini_set('track_errors',1);$Vc=@file_get_contents("$this->_url/?database=$l",false,stream_context_create(array('http'=>array('method'=>'POST','content'=>$this->isQuerySelectLike($F)?"$F FORMAT JSONCompact":$F,'header'=>'Content-type: application/x-www-form-urlencoded','ignore_errors'=>1,))));if($Vc===false){$this->error=$php_errormsg;return$Vc;}if(!preg_match('~^HTTP/[0-9.]+ 2~i',$http_response_header[0])){$this->error=$Vc;return
false;}$H=json_decode($Vc,true);if($H===null){if(!$this->isQuerySelectLike($F)&&$Vc==='')return
true;$this->errno=json_last_error();if(function_exists('json_last_error_msg'))$this->error=json_last_error_msg();else{$_b=get_defined_constants(true);foreach($_b['json']as$B=>$Y){if($Y==$this->errno&&preg_match('~^JSON_ERROR_~',$B)){$this->error=$B;break;}}}}return
new
Min_Result($H);}function
isQuerySelectLike($F){return(bool)preg_match('~^(select|show)~i',$F);}function
query($F){return$this->rootQuery($this->_db,$F);}function
connect($M,$V,$E){preg_match('~^(https?://)?(.*)~',$M,$A);$this->_url=($A[1]?$A[1]:"http://")."$V:$E@$A[2]";$H=$this->query('SELECT 1');return(bool)$H;}function
select_db($j){$this->_db=$j;return
true;}function
quote($P){return"'".addcslashes($P,"\\'")."'";}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);return$G['data'];}}class
Min_Result{var$num_rows,$_rows,$columns,$meta,$_offset=0;function
__construct($G){$this->num_rows=$G['rows'];$this->_rows=$G['data'];$this->meta=$G['meta'];$this->columns=array_column($this->meta,'name');reset($this->_rows);}function
fetch_assoc(){$I=current($this->_rows);next($this->_rows);return$I===false?false:array_combine($this->columns,$I);}function
fetch_row(){$I=current($this->_rows);next($this->_rows);return$I;}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;if($e<count($this->columns)){$H->name=$this->meta[$e]['name'];$H->orgname=$H->name;$H->type=$this->meta[$e]['type'];}return$H;}}class
Min_Driver
extends
Min_SQL{function
delete($Q,$yg,$z=0){if($yg==='')$yg='WHERE 1=1';return
queries("ALTER TABLE ".table($Q)." DELETE $yg");}function
update($Q,$N,$yg,$z=0,$L="\n"){$Xi=array();foreach($N
as$y=>$X)$Xi[]="$y = $X";$F=$L.implode(",$L",$Xi);return
queries("ALTER TABLE ".table($Q)." UPDATE $F$yg");}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
explain($g,$F){return'';}function
found_rows($R,$Z){$J=get_vals("SELECT COUNT(*) FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):""));return
empty($J)?false:$J[0];}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=$Bf=array();foreach($p
as$o){if($o[1][2]===" NULL")$o[1][1]=" Nullable({$o[1][1]})";elseif($o[1][2]===' NOT NULL')$o[1][2]='';if($o[1][3])$o[1][3]='';$c[]=($o[1]?($Q!=""?($o[0]!=""?"MODIFY COLUMN ":"ADD COLUMN "):" ").implode($o[1]):"DROP COLUMN ".idf_escape($o[0]));$Bf[]=$o[1][0];}$c=array_merge($c,$ed);$O=($wc?" ENGINE ".$wc:"");if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)$O$Wf".' ORDER BY ('.implode(',',$Bf).')');if($Q!=$B){$G=queries("RENAME TABLE ".table($Q)." TO ".table($B));if($c)$Q=$B;else
return$G;}if($O)$c[]=ltrim($O);return($c||$Wf?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$c).$Wf):true);}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
drop_tables($cj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases($cd){global$g;$G=get_rows('SHOW DATABASES');$H=array();foreach($G
as$I)$H[]=$I['name'];sort($H);return$H;}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?", $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){}function
engines(){return
array('MergeTree');}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
tables_list(){$G=get_rows('SHOW TABLES');$H=array();foreach($G
as$I)$H[$I['name']]='table';ksort($H);return$H;}function
count_tables($k){return
array();}function
table_status($B="",$Qc=false){global$g;$H=array();$S=get_rows("SELECT name, engine FROM system.tables WHERE database = ".q($g->_db));foreach($S
as$Q){$H[$Q['name']]=array('Name'=>$Q['name'],'Engine'=>$Q['engine'],);if($B===$Q['name'])return$H[$Q['name']];}return$H;}function
is_view($R){return
false;}function
fk_support($R){return
false;}function
convert_field($o){}function
unconvert_field($o,$H){if(in_array($o['type'],array("Int8","Int16","Int32","Int64","UInt8","UInt16","UInt32","UInt64","Float32","Float64")))return"to$o[type]($H)";return$H;}function
fields($Q){$H=array();$G=get_rows("SELECT name, type, default_expression FROM system.columns WHERE ".idf_escape('table')." = ".q($Q));foreach($G
as$I){$T=trim($I['type']);$hf=strpos($T,'Nullable(')===0;$H[trim($I['name'])]=array("field"=>trim($I['name']),"full_type"=>$T,"type"=>$T,"default"=>trim($I['default_expression']),"null"=>$hf,"auto_increment"=>'0',"privileges"=>array("insert"=>1,"select"=>1,"update"=>0),);}return$H;}function
indexes($Q,$h=null){return
array();}function
foreign_keys($Q){return
array();}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ch){return
true;}function
auto_increment(){return'';}function
last_id(){return
0;}function
support($Rc){return
preg_match("~^(columns|sql|status|table|drop_col)$~",$Rc);}$x="clickhouse";$U=array();$Jh=array();foreach(array('Numbers'=>array("Int8"=>3,"Int16"=>5,"Int32"=>10,"Int64"=>19,"UInt8"=>3,"UInt16"=>5,"UInt32"=>10,"UInt64"=>20,"Float32"=>7,"Float64"=>16,'Decimal'=>38,'Decimal32'=>9,'Decimal64'=>18,'Decimal128'=>38),'Date and time'=>array("Date"=>13,"DateTime"=>20),'Strings'=>array("String"=>0),'Binary'=>array("FixedString"=>0),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");$md=array();$sd=array("avg","count","count distinct","max","min","sum");$oc=array();}$gc=array("server"=>"MySQL")+$gc;if(!defined("DRIVER")){$jg=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
__construct(){parent::init();}function
connect($M="",$V="",$E="",$j=null,$fg=null,$wh=null){global$b;mysqli_report(MYSQLI_REPORT_OFF);list($Cd,$fg)=explode(":",$M,2);$Eh=$b->connectSsl();if($Eh)$this->ssl_set($Eh['key'],$Eh['cert'],$Eh['ca'],'','');$H=@$this->real_connect(($M!=""?$Cd:ini_get("mysqli.default_host")),($M.$V!=""?$V:ini_get("mysqli.default_user")),($M.$V.$E!=""?$E:ini_get("mysqli.default_pw")),$j,(is_numeric($fg)?$fg:ini_get("mysqli.default_port")),(!is_numeric($fg)?$fg:$wh),($Eh?64:0));$this->options(MYSQLI_OPT_LOCAL_INFILE,false);return$H;}function
set_charset($cb){if(parent::set_charset($cb))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $cb");}function
result($F,$o=0){$G=$this->query($F);if(!$G)return
false;$I=$G->fetch_array();return$I[$o];}function
quote($P){return"'".$this->escape_string($P)."'";}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($M,$V,$E){if(ini_bool("mysql.allow_local_infile")){$this->error=sprintf('Disable %s or enable %s or %s extensions.',"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");return
false;}$this->_link=@mysql_connect(($M!=""?$M:ini_get("mysql.default_host")),("$M$V"!=""?$V:ini_get("mysql.default_user")),("$M$V$E"!=""?$E:ini_get("mysql.default_password")),true,131072);if($this->_link)$this->server_info=mysql_get_server_info($this->_link);else$this->error=mysql_error();return(bool)$this->_link;}function
set_charset($cb){if(function_exists('mysql_set_charset')){if(mysql_set_charset($cb,$this->_link))return
true;mysql_set_charset('utf8',$this->_link);}return$this->query("SET NAMES $cb");}function
quote($P){return"'".mysql_real_escape_string($P,$this->_link)."'";}function
select_db($j){return
mysql_select_db($j,$this->_link);}function
query($F,$Ei=false){$G=@($Ei?mysql_unbuffered_query($F,$this->_link):mysql_query($F,$this->_link));$this->error="";if(!$G){$this->errno=mysql_errno($this->_link);$this->error=mysql_error($this->_link);return
false;}if($G===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;return
mysql_result($G->_result,0,$o);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($G){$this->_result=$G;$this->num_rows=mysql_num_rows($G);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$H=mysql_fetch_field($this->_result,$this->_offset++);$H->orgtable=$H->table;$H->orgname=$H->name;$H->charsetnr=($H->blob?63:0);return$H;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($M,$V,$E){global$b;$_f=array(PDO::MYSQL_ATTR_LOCAL_INFILE=>false);$Eh=$b->connectSsl();if($Eh){if(!empty($Eh['key']))$_f[PDO::MYSQL_ATTR_SSL_KEY]=$Eh['key'];if(!empty($Eh['cert']))$_f[PDO::MYSQL_ATTR_SSL_CERT]=$Eh['cert'];if(!empty($Eh['ca']))$_f[PDO::MYSQL_ATTR_SSL_CA]=$Eh['ca'];}$this->dsn("mysql:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E,$_f);return
true;}function
set_charset($cb){$this->query("SET NAMES $cb");}function
select_db($j){return$this->query("USE ".idf_escape($j));}function
query($F,$Ei=false){$this->setAttribute(1000,!$Ei);return
parent::query($F,$Ei);}}}class
Min_Driver
extends
Min_SQL{function
insert($Q,$N){return($N?parent::insert($Q,$N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));}function
insertUpdate($Q,$J,$mg){$f=array_keys(reset($J));$kg="INSERT INTO ".table($Q)." (".implode(", ",$f).") VALUES\n";$Xi=array();foreach($f
as$y)$Xi[$y]="$y = VALUES($y)";$Mh="\nON DUPLICATE KEY UPDATE ".implode(", ",$Xi);$Xi=array();$ve=0;foreach($J
as$N){$Y="(".implode(", ",$N).")";if($Xi&&(strlen($kg)+$ve+strlen($Y)+strlen($Mh)>1e6)){if(!queries($kg.implode(",\n",$Xi).$Mh))return
false;$Xi=array();$ve=0;}$Xi[]=$Y;$ve+=strlen($Y)+2;}return
queries($kg.implode(",\n",$Xi).$Mh);}function
slowQuery($F,$hi){if(min_version('5.7.8','10.1.2')){if(preg_match('~MariaDB~',$this->_conn->server_info))return"SET STATEMENT max_statement_time=$hi FOR $F";elseif(preg_match('~^(SELECT\b)(.+)~is',$F,$A))return"$A[1] /*+ MAX_EXECUTION_TIME(".($hi*1000).") */ $A[2]";}}function
convertSearch($u,$X,$o){return(preg_match('~char|text|enum|set~',$o["type"])&&!preg_match("~^utf8~",$o["collation"])&&preg_match('~[\x80-\xFF]~',$X['val'])?"CONVERT($u USING ".charset($this->_conn).")":$u);}function
warnings(){$G=$this->_conn->query("SHOW WARNINGS");if($G&&$G->num_rows){ob_start();select($G);return
ob_get_clean();}}function
tableHelp($B){$Ce=preg_match('~MariaDB~',$this->_conn->server_info);if(information_schema(DB))return
strtolower(($Ce?"information-schema-$B-table/":str_replace("_","-",$B)."-table.html"));if(DB=="mysql")return($Ce?"mysql$B-table/":"system-database.html");}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
connect(){global$b,$U,$Jh;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2])){$g->set_charset(charset($g));$g->query("SET sql_quote_show_create = 1, autocommit = 1");if(min_version('5.7.8',10.2,$g)){$Jh['Strings'][]="json";$U["json"]=4294967295;}return$g;}$H=$g->error;if(function_exists('iconv')&&!is_utf8($H)&&strlen($ah=iconv("windows-1250","utf-8",$H))>strlen($H))$H=$ah;return$H;}function
get_databases($cd){$H=get_session("dbs");if($H===null){$F=(min_version(5)?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME":"SHOW DATABASES");$H=($cd?slow_query($F):get_vals($F));restart_session();set_session("dbs",$H);stop_session();}return$H;}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){global$g;$H=null;$i=$g->result("SHOW CREATE DATABASE ".idf_escape($l),1);if(preg_match('~ COLLATE ([^ ]+)~',$i,$A))$H=$A[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$i,$A))$H=$pb[$A[1]][-1];return$H;}function
engines(){$H=array();foreach(get_rows("SHOW ENGINES")as$I){if(preg_match("~YES|DEFAULT~",$I["Support"]))$H[]=$I["Engine"];}return$H;}function
logged_user(){global$g;return$g->result("SELECT USER()");}function
tables_list(){return
get_key_vals(min_version(5)?"SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME":"SHOW TABLES");}function
count_tables($k){$H=array();foreach($k
as$l)$H[$l]=count(get_vals("SHOW TABLES IN ".idf_escape($l)));return$H;}function
table_status($B="",$Qc=false){$H=array();foreach(get_rows($Qc&&min_version(5)?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($B!=""?"AND TABLE_NAME = ".q($B):"ORDER BY Name"):"SHOW TABLE STATUS".($B!=""?" LIKE ".q(addcslashes($B,"%_\\")):""))as$I){if($I["Engine"]=="InnoDB")$I["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$I["Comment"]);if(!isset($I["Engine"]))$I["Comment"]="";if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]===null;}function
fk_support($R){return
preg_match('~InnoDB|IBMDB2I~i',$R["Engine"])||(preg_match('~NDB~i',$R["Engine"])&&min_version(5.6));}function
fields($Q){$H=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($Q))as$I){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$I["Type"],$A);$H[$I["Field"]]=array("field"=>$I["Field"],"full_type"=>$I["Type"],"type"=>$A[1],"length"=>$A[2],"unsigned"=>ltrim($A[3].$A[4]),"default"=>($I["Default"]!=""||preg_match("~char|set~",$A[1])?$I["Default"]:null),"null"=>($I["Null"]=="YES"),"auto_increment"=>($I["Extra"]=="auto_increment"),"on_update"=>(preg_match('~^on update (.+)~i',$I["Extra"],$A)?$A[1]:""),"collation"=>$I["Collation"],"privileges"=>array_flip(preg_split('~, *~',$I["Privileges"])),"comment"=>$I["Comment"],"primary"=>($I["Key"]=="PRI"),"generated"=>preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$I["Extra"]),);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SHOW INDEX FROM ".table($Q),$h)as$I){$B=$I["Key_name"];$H[$B]["type"]=($B=="PRIMARY"?"PRIMARY":($I["Index_type"]=="FULLTEXT"?"FULLTEXT":($I["Non_unique"]?($I["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$H[$B]["columns"][]=$I["Column_name"];$H[$B]["lengths"][]=($I["Index_type"]=="SPATIAL"?null:$I["Sub_part"]);$H[$B]["descs"][]=null;}return$H;}function
foreign_keys($Q){global$g,$sf;static$cg='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$H=array();$Gb=$g->result("SHOW CREATE TABLE ".table($Q),1);if($Gb){preg_match_all("~CONSTRAINT ($cg) FOREIGN KEY ?\\(((?:$cg,? ?)+)\\) REFERENCES ($cg)(?:\\.($cg))? \\(((?:$cg,? ?)+)\\)(?: ON DELETE ($sf))?(?: ON UPDATE ($sf))?~",$Gb,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){preg_match_all("~$cg~",$A[2],$yh);preg_match_all("~$cg~",$A[5],$Zh);$H[idf_unescape($A[1])]=array("db"=>idf_unescape($A[4]!=""?$A[3]:$A[4]),"table"=>idf_unescape($A[4]!=""?$A[4]:$A[3]),"source"=>array_map('idf_unescape',$yh[0]),"target"=>array_map('idf_unescape',$Zh[0]),"on_delete"=>($A[6]?$A[6]:"RESTRICT"),"on_update"=>($A[7]?$A[7]:"RESTRICT"),);}}return$H;}function
view($B){global$g;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',$g->result("SHOW CREATE VIEW ".table($B),1)));}function
collations(){$H=array();foreach(get_rows("SHOW COLLATION")as$I){if($I["Default"])$H[$I["Charset"]][-1]=$I["Collation"];else$H[$I["Charset"]][]=$I["Collation"];}ksort($H);foreach($H
as$y=>$X)asort($H[$y]);return$H;}function
information_schema($l){return(min_version(5)&&$l=="information_schema")||(min_version(5.5)&&$l=="performance_schema");}function
error(){global$g;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$g->error));}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).($d?" COLLATE ".q($d):""));}function
drop_databases($k){$H=apply_queries("DROP DATABASE",$k,'idf_escape');restart_session();set_session("dbs",null);return$H;}function
rename_database($B,$d){$H=false;if(create_database($B,$d)){$Mg=array();foreach(tables_list()as$Q=>$T)$Mg[]=table($Q)." TO ".idf_escape($B).".".table($Q);$H=(!$Mg||queries("RENAME TABLE ".implode(", ",$Mg)));if($H)queries("DROP DATABASE ".idf_escape(DB));restart_session();set_session("dbs",null);}return$H;}function
auto_increment(){$Na=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$v){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$v["columns"],true)){$Na="";break;}if($v["type"]=="PRIMARY")$Na=" UNIQUE";}}return" AUTO_INCREMENT$Na";}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=array();foreach($p
as$o)$c[]=($o[1]?($Q!=""?($o[0]!=""?"CHANGE ".idf_escape($o[0]):"ADD"):" ")." ".implode($o[1]).($Q!=""?$o[2]:""):"DROP ".idf_escape($o[0]));$c=array_merge($c,$ed);$O=($ub!==null?" COMMENT=".q($ub):"").($wc?" ENGINE=".q($wc):"").($d?" COLLATE ".q($d):"").($Ma!=""?" AUTO_INCREMENT=$Ma":"");if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)$O$Wf");if($Q!=$B)$c[]="RENAME TO ".table($B);if($O)$c[]=ltrim($O);return($c||$Wf?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$c).$Wf):true);}function
alter_indexes($Q,$c){foreach($c
as$y=>$X)$c[$y]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ",$X[2]).")");return
queries("ALTER TABLE ".table($Q).implode(",",$c));}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
queries("DROP VIEW ".implode(", ",array_map('table',$cj)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('table',$S)));}function
move_tables($S,$cj,$Zh){$Mg=array();foreach(array_merge($S,$cj)as$Q)$Mg[]=table($Q)." TO ".idf_escape($Zh).".".table($Q);return
queries("RENAME TABLE ".implode(", ",$Mg));}function
copy_tables($S,$cj,$Zh){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($S
as$Q){$B=($Zh==DB?table("copy_$Q"):idf_escape($Zh).".".table($Q));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $B"))||!queries("CREATE TABLE $B LIKE ".table($Q))||!queries("INSERT INTO $B SELECT * FROM ".table($Q)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){$zi=$I["Trigger"];if(!queries("CREATE TRIGGER ".($Zh==DB?idf_escape("copy_$zi"):idf_escape($Zh).".".idf_escape($zi))." $I[Timing] $I[Event] ON $B FOR EACH ROW\n$I[Statement];"))return
false;}}foreach($cj
as$Q){$B=($Zh==DB?table("copy_$Q"):idf_escape($Zh).".".table($Q));$bj=view($Q);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $B"))||!queries("CREATE VIEW $B AS $bj[select]"))return
false;}return
true;}function
trigger($B){if($B=="")return
array();$J=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($B));return
reset($J);}function
triggers($Q){$H=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I)$H[$I["Trigger"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($B,$T){global$g,$yc,$Td,$U;$Ca=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$zh="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Di="((".implode("|",array_merge(array_keys($U),$Ca)).")\\b(?:\\s*\\(((?:[^'\")]|$yc)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";$cg="$zh*(".($T=="FUNCTION"?"":$Td).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Di";$i=$g->result("SHOW CREATE $T ".idf_escape($B),2);preg_match("~\\(((?:$cg\\s*,?)*)\\)\\s*".($T=="FUNCTION"?"RETURNS\\s+$Di\\s+":"")."(.*)~is",$i,$A);$p=array();preg_match_all("~$cg\\s*,?~is",$A[1],$Fe,PREG_SET_ORDER);foreach($Fe
as$Pf)$p[]=array("field"=>str_replace("``","`",$Pf[2]).$Pf[3],"type"=>strtolower($Pf[5]),"length"=>preg_replace_callback("~$yc~s",'normalize_enum',$Pf[6]),"unsigned"=>strtolower(preg_replace('~\s+~',' ',trim("$Pf[8] $Pf[7]"))),"null"=>1,"full_type"=>$Pf[4],"inout"=>strtoupper($Pf[1]),"collation"=>strtolower($Pf[9]),);if($T!="FUNCTION")return
array("fields"=>$p,"definition"=>$A[11]);return
array("fields"=>$p,"returns"=>array("type"=>$A[12],"length"=>$A[13],"unsigned"=>$A[15],"collation"=>$A[16]),"definition"=>$A[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
routine_id($B,$I){return
idf_escape($B);}function
last_id(){global$g;return$g->result("SELECT LAST_INSERT_ID()");}function
explain($g,$F){return$g->query("EXPLAIN ".(min_version(5.1)?"PARTITIONS ":"").$F);}function
found_rows($R,$Z){return($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ch,$h=null){return
true;}function
create_sql($Q,$Ma,$Kh){global$g;$H=$g->result("SHOW CREATE TABLE ".table($Q),1);if(!$Ma)$H=preg_replace('~ AUTO_INCREMENT=\d+~','',$H);return$H;}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
use_sql($j){return"USE ".idf_escape($j);}function
trigger_sql($Q){$H="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")),null,"-- ")as$I)$H.="\nCREATE TRIGGER ".idf_escape($I["Trigger"])." $I[Timing] $I[Event] ON ".table($I["Table"])." FOR EACH ROW\n$I[Statement];;\n";return$H;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
convert_field($o){if(preg_match("~binary~",$o["type"]))return"HEX(".idf_escape($o["field"]).")";if($o["type"]=="bit")return"BIN(".idf_escape($o["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$o["type"]))return(min_version(8)?"ST_":"")."AsWKT(".idf_escape($o["field"]).")";}function
unconvert_field($o,$H){if(preg_match("~binary~",$o["type"]))$H="UNHEX($H)";if($o["type"]=="bit")$H="CONV($H, 2, 10) + 0";if(preg_match("~geometry|point|linestring|polygon~",$o["type"]))$H=(min_version(8)?"ST_":"")."GeomFromText($H, SRID($o[field]))";return$H;}function
support($Rc){return!preg_match("~scheme|sequence|type|view_trigger|materializedview".(min_version(8)?"":"|descidx".(min_version(5.1)?"":"|event|partitioning".(min_version(5)?"":"|routine|trigger|view")))."~",$Rc);}function
kill_process($X){return
queries("KILL ".number($X));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){global$g;return$g->result("SELECT @@max_connections");}$x="sql";$U=array();$Jh=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array("unsigned","zerofill","unsigned zerofill");$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");$md=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");$sd=array("avg","count","count distinct","group_concat","max","min","sum");$oc=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",str_replace(":","%3a",preg_replace('~\?.*~','',relative_uri())).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ia="4.7.7";class
Adminer{var$operators;function
name(){return"<a href='https://www.adminer.org/'".target_blank()." id='h1'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($i=false){return
password_file($i);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($M){return
h($M);}function
database(){return
DB;}function
databases($cd=true){return
get_databases($cd);}function
schemas(){return
schemas();}function
queryTimeout(){return
2;}function
headers(){}function
csp(){return
csp();}function
head(){return
true;}function
css(){$H=array();$Wc="adminer.css";if(file_exists($Wc))$H[]="$Wc?v=".crc32(file_get_contents($Wc));return$H;}function
loginForm(){global$gc;echo"<table cellspacing='0' class='layout'>\n",$this->loginFormField('driver','<tr><th>'.'System'.'<td>',html_select("auth[driver]",$gc,DRIVER,"loginDriver(this);")."\n"),$this->loginFormField('server','<tr><th>'.'Server'.'<td>','<input name="auth[server]" value="'.h(SERVER).'" title="hostname[:port]" placeholder="localhost" autocapitalize="off">'."\n"),$this->loginFormField('username','<tr><th>'.'Username'.'<td>','<input name="auth[username]" id="username" value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("focus(qs('#username')); qs('#username').form['auth[driver]'].onchange();")),$this->loginFormField('password','<tr><th>'.'Password'.'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'."\n"),$this->loginFormField('db','<tr><th>'.'Database'.'<td>','<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'."\n"),"</table>\n","<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
loginFormField($B,$zd,$Y){return$zd.$Y;}function
login($_e,$E){if($E=="")return
sprintf('Adminer does not support accessing a database without a password, <a href="https://www.adminer.org/en/password/"%s>more information</a>.',target_blank());return
true;}function
tableName($Qh){return
h($Qh["Name"]);}function
fieldName($o,$Bf=0){return'<span title="'.h($o["full_type"]).'">'.h($o["field"]).'</span>';}function
selectLinks($Qh,$N=""){global$x,$m;echo'<p class="links">';$ye=array("select"=>'Select data');if(support("table")||support("indexes"))$ye["table"]='Show structure';if(support("table")){if(is_view($Qh))$ye["view"]='Alter view';else$ye["create"]='Alter table';}if($N!==null)$ye["edit"]='New item';$B=$Qh["Name"];foreach($ye
as$y=>$X)echo" <a href='".h(ME)."$y=".urlencode($B).($y=="edit"?$N:"")."'".bold(isset($_GET[$y])).">$X</a>";echo
doc_link(array($x=>$m->tableHelp($B)),"?"),"\n";}function
foreignKeys($Q){return
foreign_keys($Q);}function
backwardKeys($Q,$Ph){return
array();}function
backwardKeysPrint($Pa,$I){}function
selectQuery($F,$Fh,$Pc=false){global$x,$m;$H="</p>\n";if(!$Pc&&($fj=$m->warnings())){$t="warnings";$H=", <a href='#$t'>".'Warnings'."</a>".script("qsl('a').onclick = partial(toggle, '$t');","")."$H<div id='$t' class='hidden'>\n$fj</div>\n";}return"<p><code class='jush-$x'>".h(str_replace("\n"," ",$F))."</code> <span class='time'>(".format_time($Fh).")</span>".(support("sql")?" <a href='".h(ME)."sql=".urlencode($F)."'>".'Edit'."</a>":"").$H;}function
sqlCommandQuery($F){return
shorten_utf8(trim($F),1000);}function
rowDescription($Q){return"";}function
rowDescriptions($J,$fd){return$J;}function
selectLink($X,$o){}function
selectVal($X,$_,$o,$Jf){$H=($X===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~",$o["type"])&&!preg_match("~var~",$o["type"])?"<code>$X</code>":$X));if(preg_match('~blob|bytea|raw|file~',$o["type"])&&!is_utf8($X))$H="<i>".lang(array('%d byte','%d bytes'),strlen($Jf))."</i>";if(preg_match('~json~',$o["type"]))$H="<code class='jush-js'>$H</code>";return($_?"<a href='".h($_)."'".(is_url($_)?target_blank():"").">$H</a>":$H);}function
editVal($X,$o){return$X;}function
tableStructurePrint($p){echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap'>\n","<thead><tr><th>".'Column'."<td>".'Type'.(support("comment")?"<td>".'Comment':"")."</thead>\n";foreach($p
as$o){echo"<tr".odd()."><th>".h($o["field"]),"<td><span title='".h($o["collation"])."'>".h($o["full_type"])."</span>",($o["null"]?" <i>NULL</i>":""),($o["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($o["default"])?" <span title='".'Default value'."'>[<b>".h($o["default"])."</b>]</span>":""),(support("comment")?"<td>".h($o["comment"]):""),"\n";}echo"</table>\n","</div>\n";}function
tableIndexesPrint($w){echo"<table cellspacing='0'>\n";foreach($w
as$B=>$v){ksort($v["columns"]);$og=array();foreach($v["columns"]as$y=>$X)$og[]="<i>".h($X)."</i>".($v["lengths"][$y]?"(".$v["lengths"][$y].")":"").($v["descs"][$y]?" DESC":"");echo"<tr title='".h($B)."'><th>$v[type]<td>".implode(", ",$og)."\n";}echo"</table>\n";}function
selectColumnsPrint($K,$f){global$md,$sd;print_fieldset("select",'Select',$K);$s=0;$K[""]=array();foreach($K
as$y=>$X){$X=$_GET["columns"][$y];$e=select_input(" name='columns[$s][col]'",$f,$X["col"],($y!==""?"selectFieldChange":"selectAddRow"));echo"<div>".($md||$sd?"<select name='columns[$s][fun]'>".optionlist(array(-1=>"")+array_filter(array('Functions'=>$md,'Aggregation'=>$sd)),$X["fun"])."</select>".on_help("getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'",1).script("qsl('select').onchange = function () { helpClose();".($y!==""?"":" qsl('select, input', this.parentNode).onchange();")." };","")."($e)":$e)."</div>\n";$s++;}echo"</div></fieldset>\n";}function
selectSearchPrint($Z,$f,$w){print_fieldset("search",'Search',$Z);foreach($w
as$s=>$v){if($v["type"]=="FULLTEXT"){echo"<div>(<i>".implode("</i>, <i>",array_map('h',$v["columns"]))."</i>) AGAINST"," <input type='search' name='fulltext[$s]' value='".h($_GET["fulltext"][$s])."'>",script("qsl('input').oninput = selectFieldChange;",""),checkbox("boolean[$s]",1,isset($_GET["boolean"][$s]),"BOOL"),"</div>\n";}}$bb="this.parentNode.firstChild.onchange();";foreach(array_merge((array)$_GET["where"],array(array()))as$s=>$X){if(!$X||("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators))){echo"<div>".select_input(" name='where[$s][col]'",$f,$X["col"],($X?"selectFieldChange":"selectAddRow"),"(".'anywhere'.")"),html_select("where[$s][op]",$this->operators,$X["op"],$bb),"<input type='search' name='where[$s][val]' value='".h($X["val"])."'>",script("mixin(qsl('input'), {oninput: function () { $bb }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});",""),"</div>\n";}}echo"</div></fieldset>\n";}function
selectOrderPrint($Bf,$f,$w){print_fieldset("sort",'Sort',$Bf);$s=0;foreach((array)$_GET["order"]as$y=>$X){if($X!=""){echo"<div>".select_input(" name='order[$s]'",$f,$X,"selectFieldChange"),checkbox("desc[$s]",1,isset($_GET["desc"][$y]),'descending')."</div>\n";$s++;}}echo"<div>".select_input(" name='order[$s]'",$f,"","selectAddRow"),checkbox("desc[$s]",1,false,'descending')."</div>\n","</div></fieldset>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".'Limit'."</legend><div>";echo"<input type='number' name='limit' class='size' value='".h($z)."'>",script("qsl('input').oninput = selectFieldChange;",""),"</div></fieldset>\n";}function
selectLengthPrint($fi){if($fi!==null){echo"<fieldset><legend>".'Text length'."</legend><div>","<input type='number' name='text_length' class='size' value='".h($fi)."'>","</div></fieldset>\n";}}function
selectActionPrint($w){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script".nonce().">\n","var indexColumns = ";$f=array();foreach($w
as$v){$Mb=reset($v["columns"]);if($v["type"]!="FULLTEXT"&&$Mb)$f[$Mb]=1;}$f[""]=1;foreach($f
as$y=>$X)json_row($y);echo";\n","selectFieldChange.call(qs('#form')['select']);\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint($tc,$f){}function
selectColumnsProcess($f,$w){global$md,$sd;$K=array();$pd=array();foreach((array)$_GET["columns"]as$y=>$X){if($X["fun"]=="count"||($X["col"]!=""&&(!$X["fun"]||in_array($X["fun"],$md)||in_array($X["fun"],$sd)))){$K[$y]=apply_sql_function($X["fun"],($X["col"]!=""?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],$sd))$pd[]=$K[$y];}}return
array($K,$pd);}function
selectSearchProcess($p,$w){global$g,$m;$H=array();foreach($w
as$s=>$v){if($v["type"]=="FULLTEXT"&&$_GET["fulltext"][$s]!="")$H[]="MATCH (".implode(", ",array_map('idf_escape',$v["columns"])).") AGAINST (".q($_GET["fulltext"][$s]).(isset($_GET["boolean"][$s])?" IN BOOLEAN MODE":"").")";}foreach((array)$_GET["where"]as$y=>$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){$kg="";$xb=" $X[op]";if(preg_match('~IN$~',$X["op"])){$Jd=process_length($X["val"]);$xb.=" ".($Jd!=""?$Jd:"(NULL)");}elseif($X["op"]=="SQL")$xb=" $X[val]";elseif($X["op"]=="LIKE %%")$xb=" LIKE ".$this->processInput($p[$X["col"]],"%$X[val]%");elseif($X["op"]=="ILIKE %%")$xb=" ILIKE ".$this->processInput($p[$X["col"]],"%$X[val]%");elseif($X["op"]=="FIND_IN_SET"){$kg="$X[op](".q($X["val"]).", ";$xb=")";}elseif(!preg_match('~NULL$~',$X["op"]))$xb.=" ".$this->processInput($p[$X["col"]],$X["val"]);if($X["col"]!="")$H[]=$kg.$m->convertSearch(idf_escape($X["col"]),$X,$p[$X["col"]]).$xb;else{$rb=array();foreach($p
as$B=>$o){if((preg_match('~^[-\d.'.(preg_match('~IN$~',$X["op"])?',':'').']+$~',$X["val"])||!preg_match('~'.number_type().'|bit~',$o["type"]))&&(!preg_match("~[\x80-\xFF]~",$X["val"])||preg_match('~char|text|enum|set~',$o["type"])))$rb[]=$kg.$m->convertSearch(idf_escape($B),$X,$o).$xb;}$H[]=($rb?"(".implode(" OR ",$rb).")":"1 = 0");}}}return$H;}function
selectOrderProcess($p,$w){$H=array();foreach((array)$_GET["order"]as$y=>$X){if($X!="")$H[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',$X)?$X:idf_escape($X)).(isset($_GET["desc"][$y])?" DESC":"");}return$H;}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"50");}function
selectLengthProcess(){return(isset($_GET["text_length"])?$_GET["text_length"]:"100");}function
selectEmailProcess($Z,$fd){return
false;}function
selectQueryBuild($K,$Z,$pd,$Bf,$z,$D){return"";}function
messageQuery($F,$gi,$Pc=false){global$x,$m;restart_session();$_d=&get_session("queries");if(!$_d[$_GET["db"]])$_d[$_GET["db"]]=array();if(strlen($F)>1e6)$F=preg_replace('~[\x80-\xFF]+$~','',substr($F,0,1e6))."\n‚Ä¶";$_d[$_GET["db"]][]=array($F,time(),$gi);$Ch="sql-".count($_d[$_GET["db"]]);$H="<a href='#$Ch' class='toggle'>".'SQL command'."</a>\n";if(!$Pc&&($fj=$m->warnings())){$t="warnings-".count($_d[$_GET["db"]]);$H="<a href='#$t' class='toggle'>".'Warnings'."</a>, $H<div id='$t' class='hidden'>\n$fj</div>\n";}return" <span class='time'>".@date("H:i:s")."</span>"." $H<div id='$Ch' class='hidden'><pre><code class='jush-$x'>".shorten_utf8($F,1000)."</code></pre>".($gi?" <span class='time'>($gi)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".urlencode(DB),"db=".urlencode($_GET["db"]),ME).'sql=&history='.(count($_d[$_GET["db"]])-1)).'">'.'Edit'.'</a>':'').'</div>';}function
editFunctions($o){global$oc;$H=($o["null"]?"NULL/":"");foreach($oc
as$y=>$md){if(!$y||(!isset($_GET["call"])&&(isset($_GET["select"])||where($_GET)))){foreach($md
as$cg=>$X){if(!$cg||preg_match("~$cg~",$o["type"]))$H.="/$X";}if($y&&!preg_match('~set|blob|bytea|raw|file~',$o["type"]))$H.="/SQL";}}if($o["auto_increment"]&&!isset($_GET["select"])&&!where($_GET))$H='Auto Increment';return
explode("/",$H);}function
editInput($Q,$o,$Ja,$Y){if($o["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$Ja value='-1' checked><i>".'original'."</i></label> ":"").($o["null"]?"<label><input type='radio'$Ja value=''".($Y!==null||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio",$Ja,$o,$Y,0);return"";}function
editHint($Q,$o,$Y){return"";}function
processInput($o,$Y,$r=""){if($r=="SQL")return$Y;$B=$o["field"];$H=q($Y);if(preg_match('~^(now|getdate|uuid)$~',$r))$H="$r()";elseif(preg_match('~^current_(date|timestamp)$~',$r))$H=$r;elseif(preg_match('~^([+-]|\|\|)$~',$r))$H=idf_escape($B)." $r $H";elseif(preg_match('~^[+-] interval$~',$r))$H=idf_escape($B)." $r ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i",$Y)?$Y:$H);elseif(preg_match('~^(addtime|subtime|concat)$~',$r))$H="$r(".idf_escape($B).", $H)";elseif(preg_match('~^(md5|sha1|password|encrypt)$~',$r))$H="$r($H)";return
unconvert_field($o,$H);}function
dumpOutput(){$H=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$H['gz']='gzip';return$H;}function
dumpFormat(){return
array('sql'=>'SQL','csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($l){}function
dumpTable($Q,$Kh,$ce=0){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($Kh)dump_csv(array_keys(fields($Q)));}else{if($ce==2){$p=array();foreach(fields($Q)as$B=>$o)$p[]=idf_escape($B)." $o[full_type]";$i="CREATE TABLE ".table($Q)." (".implode(", ",$p).")";}else$i=create_sql($Q,$_POST["auto_increment"],$Kh);set_utf8mb4($i);if($Kh&&$i){if($Kh=="DROP+CREATE"||$ce==1)echo"DROP ".($ce==2?"VIEW":"TABLE")." IF EXISTS ".table($Q).";\n";if($ce==1)$i=remove_definer($i);echo"$i;\n\n";}}}function
dumpData($Q,$Kh,$F){global$g,$x;$He=($x=="sqlite"?0:1048576);if($Kh){if($_POST["format"]=="sql"){if($Kh=="TRUNCATE+INSERT")echo
truncate_sql($Q).";\n";$p=fields($Q);}$G=$g->query($F,1);if($G){$Vd="";$Ya="";$je=array();$Mh="";$Sc=($Q!=''?'fetch_assoc':'fetch_row');while($I=$G->$Sc()){if(!$je){$Xi=array();foreach($I
as$X){$o=$G->fetch_field();$je[]=$o->name;$y=idf_escape($o->name);$Xi[]="$y = VALUES($y)";}$Mh=($Kh=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$Xi):"").";\n";}if($_POST["format"]!="sql"){if($Kh=="table"){dump_csv($je);$Kh="INSERT";}dump_csv($I);}else{if(!$Vd)$Vd="INSERT INTO ".table($Q)." (".implode(", ",array_map('idf_escape',$je)).") VALUES";foreach($I
as$y=>$X){$o=$p[$y];$I[$y]=($X!==null?unconvert_field($o,preg_match(number_type(),$o["type"])&&!preg_match('~\[~',$o["full_type"])&&is_numeric($X)?$X:q(($X===false?0:$X))):"NULL");}$ah=($He?"\n":" ")."(".implode(",\t",$I).")";if(!$Ya)$Ya=$Vd.$ah;elseif(strlen($Ya)+4+strlen($ah)+strlen($Mh)<$He)$Ya.=",$ah";else{echo$Ya.$Mh;$Ya=$Vd.$ah;}}}if($Ya)echo$Ya.$Mh;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",$g->error)."\n";}}function
dumpFilename($Ed){return
friendly_url($Ed!=""?$Ed:(SERVER!=""?SERVER:"localhost"));}function
dumpHeaders($Ed,$We=false){$Mf=$_POST["output"];$Kc=(preg_match('~sql~',$_POST["format"])?"sql":($We?"tar":"csv"));header("Content-Type: ".($Mf=="gz"?"application/x-gzip":($Kc=="tar"?"application/x-tar":($Kc=="sql"||$Mf!="file"?"text/plain":"text/csv")."; charset=utf-8")));if($Mf=="gz")ob_start('ob_gzencode',1e6);return$Kc;}function
importServerPath(){return"adminer.sql";}function
homepage(){echo'<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");return
true;}function
navigation($Ve){global$ia,$x,$gc,$g;echo'<h1>
',$this->name(),' <span class="version">',$ia,'</span>
<a href="https://www.adminer.org/#download"',target_blank(),' id="version">',(version_compare($ia,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($Ve=="auth"){$Mf="";foreach((array)$_SESSION["pwds"]as$Zi=>$oh){foreach($oh
as$M=>$Ui){foreach($Ui
as$V=>$E){if($E!==null){$Sb=$_SESSION["db"][$Zi][$M][$V];foreach(($Sb?array_keys($Sb):array(""))as$l)$Mf.="<li><a href='".h(auth_url($Zi,$M,$V,$l))."'>($gc[$Zi]) ".h($V.($M!=""?"@".$this->serverName($M):"").($l!=""?" - $l":""))."</a>\n";}}}}if($Mf)echo"<ul id='logins'>\n$Mf</ul>\n".script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");}else{if($_GET["ns"]!==""&&!$Ve&&DB!=""){$g->select_db(DB);$S=table_status('',true);}echo
script_src(preg_replace("~\\?.*~","",ME)."?file=jush.js&version=4.7.7");if(support("sql")){echo'<script',nonce(),'>
';if($S){$ye=array();foreach($S
as$Q=>$T)$ye[]=preg_quote($Q,'/');echo"var jushLinks = { $x: [ '".js_escape(ME).(support("table")?"table=":"select=")."\$&', /\\b(".implode("|",$ye).")\\b/g ] };\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X)echo"jushLinks.$X = jushLinks.$x;\n";}$nh=$g->server_info;echo'bodyLoad(\'',(is_object($g)?preg_replace('~^(\d\.?\d).*~s','\1',$nh):""),'\'',(preg_match('~MariaDB~',$nh)?", true":""),');
</script>
';}$this->databasesPrint($Ve);if(DB==""||!$Ve){echo"<p class='links'>".(support("sql")?"<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".'SQL command'."</a>\n<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".'Import'."</a>\n":"")."";if(support("dump"))echo"<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Export'."</a>\n";}if($_GET["ns"]!==""&&!$Ve&&DB!=""){echo'<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create table'."</a>\n";if(!$S)echo"<p class='message'>".'No tables.'."\n";else$this->tablesPrint($S);}}}function
databasesPrint($Ve){global$b,$g;$k=$this->databases();if($k&&!in_array(DB,$k))array_unshift($k,DB);echo'<form action="">
<p id="dbs">
';hidden_fields_get();$Qb=script("mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});");echo"<span title='".'database'."'>".'DB'."</span>: ".($k?"<select name='db'>".optionlist(array(""=>"")+$k,DB)."</select>$Qb":"<input name='db' value='".h(DB)."' autocapitalize='off'>\n"),"<input type='submit' value='".'Use'."'".($k?" class='hidden'":"").">\n";if($Ve!="db"&&DB!=""&&$g->select_db(DB)){if(support("scheme")){echo"<br>".'Schema'.": <select name='ns'>".optionlist(array(""=>"")+$b->schemas(),$_GET["ns"])."</select>$Qb";if($_GET["ns"]!="")set_schema($_GET["ns"]);}}foreach(array("import","sql","schema","dump","privileges")as$X){if(isset($_GET[$X])){echo"<input type='hidden' name='$X' value=''>";break;}}echo"</p></form>\n";}function
tablesPrint($S){echo"<ul id='tables'>".script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");foreach($S
as$Q=>$O){$B=$this->tableName($O);if($B!=""){echo'<li><a href="'.h(ME).'select='.urlencode($Q).'"'.bold($_GET["select"]==$Q||$_GET["edit"]==$Q,"select").">".'select'."</a> ",(support("table")||support("indexes")?'<a href="'.h(ME).'table='.urlencode($Q).'"'.bold(in_array($Q,array($_GET["table"],$_GET["create"],$_GET["indexes"],$_GET["foreign"],$_GET["trigger"])),(is_view($O)?"view":"structure"))." title='".'Show structure'."'>$B</a>":"<span>$B</span>")."\n";}}echo"</ul>\n";}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);if($b->operators===null)$b->operators=$xf;function
page_header($ji,$n="",$Xa=array(),$ki=""){global$ca,$ia,$b,$gc,$x;page_headers();if(is_ajax()&&$n){page_messages($n);exit;}$li=$ji.($ki!=""?": $ki":"");$mi=strip_tags($li.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>',$mi,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=4.7.7"),'">
',script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=4.7.7");if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.7.7"),'">
<link rel="apple-touch-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.7.7"),'">
';foreach($b->css()as$Kb){echo'<link rel="stylesheet" type="text/css" href="',h($Kb),'">
';}}echo'
<body class="ltr nojs">
';$Wc=get_temp_dir()."/adminer.version";if(!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($Wc)&&filemtime($Wc)+86400>time()){$aj=unserialize(file_get_contents($Wc));$vg="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";if(openssl_verify($aj["version"],base64_decode($aj["signature"]),$vg)==1)$_COOKIE["adminer_version"]=$aj["version"];}echo'<script',nonce(),'>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick',(isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '$ia', '".js_escape(ME)."', '".get_token()."')");?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
js_escape('You are offline.'),'\';
var thousandsSeparator = \'',js_escape(','),'\';
</script>

<div id="help" class="jush-',$x,' jsonly hidden"></div>
',script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"),'
<div id="content">
';if($Xa!==null){$_=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?$_:".").'">'.$gc[DRIVER].'</a> &raquo; ';$_=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=$b->serverName(SERVER);$M=($M!=""?$M:'Server');if($Xa===false)echo"$M\n";else{echo"<a href='".($_?h($_):".")."' accesskey='1' title='Alt+Shift+1'>$M</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Xa)))echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Xa)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Xa
as$y=>$X){$Zb=(is_array($X)?$X[1]:h($X));if($Zb!="")echo"<a href='".h(ME."$y=").urlencode(is_array($X)?$X[0]:$X)."'>$Zb</a> &raquo; ";}}echo"$ji\n";}}echo"<h2>$li</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($n);$k=&get_session("dbs");if(DB!=""&&$k&&!in_array(DB,$k,true))$k=null;stop_session();define("PAGE_HEADER",1);}function
page_headers(){global$b;header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach($b->csp()as$Jb){$yd=array();foreach($Jb
as$y=>$X)$yd[]="$y $X";header("Content-Security-Policy: ".implode("; ",$yd));}$b->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self'","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
get_nonce(){static$ff;if(!$ff)$ff=base64_encode(rand_string());return$ff;}function
page_messages($n){$Mi=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Re=$_SESSION["messages"][$Mi];if($Re){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Re)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$Mi]);}if($n)echo"<div class='error'>$n</div>\n";}function
page_footer($Ve=""){global$b,$qi;echo'</div>

';if($Ve!="auth"){echo'<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',$qi,'">
</p>
</form>
';}echo'<div id="menu">
';$b->navigation($Ve);echo'</div>
',script("setupSubmitHighlight(document);");}function
int32($Ye){while($Ye>=2147483648)$Ye-=4294967296;while($Ye<=-2147483649)$Ye+=4294967296;return(int)$Ye;}function
long2str($W,$ej){$ah='';foreach($W
as$X)$ah.=pack('V',$X);if($ej)return
substr($ah,0,end($W));return$ah;}function
str2long($ah,$ej){$W=array_values(unpack('V*',str_pad($ah,4*ceil(strlen($ah)/4),"\0")));if($ej)$W[]=strlen($ah);return$W;}function
xxtea_mx($rj,$qj,$Nh,$fe){return
int32((($rj>>5&0x7FFFFFF)^$qj<<2)+(($qj>>3&0x1FFFFFFF)^$rj<<4))^int32(($Nh^$qj)+($fe^$rj));}function
encrypt_string($Ih,$y){if($Ih=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Ih,true);$Ye=count($W)-1;$rj=$W[$Ye];$qj=$W[0];$wg=floor(6+52/($Ye+1));$Nh=0;while($wg-->0){$Nh=int32($Nh+0x9E3779B9);$nc=$Nh>>2&3;for($Nf=0;$Nf<$Ye;$Nf++){$qj=$W[$Nf+1];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$rj=int32($W[$Nf]+$Xe);$W[$Nf]=$rj;}$qj=$W[0];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$rj=int32($W[$Ye]+$Xe);$W[$Ye]=$rj;}return
long2str($W,false);}function
decrypt_string($Ih,$y){if($Ih=="")return"";if(!$y)return
false;$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Ih,false);$Ye=count($W)-1;$rj=$W[$Ye];$qj=$W[0];$wg=floor(6+52/($Ye+1));$Nh=int32($wg*0x9E3779B9);while($Nh){$nc=$Nh>>2&3;for($Nf=$Ye;$Nf>0;$Nf--){$rj=$W[$Nf-1];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$qj=int32($W[$Nf]-$Xe);$W[$Nf]=$qj;}$rj=$W[$Ye];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$qj=int32($W[0]-$Xe);$W[0]=$qj;$Nh=int32($Nh-0x9E3779B9);}return
long2str($W,true);}$g='';$xd=$_SESSION["token"];if(!$xd)$_SESSION["token"]=rand(1,1e6);$qi=get_token();$dg=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($y)=explode(":",$X);$dg[$y]=$X;}}function
add_invalid_login(){global$b;$kd=file_open_lock(get_temp_dir()."/adminer.invalid");if(!$kd)return;$Yd=unserialize(stream_get_contents($kd));$gi=time();if($Yd){foreach($Yd
as$Zd=>$X){if($X[0]<$gi)unset($Yd[$Zd]);}}$Xd=&$Yd[$b->bruteForceKey()];if(!$Xd)$Xd=array($gi+30*60,0);$Xd[1]++;file_write_unlock($kd,serialize($Yd));}function
check_invalid_login(){global$b;$Yd=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));$Xd=$Yd[$b->bruteForceKey()];$ef=($Xd[1]>29?$Xd[0]-time():0);if($ef>0)auth_error(lang(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($ef/60)));}$Ka=$_POST["auth"];if($Ka){session_regenerate_id();$Zi=$Ka["driver"];$M=$Ka["server"];$V=$Ka["username"];$E=(string)$Ka["password"];$l=$Ka["db"];set_password($Zi,$M,$V,$E);$_SESSION["db"][$Zi][$M][$V][$l]=true;if($Ka["permanent"]){$y=base64_encode($Zi)."-".base64_encode($M)."-".base64_encode($V)."-".base64_encode($l);$pg=$b->permanentLogin(true);$dg[$y]="$y:".base64_encode($pg?encrypt_string($E,$pg):"");cookie("adminer_permanent",implode(" ",$dg));}if(count($_POST)==1||DRIVER!=$Zi||SERVER!=$M||$_GET["username"]!==$V||DB!=$l)redirect(auth_url($Zi,$M,$V,$l));}elseif($_POST["logout"]){if($xd&&!verify_token()){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","db","dbs","queries")as$y)set_session($y,null);unset_permanent();redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.'.' '.'Thanks for using Adminer, consider <a href="https://www.adminer.org/en/donation/">donating</a>.');}}elseif($dg&&!$_SESSION["pwds"]){session_regenerate_id();$pg=$b->permanentLogin();foreach($dg
as$y=>$X){list(,$jb)=explode(":",$X);list($Zi,$M,$V,$l)=array_map('base64_decode',explode("-",$y));set_password($Zi,$M,$V,decrypt_string(base64_decode($jb),$pg));$_SESSION["db"][$Zi][$M][$V][$l]=true;}}function
unset_permanent(){global$dg;foreach($dg
as$y=>$X){list($Zi,$M,$V,$l)=array_map('base64_decode',explode("-",$y));if($Zi==DRIVER&&$M==SERVER&&$V==$_GET["username"]&&$l==DB)unset($dg[$y]);}cookie("adminer_permanent",implode(" ",$dg));}function
auth_error($n){global$b,$xd;$ph=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$ph]||$_GET[$ph])&&!$xd)$n='Session expired, please login again.';else{restart_session();add_invalid_login();$E=get_password();if($E!==null){if($E===false)$n.='<br>'.sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> %s method to make it permanent.',target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent();}}if(!$_COOKIE[$ph]&&$_GET[$ph]&&ini_bool("session.use_only_cookies"))$n='Session support must be enabled.';$Qf=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?$_COOKIE["adminer_key"]:rand_string()),$Qf["lifetime"]);page_header('Login',$n,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".'The action will be performed after successful login with the same credentials.'."\n";echo"</div>\n";$b->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$jg)),false);page_footer("auth");exit;}stop_session(true);if(isset($_GET["username"])&&is_string(get_password())){list($Cd,$fg)=explode(":",SERVER,2);if(is_numeric($fg)&&($fg<1024||$fg>65535))auth_error('Connecting to privileged ports is not allowed.');check_invalid_login();$g=connect();$m=new
Min_Driver($g);}$_e=null;if(!is_object($g)||($_e=$b->login($_GET["username"],get_password()))!==true){$n=(is_string($g)?h($g):(is_string($_e)?$_e:'Invalid credentials.'));auth_error($n.(preg_match('~^ | $~',get_password())?'<br>'.'There is a space in the input password which might be the cause.':''));}if($Ka&&$_POST["token"])$_POST["token"]=$qi;$n='';if($_POST){if(!verify_token()){$Sd="max_input_vars";$Le=ini_get($Sd);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$y){$X=ini_get($y);if($X&&(!$Le||$X<$Le)){$Sd=$y;$Le=$X;}}}$n=(!$_POST["token"]&&$Le?sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"'$Sd'"):'Invalid CSRF token. Send the form again.'.' '.'If you did not send this request from Adminer then close this page.');}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$n=sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.',"'post_max_size'");if(isset($_GET["sql"]))$n.=' '.'You can upload a big SQL file via FTP and import it from server.';}function
select($G,$h=null,$Ef=array(),$z=0){global$x;$ye=array();$w=array();$f=array();$Ua=array();$U=array();$H=array();odd('');for($s=0;(!$z||$s<$z)&&($I=$G->fetch_row());$s++){if(!$s){echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap'>\n","<thead><tr>";for($ee=0;$ee<count($I);$ee++){$o=$G->fetch_field();$B=$o->name;$Df=$o->orgtable;$Cf=$o->orgname;$H[$o->table]=$Df;if($Ef&&$x=="sql")$ye[$ee]=($B=="table"?"table=":($B=="possible_keys"?"indexes=":null));elseif($Df!=""){if(!isset($w[$Df])){$w[$Df]=array();foreach(indexes($Df,$h)as$v){if($v["type"]=="PRIMARY"){$w[$Df]=array_flip($v["columns"]);break;}}$f[$Df]=$w[$Df];}if(isset($f[$Df][$Cf])){unset($f[$Df][$Cf]);$w[$Df][$Cf]=$ee;$ye[$ee]=$Df;}}if($o->charsetnr==63)$Ua[$ee]=true;$U[$ee]=$o->type;echo"<th".($Df!=""||$o->name!=$Cf?" title='".h(($Df!=""?"$Df.":"").$Cf)."'":"").">".h($B).($Ef?doc_link(array('sql'=>"explain-output.html#explain_".strtolower($B),'mariadb'=>"explain/#the-columns-in-explain-select",)):"");}echo"</thead>\n";}echo"<tr".odd().">";foreach($I
as$y=>$X){if($X===null)$X="<i>NULL</i>";elseif($Ua[$y]&&!is_utf8($X))$X="<i>".lang(array('%d byte','%d bytes'),strlen($X))."</i>";else{$X=h($X);if($U[$y]==254)$X="<code>$X</code>";}if(isset($ye[$y])&&!$f[$ye[$y]]){if($Ef&&$x=="sql"){$Q=$I[array_search("table=",$ye)];$_=$ye[$y].urlencode($Ef[$Q]!=""?$Ef[$Q]:$Q);}else{$_="edit=".urlencode($ye[$y]);foreach($w[$ye[$y]]as$nb=>$ee)$_.="&where".urlencode("[".bracket_escape($nb)."]")."=".urlencode($I[$ee]);}$X="<a href='".h(ME.$_)."'>$X</a>";}echo"<td>$X";}}echo($s?"</table>\n</div>":"<p class='message'>".'No rows.')."\n";return$H;}function
referencable_primary($jh){$H=array();foreach(table_status('',true)as$Rh=>$Q){if($Rh!=$jh&&fk_support($Q)){foreach(fields($Rh)as$o){if($o["primary"]){if($H[$Rh]){unset($H[$Rh]);break;}$H[$Rh]=$o;}}}}return$H;}function
adminer_settings(){parse_str($_COOKIE["adminer_settings"],$rh);return$rh;}function
adminer_setting($y){$rh=adminer_settings();return$rh[$y];}function
set_adminer_settings($rh){return
cookie("adminer_settings",http_build_query($rh+adminer_settings()));}function
textarea($B,$Y,$J=10,$rb=80){global$x;echo"<textarea name='$B' rows='$J' cols='$rb' class='sqlarea jush-$x' spellcheck='false' wrap='off'>";if(is_array($Y)){foreach($Y
as$X)echo
h($X[0])."\n\n\n";}else
echo
h($Y);echo"</textarea>";}function
edit_type($y,$o,$pb,$gd=array(),$Nc=array()){global$Jh,$U,$Ki,$sf;$T=$o["type"];echo'<td><select name="',h($y),'[type]" class="type" aria-labelledby="label-type">';if($T&&!isset($U[$T])&&!isset($gd[$T])&&!in_array($T,$Nc))$Nc[]=$T;if($gd)$Jh['Foreign keys']=$gd;echo
optionlist(array_merge($Nc,$Jh),$T),'</select><td><input name="',h($y),'[length]" value="',h($o["length"]),'" size="3"',(!$o["length"]&&preg_match('~var(char|binary)$~',$T)?" class='required'":"");echo' aria-labelledby="label-length"><td class="options">',"<select name='".h($y)."[collation]'".(preg_match('~(char|text|enum|set)$~',$T)?"":" class='hidden'").'><option value="">('.'collation'.')'.optionlist($pb,$o["collation"]).'</select>',($Ki?"<select name='".h($y)."[unsigned]'".(!$T||preg_match(number_type(),$T)?"":" class='hidden'").'><option>'.optionlist($Ki,$o["unsigned"]).'</select>':''),(isset($o['on_update'])?"<select name='".h($y)."[on_update]'".(preg_match('~timestamp|datetime~',$T)?"":" class='hidden'").'>'.optionlist(array(""=>"(".'ON UPDATE'.")","CURRENT_TIMESTAMP"),(preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?"CURRENT_TIMESTAMP":$o["on_update"])).'</select>':''),($gd?"<select name='".h($y)."[on_delete]'".(preg_match("~`~",$T)?"":" class='hidden'")."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",$sf),$o["on_delete"])."</select> ":" ");}function
process_length($ve){global$yc;return(preg_match("~^\\s*\\(?\\s*$yc(?:\\s*,\\s*$yc)*+\\s*\\)?\\s*\$~",$ve)&&preg_match_all("~$yc~",$ve,$Fe)?"(".implode(",",$Fe[0]).")":preg_replace('~^[0-9].*~','(\0)',preg_replace('~[^-0-9,+()[\]]~','',$ve)));}function
process_type($o,$ob="COLLATE"){global$Ki;return" $o[type]".process_length($o["length"]).(preg_match(number_type(),$o["type"])&&in_array($o["unsigned"],$Ki)?" $o[unsigned]":"").(preg_match('~char|text|enum|set~',$o["type"])&&$o["collation"]?" $ob ".q($o["collation"]):"");}function
process_field($o,$Ci){return
array(idf_escape(trim($o["field"])),process_type($Ci),($o["null"]?" NULL":" NOT NULL"),default_value($o),(preg_match('~timestamp|datetime~',$o["type"])&&$o["on_update"]?" ON UPDATE $o[on_update]":""),(support("comment")&&$o["comment"]!=""?" COMMENT ".q($o["comment"]):""),($o["auto_increment"]?auto_increment():null),);}function
default_value($o){$Ub=$o["default"];return($Ub===null?"":" DEFAULT ".(preg_match('~char|binary|text|enum|set~',$o["type"])||preg_match('~^(?![a-z])~i',$Ub)?q($Ub):$Ub));}function
type_class($T){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$y=>$X){if(preg_match("~$y|$X~",$T))return" class='$y'";}}function
edit_fields($p,$pb,$T="TABLE",$gd=array()){global$Td;$p=array_values($p);$Vb=(($_POST?$_POST["defaults"]:adminer_setting("defaults"))?"":" class='hidden'");$vb=(($_POST?$_POST["comments"]:adminer_setting("comments"))?"":" class='hidden'");echo'<thead><tr>
';if($T=="PROCEDURE"){echo'<td>';}echo'<th id="label-name">',($T=="TABLE"?'Column name':'Parameter name'),'<td id="label-type">Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>',script("qs('#enum-edit').onblur = editingLengthBlur;"),'<td id="label-length">Length
<td>','Options';if($T=="TABLE"){echo'<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym id="label-ai" title="Auto Increment">AI</acronym>',doc_link(array('sql'=>"example-auto-increment.html",'mariadb'=>"auto_increment/",'sqlite'=>"autoinc.html",'pgsql'=>"datatype.html#DATATYPE-SERIAL",'mssql'=>"ms186775.aspx",)),'<td id="label-default"',$Vb,'>Default value
',(support("comment")?"<td id='label-comment'$vb>".'Comment':"");}echo'<td>',"<input type='image' class='icon' name='add[".(support("move_col")?0:count($p))."]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'>".script("row_count = ".count($p).";"),'</thead>
<tbody>
',script("mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});");foreach($p
as$s=>$o){$s++;$Ff=$o[($_POST?"orig":"field")];$dc=(isset($_POST["add"][$s-1])||(isset($o["field"])&&!$_POST["drop_col"][$s]))&&(support("drop_col")||$Ff=="");echo'<tr',($dc?"":" style='display: none;'"),'>
',($T=="PROCEDURE"?"<td>".html_select("fields[$s][inout]",explode("|",$Td),$o["inout"]):""),'<th>';if($dc){echo'<input name="fields[',$s,'][field]" value="',h($o["field"]),'" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">';}echo'<input type="hidden" name="fields[',$s,'][orig]" value="',h($Ff),'">';edit_type("fields[$s]",$o,$pb,$gd);if($T=="TABLE"){echo'<td>',checkbox("fields[$s][null]",1,$o["null"],"","","block","label-null"),'<td><label class="block"><input type="radio" name="auto_increment_col" value="',$s,'"';if($o["auto_increment"]){echo' checked';}echo' aria-labelledby="label-ai"></label><td',$Vb,'>',checkbox("fields[$s][has_default]",1,$o["has_default"],"","","","label-default"),'<input name="fields[',$s,'][default]" value="',h($o["default"]),'" aria-labelledby="label-default">',(support("comment")?"<td$vb><input name='fields[$s][comment]' value='".h($o["comment"])."' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'>":"");}echo"<td>",(support("move_col")?"<input type='image' class='icon' name='add[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'> "."<input type='image' class='icon' name='up[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=up.gif&version=4.7.7")."' alt='‚Üë' title='".'Move up'."'> "."<input type='image' class='icon' name='down[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=down.gif&version=4.7.7")."' alt='‚Üì' title='".'Move down'."'> ":""),($Ff==""||support("drop_col")?"<input type='image' class='icon' name='drop_col[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=cross.gif&version=4.7.7")."' alt='x' title='".'Remove'."'>":"");}}function
process_fields(&$p){$C=0;if($_POST["up"]){$pe=0;foreach($p
as$y=>$o){if(key($_POST["up"])==$y){unset($p[$y]);array_splice($p,$pe,0,array($o));break;}if(isset($o["field"]))$pe=$C;$C++;}}elseif($_POST["down"]){$id=false;foreach($p
as$y=>$o){if(isset($o["field"])&&$id){unset($p[key($_POST["down"])]);array_splice($p,$C,0,array($id));break;}if(key($_POST["down"])==$y)$id=$o;$C++;}}elseif($_POST["add"]){$p=array_values($p);array_splice($p,key($_POST["add"]),0,array(array()));}elseif(!$_POST["drop_col"])return
false;return
true;}function
normalize_enum($A){return"'".str_replace("'","''",addcslashes(stripcslashes(str_replace($A[0][0].$A[0][0],$A[0][0],substr($A[0],1,-1))),'\\'))."'";}function
grant($nd,$rg,$f,$rf){if(!$rg)return
true;if($rg==array("ALL PRIVILEGES","GRANT OPTION"))return($nd=="GRANT"?queries("$nd ALL PRIVILEGES$rf WITH GRANT OPTION"):queries("$nd ALL PRIVILEGES$rf")&&queries("$nd GRANT OPTION$rf"));return
queries("$nd ".preg_replace('~(GRANT OPTION)\([^)]*\)~','\1',implode("$f, ",$rg).$f).$rf);}function
drop_create($hc,$i,$ic,$di,$kc,$ze,$Qe,$Oe,$Pe,$of,$bf){if($_POST["drop"])query_redirect($hc,$ze,$Qe);elseif($of=="")query_redirect($i,$ze,$Pe);elseif($of!=$bf){$Hb=queries($i);queries_redirect($ze,$Oe,$Hb&&queries($hc));if($Hb)queries($ic);}else
queries_redirect($ze,$Oe,queries($di)&&queries($kc)&&queries($hc)&&queries($i));}function
create_trigger($rf,$I){global$x;$ii=" $I[Timing] $I[Event]".($I["Event"]=="UPDATE OF"?" ".idf_escape($I["Of"]):"");return"CREATE TRIGGER ".idf_escape($I["Trigger"]).($x=="mssql"?$rf.$ii:$ii.$rf).rtrim(" $I[Type]\n$I[Statement]",";").";";}function
create_routine($Wg,$I){global$Td,$x;$N=array();$p=(array)$I["fields"];ksort($p);foreach($p
as$o){if($o["field"]!="")$N[]=(preg_match("~^($Td)\$~",$o["inout"])?"$o[inout] ":"").idf_escape($o["field"]).process_type($o,"CHARACTER SET");}$Wb=rtrim("\n$I[definition]",";");return"CREATE $Wg ".idf_escape(trim($I["name"]))." (".implode(", ",$N).")".(isset($_GET["function"])?" RETURNS".process_type($I["returns"],"CHARACTER SET"):"").($I["language"]?" LANGUAGE $I[language]":"").($x=="pgsql"?" AS ".q($Wb):"$Wb;");}function
remove_definer($F){return
preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\1)',logged_user()).'`~','\1',$F);}function
format_foreign_key($q){global$sf;$l=$q["db"];$gf=$q["ns"];return" FOREIGN KEY (".implode(", ",array_map('idf_escape',$q["source"])).") REFERENCES ".($l!=""&&$l!=$_GET["db"]?idf_escape($l).".":"").($gf!=""&&$gf!=$_GET["ns"]?idf_escape($gf).".":"").table($q["table"])." (".implode(", ",array_map('idf_escape',$q["target"])).")".(preg_match("~^($sf)\$~",$q["on_delete"])?" ON DELETE $q[on_delete]":"").(preg_match("~^($sf)\$~",$q["on_update"])?" ON UPDATE $q[on_update]":"");}function
tar_file($Wc,$ni){$H=pack("a100a8a8a8a12a12",$Wc,644,0,0,decoct($ni->size),decoct(time()));$hb=8*32;for($s=0;$s<strlen($H);$s++)$hb+=ord($H[$s]);$H.=sprintf("%06o",$hb)."\0 ";echo$H,str_repeat("\0",512-strlen($H));$ni->send();echo
str_repeat("\0",511-($ni->size+511)%512);}function
ini_bytes($Sd){$X=ini_get($Sd);switch(strtolower(substr($X,-1))){case'g':$X*=1024;case'm':$X*=1024;case'k':$X*=1024;}return$X;}function
doc_link($bg,$ei="<sup>?</sup>"){global$x,$g;$nh=$g->server_info;$aj=preg_replace('~^(\d\.?\d).*~s','\1',$nh);$Pi=array('sql'=>"https://dev.mysql.com/doc/refman/$aj/en/",'sqlite'=>"https://www.sqlite.org/",'pgsql'=>"https://www.postgresql.org/docs/$aj/",'mssql'=>"https://msdn.microsoft.com/library/",'oracle'=>"https://www.oracle.com/pls/topic/lookup?ctx=db".preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s','\1\2',$nh)."&id=",);if(preg_match('~MariaDB~',$nh)){$Pi['sql']="https://mariadb.com/kb/en/library/";$bg['sql']=(isset($bg['mariadb'])?$bg['mariadb']:str_replace(".html","/",$bg['sql']));}return($bg[$x]?"<a href='$Pi[$x]$bg[$x]'".target_blank().">$ei</a>":"");}function
ob_gzencode($P){return
gzencode($P);}function
db_size($l){global$g;if(!$g->select_db($l))return"?";$H=0;foreach(table_status()as$R)$H+=$R["Data_length"]+$R["Index_length"];return
format_number($H);}function
set_utf8mb4($i){global$g;static$N=false;if(!$N&&preg_match('~\butf8mb4~i',$i)){$N=true;echo"SET NAMES ".charset($g).";\n\n";}}function
connect_error(){global$b,$g,$qi,$n,$gc;if(DB!=""){header("HTTP/1.1 404 Not Found");page_header('Database'.": ".h(DB),'Invalid database.',true);}else{if($_POST["db"]&&!$n)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$n,false);echo"<p class='links'>\n";foreach(array('database'=>'Create database','privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$y=>$X){if(support($y))echo"<a href='".h(ME)."$y='>$X</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',$gc[DRIVER],"<b>".h($g->server_info)."</b>","<b>$g->extension</b>")."\n","<p>".sprintf('Logged as: %s',"<b>".h(logged_user())."</b>")."\n";$k=$b->databases();if($k){$dh=support("scheme");$pb=collations();echo"<form action='' method='post'>\n","<table cellspacing='0' class='checkable'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),"<thead><tr>".(support("database")?"<td>":"")."<th>".'Database'." - <a href='".h(ME)."refresh=1'>".'Refresh'."</a>"."<td>".'Collation'."<td>".'Tables'."<td>".'Size'." - <a href='".h(ME)."dbsize=1'>".'Compute'."</a>".script("qsl('a').onclick = partial(ajaxSetHtml, '".js_escape(ME)."script=connect');","")."</thead>\n";$k=($_GET["dbsize"]?count_tables($k):array_flip($k));foreach($k
as$l=>$S){$Vg=h(ME)."db=".urlencode($l);$t=h("Db-".$l);echo"<tr".odd().">".(support("database")?"<td>".checkbox("db[]",$l,in_array($l,(array)$_POST["db"]),"","","",$t):""),"<th><a href='$Vg' id='$t'>".h($l)."</a>";$d=h(db_collation($l,$pb));echo"<td>".(support("database")?"<a href='$Vg".($dh?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>$d</a>":$d),"<td align='right'><a href='$Vg&amp;schema=' id='tables-".h($l)."' title='".'Database schema'."'>".($_GET["dbsize"]?$S:"?")."</a>","<td align='right' id='size-".h($l)."'>".($_GET["dbsize"]?db_size($l):"?"),"\n";}echo"</table>\n",(support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''>".script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };")."<input type='submit' name='drop' value='".'Drop'."'>".confirm()."\n"."</div></fieldset>\n"."</div></div>\n":""),"<input type='hidden' name='token' value='$qi'>\n","</form>\n",script("tableCheck();");}}page_footer("db");}if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(isset($_GET["import"]))$_GET["sql"]=$_GET["import"];if(!(DB!=""?$g->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}connect_error();exit;}if(support("scheme")&&DB!=""&&$_GET["ns"]!==""){if(!isset($_GET["ns"]))redirect(preg_replace('~ns=[^&]*&~','',ME)."ns=".get_schema());if(!set_schema($_GET["ns"])){header("HTTP/1.1 404 Not Found");page_header('Schema'.": ".h($_GET["ns"]),'Invalid schema.',true);page_footer("ns");exit;}}$sf="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";class
TmpFile{var$handler;var$size;function
__construct(){$this->handler=tmpfile();}function
write($Bb){$this->size+=strlen($Bb);fwrite($this->handler,$Bb);}function
send(){fseek($this->handler,0);fpassthru($this->handler);fclose($this->handler);}}$yc="'(?:''|[^'\\\\]|\\\\.)*'";$Td="IN|OUT|INOUT";if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$p=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$K=array(idf_escape($_GET["field"]));$G=$m->select($a,$K,array(where($_GET,$p)),$K);$I=($G?$G->fetch_row():array());echo$m->value($I[0],$p[$_GET["field"]]);exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$p=fields($a);if(!$p)$n=error();$R=table_status1($a,true);$B=$b->tableName($R);page_header(($p&&is_view($R)?$R['Engine']=='materialized view'?'Materialized view':'View':'Table').": ".($B!=""?$B:h($a)),$n);$b->selectLinks($R);$ub=$R["Comment"];if($ub!="")echo"<p class='nowrap'>".'Comment'.": ".h($ub)."\n";if($p)$b->tableStructurePrint($p);if(!is_view($R)){if(support("indexes")){echo"<h3 id='indexes'>".'Indexes'."</h3>\n";$w=indexes($a);if($w)$b->tableIndexesPrint($w);echo'<p class="links"><a href="'.h(ME).'indexes='.urlencode($a).'">'.'Alter indexes'."</a>\n";}if(fk_support($R)){echo"<h3 id='foreign-keys'>".'Foreign keys'."</h3>\n";$gd=foreign_keys($a);if($gd){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Source'."<td>".'Target'."<td>".'ON DELETE'."<td>".'ON UPDATE'."<td></thead>\n";foreach($gd
as$B=>$q){echo"<tr title='".h($B)."'>","<th><i>".implode("</i>, <i>",array_map('h',$q["source"]))."</i>","<td><a href='".h($q["db"]!=""?preg_replace('~db=[^&]*~',"db=".urlencode($q["db"]),ME):($q["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".urlencode($q["ns"]),ME):ME))."table=".urlencode($q["table"])."'>".($q["db"]!=""?"<b>".h($q["db"])."</b>.":"").($q["ns"]!=""?"<b>".h($q["ns"])."</b>.":"").h($q["table"])."</a>","(<i>".implode("</i>, <i>",array_map('h',$q["target"]))."</i>)","<td>".h($q["on_delete"])."\n","<td>".h($q["on_update"])."\n",'<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($B)).'">'.'Alter'.'</a>';}echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'foreign='.urlencode($a).'">'.'Add foreign key'."</a>\n";}}if(support(is_view($R)?"view_trigger":"trigger")){echo"<h3 id='triggers'>".'Triggers'."</h3>\n";$Bi=triggers($a);if($Bi){echo"<table cellspacing='0'>\n";foreach($Bi
as$y=>$X)echo"<tr valign='top'><td>".h($X[0])."<td>".h($X[1])."<th>".h($y)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($y))."'>".'Alter'."</a>\n";echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'trigger='.urlencode($a).'">'.'Add trigger'."</a>\n";}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),h(DB.($_GET["ns"]?".$_GET[ns]":"")));$Th=array();$Uh=array();$ea=($_GET["schema"]?$_GET["schema"]:$_COOKIE["adminer_schema-".str_replace(".","_",DB)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ea,$Fe,PREG_SET_ORDER);foreach($Fe
as$s=>$A){$Th[$A[1]]=array($A[2],$A[3]);$Uh[]="\n\t'".js_escape($A[1])."': [ $A[2], $A[3] ]";}$ri=0;$Ra=-1;$ch=array();$Hg=array();$te=array();foreach(table_status('',true)as$Q=>$R){if(is_view($R))continue;$gg=0;$ch[$Q]["fields"]=array();foreach(fields($Q)as$B=>$o){$gg+=1.25;$o["pos"]=$gg;$ch[$Q]["fields"][$B]=$o;}$ch[$Q]["pos"]=($Th[$Q]?$Th[$Q]:array($ri,0));foreach($b->foreignKeys($Q)as$X){if(!$X["db"]){$re=$Ra;if($Th[$Q][1]||$Th[$X["table"]][1])$re=min(floatval($Th[$Q][1]),floatval($Th[$X["table"]][1]))-1;else$Ra-=.1;while($te[(string)$re])$re-=.0001;$ch[$Q]["references"][$X["table"]][(string)$re]=array($X["source"],$X["target"]);$Hg[$X["table"]][$Q][(string)$re]=$X["target"];$te[(string)$re]=true;}}$ri=max($ri,$ch[$Q]["pos"][0]+2.5+$gg);}echo'<div id="schema" style="height: ',$ri,'em;">
<script',nonce(),'>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {',implode(",",$Uh)."\n",'};
var em = qs(\'#schema\').offsetHeight / ',$ri,';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'',js_escape(DB),'\');
</script>
';foreach($ch
as$B=>$Q){echo"<div class='table' style='top: ".$Q["pos"][0]."em; left: ".$Q["pos"][1]."em;'>",'<a href="'.h(ME).'table='.urlencode($B).'"><b>'.h($B)."</b></a>",script("qsl('div').onmousedown = schemaMousedown;");foreach($Q["fields"]as$o){$X='<span'.type_class($o["type"]).' title="'.h($o["full_type"].($o["null"]?" NULL":'')).'">'.h($o["field"]).'</span>';echo"<br>".($o["primary"]?"<i>$X</i>":$X);}foreach((array)$Q["references"]as$ai=>$Ig){foreach($Ig
as$re=>$Eg){$se=$re-$Th[$B][1];$s=0;foreach($Eg[0]as$yh)echo"\n<div class='references' title='".h($ai)."' id='refs$re-".($s++)."' style='left: $se"."em; top: ".$Q["fields"][$yh]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$se)."em;'></div></div>";}}foreach((array)$Hg[$B]as$ai=>$Ig){foreach($Ig
as$re=>$f){$se=$re-$Th[$B][1];$s=0;foreach($f
as$Zh)echo"\n<div class='references' title='".h($ai)."' id='refd$re-".($s++)."' style='left: $se"."em; top: ".$Q["fields"][$Zh]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~","",ME)."?file=arrow.gif) no-repeat right center;&version=4.7.7")."'><div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$se)."em;'></div></div>";}}echo"\n</div>\n";}foreach($ch
as$B=>$Q){foreach((array)$Q["references"]as$ai=>$Ig){foreach($Ig
as$re=>$Eg){$Ue=$ri;$Je=-10;foreach($Eg[0]as$y=>$yh){$hg=$Q["pos"][0]+$Q["fields"][$yh]["pos"];$ig=$ch[$ai]["pos"][0]+$ch[$ai]["fields"][$Eg[1][$y]]["pos"];$Ue=min($Ue,$hg,$ig);$Je=max($Je,$hg,$ig);}echo"<div class='references' id='refl$re' style='left: $re"."em; top: $Ue"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($Je-$Ue)."em;'></div></div>\n";}}}echo'</div>
<p class="links"><a href="',h(ME."schema=".urlencode($ea)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST&&!$n){$Eb="";foreach(array("output","format","db_style","routines","events","table_style","auto_increment","triggers","data_style")as$y)$Eb.="&$y=".urlencode($_POST[$y]);cookie("adminer_export",substr($Eb,1));$S=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);$Kc=dump_headers((count($S)==1?key($S):DB),(DB==""||count($S)>1));$be=preg_match('~sql~',$_POST["format"]);if($be){echo"-- Adminer $ia ".$gc[DRIVER]." dump\n\n";if($x=="sql"){echo"SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";$g->query("SET time_zone = '+00:00';");}}$Kh=$_POST["db_style"];$k=array(DB);if(DB==""){$k=$_POST["databases"];if(is_string($k))$k=explode("\n",rtrim(str_replace("\r","",$k),"\n"));}foreach((array)$k
as$l){$b->dumpDatabase($l);if($g->select_db($l)){if($be&&preg_match('~CREATE~',$Kh)&&($i=$g->result("SHOW CREATE DATABASE ".idf_escape($l),1))){set_utf8mb4($i);if($Kh=="DROP+CREATE")echo"DROP DATABASE IF EXISTS ".idf_escape($l).";\n";echo"$i;\n";}if($be){if($Kh)echo
use_sql($l).";\n\n";$Lf="";if($_POST["routines"]){foreach(array("FUNCTION","PROCEDURE")as$Wg){foreach(get_rows("SHOW $Wg STATUS WHERE Db = ".q($l),null,"-- ")as$I){$i=remove_definer($g->result("SHOW CREATE $Wg ".idf_escape($I["Name"]),2));set_utf8mb4($i);$Lf.=($Kh!='DROP+CREATE'?"DROP $Wg IF EXISTS ".idf_escape($I["Name"]).";;\n":"")."$i;;\n\n";}}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$I){$i=remove_definer($g->result("SHOW CREATE EVENT ".idf_escape($I["Name"]),3));set_utf8mb4($i);$Lf.=($Kh!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($I["Name"]).";;\n":"")."$i;;\n\n";}}if($Lf)echo"DELIMITER ;;\n\n$Lf"."DELIMITER ;\n\n";}if($_POST["table_style"]||$_POST["data_style"]){$cj=array();foreach(table_status('',true)as$B=>$R){$Q=(DB==""||in_array($B,(array)$_POST["tables"]));$Nb=(DB==""||in_array($B,(array)$_POST["data"]));if($Q||$Nb){if($Kc=="tar"){$ni=new
TmpFile;ob_start(array($ni,'write'),1e5);}$b->dumpTable($B,($Q?$_POST["table_style"]:""),(is_view($R)?2:0));if(is_view($R))$cj[]=$B;elseif($Nb){$p=fields($B);$b->dumpData($B,$_POST["data_style"],"SELECT *".convert_fields($p,$p)." FROM ".table($B));}if($be&&$_POST["triggers"]&&$Q&&($Bi=trigger_sql($B)))echo"\nDELIMITER ;;\n$Bi\nDELIMITER ;\n";if($Kc=="tar"){ob_end_flush();tar_file((DB!=""?"":"$l/")."$B.csv",$ni);}elseif($be)echo"\n";}}foreach($cj
as$bj)$b->dumpTable($bj,$_POST["table_style"],1);if($Kc=="tar")echo
pack("x512");}}}if($be)echo"-- ".$g->result("SELECT NOW()")."\n";exit;}page_header('Export',$n,($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),h(DB));echo'
<form action="" method="post">
<table cellspacing="0" class="layout">
';$Rb=array('','USE','DROP+CREATE','CREATE');$Vh=array('','DROP+CREATE','CREATE');$Ob=array('','TRUNCATE+INSERT','INSERT');if($x=="sql")$Ob[]='INSERT+UPDATE';parse_str($_COOKIE["adminer_export"],$I);if(!$I)$I=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");if(!isset($I["events"])){$I["routines"]=$I["events"]=($_GET["dump"]=="");$I["triggers"]=$I["table_style"];}echo"<tr><th>".'Output'."<td>".html_select("output",$b->dumpOutput(),$I["output"],0)."\n";echo"<tr><th>".'Format'."<td>".html_select("format",$b->dumpFormat(),$I["format"],0)."\n";echo($x=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$Rb,$I["db_style"]).(support("routine")?checkbox("routines",1,$I["routines"],'Routines'):"").(support("event")?checkbox("events",1,$I["events"],'Events'):"")),"<tr><th>".'Tables'."<td>".html_select('table_style',$Vh,$I["table_style"]).checkbox("auto_increment",1,$I["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$I["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$Ob,$I["data_style"]),'</table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="',$qi,'">

<table cellspacing="0">
',script("qsl('table').onclick = dumpClick;");$lg=array();if(DB!=""){$fb=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$fb>".'Tables'."</label>".script("qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);",""),"<th style='text-align: right;'><label class='block'>".'Data'."<input type='checkbox' id='check-data'$fb></label>".script("qs('#check-data').onclick = partial(formCheck, /^data\\[/);",""),"</thead>\n";$cj="";$Wh=tables_list();foreach($Wh
as$B=>$T){$kg=preg_replace('~_.*~','',$B);$fb=($a==""||$a==(substr($a,-1)=="%"?"$kg%":$B));$og="<tr><td>".checkbox("tables[]",$B,$fb,$B,"","block");if($T!==null&&!preg_match('~table~i',$T))$cj.="$og\n";else
echo"$og<td align='right'><label class='block'><span id='Rows-".h($B)."'></span>".checkbox("data[]",$B,$fb)."</label>\n";$lg[$kg]++;}echo$cj;if($Wh)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}else{echo"<thead><tr><th style='text-align: left;'>","<label class='block'><input type='checkbox' id='check-databases'".($a==""?" checked":"").">".'Database'."</label>",script("qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);",""),"</thead>\n";$k=$b->databases();if($k){foreach($k
as$l){if(!information_schema($l)){$kg=preg_replace('~_.*~','',$l);echo"<tr><td>".checkbox("databases[]",$l,$a==""||$a=="$kg%",$l,"","block")."\n";$lg[$kg]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$Yc=true;foreach($lg
as$y=>$X){if($y!=""&&$X>1){echo($Yc?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$y%")."'>".h($y)."</a>";$Yc=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');echo'<p class="links"><a href="'.h(ME).'user=">'.'Create user'."</a>";$G=$g->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$nd=$G;if(!$G)$G=$g->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo"<input type='hidden' name='db' value='".h(DB)."'>\n",($nd?"":"<input type='hidden' name='grant' value=''>\n"),"<table cellspacing='0'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<th></thead>\n";while($I=$G->fetch_assoc())echo'<tr'.odd().'><td>'.h($I["User"])."<td>".h($I["Host"]).'<td><a href="'.h(ME.'user='.urlencode($I["User"]).'&host='.urlencode($I["Host"])).'">'.'Edit'."</a>\n";if(!$nd||DB!="")echo"<tr".odd()."><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n";}elseif(isset($_GET["sql"])){if(!$n&&$_POST["export"]){dump_headers("sql");$b->dumpTable("","");$b->dumpData("","table",$_POST["query"]);exit;}restart_session();$Ad=&get_session("queries");$_d=&$Ad[DB];if(!$n&&$_POST["clear"]){$_d=array();redirect(remove_from_uri("history"));}page_header((isset($_GET["import"])?'Import':'SQL command'),$n);if(!$n&&$_POST){$kd=false;if(!isset($_GET["import"]))$F=$_POST["query"];elseif($_POST["webfile"]){$Bh=$b->importServerPath();$kd=@fopen((file_exists($Bh)?$Bh:"compress.zlib://$Bh.gz"),"rb");$F=($kd?fread($kd,1e6):false);}else$F=get_file("sql_file",true);if(is_string($F)){if(function_exists('memory_get_usage'))@ini_set("memory_limit",max(ini_bytes("memory_limit"),2*strlen($F)+memory_get_usage()+8e6));if($F!=""&&strlen($F)<1e6){$wg=$F.(preg_match("~;[ \t\r\n]*\$~",$F)?"":";");if(!$_d||reset(end($_d))!=$wg){restart_session();$_d[]=array($wg,time());set_session("queries",$Ad);stop_session();}}$zh="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Yb=";";$C=0;$vc=true;$h=connect();if(is_object($h)&&DB!=""){$h->select_db(DB);if($_GET["ns"]!="")set_schema($_GET["ns"],$h);}$tb=0;$_c=array();$Sf='[\'"'.($x=="sql"?'`#':($x=="sqlite"?'`[':($x=="mssql"?'[':''))).']|/\*|-- |$'.($x=="pgsql"?'|\$[^$]*\$':'');$si=microtime(true);parse_str($_COOKIE["adminer_export"],$xa);$mc=$b->dumpFormat();unset($mc["sql"]);while($F!=""){if(!$C&&preg_match("~^$zh*+DELIMITER\\s+(\\S+)~i",$F,$A)){$Yb=$A[1];$F=substr($F,strlen($A[0]));}else{preg_match('('.preg_quote($Yb)."\\s*|$Sf)",$F,$A,PREG_OFFSET_CAPTURE,$C);list($id,$gg)=$A[0];if(!$id&&$kd&&!feof($kd))$F.=fread($kd,1e5);else{if(!$id&&rtrim($F)=="")break;$C=$gg+strlen($id);if($id&&rtrim($id)!=$Yb){while(preg_match('('.($id=='/*'?'\*/':($id=='['?']':(preg_match('~^-- |^#~',$id)?"\n":preg_quote($id)."|\\\\."))).'|$)s',$F,$A,PREG_OFFSET_CAPTURE,$C)){$ah=$A[0][0];if(!$ah&&$kd&&!feof($kd))$F.=fread($kd,1e5);else{$C=$A[0][1]+strlen($ah);if($ah[0]!="\\")break;}}}else{$vc=false;$wg=substr($F,0,$gg);$tb++;$og="<pre id='sql-$tb'><code class='jush-$x'>".$b->sqlCommandQuery($wg)."</code></pre>\n";if($x=="sqlite"&&preg_match("~^$zh*+ATTACH\\b~i",$wg,$A)){echo$og,"<p class='error'>".'ATTACH queries are not supported.'."\n";$_c[]=" <a href='#sql-$tb'>$tb</a>";if($_POST["error_stops"])break;}else{if(!$_POST["only_errors"]){echo$og;ob_flush();flush();}$Fh=microtime(true);if($g->multi_query($wg)&&is_object($h)&&preg_match("~^$zh*+USE\\b~i",$wg))$h->query($wg);do{$G=$g->store_result();if($g->error){echo($_POST["only_errors"]?$og:""),"<p class='error'>".'Error in query'.($g->errno?" ($g->errno)":"").": ".error()."\n";$_c[]=" <a href='#sql-$tb'>$tb</a>";if($_POST["error_stops"])break
2;}else{$gi=" <span class='time'>(".format_time($Fh).")</span>".(strlen($wg)<1000?" <a href='".h(ME)."sql=".urlencode(trim($wg))."'>".'Edit'."</a>":"");$za=$g->affected_rows;$fj=($_POST["only_errors"]?"":$m->warnings());$gj="warnings-$tb";if($fj)$gi.=", <a href='#$gj'>".'Warnings'."</a>".script("qsl('a').onclick = partial(toggle, '$gj');","");$Hc=null;$Ic="explain-$tb";if(is_object($G)){$z=$_POST["limit"];$Ef=select($G,$h,array(),$z);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n";$if=$G->num_rows;echo"<p>".($if?($z&&$if>$z?sprintf('%d / ',$z):"").lang(array('%d row','%d rows'),$if):""),$gi;if($h&&preg_match("~^($zh|\\()*+SELECT\\b~i",$wg)&&($Hc=explain($h,$wg)))echo", <a href='#$Ic'>Explain</a>".script("qsl('a').onclick = partial(toggle, '$Ic');","");$t="export-$tb";echo", <a href='#$t'>".'Export'."</a>".script("qsl('a').onclick = partial(toggle, '$t');","")."<span id='$t' class='hidden'>: ".html_select("output",$b->dumpOutput(),$xa["output"])." ".html_select("format",$mc,$xa["format"])."<input type='hidden' name='query' value='".h($wg)."'>"." <input type='submit' name='export' value='".'Export'."'><input type='hidden' name='token' value='$qi'></span>\n"."</form>\n";}}else{if(preg_match("~^$zh*+(CREATE|DROP|ALTER)$zh++(DATABASE|SCHEMA)\\b~i",$wg)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h($g->info)."'>".lang(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$za)."$gi\n";}echo($fj?"<div id='$gj' class='hidden'>\n$fj</div>\n":"");if($Hc){echo"<div id='$Ic' class='hidden'>\n";select($Hc,$h,$Ef);echo"</div>\n";}}$Fh=microtime(true);}while($g->next_result());}$F=substr($F,$C);$C=0;}}}}if($vc)echo"<p class='message'>".'No commands to execute.'."\n";elseif($_POST["only_errors"]){echo"<p class='message'>".lang(array('%d query executed OK.','%d queries executed OK.'),$tb-count($_c))," <span class='time'>(".format_time($si).")</span>\n";}elseif($_c&&$tb>1)echo"<p class='error'>".'Error in query'.": ".implode("",$_c)."\n";}else
echo"<p class='error'>".upload_error($F)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
';$Ec="<input type='submit' value='".'Execute'."' title='Ctrl+Enter'>";if(!isset($_GET["import"])){$wg=$_GET["sql"];if($_POST)$wg=$_POST["query"];elseif($_GET["history"]=="all")$wg=$_d;elseif($_GET["history"]!="")$wg=$_d[$_GET["history"]][0];echo"<p>";textarea("query",$wg,20);echo
script(($_POST?"":"qs('textarea').focus();\n")."qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '".remove_from_uri("sql|limit|error_stops|only_errors")."');"),"<p>$Ec\n",'Limit rows'.": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";}else{echo"<fieldset><legend>".'File upload'."</legend><div>";$td=(extension_loaded("zlib")?"[.gz]":"");echo(ini_bool("file_uploads")?"SQL$td (&lt; ".ini_get("upload_max_filesize")."B): <input type='file' name='sql_file[]' multiple>\n$Ec":'File uploads are disabled.'),"</div></fieldset>\n";$Id=$b->importServerPath();if($Id){echo"<fieldset><legend>".'From server'."</legend><div>",sprintf('Webserver file %s',"<code>".h($Id)."$td</code>"),' <input type="submit" name="webfile" value="'.'Run file'.'">',"</div></fieldset>\n";}echo"<p>";}echo
checkbox("error_stops",1,($_POST?$_POST["error_stops"]:isset($_GET["import"])),'Stop on error')."\n",checkbox("only_errors",1,($_POST?$_POST["only_errors"]:isset($_GET["import"])),'Show only errors')."\n","<input type='hidden' name='token' value='$qi'>\n";if(!isset($_GET["import"])&&$_d){print_fieldset("history",'History',$_GET["history"]!="");for($X=end($_d);$X;$X=prev($_d)){$y=key($_d);list($wg,$gi,$qc)=$X;echo'<a href="'.h(ME."sql=&history=$y").'">'.'Edit'."</a>"." <span class='time' title='".@date('Y-m-d',$gi)."'>".@date("H:i:s",$gi)."</span>"." <code class='jush-$x'>".shorten_utf8(ltrim(str_replace("\n"," ",str_replace("\r","",preg_replace('~^(#|-- ).*~m','',$wg)))),80,"</code>").($qc?" <span class='time'>($qc)</span>":"")."<br>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$p=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$p):""):where($_GET,$p));$Li=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($p
as$B=>$o){if(!isset($o["privileges"][$Li?"update":"insert"])||$b->fieldName($o)==""||$o["generated"])unset($p[$B]);}if($_POST&&!$n&&!isset($_GET["select"])){$ze=$_POST["referer"];if($_POST["insert"])$ze=($Li?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$ze))$ze=ME."select=".urlencode($a);$w=indexes($a);$Gi=unique_array($_GET["where"],$w);$zg="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($ze,'Item has been deleted.',$m->delete($a,$zg,!$Gi));else{$N=array();foreach($p
as$B=>$o){$X=process_input($o);if($X!==false&&$X!==null)$N[idf_escape($B)]=$X;}if($Li){if(!$N)redirect($ze);queries_redirect($ze,'Item has been updated.',$m->update($a,$N,$zg,!$Gi));if(is_ajax()){page_headers();page_messages($n);exit;}}else{$G=$m->insert($a,$N);$qe=($G?last_id():0);queries_redirect($ze,sprintf('Item%s has been inserted.',($qe?" $qe":"")),$G);}}}$I=null;if($_POST["save"])$I=(array)$_POST["fields"];elseif($Z){$K=array();foreach($p
as$B=>$o){if(isset($o["privileges"]["select"])){$Ga=convert_field($o);if($_POST["clone"]&&$o["auto_increment"])$Ga="''";if($x=="sql"&&preg_match("~enum|set~",$o["type"]))$Ga="1*".idf_escape($B);$K[]=($Ga?"$Ga AS ":"").idf_escape($B);}}$I=array();if(!support("table"))$K=array("*");if($K){$G=$m->select($a,$K,array($Z),$K,array(),(isset($_GET["select"])?2:1));if(!$G)$n=error();else{$I=$G->fetch_assoc();if(!$I)$I=false;}if(isset($_GET["select"])&&(!$I||$G->fetch_assoc()))$I=null;}}if(!support("table")&&!$p){if(!$Z){$G=$m->select($a,array("*"),$Z,array("*"));$I=($G?$G->fetch_assoc():false);if(!$I)$I=array($m->primary=>"");}if($I){foreach($I
as$y=>$X){if(!$Z)$I[$y]=null;$p[$y]=array("field"=>$y,"null"=>($y!=$m->primary),"auto_increment"=>($y==$m->primary));}}}edit_form($a,$p,$I,$Li);}elseif(isset($_GET["create"])){$a=$_GET["create"];$Uf=array();foreach(array('HASH','LINEAR HASH','KEY','LINEAR KEY','RANGE','LIST')as$y)$Uf[$y]=$y;$Gg=referencable_primary($a);$gd=array();foreach($Gg
as$Rh=>$o)$gd[str_replace("`","``",$Rh)."`".str_replace("`","``",$o["field"])]=$Rh;$Hf=array();$R=array();if($a!=""){$Hf=fields($a);$R=table_status($a);if(!$R)$n='No tables.';}$I=$_POST;$I["fields"]=(array)$I["fields"];if($I["auto_increment_col"])$I["fields"][$I["auto_increment_col"]]["auto_increment"]=true;if($_POST)set_adminer_settings(array("comments"=>$_POST["comments"],"defaults"=>$_POST["defaults"]));if($_POST&&!process_fields($I["fields"])&&!$n){if($_POST["drop"])queries_redirect(substr(ME,0,-1),'Table has been dropped.',drop_tables(array($a)));else{$p=array();$Da=array();$Qi=false;$ed=array();$Gf=reset($Hf);$Aa=" FIRST";foreach($I["fields"]as$y=>$o){$q=$gd[$o["type"]];$Ci=($q!==null?$Gg[$q]:$o);if($o["field"]!=""){if(!$o["has_default"])$o["default"]=null;if($y==$I["auto_increment_col"])$o["auto_increment"]=true;$tg=process_field($o,$Ci);$Da[]=array($o["orig"],$tg,$Aa);if($tg!=process_field($Gf,$Gf)){$p[]=array($o["orig"],$tg,$Aa);if($o["orig"]!=""||$Aa)$Qi=true;}if($q!==null)$ed[idf_escape($o["field"])]=($a!=""&&$x!="sqlite"?"ADD":" ").format_foreign_key(array('table'=>$gd[$o["type"]],'source'=>array($o["field"]),'target'=>array($Ci["field"]),'on_delete'=>$o["on_delete"],));$Aa=" AFTER ".idf_escape($o["field"]);}elseif($o["orig"]!=""){$Qi=true;$p[]=array($o["orig"]);}if($o["orig"]!=""){$Gf=next($Hf);if(!$Gf)$Aa="";}}$Wf="";if($Uf[$I["partition_by"]]){$Xf=array();if($I["partition_by"]=='RANGE'||$I["partition_by"]=='LIST'){foreach(array_filter($I["partition_names"])as$y=>$X){$Y=$I["partition_values"][$y];$Xf[]="\n  PARTITION ".idf_escape($X)." VALUES ".($I["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$Wf.="\nPARTITION BY $I[partition_by]($I[partition])".($Xf?" (".implode(",",$Xf)."\n)":($I["partitions"]?" PARTITIONS ".(+$I["partitions"]):""));}elseif(support("partitioning")&&preg_match("~partitioned~",$R["Create_options"]))$Wf.="\nREMOVE PARTITIONING";$Ne='Table has been altered.';if($a==""){cookie("adminer_engine",$I["Engine"]);$Ne='Table has been created.';}$B=trim($I["name"]);queries_redirect(ME.(support("table")?"table=":"select=").urlencode($B),$Ne,alter_table($a,$B,($x=="sqlite"&&($Qi||$ed)?$Da:$p),$ed,($I["Comment"]!=$R["Comment"]?$I["Comment"]:null),($I["Engine"]&&$I["Engine"]!=$R["Engine"]?$I["Engine"]:""),($I["Collation"]&&$I["Collation"]!=$R["Collation"]?$I["Collation"]:""),($I["Auto_increment"]!=""?number($I["Auto_increment"]):""),$Wf));}}page_header(($a!=""?'Alter table':'Create table'),$n,array("table"=>$a),h($a));if(!$_POST){$I=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($U["int"])?"int":(isset($U["integer"])?"integer":"")),"on_update"=>"")),"partition_names"=>array(""),);if($a!=""){$I=$R;$I["name"]=$a;$I["fields"]=array();if(!$_GET["auto_increment"])$I["Auto_increment"]="";foreach($Hf
as$o){$o["has_default"]=isset($o["default"]);$I["fields"][]=$o;}if(support("partitioning")){$ld="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($a);$G=$g->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $ld ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");list($I["partition_by"],$I["partitions"],$I["partition"])=$G->fetch_row();$Xf=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $ld AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$Xf[""]="";$I["partition_names"]=array_keys($Xf);$I["partition_values"]=array_values($Xf);}}}$pb=collations();$xc=engines();foreach($xc
as$wc){if(!strcasecmp($wc,$I["Engine"])){$I["Engine"]=$wc;break;}}echo'
<form action="" method="post" id="form">
<p>
';if(support("columns")||$a==""){echo'Table name: <input name="name" data-maxlength="64" value="',h($I["name"]),'" autocapitalize="off">
';if($a==""&&!$_POST)echo
script("focus(qs('#form')['name']);");echo($xc?"<select name='Engine'>".optionlist(array(""=>"(".'engine'.")")+$xc,$I["Engine"])."</select>".on_help("getTarget(event).value",1).script("qsl('select').onchange = helpClose;"):""),' ',($pb&&!preg_match("~sqlite|mssql~",$x)?html_select("Collation",array(""=>"(".'collation'.")")+$pb,$I["Collation"]):""),' <input type="submit" value="Save">
';}echo'
';if(support("columns")){echo'<div class="scrollable">
<table cellspacing="0" id="edit-fields" class="nowrap">
';edit_fields($I["fields"],$pb,"TABLE",$gd);echo'</table>
',script("editFields();"),'</div>
<p>
Auto Increment: <input type="number" name="Auto_increment" size="6" value="',h($I["Auto_increment"]),'">
',checkbox("defaults",1,($_POST?$_POST["defaults"]:adminer_setting("defaults")),'Default values',"columnShow(this.checked, 5)","jsonly"),(support("comment")?checkbox("comments",1,($_POST?$_POST["comments"]:adminer_setting("comments")),'Comment',"editingCommentsClick(this, true);","jsonly").' <input name="Comment" value="'.h($I["Comment"]).'" data-maxlength="'.(min_version(5.5)?2048:60).'">':''),'<p>
<input type="submit" value="Save">
';}echo'
';if($a!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$a));}if(support("partitioning")){$Vf=preg_match('~RANGE|LIST~',$I["partition_by"]);print_fieldset("partition",'Partition by',$I["partition_by"]);echo'<p>
',"<select name='partition_by'>".optionlist(array(""=>"")+$Uf,$I["partition_by"])."</select>".on_help("getTarget(event).value.replace(/./, 'PARTITION BY \$&')",1).script("qsl('select').onchange = partitionByChange;"),'(<input name="partition" value="',h($I["partition"]),'">)
Partitions: <input type="number" name="partitions" class="size',($Vf||!$I["partition_by"]?" hidden":""),'" value="',h($I["partitions"]),'">
<table cellspacing="0" id="partition-table"',($Vf?"":" class='hidden'"),'>
<thead><tr><th>Partition name<th>Values</thead>
';foreach($I["partition_names"]as$y=>$X){echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'" autocapitalize="off">',($y==count($I["partition_names"])-1?script("qsl('input').oninput = partitionNameChange;"):''),'<td><input name="partition_values[]" value="'.h($I["partition_values"][$y]).'">';}echo'</table>
</div></fieldset>
';}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$Ld=array("PRIMARY","UNIQUE","INDEX");$R=table_status($a,true);if(preg_match('~MyISAM|M?aria'.(min_version(5.6,'10.0.5')?'|InnoDB':'').'~i',$R["Engine"]))$Ld[]="FULLTEXT";if(preg_match('~MyISAM|M?aria'.(min_version(5.7,'10.2.2')?'|InnoDB':'').'~i',$R["Engine"]))$Ld[]="SPATIAL";$w=indexes($a);$mg=array();if($x=="mongo"){$mg=$w["_id_"];unset($Ld[0]);unset($w["_id_"]);}$I=$_POST;if($_POST&&!$n&&!$_POST["add"]&&!$_POST["drop_col"]){$c=array();foreach($I["indexes"]as$v){$B=$v["name"];if(in_array($v["type"],$Ld)){$f=array();$we=array();$ac=array();$N=array();ksort($v["columns"]);foreach($v["columns"]as$y=>$e){if($e!=""){$ve=$v["lengths"][$y];$Zb=$v["descs"][$y];$N[]=idf_escape($e).($ve?"(".(+$ve).")":"").($Zb?" DESC":"");$f[]=$e;$we[]=($ve?$ve:null);$ac[]=$Zb;}}if($f){$Fc=$w[$B];if($Fc){ksort($Fc["columns"]);ksort($Fc["lengths"]);ksort($Fc["descs"]);if($v["type"]==$Fc["type"]&&array_values($Fc["columns"])===$f&&(!$Fc["lengths"]||array_values($Fc["lengths"])===$we)&&array_values($Fc["descs"])===$ac){unset($w[$B]);continue;}}$c[]=array($v["type"],$B,$N);}}}foreach($w
as$B=>$Fc)$c[]=array($Fc["type"],$B,"DROP");if(!$c)redirect(ME."table=".urlencode($a));queries_redirect(ME."table=".urlencode($a),'Indexes have been altered.',alter_indexes($a,$c));}page_header('Indexes',$n,array("table"=>$a),h($a));$p=array_keys(fields($a));if($_POST["add"]){foreach($I["indexes"]as$y=>$v){if($v["columns"][count($v["columns"])]!="")$I["indexes"][$y]["columns"][]="";}$v=end($I["indexes"]);if($v["type"]||array_filter($v["columns"],'strlen'))$I["indexes"][]=array("columns"=>array(1=>""));}if(!$I){foreach($w
as$y=>$v){$w[$y]["name"]=$y;$w[$y]["columns"][]="";}$w[]=array("columns"=>array(1=>""));$I["indexes"]=$w;}echo'
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
<thead><tr>
<th id="label-type">Index Type
<th><input type="submit" class="wayoff">Column (length)
<th id="label-name">Name
<th><noscript>',"<input type='image' class='icon' name='add[0]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'>",'</noscript>
</thead>
';if($mg){echo"<tr><td>PRIMARY<td>";foreach($mg["columns"]as$y=>$e){echo
select_input(" disabled",$p,$e),"<label><input disabled type='checkbox'>".'descending'."</label> ";}echo"<td><td>\n";}$ee=1;foreach($I["indexes"]as$v){if(!$_POST["drop_col"]||$ee!=key($_POST["drop_col"])){echo"<tr><td>".html_select("indexes[$ee][type]",array(-1=>"")+$Ld,$v["type"],($ee==count($I["indexes"])?"indexesAddRow.call(this);":1),"label-type"),"<td>";ksort($v["columns"]);$s=1;foreach($v["columns"]as$y=>$e){echo"<span>".select_input(" name='indexes[$ee][columns][$s]' title='".'Column'."'",($p?array_combine($p,$p):$p),$e,"partial(".($s==count($v["columns"])?"indexesAddColumn":"indexesChangeColumn").", '".js_escape($x=="sql"?"":$_GET["indexes"]."_")."')"),($x=="sql"||$x=="mssql"?"<input type='number' name='indexes[$ee][lengths][$s]' class='size' value='".h($v["lengths"][$y])."' title='".'Length'."'>":""),(support("descidx")?checkbox("indexes[$ee][descs][$s]",1,$v["descs"][$y],'descending'):"")," </span>";$s++;}echo"<td><input name='indexes[$ee][name]' value='".h($v["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n","<td><input type='image' class='icon' name='drop_col[$ee]' src='".h(preg_replace("~\\?.*~","",ME)."?file=cross.gif&version=4.7.7")."' alt='x' title='".'Remove'."'>".script("qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');");}$ee++;}echo'</table>
</div>
<p>
<input type="submit" value="Save">
<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["database"])){$I=$_POST;if($_POST&&!$n&&!isset($_POST["add_x"])){$B=trim($I["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif(DB!==$B){if(DB!=""){$_GET["db"]=$B;queries_redirect(preg_replace('~\bdb=[^&]*&~','',ME)."db=".urlencode($B),'Database has been renamed.',rename_database($B,$I["collation"]));}else{$k=explode("\n",str_replace("\r","",$B));$Lh=true;$pe="";foreach($k
as$l){if(count($k)==1||$l!=""){if(!create_database($l,$I["collation"]))$Lh=false;$pe=$l;}}restart_session();set_session("dbs",null);queries_redirect(ME."db=".urlencode($pe),'Database has been created.',$Lh);}}else{if(!$I["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($B).(preg_match('~^[a-z0-9_]+$~i',$I["collation"])?" COLLATE $I[collation]":""),substr(ME,0,-1),'Database has been altered.');}}page_header(DB!=""?'Alter database':'Create database',$n,array(),h(DB));$pb=collations();$B=DB;if($_POST)$B=$I["name"];elseif(DB!="")$I["collation"]=db_collation(DB,$pb);elseif($x=="sql"){foreach(get_vals("SHOW GRANTS")as$nd){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~',$nd,$A)&&$A[1]){$B=stripcslashes(idf_unescape("`$A[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add_x"]||strpos($B,"\n")?'<textarea id="name" name="name" rows="10" cols="40">'.h($B).'</textarea><br>':'<input name="name" id="name" value="'.h($B).'" data-maxlength="64" autocapitalize="off">')."\n".($pb?html_select("collation",array(""=>"(".'collation'.")")+$pb,$I["collation"]).doc_link(array('sql'=>"charset-charsets.html",'mariadb'=>"supported-character-sets-and-collations/",'mssql'=>"ms187963.aspx",)):""),script("focus(qs('#name'));"),'<input type="submit" value="Save">
';if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',DB))."\n";elseif(!$_POST["add_x"]&&$_GET["db"]=="")echo"<input type='image' class='icon' name='add' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'>\n";echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["scheme"])){$I=$_POST;if($_POST&&!$n){$_=preg_replace('~ns=[^&]*&~','',ME)."ns=";if($_POST["drop"])query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]),$_,'Schema has been dropped.');else{$B=trim($I["name"]);$_.=urlencode($B);if($_GET["ns"]=="")query_redirect("CREATE SCHEMA ".idf_escape($B),$_,'Schema has been created.');elseif($_GET["ns"]!=$B)query_redirect("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($B),$_,'Schema has been altered.');else
redirect($_);}}page_header($_GET["ns"]!=""?'Alter schema':'Create schema',$n);if(!$I)$I["name"]=$_GET["ns"];echo'
<form action="" method="post">
<p><input name="name" id="name" value="',h($I["name"]),'" autocapitalize="off">
',script("focus(qs('#name'));"),'<input type="submit" value="Save">
';if($_GET["ns"]!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$_GET["ns"]))."\n";echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["call"])){$da=($_GET["name"]?$_GET["name"]:$_GET["call"]);page_header('Call'.": ".h($da),$n);$Wg=routine($_GET["call"],(isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));$Jd=array();$Lf=array();foreach($Wg["fields"]as$s=>$o){if(substr($o["inout"],-3)=="OUT")$Lf[$s]="@".idf_escape($o["field"])." AS ".idf_escape($o["field"]);if(!$o["inout"]||substr($o["inout"],0,2)=="IN")$Jd[]=$s;}if(!$n&&$_POST){$ab=array();foreach($Wg["fields"]as$y=>$o){if(in_array($y,$Jd)){$X=process_input($o);if($X===false)$X="''";if(isset($Lf[$y]))$g->query("SET @".idf_escape($o["field"])." = $X");}$ab[]=(isset($Lf[$y])?"@".idf_escape($o["field"]):$X);}$F=(isset($_GET["callf"])?"SELECT":"CALL")." ".table($da)."(".implode(", ",$ab).")";$Fh=microtime(true);$G=$g->multi_query($F);$za=$g->affected_rows;echo$b->selectQuery($F,$Fh,!$G);if(!$G)echo"<p class='error'>".error()."\n";else{$h=connect();if(is_object($h))$h->select_db(DB);do{$G=$g->store_result();if(is_object($G))select($G,$h);else
echo"<p class='message'>".lang(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$za)." <span class='time'>".@date("H:i:s")."</span>\n";}while($g->next_result());if($Lf)select($g->query("SELECT ".implode(", ",$Lf)));}}echo'
<form action="" method="post">
';if($Jd){echo"<table cellspacing='0' class='layout'>\n";foreach($Jd
as$y){$o=$Wg["fields"][$y];$B=$o["field"];echo"<tr><th>".$b->fieldName($o);$Y=$_POST["fields"][$B];if($Y!=""){if($o["type"]=="enum")$Y=+$Y;if($o["type"]=="set")$Y=array_sum($Y);}input($o,$Y,(string)$_POST["function"][$B]);echo"\n";}echo"</table>\n";}echo'<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];$B=$_GET["name"];$I=$_POST;if($_POST&&!$n&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){$Ne=($_POST["drop"]?'Foreign key has been dropped.':($B!=""?'Foreign key has been altered.':'Foreign key has been created.'));$ze=ME."table=".urlencode($a);if(!$_POST["drop"]){$I["source"]=array_filter($I["source"],'strlen');ksort($I["source"]);$Zh=array();foreach($I["source"]as$y=>$X)$Zh[$y]=$I["target"][$y];$I["target"]=$Zh;}if($x=="sqlite")queries_redirect($ze,$Ne,recreate_table($a,$a,array(),array(),array(" $B"=>($_POST["drop"]?"":" ".format_foreign_key($I)))));else{$c="ALTER TABLE ".table($a);$hc="\nDROP ".($x=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($B);if($_POST["drop"])query_redirect($c.$hc,$ze,$Ne);else{query_redirect($c.($B!=""?"$hc,":"")."\nADD".format_foreign_key($I),$ze,$Ne);$n='Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.'."<br>$n";}}}page_header('Foreign key',$n,array("table"=>$a),h($a));if($_POST){ksort($I["source"]);if($_POST["add"])$I["source"][]="";elseif($_POST["change"]||$_POST["change-js"])$I["target"]=array();}elseif($B!=""){$gd=foreign_keys($a);$I=$gd[$B];$I["source"][]="";}else{$I["table"]=$a;$I["source"]=array("");}echo'
<form action="" method="post">
';$yh=array_keys(fields($a));if($I["db"]!="")$g->select_db($I["db"]);if($I["ns"]!="")set_schema($I["ns"]);$Fg=array_keys(array_filter(table_status('',true),'fk_support'));$Zh=($a===$I["table"]?$yh:array_keys(fields(in_array($I["table"],$Fg)?$I["table"]:reset($Fg))));$tf="this.form['change-js'].value = '1'; this.form.submit();";echo"<p>".'Target table'.": ".html_select("table",$Fg,$I["table"],$tf)."\n";if($x=="pgsql")echo'Schema'.": ".html_select("ns",$b->schemas(),$I["ns"]!=""?$I["ns"]:$_GET["ns"],$tf);elseif($x!="sqlite"){$Sb=array();foreach($b->databases()as$l){if(!information_schema($l))$Sb[]=$l;}echo'DB'.": ".html_select("db",$Sb,$I["db"]!=""?$I["db"]:$_GET["db"],$tf);}echo'<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th id="label-source">Source<th id="label-target">Target</thead>
';$ee=0;foreach($I["source"]as$y=>$X){echo"<tr>","<td>".html_select("source[".(+$y)."]",array(-1=>"")+$yh,$X,($ee==count($I["source"])-1?"foreignAddRow.call(this);":1),"label-source"),"<td>".html_select("target[".(+$y)."]",$Zh,$I["target"][$y],1,"label-target");$ee++;}echo'</table>
<p>
ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",$sf),$I["on_delete"]),' ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",$sf),$I["on_update"]),doc_link(array('sql'=>"innodb-foreign-key-constraints.html",'mariadb'=>"foreign-keys/",'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-REFERENCES",'mssql'=>"ms174979.aspx",'oracle'=>"https://docs.oracle.com/cd/B19306_01/server.102/b14200/clauses002.htm#sthref2903",)),'<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$I=$_POST;$If="VIEW";if($x=="pgsql"&&$a!=""){$O=table_status($a);$If=strtoupper($O["Engine"]);}if($_POST&&!$n){$B=trim($I["name"]);$Ga=" AS\n$I[select]";$ze=ME."table=".urlencode($B);$Ne='View has been altered.';$T=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");if(!$_POST["drop"]&&$a==$B&&$x!="sqlite"&&$T=="VIEW"&&$If=="VIEW")query_redirect(($x=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($B).$Ga,$ze,$Ne);else{$bi=$B."_adminer_".uniqid();drop_create("DROP $If ".table($a),"CREATE $T ".table($B).$Ga,"DROP $T ".table($B),"CREATE $T ".table($bi).$Ga,"DROP $T ".table($bi),($_POST["drop"]?substr(ME,0,-1):$ze),'View has been dropped.',$Ne,'View has been created.',$a,$B);}}if(!$_POST&&$a!=""){$I=view($a);$I["name"]=$a;$I["materialized"]=($If!="VIEW");if(!$n)$n=error();}page_header(($a!=""?'Alter view':'Create view'),$n,array("table"=>$a),h($a));echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',(support("materializedview")?" ".checkbox("materialized",1,$I["materialized"],'Materialized view'):""),'<p>';textarea("select",$I["select"]);echo'<p>
<input type="submit" value="Save">
';if($a!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$a));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$Wd=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$Hh=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");$I=$_POST;if($_POST&&!$n){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($I["INTERVAL_FIELD"],$Wd)&&isset($Hh[$I["STATUS"]])){$bh="\nON SCHEDULE ".($I["INTERVAL_VALUE"]?"EVERY ".q($I["INTERVAL_VALUE"])." $I[INTERVAL_FIELD]".($I["STARTS"]?" STARTS ".q($I["STARTS"]):"").($I["ENDS"]?" ENDS ".q($I["ENDS"]):""):"AT ".q($I["STARTS"]))." ON COMPLETION".($I["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$bh.($aa!=$I["EVENT_NAME"]?"\nRENAME TO ".idf_escape($I["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($I["EVENT_NAME"]).$bh)."\n".$Hh[$I["STATUS"]]." COMMENT ".q($I["EVENT_COMMENT"]).rtrim(" DO\n$I[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$n);if(!$I&&$aa!=""){$J=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$I=reset($J);}echo'
<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($I["EVENT_NAME"]),'" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="',h("$I[EXECUTE_AT]$I[STARTS]"),'">
<tr><th title="datetime">End<td><input name="ENDS" value="',h($I["ENDS"]),'">
<tr><th>Every<td><input type="number" name="INTERVAL_VALUE" value="',h($I["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$Wd,$I["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$Hh,$I["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($I["EVENT_COMMENT"]),'" data-maxlength="64">
<tr><th><td>',checkbox("ON_COMPLETION","PRESERVE",$I["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$I["EVENT_DEFINITION"]);echo'<p>
<input type="submit" value="Save">
';if($aa!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$aa));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["procedure"])){$da=($_GET["name"]?$_GET["name"]:$_GET["procedure"]);$Wg=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$I=$_POST;$I["fields"]=(array)$I["fields"];if($_POST&&!process_fields($I["fields"])&&!$n){$Ff=routine($_GET["procedure"],$Wg);$bi="$I[name]_adminer_".uniqid();drop_create("DROP $Wg ".routine_id($da,$Ff),create_routine($Wg,$I),"DROP $Wg ".routine_id($I["name"],$I),create_routine($Wg,array("name"=>$bi)+$I),"DROP $Wg ".routine_id($bi,$I),substr(ME,0,-1),'Routine has been dropped.','Routine has been altered.','Routine has been created.',$da,$I["name"]);}page_header(($da!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($da):(isset($_GET["function"])?'Create function':'Create procedure')),$n);if(!$_POST&&$da!=""){$I=routine($_GET["procedure"],$Wg);$I["name"]=$da;}$pb=get_vals("SHOW CHARACTER SET");sort($pb);$Xg=routine_languages();echo'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',($Xg?'Language'.": ".html_select("language",$Xg,$I["language"])."\n":""),'<input type="submit" value="Save">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
';edit_fields($I["fields"],$pb,$Wg);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",$I["returns"],$pb,array(),($x=="pgsql"?array("void","trigger"):array()));}echo'</table>
',script("editFields();"),'</div>
<p>';textarea("definition",$I["definition"]);echo'<p>
<input type="submit" value="Save">
';if($da!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$da));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["sequence"])){$fa=$_GET["sequence"];$I=$_POST;if($_POST&&!$n){$_=substr(ME,0,-1);$B=trim($I["name"]);if($_POST["drop"])query_redirect("DROP SEQUENCE ".idf_escape($fa),$_,'Sequence has been dropped.');elseif($fa=="")query_redirect("CREATE SEQUENCE ".idf_escape($B),$_,'Sequence has been created.');elseif($fa!=$B)query_redirect("ALTER SEQUENCE ".idf_escape($fa)." RENAME TO ".idf_escape($B),$_,'Sequence has been altered.');else
redirect($_);}page_header($fa!=""?'Alter sequence'.": ".h($fa):'Create sequence',$n);if(!$I)$I["name"]=$fa;echo'
<form action="" method="post">
<p><input name="name" value="',h($I["name"]),'" autocapitalize="off">
<input type="submit" value="Save">
';if($fa!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$fa))."\n";echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["type"])){$ga=$_GET["type"];$I=$_POST;if($_POST&&!$n){$_=substr(ME,0,-1);if($_POST["drop"])query_redirect("DROP TYPE ".idf_escape($ga),$_,'Type has been dropped.');else
query_redirect("CREATE TYPE ".idf_escape(trim($I["name"]))." $I[as]",$_,'Type has been created.');}page_header($ga!=""?'Alter type'.": ".h($ga):'Create type',$n);if(!$I)$I["as"]="AS ";echo'
<form action="" method="post">
<p>
';if($ga!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$ga))."\n";else{echo"<input name='name' value='".h($I['name'])."' autocapitalize='off'>\n";textarea("as",$I["as"]);echo"<p><input type='submit' value='".'Save'."'>\n";}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$B=$_GET["name"];$Ai=trigger_options();$I=(array)trigger($B)+array("Trigger"=>$a."_bi");if($_POST){if(!$n&&in_array($_POST["Timing"],$Ai["Timing"])&&in_array($_POST["Event"],$Ai["Event"])&&in_array($_POST["Type"],$Ai["Type"])){$rf=" ON ".table($a);$hc="DROP TRIGGER ".idf_escape($B).($x=="pgsql"?$rf:"");$ze=ME."table=".urlencode($a);if($_POST["drop"])query_redirect($hc,$ze,'Trigger has been dropped.');else{if($B!="")queries($hc);queries_redirect($ze,($B!=""?'Trigger has been altered.':'Trigger has been created.'),queries(create_trigger($rf,$_POST)));if($B!="")queries(create_trigger($rf,$I+array("Type"=>reset($Ai["Type"]))));}}$I=$_POST;}page_header(($B!=""?'Alter trigger'.": ".h($B):'Create trigger'),$n,array("table"=>$a));echo'
<form action="" method="post" id="form">
<table cellspacing="0" class="layout">
<tr><th>Time<td>',html_select("Timing",$Ai["Timing"],$I["Timing"],"triggerChange(/^".preg_quote($a,"/")."_[ba][iud]$/, '".js_escape($a)."', this.form);"),'<tr><th>Event<td>',html_select("Event",$Ai["Event"],$I["Event"],"this.form['Timing'].onchange();"),(in_array("UPDATE OF",$Ai["Event"])?" <input name='Of' value='".h($I["Of"])."' class='hidden'>":""),'<tr><th>Type<td>',html_select("Type",$Ai["Type"],$I["Type"]),'</table>
<p>Name: <input name="Trigger" value="',h($I["Trigger"]),'" data-maxlength="64" autocapitalize="off">
',script("qs('#form')['Timing'].onchange();"),'<p>';textarea("Statement",$I["Statement"]);echo'<p>
<input type="submit" value="Save">
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["user"])){$ha=$_GET["user"];$rg=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$I){foreach(explode(",",($I["Privilege"]=="Grant option"?"":$I["Context"]))as$Cb)$rg[$Cb][$I["Privilege"]]=$I["Comment"];}$rg["Server Admin"]+=$rg["File access on server"];$rg["Databases"]["Create routine"]=$rg["Procedures"]["Create routine"];unset($rg["Procedures"]["Create routine"]);$rg["Columns"]=array();foreach(array("Select","Insert","Update","References")as$X)$rg["Columns"][$X]=$rg["Tables"][$X];unset($rg["Server Admin"]["Usage"]);foreach($rg["Tables"]as$y=>$X)unset($rg["Databases"][$y]);$af=array();if($_POST){foreach($_POST["objects"]as$y=>$X)$af[$X]=(array)$af[$X]+(array)$_POST["grants"][$y];}$od=array();$pf="";if(isset($_GET["host"])&&($G=$g->query("SHOW GRANTS FOR ".q($ha)."@".q($_GET["host"])))){while($I=$G->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$I[0],$A)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~',$A[1],$Fe,PREG_SET_ORDER)){foreach($Fe
as$X){if($X[1]!="USAGE")$od["$A[2]$X[2]"][$X[1]]=true;if(preg_match('~ WITH GRANT OPTION~',$I[0]))$od["$A[2]$X[2]"]["GRANT OPTION"]=true;}}if(preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~",$I[0],$A))$pf=$A[1];}}if($_POST&&!$n){$qf=(isset($_GET["host"])?q($ha)."@".q($_GET["host"]):"''");if($_POST["drop"])query_redirect("DROP USER $qf",ME."privileges=",'User has been dropped.');else{$cf=q($_POST["user"])."@".q($_POST["host"]);$Zf=$_POST["pass"];if($Zf!=''&&!$_POST["hashed"]&&!min_version(8)){$Zf=$g->result("SELECT PASSWORD(".q($Zf).")");$n=!$Zf;}$Hb=false;if(!$n){if($qf!=$cf){$Hb=queries((min_version(5)?"CREATE USER":"GRANT USAGE ON *.* TO")." $cf IDENTIFIED BY ".(min_version(8)?"":"PASSWORD ").q($Zf));$n=!$Hb;}elseif($Zf!=$pf)queries("SET PASSWORD FOR $cf = ".q($Zf));}if(!$n){$Tg=array();foreach($af
as$kf=>$nd){if(isset($_GET["grant"]))$nd=array_filter($nd);$nd=array_keys($nd);if(isset($_GET["grant"]))$Tg=array_diff(array_keys(array_filter($af[$kf],'strlen')),$nd);elseif($qf==$cf){$nf=array_keys((array)$od[$kf]);$Tg=array_diff($nf,$nd);$nd=array_diff($nd,$nf);unset($od[$kf]);}if(preg_match('~^(.+)\s*(\(.*\))?$~U',$kf,$A)&&(!grant("REVOKE",$Tg,$A[2]," ON $A[1] FROM $cf")||!grant("GRANT",$nd,$A[2]," ON $A[1] TO $cf"))){$n=true;break;}}}if(!$n&&isset($_GET["host"])){if($qf!=$cf)queries("DROP USER $qf");elseif(!isset($_GET["grant"])){foreach($od
as$kf=>$Tg){if(preg_match('~^(.+)(\(.*\))?$~U',$kf,$A))grant("REVOKE",array_keys($Tg),$A[2]," ON $A[1] FROM $cf");}}}queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),!$n);if($Hb)$g->query("DROP USER $cf");}}page_header((isset($_GET["host"])?'Username'.": ".h("$ha@$_GET[host]"):'Create user'),$n,array("privileges"=>array('','Privileges')));if($_POST){$I=$_POST;$od=$af;}else{$I=$_GET+array("host"=>$g->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$I["pass"]=$pf;if($pf!="")$I["hashed"]=true;$od[(DB==""||$od?"":idf_escape(addcslashes(DB,"%_\\"))).".*"]=array();}echo'<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="',h($I["host"]),'" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="',h($I["user"]),'" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="',h($I["pass"]),'" autocomplete="new-password">
';if(!$I["hashed"])echo
script("typePassword(qs('#pass'));");echo(min_version(8)?"":checkbox("hashed",1,$I["hashed"],'Hashed',"typePassword(this.form['pass'], this.checked);")),'</table>

';echo"<table cellspacing='0'>\n","<thead><tr><th colspan='2'>".'Privileges'.doc_link(array('sql'=>"grant.html#priv_level"));$s=0;foreach($od
as$kf=>$nd){echo'<th>'.($kf!="*.*"?"<input name='objects[$s]' value='".h($kf)."' size='10' autocapitalize='off'>":"<input type='hidden' name='objects[$s]' value='*.*' size='10'>*.*");$s++;}echo"</thead>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Columns"=>'Column',"Procedures"=>'Routine',)as$Cb=>$Zb){foreach((array)$rg[$Cb]as$qg=>$ub){echo"<tr".odd()."><td".($Zb?">$Zb<td":" colspan='2'").' lang="en" title="'.h($ub).'">'.h($qg);$s=0;foreach($od
as$kf=>$nd){$B="'grants[$s][".h(strtoupper($qg))."]'";$Y=$nd[strtoupper($qg)];if($Cb=="Server Admin"&&$kf!=(isset($od["*.*"])?"*.*":".*"))echo"<td>";elseif(isset($_GET["grant"]))echo"<td><select name=$B><option><option value='1'".($Y?" selected":"").">".'Grant'."<option value='0'".($Y=="0"?" selected":"").">".'Revoke'."</select>";else{echo"<td align='center'><label class='block'>","<input type='checkbox' name=$B value='1'".($Y?" checked":"").($qg=="All privileges"?" id='grants-$s-all'>":">".($qg=="Grant option"?"":script("qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$s-all'); };"))),"</label>";}$s++;}}}echo"</table>\n",'<p>
<input type="submit" value="Save">
';if(isset($_GET["host"])){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',"$ha@$_GET[host]"));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")&&$_POST&&!$n){$le=0;foreach((array)$_POST["kill"]as$X){if(kill_process($X))$le++;}queries_redirect(ME."processlist=",lang(array('%d process has been killed.','%d processes have been killed.'),$le),$le||!$_POST["kill"]);}page_header('Process list',$n);echo'
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap checkable">
',script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});");$s=-1;foreach(process_list()as$s=>$I){if(!$s){echo"<thead><tr lang='en'>".(support("kill")?"<th>":"");foreach($I
as$y=>$X)echo"<th>$y".doc_link(array('sql'=>"show-processlist.html#processlist_".strtolower($y),'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",'oracle'=>"REFRN30223",));echo"</thead>\n";}echo"<tr".odd().">".(support("kill")?"<td>".checkbox("kill[]",$I[$x=="sql"?"Id":"pid"],0):"");foreach($I
as$y=>$X)echo"<td>".(($x=="sql"&&$y=="Info"&&preg_match("~Query|Killed~",$I["Command"])&&$X!="")||($x=="pgsql"&&$y=="current_query"&&$X!="<IDLE>")||($x=="oracle"&&$y=="sql_text"&&$X!="")?"<code class='jush-$x'>".shorten_utf8($X,100,"</code>").' <a href="'.h(ME.($I["db"]!=""?"db=".urlencode($I["db"])."&":"")."sql=".urlencode($X)).'">'.'Clone'.'</a>':h($X));echo"\n";}echo'</table>
</div>
<p>
';if(support("kill")){echo($s+1)."/".sprintf('%d in total',max_connections()),"<p><input type='submit' value='".'Kill'."'>\n";}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
',script("tableCheck();");}elseif(isset($_GET["select"])){$a=$_GET["select"];$R=table_status1($a);$w=indexes($a);$p=fields($a);$gd=column_foreign_keys($a);$mf=$R["Oid"];parse_str($_COOKIE["adminer_import"],$ya);$Ug=array();$f=array();$fi=null;foreach($p
as$y=>$o){$B=$b->fieldName($o);if(isset($o["privileges"]["select"])&&$B!=""){$f[$y]=html_entity_decode(strip_tags($B),ENT_QUOTES);if(is_shortable($o))$fi=$b->selectLengthProcess();}$Ug+=$o["privileges"];}list($K,$pd)=$b->selectColumnsProcess($f,$w);$ae=count($pd)<count($K);$Z=$b->selectSearchProcess($p,$w);$Bf=$b->selectOrderProcess($p,$w);$z=$b->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Hi=>$I){$Ga=convert_field($p[key($I)]);$K=array($Ga?$Ga:idf_escape(key($I)));$Z[]=where_check($Hi,$p);$H=$m->select($a,$K,$Z,$K);if($H)echo
reset($H->fetch_row());}exit;}$mg=$Ji=null;foreach($w
as$v){if($v["type"]=="PRIMARY"){$mg=array_flip($v["columns"]);$Ji=($K?$mg:array());foreach($Ji
as$y=>$X){if(in_array(idf_escape($y),$K))unset($Ji[$y]);}break;}}if($mf&&!$mg){$mg=$Ji=array($mf=>0);$w[]=array("type"=>"PRIMARY","columns"=>array($mf));}if($_POST&&!$n){$lj=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$gb=array();foreach($_POST["check"]as$db)$gb[]=where_check($db,$p);$lj[]="((".implode(") OR (",$gb)."))";}$lj=($lj?"\nWHERE ".implode(" AND ",$lj):"");if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");$ld=($K?implode(", ",$K):"*").convert_fields($f,$p,$K)."\nFROM ".table($a);$rd=($pd&&$ae?"\nGROUP BY ".implode(", ",$pd):"").($Bf?"\nORDER BY ".implode(", ",$Bf):"");if(!is_array($_POST["check"])||$mg)$F="SELECT $ld$lj$rd";else{$Fi=array();foreach($_POST["check"]as$X)$Fi[]="(SELECT".limit($ld,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$p).$rd,1).")";$F=implode(" UNION ALL ",$Fi);}$b->dumpData($a,"table",$F);exit;}if(!$b->selectEmailProcess($Z,$gd)){if($_POST["save"]||$_POST["delete"]){$G=true;$za=0;$N=array();if(!$_POST["delete"]){foreach($f
as$B=>$X){$X=process_input($p[$B]);if($X!==null&&($_POST["clone"]||$X!==false))$N[idf_escape($B)]=($X!==false?$X:idf_escape($B));}}if($_POST["delete"]||$N){if($_POST["clone"])$F="INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a);if($_POST["all"]||($mg&&is_array($_POST["check"]))||$ae){$G=($_POST["delete"]?$m->delete($a,$lj):($_POST["clone"]?queries("INSERT $F$lj"):$m->update($a,$N,$lj)));$za=$g->affected_rows;}else{foreach((array)$_POST["check"]as$X){$hj="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$p);$G=($_POST["delete"]?$m->delete($a,$hj,1):($_POST["clone"]?queries("INSERT".limit1($a,$F,$hj)):$m->update($a,$N,$hj,1)));if(!$G)break;$za+=$g->affected_rows;}}}$Ne=lang(array('%d item has been affected.','%d items have been affected.'),$za);if($_POST["clone"]&&$G&&$za==1){$qe=last_id();if($qe)$Ne=sprintf('Item%s has been inserted.'," $qe");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$Ne,$G);if(!$_POST["delete"]){edit_form($a,$p,(array)$_POST["fields"],!$_POST["clone"]);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$n='Ctrl+click on a value to modify it.';else{$G=true;$za=0;foreach($_POST["val"]as$Hi=>$I){$N=array();foreach($I
as$y=>$X){$y=bracket_escape($y,1);$N[idf_escape($y)]=(preg_match('~char|text~',$p[$y]["type"])||$X!=""?$b->processInput($p[$y],$X):"NULL");}$G=$m->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($Hi,$p),!$ae&&!$mg," ");if(!$G)break;$za+=$g->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$za),$G);}}elseif(!is_string($Vc=get_file("csv_file",true)))$n=upload_error($Vc);elseif(!preg_match('~~u',$Vc))$n='File must be in UTF-8 encoding.';else{cookie("adminer_import","output=".urlencode($ya["output"])."&format=".urlencode($_POST["separator"]));$G=true;$rb=array_keys($p);preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$Vc,$Fe);$za=count($Fe[0]);$m->begin();$L=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$J=array();foreach($Fe[0]as$y=>$X){preg_match_all("~((?>\"[^\"]*\")+|[^$L]*)$L~",$X.$L,$Ge);if(!$y&&!array_diff($Ge[1],$rb)){$rb=$Ge[1];$za--;}else{$N=array();foreach($Ge[1]as$s=>$nb)$N[idf_escape($rb[$s])]=($nb==""&&$p[$rb[$s]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$nb))));$J[]=$N;}}$G=(!$J||$m->insertUpdate($a,$J,$mg));if($G)$G=$m->commit();queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$za),$G);$m->rollback();}}}$Rh=$b->tableName($R);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $Rh",$n);$N=null;if(isset($Ug["insert"])||!support("table")){$N="";foreach((array)$_GET["where"]as$X){if($gd[$X["col"]]&&count($gd[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!preg_match('~[_%]~',$X["val"]))))$N.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);}}$b->selectLinks($R,$N);if(!$f&&support("table"))echo"<p class='error'>".'Unable to select the table'.($p?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($K,$f);$b->selectSearchPrint($Z,$f,$w);$b->selectOrderPrint($Bf,$f,$w);$b->selectLimitPrint($z);$b->selectLengthPrint($fi);$b->selectActionPrint($w);echo"</form>\n";$D=$_GET["page"];if($D=="last"){$jd=$g->result(count_rows($a,$Z,$ae,$pd));$D=floor(max(0,$jd-1)/$z);}$gh=$K;$qd=$pd;if(!$gh){$gh[]="*";$Db=convert_fields($f,$p,$K);if($Db)$gh[]=substr($Db,2);}foreach($K
as$y=>$X){$o=$p[idf_unescape($X)];if($o&&($Ga=convert_field($o)))$gh[$y]="$Ga AS $X";}if(!$ae&&$Ji){foreach($Ji
as$y=>$X){$gh[]=idf_escape($y);if($qd)$qd[]=idf_escape($y);}}$G=$m->select($a,$gh,$Z,$qd,$Bf,$z,$D,true);if(!$G)echo"<p class='error'>".error()."\n";else{if($x=="mssql"&&$D)$G->seek($z*$D);$uc=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$J=array();while($I=$G->fetch_assoc()){if($D&&$x=="oracle")unset($I["RNUM"]);$J[]=$I;}if($_GET["page"]!="last"&&$z!=""&&$pd&&$ae&&$x=="sql")$jd=$g->result(" SELECT FOUND_ROWS()");if(!$J)echo"<p class='message'>".'No rows.'."\n";else{$Qa=$b->backwardKeys($a,$Rh);echo"<div class='scrollable'>","<table id='table' cellspacing='0' class='nowrap checkable'>",script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"),"<thead><tr>".(!$pd&&$K?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);","")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'Modify'."</a>");$Ze=array();$md=array();reset($K);$Ag=1;foreach($J[0]as$y=>$X){if(!isset($Ji[$y])){$X=$_GET["columns"][key($K)];$o=$p[$K?($X?$X["col"]:current($K)):$y];$B=($o?$b->fieldName($o,$Ag):($X["fun"]?"*":$y));if($B!=""){$Ag++;$Ze[$y]=$B;$e=idf_escape($y);$Dd=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y);$Zb="&desc%5B0%5D=1";echo"<th>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});",""),'<a href="'.h($Dd.($Bf[0]==$e||$Bf[0]==$y||(!$Bf&&$ae&&$pd[0]==$e)?$Zb:'')).'">';echo
apply_sql_function($X["fun"],$B)."</a>";echo"<span class='column hidden'>","<a href='".h($Dd.$Zb)."' title='".'descending'."' class='text'> ‚Üì</a>";if(!$X["fun"]){echo'<a href="#fieldset-search" title="'.'Search'.'" class="text jsonly"> =</a>',script("qsl('a').onclick = partial(selectSearch, '".js_escape($y)."');");}echo"</span>";}$md[$y]=$X["fun"];next($K);}}$we=array();if($_GET["modify"]){foreach($J
as$I){foreach($I
as$y=>$X)$we[$y]=max($we[$y],min(40,strlen(utf8_decode($X))));}}echo($Qa?"<th>".'Relations':"")."</thead>\n";if(is_ajax()){if($z%2==1&&$D%2==1)odd();ob_end_clean();}foreach($b->rowDescriptions($J,$gd)as$Ye=>$I){$Gi=unique_array($J[$Ye],$w);if(!$Gi){$Gi=array();foreach($J[$Ye]as$y=>$X){if(!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~',$y))$Gi[$y]=$X;}}$Hi="";foreach($Gi
as$y=>$X){if(($x=="sql"||$x=="pgsql")&&preg_match('~char|text|enum|set~',$p[$y]["type"])&&strlen($X)>64){$y=(strpos($y,'(')?$y:idf_escape($y));$y="MD5(".($x!='sql'||preg_match("~^utf8~",$p[$y]["collation"])?$y:"CONVERT($y USING ".charset($g).")").")";$X=md5($X);}$Hi.="&".($X!==null?urlencode("where[".bracket_escape($y)."]")."=".urlencode($X):"null%5B%5D=".urlencode($y));}echo"<tr".odd().">".(!$pd&&$K?"":"<td>".checkbox("check[]",substr($Hi,1),in_array(substr($Hi,1),(array)$_POST["check"])).($ae||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Hi)."' class='edit'>".'edit'."</a>"));foreach($I
as$y=>$X){if(isset($Ze[$y])){$o=$p[$y];$X=$m->value($X,$o);if($X!=""&&(!isset($uc[$y])||$uc[$y]!=""))$uc[$y]=(is_mail($X)?$Ze[$y]:"");$_="";if(preg_match('~blob|bytea|raw|file~',$o["type"])&&$X!="")$_=ME.'download='.urlencode($a).'&field='.urlencode($y).$Hi;if(!$_&&$X!==null){foreach((array)$gd[$y]as$q){if(count($gd[$y])==1||end($q["source"])==$y){$_="";foreach($q["source"]as$s=>$yh)$_.=where_link($s,$q["target"][$s],$J[$Ye][$yh]);$_=($q["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.urlencode($q["db"]),ME):ME).'select='.urlencode($q["table"]).$_;if($q["ns"])$_=preg_replace('~([?&]ns=)[^&]+~','\1'.urlencode($q["ns"]),$_);if(count($q["source"])==1)break;}}}if($y=="COUNT(*)"){$_=ME."select=".urlencode($a);$s=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Gi))$_.=where_link($s++,$W["col"],$W["val"],$W["op"]);}foreach($Gi
as$fe=>$W)$_.=where_link($s++,$fe,$W);}$X=select_value($X,$_,$o,$fi);$t=h("val[$Hi][".bracket_escape($y)."]");$Y=$_POST["val"][$Hi][bracket_escape($y)];$pc=!is_array($I[$y])&&is_utf8($X)&&$J[$Ye][$y]==$I[$y]&&!$md[$y];$ei=preg_match('~text|lob~',$o["type"]);echo"<td id='$t'";if(($_GET["modify"]&&$pc)||$Y!==null){$ud=h($Y!==null?$Y:$I[$y]);echo">".($ei?"<textarea name='$t' cols='30' rows='".(substr_count($I[$y],"\n")+1)."'>$ud</textarea>":"<input name='$t' value='$ud' size='$we[$y]'>");}else{$Ae=strpos($X,"<i>‚Ä¶</i>");echo" data-text='".($Ae?2:($ei?1:0))."'".($pc?"":" data-warning='".h('Use edit link to modify this value.')."'").">$X</td>";}}}if($Qa)echo"<td>";$b->backwardKeysPrint($Qa,$J[$Ye]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($J||$D){$Dc=true;if($_GET["page"]!="last"){if($z==""||(count($J)<$z&&($J||!$D)))$jd=($D?$D*$z:0)+count($J);elseif($x!="sql"||!$ae){$jd=($ae?false:found_rows($R,$Z));if($jd<max(1e4,2*($D+1)*$z))$jd=reset(slow_query(count_rows($a,$Z,$ae,$pd)));else$Dc=false;}}$Of=($z!=""&&($jd===false||$jd>$z||$D));if($Of){echo(($jd===false?count($J)+1:$jd-$D*$z)>$z?'<p><a href="'.h(remove_from_uri("page")."&page=".($D+1)).'" class="loadmore">'.'Load more data'.'</a>'.script("qsl('a').onclick = partial(selectLoadMore, ".(+$z).", '".'Loading'."‚Ä¶');",""):''),"\n";}}echo"<div class='footer'><div>\n";if($J||$D){if($Of){$Ie=($jd===false?$D+(count($J)>=$z?2:1):floor(($jd-1)/$z));echo"<fieldset>";if($x!="simpledb"){echo"<legend><a href='".h(remove_from_uri("page"))."'>".'Page'."</a></legend>",script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".'Page'."', '".($D+1)."')); return false; };"),pagination(0,$D).($D>5?" ‚Ä¶":"");for($s=max(1,$D-4);$s<min($Ie,$D+5);$s++)echo
pagination($s,$D);if($Ie>0){echo($D+5<$Ie?" ‚Ä¶":""),($Dc&&$jd!==false?pagination($Ie,$D):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Ie'>".'last'."</a>");}}else{echo"<legend>".'Page'."</legend>",pagination(0,$D).($D>1?" ‚Ä¶":""),($D?pagination($D,$D):""),($Ie>$D?pagination($D+1,$D).($Ie>$D+1?" ‚Ä¶":""):"");}echo"</fieldset>\n";}echo"<fieldset>","<legend>".'Whole result'."</legend>";$ec=($Dc?"":"~ ").$jd;echo
checkbox("all",1,0,($jd!==false?($Dc?"":"~ ").lang(array('%d row','%d rows'),$jd):""),"var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$ec' : checked); selectCount('selected2', this.checked || !checked ? '$ec' : checked);")."\n","</fieldset>\n";if($b->selectCommandPrint()){echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>Modify</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Ctrl+click on a value to modify it.'.'"'),'>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete">',confirm(),'</div></fieldset>
';}$hd=$b->dumpFormat();foreach((array)$_GET["columns"]as$e){if($e["fun"]){unset($hd['sql']);break;}}if($hd){print_fieldset("export",'Export'." <span id='selected2'></span>");$Mf=$b->dumpOutput();echo($Mf?html_select("output",$Mf,$ya["output"])." ":""),html_select("format",$hd,$ya["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($uc,'strlen'),$f);}echo"</div></div>\n";if($b->selectImportPrint()){echo"<div>","<a href='#import'>".'Import'."</a>",script("qsl('a').onclick = partial(toggle, 'import');",""),"<span id='import' class='hidden'>: ","<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ya["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","</span>","</div>";}echo"<input type='hidden' name='token' value='$qi'>\n","</form>\n",(!$pd&&$K?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$O=isset($_GET["status"]);page_header($O?'Status':'Variables');$Yi=($O?show_status():show_variables());if(!$Yi)echo"<p class='message'>".'No rows.'."\n";else{echo"<table cellspacing='0'>\n";foreach($Yi
as$y=>$X){echo"<tr>","<th><code class='jush-".$x.($O?"status":"set")."'>".h($y)."</code>","<td>".h($X);}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: text/javascript; charset=utf-8");if($_GET["script"]=="db"){$Oh=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$B=>$R){json_row("Comment-$B",h($R["Comment"]));if(!is_view($R)){foreach(array("Engine","Collation")as$y)json_row("$y-$B",h($R[$y]));foreach($Oh+array("Auto_increment"=>0,"Rows"=>0)as$y=>$X){if($R[$y]!=""){$X=format_number($R[$y]);json_row("$y-$B",($y=="Rows"&&$X&&$R["Engine"]==($Ah=="pgsql"?"table":"InnoDB")?"~ $X":$X));if(isset($Oh[$y]))$Oh[$y]+=($R["Engine"]!="InnoDB"||$y!="Data_free"?$R[$y]:0);}elseif(array_key_exists($y,$R))json_row("$y-$B");}}}foreach($Oh
as$y=>$X)json_row("sum-$y",format_number($X));json_row("");}elseif($_GET["script"]=="kill")$g->query("KILL ".number($_POST["kill"]));else{foreach(count_tables($b->databases())as$l=>$X){json_row("tables-$l",$X);json_row("size-$l",db_size($l));}json_row("");}exit;}else{$Xh=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($Xh&&!$n&&!$_POST["search"]){$G=true;$Ne="";if($x=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$G=truncate_tables($_POST["tables"]);$Ne='Tables have been truncated.';}elseif($_POST["move"]){$G=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Ne='Tables have been moved.';}elseif($_POST["copy"]){$G=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Ne='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$G=drop_views($_POST["views"]);if($G&&$_POST["tables"])$G=drop_tables($_POST["tables"]);$Ne='Tables have been dropped.';}elseif($x!="sql"){$G=($x=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?"":" ANALYZE"),$_POST["tables"]));$Ne='Tables have been optimized.';}elseif(!$_POST["tables"])$Ne='No tables.';elseif($G=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('idf_escape',$_POST["tables"])))){while($I=$G->fetch_assoc())$Ne.="<b>".h($I["Table"])."</b>: ".h($I["Msg_text"])."<br>";}queries_redirect(substr(ME,0,-1),$Ne,$G);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$n,true);if($b->homepage()){if($_GET["ns"]!==""){echo"<h3 id='tables-views'>".'Tables and views'."</h3>\n";$Wh=tables_list();if(!$Wh)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n";if(support("table")){echo"<fieldset><legend>".'Search data in tables'." <span id='selected2'></span></legend><div>","<input type='search' name='query' value='".h($_POST["query"])."'>",script("qsl('input').onkeydown = partialArg(bodyKeydown, 'search');","")," <input type='submit' name='search' value='".'Search'."'>\n","</div></fieldset>\n";if($_POST["search"]&&$_POST["query"]!=""){$_GET["where"][0]["op"]="LIKE %%";search_tables();}}echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap checkable'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),'<thead><tr class="wrap">','<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);",""),'<th>'.'Table','<td>'.'Engine'.doc_link(array('sql'=>'storage-engines.html')),'<td>'.'Collation'.doc_link(array('sql'=>'charset-charsets.html','mariadb'=>'supported-character-sets-and-collations/')),'<td>'.'Data Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT','oracle'=>'REFRN20286')),'<td>'.'Index Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT')),'<td>'.'Data Free'.doc_link(array('sql'=>'show-table-status.html')),'<td>'.'Auto Increment'.doc_link(array('sql'=>'example-auto-increment.html','mariadb'=>'auto_increment/')),'<td>'.'Rows'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS','oracle'=>'REFRN20286')),(support("comment")?'<td>'.'Comment'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE')):''),"</thead>\n";$S=0;foreach($Wh
as$B=>$T){$bj=($T!==null&&!preg_match('~table~i',$T));$t=h("Table-".$B);echo'<tr'.odd().'><td>'.checkbox(($bj?"views[]":"tables[]"),$B,in_array($B,$Xh,true),"","","",$t),'<th>'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".urlencode($B)."' title='".'Show structure'."' id='$t'>".h($B).'</a>':h($B));if($bj){echo'<td colspan="6"><a href="'.h(ME)."view=".urlencode($B).'" title="'.'Alter view'.'">'.(preg_match('~materialized~i',$T)?'Materialized view':'View').'</a>','<td align="right"><a href="'.h(ME)."select=".urlencode($B).'" title="'.'Select data'.'">?</a>';}else{foreach(array("Engine"=>array(),"Collation"=>array(),"Data_length"=>array("create",'Alter table'),"Index_length"=>array("indexes",'Alter indexes'),"Data_free"=>array("edit",'New item'),"Auto_increment"=>array("auto_increment=1&create",'Alter table'),"Rows"=>array("select",'Select data'),)as$y=>$_){$t=" id='$y-".h($B)."'";echo($_?"<td align='right'>".(support("table")||$y=="Rows"||(support("indexes")&&$y!="Data_length")?"<a href='".h(ME."$_[0]=").urlencode($B)."'$t title='$_[1]'>?</a>":"<span$t>?</span>"):"<td id='$y-".h($B)."'>");}$S++;}echo(support("comment")?"<td id='Comment-".h($B)."'>":"");}echo"<tr><td><th>".sprintf('%d in total',count($Wh)),"<td>".h($x=="sql"?$g->result("SELECT @@storage_engine"):""),"<td>".h(db_collation(DB,collations()));foreach(array("Data_length","Index_length","Data_free")as$y)echo"<td align='right' id='sum-$y'>";echo"</table>\n","</div>\n";if(!information_schema(DB)){echo"<div class='footer'><div>\n";$Vi="<input type='submit' value='".'Vacuum'."'> ".on_help("'VACUUM'");$yf="<input type='submit' name='optimize' value='".'Optimize'."'> ".on_help($x=="sql"?"'OPTIMIZE TABLE'":"'VACUUM OPTIMIZE'");echo"<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>".($x=="sqlite"?$Vi:($x=="pgsql"?$Vi.$yf:($x=="sql"?"<input type='submit' value='".'Analyze'."'> ".on_help("'ANALYZE TABLE'").$yf."<input type='submit' name='check' value='".'Check'."'> ".on_help("'CHECK TABLE'")."<input type='submit' name='repair' value='".'Repair'."'> ".on_help("'REPAIR TABLE'"):"")))."<input type='submit' name='truncate' value='".'Truncate'."'> ".on_help($x=="sqlite"?"'DELETE'":"'TRUNCATE".($x=="pgsql"?"'":" TABLE'")).confirm()."<input type='submit' name='drop' value='".'Drop'."'>".on_help("'DROP TABLE'").confirm()."\n";$k=(support("scheme")?$b->schemas():$b->databases());if(count($k)!=1&&$x!="sqlite"){$l=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo"<p>".'Move to other database'.": ",($k?html_select("target",$k,$l):'<input name="target" value="'.h($l).'" autocapitalize="off">')," <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'> ".checkbox("overwrite",1,$_POST["overwrite"],'overwrite'):""),"\n";}echo"<input type='hidden' name='all' value=''>";echo
script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));".(support("table")?" selectCount('selected2', formChecked(this, /^tables\[/) || $S);":"")." }"),"<input type='hidden' name='token' value='$qi'>\n","</div></fieldset>\n","</div></div>\n";}echo"</form>\n",script("tableCheck();");}echo'<p class="links"><a href="'.h(ME).'create=">'.'Create table'."</a>\n",(support("view")?'<a href="'.h(ME).'view=">'.'Create view'."</a>\n":"");if(support("routine")){echo"<h3 id='routines'>".'Routines'."</h3>\n";$Yg=routines();if($Yg){echo"<table cellspacing='0'>\n",'<thead><tr><th>'.'Name'.'<td>'.'Type'.'<td>'.'Return type'."<td></thead>\n";odd('');foreach($Yg
as$I){$B=($I["SPECIFIC_NAME"]==$I["ROUTINE_NAME"]?"":"&name=".urlencode($I["ROUTINE_NAME"]));echo'<tr'.odd().'>','<th><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($I["SPECIFIC_NAME"]).$B).'">'.h($I["ROUTINE_NAME"]).'</a>','<td>'.h($I["ROUTINE_TYPE"]),'<td>'.h($I["DTD_IDENTIFIER"]),'<td><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($I["SPECIFIC_NAME"]).$B).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p class="links">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a>':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n";}if(support("sequence")){echo"<h3 id='sequences'>".'Sequences'."</h3>\n";$mh=get_vals("SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = current_schema() ORDER BY sequence_name");if($mh){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."</thead>\n";odd('');foreach($mh
as$X)echo"<tr".odd()."><th><a href='".h(ME)."sequence=".urlencode($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links'><a href='".h(ME)."sequence='>".'Create sequence'."</a>\n";}if(support("type")){echo"<h3 id='user-types'>".'User types'."</h3>\n";$Ti=types();if($Ti){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."</thead>\n";odd('');foreach($Ti
as$X)echo"<tr".odd()."><th><a href='".h(ME)."type=".urlencode($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links'><a href='".h(ME)."type='>".'Create type'."</a>\n";}if(support("event")){echo"<h3 id='events'>".'Events'."</h3>\n";$J=get_rows("SHOW EVENTS");if($J){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."<td>".'Schedule'."<td>".'Start'."<td>".'End'."<td></thead>\n";foreach($J
as$I){echo"<tr>","<th>".h($I["Name"]),"<td>".($I["Execute at"]?'At given time'."<td>".$I["Execute at"]:'Every'." ".$I["Interval value"]." ".$I["Interval field"]."<td>$I[Starts]"),"<td>$I[Ends]",'<td><a href="'.h(ME).'event='.urlencode($I["Name"]).'">'.'Alter'.'</a>';}echo"</table>\n";$Bc=$g->result("SELECT @@event_scheduler");if($Bc&&$Bc!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Bc)."\n";}echo'<p class="links"><a href="'.h(ME).'event=">'.'Create event'."</a>\n";}if($Wh)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}}}page_footer();
