<?php
namespace Tests;

use \PHPUnit\Framework\TestCase;
use \Sightengine\SightengineClient;

class StackTest extends TestCase
{
    public function test_nudityModel()
    {
        $client = new SightengineClient('1234', 'test');
        $binaryFile = fopen(__DIR__ . '/assets/image.jpg', 'r'); // stream handles are returned by fopen()

        $output = $client->check(['nudity'])->set_url('https://sightengine.com/assets/img/examples/example5.jpg');
        $this->assertEquals('success', $output->status);

        $output2 = $client->check(['nudity'])->set_file(__DIR__ . '/assets/image.jpg');
        $this->assertEquals('success', $output2->status);

        $output3 = $client->check(['nudity','wad','properties','type','faces', 'celebrities'])->set_bytes($binaryFile);
        $this->assertEquals('success', $output3->status);
    }

    public function test_allModel()
    {
        $client = new SightengineClient('1234', 'test');
        $binaryFile = fopen(__DIR__ . '/assets/image.jpg', 'r');

        $output = $client->check(['nudity','wad','properties','type','faces', 'celebrities'])->set_url('https://sightengine.com/assets/img/examples/example5.jpg');
        $this->assertEquals('success', $output->status);

        $output2 = $client->check(['nudity','wad','properties','type','faces', 'celebrities'])->set_file(__DIR__ . '/assets/image.jpg');
        $this->assertEquals('success', $output2->status);

        $output3 = $client->check(['nudity','wad','properties','type','faces', 'celebrities'])->set_bytes($binaryFile);
        $this->assertEquals('success', $output3->status);
    }

    public function test_feedback()
    {
        $client = new SightengineClient('1234', 'test');

        $feedback1 = $client->feedback('nudity', 'raw', 'https://sightengine.com/assets/img/examples/example5.jpg');
        $this->assertEquals('success', $feedback1->status);

        /* guzzle exception

        $feedback2 = $client->feedback('model9999', 'raw', 'https://sightengine.com/assets/img/examples/example5.jpg');
        $this->assertEquals('argument_error', $feedback2->error->type);
        $this->assertEquals('failure', $feedback2->status);

        $feedback3 = $client->feedback('nudity', 'raw9999', 'https://sightengine.com/assets/img/examples/example5.jpg');
        $this->assertEquals('argument_error', $feedback3->error->type);
        $this->assertEquals('failure', $feedback3->status); */

        $feedback4 = $client->feedback('nudity','raw', __DIR__ . '/assets/image.jpg');
        $this->assertEquals('success', $feedback4->status);
    }

    public function test_video()
    {
        $client = new SightengineClient('1234', 'test');
        $output = $client->check(['nudity','wad','properties','type','faces', 'celebrities'])->video('https://sightengine.com/assets/stream/examples/funfair.mp4','http://requestb.in/1nm1vw11');

        $this->assertEquals('success', $output->status);
    }

    public function test_video_sync()
    {
        $client = new SightengineClient('1234', 'test');
        $output = $client->check(['nudity','wad','properties','type','faces', 'celebrities'])->video_sync('https://sightengine.com/assets/stream/examples/funfair.mp4');

        $this->assertEquals('success', $output->status);
    }
}
?>
