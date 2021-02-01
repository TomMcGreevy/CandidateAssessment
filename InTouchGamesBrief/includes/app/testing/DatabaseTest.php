<?php
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotTrue;
use function PHPUnit\Framework\assertTrue;

class DatabaseTest extends TestCase {
    protected $database;

    public function setUp() : void {
        //Method is called before every test allowing them to access database variables
        $this->database = new Database;
        $this->database->connect();
    } 
    public function testRequestKey() {
        $key = $this->database->createApiKey();
        //explode key into 2 strings (key id / api key) around the $ symbol.
        $substrings = explode("$", $key);
        // Check to see if userid is a number
        $this->assertTrue(is_numeric($substrings[0]));
        // Check to see if key is 100 characters in length
        $this->assertEquals(100, strlen($substrings[1]));
        return $substrings;
    } 
    /**
     * @depends testRequestKey
     */
    public function testCheckKey($key) {
        //This test uses the key created in the previous test as a valid key to test the checkkey function
        $checkkey = $this->database->checkApiKey($key);
        assertTrue($checkkey);
        //Invalid key and userid tested to check if it will throw an error.     
        $fakekey = array();
        array_push($fakekey, '1');
        array_push($fakekey, 'fakekey');
        assertNotTrue($this->database->checkApiKey($fakekey), 'checkKey returned true for fake api key');
        return $key;
    } 

     /**
     * @depends testCheckKey
     */
    public function testCreateUser($key) {
        $name = 'Tom';
        $surname = 'McGreevy';
        $dob = '1996-08-22';
        $phone = 447718209852;
        $email = 'tom_mcgreevy@hotmail.co.uk';
        $checkkey = $this->database->createUser($key, $name, $surname, $dob, $phone, $email);
        assertEquals('user created', json_decode($checkkey, true)['success']);
        $userid = json_decode($checkkey, true)['userid'];
        $data = array();
        array_push($data, $key, $userid);
        return $data;
    } 
            /**
     * @depends testCreateUser
     */
    public function testReadAllUsers($data) {
        $key = $data[0];
        $checkkey = $this->database->readAllUsers($key);
        assertNotEquals(true, empty(json_decode($checkkey, true)), "ReadAllUsers returned empty set after user has been created.");

    }

        /**
     * @depends testCreateUser
     */
    public function testReadUser($data) {
        $key = $data[0];
        $userid = $data[1];
        $name = 'Tom';
        $surname = 'McGreevy';
        $dob = '1996-08-22';
        $phone = 447718209852;
        $email = 'tom_mcgreevy@hotmail.co.uk';

        $checkkey = $this->database->readUser($key, $userid);
        assertEquals('McGreevy', json_decode($checkkey, true)['surname']);
        $fakeuserid = 0;
        $checkkey = $this->database->readUser($key, $fakeuserid);
        assertNotEquals('', json_decode($checkkey, true)['errors'], "No error thrown for readUser() on incorrect userid.");
        $fakekey = 'fakekey';
        $checkkey = $this->database->readUser($fakekey, $userid);
        assertNotEquals('', json_decode($checkkey, true)['errors'], "No error thrown for readUser() on incorrect api key.");

    }
            /**
     * @depends testCreateUser
     */
    public function testEditUser($data) {
        $key = $data[0];
        $userid = $data[1];
        $name = 'Tom';
        $surname = 'McGreevy';
        $dob = '1996-08-22';
        $phone = 447718209852;
        $email = 'tom_mcgreevy@hotmail.co.uk';
        $checkkey = $this->database->editUser($key, $userid, 1, 'David');
        assertEquals(' User information changed.', json_decode($checkkey, true)['success']);
        $checkkey = $this->database->editUser($key, $userid, 1, 'David');
        assertNotEquals('', json_decode($checkkey, true)['errors'], "No error thrown on editUser() when data is equal in database.");
        $checkkey = $this->database->editUser($key, $userid, 9, 'David');
        assertEquals(' Column does not exist.', json_decode($checkkey, true)['errors']);
        $checkkey = $this->database->editUser($key, $userid, 'h', 'David');
        assertEquals(' Column does not exist.', json_decode($checkkey, true)['errors']);


    }
    /**
     * @depends testCreateUser
     */
    public function testDeleteUser($data) {
        $key = $data[0];
        $userid = $data[1];
        $checkkey = $this->database->deleteUser($key, $userid);
        assertEquals(' User deleted.', json_decode($checkkey, true)['success']);
        $checkkey = $this->database->deleteUser($key, $userid);
        assertEquals(' UserId not valid.', json_decode($checkkey, true)['errors']);



    }


}