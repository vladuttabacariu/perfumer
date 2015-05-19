<?php include 'includes/head.php'; ?>
<?php include 'includes/pagetop.php'; ?>
<?php
//include config
require_once('config/config.php');

//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: index.php'); } 

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	$_SESSION['username'] = $username;
	if($user->login($username,$password)){ 
		
		header('Location: index.php');
		exit;
	
	} else {
		$error[] = 'Numele utilizator sau parola incorecta! Sau contul nu a fost activat!';
	}

}//end if submit

//define page title
$title = 'Login';

?>
<div id="site">
    <div id="content">
    	<div id="login">
            <h1>Login</h1>   
            <p><a href='<?php echo $siteroot; ?>/index.php'>Intoarce-te la prima pagina!</a></p>
            <?php
                //check for any errors
                if(isset($error)){
                    foreach($error as $error){
                        echo '<p class="bg-danger">'.$error.'</p>';
                    }
                }
                if(isset($_GET['action'])){
    
                    //check the action
                    switch ($_GET['action']) {
                        case 'active':
                            echo "<h2 class='bg-success'>Contul dumneavoastra este acum activ. Va puteti loga!</h2>";
                            break;
                        case 'reset':
                            echo "<h2 class='bg-success'>Verifica inbox-ul pentru email de resetare parola!.</h2>";
                            break;
                        case 'resetAccount':
                            echo "<h2 class='bg-success'>Parola a fost schimbata, va puteti loga!</h2>";
                            break;
                    }
                } 
                ?>
             <form class="login_new" action="" method="post" >
                 <div class="input">
                    <input class="input_text" type="text" placeholder="Username" name="username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1"></input>
                 </div>
                 <div class="input">
                    <input class="input_text" type="password" autocomplete="off" placeholder="Password" name="password" tabindex="2"></input>                                      
                 </div> 
                  <p><a href='reset.php'>Ti-ai uitat parola?</a></p>
                 <div class="input">
                    <input type="submit" name="submit" value="LOGIN" class="button login" tabindex="3">
                 </div>
             </form>
    	</div>
    </div>
</div>






<?php include 'includes/pagebottom.php'; ?>