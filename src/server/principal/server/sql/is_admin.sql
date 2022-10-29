SELECT S_Id,
    Utopia_Server.Name,
    Url,
    Gigly_Account.Username
FROM Utopia_Server
    INNER JOIN Gigly_Right on Gigly_Account.R_Id = Gigly_Right.R_Id
    INNER JOIN Gigly_Account on Gigly_Account.G_Token = Utopia_Server.G_Token
WHERE Utopia_Server.S_Id = ?
    AND (
        Utopia_Server.G_Token = ?
        or ? in (
            SELECT Gigly_Account.G_Token
            FROM Gigly_Account
                INNER JOIN Gigly_Right on Gigly_Account.R_Id = Gigly_Right.R_Id
            WHERE Gigly_Right.Name = 'Admin'
        )
        or ? in (
            SELECT Stars.G_Token
            FROM Stars
                INNER JOIN Gigly_Right as r on Stars.R_Id = r.R_Id
            WHERE Stars.S_Id = ?
                and r.Name = 'Admin'
        )
    )