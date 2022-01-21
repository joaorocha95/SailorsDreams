-----------------------------------------
-- Drop old schema
-----------------------------------------
DROP SCHEMA lbaw2182 CASCADE;

-----------------------------------------
-- Types
-----------------------------------------
CREATE SCHEMA lbaw2182;

CREATE TYPE lbaw2182.accType AS ENUM ('User', 'Client', 'Seller', 'Admin', 'Support');
CREATE TYPE lbaw2182.order_status AS ENUM ('In_Negotiation', 'Transaction_Completed', 'Transaction_Failed');
CREATE TYPE lbaw2182.message_type AS ENUM ('Ticket', 'Order');
CREATE TYPE lbaw2182.order_type AS ENUM ('Loan', 'Purchase');
CREATE TYPE lbaw2182.application_state AS ENUM ('Evaluating', 'Rejected', 'Accepted');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE IF NOT EXISTS lbaw2182.Users (
   id SERIAL PRIMARY KEY,
   username TEXT NOT NULL,
   email TEXT NOT NULL UNIQUE,
   birthDate DATE NOT NULL,
   password TEXT NOT NULL,
   banned BOOLEAN NOT NULL DEFAULT FALSE,
   accType lbaw2182.accType NOT NULL DEFAULT 'User',
   img TEXT,
   phone INTEGER NOT NULL UNIQUE
);

--psql --host=db --username=postgres --dbname=gitlab -f BD.sql
CREATE TABLE IF NOT EXISTS lbaw2182.Product (
   id SERIAL PRIMARY KEY,
   seller INTEGER REFERENCES lbaw2182.users (id) ON DELETE CASCADE,
   img TEXT,
   productname TEXT NOT NULL,
   description TEXT NOT NULL,
   active BOOLEAN DEFAULT FALSE,
   price REAL,
   pricePerDay REAL
   CONSTRAINT price_check CHECK (price > 0)
   CONSTRAINT pricePerDay_check CHECK (price > 0)
);

CREATE TABLE IF NOT EXISTS lbaw2182.Order (
   id SERIAL PRIMARY KEY,
   product INTEGER REFERENCES lbaw2182.Product (id) ON DELETE CASCADE,
   client INTEGER REFERENCES lbaw2182.users (id) ON UPDATE CASCADE,
   seller INTEGER REFERENCES lbaw2182.users (id) ON UPDATE CASCADE,
   order_status lbaw2182.order_status NOT NULL DEFAULT 'In_Negotiation',
   order_type lbaw2182.order_type NOT NULL,
   loan_start Date,
   loan_end Date,
   total_price REAL NOT NULL
   CONSTRAINT noOverlap CHECK (loan_end > loan_start OR loan_start = NULL)
   CONSTRAINT price_check CHECK (total_price > 0)

);

CREATE TABLE IF NOT EXISTS lbaw2182.Review (
   id SERIAL PRIMARY KEY,
   orderid INTEGER REFERENCES lbaw2182.Order (id) ON DELETE CASCADE,
   to_user INTEGER REFERENCES lbaw2182.users (id) ON DELETE CASCADE,
   from_user INTEGER REFERENCES lbaw2182.users (id) ON UPDATE CASCADE,
   rating INTEGER NOT NULL,
   comment TEXT,
   review_date DATE DEFAULT now() NOT NULL
   CONSTRAINT ratingbuyer_ck CHECK (((rating > 0) OR (rating <= 5)))
);

