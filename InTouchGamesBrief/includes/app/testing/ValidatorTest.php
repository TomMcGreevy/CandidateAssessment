<?php
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotTrue;
use function PHPUnit\Framework\assertTrue;

class ValidatorTest extends TestCase {
    protected $validator;

    public function setUp() : void {
        //Method is called before every test allowing them to access database variables
        $this->validator = new Validator;
    } 
    public function testSanitiseString() {
        $testString = 'hello';
        assertEquals('hello', $this->validator->sanitiseString($testString), "Sanatise string does not return expected value.");
        $testString = 'hello<>';
        assertEquals('hello', $this->validator->sanitiseString($testString), "Sanatise string does not escape special characters.");
    } 
    public function testValidateInt() {
        $testInt = 1;
        assertEquals(1, $this->validator->validateInt($testInt), "ValidateInt() does not allow correctly formatted int.");
        $testInt = 'hello';
        assertEquals(false, $this->validator->validateInt($testInt), "ValidateInt() allows string as Int.");
        $testInt = '1hello';
        assertEquals(false, $this->validator->validateInt($testInt), "ValidateInt() allows string as Int.");
        $testInt = '07';
        assertEquals("7", $this->validator->validateInt($testInt), "ValidateInt() does not work with leading zeros.");
    } 
    public function testValidateDate() {
        $testDate = "1996-08-22";
        assertEquals("1996-08-22", $this->validator->validateDate($testDate), "ValidateDate() does not allow correctly formatted Date.");
        $testDate = '1996/08/22';
        assertEquals("1996-08-22", $this->validator->validateDate($testDate), "ValidateDate() does not change format for dates using '/'.");
        $testDate = '22/08/1996';
        assertEquals("1996-08-22", $this->validator->validateDate($testDate), "ValidateDate() does not change format dates given in the format 'DD-MM-YYYY'.");
        $testDate = 'hello';
        assertEquals(false, $this->validator->validateDate($testDate), "ValidateDate() allows non date strings.");
    } 
    public function testSanitiseEmail() {
        $testEmail = "tom_mcgreevy@hotmail.co.uk";
        assertEquals("tom_mcgreevy@hotmail.co.uk", $this->validator->sanitiseEmail($testEmail), "sanitiseEmail() does not allow correctly formatted Email.");
        $testEmail = "hello";
        assertEquals(false, $this->validator->sanitiseEmail($testEmail), "sanitiseEmail() allows incorrectly formatted Email.");
        $testEmail = "tom_mcgreevy@hotmail.";
        assertEquals(false, $this->validator->sanitiseEmail($testEmail), "sanitiseEmail() allows incorrectly formatted Email.");
        $testEmail = "tom_mcgreevyhotmail.co.uk";
        assertEquals(false, $this->validator->sanitiseEmail($testEmail), "sanitiseEmail() allows incorrectly formatted Email.");
    } 
    public function testvalidateApiKey() {
        $testKey = "276\$d42fcd56711d12850d65661bd686ee4db2333c9d29ac9780766c44062859884653cf9e72ae98120836fbfe5611816478a687";
        assertEquals('276', $this->validator->validateApiKey($testKey)[0], "sanitiseApiKey() return key array for valid key string.");
        assertEquals('d42fcd56711d12850d65661bd686ee4db2333c9d29ac9780766c44062859884653cf9e72ae98120836fbfe5611816478a687', $this->validator->validateApiKey($testKey)[1], "sanitiseApiKey() return key array for valid key string.");

        $testKey = "276\$d42fcd56711";
        assertEquals(false, $this->validator->validateApiKey($testKey), "sanitiseApiKey() return key array for valid key string.");
        $testKey = "hello\$d42fcd56711d12850d65661bd686ee4db2333c9d29ac9780766c44062859884653cf9e72ae98120836fbfe5611816478a687";
        assertEquals(false, $this->validator->validateApiKey($testKey), "sanitiseApiKey() return key array for valid key string.");
        $testKey = "276d42fcd56711d12850d65661bd686ee4db2333c9d29ac9780766c44062859884653cf9e72ae98120836fbfe5611816478a687";
        assertEquals(false, $this->validator->validateApiKey($testKey), "sanitiseApiKey() return key array for valid key string.");
    } 
    
}