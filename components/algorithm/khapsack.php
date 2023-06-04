<?php
// Knapsack algorithm to find the optimal combination of rooms within the constraints
function knapsack($availableRooms, $adults, $children) {
    $n = count($availableRooms);
    $dp = array_fill(0, $n + 1, array_fill(0, $adults + 1, array_fill(0, $children + 1, 0)));

    for ($i = 1; $i <= $n; $i++) {
        $room = $availableRooms[$i - 1];
        $roomAdultPrice = $room['adult_price'];
        $roomChildPrice = $room['kid_price'];
        $roomCapacity = $room['capacity'];
        $roomValue = 1; // We assume the value of each room is 1

        for ($j = 1; $j <= $adults; $j++) {
            for ($k = 0; $k <= $children; $k++) {
                if ($roomCapacity <= ($j + $k)) {
                    $dp[$i][$j][$k] = max(
                        $roomValue + $dp[$i - 1][$j - $roomCapacity][$k],
                        $dp[$i - 1][$j][$k],
                        ($k > 0) ? ($roomValue + $dp[$i - 1][$j - $roomCapacity][$k - 1]) : 0
                    );
                } else {
                    $dp[$i][$j][$k] = $dp[$i - 1][$j][$k];
                }
            }
        }
    }

    // Backtrack to find the selected rooms
    $selectedRooms = [];
    $i = $n;
    $j = $adults;
    $k = $children;
    while ($i > 0 && $j > 0 && $k >= 0) {
        if ($dp[$i][$j][$k] != $dp[$i - 1][$j][$k]) {
            $selectedRooms[] = $availableRooms[$i - 1];
            $j -= $availableRooms[$i - 1]['capacity'];
        } elseif ($k > 0 && $dp[$i][$j][$k] != $dp[$i - 1][$j][$k - 1]) {
            $selectedRooms[] = $availableRooms[$i - 1];
            $k -= 1;
            $j -= $availableRooms[$i - 1]['capacity'];
        }
        $i--;
    }

    return $selectedRooms;
}
?>