<?php

/** Progress
 *  Linear progress - option for length
 *  Randomize chests, potions, enemies (entities)
 *  Difficulty increases puzzle difficulty, harder enemy count & potion chances
 *  Display progress with entities
 *    [----|--*-1---p--:----]
 *      | - door
 *      ! - puzzle door
 *      : - branching door
 *      * - chest
 *      p - potion
 *      1 - low level enemy
 *      2 - mid level enemy
 *      3 - top level enemy
 *  Shop between levels? - save stats to file
 *  Way to find health upgrades - chests, potion or certain amount of enemies killed?
 */

/** How to Generate
 *  10 spaces for now
 *  blank space between
 *  generate entities (chance per one)
 *    add space after entity
 *  only level 1 and 2 enemies and regular doors for now
 *  iterate over length of array
 *
 *  or just do custom designed paths?
 */


function path_gen($path_length, $entities) {
    $path = [];
    for ($i = 0; count($path) < $path_length + 5;) {
        $entity = $entities[array_rand($entities)];
        if ($entity != '-') {
            $path[] = $entity;
            $path[] = '-';
        }
        $path[] = '-';
    }
    return explode(' ', substr(implode($path, ' '), 0, $path_length * 2));
}

function path_return_dashes($path) {
    $path = explode(']', $path);
    $dash_positions = [];
    for ($i = 0; $i < count($path); $i++) {
        if ($path[$i] == '-') {
            $dash_positions[] = $i;
        }
    }
    return $dash_positions;
}

/**
 *  take path, positions of entity to insert (with probability) and how many to add
 *    generate an array of the length provided by $probability (1 in ??), with one item being 1, and the rest 0
 *    if random pick from the array is 1, pick randomly from dash positions array and insert in that position
 */
// ADD RELATIVE TO AMOUNT
function path_insert($path, $entity_arr) {
    $entity = $entity_arr[0];
    $probability = $entity_arr[1];
    $amount = round((strlen($path) / $entity_arr[2]) * 100);
    $prob_arr = [1];
    // generate probability array
    $prob_arr = array_pad($prob_arr, $probability, 0);
    // for the max amount to add
    $dashes = path_return_dashes($path);
    for ($i = 0; $i < $amount; $i++) {
        if ($prob_arr[array_rand($prob_arr)] == 1) {
            // replace random dash position with the entity
            $to_add = $dashes[array_rand($dashes)];
            //echo substr($path, $dashes[array_rand($dashes)]) . "\n";
            $path = substr_replace($path, $entity, $to_add, 1);
            $dashes = path_return_dashes($path);
        }
    }
    return str_replace(']', '', $path);
}

/**
 *  a string of dashes is created, then iterated over
 *    find which positions have dashes, replace
 */
function path_gen_replace($path_length, $entities) {
    $path = str_repeat('-]', $path_length - 1);
    $path .= '-';
    $path = path_insert($path, $entities[0]);
    // loop through $entities
    //$entity = $entities[array_rand($entities)];
    return explode(' ', $path);
}

function path_view($path) {
    echo '[';
    foreach ($path as $space) {
        echo $space;
    }
    echo "]\n";
}

/** How to Render
 *  track player position
 *  track entities
 *  loop for each space, interrupt for entity
 *  choose to move (add ranged and potions later)
 *  -p-1-!-*-3
 */
/** Colours
 *  Red for enemies \e[1;31m
 *  Blue for player \e[1;34m
 *  Yellow for chests \e[1;33m
 *  Light green for potions \e[1;32m
 *  Grey for doors \e[1;37m
 */
function path_play($path, $player) {
    $path_og = $path;
    for ($i = 0; $i < count($path); $i++) {
        switch ($path[$i]) {
            case ('!'):
                $path[$i] = "\e[1;37m!\e[0m";
                break;
            case (':'):
                $path[$i] = "\e[1;37m:\e[0m";
                break;
            case ('|'):
                $path[$i] = "\e[1;37m|\e[0m";
                break;
            case ('*'):
                $path[$i] = "\e[1;33m*\e[0m";
                break;
            case ('p'):
                $path[$i] = "\e[1;32mp\e[0m";
                break;
            case ('1'):
                $path[$i] = "\e[1;31m1\e[0m";
                break;
            case ('2'):
                $path[$i] = "\e[1;31m2\e[0m";
                break;
            case ('3'):
                $path[$i] = "\e[1;31m3\e[0m";
                break;
            default:
                break;
        }
    }
    while ($player['pos'] != count($path)) {
        array_splice($path, $player['pos'], 1, "\e[1;34mA\e[0m");
        if ($player['pos'] != 0) {
            switch ($path_og[$player['pos']-1]) {
                case ('!'):
                    $path[$player['pos']-1] = "\e[0;37m!\e[0m";
                    $past = "\e[0;37m!\e[0m";
                    break;
                case (':'):
                    $past = "\e[0;37m:\e[0m";
                    break;
                case ('|'):
                    $past = "\e[0;37m|\e[0m";
                    break;
                case ('*'):
                    $path[$player['pos']-1] = "\e[0;33m*\e[0m";
                    $past = "\e[0;33m*\e[0m";
                    break;
                default:
                    $past = "\e[0;37m-\e[0m";
                    break;
            }
            array_splice($path, $player['pos']-1, 1, $past);
        }

        path_view($path);
        $player['pos']++;
    }

};