CREATE TABLE IF NOT EXISTS lbaw2182.Addresses(
   userid INTEGER REFERENCES lbaw2182.users (id) ON DELETE CASCADE ON UPDATE CASCADE,
   addr TEXT NOT NULL,
   city TEXT NOT NULL,
   country TEXT NOT NULL, 
   zipcode TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS lbaw2182.CategoryNames (
   id SERIAL PRIMARY KEY,
   name TEXT NOT NULL,
   CONSTRAINT CAT_NAMES_UK UNIQUE (id, name)
);

CREATE TABLE IF NOT EXISTS lbaw2182.Category (
   id SERIAL PRIMARY KEY,
   product_id INTEGER REFERENCES lbaw2182.Product (id) ON DELETE CASCADE,
   name INTEGER REFERENCES lbaw2182.CategoryNames (id) ON DELETE CASCADE,
   CONSTRAINT CAT_UK UNIQUE (product_id, name)
);

CREATE TABLE IF NOT EXISTS lbaw2182.Ticket (
   id SERIAL PRIMARY KEY,
   userid INTEGER REFERENCES lbaw2182.users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   support INTEGER REFERENCES lbaw2182.users (id) ON UPDATE CASCADE,
   title TEXT NOT NULL
);


CREATE TABLE IF NOT EXISTS lbaw2182.Message (
   id SERIAL PRIMARY KEY,
   sender INTEGER REFERENCES lbaw2182.Users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   message_type lbaw2182.message_type NOT NULL, 
   associated_order INTEGER REFERENCES lbaw2182.Order (id) ON UPDATE CASCADE ON DELETE CASCADE,
   associated_ticket INTEGER REFERENCES lbaw2182.Ticket (id) ON UPDATE CASCADE ON DELETE CASCADE,
   message TEXT NOT NULL,
   date DATE DEFAULT now() NOT NULL
);

CREATE TABLE IF NOT EXISTS lbaw2182.Wishlist (
   userid INTEGER NOT NULL REFERENCES lbaw2182.users (id) ON UPDATE CASCADE,
   product INTEGER NOT NULL REFERENCES lbaw2182.Product (id) ON UPDATE CASCADE,
   PRIMARY KEY (userid, product)
);

CREATE TABLE IF NOT EXISTS lbaw2182.Application (
   id SERIAL PRIMARY KEY,
   application_state lbaw2182.application_state NOT NULL DEFAULT 'Evaluating',
   userid INTEGER NOT NULL REFERENCES lbaw2182.users (id) ON UPDATE CASCADE,
   title TEXT NOT NULL,
   description TEXT NOT NULL
);

--TRIGGER01
--DROP FUNCTION IF EXISTS lbaw2182.anonymize_reviews CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.anonymize_reviews() RETURNS TRIGGER AS
$$BEGIN
    UPDATE lbaw2182."review" SET from_user = NULL
        WHERE from_user = OLD.id;
    DELETE FROM lbaw2182."review"
        WHERE to_user = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_reviews_on_delete
    BEFORE DELETE ON lbaw2182.users FOR EACH ROW
    EXECUTE PROCEDURE lbaw2182.anonymize_reviews();


--TRIGGER02/03
--DROP FUNCTION IF EXISTS lbaw2182.remove_products CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.remove_products() RETURNS TRIGGER AS
$$BEGIN
    DELETE FROM lbaw2182."product" 
        WHERE seller = OLD.id;
    UPDATE lbaw2182."ticket" SET support = NULL
        WHERE support = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_user_products
    BEFORE DELETE ON lbaw2182.users FOR EACH ROW
    EXECUTE PROCEDURE lbaw2182.remove_products();


--TRIGGER04
--DROP FUNCTION IF EXISTS lbaw2182.auto_order CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.auto_order() RETURNS TRIGGER AS
$$BEGIN
    IF EXISTS
    (
        SELECT *
        FROM lbaw2182.Product
        WHERE lbaw2182.Product.id = NEW.product
        AND NEW.client = lbaw2182.Product.seller
    )
    THEN
    RAISE EXCEPTION 'A user cannot loan their own products.';
    END IF;
    RETURN NEW;
END;$$
LANGUAGE plpgsql;

CREATE TRIGGER delete_auto_order
        BEFORE INSERT ON lbaw2182.Order
        FOR EACH ROW
        EXECUTE PROCEDURE lbaw2182.auto_order();


---TRIGGER05/06
--DROP FUNCTION IF EXISTS lbaw2182.verify_review_client CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.verify_review_client() RETURNS TRIGGER AS
$$BEGIN
    IF NOT EXISTS ( 
        SELECT *
        FROM lbaw2182.product JOIN lbaw2182.order
            ON lbaw2182.Product.id = lbaw2182.Order.product
        WHERE 
            (
                lbaw2182.Order.client = NEW.from_user
                AND 
                lbaw2182.Product.seller = NEW.to_user
            )
            OR
            (
                lbaw2182.Order.client = NEW.to_user
                AND 
                lbaw2182.Product.seller = NEW.from_user
            )
            AND lbaw2182.Order.id = NEW.orderid
    ) THEN
        RAISE EXCEPTION 'You can only review a user who you made a transaction with!';
    END IF;
        RETURN NEW;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER verify_review
    BEFORE INSERT ON lbaw2182.Review FOR EACH ROW
    EXECUTE PROCEDURE lbaw2182.verify_review_client();


---TRIGGER07
---DROP FUNCTION IF EXISTS lbaw2182.active_item CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.active_item() RETURNS TRIGGER AS
$$BEGIN
    IF NEW.order_status = 'Transaction_Completed'
        AND NEW.order_type = 'Purchase'
    THEN
        UPDATE lbaw2182."product" SET active = FALSE
            WHERE id = NEW.product;
    END IF;
    RETURN NEW;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER item_bought_now_inactive
    AFTER INSERT OR UPDATE ON lbaw2182.Order FOR EACH ROW
    EXECUTE PROCEDURE lbaw2182.active_item();


--TRIGGER08
--DROP FUNCTION IF EXISTS lbaw2182.wishlist_removal CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.wishlist_removal() RETURNS TRIGGER AS
$$
BEGIN
    DELETE FROM lbaw2182.Wishlist
        WHERE product = OLD.id;
    RETURN OLD;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER wishlist_check
    BEFORE DELETE ON lbaw2182.Product
    FOR EACH ROW
        EXECUTE PROCEDURE lbaw2182.wishlist_removal();


--TRIGGER09
--DROP FUNCTION IF EXISTS lbaw2182.remove_item_from_order CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.remove_item_from_order() RETURNS TRIGGER AS
$$
BEGIN
    UPDATE lbaw2182."order" SET product = NULL
        WHERE product = OLD.id;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_product_from_order
    BEFORE DELETE ON lbaw2182.Product
    FOR EACH ROW
        EXECUTE PROCEDURE lbaw2182.remove_item_from_order();


--TRIGGER10
--DROP FUNCTION IF EXISTS lbaw2182.check_account CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.check_account() RETURNS TRIGGER AS
$$BEGIN
    IF NOT EXISTS
    (
        SELECT *
        FROM lbaw2182.users
        WHERE NEW.seller = lbaw2182.users.id
        AND lbaw2182.users.accType = 'Seller'
    )
    THEN
    RAISE EXCEPTION 'Only a Seller can Sell Products.';
    END IF;
    RETURN NEW;
END;$$
LANGUAGE plpgsql;

CREATE TRIGGER check_seller_account
        BEFORE INSERT ON lbaw2182.Product
        FOR EACH ROW
        EXECUTE PROCEDURE lbaw2182.check_account();       
-----------------------------------------
-- Populate the database
-----------------------------------------

-----------------------------------
--  	User Inserts
-----------------------------------
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Thermed','admin@sailorsdream.com','1946-02-13','UTaxe6fieH',FALSE, 'Admin', '', 212288151);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Selinglese','selinglese@sailorsdream.com','1945-07-18','ohr7ieg2L',FALSE,'Support', '', 212725182);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Whanterrene','jemtally83@gmail.com','1956-04-13','OFK00XCC7OD',FALSE, 'Client', '', 212847851);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Catelleaden1969','najagreen12@gmail.com','1977-08-19','ieSh2riil',FALSE, 'User', '', 212722946);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Faidelper','kadenn2001@gmail.com','1984-02-12','ohGh3uuG0ei',TRUE, 'User', '', 212735614);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Sathereend','sunelliot17@gmail.com','1995-12-13','woh2be6Auph1',TRUE,'Seller', '', 212735615);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Whimpappered52','kaydenstark1952@gmail.com','1956-11-30','sheiJuot3phai',FALSE, 'Client', '', 212441165);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Difeentle','difeentle@sailorsdream.com','1966-06-17','PZJ77DKO2VZ',FALSE, 'Support', '', 212655776);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('Painged','paigestew1971@gmail.com','1969-09-19','SahWai0Ie',FALSE, 'Seller', '', 212344626);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('user','user@user.com','1995-05-16','$2y$10$uv4cv/FWDB3BXO4tmKCaWuzQIv20DeVp72d9M/AXHfnZqcxgxCMKy',FALSE, 'Client', '', 212659594);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('admin','admin@admin.com','1995-05-16','$2y$10$uv4cv/FWDB3BXO4tmKCaWuzQIv20DeVp72d9M/AXHfnZqcxgxCMKy',FALSE, 'Admin', '', 212347294);
INSERT INTO lbaw2182.users (username,email,birthDate,password,banned,accType,img,phone) VALUES ('seller','seller@seller.com','1995-05-16','$2y$10$uv4cv/FWDB3BXO4tmKCaWuzQIv20DeVp72d9M/AXHfnZqcxgxCMKy',FALSE, 'Seller', '', 212371594);

