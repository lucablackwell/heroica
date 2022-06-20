<?php

/* Progress
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
 *  No point in doors with keys because linear
 *    Doors with puzzles instead
 *      Guess the number / mastermind
 *  Generate different paths - one with more enemies and fewer puzzles / higher level enemies for higher reward
 *    [----|--*-1---p---#----]
 *                      [-3----*---!-] one high level enemy for okay reward
 *                      [-2-2--*-p-!-] two mid level enemies for good reward - more chances for hits taken
 *                      [-!-!--*---!-] two puzzles for okay reward - no combat
 *  Shop between levels? - save stats to file
 *  Way to find health upgrades - chests, potion or certain amount of enemies killed?
 */

/* Dice Movement
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

/* Dice Fighting
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

/* Chests
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

/* Potions
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

/* Heroes / Skills
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

/* Enemies
 *  Randomly generate names and bios for higher levels
 *  Level 1
 *    1 Strength, default name
 *  Level 2
 *    2 Strength, random name
 *  Level 3
 *    3 Strength, random name and description
 */

/* Weapons - cost 3, sell back for 2
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