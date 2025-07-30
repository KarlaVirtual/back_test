<?php
 /**
 * File: SafetyPayProxy.php
 * Author: SafetyPay Inc.
 * Description: Util funcionts
 * @version 2.0
 * @package class
 * @license Open Software License (OSL 3.0)
 * Copyright 2012-2016 SafetyPay Inc. All rights reserved.
*******************************************************************************/

function do_offset($level)
{
    $offset = ''; // offset for subarry
    for ($i=1; $i<$level ;$i++)
        $offset = $offset . '<td></td>';
    return $offset;
}
