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


--TRIGGER01
DROP FUNCTION IF EXISTS anonymize_reviews CASCADE;
CREATE OR REPLACE FUNCTION anonymize_reviews() RETURNS TRIGGER AS
$$BEGIN
    UPDATE sailorsdream."review" SET from_user = NULL
        WHERE from_user = OLD.id;
    DELETE FROM sailorsdream."review"
        WHERE to_user = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_reviews_on_delete
    BEFORE DELETE ON sailorsDream.Users FOR EACH ROW
    EXECUTE PROCEDURE anonymize_reviews();


--TRIGGER02/03
DROP FUNCTION IF EXISTS remove_products CASCADE;
CREATE OR REPLACE FUNCTION remove_products() RETURNS TRIGGER AS
$$BEGIN
    DELETE FROM sailorsdream."product" 
        WHERE seller = OLD.id;
    UPDATE sailorsDream."ticket" SET support = NULL
        WHERE support = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_user_products
    BEFORE DELETE ON sailorsDream.Users FOR EACH ROW
    EXECUTE PROCEDURE remove_products();


--TRIGGER04
DROP FUNCTION IF EXISTS auto_order CASCADE;
CREATE OR REPLACE FUNCTION auto_order() RETURNS TRIGGER AS
$$BEGIN
    IF EXISTS
    (
        SELECT *
        FROM sailorsDream.Product
        WHERE sailorsDream.Product.id = NEW.product
        AND NEW.client = sailorsDream.Product.seller
    )
    THEN
    RAISE EXCEPTION 'A user cannot loan their own products.';
    END IF;
    RETURN NEW;
END;$$
LANGUAGE plpgsql;

CREATE TRIGGER delete_auto_order
        BEFORE INSERT ON sailorsDream.Order
        FOR EACH ROW
        EXECUTE PROCEDURE auto_order();


---TRIGGER05/06
DROP FUNCTION IF EXISTS verify_review_client CASCADE;
CREATE OR REPLACE FUNCTION verify_review_client() RETURNS TRIGGER AS
$$BEGIN
    IF NOT EXISTS ( 
        SELECT *
        FROM sailorsdream.product JOIN sailorsdream.order
            ON sailorsdream.Product.id = sailorsdream.Order.product
        WHERE 
            (
                sailorsDream.Order.client = NEW.from_user
                AND 
                sailorsDream.Product.seller = NEW.to_user
            )
            OR
            (
                sailorsDream.Order.client = NEW.to_user
                AND 
                sailorsDream.Product.seller = NEW.from_user
            )
            AND sailorsDream.Order.id = NEW.orderid
    ) THEN
        RAISE EXCEPTION 'You can only review a user who you made a transaction with!';
    END IF;
        RETURN NEW;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER verify_review
    BEFORE INSERT ON sailorsDream.Review FOR EACH ROW
    EXECUTE PROCEDURE verify_review_client();


---TRIGGER07
DROP FUNCTION IF EXISTS active_item CASCADE;
CREATE OR REPLACE FUNCTION active_item() RETURNS TRIGGER AS
$$BEGIN
    IF NEW.order_status = 'Transaction_Completed'
        AND NEW.order_type = 'Purchase'
    THEN
        UPDATE sailorsdream."product" SET active = FALSE
            WHERE id = NEW.product;
    END IF;
    RETURN NEW;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER item_bought_now_inactive
    AFTER INSERT OR UPDATE ON sailorsDream.Order FOR EACH ROW
    EXECUTE PROCEDURE active_item();


--TRIGGER08
DROP FUNCTION IF EXISTS wishlist_removal CASCADE;
CREATE OR REPLACE FUNCTION wishlist_removal() RETURNS TRIGGER AS
$$
BEGIN
    DELETE FROM sailorsDream.Wishlist
        WHERE product = OLD.id;
    RETURN OLD;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER wishlist_check
    BEFORE DELETE ON sailorsDream.Product
    FOR EACH ROW
        EXECUTE PROCEDURE wishlist_removal();


--TRIGGER09
DROP FUNCTION IF EXISTS remove_item_from_order CASCADE;
CREATE OR REPLACE FUNCTION remove_item_from_order() RETURNS TRIGGER AS
$$
BEGIN
    UPDATE sailorsdream."order" SET product = NULL
        WHERE product = OLD.id;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_product_from_order
    BEFORE DELETE ON sailorsDream.Product
    FOR EACH ROW
        EXECUTE PROCEDURE remove_item_from_order();


--TRIGGER10
DROP FUNCTION IF EXISTS check_account CASCADE;
CREATE OR REPLACE FUNCTION check_account() RETURNS TRIGGER AS
$$BEGIN
    IF NOT EXISTS
    (
        SELECT *
        FROM sailorsDream.Users
        WHERE NEW.seller = sailorsDream.Users.id
        AND sailorsDream.Users.accType = 'Seller'
    )
    THEN
    RAISE EXCEPTION 'Only a Seller can Sell Products.';
    END IF;
    RETURN NEW;
END;$$
LANGUAGE plpgsql;

CREATE TRIGGER check_seller_account
        BEFORE INSERT ON sailorsDream.Product
        FOR EACH ROW
        EXECUTE PROCEDURE check_account();
        
-----------------------------------------
-- Populate the database
-----------------------------------------

