<?php 

/////////////////////////////////////////////////////////////

$csvFile = file('data.csv');

$data = array_map('str_getcsv', $csvFile);

function UserCount(){
  $csvFile = file('users.csv');
  $i = 0;
  $data = [];
  foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
    $i = $i + 1;
  }
  return $i;
}

function MessageCount(){
  $csvFile = file('data.csv');
  $i = 0;
  $data = [];
  foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
    $i = $i + 1;
  }
  return $i;
}

/*function NotifCount(){
  $csvFile = file('notifs.csv');
  $i = 0;
  $data = [];
  foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
    $i = $i + 1;
  }
  return $i;
}*/

function Encrypter($data,$encrypt){
  $ciphering = "AES-128-CTR";
  $iv_length = openssl_cipher_iv_length($ciphering);
  $options = 0;
  $encryption_iv = '1234567891011121';
  $encryption_key = "&";
  if($encrypt == 1){
    $encryption = openssl_encrypt($data, $ciphering, $encryption_key, $options, $encryption_iv);
    return $encryption;
  } else {
    $decryption = openssl_decrypt ($data, $ciphering, $encryption_key, $options, $encryption_iv);
    return $decryption;
  }
}

/*function CompareIDtoName($input, $yesID){
  if($yesID = 1){
    $ID = $input;
    $csvFile = file('users.csv');
    $data = [];
    $i = 0;
    foreach ($csvFile as $line) {
      $data[] = str_getcsv($line);
      if($i == $ID){
        return $data[$ID][0]; //encrypted still
      }
      $i++;
    }
  } else {
    $Name = $input;
    $csvFile = file('users.csv');
    $data = [];
    $i = 0;
    foreach ($csvFile as $line) {
      $data[] = str_getcsv($line);
      if($data[$i][0] == $Name){
        return $i;
      }
      $i++;
    } 
  }
  return '';
}*/

/////////////////////////////////////////////////////////////

$userID = 0; //only used for setup

function LogOrReg(){
  mainHeader();
  echo
  '<div class="LogRegClass">
  </br></br></br>
  <p>Do you want to login or register?</p>
  <form action="index.php" method="post">
    <input type="submit" name="Login" value="Login" id="LogReg"/>
    <input type="submit" name="Register" value="Register" id="LogReg"/>
  </form></div>
  <footer><ul id="logreglist"><li> We take no responsibility for what happens in this site.</li><li>The files/conversations here are cleared often to keep the website as fast as possible.</li></ul>
  </footer>';
  bottomInfo();
}

function Register($takenName){
  mainHeader();
  echo
  '<div id="Register">
  <span id="backalign"><a href="index.php"><button type="button" id="backButton">Back</button></a></span>
</br></br></br></br>
  <form method="post">
  <table id="inputTable"><tr><td></td><td><p id="Subtitle">Register</p></td><td></td></tr><tr>
  <td><label for="NewName">Username:</label></td>
  <td><input type="text" name="NewName" placeholder="Username" required autocomplete="off" id="Input"></td></tr><tr>
  <td><label for="NewPass" required autocomplete="off" >Password:</label></td>    
  <td><input type="password" name="NewPass" placeholder="Password" required autocomplete="off" id="Input"></td>
  </input>
  <td><input type="submit" name="SubmitNewUser" id="Input"></td></tr></table>
  </form>';
  if($takenName == 1){
    echo '<p id="error">Username taken, try again.</p>';
  } else {
    echo '</br>';
  }
  echo '<ul id="reglist"><li>Use letters or numbers, nothing else will work (This includes spaces).</li><li>If you forget your login details, we can not get them, so write them down.</li><li>While in a chat you should press "submit" every so often to see others messages easily, it is messy but it is easy.</li></ul>
  </div>';
  bottomInfo();
}

function Login($wrongName){
  mainHeader();
  echo
  '<div id="Login">
  <span id="backalign"><a href="index.php"><button type="button" id="backButton">Back</button></a></span>
</br></br></br></br>
  <form method="post">
  <table id="inputTable"><tr><td></td><td><p id="Subtitle">Login</p></td><td></td></tr><tr>
  <td><label for="LoginName">Username:</label></td>
  <td><input type="text" name="LoginName" placeholder="Username" required autocomplete="off" id="Input"> </td></tr><tr><td>
  <label for="LoginPass" required autocomplete="off">Password:</label>    </td><td>
  <input type="password" name="LoginPass" placeholder="Password" required autocomplete="off" id="Input"></td>
  </input>
  <td><input type="submit" name="SubmitLogin" id="Input"></td></tr></table>
  </form>
  </div>';
  if($wrongName == 1){
    echo '<p id="error">Your username or password is incorrect.</p>';
  }
  bottomInfo();
}

