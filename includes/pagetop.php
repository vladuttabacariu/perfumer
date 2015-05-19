<?php
$siteroot = '/perfumer';
?>

<?php include 'config/config.php'; ?>
<div id="header_container">
	<div id="header">
    	<div class="logo">
        	<a href="<?php echo $siteroot; ?>/index.php" tittle="Perfumer">
            	<img src="<?php echo $siteroot; ?>/images/logo.png" alt="Perfumer" width=200 heigth=70>
            </a>
        </div>
        
        <div class="linkuri_header" >
            <ul id="linkuri">
            	<li><a href="">Cum comand?</a></li> |
                <li><a href="">Cum platesc?</a></li> |
                <li><a href="">Intrebari frecvente</a></li> |
                <li><a href="">Contact</a></li>
             </ul>   
            <div class="search">
            	<form action="#" method="get" id="quick_search" role="search">
                <input id="quickSearch" class="text white" placeholder="Cauta parfumul perfect" type="search">
                <span class="btn_icon icon_search">
                <input value="Search" type="submit">
                </span>
            </form>
            </div>
        </div>
        <div class="user_header">
        	<?php if( $user->is_logged_in() ) {
				echo '<p>Buna ';
				echo  $_SESSION['username'];
				echo '</p>';
				echo '<a href="';
				echo $siteroot;
				echo '/logout.php"><span>Logout!</span></a>';

			}
			else{
				echo '<a href="';
				echo $siteroot;
				echo '/loginpage.php"><span>Login</span></a>';
				echo '<a href="';
				echo $siteroot;
				echo '/registerpage.php"><span>Register</span></a>';
			}
			?>      
        </div>
    
    </div>
    <div id="navigation_menu">
	
    <ul id="ul_nav">
    	<li><a href="index.php" id="home"><span class="ul_nav_span">Home</span></a></li>
        <li><a href="brandpage.php?query=all" id="home"><span class="ul_nav_span">Parfumuri</span></a></li>
        <li><a href="brandpage.php?query=promotions" id="home"><span class="ul_nav_span">Promotii</span></a></li>
        <li><a href="brandpage.php?query=set cadou" id="home"><span class="ul_nav_span">Seturi Cadou</span></a></li>
        <li><a href="brandpage.php?query=nou" id="home"><span class="ul_nav_span">Noutati</span></a></li>
        <li><a href="" id="home"><span class="ul_nav_span">Revista</span></a></li>
    </ul>
	</div>
</div>

