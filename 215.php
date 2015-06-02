<?php
/**
 * Reddit daily code challenge #215
 * http://www.reddit.com/r/dailyprogrammer/comments/36cyxf/20150518_challenge_215_easy_sad_cycles/
 */

echo "Enter base (b): ";
$b = (int) trim(fgets(STDIN));

echo "Enter starting number (n): ";
$n = (int) trim(fgets(STDIN));

$cycle = calcSadCycle($b, $n);

echo PHP_EOL . "The {$b}-sad cycle for {$n}";
if ($cycle === false) {
    echo " could not be determined" . PHP_EOL;
} else {
    echo " is:" . PHP_EOL . join(', ', $cycle) . PHP_EOL;
}

/**
 * Calculate the b-sad cycle for n
 *
 * @param $exp Exponent (b)
 * @param $number Number (n)
 * @param $history Numbers we encountered in the past
 * @param $pattern Potential cycle of numbers
 * @return array|false An array containing the numbers in the b-sad cycle for n,
 *     or false if no cycle could be detected
 */
function calcSadCycle($exp, $number, array $history = [], array $pattern = [])
{
    // Safety check to prevent infinite loop
    static $runs = 0;
    if (++$runs > 10000) {
        return false;
    }

    // Get the sum of each digit raised to the exponent power
    $newNumber = 0;
    foreach (str_split((string)$number) as $digit) {
        $newNumber += pow($digit, $exp);
    }

    // Check for a cycle
    $pattern[] = $newNumber;
    if (!in_array($newNumber, $history)) {
        // New number is not in history so no cycle yet
        $history = array_merge($history, $pattern);
        $pattern = [];
    } else {
        // Remove everything in history up to, but not including, first number in pattern
        $history = array_slice($history, array_search($pattern[0], $history));
        if ($pattern === $history) {
            // Found the cycle!
            return $pattern;
        }
    }

    return calcSadCycle($exp, $newNumber, $history, $pattern);
}