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
