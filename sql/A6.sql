-----------------------------------------
-- Populate the database
-----------------------------------------

-----------------------------------
--  	User Inserts
-----------------------------------
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (1,'Thermed','admin@sailorsdream.com','1946-02-13','UTaxe6fieH',FALSE, 'Admin', '', 212288151);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (2,'Selinglese','selinglese@sailorsdream.com','1945-07-18','ohr7ieg2L',FALSE,'Support', '', 212725182);
INSERT INTO sailorsDream.Users (id,username,email,birthDate,password,banned,accType,img,phone) VALUES (3,'Whanterrene','jemtally83@gmail.com','1956-04-43','OFK00XCC7OD',FALSE, 'Client', '', 212847851);
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
