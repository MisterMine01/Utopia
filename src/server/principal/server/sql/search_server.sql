SELECT Utopia_Server.S_Id,
    Owner.Username as 'Owner',
    Utopia_Server.Name as 'ServerName',
    Utopia_Visibility.Name as 'Visibility',
    Utopia_Server.Url
FROM Utopia_Server
    INNER JOIN Gigly_Account as Owner on Utopia_Server.G_Token = Owner.G_Token
    INNER JOIN Utopia_Visibility on Utopia_Server.V_Id = Utopia_Visibility.V_Id
WHERE Utopia_Visibility.Name = 'public'
    OR Owner.G_Token = ?
    OR ? IN (
        SELECT a.G_Token
        FROM Gigly_Account a
            INNER JOIN Gigly_Right r on a.R_Id = r.R_Id
        WHERE r.Name = 'Admin' or r.Name = 'Moderator'
    )
    OR ? IN (
        SELECT Stars.G_Token
        FROM Stars
            INNER JOIN Gigly_Right r on Stars.R_Id = r.R_Id
        WHERE Stars.S_Id = Utopia_Server.S_Id
            AND r.Name != 'Banned'
    )