<?php

function findPoint($strArr)
{

    $array1 = array_map('trim', explode(',', $strArr[0]));
    $array2 = array_map('trim', explode(',', $strArr[1]));

    $intersection = array_intersect($array1, $array2);

    if(!empty($intersection)) {
        return implode(',', $intersection);
    } 

    return 'false';
}

// keep this function call here
echo findPoint(['1, 3, 4, 7, 13', '1, 2, 4, 13, 15']);
echo "\n";

echo findPoint(['1, 3, 9, 10, 17, 18', '1, 4, 9, 10']);
echo "\n";
