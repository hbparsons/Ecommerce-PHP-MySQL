<?php

function getAllProducts($dbc)
{
    $sql = 'SELECT * FROM product';
    $result = mysqli_query($dbc, $sql);
    return $result;
}

function getCategoryDescription($dbc)
{
    $sql = 'SELECT * FROM category ORDER BY CategoryID';
    $result = mysqli_query($dbc, $sql);
    return $result;
}

function getCategoryByID($dbc, $categoryID)
{
    $sql = 'SELECT 
    p.ProductID, 
    p.Product_Name, 
    p.ProductDescription, 
    p.Stock, 
    p.ProductPrice, 
    p.ImageFile
FROM 
    product p
JOIN 
    product_category pc ON p.ProductID = pc.ProductID
WHERE 
    pc.CategoryID = ?';

    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $categoryID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getorderHistory($dbc, $userId)
{
    $query = 'SELECT 
    oh.OrderNumber,
    oh.Date,
    oh.Status,
    od.ProductID,
    p.Product_Name,
    od.PurchasedQuantity,
    od.ProductPrice
FROM 
    order_history oh
JOIN 
    order_details od ON oh.OrderNumber = od.OrderNumber
JOIN 
    product p ON od.ProductID = p.ProductID
WHERE 
    oh.UserID = ?
ORDER BY 
    oh.Date DESC';

    $stmt = $dbc->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
