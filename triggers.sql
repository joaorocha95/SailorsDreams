--TRIGGER01
CREATE FUNCTION loan_item() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * FROM sailorsDream.Order
            WHERE product = new.product
            AND order_type = 'Loan'
            AND (
                (NEW.loan_start > loan_start AND NEW.loan_start < loan_end)
                OR
                (NEW.loan_end > loan_start AND NEW.loan_end < loan_end)
            )
            AND order_status = 'Transaction_Completed'
        )THEN
        RAISE EXCEPTION 'An item can only be loaned to one user at a time.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER loan_item
        BEFORE INSERT OR UPDATE ON sailorsDream.Order
        FOR EACH ROW
        EXECUTE PROCEDURE loan_item();


--TRIGGER04
CREATE FUNCTION review_remove_user() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE sailorsDream.Review SET from_user = NULL WHERE from_user = OLD.sailorsDream.Users.id
END
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER delete_user_review
        BEFORE DELETE ON sailorsDream.Users
        FOR EACH ROW
        EXECUTE PROCEDURE review_remove_user();


--TRIGGER05 -> Acho que basta adicionar um 'ON DELETE CASCADE' na linha do 'to_user' da tabela 'Review'
--TRIGGER06 -> A mesma coisa """"


--TRIGGER07
CREATE FUNCTION auto_order() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF 
        NEW.sailorsDream.Order.client == (SELECT seller FROM sailorsDream.Product WHERE id = NEW.sailorsDream.Order.product)
    THEN
    RAISE EXCEPTION 'You cannot buy your own items.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER delete_auto_order
        BEFORE INSERT ON sailorsDream.Order
        FOR EACH ROW
        EXECUTE PROCEDURE auto_order();


--TRIGGER08
CREATE FUNCTION validate_review() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS(
        SELECT * FROM sailorsDream.Order JOIN sailorsDream.Review 
            ON sailorsDream.Review.orderid = sailorsDream.Order.id
        WHERE to_user = NEW.sailorsDream.Review.to_user
    )THEN
    
END
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER valid_review
        BEFORE INSERT OR UPDATE ON sailorsDream.Review
        FOR EACH ROW
        EXECUTE PROCEDURE validate_review();


CREATE FUNCTION remove_poduct() RETURNS TRIGGER AS
$BODY$
BEGIN



END
$BODY$

CREATE OR REPLACE TRIGGER 