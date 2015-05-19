<?php include 'includes/head.php'; ?>
<?php include 'includes/pagetop.php'; ?>

<div id="site">
	<div id="content">
    	<div id="wrapper">
        	<div class="left_sidebar">
                <div class="blocks">
                    <div class="block_top">
                    	<a href=""><span><?php if($_GET['query'] == 'all') echo 'Toate parfumurile'; else if($_GET['query'] == 'promotions') echo 'Promotii'; else if($_GET['query'] == 'set cadou') echo 'Seturi cadou'; else if($_GET['query'] == 'nou') echo 'Noutati'; else echo $_GET['query'];  ?></span></a>
                    </div>
                    <div class="block_content">
                    	<ul>
                        	<li><a href="javascript:changeCategory('barbati')">Parfumuri pentru barbati</a></li>
                            <li><a href="javascript:changeCategory('femei')">Parfumuri pentru femei</a></li>
                            <li><a href="javascript:changeCategory('unisex')">Parfumuri unisex</a></li>
                            <?php if($_GET['query'] != 'set cadou') echo '<li><a href="javascript:changeType(\'deodorant\')">Parfumuri deodorant</a></li>
                            <li><a href="javascript:changeType(\'set cadou\')">Seturi cadou</a></li>';?>
                            
                        </ul>
                    </div>      
                </div>
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

                <h1>Parfumuri - <?php if($_GET['query'] == 'all') echo 'Toate parfumurile'; else if($_GET['query'] == 'promotions') echo 'Promotii'; else if($_GET['query'] == 'set cadou') echo 'Seturi cadou'; else if($_GET['query'] == 'nou') echo 'Noutati'; else echo $_GET['query'];  ?> </h1>
                <div class="filters">
                	<label class="">
                     Selectie
                    </label>
                	<form method="get" id="fltForm">
                    	<div>
                    	<label class="select_label">
                            <select id="category_select" onchange="showResults()" name="category">
                                <option value="-1"> Categorie </option>
                                <option value="barbati">Parfumuri pentru barbati</option>
                                <option value="femei">Parfumuri pentru femei</option>
                                <option value="unisex">Parfumuri unisex</option>

                             </select>
                         </label>
                         <label class="select_label">
                            <select id="type_select" onchange="showResults()" name="type">
                                <option value="-1"> Tip </option>
                                <option value="deodorant">Dedorant</option>
                                <option value="set cadou">Set cadou</option>

                             </select>
                         </label>
                         </div>
                         <div>
                         <label class="select_label">
                             <select id="essence_select" onchange="showResults()" name="essence">
                                 <option value="-1"> Esenta</option>
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
                         <label class="select_label">
                         	<select id="pricerange_select" onchange="showResults()" name="pricerange">
                            	<option value="-1"> Pret</option>
                                <option value="0"> 0-100 RON </option>
                                <option value="100"> 100-200 RON </option>
                                <option value="200"> 200-300 RON </option>
                                <option value="300"> 300-400 RON </option>
                                <option value="400"> 400-500 RON </option>
                            </select>
                         </label>
                         </div>
                    </form>
                </div>
            	<div id="products">
                	<ul class="product_item">
                        <?php
						if(isset($_GET['brand'])){$brand = $_GET['brand'];}else{$brand = '';}
						if(isset($_GET['type'])){$type = $_GET['type'];}else{$type = '';}
						if(isset($_GET['essence'])){$essence = $_GET['essence'];}else{$essence = '';}
						
						$query = $_GET['query'];
						try {
							switch($query){
								
								case 'promotions':
								$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE promo > 0 ORDER BY name');
								$stmt->execute();
								break;
								
								case 'nou':
								$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE date >  DATE_SUB(NOW(),INTERVAL 1 MONTH) ORDER BY name');
								$stmt->execute();	
								break;
								
								case 'set cadou':
								$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE type= :type ORDER BY name');
								$stmt->execute(array('type' => $query));
								break;
								
								case 'all':		
								$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products ORDER BY name');
								$stmt->execute();
								break;
								
								default:
								$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE brand= :brand ORDER BY name');
								$stmt->execute(array('brand' => $query));	
							}
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<li>';
								
								echo '<a href="perfumepage.php?productID='.$row['productID'].'">';
								if($row['promo'] > 0){
									echo '<span class="new"><img src="'.$siteroot.'/images/elements/sale.png" alt="Promotii"></span>';
								}
									echo '<img src="'.$siteroot.'/images/perfumes/'.$row['brand'].'/'.$row['image'].'.jpg" alt="Perfumer" width=155 heigth=155>';
									echo '<span class="name">'.$row['name'].'</span>';
									echo '<span class="brand">by '.$row['brand'].'</span>';
									echo '<span class="type">';
									if($row['sex'] != 'unisex')
									echo ' pentru ';
									echo $row['sex'].'</span>';
									if($row['promo'] > 0){
									echo '<span class="oldprice">'.$row['price'].' RON pret vechi</span>';
									$newprice = $row['price'] - $row['price']*$row['promo']/100;
									echo '<span class="price">'.ceil($newprice).' RON</span>';
									}
									else{
										echo '<span class="price">'.$row['price'].' RON</span>';
									}
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