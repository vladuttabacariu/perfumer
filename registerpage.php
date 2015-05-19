<?php include 'includes/head.php'; ?>
<?php include 'includes/pagetop.php'; ?>

<?php

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: index.php'); } 

//if form has been submitted process it
if(isset($_POST['submit'])){

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'Numele de utilizator este prea scurt!';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Numele de utilizator este deja folosit!';
		}
			
	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Parola este prea scurta!';
	}

	if(strlen($_POST['confirm_password']) < 3){
		$error[] = 'Confirma parola este prea scurta!';
	}

	if($_POST['password'] != $_POST['confirm_password']){
		$error[] = 'Parolele nu se potrivesc';
	}

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Introdu o adresa de email valida!';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Adresa de email pe care ai introdus-o este deja folosita!';
		}
			
	}
	
	if($_POST['sex'] == '0'){
		$error[] = 'Alege sex!';
	}
	if($_POST['state'] == '0'){
		$error[] = 'Alege judet!';
	}
	if(strlen($_POST['city']) < 1){
		$error[] = 'Introdu un oras!';
	}
	if(strlen($_POST['adress']) < 1){
		$error[] = 'Introdu o adresa!';
	}
	if(strlen($_POST['zipcode']) < 6){
		$error[] = 'Introdu codul postal (6 cifre)!';
	}
	if(strlen($_POST['telephoneno']) < 10){
		$error[] = 'Numar de telefon invalid!';
	}
	if($_POST['preference'] == '0'){
		$error[] = 'Alege preferinte arome!';
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));
		$date = date('Y-m-d H:i:s');
		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,name,sex,state,city,adress,zipcode,telephoneno,preference,datetime,active) VALUES (:username, :password, :email, :name, :sex, :state, :city, :adress, :zipcode, :telephoneno, :preference, :datetime, :active)');
			$stmt->execute(array(
				':username' => $_POST['username'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':name' => $_POST['name'],
				':sex' => $_POST['sex'],
				':state' => $_POST['state'],
				':city' => $_POST['city'],
				':adress' => $_POST['adress'],
				':zipcode' => $_POST['zipcode'],
				':telephoneno' => $_POST['telephoneno'],
				':preference' => $_POST['preference'],
				':datetime' => $date,
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "Thank you for registering at tubeplay site.\n\n To activate your account, please click on this link:\n\n ".DIR."activate.php?x=$id&y=$activasion\n\n Regards Site Admin \n\n";
			$additionalheaders = "From: <".SITEEMAIL.">\r\n";
			$additionalheaders .= "Reply-To: $".SITEEMAIL."";
			mail($to, $subject, $body, $additionalheaders);

			//redirect to index page
			header('Location: registerpage.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Sign In';

?>
<div id="site">
    <div id="content">
    	<div id="register">
        <h1>Inregistreaza-te</h1>   
        <p style="margin-bottom:10px;">Ai deja cont? <a href='loginpage.php'>Login</a></p>   
        <h2 style="font-size:15px;"> Toate campurile sunt obligatorii</h2>   	  
        <?php
            //check for any errors
            if(isset($error)){
                foreach($error as $error){
                    echo '<p class="error">*'.$error.'</p>';
                }
            }

            //if action is joined show sucess
            if(isset($_GET['action']) && $_GET['action'] == 'joined'){
                echo "<h2 class='success'>Registration successful, please check your email to activate your account.</h2>";
            }
            ?>
         <form class="register_new" action="" method="post" >
             <div class="input">
                    <input class="input_text" type="text" placeholder="Nume utilizator" name="username"  value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1"></input>
                    
             </div>
             <div class="input">
                    <input class="input_text" type="email" placeholder="Email" name="email" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2"></input>
             </div>
             <div class="input">
                    <input class="input_text" type="password" autocomplete="off" placeholder="Parola" name="password" tabindex="3"></input>
                    <input class="input_text" type="password" autocomplete="off" placeholder="Confirma parola" name="confirm_password" tabindex="4"></input>                     
             </div> 
             
             <div class="input">
                    <input class="input_text" type="text" placeholder="Nume si prenume" name="name"  value="<?php if(isset($error)){ echo $_POST['name']; } ?>" tabindex="5"></input>
             </div>
             <div class="input">
             <label class="select_label">
                 <select class="select" name="sex" tabindex="6">
                 	<option value="0">Alege sex</option>
                    <option value="male">Masculin</option>
                    <option value="female">Feminin</option>         
                 </select>
             </label>
             </div>
			 <div class="input">
             	<label class="select_label">
                  <select class="select" name="state" tabindex="7">
                    <option value="0">Alege judet</option>
                    <option value="Alba">Alba</option>
                    <option value="Arad">Arad</option>
                    <option value="Arges">Arges</option>
                    <option value="Bacau">Bacau</option>
                    <option value="Bihor">Bihor</option>
                    <option value="Bistrita Nasaud">Bistrita Nasaud</option>
                    <option value="Botosani">Botosani</option>
                    <option value="Brasov">Brasov</option>
                    <option value="Braila">Braila</option>
                    <option value="Bucuresti">Bucuresti</option>
                    <option value="Buzau">Buzau</option>
                    <option value="Caras Severin">Caras Severin</option>
                    <option value="Calarasi">Calarasi</option>
                    <option value="Cluj">Cluj</option>
                    <option value="Constanta">Constanta</option>
                    <option value="Covasna">Covasna</option>
                    <option value="Dambovita">Dambovita</option>
                    <option value="Dolj">Dolj</option>
                    <option value="Galati">Galati</option>
                    <option value="Giurgiu">Giurgiu</option>
                    <option value="Gorj">Gorj</option>
                    <option value="Harghita">Harghita</option>
                    <option value="Hunedoara">Hunedoara</option>
                    <option value="Ialomita">Ialomita</option>
                    <option value="Iasi">Iasi</option>
                    <option value="Ilfov">Ilfov</option>
                    <option value="Maramures">Maramures</option>
                    <option value="Mehedinti">Mehedinti</option>
                    <option value="Mures">Mures</option>
                    <option value="Neamt">Neamt</option>
                    <option value="Olt">Olt</option>
                    <option value="Prahova">Prahova</option>
                    <option value="Satu Mare">Satu Mare</option>
                    <option value="Salaj">Salaj</option>
                    <option value="Sibiu">Sibiu</option>
                    <option value="Suceava">Suceava</option>
                    <option value="Teleorman">Teleorman</option>
                    <option value="Timis">Timis</option>
                    <option value="Tulcea">Tulcea</option>
                    <option value="Vaslui">Vaslui</option>
                    <option value="Valcea">Valcea</option>
                    <option value="Vrancea">Vrancea</option>
   				 	</select>   
                 </label>   
             </div>
             <div class="input">
                    <input class="input_text" type="text" placeholder="Oras" name="city"  value="<?php if(isset($error)){ echo $_POST['city']; } ?>" tabindex="8"></input>
             </div>
             <div class="input">
                    <input class="input_text" type="text" placeholder="Adresa" name="adress"  value="<?php if(isset($error)){ echo $_POST['adress']; } ?>" tabindex="9"></input>
             </div>
             <div class="input">
                    <input class="input_text" type="text" placeholder="Cod postal" name="zipcode"  value="<?php if(isset($error)){ echo $_POST['zipcode']; } ?>" tabindex="10"></input>
             </div>
             <div class="input">
                    <input class="input_text" type="text" placeholder="Numar de telefon" name="telephoneno"  value="<?php if(isset($error)){ echo $_POST['telephoneno']; } ?>" tabindex="11"></input>
             </div>
             <div class="input">
 
             	<label class="select_label">
                	<select class="select" name="preference" tabindex="12">
                    	<option value="0">Alege preferinte arome</option>
                        <option value="female" disabled>Femei -- </option>
                        <?php
						try {
							$stmt = $db->prepare('SELECT name, flavours FROM essences WHERE sex = "female" ORDER BY name');
							$stmt->execute();
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<option value="'.$row['name'].'">'.$row['name'].' - '.$row['flavours'].'</option>';
							
							}
							} catch(PDOException $e) {
		    				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
							}
						?>   
						<option value="male" disabled>Barbati -- </option>
                        <?php
						try {
							$stmt = $db->prepare('SELECT name, flavours FROM essences WHERE sex = "male" ORDER BY name');
							$stmt->execute();
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<option value="'.$row['name'].'">'.$row['name'].' - '.$row['flavours'].'</option>';
							
							}
							} catch(PDOException $e) {
		    				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
							}
						?>
                	</select>
                </label>
             </div>
             <div class="input">
                <input type="submit" name="submit" value="Register" class="button register">
             </div>
             
         </form>
         </div>
    </div>
</div>





<?php include 'includes/pagebottom.php'; ?>