-----------------------------------
--  	Product Inserts
-----------------------------------
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 6, '1642728125.jpg', 'Veleiro', 'Tem uma folha no leme', TRUE, 200.00, NULL);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 6, '1642727376.jpg', 'Lancha', 'Rápida e ainda está inteira!', TRUE, 100.00, NULL);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 9, '1642727607.jpg', 'Iate', 'Brand new', TRUE,NULL, 75.00);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 9, '1642728201.jpg', 'Ketch', 'Used', TRUE,NULL, 65.00);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 9, '1642727623.jpg', 'Iate', 'New', FALSE,NULL, 95.00);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 9, '1642727780.jpg', 'Man O War', 'Old', TRUE,200.00, NULL);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 12, '1642728140.webp', 'Barco Leve', 'Brand new', TRUE,200.00, NULL);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 12, '1642727909.jpg', 'Iate Luxo', 'Brand new', TRUE,200.00, NULL);
INSERT INTO lbaw2182.Product (seller,img,productname,description,active,price,pricePerDay) VALUES ( 12, '1642728005.webp', 'Iate', 'Brand new', TRUE,250.00, NULL);

-----------------------------------
--  	Order Inserts
-----------------------------------
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 1, 3, 6, 'Transaction_Completed', 'Purchase',NULL,NULL, 200.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 1, 10, 6, 'Transaction_Completed', 'Loan', '2021-11-20', '2021-11-23', 75.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 6, 7, 9, 'Transaction_Failed', 'Purchase', NULL, NULL, 1175.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 2, 10, 6, 'In_Negotiation', 'Purchase', NULL, NULL, 1175.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 3, 10, 9, 'In_Negotiation', 'Loan', NULL, NULL, 300.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 4, 10, 9, 'In_Negotiation', 'Loan', NULL, NULL, 125.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 7, 7, 12, 'In_Negotiation', 'Purchase', NULL, NULL, 200.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 8, 3, 12, 'Transaction_Completed', 'Purchase', NULL, NULL, 200.00);
INSERT INTO lbaw2182.Order (product,client,seller,order_status,order_type,loan_start,loan_end, total_price) VALUES ( 9, 10, 12, 'In_Negotiation', 'Purchase', NULL, NULL, 200.00);

