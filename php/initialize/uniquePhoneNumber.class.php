<?php
class Util
{
    private static $mobileSegment = [
        '134', '135', '136', '137', '138', '139', '150', '151', '152', '157', '130', '131', '132', '155', '186', '133', '153', '189',
    ];

    public function nextMobile()
    {
        $prefix = self::$mobileSegment[array_rand(self::$mobileSegment)];
        $middle = mt_rand(2000, 9000);
        $suffix = mt_rand(2000, 9000);

        return $prefix . $middle . $suffix;
    }
}
