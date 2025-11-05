
-- INSERTING DATA IN CUSTOMER TABLE
INSERT INTO customer (Username, Firstname, Lastname, Password, Email, PhoneNumber, Address, isAdmin, privacyAccepted) VALUES
('KPaneque', 'Katia', 'Paneque', '$2y$10$eUJZN1IdJ.DO3HMtrPU4n.SlZcEVaQ4mpWGxTDHkY1.ACKLcxWnv2', 'kpaneque@gmail.com', '555-1234', '123 Elm Street', 1, 1),
('HParsons', 'Hadley', 'Parsons', '$2y$10$n.e5rrH/JcyzFwW8fxhO/uS0/6Aj5WLTdwmQ4M/sf/b13WXr317Tu', 'hparsons@gmail.com', '555-5678', '456 Oak Avenue', 1, 1),
('CFraser', 'Ciara', 'Fraser', '$2y$10$bcG8u6QnxuiZEX9OPoWKqezk.cqG7FnkBNgb7BhPJE1Nm7oYAfoJ2', 'cfraser@gmail.com', '555-4321', '789 Pine Road', 0, 1),
('TSwift', 'Taylor', 'Swift', '$2y$10$eUJZN1IdJ.DO3HMtrPU4n.SlZcEVaQ4mpWGxTDHkY1.ACKLcxWnv2', 'tswift@gmail.com', '777-1234', '45 Bell St', 0, null),
('ASandler@2024', 'Adam', 'Sandler', '$2y$10$n.e5rrH/JcyzFwW8fxhO/uS0/6Aj5WLTdwmQ4M/sf/b13WXr317Tu', 'asandler@gmail.com', '777-4321', '633 Anderson St', 0, 0);



-- INSERTING DATA IN CATEGORY TABLE
INSERT INTO category (CategoryName) VALUES 
('Soft'),
('Hard'),
('Vegan'),
('Chocolate'),
('Sour');


-- INSERTING DATA IN PRODUCT TABLE

INSERT INTO product (Product_Name, ProductDescription, Stock, ProductPrice, ImageFile) VALUES 
('Candy Bracelet', 'Wearable candy bracelet', 10, 2.5, 'images/products/candy-bracelet.jpg'),
('Candy Corn', 'A sweet halloween treat', 20, 0.6, 'images/products/candy-corn.jpg'),
('Candy Heart', 'Complete with cute sayings', 30, 0.6, 'images/products/candy-heart.jpg'),
('Chocolate Bar', 'Milk chocolate', 50, 5, 'images/products/chocolate-bar.jpg'),
('Box of Chocolates', 'Assorted chocolates', 10, 25, 'images/products/chocolate-box.jpg'),
('Chocolate Peanut Bar', 'Chocolate bar with peanuts', 20, 5.5, 'images/products/chocolate-peanut.png'),
('Chocolate Truffle', 'Assorted chocolate truffles', 30, 1.5, 'images/products/chocolate-truffle.jpg'),
('Cinnamon Hearts', 'Spicy!', 40, 0.5, 'images/products/cinnamon-heart.jpg'),
('Citrus Gummies', 'Sweet and sour citrus flavor', 50, 0.75, 'images/products/citrus-gum.jpg'),
('Cola-Gummies', 'Classic gummy', 10, 0.25, 'images/products/coke-gum.jpg'),
('Hardy Candy Fruit Mix', 'Assorted hard candies', 20, 0.6, 'images/products/fruit-mix.jpg'),
('Gummy Bears', 'Assorted gummy candies', 30, 0.6, 'images/products/gummybear.jpg'),
('Sour Lemons', 'Sour lemon hard candies', 40, 0.6, 'images/products/lemon-sour.jpg'),
('Licorice All-Sorts', 'Assorted licorice pieces', 50, 0.6, 'images/products/licorice-allsorts.jpg'),
('Lollipop', 'Assorted lollipops!', 10, 1, 'images/products/lollipop.jpg'),
('Smarties', 'They\'re smarties.', 20, 0.6, 'images/products/smarties.jpg'),
('Sour Spiders', 'The only spider you\'re happy to see.', 30, 0.6, 'images/products/sour-spider.jpg'),
('Sour Squares', 'They\'re sour and square.', 40, 0.6, 'images/products/sour-squares.jpg'),
('Tri-Coloured Gummies', 'Sour and sweet gummy hearts', 50, 0.6, 'images/products/tri-gum.jpg'),
('Wine Gums', 'Not from your grandma\'s purse.', 10, 0.6, 'images/products/wine-gum.jpg');


