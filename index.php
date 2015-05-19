<?php include 'includes/head.php'; ?>
<?php include 'includes/pagetop.php'; ?>
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
            	<div class="banner">
                	<div id="slider">
                    	<div class="animationContainerCell" id="frame0" style="position: absolute">
                        	<a href="brandpage.php?query=nou" title="Noutati">
                            	<img src="<?php echo $siteroot; ?>/images/elements/primavera.jpg" alt="Perfumer" width=450 heigth=220>
                                <div class="animationTitleRow">
                                	<span>Noutati</span>
                                </div>
                            </a>
                        </div>
                        <div class="animationContainerCell" id="frame1" style="position: absolute">
                        	<a href="brandpage.php?query=promotions" title="Promotii">
                            	<img src="<?php echo $siteroot; ?>/images/elements/promotii.jpg" alt="Perfumer" width=450 heigth=220>
                                <div class="animationTitleRow">
                                	<span>Promotii</span>
                                </div>
                            </a>
                        </div> 
                        <div class="animationContainerCell" id="frame2" style="position: absolute">
                        	<a href="brandpage.php?query=set cadou" title="Seturi Parfum">
                            	<img src="<?php echo $siteroot; ?>/images/elements/set_parfum.jpg" alt="Perfumer" width=450 heigth=220>
                                <div class="animationTitleRow">
                                	<span>Seturi Parfum</span>
                                </div>
                            </a>
                        </div> 
                        
                    </div>
                	<div class="shipping">
                    	<a href="">
                    		<img src="<?php echo $siteroot; ?>/images/elements/transport.jpg" alt="Perfumer" width=191 heigth=220>
                            </a>
                    </div>
                </div>
                <div class="clear"></div>
                <br>
                <h1>Parfumuri promotionale</h1>
            	<div id="products">
                	<ul class="product_item">
                        <?php
						try {
							$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE promo >0 LIMIT 12');
							$stmt->execute();
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<li>';
								
								echo '<a href="perfumepage.php?productID='.$row['productID'].'">';
									echo '<span class="new"><img src="'.$siteroot.'/images/elements/sale.png" alt="Promotii"></span>';
									echo '<img src="'.$siteroot.'/images/perfumes/'.$row['brand'].'/'.$row['image'].'.jpg" alt="Perfumer" width=155 heigth=155>';
									echo '<span class="name">'.$row['name'].'</span>';
									echo '<span class="brand">by '.$row['brand'].'</span>';
									echo '<span class="type">';
									if($row['sex'] != 'unisex')
									echo ' pentru ';
									echo $row['sex'].'</span>';
									echo '<span class="oldprice">'.$row['price'].' RON pret vechi</span>';
									$newprice = $row['price'] - $row['price']*$row['promo']/100;
									echo '<span class="price">'.ceil($newprice).' RON</span>';
								echo '</a>';
								echo '</li>';
							
							}
							} catch(PDOException $e) {
		    				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
							}
						?>   
                    </ul>         
                </div>
                
            </div>
    	</div>
	</div>
</div>
<?php include 'includes/pagebottom.php'; ?>



