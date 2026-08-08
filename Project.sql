CREATE TABLE users (
    user_id         INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(21) NOT NULL UNIQUE,
    email           VARCHAR(100) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    contact_number  VARCHAR(8) NOT NULL,
    date_of_birth   DATE NOT NULL,
    address         TEXT NULL,
    postal_code     VARCHAR(10) NULL,
    role            ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bbq_pits (
    pit_id          INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    description     TEXT NULL,
    location        VARCHAR(150) NOT NULL,
    capacity        INT NOT NULL,
    price_per_session DECIMAL(8,2) NOT NULL,
    image_path      VARCHAR(255) NULL,
    status          ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE reservations (
    reservation_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    pit_id          INT NOT NULL,
    booking_date    DATE NOT NULL,
    start_time      TIME NOT NULL,
    end_time        TIME NOT NULL,
    status          ENUM('confirmed', 'cancelled') NOT NULL DEFAULT 'confirmed',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (pit_id) REFERENCES bbq_pits(pit_id) ON DELETE CASCADE
);


CREATE TABLE contact_messages (
    message_id      INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    email           VARCHAR(100) NOT NULL,
    subject         VARCHAR(100) NOT NULL,
    message         TEXT NOT NULL,
    status          ENUM('new', 'resolved') NOT NULL DEFAULT 'new',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO bbq_pits (name, description, location, capacity, price_per_session, image_path, status) VALUES
('Chill BBQ Pit', 'Cozy pit near the waterfront, great for small groups.', 'East Coast Park, Area A', 10, 40.00, 'images/pitA.png', 'active'),
('Blaze BBQ Pit', 'Spacious pit with covered seating.', 'East Coast Park, Area B', 20, 60.00, 'images/pitB.png', 'active'),
('Sunset Grill Pit', 'Scenic pit facing the sea, popular for evening bookings.', 'Pasir Ris Park', 15, 50.00, 'images/pitA.png', 'active'),
('Riverside BBQ Pit', 'Peaceful spot along the river, ideal for family gatherings.', 'Pasir Ris Park', 12, 45.00, 'images/pitB.png', 'active'),
('Coastal Breeze Pit', 'Open-air pit with great sea breeze all year round.', 'East Coast Park, Area C', 25, 65.00, 'images/pitA.png', 'active'),
('Garden View Pit', 'Surrounded by greenery, quiet and shaded.', 'West Coast Park', 8, 35.00, 'images/pitB.png', 'active'),
('Beachside Pit', 'Steps away from the sand, great for beach parties.', 'Changi Beach Park', 18, 55.00, 'images/pitA.png', 'active'),
('Lakeside BBQ Pit', 'Overlooks the reservoir, popular for sunset photos.', 'Bedok Reservoir Park', 14, 48.00, 'images/pitB.png', 'active'),
('Big Group Pit', 'Our largest pit, perfect for company events or big celebrations.', 'East Coast Park, Area D', 40, 90.00, 'images/pitA.png', 'active'),
('Cozy Corner Pit', 'Small and intimate, best for close friends and family.', 'West Coast Park', 6, 30.00, 'images/pitB.png', 'active'),
('Parkside Grill', 'Centrally located with easy parking access.', 'Bishan-Ang Mo Kio Park', 16, 52.00, 'images/pitA.png', 'active'),
('Riverfront Pit', 'Modern facilities right by the river promenade.', 'Punggol Waterway Park', 20, 58.00, 'images/pitB.png', 'active');


