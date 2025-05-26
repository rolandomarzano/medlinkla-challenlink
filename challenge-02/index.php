<?php

function noIterate($strArr)
{
    $NValue = $strArr[0];
    $KValue = $strArr[1];

    // Character frequency (exact counts required)
    $targetFreq = count_chars($KValue, 1);
    $frequency = [];

    $requiredUniqueChars = count($targetFreq);
    $charsSatisfied = 0;

    $left = 0;
    $right = 0;

    $match = [PHP_INT_MAX, 0, 0];

    $textLength = strlen($NValue);

    while ($right < $textLength) {
        $currentChar = $NValue[$right];
        $charCode = ord($currentChar);

        // Increase the frequency of that character in the search
        $frequency[$charCode] = ($frequency[$charCode] ?? 0) + 1;

        // Reached the required frequency for that character, increase the counter
        if (isset($targetFreq[$charCode]) && $frequency[$charCode] === $targetFreq[$charCode]) {
            $charsSatisfied++;
        }

        // Tried to reduce the search while maintaining the number of unique characters
        while ($charsSatisfied === $requiredUniqueChars && $left <= $right) {
            $windowLength = $right - $left + 1;
            if ($windowLength < $match[0]) {
                $match = [$windowLength, $left, $right];
            }

            $leftChar = $NValue[$left];
            $leftCode = ord($leftChar);
            $frequency[$leftCode]--;

            // Stops satisfying the required frequency for that character, decrease the counter
            if (isset($targetFreq[$leftCode]) && $frequency[$leftCode] < $targetFreq[$leftCode]) {
                $charsSatisfied--;
            }

            // Increase the search from the left
            $left++;
        }

        // Increase the search from the right
        $right++;
    }

    return $match[0] === PHP_INT_MAX
        ? "Not in string"
        : substr($NValue, $match[1], $match[0]);
}

// keep this function call here
echo noIterate(["ahffaksfajeeubsne", "jefaa"]);
echo "\n";

echo noIterate(["aaffhkksemckelloe", "fhea"]);
echo "\n";