function LoginTest($name,$pass){
  global $userID;
  $csvFile = file('users.csv');
  $data = [];
  $i = 0;
  foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
    if($data[$i][0] == $name AND $data[$i][1] == $pass){
      $userID = $i;
      $set = True;
    }
    $i = $i + 1;
  }
  if($set == True){
    return True;
  } else {
    return False;
  }
}

if(isset($_POST['SubmitLogin'])){  if(LoginTest(Encrypter($_POST['LoginName'],1),Encrypter($_POST['LoginPass'],1)) == True){
    $_SESSION['LoginName'] = stripslashes(htmlspecialchars($_POST['LoginName']));
  } else {
    $usernameWrong = True;
  }
}

if(isset($_POST['SubmitNewUser'])){

  $Newname = stripslashes(htmlspecialchars($_POST['NewName']));
  $Newpass = $_POST['NewPass'];
  $babycsvFile = file('users.csv');
  $babydata = [];
  $i = 0;
  foreach ($babycsvFile as $line) {
    $babydata[] = str_getcsv($line);
    if($babydata[$i][0] == Encrypter($Newname,1)){
      $babynotfree = True;
    }
    $i = $i + 1;
  }
  if($babynotfree != True){
    $file = fopen("users.csv", "a");

    $print = Encrypter($Newname,1).",".Encrypter($Newpass,1)."\n";

    fwrite($file, $print);
    fclose($file);
  } else {
    $usernameTaken = True;
  }  
}

/////////////////////////////////////////////////////////////

function MessageChoice(){
  mainHeader();
  if(!isset($_GET['PostLogin'])){
    global $userID;
  } else {
    $userID = $_GET['userid'];
  }
  $csvFile = file('users.csv');
  $data = [];
  $i = 0;
  $k = 0;
  echo '<span id="backalign"><a href="index.php"><button type="button" id="backButton">Logout</button></a></span><p id="miniTitle">-Games-</p><table id="gameTable"><tr><td><a href="http://giacomotag.io/hb/" target="_blank"><button type="button" id="gameButton">Hexa Battle</button></a></td><td><a href="https://live-chess.jjroley.repl.co/" target="_blank"><button type="button" id="gameButton">Chess</button></a></td></tr></table><p id="miniTitle">-Users-</p>';
  echo '<table id="userTable">';
  foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
    if($i != $userID){
      if($k == 0){
        echo '<tr><td><a href="index.php?otheruser='.Encrypter($i,1).'&userid='.$userID.'&otherusername='.$data[$i][0].'&username='.substr($csvFile[$userID],0,strpos($csvFile[$userID],",")).'"><button type="button" class="userButton">'.Encrypter($data[$i][0],0).'</button></a></td>';
      $k = 1;
      } else if($k == 1 OR $k == 2 OR $k == 3){
        echo '<td><a href="index.php?otheruser='.Encrypter($i,1).'&userid='.$userID.'&otherusername='.$data[$i][0].'&username='.substr($csvFile[$userID],0,strpos($csvFile[$userID],",")).'"><button type="button" class="userButton">'.Encrypter($data[$i][0],0).'</button></a></td>';
      $k++;
      } else if($k == 4){
        echo '<td><a href="index.php?otheruser='.Encrypter($i,1).'&userid='.$userID.'&otherusername='.$data[$i][0].'&username='.substr($csvFile[$userID],0,strpos($csvFile[$userID],",")).'"><button type="button" class="userButton">'.Encrypter($data[$i][0],0).'</button></a></td></tr>';
      $k = 0;
      }
    }
    $i = $i + 1;
  }
  echo '</table>';
  bottomInfo();
}

function MessageExists($messageID){
  $temp = array('','');
  $count = MessageCount()-1;
  if($messageID > $count){
    $csvFile = fopen('data.csv', 'a');
    while($messageID > $count){
      fputcsv($csvFile, $temp);
      $count = MessageCount()-1;
    }
    fclose($csvFile);
  } 
}

function bottomInfo(){
  echo '<div id="bottomInfo"><span>If you have any issues, improvements or games, mail us here: <u>discardteam@outlook.com</u></span></div>';
}

