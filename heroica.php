<?php

/** Progress
 *  Linear progress - option for length
 *  Randomize chests, potions, enemies (entities)
 *  Difficulty increases puzzle difficulty, harder enemy count & potion chances
 *  Display progress with entities
 *    [----|--*-1---p---#----]
 *      * - chest
 *      | - door
 *      ! - puzzle door
 *      # - branching door
 *      p - potion
 *      1 - low level enemy
 *      2 - mid level enemy
 *      3 - mid level enemy
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
    $path = explode(' ', $path);
    $dash_positions = [];
    for ($i = 0; $i < count($path); $i++) {
        if ($path[$i] == '-') {
            $dash_positions[] = $i;
        }
    }
    return $dash_positions;
}

function path_insert($path, $entity, $probability) {

}

/**
 *  could do it so that a string of dashes is created, then iterated over
 *    find which positions have dashes, replace
 */
function path_gen_replace($path_length, $entities) {
    $path = str_repeat('- ', $path_length);
    $dashes = path_return_dashes($path);
    $entity = $entities[array_rand($entities)];
    return explode(' ', $path);
}

function path_view($path) {
    echo '[';
    foreach ($path as $space) {
        echo $space;
    }
    echo "]\n";
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

#$path = path_gen(9, $entities); // one less than desired length
$path = path_gen_replace(10, $entities);

path_view($path);

/** How to Render
 *  track player position
 *  track entities
 *  loop for each space, interrupt for entity
 *  choose to move (add ranged and potions later)
 */

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
 *    Lose health equal to strength + (move back one space)
 *  Sword & Skull / 1
 *    Defeat and lose health equal to strength + (move back one space)
 */

/** Doors
 *  Regular door
 *    Pass through as normal, provide description
 *  Puzzle door
 *    Present player with number guess or mastermind game (depending on difficulty)
 *  Branching door
 *    Generate new paths, some with more enemies and rewards, others with more puzzles
 *      [----|--*-1---p---#----]
 *                        [-3----*---!-] one high level enemy for okay reward
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