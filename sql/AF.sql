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

--Index
CREATE INDEX message_order
    ON sailorsDream.Message
    USING hash (associated_order);
    CLUSTER sailorsDream.Message USING message_order

CREATE INDEX category_name
    ON sailorsDream.Category
    USING hash (name);
    CLUSTER sailorsDream.Category USING category_name

CREATE INDEX product_productname
    ON sailorsDream.Product
    USING hash (productname)

--Triggers
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
