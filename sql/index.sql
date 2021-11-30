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
