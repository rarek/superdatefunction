<?php

require_once __DIR__ . '/../function.inc.php';

class MTimeRangeFunctionTest extends PHPUnit_Framework_TestCase {



    /**
     * @dataProvider additionProvider
     */
    public function testResult($sStart1, $sEnd1, $sStart2, $sEnd2, $expected, $sFormat) {
        $this->assertEquals($expected,time_range($sStart1,$sEnd1,$sStart2,$sEnd2,$sFormat));
    }


    /**
     *  @expectedException Exception
     */
    public function testNegWrongInput() {
        list($sStart1, $sEnd1, $sStart2, $sEnd2) = [
            '2017-05-12 06:08:23',
            '2017-05-13 12:11:52',
            '05/12 13:12:16',
            '2017-06-15 00:03:34',
        ];
        time_range($sStart1,$sEnd1,$sStart2,$sEnd2);
    }

    /**
     *  @expectedException Exception
     */
    public function testNegEqualDeclaration() {
        list($sStart1, $sEnd1, $sStart2, $sEnd2) = [
            '2017-05-12 06:08:23',
            '2017-05-13 12:11:52',
            '13:12:16',
            '00:03:34',
        ];
        time_range($sStart1,$sEnd1,$sStart2,$sEnd2);
    }


    /**
     *  @expectedException Exception
     */
    public function testNegWrongRanges() {
        list($sStart1, $sEnd1, $sStart2, $sEnd2) = [
            '06:08:23',
            '12:11:52',
            '13:12:16',
            '00:03:34',
        ];
        time_range($sStart1,$sEnd1,$sStart2,$sEnd2);
    }

    public function additionProvider()
    {
        return [
            'Format'  => [
                '2017-05-12 06:08:23',
                '2017-05-13 12:11:52',
                '2017-05-12 13:12:16',
                '2017-06-15 00:03:34',
                [
                    '13-12-16 2017/05/12',
                    '12-11-52 2017/05/13',
                ],
                "H-i-s Y/m/d"
            ],
            'Positive simple'  => [
                '2017-05-12 06:08:23',
                '2017-05-13 12:11:52',
                '2017-05-11 13:12:16',
                '2017-06-15 00:03:34',
                [
                    '2017-05-12 06:08:23.000000 +0300',
                    '2017-05-13 12:11:52.000000 +0300',
                ],
                "Y-m-d H:i:s.u O"
            ],
            'Border simple'  => [
                '2017-05-12 06:08:23',
                '2017-05-13 12:11:52.34567 +0700',
                '2017-05-13 12:11:52.34567 +0700',
                '2017-06-15 00:03:34',
                [
                    '2017-05-13 12:11:52.345670 +0700',
                    '2017-05-13 12:11:52.345670 +0700',
                ],
                "Y-m-d H:i:s.u O"
            ],
            'Negative simple'  => [
                '2017-05-12 06:08:23',
                '2017-05-12 13:12:16',
                '2017-05-13 12:11:52',
                '2017-06-15 00:03:34',
                false,
                "Y-m-d H:i:s.u O"
            ],

            'Positive complex'  => [
                '2017-05-12 06:08:23.54652 +0200',
                '2017-05-12 18:11:52.95748 -0700',
                '2017-05-13 02:12:16 +0900',
                '2017-06-15 00:03:34.87475',
                [
                    '2017-05-13 02:12:16.000000 +0900',
                    '2017-05-13 10:11:52.957480 +0900',
                ],
                "Y-m-d H:i:s.u O"
            ],
            'Border complex'  => [
                '2017-05-12 06:08:23.54652 +0200',
                '2017-05-12 19:25:52.54852 -0400',
                '2017-05-13 02:25:52.548520 +0300',
                '2017-06-15 00:03:34.87475',
                [
                    '2017-05-13 02:25:52.548520 +0300',
                    '2017-05-13 02:25:52.548520 +0300',
                ],
                "Y-m-d H:i:s.u O"
            ],
            'Negative complex'  => [
                '2017-05-12 06:08:23.54652 +0200',
                '2017-05-13 12:25:52.54852 +0900',
                '2017-05-12 19:25:52.548521 -0800',
                '2017-06-15 00:03:34.87475',
                false,
                "Y-m-d H:i:s.u O"
            ],
            'Positive short'  => [
                '06:08:23',
                '13:12:16',
                '12:11:52',
                '16:03:34',
                [
                    '12:11:52',
                    '13:12:16',
                ],
                "H:i:s"
            ],
            'Border short'  => [
                '06:08:23',
                '13:12:16.5245',
                '13:12:16.52450',
                '16:03:34',
                [
                    '13:12:16',
                    '13:12:16',
                ],
                "H:i:s"
            ],
            'Negative short'  => [
                '06:08:23',
                '13:12:16',
                '14:11:52',
                '23:03:34',
                false,
                "H:i:s"
            ],
        ];
    }
}