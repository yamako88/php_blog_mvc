#SQL

CREATE DATABASE php_blog;

mysql> create table submission_form(

    -> id int(10) unsigned not null auto_increment,
    -> title varchar(64) not null,
    -> text varchar(128) not null,
    -> date datetime not null default current_timestamp on update current_timestamp,
    -> category_id int(10) unsigned not null,
    -> user_id int(10) unsigned not null,
    -> primary key (id)
    -> );

mysql> create table users(

    -> id int(10) unsigned not null auto_increment,
    -> name varchar(64) not null,
    -> email varchar(255) not null,
    -> password varchar(100) not null,
    -> primary key (id)
    -> );

mysql> create table category(

    -> category_id int(10) unsigned not null auto_increment,
    -> category_name varchar(64) not null,
    -> user_id_category int(10) unsigned not null,
    -> primary key (id)
    -> );

mysql> create table tag(

    -> id int(10) unsigned not null auto_increment,
    -> tag_name varchar(64) not null,
    -> user_id int(10) unsigned not null,
    -> primary key (id)
    -> );

mysql> create table form_tag(

    -> id int(10) unsigned not null auto_increment,
    -> form_id int(10) not null,
    -> tag_id int(10) not null,
    -> primary key (id)
    -> );
