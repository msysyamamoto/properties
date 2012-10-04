Properties
==========

Properties is automatically creates a setter and getter via annotations.

Example
-------

```PHP
<?php
use Ymmtmsys\Properties\Properties;

class SubClass extends Properties // extend Properties class
{
    /**
     * @reader
     */
    protected $rdonly = 'Read only property';

    /**
     * @accessor
     */
    protected $rdwr = 'Read and wirte property';

    /**
     * @writer
     */
    protected $wronly = 'Write only property';
}

$obj = new SubClass;

// Read
echo $obj->rdonly, "\n"; // => "Read only property"
echo $obj->rdwr,   "\n"; // => "Read and wirte property" 

// Write 
$obj->rdwr   = 'Yup!';
$obj->wronly = 'Yippee!';

// Fatal Error 
$obj->rdonly = 'Oops!';
echo $obj->wronly, "\n";
```

Copyright
---------

Copyright (c) 2012 ymmtmsys. See LICENSE for further details.
