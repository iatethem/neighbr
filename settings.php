<?php	session_start();	require_once "./include/config.php";	require_once "./include/inursql.php";	require_once "./include/inurweb.php";	$db = new inursql();	$c = $db->connect($hostname, $username, $password, $database);		// start collect connected website streams		$sql = "SELECT * FROM connections WHERE neighbr = '" . $_SESSION['username'] . "'";	$result = $db->query($sql);		if(mysql_num_rows($result) > 0) {		while($connection = $db->grab($result)) {			if($connection['name'] == 'twitpic') {				$twitpic = 'http://twitpic.com/photos/' . $connection['token'] . '/feed.rss';			}		}	}		// end collect connected website streams	include "./templates/header.php";?>		<div style="padding: 10px;">			<div style="float: left; width: 50px;"><a href="./arguments.callee/"><img src="./img/avatars/arguments.callee.jpg" alt="neighbr: arguments.callee" style="width: 50px; height: 50px; border: 0px;" /></a></div>			<div style="float: right; width: 500px;">			<div>				<div class="post-header"><div style="padding: 5px;">Change Your Avatar</div></div>				<div class="post-content">					<div style="padding: 5px;">						test					</div>				</div>			</div>			</div><div style="clear: both;"></div>		</div>		<div style="padding: 10px;">			<div>				<div class="post-header"><div style="padding: 5px;">Connected Websites</div></div>				<div class="post-content">					<div style="padding: 5px;"><?php if(isset($twitpic)) { ?>						<table width="100%" cellspacing="0" cellpadding="0" border="0">						<tr>							<td width="25%">TWITPIC.COM</td>							<td width="75%"><?php echo $twitpic; ?></td>						</tr>						</table><?php } ?>					</div>				</div>			</div><br />			<div>				<div class="post-header"><div style="padding: 5px;">Heading</div></div>				<div class="post-content">					<div style="padding: 5px;"><pre><?php	function blobScan($file) {	if(strlen($file)>1 && $file[0]=='B' && $file[1]=='M')		return "bmp";	if(strlen($file)>2 &&  $file[0]=='G' && $file[1]=='I' && $file[2]=='F')		return "gif";	if(strlen($file)>3 &&  ord($file[0])==0xff && ord($file[1])==0xd8 && ord($file[2])==0xff)		return "jpg";	if(strlen($file)>8 &&  ord($file[0])==0x89 && ord($file[1])==0x50 && ord($file[2])==0x4e && ord($file[3])==0x47					   &&  ord($file[4])==0x0d && ord($file[5])==0x0a && ord($file[6])==0x1a && ord($file[7])==0x0a)		return "png";	}	if(isset($twitpic)) {				$xml = simplexml_load_file($twitpic, 'SimpleXMLElement', LIBXML_NOCDATA);				foreach($xml->channel->item as $pic) {						// echo $pic->pubDate . "<br />";						$sql = "SELECT * FROM posts WHERE neighbr = '" . $_SESSION['username'] . "' AND type = 'image' AND timestamp = '" . date("Y-m-d H:i:s", strtotime($pic->pubDate)) . "'";			$result = $db->query($sql);						if(mysql_num_rows($result) == 0) {								$explode = explode("/", $pic->link);				$token = end($explode);								$image = new GetWebObject("twitpic.com", 80, "/show/full/" . $token);				$fetch = $image->get_header();								$pieces = explode("?", $fetch['Location']);				$remote = explode(".", $pieces[0]);				$crumbs = explode("/", $pieces[0]);				// echo $fetch['Location'];				// echo "./images/" . $token . "." . end($remote) . "<br />"; continue;								$name = preg_replace('/[^0-9a-z\-\_\.]/i', '', end($crumbs));				$file = "./images/" . $name;				$data = file_get_contents($fetch['Location']);				$note = sanitize(implode(" ", array_slice(explode(" ", $pic->title), 2)));				$handle = fopen($file, "w+");				if(fwrite($handle, $data)) {					fclose($handle);					$query = "INSERT INTO posts (neighbr, type, source, title, note, timestamp) VALUES ('" . $_SESSION['username'] . "', 'image', 'http://yoursite.com/images/" . $name . "', '" . $name . "', '" . $note . "', '" . date("Y-m-d H:i:s", strtotime($pic->pubDate)) . "')";					$fetch = $db->query($query);					if($fetch) { echo "Twitpic inserted.<br />"; }				}				// print_r($fetch);								// $headers = filetype(file_get_contents("http://twitpic.com/show/full/" . $token));												//echo basename("http://twitpic.com/show/full/" . $token) . "<br />"; continue;				/*				$image = new GetImage;				$image->source = "http://twitpic.com/show/full/" . $token;				$image->save_to = "./images/";								if($image->download('gd')) {					echo "yay" . "<br />";				} else {					echo "awe" . "<br />";				}				*/				// $query = "INSERT INTO posts (neighbr, type, source, title, note, timestamp) VALUES ('" . $_SESSION['username'] . "', 'image', )";							}					}		// $link = str_replace($username.": <br>","",$xml->channel->item[$i]->description);		// print_r($xml);			}?>					</pre></div>				</div>			</div>		</div><?php include "./templates/footer.php"; ?>