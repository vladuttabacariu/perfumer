<?php include 'includes/head.php'; ?>
<?php include 'includes/pagetop.php'; 

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: index.php'); } 

//if form has been submitted process it
if(isset($_POST['submit'])){

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['email'])){
			$error[] = 'Email provided is not on recognised.';
		}
			
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activasion code
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $db->prepare("UPDATE members SET resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
				':email' => $row['email'],
				':token' => $token
			));

			//send email
			$to = $row['email'];
			$subject = "Password Reset";
			$body = "Someone requested that the password be reset. \n\nIf this was a mistake, just ignore this email and nothing will happen.\n\nTo reset your password, visit the following address: ".DIR."resetPassword.php?key=$token";
			$additionalheaders = "From: <".SITEEMAIL.">\r\n";
			$additionalheaders .= "Reply-To: $".SITEEMAIL."";
			mail($to, $subject, $body, $additionalheaders);

			//redirect to index page
			header('Location: loginpage.php?action=reset');
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
            <h1>Schimba parola</h1>   
            <p><a href='index.php'>Intoarce-te la prima pagina!</a></p>
    	  
            <?php
                //check for any errors
                if(isset($error)){
                    foreach($error as $error){
                        echo '<p class="errorr">'.$error.'</p>';
                    }
                }

                if(isset($_GET['action'])){

                    //check the action
                    switch ($_GET['action']) {
                        case 'active':
                            echo "<h2 class='success'>Contul dumneavoastra este activ. Va puteti loga!</h2>";
                            break;
                        case 'reset':
                            echo "<h2 class='success'>Verifica email-ul pentru linkul de resetare parola!</h2>";
                            break;
                    }
                }
                ?>
             <form class="sign_in_new" action="" method="post" >

                 <div class="input">
                        <input class="input_text" type="email" placeholder="Email" name="email" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="1"></input>
                 </div>
                 <div class="input">
                    <input type="submit" name="submit" value="Schimba parola" class="button register" tabindex="2">
                 </div>
             </form>
         </div>
    </div>
</div>


<?php include("includes/pagebottom.php"); ?>