CREATE TABLE %db_prefix%_section_groups (
    id INT UNSIGNED NOT NULL auto_increment,
    parent INT UNSIGNED,
    old_parent INT UNSIGNED,
    weight INT,
    name TINYTEXT,

    PRIMARY KEY(id)
);

CREATE TABLE %db_prefix%_sections (
    id INT UNSIGNED NOT NULL auto_increment,
    parent INT UNSIGNED NOT NULL,
    old_parent INT UNSIGNED,
    weight INT,
    title TINYTEXT,
    subtitle TINYTEXT,

    locked BOOL NOT NULL DEFAULT FALSE,
    container BOOL NOT NULL DEFAULT FALSE,

    count_topics INT UNSIGNED NOT NULL DEFAULT 0,
    count_posts INT UNSIGNED NOT NULL DEFAULT 0,

    last_topic_id INT UNSIGNED,
    last_topic_title TINYTEXT,
    last_post_id INT UNSIGNED,
    last_post_time DATETIME,
    last_user_id INT UNSIGNED,
    last_user_name TINYTEXT,
    
    PRIMARY KEY(id)
);
    
CREATE TABLE %db_prefix%_topics (
    id INT UNSIGNED NOT NULL auto_increment,
    type SMALLINT UNSIGNED NOT NULL,
    parent INT UNSIGNED NOT NULL,
    old_parent INT UNSIGNED,
    user_id INT UNSIGNED NOT NULL,
    user_name TINYTEXT,
    title TINYTEXT NOT NULL,
    subtitle TINYTEXT,
    
    count_posts INT UNSIGNED NOT NULL DEFAULT 0,
    count_views INT UNSIGNED NOT NULL DEFAULT 0,
    
    last_post_id INT UNSIGNED NOT NULL,
    last_post_time DATETIME NOT NULL,
    last_user_id INT UNSIGNED NOT NULL,
    last_user_name TINYTEXT NOT NULL,
    
    PRIMARY KEY(id)
);
    
CREATE TABLE %db_prefix%_topics_read (
    topic INT UNSIGNED NOT NULL,
    user INT UNSIGNED NOT NULL
);
    
CREATE TABLE %db_prefix%_topic_posts (
    topic_id INT UNSIGNED NOT NULL,
    post_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    user_name TINYTEXT,
    bbcode BOOL NOT NULL,
    time DATETIME,
    title TINYTEXT NOT NULL,
    content LONGTEXT NOT NULL
);

/* Indexes creation */
CREATE INDEX topic_id   ON %db_prefix%_topics (id);
CREATE INDEX section_id ON %db_prefix%_sections (id);
CREATE INDEX group_id   ON %db_prefix%_section_groups (id);

/* Sections creation. */
INSERT INTO %db_prefix%_section_groups
           (parent, weight, name)
    VALUES (0,      1,      "Sections");

INSERT INTO %db_prefix%_sections
           (parent,   weight, title,          subtitle)
    VALUES (1,        1,      "Main section", "Main section subtitle.");

