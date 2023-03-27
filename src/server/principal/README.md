# Principal server

## Summary

- [Principal server](#principal-server)
  - [Summary](#summary)
  - [Api](#api)
  - [client](#client)
    - [Create a new account](#create-a-new-account)
    - [Connect to an account](#connect-to-an-account)
    - [Change password](#change-password)
    - [Test if it's a principal server](#test-if-its-a-principal-server)
    - [Get all lang](#get-all-lang)
    - [Get image of a user](#get-image-of-a-user)
    - [Get name of a user](#get-name-of-a-user)
    - [Search a user](#search-a-user)
    - [Send an image](#send-an-image)
  - [Server](#server)


## Api

## client

### Create a new account

`POST /client?accounts=202`

```json
{
    "Username": "Username",
    "Password": "Password"
}
```

### Connect to an account

`POST /client?accounts=200`
```json
{
    "Username": "Username",
    "Password": "Password"
}
```

`POST /client?accounts=204`
```json
{
    "Username": "Username",
    "A-Token": "drfvbuiozsrevighuzergybiufvzererg10"
}
```

### Change password

`POST /client?accounts=206`
```json
{
    "Username": "Username",
    "old_password": "old_password",
    "new_password": "new_password"
}
```

### Test if it's a principal server

`POST /client?IfPrincipalServer=200`
```json
{}
```

### Get all lang

`POST /client?Language=200`
```json
{}
```

### Get image of a user

`POST /client?img=200`
```json
{
    "Token": "Token"
}
```

### Get name of a user

`POST /client?name=200`
```json
{
    "Token": "Token"
}
```

### Search a user

`POST /client?search=200`
```json
{
    "Username": "Username"
}
```

### Send an image

`POST /client?img=202`
```json
{
    "Token": "Token",
    "A-Token": "A-Token",
    "Img": "Img"
}
```

## Server

