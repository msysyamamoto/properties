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
            array(new SubProtectedProps),
            array(new SubPrivateProps),
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
     * @dataProvider objProvider
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

    /**
     * @test
     * @dataProvider objProvider
     * @expectedException PHPUnit_Framework_Error
     * @group cantaccess
     */
    public function testCantAccessRead($obj)
    {
        $read = $obj->non_annotation;
    }

    /**
     * @test
     * @dataProvider objProvider
     * @expectedException PHPUnit_Framework_Error
     * @group cantaccess
     */
    public function testCantAccessWrite($obj)
    {
        $obj->non_annotation = 'qwerty';
    }
}

class ProtectedProps extends Properties
{
    protected $non_annotation = 'cant access';

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
    private $non_annotation = 'cant access';

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

class SubProtectedProps extends ProtectedProps
{
}

class SubPrivateProps extends PrivateProps
{
}