// 2 in 3 chance of space
// 1 in 6 chance of level 1
// 1 in 12 chance of level 2
// 1 in 24 chance of door
$entities = [
    '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
    '1', '1', '1', '1',
    '2', '2',
    '|'
];

// entity, probability (1/??), amount relative to length (10)
$entities_prob = [
    ['1', 6, 30],
    ['2', 12, 10 ],
    ['|', 12, 20]
];

//$path = path_gen(9, $entities); // one less than desired length
//$path = path_gen_replace(10, $entities_prob);
//      | - door
//      ! - puzzle door
//      : - branching door
//      * - chest
//      p - potion
//      1 - low level enemy
//      2 - mid level enemy
//      3 - top level enemy
// pick from pre-defined paths
$pre_def = [
    '-]!]-]*]-]1]-]-]p]-',
    '-]-]2]-]|]-]-]*]-]-',
    '-]3]-]-]*]-]-]-]!]-',
    '-]!]-]!]-]-]*]-]!]-',
    '-]2]-]2]-]*]-]-]p]-'
];
$path = explode(']', $pre_def[array_rand($pre_def)]);

$path = [
    '-',
    'p',
    '-',
    '1',
    '-',
    '!',
    '-',
    '*',
    '-',
    '3',
];
//path_view($path);

/** Player stats
 *  Health - '3/10'
 *    display in red/orange/green based on percentage left
 *  Gold - '02'/'23'
 *    display in yellow
 *  Items
 *    weapons and potions: blue title, white bold items
 */

// testing path:
// -p-1-!-*-3
$path = [
    '-',
    'p',
    '-',
    '1',
    '-',
    '!',
    '-',
    '*',
    '-',
    '3'
];

$player = [
    'pos' => 0,
    'health current' => 5,
    'health max' => 5,
    'gold' => 0
];

path_play($path, $player);

/** Dice Movement
 *  6 sides
 *  Pick up potions and carry on
 *  Stop for enemies and chests
 *  Shield
 *    Move up to 4 spaces / use Ranged skill
 *  Sword / 3
 *    Move 3 spaces
 *  Skull / 2
 *    Move 2 spaces
 *  Sword & Skull / 1
 *    Move 1 spaces
 */

/** Dice Fighting
 *  6 sides
 *  Shield
 *    Defeat / use Melee skill
 *  Sword / 3
 *    Defeat
 *  Skull / 2
 *    Lose health equal to strength
 *  Sword & Skull / 1
 *    Defeat and lose health equal to strength
 */

/** Doors
 *  Regular door
 *    Pass through as normal, provide description
 *  Puzzle door
 *    Present player with number guess or mastermind game (depending on difficulty)
 *  Branching door
 *    Generate new paths, some with more enemies and rewards, others with more puzzles
 *      [----|--*-1---p---:----]
 *                        [-3----*---!-] one top level enemy for okay reward
 *                        [-2-2--*-p-!-] two mid level enemies for good reward - more chances for hits taken
 *                        [-!-!--*---!-] two puzzles for okay reward - no combat
 */

/** Chests
 *  Remove health detraction - only reward
 *  Shield
 *    Find 2 gold
 *  Sword / 3
 *    Find 1 gold
 *  Skull / 2
 *    Set back 1 space
 *  Sword & Skull / 1
 *    Find 1 gold and set back 1 space
 */

/** Potions
 *  Add later
 *  Usable at any time
 *  Each only usable once
 *  Life
 *    Restore 2 health
 *  Luck
 *    Re-roll the dice
 *  Speed
 *    Move up to 4 extra spaces
 *  Strength
 *    Defeat an adjacent monster
 */

/** Heroes / Skills
 *  Add later
 *  Barbarian
 *    Melee: defeat all adjacent monsters + move 1 space
 *  Wizard
 *    Ranged: defeat a monster up to 4 spaces away even around corners
 *  Druid
 *    Ranged: restore full health
 *  Knight
 *    Ranged: move up to 2 spaces and defeat an adjacent monster
 */

/** Enemies
 *  Randomly generate names and bios for higher levels
 *  Level 1
 *    1 Strength, default name
 *  Level 2
 *    2 Strength, random name
 *  Level 3
 *    3 Strength, random name and description
 */

/** Weapons - cost 3, sell back for 2
 *  Add later
 *  Axe
 *    Melee: defeat all adjacent monsters
 *  Wand
 *    Ranged: defeat a monster up to 3 spaces away
 *  Staff
 *    Ranged: restore up to 2 health
 *  Bow
 *    Ranged: defeat all monsters up to 5 spaces away (costs 1 health)
 *  Dagger
 *    Melee: defeat the monster and take 1 gold from the store
 *  Sword
 *    Melee: defeat the monster and take 2 gold from the store (costs 1 health)
 */