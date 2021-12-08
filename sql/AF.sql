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

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE IF NOT EXISTS lbaw2182.Users (
   id SERIAL PRIMARY KEY,
   username TEXT NOT NULL,
   email TEXT NOT NULL UNIQUE,
   birthDate DATE NOT NULL,
   password TEXT NOT NULL,
   banned BOOLEAN NOT NULL,
   accType lbaw2182.accType NOT NULL DEFAULT 'User',
   img TEXT,
   phone INTEGER NOT NULL UNIQUE
);

--psql --host=db --username=postgres --dbname=gitlab -f BD.sql
CREATE TABLE IF NOT EXISTS lbaw2182.Product (
   id SERIAL PRIMARY KEY,
   seller INTEGER REFERENCES lbaw2182.Users (id) ON DELETE CASCADE,
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
   client INTEGER REFERENCES lbaw2182.Users (id) ON UPDATE CASCADE,
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
   to_user INTEGER REFERENCES lbaw2182.Users (id) ON DELETE CASCADE,
   from_user INTEGER REFERENCES lbaw2182.Users (id) ON UPDATE CASCADE,
   rating INTEGER NOT NULL,
   comment TEXT,
   review_date DATE DEFAULT now() NOT NULL
   CONSTRAINT ratingbuyer_ck CHECK (((rating > 0) OR (rating <= 5)))
);

