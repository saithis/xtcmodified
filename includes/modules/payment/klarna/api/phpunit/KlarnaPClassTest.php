<?php
//~ require_once 'vfsStream/vfsStream.php';
require_once(DIR_WS_INCLUDES.'external/klarna/api/transport/xmlrpc-3.0.0.beta/lib/xmlrpc.inc');
require_once(DIR_WS_INCLUDES.'external/klarna/api/transport/xmlrpc-3.0.0.beta/lib/xmlrpc_wrappers.inc');
require_once(DIR_WS_INCLUDES.'external/klarna/api/pclasses/storage.intf.php');
require_once('../Klarna.php');

/**
 * Test class for KlarnaPClass.
 * Generated by PHPUnit on 2011-02-03 at 10:10:47.
 */
class KlarnaPClassTest extends PHPUnit_Framework_TestCase {

    /**
     * @var KlarnaPClass
     */
    protected $klarnaPclass;

    public static function setUpBeforeClass() {
        date_default_timezone_set('Europe/Stockholm');
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $pcArray = array(
            "1029",
            "137",
            "6 m�n",
            "6",
            "195",
            "29",
            "0",
            "1000",
            "209",
            "0",
            "10"
        );
        $this->klarnaPclass = new KlarnaPClass($pcArray);
        $this->storage = new TestStorage();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        unset($this->klarnaPclass);
    }

    public function testId() {
        $this->assertEquals("137", $this->klarnaPclass->getId());
        $this->klarnaPclass->setId("0000");
        $this->assertEquals("0000", $this->klarnaPclass->getId());
    }

    public function testDescription() {
        $this->assertEquals("6 m�n", $this->klarnaPclass->getDescription());
        $this->klarnaPclass->setDescription("description");
        $this->assertEquals("description", $this->klarnaPclass->getDescription());
    }

    public function testMonths() {
        $this->assertEquals("6", $this->klarnaPclass->getMonths());
        $this->klarnaPclass->setMonths("12");
        $this->assertEquals("12", $this->klarnaPclass->getMonths());
    }

    public function testStartFee() {
        $this->assertEquals("195", $this->klarnaPclass->getStartFee());
        $this->klarnaPclass->setStartFee("9999");
        $this->assertEquals("9999", $this->klarnaPclass->getStartFee());

        $this->klarnaPclass->setStartFee(0.1);
        $this->assertInternalType('float', $this->klarnaPclass->getStartFee());
        $this->assertEquals(0.1, $this->klarnaPclass->getStartFee());
    }

    public function testInvoiceFee() {
        $this->assertEquals("29", $this->klarnaPclass->getInvoiceFee());
        $this->klarnaPclass->setInvoiceFee("8888");
        $this->assertEquals("8888", $this->klarnaPclass->getInvoiceFee());

        $this->klarnaPclass->setInvoiceFee(0.1);
        $this->assertInternalType('float',
            $this->klarnaPclass->getInvoiceFee());
        $this->assertEquals(0.1, $this->klarnaPclass->getInvoiceFee());
    }

    public function testInterestRate() {
        $this->assertEquals("0", $this->klarnaPclass->getInterestRate());
        $this->klarnaPclass->setInterestRate("10");
        $this->assertEquals("10", $this->klarnaPclass->getInterestRate());

        $this->klarnaPclass->setInterestRate(0.1);
        $this->assertInternalType('float',
            $this->klarnaPclass->getInterestRate());
        $this->assertEquals(0.1, $this->klarnaPclass->getInterestRate());
    }

    public function testMinAmount() {
        $this->assertEquals("1000", $this->klarnaPclass->getMinAmount());
        $this->klarnaPclass->setMinAmount("7777");
        $this->assertEquals("7777", $this->klarnaPclass->getMinAmount());

        $pc = new KlarnaPClass();
        $pc->setMinAmount(0.1);
        $this->assertInternalType('float', $pc->getMinAmount());
        $this->assertEquals(0.1, $pc->getMinAmount());

    }

    public function testCountry() {
        $this->assertEquals("209", $this->klarnaPclass->getCountry());
        $this->klarnaPclass->setCountry("6666");
        $this->assertEquals("6666", $this->klarnaPclass->getCountry());
    }