function mainHeader(){
  echo '<div id="mainHeader"><table><tr><td><img src="internal/discardLogo.PNG" id="headerIMG"></td><td><h1 id="headerText">Discard</h1></td></tr></table></div>';
}

/////////////////////////////////////////////////////////////

?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Discard</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php

    ob_start();
  if(!isset($_GET['otheruser'])){
    if(!isset($_SESSION['LoginName']) AND !isset($_GET['PostLogin'])){
      if($usernameTaken == True){
        ob_end_clean();
        ob_start();
        Register(1);
        $usernameTaken = False;
      } else if($usernameWrong == True){
        ob_end_clean();
        ob_start();
        Login(1);
      } else {
        ob_end_clean();
        ob_start();
        LogOrReg();
      }
        
      if(isset($_POST['Register'])){
        ob_end_clean();
        ob_start();
        Register(0);
      }
      
      if(isset($_POST['Login'])){
        ob_end_clean();
        ob_start();
        Login(0);
      }
    } else {
      ob_end_clean();
      ob_start();
      MessageChoice();
    }
  } else {

    //everything main goes here
    
    ob_end_clean();
    ob_start();

    //these are all very helpful
    $mUserID = $_GET['userid']; //with oUserId to find messages
    $mUserName = Encrypter($_GET['username'],0); // to add who said what
    $oUserID = Encrypter($_GET['otheruser'],0);
    $oUserName = Encrypter($_GET['otherusername'],0); // same as mUserName
    $mMessageID = ($mUserID ** 3)+($oUserID ** 3); //big

    /*
    echo ' Your User ID = '.$mUserID;
    echo ' Your User Name = '.$mUserName;
    echo ' Other User ID = '.$oUserID;
    echo ' Other User Name = '.$oUserName;
    echo ' Main Message ID = '.$mMessageID;
    echo ' User Count total = '.UserCount();
    */
    
    if(filesize('data.csv') > 50000){
      unlink('data.csv');
      $filesizeReset = fopen('data.csv','w');
      fclose($filesizeReset);
      $files = glob('upload/*'); // get all file names
      foreach($files as $file){ // iterate files
        if(is_file($file)) {
          unlink($file); // delete file               
        }
      }
    }
    MessageExists($mMessageID);
    mainHeader();
    
    echo '<span id="backalign"><a href="index.php?PostLogin=True&userid='.$mUserID.'"><button type="button" id="backButton">
      Back</button></a></span>';
    ?>

    <div id="ChatBox">
    <?php
    //section printout
    if(isset($_POST['SubmitInput'])){
      if(isset($_POST['Input']) AND $_POST['Input'] != ''){
        $messageAddon = "<div id='userMessage'><span class='chat-time'>".date("g:i A")."</span> <strong class='names' id=type".($mUserID % 6).">".$mUserName."</strong><span> ".$_POST['Input']."<br></span><div>";
      } else if(isset($_FILES['userFile'])){
        $target_Path = str_replace(' ', '', basename( $_FILES['userFile']['name'] ));
        move_uploaded_file( $_FILES['userFile']['tmp_name'], 'upload/'.$target_Path );
        $messageAddon = "<div id='userMessage'><span class='chat-time'>".date("g:i A")."</span> <strong class='names' id=type".($mUserID % 6).">".$mUserName."</strong><span> </span><img src=upload/".$target_Path." class='postimage'><br></div>";
      } else {
        $messageAddon = '';
      }
      $csvFile = file('data.csv');
      $data = array_map('str_getcsv', $csvFile);
      $data[$mMessageID][0] = $data[$mMessageID][0].$messageAddon;
      unlink('data.csv');
      $f = fopen('data.csv', 'w');
      foreach ($data as $row) {
	      fputcsv($f, $row);  
      }
      fclose($f);
    }
    
    if($data[$mMessageID][0] == ''){
      echo '<p id="warning">Make sure your first message is text. It breaks for some reason otherwise</p></br>';
    } else {
      echo $data[$mMessageID][0];
    }
    
    ?>
    </div>
    <div id="InputBox"> <!-- for some reason this is in the other div -->
    <form action='' method="post" enctype='multipart/form-data'>
    <table><tr><td>
      <input type="text" name="Input" placeholder="Type here" id="submittext"></td><td><div class="file"><input type='file' name='userFile' accept="image/*" class="filesubmit"></div></td><td>
      <input type="submit" name="SubmitInput" id="submitmessage"></td></tr></table>
    </form>
    </div>
    <?php
    bottomInfo();

    ?>
  </body>
</html>
<?php
  }
?>