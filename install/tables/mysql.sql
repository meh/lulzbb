CREATE TABLE %db_prefix%_users (
    id INT UNSIGNED NOT NULL auto_increment,
    session TINYTEXT,
    name TINYTEXT NOT NULL,
    password TINYTEXT NOT NULL,
    email TINYTEXT NOT NULL,
    avatar TEXT,
    
    real_name TINYTEXT,
    real_surname TINYTEXT,
    sex TINYTEXT,
    birth DATE,
    location TINYTEXT,
    
    biography TEXT,
    hobby TEXT,
    job TINYTEXT,
    signature TEXT,
    
    homepage TEXT,
    msn TINYTEXT,
    icq TINYTEXT,
    yahoo TINYTEXT,
    
    option_email BOOL NOT NULL DEFAULT TRUE,
    option_bbcode BOOL NOT NULL DEFAULT TRUE,
    registration_date DATETIME,
    
    PRIMARY KEY (id)
);

CREATE TABLE %db_prefix%_user_groups (
    id INT UNSIGNED NOT NULL auto_increment,
    name VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    
    level SMALLINT DEFAULT 0,
    
    PRIMARY KEY (id)
);
    
CREATE TABLE %db_prefix%_user_groups_users (
    group_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    
    level SMALLINT DEFAULT 0
);

/* User groups creations. */
INSERT INTO %db_prefix%_user_groups
           (name,          description,                            level)
    VALUES ("Unconfirmed", "LOLOL, THEY HAVE TO CONFIRM, FOR SRS", -1);

INSERT INTO %db_prefix%_user_groups
           (name,   description,     level)
    VALUES ("User", "Namefag here.", 1);

INSERT INTO %db_prefix%_user_groups
           (name,        description,   level)
    VALUES ("Moderator", "MODS = FAGS", 8999);

INSERT INTO %db_prefix%_user_groups
           (name,            description, level)
    VALUES ("Administrator", "OH SHI-",   9001);

