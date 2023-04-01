# Battle server

## Summary

- [Battle server](#battle-server)
  - [Summary](#summary)
  - [Api](#api)
    - [MatchMaking](#matchmaking)
      - [Get the server id](#get-the-server-id)
      - [Get database version](#get-database-version)
      - [Get the database](#get-the-database)
      - [Get the image of a card](#get-the-image-of-a-card)
      - [test if it's a battle server](#test-if-its-a-battle-server)
      - [ping the battle server](#ping-the-battle-server)
    - [Principal](#principal)
      - [Set the id of the server](#set-the-id-of-the-server)
      - [Add a new client to the server](#add-a-new-client-to-the-server)
    - [Battle](#battle)


## Api

### MatchMaking

#### Get the server id

`POST ?ServerId=200`
```json
{}
```

#### Get database version

`POST ?UtopiaVersion=200`
```json
{}
```

#### Get the database

`POST ?Utopia=200`
```json
{}
```

#### Get the image of a card

`POST ?Image=200`
```json
{
    "idImage": "idImage",
    "language": "language"
}
```

#### test if it's a battle server

`POST ?IfBattleServer=200`
```json
{}
```

#### ping the battle server

`POST ?ping=200`
```json
{
    "Token": "Token",
    "B-Token": "B-Token"
}
```

### Principal

#### Set the id of the server

`POST /PrincipalApi?id=200`
```json
{
    "newId": "newId",
    "ApiKey": "ApiKey"
}
```

#### Add a new client to the server

`POST /PrincipalApi?client=200`
```json
{
    "Token": "Token",
    "B-Token": "B-Token",
    "ApiKey": "ApiKey"
}
```

### Battle