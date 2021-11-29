--TRIGGER04
DROP FUNCTION IF EXISTS anonymize_reviews CASCADE;
CREATE OR REPLACE FUNCTION anonymize_reviews() RETURNS TRIGGER AS
$$BEGIN
    UPDATE sailorsdream."review" SET from_user = NULL
        WHERE from_user = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER anonymize_reviews_on_delete
    BEFORE DELETE ON sailorsDream.Users FOR EACH ROW
    EXECUTE PROCEDURE anonymize_reviews();


--TRIGGER05/06
DROP FUNCTION IF EXISTS remove_products CASCADE;
CREATE OR REPLACE FUNCTION remove_products() RETURNS TRIGGER AS
$$BEGIN
    DELETE FROM sailorsdream."product" 
        WHERE seller = OLD.id;
    DELETE FROM sailorsdream."review"
        WHERE to_user = OLD.id;
    UPDATE sailorsdream."review" set from_user = NULL
        WHERE from_user = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_user_products
    BEFORE DELETE ON sailorsDream.Users FOR EACH ROW
    EXECUTE PROCEDURE remove_products();


--TRIGGER07
DROP FUNCTION IF EXISTS auto_order CASCADE;
CREATE OR REPLACE FUNCTION auto_order() RETURNS TRIGGER AS
$$BEGIN
    IF EXISTS
    (
        SELECT *
        FROM sailorsDream.Order JOIN sailorsDream.Product 
            ON sailorsDream.Order.product = sailorsDream.Product.id
        WHERE sailorsDream.Product.id = sailorsDream.Order.product
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


---TRIGGER08/09
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


---TRIGGER11
DROP FUNCTION IF EXISTS remove_item CASCADE;
CREATE OR REPLACE FUNCTION remove_item() RETURNS TRIGGER AS
$$BEGIN
    UPDATE sailorsdream."order" SET product = NULL
        WHERE product = OLD.id;
    RETURN OLD;
END;$$ LANGUAGE plpgsql;

CREATE TRIGGER remove_product_from_order
    BEFORE DELETE ON sailorsDream.Product FOR EACH ROW
    EXECUTE PROCEDURE remove_item();


--TRIGGER12
DROP FUNCTION IF EXISTS wishlist_removal CASCADE;
CREATE OR REPLACE FUNCTION wishlist_removal() RETURNS TRIGGER AS
$$
BEGIN
    DELETE FROM sailorsDream.Wishlist WHERE
        product = OLD.id;
    RETURN OLD;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER wishlist_check
    BEFORE DELETE ON sailorsDream.Product
    FOR EACH ROW
        EXECUTE PROCEDURE wishlist_removal();