CREATE TABLE IF NOT EXISTS lbaw2182.Addresses(
   userid INTEGER REFERENCES lbaw2182.Users (id) ON DELETE CASCADE ON UPDATE CASCADE,
   addr TEXT NOT NULL,
   city TEXT NOT NULL,
   country TEXT NOT NULL, 
   zipcode TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS lbaw2182.Category (
   product_id INTEGER REFERENCES lbaw2182.Product (id) ON DELETE CASCADE,
   name TEXT NOT NULL,
   UNIQUE (product_id, name)
);

CREATE TABLE IF NOT EXISTS lbaw2182.Ticket (
   id SERIAL PRIMARY KEY,
   userid INTEGER REFERENCES lbaw2182.Users (id) ON UPDATE CASCADE ON DELETE CASCADE,
   support INTEGER REFERENCES lbaw2182.Users (id) ON UPDATE CASCADE,
   title TEXT NOT NULL
);


CREATE TABLE IF NOT EXISTS lbaw2182.Message (
   id SERIAL PRIMARY KEY,
   message_type lbaw2182.message_type NOT NULL, 
   associated_order INTEGER REFERENCES lbaw2182.Order (id) ON UPDATE CASCADE ON DELETE CASCADE,
   associated_ticket INTEGER REFERENCES lbaw2182.Ticket (id) ON UPDATE CASCADE ON DELETE CASCADE,
   message TEXT NOT NULL,
   date DATE DEFAULT now() NOT NULL
);

CREATE TABLE IF NOT EXISTS lbaw2182.Wishlist (
   userid INTEGER NOT NULL REFERENCES lbaw2182.Users (id) ON UPDATE CASCADE,
   product INTEGER NOT NULL REFERENCES lbaw2182.Product (id) ON UPDATE CASCADE,
   PRIMARY KEY (userid, product)
);

--TRIGGER01
DROP FUNCTION IF EXISTS lbaw2182.anonymize_reviews CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.anonymize_reviews() RETURNS TRIGGER AS
$$BEGIN
    UPDATE lbaw2182."review" SET from_user = NULL
        WHERE from_user = OLD.id;
    DELETE FROM lbaw2182."review"
        WHERE to_user = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_reviews_on_delete
    BEFORE DELETE ON lbaw2182.Users FOR EACH ROW
    EXECUTE PROCEDURE lbaw2182.anonymize_reviews();


--TRIGGER02/03
DROP FUNCTION IF EXISTS lbaw2182.remove_products CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.remove_products() RETURNS TRIGGER AS
$$BEGIN
    DELETE FROM lbaw2182."product" 
        WHERE seller = OLD.id;
    UPDATE lbaw2182."ticket" SET support = NULL
        WHERE support = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_user_products
    BEFORE DELETE ON lbaw2182.Users FOR EACH ROW
    EXECUTE PROCEDURE lbaw2182.remove_products();


--TRIGGER04
DROP FUNCTION IF EXISTS lbaw2182.auto_order CASCADE;
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
DROP FUNCTION IF EXISTS lbaw2182.verify_review_client CASCADE;
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
DROP FUNCTION IF EXISTS lbaw2182.active_item CASCADE;
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
DROP FUNCTION IF EXISTS lbaw2182.wishlist_removal CASCADE;
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
DROP FUNCTION IF EXISTS lbaw2182.remove_item_from_order CASCADE;
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
DROP FUNCTION IF EXISTS lbaw2182.check_account CASCADE;
CREATE OR REPLACE FUNCTION lbaw2182.check_account() RETURNS TRIGGER AS
$$BEGIN
    IF NOT EXISTS
    (
        SELECT *
        FROM lbaw2182.Users
        WHERE NEW.seller = lbaw2182.Users.id
        AND lbaw2182.Users.accType = 'Seller'
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
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (1,'Thermed','admin@sailorsdream.com','1946-02-13','UTaxe6fieH',FALSE, 'Admin', '', 212288151);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (2,'Selinglese','selinglese@sailorsdream.com','1945-07-18','ohr7ieg2L',FALSE,'Support', '', 212725182);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (3,'Whanterrene','jemtally83@gmail.com','1956-04-13','OFK00XCC7OD',FALSE, 'Client', '', 212847851);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (4,'Catelleaden1969','najagreen12@gmail.com','1977-08-19','ieSh2riil',FALSE, 'User', '', 212722946);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (5,'Faidelper','kadenn2001@gmail.com','1984-02-12','ohGh3uuG0ei',TRUE, 'User', '', 212735614);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (6,'Sathereend','sunelliot17@gmail.com','1995-12-13','woh2be6Auph1',TRUE,'Seller', '', 212735615);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (7,'Whimpappered52','kaydenstark1952@gmail.com','1956-11-30','sheiJuot3phai',FALSE, 'Client', '', 212441165);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (8,'Difeentle','difeentle@sailorsdream.com','1966-06-17','PZJ77DKO2VZ',FALSE, 'Support', '', 212655776);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (9,'Painged','paigestew1971@gmail.com','1969-09-19','SahWai0Ie',FALSE, 'Seller', '', 212344626);
INSERT INTO lbaw2182.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (10,'Eneive','mcdonaldgrey@gmail.com','1995-05-16','eeNgeiHi5',FALSE, 'Client', '', 212349594);

-----------------------------------
--  	Product Inserts
-----------------------------------
INSERT INTO lbaw2182.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (1, 6, 'Veleiro', 'Tem uma folha no leme', TRUE, 200.00, NULL);
INSERT INTO lbaw2182.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (2, 6, 'Lancha', 'Rápida e ainda está inteira!', TRUE, 100.00, NULL);
INSERT INTO lbaw2182.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (3, 9, 'Iate', 'Brand new', TRUE,NULL, 75.00);
INSERT INTO lbaw2182.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (4, 9, 'Ketch', 'Used', TRUE,NULL, 65.00);
INSERT INTO lbaw2182.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (5, 9, 'Iate', 'New', FALSE,NULL, 95.00);
INSERT INTO lbaw2182.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (6, 9, 'Man O War', 'Old', TRUE,NULL, 1175.00);

-----------------------------------
--  	Order Inserts
-----------------------------------
INSERT INTO lbaw2182.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (1, 1, 3, 'Transaction_Completed', 'Purchase',NULL,NULL, 200.00);
INSERT INTO lbaw2182.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (2, 1, 10, 'Transaction_Completed', 'Loan', '2021-11-20', '2021-11-23', 75.00);
INSERT INTO lbaw2182.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (3, 6, 7, 'Transaction_Failed', 'Purchase', NULL, NULL, 1175.00);
INSERT INTO lbaw2182.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (4, 6, 7, 'In_Negotiation', 'Purchase', NULL, NULL, 1175.00);


-----------------------------------
--  	Review Inserts
-----------------------------------
INSERT INTO lbaw2182.Review (id, orderid, to_user, from_user, rating, comment) VALUES (1, 1, 3, 6, 4, NULL);
INSERT INTO lbaw2182.Review (id, orderid, to_user, from_user, rating, comment) VALUES (2, 1, 6, 3, 5, NULL);
INSERT INTO lbaw2182.Review (id, orderid, to_user, from_user, rating, comment) VALUES (3, 2, 10, 6, 3, NULL);
INSERT INTO lbaw2182.Review (id, orderid, to_user, from_user, rating, comment) VALUES (4, 2, 6, 10, 2, NULL);

-----------------------------------
--  	Addresses Inserts
-----------------------------------

INSERT INTO lbaw2182.Addresses (userid,addr,city,country,zipcode) VALUES (9,'4152 Woodside Circle', 'Pensacola, FL', 'United States of America', '19801');
INSERT INTO lbaw2182.Addresses (userid,addr,city,country,zipcode) VALUES (6,'3452 Oakmound Drive', 'Chicago, IL', 'United States of America', '60626');

-----------------------------------
--   	Category Inserts
-----------------------------------
INSERT INTO lbaw2182.Category (product_id, name) VALUES (1, 'Water vehicle');
INSERT INTO lbaw2182.Category (product_id, name) VALUES (2, 'Water vehicle');
INSERT INTO lbaw2182.Category (product_id, name) VALUES (3, 'Luxurious vehicle');
INSERT INTO lbaw2182.Category (product_id, name) VALUES (4, 'Luxurious vehicle');
INSERT INTO lbaw2182.Category (product_id, name) VALUES (5, 'Luxurious vehicle');
INSERT INTO lbaw2182.Category (product_id, name) VALUES (6, 'Classics');

-----------------------------------
--  	Ticket Inserts
-----------------------------------
INSERT INTO lbaw2182.Ticket(id, userid, support, title) VALUES (1, 3, 2, 'Problem with my loan!');
INSERT INTO lbaw2182.Ticket(id, userid, support, title) VALUES (2, 6, 8, 'Cant see any product');

-----------------------------------
--  	Message Inserts
-----------------------------------
INSERT INTO lbaw2182.Message(id, message_type, associated_order, associated_ticket, message) VALUES (1, 'Ticket', NULL, 2, 'Hey, can you fix the product pages please?');
INSERT INTO lbaw2182.Message(id, message_type, associated_order, associated_ticket, message) VALUES (2, 'Order', 2, NULL, 'Oi, onde estaciono o iate?');

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

