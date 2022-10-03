<?php

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

function red($text) {
    return "\e[1;31m$text\e[0m";
}

function green($text) {
    return "\e[1;32m$text\e[0m";
}

function yellow($text) {
    return "\e[1;33m$text\e[0m";
}

function blue($text) {
    return "\e[1;34m$text\e[0m";
}

function cyan($text) {
    return "\e[1;36m$text\e[0m";
}

function grey($text) {
    return "\e[1;37m$text\e[0m";
}

function yellow_faded($text) {
    return "\e[0;33m$text\e[0m";
}

function grey_faded($text) {
    return "\e[0;37m$text\e[0m";
}

function path_view($path) {
    echo '[';
    foreach ($path as $space) {
        echo $space;
    }
    echo "]\n";
}

function a_or_an($word) {
    if (in_array($word[0], ['a', 'e', 'i', 'o', 'u'])) {
        return 'an';
    } else {
        return 'a';
    }
} 

function enemy_generate($level) {
    $types = [
        'demon', 'goblin', 'mutant'
    ];

    $statuses = [
        'pre' => ['elite', 'esteemed', 'legendary', 'terrifying', 'mighty'],
        'post' => ['warrior', 'leader', 'king', 'knight']
    ];

    $names = [
        'Gog', 'Waldurk', 'Ithrion', 'Alran', 'Kelat'
    ];

    switch ($level) {
        case (1):
            return [
                // Name, Level
                'name' => $types[array_rand($types)],
                'level' => 1
            ];
            break;
        case (2):
            if (array_rand([1, 1]) == 1) {
                return [
                    'name' => $statuses['pre'][array_rand($statuses['pre'])] . ' ' . $types[array_rand($types)],
                    'level' => 2
                ];
            } else {
                return [
                    'name' => $types[array_rand($types)] . ' ' . $statuses['post'][array_rand($statuses['post'])],
                    'level' => 2
                ];
            }
            break;
        case (3):
            if (array_rand([1, 1]) == 1) {
                return [
                    'name' => $names[array_rand($names)] . ', the ' . ucwords($statuses['pre'][array_rand($statuses['pre'])] . ' ' . $types[array_rand($types)]),
                    'level' => 3
                ];
            } else {
                return [
                    'name' => $names[array_rand($names)] . ', the ' . ucwords($types[array_rand($types)] . ' ' . $statuses['post'][array_rand($statuses['post'])]),
                    'level' => 3
                ];
            }
    }
}

