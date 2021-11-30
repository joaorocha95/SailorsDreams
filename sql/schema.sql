-----------------------------------------
-- Drop old schema
-----------------------------------------
DROP SCHEMA sailorsDream CASCADE;

-----------------------------------------
-- Types
-----------------------------------------
CREATE SCHEMA sailorsDream;

CREATE TYPE sailorsDream.accType AS ENUM ('User', 'Client', 'Seller', 'Admin', 'Support');
CREATE TYPE sailorsDream.order_status AS ENUM ('In_Negotiation', 'Transaction_Completed', 'Transaction_Failed');
CREATE TYPE sailorsDream.message_type AS ENUM ('Ticket', 'Order');
CREATE TYPE sailorsDream.order_type AS ENUM ('Loan', 'Purchase');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE IF NOT EXISTS sailorsDream.Users (
   id SERIAL PRIMARY KEY,
   username TEXT NOT NULL,
   email TEXT NOT NULL UNIQUE,
   birthDate DATE NOT NULL,
   password TEXT NOT NULL,
   banned BOOLEAN NOT NULL,
   accType sailorsDream.accType NOT NULL DEFAULT 'User',
   img TEXT,
   phone INTEGER NOT NULL UNIQUE
);

--psql --host=db --username=postgres --dbname=gitlab -f BD.sql
CREATE TABLE IF NOT EXISTS sailorsDream.Product (
   id SERIAL PRIMARY KEY,
   seller INTEGER REFERENCES sailorsDream.Users (id) ON DELETE CASCADE,
   productname TEXT NOT NULL,
   description TEXT NOT NULL,
   active BOOLEAN DEFAULT FALSE,
   price REAL,
   pricePerDay REAL
   CONSTRAINT price_check CHECK (price > 0)
   CONSTRAINT pricePerDay_check CHECK (price > 0)
);

CREATE TABLE IF NOT EXISTS sailorsDream.Order (
   id SERIAL PRIMARY KEY,
   product INTEGER REFERENCES sailorsDream.Product (id) ON DELETE CASCADE,
   client INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   order_status sailorsDream.order_status NOT NULL DEFAULT 'In_Negotiation',
   order_type sailorsDream.order_type NOT NULL,
   loan_start Date,
   loan_end Date,
   total_price REAL NOT NULL
   CONSTRAINT noOverlap CHECK (loan_end > loan_start OR loan_start = NULL)
   CONSTRAINT price_check CHECK (total_price > 0)

);

CREATE TABLE IF NOT EXISTS sailorsDream.Review (
   id SERIAL PRIMARY KEY,
   orderid INTEGER REFERENCES sailorsDream.Order (id) ON DELETE CASCADE,
   to_user INTEGER REFERENCES sailorsDream.Users (id) ON DELETE CASCADE,
   from_user INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   rating INTEGER NOT NULL,
   comment TEXT,
   review_date DATE DEFAULT now() NOT NULL
   CONSTRAINT ratingbuyer_ck CHECK (((rating > 0) OR (rating <= 5)))
);

CREATE TABLE IF NOT EXISTS sailorsDream.Addresses(
   userid INTEGER REFERENCES sailorsDream.Users (id) ON DELETE CASCADE ON UPDATE CASCADE,
   addr TEXT NOT NULL,
   city TEXT NOT NULL,
   country TEXT NOT NULL, 
   zipcode TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Category (
   product_id INTEGER REFERENCES sailorsDream.Product (id) ON DELETE CASCADE,
   name TEXT NOT NULL,
   UNIQUE (product_id, name)
);

CREATE TABLE IF NOT EXISTS sailorsDream.Ticket (
   id SERIAL PRIMARY KEY,
   userid INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   support INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   title TEXT NOT NULL
);


CREATE TABLE IF NOT EXISTS sailorsDream.Message (
   id SERIAL PRIMARY KEY,
   message_type sailorsDream.message_type NOT NULL, 
   associated_order INTEGER REFERENCES sailorsDream.Order (id) ON UPDATE CASCADE ON DELETE CASCADE,
   associated_ticket INTEGER REFERENCES sailorsDream.Ticket (id) ON UPDATE CASCADE ON DELETE CASCADE,
   message TEXT NOT NULL,
   date DATE DEFAULT now() NOT NULL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Wishlist (
   userid INTEGER NOT NULL REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   product INTEGER NOT NULL REFERENCES sailorsDream.Product (id) ON UPDATE CASCADE,
   PRIMARY KEY (userid, product)
);
