create database candycraze_db;

create user 'candy_user'@'localhost' identified by 'candy';

grant all privileges on candycraze_db.* to 'candy_user'@'localhost'; 
	