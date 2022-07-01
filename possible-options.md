Things that can happen on a turn (one iteration)

roll key: shield, sword/3, skull/2, sword+skull/1

## Entities
1. Door - `|` - white bold
   * go through it
   * description
2. Puzzle door - `!` - white bold
   * generate a puzzle (mastermind / number guess)
   * go through it once complete
3. Branching door - `:` - white bold
   * generate other paths with varying enemies and puzzles
   *      [----|--*-1---p---:----]
   *                        [-3----*---!-] one top level enemy for okay reward
   *                        [-2-2--*-p-!-] two mid level enemies for good reward - more chances for hits taken
   *                        [-!-!--*---!-] two puzzles for okay reward - no combat
4. Chest - `*`
   * roll: 2 gold, 1 gold, back a space, 1 gold and back a space
5. Potion - `p`
   * available potions:
     1. Vitality
        * Restore your health to full
     2. Life
        * Restore your health halfway and double your maximum
     3. Stealth
        * Sneak past any enemies for five spaces, stealing 1 gold from each of them
   * generate which to pick up (based on remaining) (if all used, start over)
   * show text about adding to inventory
     * description of specific potion
6. Low Enemy - `1`
   * introduce enemy with 1 strength, default name (demon, goblin, etc.)
   * until enemy dead:
     * show melee skill, weapon, health, enemy strength
     * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health
   * show `defeated ??` with optional `for ?? gold`
   * add 1 to 'slain' count
7. Mid Enemy - `2`
   * introduce enemy with 2 strength, random name
   * until enemy dead:
     * show melee skill, weapon, health, enemy strength
     * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health
   * show `defeated ??` with optional `for ?? gold`
   * add 1 to 'slain' count
8. Top Enemy - `3`
   * introduce enemy with 3 strength, random name and description
   * until enemy dead:
      * show melee skill, weapon, health, enemy strength
      * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health
   * show `defeated ??` with optional `for ?? gold`
   * add 1 to 'slain' count

### Change fighting so each hit does one health, multiple turns per enemy depending on their strength?
### `?? hit you gruesomely/in a fit of rage` / `you delivered a gruesome/impactful blow to ??` 

### Shop between levels? - save stats to file
### Way to find health upgrades - chests, potion or certain amount of enemies killed?

### Add 1 health for each enemy defeated? - or a kind of combo system?

## Player stats to track and display
* Health `3/10`
    * display in red/orange/green based on percentage left
* Gold - `02`/`23`
    * display in yellow
* Items
    * list of weapons & potions
    * blue `items` title, white bold items
* Slain `01`
    * display in red

## What player can do
1. move
    * roll: 4, 3, 2, 1 
    * show dice roll - function for rolling?
    * show outcome
    * show progression - flag for movement that ignores menus
    * show `moved ?? spaces`
2. weapons
    * show gold, weapons in inventory
    * for each weapon
        1. inspect weapon
           * show name, description, skill
           * if skill is ranged:
             1. use skill
                * logic depending on skill - if requires enemies, warn 
                * show `brandishing your ??, you summon the skill ??`
                * logic and description of effects of skill
        2. sell weapon
            * remove related skill from skill inventory
            * remove weapon from weapon inventory
            * add weapon to shop
            * add gold
            * show `?? sold for ?? gold`
3. potions
   * show current potions
   * for each potion
     * description of effects
     * option to use
4. shop
   * show gold, shop weapons
   * buy weapon
      * if enough gold:
        * remove gold
        * remove weapon from shop
        * add weapon to weapon inventory
        * add related skill to skill inventory
        * show `?? bought for ?? gold`
      * if not enough gold:
        * show relevant message `Not enough gold!`

## Colours - close off with \e[0m
* Red for enemies \e[1;31m
* Blue for player \e[1;34m
* Yellow for chests \e[1;33m
* Light green for potions \e[1;32m
* Grey for doors \e[1;37m
* Cyan for system stuff \e[1;36m
* Faded grey for used doors and spaces \e[0;37m
* Faded yellow for used chests \e[0;33m

## Weapons / Skills
* Bow
  * Ranged: defeat a monster up to 3 spaces away
* Wand
  * Ranged: restore up to 2 health
* Staff
  * Ranged: defeat all monsters up to 5 spaces away (costs 1 health)
* Sword
  * Melee: defeat the monster and take 1 gold from the store
* Axe
  * Melee: defeat the monster and take 3 gold from the store (costs 1 health)

## Enemy array
name, strength
'the demon' / 'the elite minotaur' / 'the goblin king'