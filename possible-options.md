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

## Player stats to track and display
* Health `3/10`
    * display in red/orange/green based on percentage left
* Gold - `02`/`23`
    * display in yellow
* Items
    * list of weapons & potions
    * blue `items` title, white bold items

## What player can do
1. move
    * show dice roll - function for rolling?
    * show outcome
    * show progression - flag for movement that ignores menus
    * show `moved ?? spaces`
2. shop
    * show gold, current weapons
    * for each weapon
        1. buy weapon
            * if enough gold:
                * remove gold
                * remove weapon from shop
                * add weapon to inventory
                * show `?? bought for ?? gold`
            * if not enough gold:
                * show relevant message `Not enough gold!`
        2. sell weapon
            * remove weapon from inventory
            * add weapon to shop
            * add gold
            * show `?? sold for ?? gold`
3. potions
   * show current potions
   * for each potion
     * description of effects
     * option to use
4. skills