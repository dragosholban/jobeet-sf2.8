<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\Jobeet;
use PHPUnit\Framework\TestCase;

class JobeetTest extends TestCase
{
    public function testSlugify()
    {
        $this->assertEquals('sensio', Jobeet::slugify('Sensio'));
        $this->assertEquals('sensio-labs', Jobeet::slugify('sensio labs'));
        $this->assertEquals('sensio-labs', Jobeet::slugify('sensio   labs'));
        $this->assertEquals('paris-france', Jobeet::slugify('paris,france'));
        $this->assertEquals('sensio', Jobeet::slugify('  sensio'));
        $this->assertEquals('sensio', Jobeet::slugify('sensio  '));
        $this->assertEquals('n-a', Jobeet::slugify(''));
        $this->assertEquals('n-a', Jobeet::slugify(' - '));
        if (function_exists('iconv'))
        {
            $this->assertEquals('developpeur-web', Jobeet::slugify('Développeur Web'));
        }
    }
}

