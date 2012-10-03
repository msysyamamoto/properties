<?php
require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use Ymmtmsys\Properties\Properties;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function objProvider()
    {
        return array(
            array(new ProtectedProps),
            array(new PrivateProps),
        ); 
    }

    /**
     * @test
     * @dataProvider objProvider 
     * @group reader
     */
    public function testReaderRead($obj)
    {
        $this->assertSame('rdonly', $obj->rdonly);
    }

    /**
     * @test
     * @dataProvider objProvider 
     * @expectedException PHPUnit_Framework_Error
     * @group reader
     */
    public function testReaderWrite($obj)
    {
         $obj->rdonly = 'write!';
    }

    /**
     * @test
     * @dataProvider objProvider 
     * @group writer 
     */
    public function testWriterWrite($obj)
    {
        $data = 'abcdef';
        $this->assertNotSame($data, $obj->getWronly());

        $obj->wronly = $data;
        $this->assertSame($data, $obj->getWronly());
    }

    /**
     * @test
     * @dataProviderr 
     * @expectedException PHPUnit_Framework_Error
     * @group writer 
     */
    public function testWriterRead($obj)
    {
        $read = $obj->wronly;
    }

    /**
     * @test
     * @dataProvider objProvider 
     * @group accessor 
     */
    public function testAccessor($obj)
    {
        $data = 'abcdef';
        $obj->rdwr = $data;
        $this->assertSame($data, $obj->rdwr);
    }
}

class ProtectedProps extends Properties
{
    /**
     * @reader
     */
    protected $rdonly = 'rdonly';

    /**
     * @accessor
     */
    protected $rdwr = 'rdwr';

    /**
     * @writer
     */
    protected $wronly = 'wronly';

    public function getWronly()
    {
        return $this->wronly;
    }
}

class PrivateProps extends Properties
{
    /**
     * @reader
     */
    private $rdonly = 'rdonly';

    /**
     * @accessor
     */
    private $rdwr = 'rdwr';

    /**
     * @writer
     */
    private $wronly = 'wronly';

    public function getWronly()
    {
        return $this->wronly;
    }
}
