CREATE TABLE pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    hewan INT NOT NULL COMMENT '1=Cat, 2=Dog, 3=Bird, 4=Other',
    ras VARCHAR(255),
    nama VARCHAR(255) NOT NULL,
    umur INT,
    note VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);
