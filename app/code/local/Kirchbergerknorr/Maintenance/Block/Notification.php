<?php

class Kirchbergerknorr_Maintenance_Block_Notification extends Mage_Core_Block_Template
{

    public function getMessage()
    {
        return Mage::getStoreConfig('core/maintenance/message');
    }

    public function showMaintenance()
    {
        $cookie = Mage::getModel('core/cookie')->get('showMaintenance');
        return $cookie != 'false';
    }

    public function isActive()
    {
        $current = $this->getCurrentTimestamp();
        return $current >= $this->getStartTimestamp() && $current <= $this->getEndTimestamp();
    }

    public function getStartTimestamp()
    {
        return $this->getTimestamp(Mage::getStoreConfig('core/maintenance/startdate'), Mage::getStoreConfig('core/maintenance/starttime'));
    }

    public function getEndTimestamp()
    {
        return $this->getTimestamp(Mage::getStoreConfig('core/maintenance/enddate'), Mage::getStoreConfig('core/maintenance/endtime'));
    }

    public function getCurrentTimestamp()
    {
        $current = localtime(time(), true);
        $day = $current['tm_mday'];
        $month = 1 + $current['tm_mon'];
        $year = 1900 + $current['tm_year'];
        $date = array(
            strlen($day) < 2 ? '0' . $day : $day,
            strlen($month) < 2 ? '0' . $month : $month,
            $year
        );
        $time = array (
            $current['tm_hour'],
            $current['tm_min'],
            $current['tm_sec']
        );
        return $this->getTimestamp(join('.', $date), join(',', $time));
    }

    private function getTimestamp($date, $time)
    {
        $seconds = strtotime($date);
        $split = explode(',', $time);
        $seconds += $split[0] * 60 * 60;
        $seconds += $split[1] * 60;
        $seconds += $split[2];
        return $seconds;
    }
}