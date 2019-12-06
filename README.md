# Poker hands ranker

Written in php as an assignment to Azerion.

## Installation
In the root folder
```bash
composer install
```

## Usage

In the root folder type
```bash
php index.php
```
After the prompt appears, the path to the input file should be given.
By default it is in the sample folder and the name of the file is input.txt

```bash
sample/input.txt
```

The content of the input should be something like this:
```bash
A♥ 2♦ 3♠ 4♣ 5♦
4♠ J♠ 8♠ 2♠ 9♠
3♦ J♣ 8♠ 4♥ 2♠
7♣ 7♦ 7♠ K♣ 3♦
A♥ A♦ 8♣ 4♠ 7♥
J♥ J♦ J♠ J♣ 7♦
8♣ 7♣ 6♣ 5♣ 4♣
9♣ 8♦ 7♠ 6♦ 5♥
4♣ 4♠ 3♣ 3♦ Q♣
A♦ K♦ Q♦ J♦ 10♦
```
In one line 5 cards should be added, each with it's rank first, then it's suit second without any spaces. Between the cards should be one space.

The process starts in the Engine.php  
The input gets validated, then the program creates the Cards, Hands and the Deal.  
In the deal the hands have ranks, and they are compared to each other then placed in the correct order.  
At the end of the process, the result gets printed.