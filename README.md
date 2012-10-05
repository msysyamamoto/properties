Properties
==========

Properties is automatically creates a setter and getter via annotations.

[![Build Status](https://secure.travis-ci.org/ymmtmsys/properties.png)](http://travis-ci.org/ymmtmsys/properties)

### Annotations

#### @accessor

The @accessor annotation is able to read and write variable.

#### @reader

The @reader annotation is able to read variable.

#### @writer

The @writer annotation is able to write variable.

Example
-------

```PHP
<?php
use Ymmtmsys\Properties\Properties;

class SubClass extends Properties // extend Properties class
{
    /**
     * @accessor
     */
    protected $rdwr = 'Read and wirte property';

    /**
     * @reader
     */
    protected $rdonly = 'Read only property';

    /**
     * @writer
     */
    protected $wronly = 'Write only property';

    protected $no_annotation = 'no annotation';
}

$obj = new SubClass;

// Read
echo $obj->rdonly, "\n"; // => "Read only property"
echo $obj->rdwr,   "\n"; // => "Read and wirte property" 

// Write 
$obj->wronly = 'Yippee!';
$obj->rdwr   = 'Yup!';

// Error!!
$obj->rdonly = 'Oops!';
echo $obj->wronly, "\n";
$obj->no_annotation = 'php';
echo $obj->no_annotation, "\n";
```

Copyright
---------

Copyright (c) 2012 ymmtmsys. See LICENSE for further details.
