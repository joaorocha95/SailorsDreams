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
