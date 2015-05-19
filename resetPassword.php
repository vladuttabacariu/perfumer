<?php include("/includes/head.php"); ?>
<?php include 'includes/pagetop.php';

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: index.php'); } 

$stmt = $db->prepare('SELECT resetToken, resetComplete FROM members WHERE resetToken = :token');
$stmt->execute(array(':token' => $_GET['key']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//if no token from db then kill the page
if(empty($row['resetToken'])){
	$stop = 'Token invalid, va rugam sa folositi linkul de resetare primit in email!';
} elseif($row['resetComplete'] == 'Yes') {
	$stop = 'Parola a fost deja schimbata!';
}

//if form has been submitted process it
if(isset($_POST['submit'])){

	//basic validation
	if(strlen($_POST['password']) < 3){
		$error[] = 'Parola prea scurta!';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirma parola prea scurta!';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Parolele nu se potrivesc!.';
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		try {

			$stmt = $db->prepare("UPDATE members SET password = :hashedpassword, resetComplete = 'Yes'  WHERE resetToken = :token");
			$stmt->execute(array(
				':hashedpassword' => $hashedpassword,
				':token' => $row['resetToken']
			));

			//redirect to index page
			header('Location: loginpage.php?action=resetAccount');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Reset Account';

?> 
 <div id="site">
    <div id="content">
        <div id="reset">
        <h1>Change Password</h1>      
        <hr>       	  
        <?php
            //check for any errors
            if(isset($error)){
                foreach($error as $error){
                    echo '<p class="errorr">'.$error.'</p>';
                }
            }

            //check the action
            
                
                ?>
         <form class="sign_in_new" action="" method="post" >
             <div class="input_cont">
                    <input class="input_text" type="password" autocomplete="off" placeholder="Password" name="password" tabindex="1"></input>
                    <input class="input_text" type="password" autocomplete="off" placeholder="Confirm Password" name="passwordConfirm" tabindex="2"></input>                     
             </div> 
             
             <div class="input_Cont">
                <input type="submit" name="submit" value="Change Password" class="button register" tabindex="3">
             </div>
         </form>
         </div>
    </div>
</div>


<?php include("includes/pagebottom.php"); ?>