-- Insert the data into the Product_Category table
INSERT INTO product_category (CategoryID, ProductID) VALUES 
(101, 1000), -- Candy Bracelet
(102, 1000), -- Candy Bracelet
(100, 1001), -- Candy Corn
(101, 1002), -- Candy Heart
(102, 1002), -- Candy Heart
(103, 1003), -- Chocolate Bar
(103, 1004), -- Box of Chocolates
(103, 1005), -- Chocolate Peanut Bar
(103, 1006), -- Chocolate Truffle
(101, 1007), -- Cinnamon Hearts
(102, 1007), -- Cinnamon Hearts
(100, 1008), -- Citrus Gummies
(104, 1008), -- Citrus Gummies
(100, 1009), -- Cola-Gummies
(100, 1010), -- Hardy Candy Fruit Mix
(102, 1010), -- Hardy Candy Fruit Mix
(100, 1011), -- Gummy Bears
(101, 1012), -- Sour Lemons
(104, 1012), -- Sour Lemons
(100, 1013), -- Licorice All-Sorts
(101, 1014), -- Lollipop
(102, 1014), -- Lollipop
(103, 1015), -- Smarties
(100, 1016), -- Sour Spiders
(104, 1016), -- Sour Spiders
(100, 1017), -- Sour Squares
(104, 1017), -- Sour Squares
(100, 1018), -- Tri-Coloured Gummies
(104, 1018), -- Tri-Coloured Gummies
(100, 1019); -- Wine Gums


-- Insert sample data into the Cart table
INSERT INTO cart (UserID, ProductID, PurchasedQuantity) VALUES
(1, 1000, 2), -- UserID 1 purchasing 2 quantities of ProductID 1000
(2, 1001, 5), -- UserID 2 purchasing 5 quantities of ProductID 1001
(1, 1003, 1), -- UserID 1 purchasing 1 quantity of ProductID 1003
(3, 1002, 3), -- UserID 3 purchasing 3 quantities of ProductID 1002
(2, 1004, 4); -- UserID 2 purchasing 4 quantities of ProductID 1004

INSERT INTO cart (UserID, ProductID, PurchasedQuantity) VALUES
(5, 1001, 3), -- Adam purchasing 3 quantities of ProductID 1001
(5, 1002, 2); -- Adam purchasing 2 quantities of ProductID 100


-- Insert sample data into the Order_History table
INSERT INTO order_history (UserID, Date, Status) VALUES
(1, '2024-05-20', 'Completed'),
(2, '2024-05-21', 'Completed'),
(1, '2024-05-22', 'Completed'),
(3, '2024-05-23', 'Completed'),
(2, '2024-05-24', 'Completed');

INSERT INTO order_history (UserID, Date, Status) VALUES
(5, '2024-06-01', 'Completed'), -- New order for Adam
(5, '2024-06-02', 'Pending');   -- Another new order for Adam

-- Insert sample data into the Order_Details table
INSERT INTO order_details (OrderNumber, ProductID, PurchasedQuantity, ProductPrice) VALUES
(1, 1000, 2, 2.50), -- OrderNumber 1 has 2 quantities of ProductID 1000 at a price of 2.50 each
(1, 1003, 1, 5.00), -- OrderNumber 1 has 1 quantity of ProductID 1003 at a price of 5.00
(2, 1001, 5, 0.60), -- OrderNumber 2 has 5 quantities of ProductID 1001 at a price of 0.10 each
(3, 1002, 3, 0.60), -- OrderNumber 3 has 3 quantities of ProductID 1002 at a price of 0.10 each
(3, 1004, 2, 25.00), -- OrderNumber 3 has 2 quantities of ProductID 1004 at a price of 25.00 each
(4, 1003, 4, 5.00), -- OrderNumber 4 has 4 quantities of ProductID 1003 at a price of 5.00 each
(5, 1005, 1, 5.50), -- OrderNumber 5 has 1 quantity of ProductID 1005 at a price of 5.50
(5, 1006, 2, 1.50); -- OrderNumber 5 has 2 quantities of ProductID 1006 at a price of 1.50 each

INSERT INTO order_details (OrderNumber, ProductID, PurchasedQuantity, ProductPrice) VALUES
(6, 1001, 3, 0.60), -- OrderNumber 6 with ProductID 1001, 3 quantities at 0.10 each
(6, 1003, 1, 5.00), -- OrderNumber 6 with ProductID 1003, 1 quantity at 5.00 each
(7, 1002, 2, 0.60), -- OrderNumber 7 with ProductID 1002, 2 quantities at 0.10 each
(7, 1005, 1, 5.50); -- OrderNumber 7 with ProductID 1005, 1 quantity at 5.50 each

