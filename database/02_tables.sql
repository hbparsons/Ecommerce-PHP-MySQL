
CREATE TABLE customer (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50),
    Firstname VARCHAR(50),
    Lastname VARCHAR(50),
    Password VARCHAR(100),
    Email VARCHAR(100),
    PhoneNumber VARCHAR(15),
    Address VARCHAR(255),
    isAdmin TINYINT(1),
    privacyAccepted TINYINT(1) DEFAULT NULL
);

ALTER TABLE customer MODIFY Username VARCHAR(50) BINARY;

CREATE TABLE category (
    CategoryID INT PRIMARY KEY AUTO_INCREMENT,
    CategoryName VARCHAR(100)
);

ALTER TABLE category AUTO_INCREMENT = 100;

CREATE TABLE product (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    Product_Name VARCHAR(100),
    ProductDescription TEXT,
    Stock INT,
    ProductPrice DECIMAL(10, 2),
    ImageFile VARCHAR(255)
);

ALTER TABLE product AUTO_INCREMENT = 1000;

CREATE TABLE product_category (
    CategoryID INT,
    ProductID INT,
    PRIMARY KEY (CategoryID, ProductID),
    FOREIGN KEY (CategoryID) REFERENCES category(CategoryID),
    FOREIGN KEY (ProductID) REFERENCES product(ProductID)
);

CREATE TABLE cart (
    UserID INT,
    ProductID INT,
    PurchasedQuantity INT,
    PRIMARY KEY (UserID, ProductID),
    FOREIGN KEY (UserID) REFERENCES customer(UserID),
    FOREIGN KEY (ProductID) REFERENCES product(ProductID)
);

CREATE TABLE order_history (
    OrderNumber INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    Date DATETIME,
	Status VARCHAR(45),
    FOREIGN KEY (UserID) REFERENCES customer(UserID)
);

CREATE TABLE order_details (
    OrderNumber INT,
    ProductID INT,
    PurchasedQuantity INT,
    ProductPrice DECIMAL(10, 2),
    PRIMARY KEY (OrderNumber, ProductID),
    FOREIGN KEY (OrderNumber) REFERENCES order_history(OrderNumber),
    FOREIGN KEY (ProductID) REFERENCES product(ProductID)
);