function mastermind($limit, $attempt_max, $hard) {
    echo cyan("You come across a door with an ancient mechanism.\nYou'll need to input the correct combination of 4 numbers to proceed.\n");
    sleep(2);
    echo cyan("You can only use the numbers from 0-" . $limit . ", including 0 itself.\n");
    sleep(2);
    echo cyan("Studying the mechanism, you see that you'll have " . $attempt_max . " attempts before the combination resets.\n");
    sleep(2);
    echo "Enter 4 numbers to input them into the mechanism.\n";

    $beaten = false;

    while (!$beaten) {
        // For the remaining, add a random one to the combo
        $combo = [];
        $remaining = [];
        for ($i = 0; $i <= $limit; $i++) {
            $remaining[] = $i;
        }
        for ($i = 0; $i < 4; $i++) {
            $rand = array_rand($remaining);
            $combo[] = $rand;
            unset($remaining[array_search($rand, $remaining)]);
        }

        $attempt_total = 0;
        $input_arr = [];
        $correct = 0;
        while (($correct != 4 || implode($input_arr) != implode($combo)) && $attempt_total < $attempt_max) {
            $attempt_total += 1;
            $input = readline();
            $sanitised = false;
            while (!$sanitised) {
                // If there are more or less than 4 characters
                if (strlen($input) != 4) {
                    echo "The ancient mechanism only has 4 spaces.\n";
                    $input = readline();
                    // If there are letters
                } elseif (preg_match('/[a-z]/', $input)) {
                    echo "Only numbers are usable to unlock this mechanism.\n";
                    $input = readline();
                    // If there are symbols
                } elseif (preg_match('/[^\p{L}\d\s@]/u', $input)) {
                    echo "Only numbers are usable to unlock this mechanism.\n";
                    $input = readline();
                    // If there are only numbers
                } elseif (preg_match('/[0-9]/', $input)) {
                    $low_enough = true;
                    foreach (str_split($input) as $num) {
                        if ($num > $limit) {
                            $low_enough = false;
                        }
                    }
                    // If any of the numbers are too high
                    if (!$low_enough) {
                        echo "The given numbers only go up to " . ($limit) . "!\n";
                        $input = readline();
                    }
                    // If there are duplicates
                    if (strlen(count_chars($input, 3)) != 4) {
                        echo "You are only given one of each number.\n";
                        $input = readline();
                    }
                    if ($low_enough && strlen(count_chars($input, 3)) == 4) {
                        $sanitised = true;
                    }
                }
            }

            $input_arr = str_split($input);

            $correct = 0;
            $half = 0;
            $wrong = 0;
            if ($hard) {
                for ($i = 0; $i < count($input_arr); $i++) {
                    // If at least right number in any place
                    if (in_array($input_arr[$i], $combo)) {
                        // If right place
                        if ($input_arr[$i] == $combo[$i]) {
                            $correct += 1;
                        } else {
                            $half += 1;
                        }
                    } else {
                        $wrong += 1;
                    }
                }
                echo "      " . green($correct) . " " . yellow($half) . " " . red($wrong);
            } else {
                for ($i = 0; $i < count($input_arr); $i++) {
                    // If at least right number in any place
                    if (in_array($input_arr[$i], $combo)) {
                        // If right place
                        if ($input_arr[$i] == $combo[$i]) {
                            $correct += 1;
                            echo green($input_arr[$i]);
                        } else {
                            echo yellow($input_arr[$i]);
                        }
                    } else {
                        echo red($input_arr[$i]);
                    }
                }
            }
            echo "  Attempt $attempt_total";
            // If all correct
            if ($correct == 4 || implode($input_arr) == implode($combo)) {
                echo cyan("\nDust falls as the ancient lock opens.");
                sleep(2);
                $beaten = true;
            }
            echo "\n";
        }
        if ($attempt_total == $attempt_max && ($correct == 4 || implode($input_arr) == implode($combo))) {
            echo cyan("You took too many attempts, and the ancient lock reconfigured itself!\n");
        }
    }

}

