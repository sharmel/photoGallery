<?php
// If it's going to need the database, then it's 
// probably smart to require it before starting.
require_once(LIB_PATH.DS.'database.php');

class User extends DatabaseObject {
	
	protected static $table_name="users";
	protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
	
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	
	
	
	
	public function formValues(){
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['action']))):

	if (isset($_POST['nationalId'])) { $nationalId = $_POST['nationalId']; }
	if (isset($_POST['firstname'])) { $firstname = $_POST['firstname']; }
	if (isset($_POST['middlename'])) { $middlename = $_POST['middlename']; }
	if (isset($_POST['lastname'])) { $lastname = $_POST['lastname']; }
	if (isset($_POST['email'])) { $email = $_POST['email']; }
	if (isset($_POST['phone'])) { $phone = $_POST['phone']; }
	if (isset($_POST['address'])) { $address = $_POST['address']; }
	if (isset($_POST['address2'])) { $address2 = $_POST['address2']; }
	if (isset($_POST['country'])) { $country = $_POST['country']; }
	if (isset($_POST['city'])) { $city = $_POST['city'];}
	if (isset($_POST['postalcode'])) { $postalcode = $_POST['postalcode'];}
	if (isset($_POST['postalcode'])) { $city = $_POST['postalcode'];}
	if (isset($_POST['mypassword'])) { $mypassword = $_POST['mypassword']; }
	if (isset($_POST['mypasswordconf'])) { $mypasswordconf = $_POST['mypasswordconf']; }
	if (isset($_POST['reference'])) { $reference = $_POST['reference']; }
	if (isset($_POST['favoritemusic'])) { $favoritemusic = $_POST['favoritemusic']; }
	if (isset($_POST['requesttype'])) { $requesttype = $_POST['requesttype']; }


	if ($firstname === '') :
		$err_firstname = '<div class="error">Sorry, your name is  required</div>';
	endif; // input field empty
	if ($nationalId==='') :
		$err_nationalId = '<div class="error">Sorry, National Id is required</div>';
	endif;

	if ($lastname==='') :
		$err_lastname = '<div class="error">Sorry, your name is required</div>';
		endif;
		
	if ($phone==='') :
		$err_phone = '<div class="error">Sorry, Phone Number is required</div>';
		endif;
		
	if ($city==='') :
		$err_city = '<div class="error">Sorry, City is required</div>';
		endif;
	if ($postalcode==='') :
		$err_postalcode = '<div class="error">Sorry, Postal Code is required</div>';
		endif;
	if ($address==='') :
		$err_address = '<div class="error">Sorry, Address is required</div>';
		endif;
	if ($reference==='') :
		$err_reference = '<div class="error">Sorry, country is required</div>';
		endif;
	if (strlen($mypassword) <= 6):
		$err_passlength = '<div class="error">Sorry, the password must be at least six characters</div>';
	endif; //password not long enough


	if ($mypassword !== $mypasswordconf) :
		$err_mypassconf = '<div class="error">Sorry, passwords must match</div>';
	endif; //passwords don't match


	if ( !(preg_match('/[A-Za-z]+/', $firstname)) ) :
		$err_patternmatch = '<div class="error">Sorry, the name must be an alphabet: First</div>';
	endif; // pattern doesn't match
	

endif; //form submitted
	
	
	}
	
	protected function attributes() { 
		// return an array of attribute names and their values
	  $attributes = array();
	  foreach(self::$db_fields as $field) {
	    if(property_exists($this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
	}
	
	protected function sanitized_attributes() {
	  global $database;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $database->escape_value($value);
	  }
	  return $clean_attributes;
	}
	
	public function register() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
	  $sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
	  $sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
	  if($database->query($sql)) {
	    $this->id = $database->insert_id();
	    return true;
	  } else {
	    return false;
	  }
	}
}
 	
?>
