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
    type VARCHAR(200),
    context CHAR(36) DEFAULT NULL,
    first_req BOOL DEFAULT TRUE,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(title)
);

-- metadata
CREATE TABLE IF NOT EXISTS metadata (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    attribute VARCHAR(50),
    value TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(value)
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
    page INT,
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
    activation CHAR(36),
    reset CHAR(36),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);

-- jobs
CREATE TABLE IF NOT EXISTS jobs (
    id CHAR(36) PRIMARY KEY,
    data TEXT,
    name VARCHAR(100),
    status INT,
    attempts INT NOT NULL DEFAULT 0,
    locked_at DATETIME DEFAULT NULL,
    locked_by VARCHAR(255) NULL,
    failed_at DATETIME NULL,
    error TEXT NULL,
    progress INT NOT NULL DEFAULT 0,
    created DATETIME DEFAULT NULL
);

-- keywords
CREATE TABLE IF NOT EXISTS keywords (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    keyword TEXT,
    created DATETIME DEFAULT NULL,
    FULLTEXT(keyword)
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

-- annotations
CREATE TABLE IF NOT EXISTS annotations (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    relation CHAR(36),
    transcript TEXT,
    url TEXT,
    x1 FLOAT,
    y1 FLOAT,
    x2 FLOAT,
    y2 FLOAT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FULLTEXT(transcript)
);

-- bookmarks
CREATE TABLE IF NOT EXISTS bookmarks (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    description TEXT,
    created DATETIME DEFAULT NULL
);

-- flags
CREATE TABLE IF NOT EXISTS flags (
    id CHAR(36) PRIMARY KEY,
    resource_id CHAR(36),
    user_id CHAR(36),
    reason VARCHAR(100),
    explanation TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);
