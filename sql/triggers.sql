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
