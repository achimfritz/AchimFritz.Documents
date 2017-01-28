<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Facet\TvRecording;

/**
 * Testcase for TvRecording
 */
class TvRecordingTest extends \TYPO3\Flow\Tests\UnitTestCase
{

    /**
     * @return array
     */
    public static function timeProvider()
    {
        return array(
            array('starttime' => '20:15', 'endtime' => '21:45', 'length' => 90),
            array('starttime' => '23:15', 'endtime' => '00:45', 'length' => 90),
            array('starttime' => '20:45', 'endtime' => '21:15', 'length' => 30),
            array('starttime' => '23:45', 'endtime' => '00:15', 'length' => 30),
            array('starttime' => '23:45', 'endtime' => '01:15', 'length' => 90),
            array('starttime' => '20:45', 'endtime' => '20:15', 'length' => -30),
            array('starttime' => '21:45', 'endtime' => '20:15', 'length' => 1350)
        );
    }

    /**
     * @test
     * @dataProvider timeProvider
     */
    public function getLengthCalculateLength($starttime, $endtime, $length)
    {
        $tvRecording = new TvRecording();
        $tvRecording->setStarttime($starttime);
        $tvRecording->setEndtime($endtime);
        $this->assertSame($length, $tvRecording->getLength());
    }
}
