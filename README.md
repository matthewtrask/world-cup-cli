# world-cup-cli
CLI scripts to watch the world cup

### To Run

Clone this repository, cd to the project and run ```composer install``` from the root of the project. From there, you have (currently) three options

```
$ php bin/worldcup worldcup:team - returns list of teams and their groups
```

```
$ php bin/worldcup worldcup:matchday - returns the current days matches in real time
```

Optionally, you can run 

```
$ php bin/worldcup worldcup:matchday events - returns a list of events (subs, cards, goals) with time and player
```

I will be adding more on to this, this is just a initial round of fun stuff to use.
