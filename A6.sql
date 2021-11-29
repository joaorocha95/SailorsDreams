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
   userid INTEGER REFERENCES sailorsDream.Users (id) ON UPDATE CASCADE,
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


-----------------------------------------
-- Populate the database
-----------------------------------------

INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (1,'Thermed','admin@sailorsdream.com','1946-02-13','UTaxe6fieH',FALSE, 'Admin', '', 212288151);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (2,'Selinglese','selinglese@sailorsdream.com','1946-02-13','ohr7ieg2L',FALSE,'Support', '', 212725182);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (3,'Whanterrene','jemtally83@gmail.com','1946-02-13','OFK00XCC7OD',FALSE, 'Client', '', 212847851);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (4,'Catelleaden1969','najagreen12@gmail.com','1946-02-13','ieSh2riil',FALSE, 'User', '', 212722946);

INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (5,'Faidelper','kadenn2001@gmail.com','1946-02-13','ohGh3uuG0ei',TRUE, 'User', '', 212735614);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (6,'Sathereend','sunelliot17@gmail.com','1946-02-13','woh2be6Auph1',TRUE,'Seller', '', 212735615);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (7,'Whimpappered52','kaydenstark1952@gmail.com','1946-02-13','sheiJuot3phai',FALSE, 'Client', '', 212441165);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (8,'Difeentle','difeentle@sailorsdream.com','1946-02-13','PZJ77DKO2VZ',FALSE, 'Support', '', 212655776);

INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (9,'Painged','paigestew1971@gmail.com','1946-02-13','SahWai0Ie',FALSE, 'Seller', '', 212344626);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (10,'Eneive','mcdonaldgrey@gmail.com','1946-02-13','eeNgeiHi5',FALSE, 'Client', '', 212349594);

INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (1, 6, 'Veleiro', 'Tem uma folha no leme', TRUE, 200.00, NULL);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (2, 6, 'Lancha', 'Rápida e ainda está inteira!', TRUE, 100.00, NULL);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (3, 9, 'Iate', 'Brand new', TRUE,NULL, 75.00);

INSERT INTO sailorsDream.Category (product_id, name) VALUES (1, 'Water vehicle');

INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (1, 1, 3, 'Transaction_Completed', 'Purchase',NULL,NULL, 200.00);
INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (2, 1, 10, 'Transaction_Completed', 'Loan', '2021-11-20', '2021-11-23', 75.00);

INSERT INTO sailorsDream.Review (id, orderid, to_user, from_user, rating, comment) VALUES (1, 1, 2, 4, 4, NULL);

INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (3, 1, 3, 'Transaction_Completed', 'Purchase',NULL,NULL, 200.00);
INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (4, 3, 10, 'Transaction_Completed', 'Loan', '2021-2-11', '2021-1-11', 75.00);

INSERT INTO sailorsDream.Review (id,orderid,to_user,from_user,rating,comment) VALUES (2,1,3,6,5, 'Very cheap and fast!');

-- removed for brevity

-----------------------------------------
-- end
-----------------------------------------
