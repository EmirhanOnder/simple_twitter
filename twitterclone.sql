
CREATE TABLE Users (
    UserID int NOT NULL AUTO_INCREMENT,
    UserName varchar(255) NOT NULL,
    Email varchar(255),
    Password varchar(255),
    JoinDate date,
    Following int DEFAULT 0,
    Followers int DEFAULT 0,
    PRIMARY KEY (UserID)
);

CREATE TABLE Tweets (
    TweetID int NOT NULL AUTO_INCREMENT,
    UserID int,
    Tweet varchar(280),
    TimePosted datetime,
    PRIMARY KEY (TweetID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Follows (
    FollowerID int,
    FollowingID int,
    FollowDate date,
    PRIMARY KEY (FollowerID, FollowingID),
    FOREIGN KEY (FollowerID) REFERENCES Users(UserID),
    FOREIGN KEY (FollowingID) REFERENCES Users(UserID)
);

DELIMITER //
CREATE PROCEDURE ShowHomePage(IN userID INT)
BEGIN
    SELECT Tweets.Tweet, Tweets.TimePosted, Users.UserName 
    FROM Tweets 
    JOIN Follows ON Tweets.UserID = Follows.FollowingID 
    JOIN Users ON Tweets.UserID = Users.UserID
    WHERE Follows.FollowerID = userID 
    ORDER BY Tweets.TimePosted DESC;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ShowProfilePage(IN userID INT)
BEGIN
    SELECT Tweets.Tweet, Tweets.TimePosted 
    FROM Tweets 
    WHERE Tweets.UserID = userID 
    ORDER BY Tweets.TimePosted DESC;
END //
DELIMITER ;