-----------------------------------
--  	Review Inserts
-----------------------------------
INSERT INTO lbaw2182.Review ( orderid, to_user, from_user, rating, comment) VALUES ( 1, 3, 6, 4, NULL);
INSERT INTO lbaw2182.Review ( orderid, to_user, from_user, rating, comment) VALUES ( 1, 6, 3, 5, NULL);
INSERT INTO lbaw2182.Review ( orderid, to_user, from_user, rating, comment) VALUES ( 2, 10, 6, 3, NULL);
INSERT INTO lbaw2182.Review ( orderid, to_user, from_user, rating, comment) VALUES ( 2, 6, 10, 2, NULL);

-----------------------------------
--  	Addresses Inserts
-----------------------------------

INSERT INTO lbaw2182.Addresses (userid,addr,city,country,zipcode) VALUES (9,'4152 Woodside Circle', 'Pensacola, FL', 'United States of America', '19801');
INSERT INTO lbaw2182.Addresses (userid,addr,city,country,zipcode) VALUES (6,'3452 Oakmound Drive', 'Chicago, IL', 'United States of America', '60626');

-----------------------------------
--   	CategoryNames Inserts
-----------------------------------
INSERT INTO lbaw2182.CategoryNames ( name) VALUES ('Water vehicle');
INSERT INTO lbaw2182.CategoryNames ( name) VALUES ('Luxurious vehicle');
INSERT INTO lbaw2182.CategoryNames ( name) VALUES ('Classics');

-----------------------------------
--   	Category Inserts
-----------------------------------
INSERT INTO lbaw2182.Category (product_id, name) VALUES (1, 1);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (2, 1);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (3, 2);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (4, 2);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (5, 2);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (6, 3);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (7, 2);
INSERT INTO lbaw2182.Category (product_id, name) VALUES (8, 2);

-----------------------------------
--  	Ticket Inserts
-----------------------------------
INSERT INTO lbaw2182.Ticket( userid, support, title) VALUES ( 3, 2, 'Problem with my loan!');
INSERT INTO lbaw2182.Ticket( userid, support, title) VALUES ( 6, 8, 'Cant see any product');

-----------------------------------
--  	Message Inserts
-----------------------------------
INSERT INTO lbaw2182.Message(sender, message_type, associated_order, associated_ticket, message) VALUES (6, 'Ticket', NULL, 2, 'Hey, can you fix the product pages please?');
INSERT INTO lbaw2182.Message(sender, message_type, associated_order, associated_ticket, message) VALUES (10, 'Order', 2, NULL, 'Oi, onde estaciono o iate?');

-----------------------------------
--  	Wishlist Inserts
-----------------------------------
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (3, 2);
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (3, 3);
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (4, 1);
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (4, 2);
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (4, 3);
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (4, 4);
INSERT INTO lbaw2182.Wishlist(userid, product) VALUES (4, 6);


-----------------------------------
--  	Application Inserts
-----------------------------------
INSERT INTO lbaw2182.Application(application_state ,userid, title, description) VALUES ('Rejected', 10, 'Hey', 'I want to apply to be a seller!');
INSERT INTO lbaw2182.Application(application_state ,userid, title, description) VALUES ('Evaluating', 10, 'Heyv2', 'its me again, I want to apply to be a seller!');

-----------------------------------------
-- end
-----------------------------------------

CREATE INDEX message_order
    ON lbaw2182.Message
    USING btree (associated_order);
    CLUSTER lbaw2182.Message USING message_order;

CREATE INDEX category_name
    ON lbaw2182.Category
    USING hash (name);

CREATE INDEX product_productname
    ON lbaw2182.Product
    USING hash (productname);

