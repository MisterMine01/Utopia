UPDATE Utopia_Server
SET Name = ?, Url = ?, V_Id = (select V_Id FROM Utopia_Visibility WHERE Name = ?)
WHERE S_Id = ?