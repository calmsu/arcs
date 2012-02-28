--
-- ARCS MySQL schema
--

-- resources
CREATE TABLE IF NOT EXISTS resources (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36),
    sha VARCHAR(40),
    public BOOL,
    exclusive BOOL,
    file_name VARCHAR(200),
    file_size INT(11),
    mime_type VARCHAR(100),
    title TEXT,
    first_req BOOL DEFAULT TRUE,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);

-- metadata
CREATE TABLE IF NOT EXISTS metadata (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    property VARCHAR(50),
    value TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(value)
);

-- uploads
CREATE TABLE IF NOT EXISTS uploads (
    id CHAR(36) PRIMARY KEY,
    file_name VARCHAR(200),
    tmp_name VARCHAR(40),
    created DATETIME DEFAULT NULL
);

-- collections
CREATE TABLE IF NOT EXISTS collections (
    id CHAR(36) PRIMARY KEY,
    title TEXT, 
    description TEXT,
    public BOOL,
    user_id CHAR(36),
    pdf CHAR(36),       
    temporary BOOL DEFAULT FALSE,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(title, description)
);

-- memberships
CREATE TABLE IF NOT EXISTS memberships (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    collection_id CHAR(36),
    created DATETIME DEFAULT NULL
);

-- users
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY,
    email VARCHAR(100),
    name VARCHAR(100),
    username VARCHAR(100),
    password VARCHAR(100),
    role INT(1),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);

-- collaborators
CREATE TABLE IF NOT EXISTS collaborators (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36),
    resource_id CHAR(36),
    role INT(1),
    created DATETIME DEFAULT NULL
);

-- tasks
CREATE TABLE IF NOT EXISTS tasks (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    data TEXT,
    job VARCHAR(40),
    status INT(1),
    in_progress BOOL DEFAULT FALSE,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);

-- tags
CREATE TABLE IF NOT EXISTS tags (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    tag TEXT,
    created DATETIME DEFAULT NULL,
    FULLTEXT(tag)
);

-- comments
CREATE TABLE IF NOT EXISTS comments (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    content TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(content)
);

-- hotspots
CREATE TABLE IF NOT EXISTS hotspots (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    x1 FLOAT,
    y1 FLOAT,
    x2 FLOAT,
    y2 FLOAT,
    type VARCHAR(40),
    caption TEXT,
    title VARCHAR(200),
    link VARCHAR(400),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(caption, title)
);

-- bookmarks
CREATE TABLE IF NOT EXISTS bookmarks (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    description TEXT,
    created DATETIME DEFAULT NULL
);
