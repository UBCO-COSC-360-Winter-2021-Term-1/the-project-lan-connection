
CREATE TABLE Account(
    uname VARCHAR(30),
    email VARCHAR(255),
    fname VARCHAR(255),
    lname VARCHAR(255),
    pword VARCHAR(255),
    administrator BOOLEAN,
    pfp BLOB,
    PRIMARY KEY(uname)
); 

CREATE TABLE Category(
    cat_title VARCHAR(255),
    PRIMARY KEY(cat_title)
); 

CREATE TABLE Post(
    pid INTEGER NOT NULL AUTO_INCREMENT,
    post_title VARCHAR(255),
    post_body VARCHAR(1000),
    uname VARCHAR(255),
    cat_title VARCHAR(255),
    post_date DATETIME,
    p_likes INTEGER,
    p_dislikes INTEGER,
    pfp BLOB,
    FOREIGN KEY (pfp) REFERENCES Account(pfp) ON DELETE CASCADE,
    FOREIGN KEY (uname) REFERENCES Account(uname) ON DELETE CASCADE,
    FOREIGN KEY (cat_title) REFERENCES Category(cat_title) ON DELETE CASCADE,
    PRIMARY KEY(pid)
); 

CREATE TABLE Comment(
    uname VARCHAR(255),
    cid INTEGER NOT NULL AUTO_INCREMENT,
    comment_body VARCHAR(1000),
    pid INTEGER,
    comment_date DATETIME,
    c_likes INTEGER,
    c_dislikes INTEGER,
    pfp BLOB,
    FOREIGN KEY (pfp) REFERENCES Account(pfp) ON DELETE CASCADE,
    FOREIGN KEY (uname) REFERENCES Account(uname) ON DELETE CASCADE,
    FOREIGN KEY (pid) REFERENCES Post(pid) ON DELETE CASCADE,
    PRIMARY KEY(cid)
);

INSERT INTO Category VALUES ('Mountain Biking');
INSERT INTO Category VALUES ('Hiking');
INSERT INTO Category VALUES ('Climbing');
INSERT INTO Category VALUES ('Snowboarding');
INSERT INTO Category VALUES ('Golf');
INSERT INTO Category VALUES ('Hockey');

INSERT INTO Account('uname', 'email', 'fname', 'lname', 'pword', 'administrator', 'pfp') VALUES ('admin', NULL, NULL, NULL, 'Admin_pword72', TRUE, NULL);