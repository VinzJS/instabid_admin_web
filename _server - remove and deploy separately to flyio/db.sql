CREATE DATABASE IF NOT EXISTS instabid_db;

USE instabid_db;

-- Users Table

CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    emailVisibility BOOLEAN NOT NULL DEFAULT FALSE,
    hasApplication BOOLEAN NOT NULL DEFAULT FALSE,
    isStoreOwner BOOLEAN NOT NULL DEFAULT FALSE,
    name VARCHAR(255) NOT NULL,
    profilePhoto VARCHAR(255) NOT NULL DEFAULT '',
    username VARCHAR(255) NOT NULL,
    verified BOOLEAN NOT NULL DEFAULT FALSE,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(255) NOT NULL,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- For dummy tokens 

CREATE TABLE IF NOT EXISTS tokens (
    id VARCHAR(255) PRIMARY KEY,
    userId VARCHAR(255) NOT NULL,
    refreshToken VARCHAR(255) NOT NULL,
    accessToken VARCHAR(255) NOT NULL,
    expiresAt TIMESTAMP NOT NULL,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- For Admin

CREATE TABLE IF NOT EXISTS admins (
    id VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    emailVisibility BOOLEAN NOT NULL DEFAULT FALSE,
    hasApplication BOOLEAN NOT NULL DEFAULT FALSE,
    isStoreOwner BOOLEAN NOT NULL DEFAULT FALSE,
    name VARCHAR(255) NOT NULL,
    profilePhoto VARCHAR(255) NOT NULL DEFAULT '',
    username VARCHAR(255) NOT NULL,
    verified BOOLEAN NOT NULL DEFAULT FALSE,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(255) NOT NULL,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Categories

CREATE TABLE categories (
    category_id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255),
    store VARCHAR(255) NOT NULL,
    FOREIGN KEY (store_id) REFERENCES stores (store_id),
);

-- Stores

CREATE TABLE stores (
    store_id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    user VARCHAR(255) NOT NULL,
    isPublished BOOLEAN NOT NULL DEFAULT FALSE,
    rating INTEGER,
    displayImage VARCHAR(255) NOT NULL,
    contactNumber VARCHAR(255) NOT NULL,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);


-- Product

CREATE TABLE products (
    product_id VARCHAR(255) PRIMARY KEY,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    name VARCHAR(255),
    description TEXT,
    is_biddable BOOLEAN,
    bid_start TIMESTAMP NULL DEFAULT NULL,
    bid_end TIMESTAMP NULL DEFAULT NULL,
    purchase_notes TEXT,
    size_guide VARCHAR(255),
    increment_amount NUMERIC,
    min_bid NUMERIC,
    price NUMERIC,
    is_published BOOLEAN,
    is_purchased BOOLEAN,
    category_id VARCHAR(255),
    store VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (store) REFERENCES stores(id)
);

-- Tags

CREATE TABLE tags (
    id VARCHAR(255) PRIMARY KEY,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL
);
-- Product Images 

CREATE TABLE product_images (
    product_images_id VARCHAR(255) PRIMARY KEY,
    product_id VARCHAR(255),
    image_url TEXT,
    FOREIGN KEY (product_id) REFERENCES products (product_id)
);

-- Product Tags

CREATE TABLE tags (
    tag_id VARCHAR(255) PRIMARY KEY,
    product_id VARCHAR(255),
    name VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products (id)
);

-- Reviews Tags

CREATE TABLE reviews (
    review_id VARCHAR(255) PRIMARY KEY,
    product_id VARCHAR(255),
    user_id VARCHAR(255),
    rating INTEGER,
    review TEXT,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);

-- Bids

CREATE TABLE bids (
    bid_id VARCHAR(255) PRIMARY KEY,
    product_id VARCHAR(255),
    user_id VARCHAR(255),
    amount INTEGER,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products (product_id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);

-- Orders

CREATE TABLE orders (
    order_id VARCHAR(255) PRIMARY KEY,
    status VARCHAR(50) NOT NULL,
    product_id VARCHAR(255) NOT NULL,
    bid_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status TEXT NOT NULL,
    productCopy JSON,
    total DECIMAL(10, 2) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (bid_id) REFERENCES bids(bid_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)