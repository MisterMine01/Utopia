SELECT Stars.G_Token, r.Name
FROM Stars
    INNER JOIN Gigly_Right r on Stars.R_Id = r.R_Id
    INNER JOIN Gigly_Account on Stars.G_Token = Gigly_Account.G_Token
WHERE Stars.S_Id = ?