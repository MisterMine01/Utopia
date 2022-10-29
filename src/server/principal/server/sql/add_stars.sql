INSERT INTO Stars (G_Token, S_Id, R_Id)
VALUES (
        ?,
        ?,
        (
            SELECT R_Id
            FROM Gigly_Right
            where Name = ?
        )
    )