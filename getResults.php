<?php
include 'config/config.php';
$siteroot='/perfumer';
echo $_GET['query'];
echo $_GET['category'];
echo $_GET['type'];
echo $_GET['essence'];
echo $_GET['pricerange'];
$query = $_GET['query'];
$category = $_GET['category'];
$type = $_GET['type'];
$essence = $_GET['essence'];
$pricelower = $_GET['pricerange'];
$priceupper = $pricelower+100;

//BIG ASS SWITCH

try {
	switch($query){
		case 'all':
			$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE (CASE WHEN :sex <> -1 THEN sex = :sex ELSE 1=1  END) AND (CASE WHEN :type <> -1 THEN type = :type ELSE 1=1  END) AND (CASE WHEN :essence <> -1 THEN essence = :essence ELSE 1=1  END) AND (CASE WHEN :pricelower <> -1 THEN price > :pricelower AND price <= :priceupper ELSE 1=1  END) ');
			$stmt->execute(array('sex' => $category, 'type' => $type, 'essence' => $essence, 'pricelower' => $pricelower, 'priceupper' => $priceupper));
		break;
		
		case 'promotions':
			$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE (CASE WHEN :sex <> -1 THEN sex = :sex ELSE 1=1  END) AND (CASE WHEN :type <> -1 THEN type = :type ELSE 1=1  END) AND (CASE WHEN :essence <> -1 THEN essence = :essence ELSE 1=1  END) AND (CASE WHEN :pricelower <> -1 THEN price > :pricelower AND price <= :priceupper ELSE 1=1  END) AND promo > 0 ');
			$stmt->execute(array('sex' => $category, 'type' => $type, 'essence' => $essence, 'pricelower' => $pricelower, 'priceupper' => $priceupper));
		break;
		
		case 'nou':
			$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE (CASE WHEN :sex <> -1 THEN sex = :sex ELSE 1=1  END) AND (CASE WHEN :type <> -1 THEN type = :type ELSE 1=1  END) AND (CASE WHEN :essence <> -1 THEN essence = :essence ELSE 1=1  END) AND (CASE WHEN :pricelower <> -1 THEN price > :pricelower AND price <= :priceupper ELSE 1=1  END) AND date >  DATE_SUB(NOW(),INTERVAL 1 MONTH) ');
			$stmt->execute(array('sex' => $category, 'type' => $type, 'essence' => $essence, 'pricelower' => $pricelower, 'priceupper' => $priceupper));		
		break;
		
		case 'set cadou':
			$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE (CASE WHEN :sex <> -1 THEN sex = :sex ELSE 1=1  END) AND (CASE WHEN :type <> -1 THEN type = :type ELSE 1=1  END) AND (CASE WHEN :essence <> -1 THEN essence = :essence ELSE 1=1  END) AND (CASE WHEN :pricelower <> -1 THEN price > :pricelower AND price <= :priceupper ELSE 1=1  END) ');
			$stmt->execute(array('sex' => $category, 'type' => $query, 'essence' => $essence, 'pricelower' => $pricelower, 'priceupper' => $priceupper));
		break;
		
		default:
			$stmt = $db->prepare('SELECT productID, image, name, brand, sex, price, promo FROM products WHERE brand = :brand AND (CASE WHEN :sex <> -1 THEN sex = :sex ELSE 1=1  END) AND (CASE WHEN :type <> -1 THEN type = :type ELSE 1=1  END) AND (CASE WHEN :essence <> -1 THEN essence = :essence ELSE 1=1  END) AND (CASE WHEN :pricelower <> -1 THEN price > :pricelower AND price <= :priceupper ELSE 1=1  END) ');
			$stmt->execute(array('brand' => $query, 'sex' => $category, 'type' => $type, 'essence' => $essence, 'pricelower' => $pricelower, 'priceupper' => $priceupper));
			
	}
	
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		
		echo '<ul class="product_item">';
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
		echo '</ul>';
	
	}
	} catch(PDOException $e) {
	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	}
	
?>