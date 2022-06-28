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
function path_play($path, $player, $potions) {
    $moving = 0;
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
    while ($player['health current'] > 0) {
        while ($player['pos'] != count($path)) {
            // check for entities
            // need to be colour versions
            switch ($path[$player['pos']]) {
                case ("\e[1;37m!\e[0m"):
                    door_puzzle();
                    break;
                case ("\e[1;37m:\e[0m"):
                    door_branch();
                    break;
                case ("\e[1;37m|\e[0m"):
                    door();
                    break;
                case ("\e[1;33m*\e[0m"):
                    $player = chest($player);
                    break;
                case ("\e[1;32mp\e[0m"):
                    $player = potion_get($player, $potions);
                    break;
                case ("\e[1;31m1\e[0m"):
                    $player = fight($player, 1);
                    break;
                case ("\e[1;31m2\e[0m"):
                    $player = fight($player, 2);
                    break;
                case ("\e[1;31m3\e[0m"):
                    $player = fight($player, 3);
                    break;
                default:
                    break;
            }
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


            // check if already moving
            if (!$moving) {
                // display stats
                show_stats($player, true);
                $choice = show_choice(['move', 'weapons', 'potions', 'shop']);
                if ($choice == 'move') {
                    $options = [
                        3, 2, 2, 1, 1, 0
                    ];
                    $moving = $options[array_rand($options)];
                    echo "Moving " . ($moving + 1) . " spaces\n";
                } elseif ($choice == 'weapons') {
                    exit('not yet implemented');
                } elseif ($choice == 'potions') {
                    $player = potion_view($player);
                } elseif ($choice == 'shop') {
                    exit('not yet implemented');
                } else {
                    exit('Something isn\'t right...');
                }
            } else {
                $moving--;
            }
            $player['pos']++;
            sleep(1);
        }
        // congrats text, print statistics
    }
    echo "\e[1;31mGame over!\e[0m\n";
    show_stats($player, false);
}
function show_stats($player, $show_health) {
    if ($show_health) {
        echo "\e[1;34mHealth\e[0m: ";
        $health_div = $player['health current'] / $player['health max'];
        if ($health_div > .66) {
            echo "\e[1;32m";
        } elseif ($health_div <= .66 && $health_div > .33) {
            echo "\e[1;33m";
        } else {
            echo "\e[1;31m";
        }
        echo $player['health current'] . "\e[0m/" . $player['health max'] . "\e[1;36m | ";
    }
    echo "\e[1;34mGold\e[0m: \e[1;33m" . $player['gold'] . "\e[0m\e[1;36m | ";
    echo "\e[1;34mSlain\e[0m: \e[1;31m" . $player['slain'] . "\e[0m\n";
}

function door_puzzle() {
}

function door_branch() {
}

function door() {
}

function chest($player) {
    return $player;
}

function potion_get($player, $potions) {
    $potion_flag = false;
    while (!$potion_flag) {
        $potion = $potions[array_rand($potions)];
        if (!array_key_exists($potion[0], $player['inventory']['potions'])) {
            $potion_flag = true;
        }
    }
    echo "Picked up a \e[1;36m" . $potion[0] . "\e[0m!\n";
    $player['inventory']['potions'][] = $potion;
    return $player;
}

function potion_view($player) {
    $potions = $player['inventory']['potions'];
    echo "\nPotions:\n";
    foreach ($potions as $potion) {
        echo "  \e[1;36m " . $potion[0] . "\e[0m: " . $potion[1] . "\n";
    }
    $player['pos']--;
    return $player;
}

function fight($player, $strength) {
    return $player;
}

# Show and take input for choices
function show_choice($choices) {
    # Initial spacer
    echo "    ";
    # For each choice
    for ($i = 0; $i < count($choices); $i++) {
        # Show the choice"\e[1;34mA\e[0m"
        echo("\e[1;34m" . ($i+1) . "\e[0m. " . $choices[$i] . "\n");
        # If it is the last
        if ($i != count($choices) - 1) {
            # Intermediate spacer
            echo "    ";
        }
    }
    # Take user input
    $input = readline('> ');
    $forward = false;
    $to_return = null;
    while (!$forward) {
        # Loop through each choice
        foreach ($choices as $choice) {
            # If the input is only numbers, is within the range of choices and is equal to the current choice
            if (preg_match('/[0-9]/', $input) && !preg_match('/[a-z]/', $input) && $input <= count($choices) && $choices[$input-1] == $choice) {
                # Set the current choice to be returned
                $to_return = $choice;
                # If the input is the same as the current choice
            } elseif (strtolower($input) == strtolower($choice)) {
                # Set the current choice to be returned
                $to_return = $choice;
            }
        }
        # If there is something to return
        if ($to_return) {
            # End the loop
            $forward = true;
            # If there isn't something to return (i.e. the input is invalid)
        } else {
            echo("\nInvalid input." . "\n");
            # Take user input again
            $input = readline('> ');
        }
    }
    # Return the assigned value
    return $to_return;
}

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
    '|',
    'p',
    '-',
    '1',
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
    'gold' => 0,
    'slain' => 0,
    'inventory' => [
        'weapons' => [
            // name => description, skill, price
            'Short Sword' => ['Basic sword with no skill. Cannot be sold.', null, null]
        ],
        'potions' => [
            // name => description
            ['Flask of Water', "A flask filled with grey water. Despite the odd taste, it keeps you hydrated. \e[0;33mCannot be sold.\e[0m"]
        ]
    ]
];

$potions = [
    // name, description
    ['Chalice of Vitality', "The chalice feels cold in your hands. \e[0;33mAllows you to restore your health to full.\e[0m"],
    ['Life Vial', "A thin vial that you hold gently. It is filled with a red liquid. \e[0;33mAllows you to restore your health halfway and double your maximum.\e[0m"],
    ['Stealth Ointment', "The ointment has a strange texture, and an even stranger smell. \e[0;33mAllows you to sneak for five spaces, stealing 1 gold from each enemy you pass.\e[0m"]
];

path_play($path, $player, $potions);

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
 *  Descriptions for each skill
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