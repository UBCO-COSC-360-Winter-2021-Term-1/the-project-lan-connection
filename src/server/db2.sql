DROP TABLE IF EXISTS `Ratings`;
DROP TABLE IF EXISTS `Bookmarks`;
DROP TABLE IF EXISTS `Comment`;
DROP TABLE IF EXISTS `Post`;
DROP TABLE IF EXISTS `Category`;
DROP TABLE IF EXISTS `Account`;
DROP TABLE IF EXISTS `Images`;

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
  user_enabled BOOLEAN,
  PRIMARY KEY(uname)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE Account ADD CONSTRAINT fk_imageID_account FOREIGN KEY (imageID) REFERENCES Images(imageID) ON UPDATE CASCADE ON DELETE SET NULL;

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
ALTER TABLE Post ADD CONSTRAINT fk_imageID_post FOREIGN KEY (imageID) REFERENCES Images(imageID) ON UPDATE CASCADE ON DELETE SET NULL;

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

