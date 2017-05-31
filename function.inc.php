<?php

/**
 * @param string $sStart1
 * @param string $sEnd1
 * @param string $sStart2
 * @param string $sEnd2
 * @param string $sEnd2
 * @throws Exception
 * @return array|bool
 */
function time_range($sStart1, $sEnd1, $sStart2, $sEnd2, $sReturnFormat = "Y-m-d H:i:s.u O") {
    $ret = [];
    date_default_timezone_set('Europe/Moscow');

    $oStart1 = new SuperDateTime($sStart1);
    $oStart2 = new SuperDateTime($sStart2);
    $oEnd1 = new SuperDateTime($sEnd1);
    $oEnd2 = new SuperDateTime($sEnd2);

    if ($oStart1->getTimestampAsFloat() > $oEnd1->getTimestampAsFloat()) throw new Exception("Start must be lesser than end for the first date range");
    if ($oStart2->getTimestampAsFloat() > $oEnd2->getTimestampAsFloat()) throw new Exception("Start must be lesser than end for the second date range");

    //check that all interval are in equal format
    //they all must have years, months, days or all must not
    if (1 != count(array_unique([
            $oStart1->hasFullDate(),
            $oStart2->hasFullDate(),
            $oEnd1->hasFullDate(),
            $oEnd2->hasFullDate()
        ]))) {
        throw new Exception('All dates must be in equal declaration - all must have years, months, days or all must not');
    }

    //check intersect
    if ($oEnd1->getTimestampAsFloat() >= $oStart2->getTimestampAsFloat()
    && $oEnd2->getTimestampAsFloat() >= $oStart1->getTimestampAsFloat()) {

        $oRetStart = ($oStart2->getTimestampAsFloat() >= $oStart1->getTimestampAsFloat()) ?
                    $oStart2 :
                    $oStart1;

        $oRetEnd = ($oEnd2->getTimestampAsFloat() <= $oEnd1->getTimestampAsFloat()) ?
            $oEnd2 :
            $oEnd1;

        //getting one equal timezone for the result
        $oRetEnd->setTimezone($oRetStart->getTimezone());
        $ret = [
            $oRetStart->format($sReturnFormat),
            $oRetEnd->format($sReturnFormat)
        ];
    }

    return empty($ret)? false : $ret;
}

class SuperDateTime extends DateTime  {

    /**
     * @var array
     */
    public $aParsedData = [];

    /**
     * @param string $time
     * @param DateTimeZone $timezone
     * @throws Exception
     */
    public function __construct($time='now', DateTimeZone $timezone=null) {
        $this->aParsedData = date_parse($time);
        if (false === $this->aParsedData['hour']
        || false === $this->aParsedData['minute'] ) {
            throw new Exception("Expects hour and minute to determine datetime range");
        }
        return parent::__construct($time,$timezone);
    }

    /**
     * @return bool
     */
    public function hasFullDate() {
        return is_int($this->aParsedData['year']) && is_int($this->aParsedData['month']) && is_int($this->aParsedData['day']);
    }

    /**
     * @return float
     */
    public function getTimestampAsFloat() {
        return   $timestamp = floatval($this->getTimestamp() . '.' . $this->format('u'));
    }
}