USE instabid_db;

-- Temporarily disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;


-- Clear the tokens table
TRUNCATE TABLE tokens;

-- Clear the users table
TRUNCATE TABLE users;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Insert dummy data into the users table
INSERT INTO users (id, email, emailVisibility, hasApplication, isStoreOwner, name, profilePhoto, username, verified, created, password, updated) VALUES
('1', 'user1@test.com', FALSE, FALSE, FALSE, 'User One', '', 'userone', FALSE, '2023-01-01 00:00:00', '$2y$10$wH0s0pF1qw9eQKjZmFh1JO9', '2023-01-01 00:00:00'),
('2', 'user2@test.com', TRUE, FALSE, TRUE, 'User Two', '', 'usertwo', TRUE, '2023-02-01 00:00:00', '$2y$10$wH0s0pF1qw9eQKjZmFh1JO9', '2023-02-01 00:00:00'),
('3', 'user3@test.com', FALSE, TRUE, FALSE, 'User Three', '', 'userthree', FALSE, '2023-03-01 00:00:00', '$2y$10$wH0s0pF1qw9eQKjZmFh1JO9', '2023-03-01 00:00:00'),
('4', 'user4@test.com', TRUE, TRUE, TRUE, 'User Four', '', 'userfour', TRUE, '2023-04-01 00:00:00', '$2y$10$wH0s0pF1qw9eQKjZmFh1JO9', '2023-04-01 00:00:00'),
('5', 'user5@test.com', FALSE, FALSE, FALSE, 'User Five', '', 'userfive', FALSE, '2023-05-01 00:00:00', '$2y$10$wH0s0pF1qw9eQKjZmFh1JO9', '2023-05-01 00:00:00');