<?php
/**
 *
 * A database class that connects using PDO to a mysql database.
 * Functions rely on validated inputs. Inputs should be validated using Validator.php.
 * Using unvalidated inputs may cause unhandled errors.
 */
class Database {
    /**
    * @var string
    */
    private $servername;
    /**
    * @var string
    */
    private $username;
    /**
    * @var string
    */
    private $password;
    /**
    * @var string
    */
    private $dbname;
    /**
    * @var string
    */
    private $charset;
    /**
    * @var PDO
    */
    public $pdo;

    /**
     * Creates a database connection using MySQL address and login details.
     * Returns the database connection object
     * @param
     * @return PDO
     */
   public function connect() {
       $this->servername = "localhost";
       $this->username = "root";
       $this->password = "root";
       $this->dbname = "intouchgames";
       $this->charset = "utf8mb4";
    $connection = "mysql:host=" . $this->servername . ";dbname=" . $this->dbname . ";charset=" . $this->charset;     
    $this->pdo = new PDO( $connection, $this->username, $this->password);
    return $this->pdo;  
    }

    /**
     * Creates an Api key that can be used to interact with the database.
     * Api key is made up of key id + random api key.
     * This can be easily modified to work with user logins / sessions.
     * @param
     * @return string
     */
    public function createApiKey() {
        // Creates random Api key, inserts hashed version of key into database. The user is returned a key made up of the primary key + api key to identify record.
        $dategenerated = date("y-m-d");
        $key = bin2hex(random_bytes(50));
        $hashedkey = password_hash($key, PASSWORD_DEFAULT);
        $statement = $this->pdo->prepare("INSERT INTO apikeys (apikey, isvalid, dategenerated) VALUES (:apikey, true, :dategenerated)");
        $statement->bindParam('apikey', $hashedkey);
        $statement->bindParam('dategenerated', $dategenerated);
        $statement->execute();
        $key = $this->pdo->lastInsertId() . "$" . $key;
        return $key;
    }
    
    /*
    Calls to database to check if api key is valid
    */
    /**
     * Checks a given keyset within the database to see if the key matches the database hash + is still valid.
     * If the key's creation date is not within 7 days the key will be invalidated in the database.
     * 
     * array[0] Defines the user's keyid within the database.
     * array[1] Defines the user's Api key.
     * 
     * @param array $validatedkey (See above)
     * @return bool
     */
    public function checkApiKey($validatedkey) {
        //Requests api key info 
        $keyid = $validatedkey[0];
        $statement = $this->pdo->prepare("SELECT * FROM apikeys WHERE keyid = :keyid");
        $statement->bindParam('keyid', $keyid);
        $statement->execute();
        $keydata = $statement->fetchAll();
        if(empty($keydata) != 1) {
            if (password_verify($validatedkey[1],$keydata[0]["apikey"])) {
                if ($keydata[0]["isvalid"] == 1) {
                     if($keydata[0]["dategenerated"] > date('Y-m-d',strtotime("-7 days"))) {
                        return true;
                     } else {

                         //edit key to not valid
                         $statement = $this->pdo->prepare("UPDATE users SET isvalid = 0 WHERE keyid = :id");
                         $statement->bindParam('id', $keyid);
                         $statement->execute();
                     }
                }
            }
        }
        return false;

    }

    /**
     * Creates a user within the database using details provided within parameters.
     * 
     * array[0] Defines the user's keyid within the database.
     * array[1] Defines the user's Api key.
     * 
     * @param array $validatedkey (See above)
     * @param string $validatedname (First name of the user.)
     * @param string $validatedsurname (Surname of the user)
     * @param string $validateddob (Date of Birth of the user in format 'YYYY-MM-DD')
     * @param int $validatedphone (Phone Number of the user)
     * @param string $validatedkey (Email Address of the user in format '*******@*******.***)
     * @return string
     */
    public function createUser($validatedkey, $validatedname, $validatedsurname, $validateddob, $validatedphone, $validatedemail) {
        if($this->checkApiKey($validatedkey) == false) {
            return json_encode(['errors' => 'Key Invalid']);
        }
        //Inserts into database (already validated within route.)
        $statement = $this->pdo->prepare("INSERT INTO users (firstname, surname, dob, phone, email) VALUES (:firstname, :surname, :dob, :phone, :email)");
        $statement->bindParam('firstname', $validatedname);
        $statement->bindParam('surname', $validatedsurname);
        $statement->bindParam('dob', $validateddob);
        $statement->bindParam('phone', $validatedphone);
        $statement->bindParam('email', $validatedemail);
        $statement->execute();
     /*
    If rows were affected by query user will be notified, if not they will receive an error.
    */
    switch($statement->rowCount()) {
        case 1:
        return json_encode([
            'success' => 'user created',
            'userid' => $key = $this->pdo->lastInsertId(),
            'errors' => ''
        ]);
            break;
            case 0:
        return json_encode([
            'success' => '', 
            'errors' => 'Invalid Data Format']);     
                break;
    }
    }



