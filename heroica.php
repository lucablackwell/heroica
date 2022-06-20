<?php

/* Progress
 *  Linear progress - option for length
 *  Randomize chests, potions, enemies (entities)
 *  Difficulty increases puzzle difficulty, harder enemy count & potion chances
 *  Display progress with entities
 *    [----|--*-E---p---E----]
 *      * - chest
 *      | - door
 *      p - potion
 *      E - enemy
 *  No point in doors with keys because linear
 *    Doors with puzzles instead
 *      Guess the number / mastermind
 *  Generate different paths - one with more enemies and fewer puzzles?
 */
#git test
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
 *  Shield
 *    Find 2 gold
 *  Sword / 3
 *    Find 1 gold
 *  Skull / 2
 *    Lose 1 health
 *  Sword & Skull / 1
 *    Find 1 gold and lose 1 health
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
 *  Barbarian
 *    Melee: defeat all adjacent monsters + move 1 space
 *  Wizard
 *    Ranged: defeat a monster up to 4 spaces away even around corners
 *  Druid
 *    Ranged: restore full health
 *  Knight
 *    Ranged: move up to 2 spaces and defeat an adjacent monster
 */

/* Monsters
 *  Goblin Warrior
 *    1 Strength
 *  Goblin Guardian
 *    2 Strength
 *  Goblin King
 *    3 Strength
 */

/* Weapons - cost 3, sell back for 2
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