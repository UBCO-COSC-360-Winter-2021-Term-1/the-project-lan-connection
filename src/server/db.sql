
DROP TABLE IF EXISTS `Images`;
DROP TABLE IF EXISTS `Account`;
DROP TABLE IF EXISTS `Category`;
DROP TABLE IF EXISTS `Post`;
DROP TABLE IF EXISTS `Comment`;
DROP TABLE IF EXISTS `Ratings`;
DROP TABLE IF EXISTS `Bookmarks`;

CREATE TABLE Images(
  imageID int(11) NOT NULL AUTO_INCREMENT,
  contentType varchar(255) NOT NULL,
  image BLOB NOT NULL,
  PRIMARY KEY(imageID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Account(
  uname VARCHAR(30),
  email VARCHAR(255),
  fname VARCHAR(255),
  lname VARCHAR(255),
  pword VARCHAR(255),
  administrator BOOLEAN,
  imageID int(11),
  PRIMARY KEY(uname)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE Account ADD CONSTRAINT fk_imageID_account FOREIGN KEY (imageID) REFERENCES Images(imageID) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE Category(
  cat_title VARCHAR(255),
  PRIMARY KEY(cat_title)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE Post(
  pid INTEGER NOT NULL AUTO_INCREMENT,
  post_body VARCHAR(1000),
  uname VARCHAR(255),
  cat_title VARCHAR(255),
  post_date DATETIME,
  imageID int(11),
  PRIMARY KEY(pid)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE Post ADD CONSTRAINT fk_uname_post FOREIGN KEY (uname) REFERENCES Account(uname) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Post ADD CONSTRAINT fk_cat_post FOREIGN KEY (cat_title) REFERENCES Category(cat_title) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Post ADD CONSTRAINT fk_imageID_post FOREIGN KEY (imageID) REFERENCES Images(imageID) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE Comment(
  uname VARCHAR(255),
  cid INTEGER NOT NULL AUTO_INCREMENT,
  comment_body VARCHAR(1000),
  pid INTEGER,
  comment_date DATETIME,
  PRIMARY KEY(cid)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE Comment ADD CONSTRAINT fk_uname_comment FOREIGN KEY (uname) REFERENCES Account(uname) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE Comment ADD CONSTRAINT fk_pid_comment FOREIGN KEY (pid) REFERENCES Post(pid) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE Ratings (
  uname VARCHAR(255),
  pid INTEGER,
  action VARCHAR(10),
  PRIMARY KEY (uname, pid)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE Bookmarks (
  uname VARCHAR(255),
  pid INTEGER,
  PRIMARY KEY(uname, pid)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE Ratings ADD CONSTRAINT fk_uname_ratings FOREIGN KEY (uname) REFERENCES Account(uname) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE Ratings ADD CONSTRAINT fk_pid_ratings FOREIGN KEY (pid) REFERENCES Post(pid) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE Bookmarks ADD CONSTRAINT fk_pid_bookmarks FOREIGN KEY (pid) REFERENCES Post(pid) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE Bookmarks ADD CONSTRAINT fk_uname_bookmarks FOREIGN KEY (uname) REFERENCES Account(uname) ON UPDATE CASCADE ON DELETE CASCADE; 




-- Populating database for displaying of our features
INSERT INTO Category VALUES ('Mountain Biking');
INSERT INTO Category VALUES ('Hiking');
INSERT INTO Category VALUES ('Climbing');
INSERT INTO Category VALUES ('Snowboarding');
INSERT INTO Category VALUES ('Golf');
INSERT INTO Category VALUES ('Hockey');

INSERT INTO Account VALUES ('noahward', 'noahward@shaw.ca', 'Noah', 'Ward', '5f4dcc3b5aa765d61d8327deb882cf99', TRUE, NULL);
INSERT INTO Account VALUES ('andymcd', 'andymcd@shaw.ca', 'Andy', 'McDonald', '5f4dcc3b5aa765d61d8327deb882cf99', TRUE, NULL);
INSERT INTO Account VALUES ('liviaz', 'liviaz@shaw.ca', 'Livia', 'Zalilla', '5f4dcc3b5aa765d61d8327deb882cf99', TRUE, NULL);
INSERT INTO Account VALUES ('pattym', 'patrickmahler@hotmail.com', 'Patrick', 'Mahler', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('simotheyam', 'simon@gmail.com', 'Simon', 'Yamamoto', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('coletweed', 'cole@gmail.com', 'Cole', 'Tweed', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('alexandrias', 'alex@shaw.ca', 'Alex', 'Shaw', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('matty', 'matt@shaw.ca', 'Matt', 'Francais', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('jakeywitty', 'jakey@gmail.com', 'Jake', 'Wittenberg', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('jessy', 'jessica@gmail.com', 'Jessica', 'Grant', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('joliev', 'jolie@telus.net', 'Jolie', 'Volk', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('terpy7536', 'wesley@shaw.ca', 'Wesley', 'Terpstra', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('tendybrendy', 'james@hotmail.com', 'James', 'Brendeland', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('golferboy2', 'jakelane@shaw.ca', 'Jake', 'Lane', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('aussy', 'austin@shaw.ca', 'Austin', 'Smith', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);
INSERT INTO Account VALUES ('nessa', 'vanessa@shaw.ca', 'Vanessa', 'Dunn', '5f4dcc3b5aa765d61d8327deb882cf99', FALSE, NULL);

INSERT INTO Post VALUES (1, "Golf is such a great game, thank God I'm better than Wesley!", 'noahward', 'Golf', '2021-11-28 23:42:13', NULL);
INSERT INTO Post VALUES (2, "30cm powday at Big White today. Tried out the new board for the new season", 'aussy', 'Snowboarding', '2021-11-25 13:42:13', NULL);
INSERT INTO Post VALUES (3, "Great day at Skaha Bluffs today, many sends.", 'pattym', 'Climbing', '2021-11-28 20:15:13', NULL);
INSERT INTO Post VALUES (4, "Montreal is having a great season... Hoping for a bit more out of Price this year though.", 'tendybrendy', 'Hockey', '2021-11-29 20:12:09', NULL);
INSERT INTO Post VALUES (5, "Silverstar hasn't even opened yet, imagine that lol, couldn't be Big White", 'noahward', 'Snowboarding', '2021-11-30 10:53:23', NULL);
INSERT INTO Post VALUES (6, "Sick whips today up at Knox, trails are prime right now!", 'coletweed', 'Mountain Biking', '2021-11-28 19:08:44', NULL);
INSERT INTO Post VALUES (7, "Dropping bombs today like I'm DeChambeau... But go Brooksie still.", 'golferboy2', 'Golf', '2021-11-10 08:49:58', NULL);
INSERT INTO Post VALUES (8, "Looking forward to visiting Revvy this season if they get a decent powday soon! Although I'll be skiing...", 'jessy', 'Snowboarding', '2021-11-15 11:13:19', NULL);
INSERT INTO Post VALUES (9, "Looking to start climbing... Anyone want to show me around Skaha?", 'nessa', 'Climbing', '2021-11-27 21:12:59', NULL);
INSERT INTO Post VALUES (10, "Did anyone see that Taylor Gooch was 22 under par at the RSM Classic?? Great to see the young lad winning", 'noahward', 'Golf', '2021-11-28 16:44:50', NULL);
INSERT INTO Post VALUES (11, "Who would have though the Leafs would be on top this year... About time they pulled it together these past couple seasons!", 'jakeywitty', 'Hockey', '2021-11-29 18:49:02', NULL);

INSERT INTO Comment VALUES('tendybrendy', 1, 'Lmao Silverstar is pretty bad', 5, '2021-11-30 12:45:13');
INSERT INTO Comment VALUES('noahward', 2, 'Price is the greatest tendy of all time, guy is the goat', 4, '2021-11-30 07:12:55');
INSERT INTO Comment VALUES('pattym', 3, 'I would take ya for a climb!', 9, '2021-11-27 22:45:13');
INSERT INTO Comment VALUES('coletweed', 4, 'Hey dont be mean to SilverStar', 5, '2021-11-30 12:45:13');
INSERT INTO Comment VALUES('terpy7536', 5, 'I can confirm, you drop bombs', 7, '2021-11-12 10:49:13');
INSERT INTO Comment VALUES('matty', 6, 'Guy couldnt hit a green to save his life though ;)', 7, '2021-11-13 14:12:36');

INSERT INTO Ratings VALUES('noahward', 2, 'liked');
INSERT INTO Ratings VALUES('noahward', 4, 'liked');
INSERT INTO Ratings VALUES('noahward', 5, 'liked');
INSERT INTO Ratings VALUES('noahward', 8, 'liked');
INSERT INTO Ratings VALUES('noahward', 9, 'liked');
INSERT INTO Ratings VALUES('tendybrendy', 1, 'disliked');
INSERT INTO Ratings VALUES('tendybrendy', 3, 'disliked');
INSERT INTO Ratings VALUES('tendybrendy', 4, 'liked');
INSERT INTO Ratings VALUES('tendybrendy', 5, 'liked');
INSERT INTO Ratings VALUES('tendybrendy', 9, 'liked');
INSERT INTO Ratings VALUES('tendybrendy', 11, 'disliked');
INSERT INTO Ratings VALUES('aussy', 1, 'liked');
INSERT INTO Ratings VALUES('aussy', 2, 'liked');
INSERT INTO Ratings VALUES('aussy', 3, 'liked');
INSERT INTO Ratings VALUES('aussy', 4, 'disliked');
INSERT INTO Ratings VALUES('aussy', 5, 'liked');
INSERT INTO Ratings VALUES('aussy', 6, 'liked');
INSERT INTO Ratings VALUES('aussy', 7, 'disliked');
INSERT INTO Ratings VALUES('simotheyam', 4, 'liked');
INSERT INTO Ratings VALUES('simotheyam', 6, 'liked');
INSERT INTO Ratings VALUES('simotheyam', 7, 'liked');
INSERT INTO Ratings VALUES('simotheyam', 9, 'liked');
INSERT INTO Ratings VALUES('simotheyam', 11, 'liked');
INSERT INTO Ratings VALUES('simotheyam', 10, 'liked');
INSERT INTO Ratings VALUES('simotheyam', 1, 'liked');
INSERT INTO Ratings VALUES('joliev', 1, 'disliked');
INSERT INTO Ratings VALUES('joliev', 6, 'disliked');
INSERT INTO Ratings VALUES('joliev', 8, 'disliked');
INSERT INTO Ratings VALUES('joliev', 2, 'liked');
INSERT INTO Ratings VALUES('joliev', 3, 'liked');
INSERT INTO Ratings VALUES('joliev', 4, 'liked');
INSERT INTO Ratings VALUES('jessy', 2, 'disliked');
INSERT INTO Ratings VALUES('noahward', 1, 'liked');
INSERT INTO Ratings VALUES('noahward', 3, 'liked');
INSERT INTO Ratings VALUES('noahward', 11, 'liked');

INSERT INTO BOOKMARKS VALUES('noahward', 2);
INSERT INTO BOOKMARKS VALUES('jakeywitty', 3);
INSERT INTO BOOKMARKS VALUES('joliev', 6);
INSERT INTO BOOKMARKS VALUES('andymcd', 1);
INSERT INTO BOOKMARKS VALUES('noahward', 9);
INSERT INTO BOOKMARKS VALUES('noahward', 11);
INSERT INTO BOOKMARKS VALUES('jessy', 2);