    /**
     * Edits a single users information in the database.
     * Each call can only affect one field for a user.
     * 
     * array[0] Defines the user's keyid within the database.
     * array[1] Defines the user's Api key.
     * 
     * @param array $validatedkey (See above)
     * @param int $validatedid (User Id of the user.)
     * @param int $validatedcolumn (Column index to edit 1=Name, 2=Surname, 3=Date of Birth, 4=Phone Number and 5=Email Address)
     * @param string|int $validatedinput (Data to replace in the database. Int for Phone Number and String for all others)
     * @return string
     */
    public function editUser($validatedkey, $validatedid,  $validatedcolumn, $validatedinput) {
        if($this->checkApiKey($validatedkey) == false) {
            return json_encode([
                'success' => '', 
                'errors' => 'Key Invalid']);
        }
        // PDO does not allow columns as placeholders so each case has its own query to update records.
        switch($validatedcolumn) {
            case 1:
                $statement = $this->pdo->prepare("UPDATE users SET firstname = :input WHERE userid = :id");
                $statement->bindParam('id', $validatedid);
                $statement->bindParam('input', $validatedinput);
                break;
            case 2:
                $statement = $this->pdo->prepare("UPDATE users SET surname = :input WHERE userid = :id");
                $statement->bindParam('id', $validatedid);
                $statement->bindParam('input', $validatedinput);
            break;
            case 3:
                $statement = $this->pdo->prepare("UPDATE users SET dob = :input WHERE userid = :id");
                $statement->bindParam('id', $validatedid);
                $statement->bindParam('input', $validatedinput);
            break;
            case 4:
                $statement = $this->pdo->prepare("UPDATE users SET phone = :input WHERE userid = :id");
                $statement->bindParam('id', $validatedid);
                $statement->bindParam('input', $validatedinput);
            break;
            case 5:
                $statement = $this->pdo->prepare("UPDATE users SET email = :input WHERE userid = :id");
                $statement->bindParam('id', $validatedid);
                $statement->bindParam('input', $validatedinput);
            break;
            default:
            return json_encode([
                'success' => '', 
                'errors' => ' Column does not exist.']);  
            break; 
        }
        $statement->execute();
    /*
    If rows were affected by query user will be notified, if not they will receive an error.
    */
        switch($statement->rowCount()) {
            case 1:
                return json_encode(['success' => ' User information changed.']);
            break;
            case 0:
                return json_encode([
                    'success' => '', 
                    'errors' => 'No users affected - UserId invalid or data was equal to the requested change.']);     
            break;
        }
    }
    /**
     * Deletes a users record within the database using their userid.
     * 
     * array[0] Defines the user's keyid within the database.
     * array[1] Defines the user's Api key.
     * 
     * @param array $validatedkey (See above)
     * @param int $validatedid (User Id of the user.)
     * @return string
     */
    public function deleteUser($validatedkey, $validatedid) {
        if($this->checkApiKey($validatedkey) == false) {
            return json_encode([
                'success' => '', 
                'errors' => 'Key Invalid']);
        }
        $statement = $this->pdo->prepare("DELETE FROM users WHERE userid = :id");
        $statement->bindParam('id', $validatedid);
        $statement->execute();
    /*
    If rows were affected by query user will be notified, if not they will receive an error.
    */
        switch($statement->rowCount()) {
            case 1:
                return json_encode(['success' => ' User deleted.']);
            break;
            case 0:
                return json_encode([
                    'success' => '', 
                    'errors' => ' UserId not valid.']);     
            break;
        }

    }

    /**
     * Returns a users record within the database using their userid.
     * 
     * array[0] Defines the user's keyid within the database.
     * array[1] Defines the user's Api key.
     * 
     * @param array $validatedkey (See above)
     * @param int $validatedid (User Id of the user.)
     * @return string
     */
    public function readUser($validatedkey, $validatedid) {
        $key = array();

        print_r($this->checkApiKey($validatedkey));
        if($this->checkApiKey($validatedkey) == false) {
            return json_encode([
                'success' => '', 
                'errors' => 'Key Invalid']);
        }
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE userid = :id");
        $statement->bindParam('id', $validatedid);
        $statement->execute();
    /*
    Sends selected user information in JSON format
    */
    switch($statement->rowCount()) {
        case 1:
            return json_encode($statement->fetch());
        break;
        case 0:
            return json_encode([
                'success' => '', 
                'errors' => 'UserId not valid']);     
        break;
    }
    }

    /**
     * Returns all users records within the database.
     * 
     * array[0] Defines the user's keyid within the database.
     * array[1] Defines the user's Api key.
     * 
     * @param array $validatedkey (See above)
     * @return string
     */
    public function readAllUsers($validatedkey) {
        if($this->checkApiKey($validatedkey) == false) {
            return json_encode([
                'success' => '', 
                'errors' => 'Key Invalid']);
        }
        $statement = $this->pdo->prepare("SELECT * FROM `users`");
        $statement->execute();
    /*
    Sends all User information in JSON format
    */
        return json_encode($statement->fetchAll());
    }
}
?>