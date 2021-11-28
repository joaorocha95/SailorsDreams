DROP SCHEMA sailorsDream CASCADE;
CREATE SCHEMA sailorsDream;

CREATE TYPE sailorsDream.accType AS ENUM ('User', 'Client', 'Seller', 'Admin', 'Support');
CREATE TYPE sailorsDream.order_status AS ENUM ('In_Negotiation', 'Transaction_Completed', 'Transaction_Failed');
CREATE TYPE sailorsDream.message_type AS ENUM ('Ticket', 'Order');
CREATE TYPE sailorsDream.order_type AS ENUM ('Loan', 'Purchase');

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
   seller INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   productname TEXT NOT NULL,
   description TEXT NOT NULL,
   active BOOLEAN DEFAULT FALSE,
   price REAL,
   pricePerDay REAL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Order (
   id SERIAL PRIMARY KEY,
   product INTEGER REFERENCES sailorsDream.Product (id) ON UPDATE CASCADE,
   client INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   order_tatus sailorsDream.order_status NOT NULL,
   order_type sailorsDream.order_type NOT NULL,
   loan_start Date,
   loan_end Date,
   total_price REAL NOT NULL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Review (
   id SERIAL PRIMARY KEY,
   orderid INTEGER REFERENCES sailorsDream.Order (id) ON UPDATE CASCADE,
   to_user INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   from_user INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   rating_buyer INTEGER NOT NULL,
   rating_seller INTEGER NOT NULL,
   comment TEXT,
   date DATE DEFAULT now() NOT NULL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Addresses(
   userid INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   addr TEXT NOT NULL,
   city TEXT NOT NULL,
   country TEXT NOT NULL, 
   zipcode TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Category (
   product_id INTEGER REFERENCES sailorsDream.Product (id) ON UPDATE CASCADE,
   name TEXT NOT NULL,
   UNIQUE (product_id, name)
);

CREATE TABLE IF NOT EXISTS sailorsDream.Ticket (
   id SERIAL PRIMARY KEY,
   userid INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   support INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   title TEXT NOT NULL
);


CREATE TABLE IF NOT EXISTS sailorsDream.Message (
   id SERIAL PRIMARY KEY,
   message_type sailorsDream.message_type NOT NULL, 
   associated_order INTEGER REFERENCES sailorsDream.Order (id) ON UPDATE CASCADE,
   associated_ticcket INTEGER REFERENCES sailorsDream.Ticket (id) ON UPDATE CASCADE,
   message TEXT NOT NULL,
   date DATE DEFAULT now() NOT NULL
);

CREATE TABLE IF NOT EXISTS sailorsDream.Wishlist (
   userid INTEGER NOT NULL REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
   product INTEGER NOT NULL REFERENCES sailorsDream.Product (id) ON UPDATE CASCADE,
   PRIMARY KEY (userid, product)
);
