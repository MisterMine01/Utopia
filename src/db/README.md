# Database

## Summary

- [Database](#database)
  - [Summary](#summary)
  - [How to use ?](#how-to-use-)
    - [Requirements](#requirements)
    - [Usage](#usage)
    - [How to add a new card ?](#how-to-add-a-new-card-)
      - [JSON](#json)
      - [CSV](#csv)
    - [Add a new language](#add-a-new-language)
    - [Rarity](#rarity)
    - [PrimaryPhase and SecondaryPhase](#primaryphase-and-secondaryphase)
    - [Phase\_User](#phase_user)
      - [Type of a phase](#type-of-a-phase)
    - [Data](#data)
      - [image\_folder](#image_folder)
      - [function\_folder](#function_folder)
      - [phase\_folder](#phase_folder)
    - [Other](#other)
  - [Compile](#compile)


## How to use ?

### Requirements

- [python3](https://www.python.org/downloads/)
- a text editor

### Usage

For begin, you have the [`index.json`](Utopia/index.json) file. This file is the main file of the database. It's a json file who contain connection to the other json files.

```json
{
    "Cards": {
        "__comment": "This is her who give all the cards, they have two types of connection",
        "Id": "Cards.json",
        "*CSV*": "Link to a csv file with all the cards"
    },
    "lang": {
        "__comment": "This is her who give all the languages, the key is in the format of Country Code Language List",
        "fr_FR": "fr_FR.json",
        "en_US": "en_US.json"
    },
    "Rarity": {
        "__comment": "This is her who give all the rarities of cards, it use to create layout of cards",
        "Common": "Common.json",
        "Rare": "Rare.json"
    },
    "PrimaryPhase": {
        "__comment": "This is her who give all primary phases, more explanation to server/battle",
        "Battle": "Battle.json",
        "Defense": "Defense.json"
    },
	"SecondaryPhase": {
        "__comment": "This is her who give all secondary phases, more explanation to server/battle",
		"Damage": "File/Phase/Secondary/DamagePhase.json",
		"CreaWin": "File/Phase/Secondary/CreaWinPhase.json"
	},
	"data": {
        "__comment": "data for files who need to get compiled, head of card, function and phase",
		"image_folder": "index_part/image_data.json",
		"function_folder": "index_part/function_data.json",
		"phase_folder": "index_part/phase_data.json"
	},
    "Other": {
        "__comment": "Other data for the client",
		"state_color": "File/state.json"
    }
}
```

### How to add a new card ?

#### JSON

For json you need to declare the card in the index with a id for the card

A card look like this:

```json
{
    "att": 0,
    "def": 0,
    "price": 0,
    "rarity": "Common",
    "func": {
        "new": "FunctionName",
        "died": "FunctionName",
        "startTurn": "FunctionName",
        "endTurn": "FunctionName",
        "eachSend": "FunctionName"
    },
    "tags": {
        "primary": [],
        "secundary": []
    }
}
```

#### CSV

For csv your first line need to have all that name:

```json
[
    "id",
    "att",
    "def",
    "price",
    "rarity",
    "func_new",
    "func_died",
    "func_startturn",
    "func_endturn",
    "func_eachsend",
    "tags_primary",
    "tags_secondary"
]
```

After that just add your card.

### Add a new language

For add a new language you need to declare the language in the index with a id for the language

After that, add for each id of card the translation of the name and description

```json
{
    "id": {
        "name": "Name of the card",
        "description": "Description of the card"
    }
}
```

### Rarity

For add a new rarity you need to declare the rarity in the index with a id for the rarity

After that a rarity is just a list of image in rarity folder.

For append the head of the card just type `head` in the list.

The first image is the back of the card, the last image is the front of the card.

```json
[
    "C-back.png",
    "head",
    "C-front.png"
]
```

### PrimaryPhase and SecondaryPhase

the PrimaryPhase and SecondaryPhase works in the same way.

For add a new phase you need to declare the phase in the index with a id for the phase

They have a function calling when the phase is active, a type and a Phase_User.

```json
{
    "function": "FunctionName",
    "type": 4,
    "Phase_User": 0
}
```

### Phase_User

| Phase_User | Description                          |
| ---------- | ------------------------------------ |
| 0          | The user who is the turn, play       |
| 1          | The user who don't is the turn, play |

#### Type of a phase

| Type | Description                                                        |
| ---- | ------------------------------------------------------------------ |
| 0    | The user can choose only in his hand                               |
| 1    | The user can choose only in the field of the enemy                 |
| 2    | The user can choose only in his field                              |
| 3    | The user can choose only in the field of the enemy or in his hand  |
| 4    | The user can choose only in his field or in the field of the enemy |
| 5    | The user can choose only in his field or in his hand               |
| 6    | The user can choose everywhere                                     |

### Data

data need only 3 files:

```json
{
	"image_folder": "index_part/image_data.json",
	"function_folder": "index_part/function_data.json",
	"phase_folder": "index_part/phase_data.json"
}
```

#### image_folder

card is the head of all card, rarity is the layer of rarity

```json
{
	"card": "Image/Card",
	"rarity": "Image/Rarity"
}
```

#### function_folder

It's just a string with the path of the folder of the function
```json
"ServerCode/Function"
```

#### phase_folder

It's here they have the code of the phase, the primary and the secondary
```json
{
	"PrimaryPhase": "ServerCode/Phase/Primary",
	"SecondaryPhase": "ServerCode/Phase/Secondary"
}
```

### Other

In other you have all other things that the client can need

For the moment they have only `state_color` this is the color of a card in function of his state

```json
{
	"Alive": "#FFFFFF",
	"Dead": "#FFFFFF",
	"Attack": "#FF0000",
	"OnAttack": "#FFFB00"
}
```

## Compile

After creating your own database you need to compile it.

`python3 main.py`