DROP TABLE IF EXISTS `Images`;
DROP TABLE IF EXISTS `Account`;
DROP TABLE IF EXISTS `Category`;
DROP TABLE IF EXISTS `Post`;
DROP TABLE IF EXISTS `Comment`;

CREATE TABLE Images(
  imageID int(11) NOT NULL AUTO_INCREMENT,
  contentType varchar(255) NOT NULL,
  image blob NOT NULL,
  PRIMARY KEY(imageID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Account(
  uname VARCHAR(30),
  email VARCHAR(255),
  fname VARCHAR(255),
  lname VARCHAR(255),
  pword VARCHAR(255),
  administrator BOOLEAN,
  imageID int(11),
  PRIMARY KEY(uname)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

ALTER TABLE Account ADD CONSTRAINT fk_imageID_account FOREIGN KEY (imageID) REFERENCES Images(imageID);


CREATE TABLE Category(
  cat_title VARCHAR(255),
  PRIMARY KEY(cat_title)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

CREATE TABLE Post(
  pid INTEGER NOT NULL AUTO_INCREMENT,
  post_body VARCHAR(1000),
  uname VARCHAR(255),
  cat_title VARCHAR(255),
  post_date DATETIME,
  p_likes INTEGER,
  p_dislikes INTEGER,
  imageID int(11),
  PRIMARY KEY(pid)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

ALTER TABLE Post ADD CONSTRAINT fk_uname_post FOREIGN KEY (uname) REFERENCES Account(uname);
ALTER TABLE Post ADD CONSTRAINT fk_cat_post FOREIGN KEY (cat_title) REFERENCES Category(cat_title);
ALTER TABLE Post ADD CONSTRAINT fk_imageID_post FOREIGN KEY (imageID) REFERENCES Images(imageID);

CREATE TABLE Comment(
  uname VARCHAR(255),
  cid INTEGER NOT NULL AUTO_INCREMENT,
  comment_body VARCHAR(1000),
  pid INTEGER,
  comment_date DATETIME,
  c_likes INTEGER,
  c_dislikes INTEGER,
  imageID int(11),
  PRIMARY KEY(cid)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

ALTER TABLE Comment ADD CONSTRAINT fk_uname_comment FOREIGN KEY (uname) REFERENCES Account(uname); 
ALTER TABLE Comment ADD CONSTRAINT fk_pid_comment FOREIGN KEY (pid) REFERENCES Post(pid);
ALTER TABLE Comment ADD CONSTRAINT fk_imageID_comment FOREIGN KEY (imageID) REFERENCES Images(imageID);

INSERT INTO Category VALUES ('Mountain Biking');
INSERT INTO Category VALUES ('Hiking');
INSERT INTO Category VALUES ('Climbing');
INSERT INTO Category VALUES ('Snowboarding');
INSERT INTO Category VALUES ('Golf');
INSERT INTO Category VALUES ('Hockey');

