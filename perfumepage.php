<?php include 'includes/head.php'; ?>
<?php include 'includes/pagetop.php'; ?>
<?php
	$productID = $_GET['productID'];
	if(isset($_POST['submit'])) {
		$author = trim($_POST['author']);
		$body = trim($_POST['body']);
		$new_comment = Comment::make($productID, $author, $body);
		if($new_comment && $new_comment->save()){
			header('location:perfumepage.php?productID='.$productID);
		}
		else{
			//Failed
		}
	
	} else {
		$author = "";
		$body = "";
	}
	
	$comments = Comment::find_comments($productID);	
?>
<div id="site">
	<div id="content">
    	<div id="wrapper">
        	<div class="left_sidebar">
                
                <div class="blocks">
                    <div class="block_top">
                    	<a href=""><span>Parfumuri</span></a>
                    </div>
                    <div class="block_content">
                    	<ul>
                        	<li class="highlight"><a href="brandpage.php?query=all">Toate marcile de parfumuri</a></li>
                        	<?php
							try {
								$stmt = $db->prepare('SELECT name FROM brands ORDER BY name');
								$stmt->execute();
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

									echo '<li><a href="brandpage.php?query='.$row['name'].'">'.$row['name'].'</a></li>';

								
								}
								} catch(PDOException $e) {
								echo '<p class="bg-danger">'.$e->getMessage().'</p>';
								}
							?>        
                        </ul>
                    </div> 
                </div>
            </div>
            <div class="right_siderbar">
            	<div class="product">
                	<div class="image">
						<?php
                        $productID = $_GET['productID'];
                        
                        //echo $brand;
                        //echo $name;
                        try {
								$stmt = $db->prepare('SELECT name, brand, essence, sex, price, description, image, promo FROM products WHERE productID = :productID');
								$stmt->execute(array('productID' => $productID));
								while ($row = $stmt->fetch()) {
								if($row['promo'] > 0){
									echo '<span class="new"><img src="'.$siteroot.'/images/elements/sale.png" alt="Promotii"></span>';
								}	
								echo '<img src="'.$siteroot.'/images/perfumes/'.$row['brand'].'/'.$row['image'].'.jpg" alt="Perfumer" width=300 heigth=300>';							
								}
								} catch(PDOException $e) {
								echo '<p class="bg-danger">'.$e->getMessage().'</p>';
							}
            
                        ?>
                    </div>
                    <div class="details">
                    	<?php
						try {
								$stmt = $db->prepare('SELECT name, brand, type, essence, sex, price, description, image, promo FROM products WHERE productID = :productID');
								$stmt->execute(array('productID' => $productID));
								$row = $stmt->fetch(); 
								echo '<span class="brand">'.$row['brand'].'</span>';
							    echo '<span class="name">'.$row['name'].'</span>';
								echo '<span class="type">';
								if($row['type'] == 'perfume') echo  'parfum '; else if($row['type'] == 'set cadou') echo  'set cadou '; else if($row['type'] == 'deodorant') echo  'deodorant '; 
								if($row['sex'] != 'unisex')
									echo ' pentru ';
								echo $row['sex'].'</span>';
								echo '<span class="price">'.ceil($row['price'] - $row['price']*$row['promo']/100).' RON</span>';
								
								$stmt2 = $db->prepare('SELECT name, flavours FROM essences WHERE name = :name');
								$stmt2->execute(array('name' => $row['essence']));
								$row2 = $stmt2->fetch(); 
								
								echo '<span class="flavours">Esenta - Arome<br>'.$row2['name'].' - '.$row2['flavours'].'</span>';
								} catch(PDOException $e) {
								echo '<p class="bg-danger">'.$e->getMessage().'</p>';
							}
            
                        ?>
						
						
						
                    </div>
                </div>
                <div>
                	<span class="description">Descriere</span><br><br>
                    <div class="description_details">
                    <?php
                            try {
                                    $stmt = $db->prepare('SELECT description FROM products WHERE productID = :productID');
                                    $stmt->execute(array('productID' => $productID));
                                    $row = $stmt->fetch();
                                    echo '<span class="description_content">'.$row['description'].'</span>';
                                    } catch(PDOException $e) {
                                    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
                                }
                
                            ?>
                    </div>
              	</div>
                
                <div id="comments">
					<?php foreach($comments as $comment): ?>
                    	<div class="comment" style="margin-bottom: 2em;">
                            <div class="author">
                            	<?php echo $comment['author']; ?> a scris:
                            </div>
                            <div class="body">
                                <?php echo $comment['body']; ?>
                            </div>
                            <div class="meta-info" style="font-size: 0.8em;">
                                <?php echo $comment['created']; ?>
                            </div>
                   		</div>
                    <?php endforeach; ?>
                    <?php if(empty($comments)) { echo "Nu sunt comentarii."; } ?>
                </div>
                
                
                
                <div id="comment-form">
                
                
                  <h3>Comentezi?</h3>
                  
                  <form action="perfumepage.php?productID=<?php echo $productID; ?>" method="post">
                    <table>
                      <tr>
                        <td>Numele:</td>
                        <td><input type="text" name="author" value="<?php if( $user->is_logged_in() ) echo $_SESSION['username']; else echo $author; ?>" /></td>
                      </tr>
                      <tr>
                        <td>Comentariu:</td>
                        <td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="submit" value="Trimite comentariu" /></td>
                      </tr>
                    </table>
                  </form>
                </div>
            </div>
    	</div>
	</div>
</div>



<?php include 'includes/pagebottom.php'; ?>