-----------------------------------
--  	User Inserts
-----------------------------------
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (1,'Thermed','admin@sailorsdream.com','1946-02-13','UTaxe6fieH',FALSE, 'Admin', '', 212288151);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (2,'Selinglese','selinglese@sailorsdream.com','1945-07-18','ohr7ieg2L',FALSE,'Support', '', 212725182);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (3,'Whanterrene','jemtally83@gmail.com','1956-04-13','OFK00XCC7OD',FALSE, 'Client', '', 212847851);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (4,'Catelleaden1969','najagreen12@gmail.com','1977-08-19','ieSh2riil',FALSE, 'User', '', 212722946);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (5,'Faidelper','kadenn2001@gmail.com','1984-02-12','ohGh3uuG0ei',TRUE, 'User', '', 212735614);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (6,'Sathereend','sunelliot17@gmail.com','1995-12-13','woh2be6Auph1',TRUE,'Seller', '', 212735615);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (7,'Whimpappered52','kaydenstark1952@gmail.com','1956-11-30','sheiJuot3phai',FALSE, 'Client', '', 212441165);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (8,'Difeentle','difeentle@sailorsdream.com','1966-06-17','PZJ77DKO2VZ',FALSE, 'Support', '', 212655776);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (9,'Painged','paigestew1971@gmail.com','1969-09-19','SahWai0Ie',FALSE, 'Seller', '', 212344626);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (10,'Eneive','mcdonaldgrey@gmail.com','1995-05-16','eeNgeiHi5',FALSE, 'Client', '', 212349594);

-----------------------------------
--  	Product Inserts
-----------------------------------
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (1, 6, 'Veleiro', 'Tem uma folha no leme', TRUE, 200.00, NULL);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (2, 6, 'Lancha', 'Rápida e ainda está inteira!', TRUE, 100.00, NULL);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (3, 9, 'Iate', 'Brand new', TRUE,NULL, 75.00);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (4, 9, 'Ketch', 'Used', TRUE,NULL, 65.00);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (5, 9, 'Iate', 'New', FALSE,NULL, 95.00);
INSERT INTO sailorsDream.Product (id,seller,productname,description,active,price,pricePerDay) VALUES (6, 9, 'Man O War', 'Old', TRUE,NULL, 1175.00);

-----------------------------------
--  	Order Inserts
-----------------------------------
INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (1, 1, 3, 'Transaction_Completed', 'Purchase',NULL,NULL, 200.00);
INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (2, 1, 10, 'Transaction_Completed', 'Loan', '2021-11-20', '2021-11-23', 75.00);
INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (3, 6, 7, 'Transaction_Failed', 'Purchase', NULL, NULL, 1175.00);
INSERT INTO sailorsDream.Order (id,product,client,order_status,order_type,loan_start,loan_end, total_price) VALUES (4, 6, 7, 'In_Negotiation', 'Purchase', NULL, NULL, 1175.00);


-----------------------------------
--  	Review Inserts
-----------------------------------
INSERT INTO sailorsDream.Review (id, orderid, to_user, from_user, rating, comment) VALUES (1, 1, 3, 6, 4, NULL);
INSERT INTO sailorsDream.Review (id, orderid, to_user, from_user, rating, comment) VALUES (2, 1, 6, 3, 5, NULL);
INSERT INTO sailorsDream.Review (id, orderid, to_user, from_user, rating, comment) VALUES (3, 2, 10, 6, 3, NULL);
INSERT INTO sailorsDream.Review (id, orderid, to_user, from_user, rating, comment) VALUES (4, 2, 6, 10, 2, NULL);

-----------------------------------
--  	Addresses Inserts
-----------------------------------

INSERT INTO sailorsDream.Addresses (userid,addr,city,country,zipcode) VALUES (9,'4152 Woodside Circle', 'Pensacola, FL', 'United States of America', '19801');
INSERT INTO sailorsDream.Addresses (userid,addr,city,country,zipcode) VALUES (6,'3452 Oakmound Drive', 'Chicago, IL', 'United States of America', '60626');

-----------------------------------
--   	Category Inserts
-----------------------------------
INSERT INTO sailorsDream.Category (product_id, name) VALUES (1, 'Water vehicle');
INSERT INTO sailorsDream.Category (product_id, name) VALUES (2, 'Water vehicle');
INSERT INTO sailorsDream.Category (product_id, name) VALUES (3, 'Luxurious vehicle');
INSERT INTO sailorsDream.Category (product_id, name) VALUES (4, 'Luxurious vehicle');
INSERT INTO sailorsDream.Category (product_id, name) VALUES (5, 'Luxurious vehicle');
INSERT INTO sailorsDream.Category (product_id, name) VALUES (6, 'Classics');

-----------------------------------
--  	Ticket Inserts
-----------------------------------
INSERT INTO sailorsDream.Ticket(id, userid, support, title) VALUES (1, 3, 2, 'Problem with my loan!');
INSERT INTO sailorsDream.Ticket(id, userid, support, title) VALUES (2, 6, 8, 'Cant see any product');

-----------------------------------
--  	Message Inserts
-----------------------------------
INSERT INTO sailorsDream.Message(id, message_type, associated_order, associated_ticket, message) VALUES (1, 'Ticket', NULL, 2, 'Hey, can you fix the product pages please?');
INSERT INTO sailorsDream.Message(id, message_type, associated_order, associated_ticket, message) VALUES (2, 'Order', 2, NULL, 'Oi, onde estaciono o iate?');

-----------------------------------
--  	Wishlist Inserts
-----------------------------------
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (3, 2);
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (3, 3);
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (4, 1);
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (4, 2);
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (4, 3);
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (4, 4);
INSERT INTO sailorsDream.Wishlist(userid, product) VALUES (4, 6);

-----------------------------------------
-- end
-----------------------------------------

CREATE INDEX message_order
    ON sailorsDream.Message
    USING btree (associated_order);
    CLUSTER sailorsDream.Message USING message_order;

CREATE INDEX category_name
    ON sailorsDream.Category
    USING hash (name);

CREATE INDEX product_productname
    ON sailorsDream.Product
    USING hash (productname);