    public function testType() {
        $this->assertEquals("0", $this->klarnaPclass->getType());
        $this->klarnaPclass->setType("5555");
        $this->assertEquals("5555", $this->klarnaPclass->getType());
    }

    public function testEid() {
        $this->assertEquals("1029", $this->klarnaPclass->getEid());
        $this->klarnaPclass->setEid("4444");
        $this->assertEquals("4444", $this->klarnaPclass->getEid());
    }

    public function testExpire() {
        $this->assertEquals("10", $this->klarnaPclass->getExpire());
        $this->klarnaPclass->setExpire("20");
        $this->assertEquals("20", $this->klarnaPclass->getExpire());
    }

    public function testValid() {
        $this->klarnaPclass->setExpire(null);
        $this->assertTrue($this->klarnaPclass->isValid());
        $this->klarnaPclass->setExpire("1");
        $this->assertFalse($this->klarnaPclass->isValid());
    }

    /**
     * @expectedException Exception
     */
    public function testPCStorageNullDesc() {

        $this->klarnaPclass->setDescription(null);
        $this->storage->addPClass($this->klarnaPclass);

        //Test so null description pclasses cannot be added.
        $this->storage->getPClass($this->klarnaPclass->getId(), $this->klarnaPclass->getEid(),
            $this->klarnaPclass->getCountry());
    }

    /**
     * @expectedException Exception
     */
    public function testPCStorageInvalidId() {
        //Test so exception is thrown for invalid id
        $this->storage->getPClass(null, null, null);
    }

    /**
     * @expectedException Exception
     */
    public function testPCStorageNotFound() {
        $this->storage->addPClass($this->klarnaPclass);

        //Test so exception is thrown for pclass not found
        $this->storage->getPClass($this->klarnaPclass->getId()+1, $this->klarnaPclass->getEid(),
            $this->klarnaPclass->getCountry());
    }

    /**
     * @expectedException Exception
     */
    public function testPCStorageCountryMismatch() {
        $this->klarnaPclass->setExpire(strtotime("+1 day" , time()));
        $this->storage->addPClass($this->klarnaPclass);

        //Test so that country mismatch exception is thrown
        $this->storage->getPClass($this->klarnaPclass->getId(), $this->klarnaPclass->getEid(), 55);
    }

    /**
     * @expectedException KlarnaException
     */
    public function testPCStorageAddFaultyPClass() {
        //Try to add non-pclass object
        $this->storage->addPClass(new stdClass());
    }

    /**
     * @expectedException Exception
     */
    public function testPCStorageInvalidCountry() {
        $this->storage->getPClasses(null, null, null);
    }

    public function testPCStorageGetInvalid() {
        $this->storage->addPClass($this->klarnaPclass);

        //Test so pclass invalid is skipped.
        $pclasses = $this->storage->getPClasses($this->klarnaPclass->getEid(), $this->klarnaPclass->getCountry());

        $this->assertEmpty($pclasses[$this->klarnaPclass->getEid()]);
    }

    public function testLargerPCArray() {
        $pcArray = $this->klarnaPclass->toArray();
        $pcArray['testing'] = 'test';

        //Nothing should break when doing this...
        $pc = new KlarnaPClass($pcArray);

        $this->assertEquals($pc->toArray(), $this->klarnaPclass->toArray());

        $pcArray = array(
            "1029",
            "137",
            "6 m�n",
            "6",
            "195",
            "29",
            "0",
            "1000",
            "209",
            "0",
            "10",
            "something"
        );

        $pc = new KlarnaPClass($pcArray);
        $this->assertEquals($pc->toArray(), $this->klarnaPclass->toArray());
    }

    public function testToArray() {
        $pcArray = array(
            'id'           => 137,
            'description'  => "6 m�n",
            'eid'          => 1029,
            'expire'       => 10,
            'months'       => 6,
            'startfee'     => 195,
            'invoicefee'   => 29,
            'interestrate' => 0,
            'minamount'    => 1000,
            'country'      => 209,
            'type'         => 0
        );
        $this->assertEquals($pcArray, $this->klarnaPclass->toArray());
    }

}

class TestStorage extends PCStorage  {
    public function load($uri) {
    }
    public function save($uri) {
    }
    public function clear($uri) {
    }
}
?>
