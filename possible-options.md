Things that can happen on a turn (one iteration)

roll key: shield, sword/3, skull/2, sword+skull/1

## Entities
1. Door - `|`
   * go through it
   * description
2. Puzzle door - `!`
   * generate a puzzle (mastermind / number guess)
   * go through it once complete
3. Branching door - `:`
   * generate other paths with varying enemies and puzzles
4. Chest - `*`
   * roll: 2 gold, 1 gold, back a space, 1 gold and back a space
5. Potion - `p`
   * available potions:
     1. Life
        * restore 2 health
     2. Luck
        * re-roll the dice
     3. Speed
        * move up to 4 extra spaces
     4. Strength
        * defeat an adjacent monster
   * generate which to pick up (based on remaining) (if all used, start over)
   * show text about adding to inventory
     * description of specific potion
6. Low Enemy - `1`
   * introduce enemy with 1 strength, default name (demon, goblin, etc.)
   * until enemy dead:
     * show melee skill, weapon, health, enemy strength
     * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health
7. Mid Enemy - `2`
   * introduce enemy with 2 strength, random name
   * until enemy dead:
     * show melee skill, weapon, health, enemy strength
     * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health
8. Top Enemy - `3`
   * introduce enemy with 3 strength, random name and description
   * until enemy dead:
      * show melee skill, weapon, health, enemy strength
      * roll: kill/melee skill, kill, lose as much health as strength, kill and lose health

## Player stats to track and display
* Health `3/10`
  * display in red/orange/green based on percentage left
* Gold - `02`/`23`
  * display in yellow
* Items
  * list of weapons & potions
  * blue `items` title, white bold items