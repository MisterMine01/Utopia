# Utopia

## Summary

- [Utopia](#utopia)
  - [Summary](#summary)
  - [What is Utopia ?](#what-is-utopia-)
  - [How to play ?](#how-to-play-)
  - [What you have in this repository ?](#what-you-have-in-this-repository-)
    - [Client](#client)
    - [Database](#database)
    - [Server](#server)

## What is Utopia ?

Utopia is a game write in php, html, css and javascript. It's a card game where you have to get off the enemy's life points to 0.

:warning: This game is not finished yet and this version is maybe never finished. You can maybe play but i don't garantee that you will not have any problem. :warning:

## How to play ?

The rules can be change in each server but the basic rules can be found [here](src/server/battle/README.md).

## What you have in this repository ?

### [Client](src/client/)

The client is a web application. It's a single page who load all the other page when you need it. for play

[MORE INFO](src/client/README.md)

### [Database](src/db/)

The database is the basic database of cards... when compiled it's a json file and all png images.

[MORE INFO](src/db/README.md)

### [Server](src/server/)

The server is cut in 3 different parts.
- The battle part
- The principal part
- The website

[MORE INFO](src/server/README.md)