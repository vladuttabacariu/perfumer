<?php 
class Comment {

  protected static $table_name="comments";
  protected static $db_fields=array('commentID', 'productID', 'created', 'author', 'body');

  public $commentID;
  public $productID;
  public $created;
  public $author;
  public $body;
  

  // "new" is a reserved word so we use "make" (or "build")
	public static function make($productID, $author="Anonymous", $body="") {
    if(!empty($productID) && !empty($author) && !empty($body)) {
			$comment = new Comment();
	    $comment->productID = (int)$productID;
	    $comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
	    $comment->author = $author;
	    $comment->body = $body;
	    return $comment;
		} else {
			return false;
		}
	}
	
 	public function save(){
		try {
		//create PDO connection 
		$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		} catch(PDOException $e) {
		exit;
		}
		
		$stmt = $db->prepare('INSERT INTO '.self::$table_name.' (productID, created, author, body) VALUES (:productID, :created, :author, :body)');
		return $stmt->execute(array('productID' => $this->productID, 'created' => $this->created, 'author' => $this->author, 'body' => $this->body));
	}
	
	public function find_comments($productID){
		try {
		//create PDO connection 
		$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		} catch(PDOException $e) {
		exit;
		}
		
		$stmt = $db->prepare('SELECT * FROM '.self::$table_name.' WHERE productID = :productID ORDER BY created ASC');
		$stmt->execute(array('productID' => $productID));
		$object_array= array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$object_array[] = $row;
		}
		return $object_array;
		
	}
}

?>