function path_play($path, $player, $potions) {
    $moving = 0;
    $path_og = $path;
    for ($i = 0; $i < count($path); $i++) {
        switch ($path[$i]) {
            case ('!'):
                $path[$i] = grey('!');
                break;
            case (':'):
                $path[$i] = grey(':');
                break;
            case ('|'):
                $path[$i] = grey('|');
                break;
            case ('*'):
                $path[$i] = yellow('*');
                break;
            case ('p'):
                $path[$i] = green('p');
                break;
            case ('1'):
                $path[$i] = red('1');
                break;
            case ('2'):
                $path[$i] = red('2');
                break;
            case ('3'):
                $path[$i] = red('3');
                break;
            default:
                break;
        }
    }
    while ($player['health current'] > 0) {
        while ($player['pos'] != count($path)) {
            if ($player['health current'] == 0) {
                break;
            }
            // check for entities
            // need to be colour versions
            switch ($path[$player['pos']]) {
                case (grey('!')):
                    door_puzzle();
                    break;
                case (grey(':')):
                    door_branch();
                    break;
                case (grey('|')):
                    door();
                    break;
                case (yellow('*')):
                    $player = chest($player, $path);
                    break;
                case (green('p')):
                    $player = potion_get($player, $potions);
                    break;
                case (red('1')):
                    $enemy = enemy_generate(1);
                    echo cyan('An ') . red('enemy ') . cyan("approaches!\n");
                    sleep(1);
                    echo cyan('As your fear rises, you see that your opponent is ' . a_or_an($enemy['name']) . ' ');
                    echo red($enemy['name'] . '!') . "\n";
                    $player = fight($player, $enemy);
                    break;
                case (red('2')):
                    $enemy = enemy_generate(2);
                    echo cyan('An ') . red('enemy ') . cyan("approaches!\n");
                    sleep(1);
                    echo cyan('As your fear rises, you see that your opponent is ' . a_or_an($enemy['name']) . ' ');
                    echo red($enemy['name'] . '!') . "\n";
                    $player = fight($player, $enemy);
                    break;
                case (red('3')):
                    $enemy = enemy_generate(3);
                    echo cyan('An ') . red('enemy ') . cyan("approaches!\n");
                    sleep(1);
                    echo cyan('As your fear rises, you see that your opponent is ');
                    echo red($enemy['name'] . '!') . "\n";
                    $player = fight($player, $enemy);
                    break;
                default:
                    break;
            }
            if ($player['health current'] == 0) {
                break;
            }
            array_splice($path, $player['pos'], 1, blue('A'));
            if ($player['pos'] != 0) {
                switch ($path_og[$player['pos']-1]) {
                    case ('!'):
                        $path[$player['pos']-1] = grey_faded('!');
                        $past = grey_faded('!');
                        break;
                    case (':'):
                        $past = grey_faded(':');
                        break;
                    case ('|'):
                        $past = grey_faded('|');
                        break;
                    case ('*'):
                        $path[$player['pos']-1] = ('*');
                        $past = yellow_faded('*');
                        break;
                    default:
                        $past = grey_faded('-');
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
                    if ($moving + 1 == 1) {
                        echo 'Moving ' . ($moving + 1) . " space\n";
                    } else {
                        echo 'Moving ' . ($moving + 1) . " spaces\n";
                    }
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
        if ($player['health current'] == 0) {
            break;
        }
        echo green("Congratulations! You made it through with:\n");
        show_stats($player, true);
        exit();
    }
    echo red("Game over!\n");
    show_stats($player, false);
}

function show_health($player) {
    echo blue('Health') . ': ';
    $health_div = $player['health current'] / $player['health max'];
    if ($health_div > .66) {
        echo green($player['health current']);
    } elseif ($health_div <= .66 && $health_div > .33) {
        echo yellow($player['health current']);
    } else {
        echo red($player['health current']);
    }
    echo '/' . $player['health max'];
}

function show_stats($player, $show_health) {
    if ($show_health) {
        show_health($player);
        echo cyan(' | ');
    }
    echo blue('Gold') . ': ' . yellow($player['gold']) . cyan(' | ');
    echo blue('Slain') . ': ' . red($player['slain']) . "\n";
}

function door_puzzle() {
    //mastermind(9, 6, false);
}

function door_branch() {
    // this requires path gen to be fully refined, which it currently isn't
}

function door() {
    $options = [
        "Narrowly avoiding a splinter, you push open the door.\n",
        "The hinges of the door squeak as it opens.\n",
        "You just manage to summon enough strength to open the door.\n"
    ];
    echo cyan($options[array_rand($options)]);
    sleep(2);
}

function chest($player, $path) {
    echo cyan("You come across a rusted chest.\n");
    sleep(2);

    $outcome = ['2g', '2g', '1g', '1g', 'back', '1g back', 'back', '1g back', 'back', 'back'][array_rand(['2g', '2g', '1g', '1g', 'back', '1g back', 'back', '1g back', 'back', 'back'])];

    if ($outcome == '2g') {
        echo cyan('Heaving it open, you find ') . yellow("2 gold!\n");
        $player['gold']++;
        $player['gold']++;
    } elseif ($outcome == '1g') {
        echo cyan('Heaving it open, you find ') . yellow("1 gold!\n");
        $player['gold']++;
    } elseif ($outcome == 'back') {
        echo cyan("Heaving it open, you find that the chest is a trap!\n");
        sleep(2);
        echo cyan('You narrowly avoid ') . red("certain death") . cyan(", losing 1 Health!\n");
        sleep(1);
        $player['health current'] -= 1;
        $path[$player['pos']+1] = yellow_faded('*');
    } elseif ($outcome == '1g back') {
        echo cyan("Heaving it open, you find that the chest is a trap!\n");
        sleep(2);
        echo cyan('You narrowly avoid ') . red('certain death') . cyan(", losing 1 Health!\n") . cyan("\nWhoever laid the trap was clumsy: you find ") . yellow("2 gold!\n");
        $player['health current'] -= 1;
        sleep(1);
        $path[$player['pos']+1] = yellow_faded('*');
        $player['gold']++;
    }
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
    echo 'Picked up a ' . cyan($potion[0]) . "!\n";
    $player['inventory']['potions'][] = $potion;
    return $player;
}

function potion_view($player) {
    if (empty($player['inventory']['potions'])) {
        echo "\n" . cyan('No potions to show!') . "\n";
    } else {
        $potions = $player['inventory']['potions'];
        echo "\nPotions:\n";
        foreach ($potions as $potion) {
            echo'  ' . cyan($potion[0]) . ': ' . $potion[1] . "\n";
        }
    }
    $player['pos']--;
    return $player;
}

/**
 * introduce enemy with 1 strength, default name (demon, goblin, etc.) / 2 strength, random name / 3 strength, random name and description
 * until enemy dead:
     * show melee skill, weapon, health, enemy strength
     * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health
 * show `defeated ??` with optional `for ?? gold`
 * add 1 to 'slain' count
 */

function fight($player, $enemy) {
    // while enemy alive
    //  while player alive
    //  if player dies, say game over, slain by ??, stats
    $name_cap = ucfirst($enemy['name']); // not sure why we do this, might remove
    if ($enemy['level'] == 1 || $enemy['level'] == 2) {
        $name_cap = 'the ' . $name_cap;
    }
    $enemy_dead = false;
    while (!$enemy_dead) {
        while ($player['health current'] > 0) {
            show_health($player);
            //echo cyan(' | ') . red($name_cap) . blue(' Strength') . ': ' . red($enemy['level']); // can scrap, just say 'hit for 1'
            //echo cyan(' | ') . red($name_cap) . blue(' Health') . ': ' . red($enemy['health']) . "\n";
            
            exit;
        }
    }
    return $player;
}

# Show and take input for choices
function show_choice($choices) {
    # Initial spacer
    echo "    ";
    # For each choice
    for ($i = 0; $i < count($choices); $i++) {
        # Show the choice
        echo(blue($i+1) . '. ' . $choices[$i] . "\n");
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

// testing path:
// -p-1-!-*-3
$path = [
    '-',
    '|',
    'p',
    '*',
    '1',
    '!',
    '-',
    '*',
    '-',
    '3'
];

// Fighting test path
$path = [
    '-',
    '1',
    '2',
    '3',

];

$player = [
    'pos' => 0,
    'health current' => 5,
    'health max' => 5,
    'gold' => 0,
    'slain' => 0,
    'chest opened' => false,
    'inventory' => [
        'weapons' => [
            // name => description, skill, price
            'Short Sword' => ['Basic sword with no skill. Cannot be sold.', null, null]
        ],
        'potions' => [
            // name => description
            //['Flask of Water', "A flask filled with grey water. Despite the odd taste, it keeps you hydrated. \e[0;33mCannot be sold.\e[0m"]
        ]
    ]
];

$potions = [
    // name, description
    ['Chalice of Vitality', "The chalice feels cold in your hands. \e[0;33mAllows you to restore your health to full.\e[0m"],
    ['Life Vial', "A thin vial that you hold gently. It is filled with a red liquid. \e[0;33mAllows you to restore your health halfway and double your maximum.\e[0m"],
    ['Stealth Ointment', "The ointment has a strange texture and smell. \e[0;33mAllows you to sneak for five spaces, stealing 1 gold from each enemy you pass.\e[0m"]
];

path_play($path, $player, $potions);